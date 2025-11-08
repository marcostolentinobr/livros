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

    public function testFind(): void
    {
        $id = $this->model->create(['Nome' => 'Autor para Find']);
        $autor = $this->model->find($id);
        
        $this->assertIsArray($autor);
        $this->assertEquals($id, $autor['CodAu']);
    }

    public function testFindReturnsNullWhenNotFound(): void
    {
        $autor = $this->model->find(99999);
        $this->assertNull($autor);
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
}
