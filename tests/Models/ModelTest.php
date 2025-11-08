<?php

namespace Tests\Models;

use Tests\TestCase;
use App\Models\Autor;

class ModelTest extends TestCase
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
        $id = $this->model->create(['Nome' => 'Novo Autor']);
        $this->assertGreaterThan(0, $id);
    }

    public function testUpdate(): void
    {
        $id = $this->model->create(['Nome' => 'Original']);
        $result = $this->model->update($id, ['Nome' => 'Atualizado']);
        
        $this->assertTrue($result);
        $autor = $this->model->find($id);
        $this->assertEquals('Atualizado', $autor['Nome']);
    }

    public function testDelete(): void
    {
        $id = $this->model->create(['Nome' => 'Para Excluir']);
        $result = $this->model->delete($id);
        
        $this->assertTrue($result);
        $this->assertNull($this->model->find($id));
    }

    public function testDeleteReturnsFalseWhenNotFound(): void
    {
        $result = $this->model->delete(99999);
        $this->assertFalse($result);
    }
}

