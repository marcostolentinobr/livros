<?php

namespace Tests\Models;

use Tests\TestCase;
use App\Models\Autor;

class AutorTest extends TestCase
{
    private Autor $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new Autor();
    }

    public function testCreate(): void
    {
        $id = $this->model->create(['Nome' => 'Autor de Teste']);
        $this->assertGreaterThan(0, $id);
        
        $autor = $this->model->find($id);
        $this->assertEquals('Autor de Teste', $autor['Nome']);
    }

    public function testFindAll(): void
    {
        $this->model->create(['Nome' => 'Autor 1']);
        $this->model->create(['Nome' => 'Autor 2']);
        
        $autores = $this->model->findAll();
        $this->assertGreaterThanOrEqual(2, count($autores));
    }

    public function testUpdate(): void
    {
        $id = $this->model->create(['Nome' => 'Original']);
        $this->model->update($id, ['Nome' => 'Atualizado']);
        
        $autor = $this->model->find($id);
        $this->assertEquals('Atualizado', $autor['Nome']);
    }

    public function testDelete(): void
    {
        $id = $this->model->create(['Nome' => 'Para Excluir']);
        $this->model->delete($id);
        
        $this->assertNull($this->model->find($id));
    }

    public function testFindByLivro(): void
    {
        $autorId = $this->model->create(['Nome' => 'Autor para Livro']);
        $livroModel = new \App\Models\Livro();
        $livroId = $livroModel->create([
            'Titulo' => 'Livro Teste',
            'Editora' => 'Editora',
            'Edicao' => 1,
            'AnoPublicacao' => '2024',
            'Valor' => 50.00
        ]);
        
        $livroModel->setAutores($livroId, [$autorId]);
        
        $autores = $this->model->findByLivro($livroId);
        $this->assertIsArray($autores);
        $this->assertGreaterThanOrEqual(1, count($autores));
    }
}
