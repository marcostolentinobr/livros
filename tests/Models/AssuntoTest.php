<?php

namespace Tests\Models;

use Tests\TestCase;
use App\Models\Assunto;

class AssuntoTest extends TestCase
{
    private Assunto $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new Assunto();
    }

    public function testCreate(): void
    {
        $id = $this->model->create(['Descricao' => 'Romance']);
        $this->assertGreaterThan(0, $id);
        
        $assunto = $this->model->find($id);
        $this->assertEquals('Romance', $assunto['Descricao']);
    }

    public function testFindAll(): void
    {
        $this->model->create(['Descricao' => 'Ficção']);
        $this->model->create(['Descricao' => 'Drama']);
        
        $assuntos = $this->model->findAll();
        $this->assertGreaterThanOrEqual(2, count($assuntos));
    }

    public function testUpdate(): void
    {
        $id = $this->model->create(['Descricao' => 'Original']);
        $this->model->update($id, ['Descricao' => 'Atualizado']);
        
        $assunto = $this->model->find($id);
        $this->assertEquals('Atualizado', $assunto['Descricao']);
    }

    public function testDelete(): void
    {
        $id = $this->model->create(['Descricao' => 'Para Excluir']);
        $this->model->delete($id);
        
        $this->assertNull($this->model->find($id));
    }

    public function testGetAssuntosFromLivro(): void
    {
        $assuntoId = $this->model->create(['Descricao' => 'Assunto para Livro']);
        $livroModel = new \App\Models\Livro();
        $livroId = $livroModel->create([
            'Titulo' => 'Livro Teste',
            'Editora' => 'Editora',
            'Edicao' => 1,
            'AnoPublicacao' => '2024',
            'Valor' => 50.00
        ]);
        
        $livroModel->setAssuntos($livroId, [$assuntoId]);
        
        $assuntos = $livroModel->getAssuntos($livroId);
        $this->assertIsArray($assuntos);
        $this->assertGreaterThanOrEqual(1, count($assuntos));
        $this->assertEquals('Assunto para Livro', $assuntos[0]['Descricao']);
    }
}
