<?php

namespace Tests\Models;

use Tests\TestCase;
use App\Models\Autor;

/** Testes para a classe base Model */
class ModelTest extends TestCase
{
    private Autor $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new Autor();
    }

    /** Testa se o método find retorna um registro existente */
    public function testFind(): void
    {
        $id = $this->model->create(['Nome' => 'Autor para Find']);
        $autor = $this->model->find($id);
        
        $this->assertIsArray($autor);
        $this->assertEquals($id, $autor['CodAu']);
    }

    /** Testa se o método find retorna null quando o registro não existe */
    public function testFindReturnsNullWhenNotFound(): void
    {
        $autor = $this->model->find(99999);
        $this->assertNull($autor);
    }

    /** Testa se o método create cria um novo registro e retorna o ID */
    public function testCreate(): void
    {
        $id = $this->model->create(['Nome' => 'Novo Autor']);
        $this->assertGreaterThan(0, $id);
    }

    /** Testa se o método update atualiza um registro existente */
    public function testUpdate(): void
    {
        $id = $this->model->create(['Nome' => 'Original']);
        $result = $this->model->update($id, ['Nome' => 'Atualizado']);
        
        $this->assertTrue($result);
        $autor = $this->model->find($id);
        $this->assertEquals('Atualizado', $autor['Nome']);
    }

    /** Testa se o método delete remove um registro existente */
    public function testDelete(): void
    {
        $id = $this->model->create(['Nome' => 'Para Excluir']);
        $result = $this->model->delete($id);
        
        $this->assertTrue($result);
        $this->assertNull($this->model->find($id));
    }

    /** Testa se o método delete retorna false quando o registro não existe */
    public function testDeleteReturnsFalseWhenNotFound(): void
    {
        $result = $this->model->delete(99999);
        $this->assertFalse($result);
    }
}

