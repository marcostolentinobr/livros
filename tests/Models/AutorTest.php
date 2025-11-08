<?php

namespace Tests\Models;

use Tests\TestCase;
use App\Models\Autor;

class AutorTest extends TestCase
{
    private Autor $autorModel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->autorModel = new Autor();
    }

    public function testCreateAutor(): void
    {
        $data = ['Nome' => 'Autor de Teste'];
        $id = $this->autorModel->create($data);

        $this->assertIsInt($id);
        $this->assertGreaterThan(0, $id);

        $autor = $this->autorModel->find($id);
        $this->assertEquals('Autor de Teste', $autor['Nome']);
    }

    public function testFindAllAutores(): void
    {
        // Criar alguns autores
        $this->autorModel->create(['Nome' => 'Autor 1']);
        $this->autorModel->create(['Nome' => 'Autor 2']);

        $autores = $this->autorModel->findAll();
        $this->assertIsArray($autores);
        $this->assertGreaterThanOrEqual(2, count($autores));
    }

    public function testUpdateAutor(): void
    {
        $id = $this->autorModel->create(['Nome' => 'Nome Original']);
        
        $result = $this->autorModel->update($id, ['Nome' => 'Nome Atualizado']);
        
        $this->assertTrue($result);
        
        $autor = $this->autorModel->find($id);
        $this->assertEquals('Nome Atualizado', $autor['Nome']);
    }

    public function testDeleteAutor(): void
    {
        $id = $this->autorModel->create(['Nome' => 'Autor para Excluir']);
        
        $result = $this->autorModel->delete($id);
        $this->assertTrue($result);
        
        $autor = $this->autorModel->find($id);
        $this->assertNull($autor);
    }
}

