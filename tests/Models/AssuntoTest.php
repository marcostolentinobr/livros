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

    public function testFind(): void
    {
        $id = $this->model->create(['Descricao' => 'Assunto para Find']);
        $assunto = $this->model->find($id);
        
        $this->assertIsArray($assunto);
        $this->assertEquals($id, $assunto['codAs']);
    }

    public function testFindReturnsNullWhenNotFound(): void
    {
        $assunto = $this->model->find(99999);
        $this->assertNull($assunto);
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
}
