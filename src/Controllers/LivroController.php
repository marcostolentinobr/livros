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


    /** Retorna definição dos campos do formulário */
    protected function getFields(): array
    {
        return [
            ['titulo', 'Título', true, 40],
            ['editora', 'Editora', true, 40],
            ['ano_publicacao', 'Ano de Publicação', true, 4],
            ['autores', 'Autores', false, null, (new \App\Models\Autor())->findAll(), true],
            ['assuntos', 'Assuntos', false, null, (new \App\Models\Assunto())->findAll(), true]
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
        // Converte para array se vier como string (select único)
        $autores = $_POST['autores'] ?? [];
        $assuntos = $_POST['assuntos'] ?? [];
        if (!is_array($autores)) $autores = $autores ? [$autores] : [];
        if (!is_array($assuntos)) $assuntos = $assuntos ? [$assuntos] : [];
        
        $this->model->setAutores($id, $autores);
        $this->model->setAssuntos($id, $assuntos);
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
