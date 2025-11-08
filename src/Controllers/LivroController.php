<?php

namespace App\Controllers;

/** Controller para gerenciar livros */
class LivroController extends BaseController
{
    protected string $icon = 'bi-book';
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
            $data['livroAutores'] = $this->model->getAutores($codigoLivro);
            $data['livroAssuntos'] = $this->model->getAssuntos($codigoLivro);
        }

        $this->render('livro/livro_form', $data);
    }

    /** Retorna definição dos campos do formulário */
    protected function getFields(): array
    {
        return [
            ['titulo', 'Título', true, 40],
            ['editora', 'Editora', true, 40],
            ['ano_publicacao', 'Ano de Publicação', true, 4]
        ];
    }

    /** Prepara e valida dados do formulário */
    protected function prepareData(): array
    {
        $data = $this->validateFields($this->getFields());

        // Campos opcionais com valores padrão
        $data['Edicao'] = (int)($_POST['edicao'] ?? 1);
        $data['Valor'] = $this->formatCurrencyToDb($_POST['valor'] ?? '0');

        return $data;
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
        return (float)$value;
    }
}
