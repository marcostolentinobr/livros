<?php

namespace App\Controllers;

/** Controller para gerenciar livros */
class LivroController extends BaseController
{
    /** Lista livros com relações */
    public function index(): void
    {
        $this->render('livro/livro_index', [
            'livros' => $this->model->findAllWithRelations()
        ]);
    }

    /** Renderiza formulário com autores e assuntos */
    protected function renderForm(?array $livro, string $action): void
    {
        $data = [
            'livro' => $livro,
            'autores' => (new \App\Models\Autor())->findAll(),
            'assuntos' => (new \App\Models\Assunto())->findAll(),
            'action' => $action
        ];

        // Carrega relações apenas se for edição
        if ($livro !== null) {
            $codigoLivro = $livro['Codl'];
            $data['livroAutores'] = array_column($this->model->getAutores($codigoLivro), 'CodAu');
            $data['livroAssuntos'] = array_column($this->model->getAssuntos($codigoLivro), 'codAs');
        }

        $this->render('livro/livro_form', $data);
    }

    /** Prepara e valida dados do formulário */
    protected function prepareData(): array
    {
        $titulo = trim($_POST['titulo'] ?? '');
        // Valida título obrigatório
        if (empty($titulo)) {
            throw new \RuntimeException("O título é obrigatório.", 400);
        }

        $editora = trim($_POST['editora'] ?? '');
        // Valida editora obrigatória
        if (empty($editora)) {
            throw new \RuntimeException("A editora é obrigatória.", 400);
        }

        $anoPublicacao = trim($_POST['ano_publicacao'] ?? '');
        // Valida ano de publicação obrigatório
        if (empty($anoPublicacao)) {
            throw new \RuntimeException("O ano de publicação é obrigatório.", 400);
        }

        return [
            'Titulo' => $titulo,
            'Editora' => $editora,
            'Edicao' => (int)($_POST['edicao'] ?? 1),
            'AnoPublicacao' => $anoPublicacao,
            'Valor' => $this->formatCurrencyToDb($_POST['valor'] ?? '0')
        ];
    }

    /** Associa autores e assuntos ao livro */
    protected function afterSave(int $id): void
    {
        $this->model->setAutores($id, $_POST['autores'] ?? []);
        $this->model->setAssuntos($id, $_POST['assuntos'] ?? []);
    }

    /** Converte valor monetário formatado para float */
    private function formatCurrencyToDb(string $value): float
    {
        // Remove símbolos e separadores de milhar, depois substitui vírgula por ponto
        $value = str_replace(['R$', ' ', '.'], '', trim($value));
        $value = str_replace(',', '.', $value);
        return (float) $value;
    }
}
