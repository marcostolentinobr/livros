<?php

namespace Tests\Models;

use Tests\TestCase;
use App\Models\Assunto;

class AssuntoTest extends TestCase
{
    private Assunto $assuntoModel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->assuntoModel = new Assunto();
    }

    public function testCreateAssunto(): void
    {
        $data = ['Descricao' => 'Romance'];
        $id = $this->assuntoModel->create($data);

        $this->assertIsInt($id);
        $this->assertGreaterThan(0, $id);

        $assunto = $this->assuntoModel->find($id);
        $this->assertEquals('Romance', $assunto['Descricao']);
    }

    public function testFindAllAssuntos(): void
    {
        $this->assuntoModel->create(['Descricao' => 'Ficção']);
        $this->assuntoModel->create(['Descricao' => 'Drama']);

        $assuntos = $this->assuntoModel->findAll();
        $this->assertIsArray($assuntos);
        $this->assertGreaterThanOrEqual(2, count($assuntos));
    }

    public function testUpdateAssunto(): void
    {
        $id = $this->assuntoModel->create(['Descricao' => 'Original']);
        
        $result = $this->assuntoModel->update($id, ['Descricao' => 'Atualizado']);
        
        $this->assertTrue($result);
        
        $assunto = $this->assuntoModel->find($id);
        $this->assertEquals('Atualizado', $assunto['Descricao']);
    }

    public function testDeleteAssunto(): void
    {
        $id = $this->assuntoModel->create(['Descricao' => 'Para Excluir']);
        
        $result = $this->assuntoModel->delete($id);
        $this->assertTrue($result);
        
        $assunto = $this->assuntoModel->find($id);
        $this->assertNull($assunto);
    }
}

