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

    public function testCreate(): void
    {
        $id = $this->livroModel->create([
            'Titulo' => 'Teste de Livro',
            'Editora' => 'Editora Teste',
            'Edicao' => 1,
            'AnoPublicacao' => '2024',
            'Valor' => 50.00
        ]);
        
        $this->assertGreaterThan(0, $id);
        
        $livro = $this->livroModel->find($id);
        $this->assertEquals('Teste de Livro', $livro['Titulo']);
    }

    public function testUpdate(): void
    {
        $id = $this->livroModel->create([
            'Titulo' => 'Original',
            'Editora' => 'Editora Teste',
            'Edicao' => 1,
            'AnoPublicacao' => '2024',
            'Valor' => 50.00
        ]);
        
        $this->livroModel->update($id, ['Titulo' => 'Atualizado']);
        
        $livro = $this->livroModel->find($id);
        $this->assertEquals('Atualizado', $livro['Titulo']);
    }

    public function testDelete(): void
    {
        $id = $this->livroModel->create([
            'Titulo' => 'Para Excluir',
            'Editora' => 'Editora Teste',
            'Edicao' => 1,
            'AnoPublicacao' => '2024',
            'Valor' => 50.00
        ]);
        
        $this->livroModel->delete($id);
        $this->assertNull($this->livroModel->find($id));
    }

    public function testSetAutores(): void
    {
        $autorId = $this->autorModel->create(['Nome' => 'Autor Teste']);
        $livroId = $this->livroModel->create([
            'Titulo' => 'Livro com Autor',
            'Editora' => 'Editora Teste',
            'Edicao' => 1,
            'AnoPublicacao' => '2024',
            'Valor' => 50.00
        ]);
        
        $this->livroModel->setAutores($livroId, [$autorId]);
        
        $autores = $this->livroModel->getAutores($livroId);
        $this->assertCount(1, $autores);
        $this->assertEquals('Autor Teste', $autores[0]['Nome']);
    }

    public function testGetAssuntos(): void
    {
        $assuntoId = $this->assuntoModel->create(['Descricao' => 'Assunto Teste']);
        $livroId = $this->livroModel->create([
            'Titulo' => 'Livro com Assunto',
            'Editora' => 'Editora Teste',
            'Edicao' => 1,
            'AnoPublicacao' => '2024',
            'Valor' => 50.00
        ]);
        
        $this->livroModel->setAssuntos($livroId, [$assuntoId]);
        
        $assuntos = $this->livroModel->getAssuntos($livroId);
        $this->assertCount(1, $assuntos);
        $this->assertEquals('Assunto Teste', $assuntos[0]['Descricao']);
    }

    public function testSetAssuntos(): void
    {
        $assuntoId = $this->assuntoModel->create(['Descricao' => 'Assunto Teste']);
        $livroId = $this->livroModel->create([
            'Titulo' => 'Livro com Assunto',
            'Editora' => 'Editora Teste',
            'Edicao' => 1,
            'AnoPublicacao' => '2024',
            'Valor' => 50.00
        ]);
        
        $this->livroModel->setAssuntos($livroId, [$assuntoId]);
        
        $assuntos = $this->livroModel->getAssuntos($livroId);
        $this->assertCount(1, $assuntos);
    }

    public function testFindAllWithRelations(): void
    {
        $autorId = $this->autorModel->create(['Nome' => 'Autor Relação']);
        $assuntoId = $this->assuntoModel->create(['Descricao' => 'Assunto Relação']);
        $livroId = $this->livroModel->create([
            'Titulo' => 'Livro com Relações',
            'Editora' => 'Editora Teste',
            'Edicao' => 1,
            'AnoPublicacao' => '2024',
            'Valor' => 50.00
        ]);
        
        $this->livroModel->setAutores($livroId, [$autorId]);
        $this->livroModel->setAssuntos($livroId, [$assuntoId]);
        
        $livros = $this->livroModel->findAllWithRelations();
        $this->assertIsArray($livros);
        $this->assertGreaterThanOrEqual(1, count($livros));
        
        $livro = $livros[array_search($livroId, array_column($livros, 'Codl'))];
        $this->assertArrayHasKey('Autores', $livro);
        $this->assertArrayHasKey('Assuntos', $livro);
    }

    public function testSetAutoresWithMultiple(): void
    {
        $autor1 = $this->autorModel->create(['Nome' => 'Autor 1']);
        $autor2 = $this->autorModel->create(['Nome' => 'Autor 2']);
        $livroId = $this->livroModel->create([
            'Titulo' => 'Livro Múltiplos Autores',
            'Editora' => 'Editora Teste',
            'Edicao' => 1,
            'AnoPublicacao' => '2024',
            'Valor' => 50.00
        ]);
        
        $this->livroModel->setAutores($livroId, [$autor1, $autor2]);
        
        $autores = $this->livroModel->getAutores($livroId);
        $this->assertGreaterThanOrEqual(2, count($autores));
    }
}
