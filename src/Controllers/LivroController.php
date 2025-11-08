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
            ['ano_publicacao', 'Ano de Publicação', true, 4, null, 'year'],
            ['edicao', 'Edição', true, 4, 4, 'number'],
            ['valor', 'Valor (R$)', true, null, '0', 'currency'],
            ['autores', 'Autores', true, null, (new \App\Models\Autor())->findAll(), 'select-multiple'],
            ['assuntos', 'Assuntos', true, null, (new \App\Models\Assunto())->findAll(), 'select-multiple']
        ];
    }

    /** Prepara e valida dados do formulário */
    protected function prepareData(): array
    {
        $data = $this->validateFields($this->getFields());
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
}
