<?php

namespace Tests\Models;

use Tests\TestCase;
use App\Models\Livro;
use App\Models\Autor;
use App\Models\Assunto;

class LivroTest extends TestCase
{
    private Livro $livroModel;
    private Autor $autorModel;
    private Assunto $assuntoModel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->livroModel = new Livro();
        $this->autorModel = new Autor();
        $this->assuntoModel = new Assunto();
    }

    public function testCreateLivro(): void
    {
        $data = [
            'Titulo' => 'Teste de Livro',
            'Editora' => 'Editora Teste',
            'Edicao' => 1,
            'AnoPublicacao' => '2024',
            'Valor' => 50.00
        ];

        $id = $this->livroModel->create($data);
        
        $this->assertIsInt($id);
        $this->assertGreaterThan(0, $id);

        $livro = $this->livroModel->find($id);
        $this->assertEquals('Teste de Livro', $livro['Titulo']);
        $this->assertEquals(50.00, (float)$livro['Valor']);
    }

    public function testUpdateLivro(): void
    {
        // Criar livro primeiro
        $data = [
            'Titulo' => 'Livro Original',
            'Editora' => 'Editora Teste',
            'Edicao' => 1,
            'AnoPublicacao' => '2024',
            'Valor' => 50.00
        ];
        $id = $this->livroModel->create($data);

        // Atualizar
        $updateData = [
            'Titulo' => 'Livro Atualizado',
            'Valor' => 75.50
        ];
        $result = $this->livroModel->update($id, $updateData);

        $this->assertTrue($result);

        $livro = $this->livroModel->find($id);
        $this->assertEquals('Livro Atualizado', $livro['Titulo']);
    }

    public function testDeleteLivro(): void
    {
        // Criar livro primeiro
        $data = [
            'Titulo' => 'Livro para Excluir',
            'Editora' => 'Editora Teste',
            'Edicao' => 1,
            'AnoPublicacao' => '2024',
            'Valor' => 50.00
        ];
        $id = $this->livroModel->create($data);

        // Excluir
        $result = $this->livroModel->delete($id);
        $this->assertTrue($result);

        // Verificar se foi excluído
        $livro = $this->livroModel->find($id);
        $this->assertNull($livro);
    }

    public function testSetAutores(): void
    {
        // Criar autor e livro
        $autorId = $this->autorModel->create(['Nome' => 'Autor Teste']);
        $livroId = $this->livroModel->create([
            'Titulo' => 'Livro com Autor',
            'Editora' => 'Editora Teste',
            'Edicao' => 1,
            'AnoPublicacao' => '2024',
            'Valor' => 50.00
        ]);

        // Associar autor
        $this->livroModel->setAutores($livroId, [$autorId]);
        
        $autores = $this->livroModel->getAutores($livroId);
        $this->assertCount(1, $autores);
        $this->assertEquals('Autor Teste', $autores[0]['Nome']);
    }
}

