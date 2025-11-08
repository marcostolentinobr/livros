<?php

namespace App\Controllers;

/** Controller para gerenciar livros */
class LivroController extends BaseController
{
    protected string $icon = 'bi-book';
    protected bool $showPrimaryKey = true;

    /** Retorna definição dos campos do formulário */
    protected function getFields(): array
    {
        return [
            ['titulo', 'Título', true, 40],
            ['editora', 'Editora', true, 40],
            ['edicao', 'Edição', true, 4, null, 'number'],
            ['ano_publicacao', 'Ano', true, 4, null, 'year'],
            ['valor', 'Valor', true, null, '0', 'currency'],
            ['autores', 'Autores', true, null, (new \App\Models\Autor())->findAll(), 'select-multiple'],
            ['assuntos', 'Assuntos', true, null, (new \App\Models\Assunto())->findAll(), 'select-multiple']
        ];
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
