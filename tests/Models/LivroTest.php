<?php

namespace Tests\Models;

use Tests\TestCase;
use App\Models\Livro;
use App\Models\Autor;
use App\Models\Assunto;

/** Testes para a classe Livro */
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

    /** Testa método find */
    public function testFind(): void
    {
        $id = $this->livroModel->create([
            'Titulo' => 'Livro para Find',
            'Editora' => 'Editora Teste',
            'Edicao' => 1,
            'AnoPublicacao' => '2024',
            'Valor' => 50.00
        ]);
        
        $livro = $this->livroModel->find($id);
        $this->assertIsArray($livro);
        $this->assertEquals($id, $livro['Codl']);
    }

    /** Testa método find retorna null quando registro não existe */
    public function testFindReturnsNullWhenNotFound(): void
    {
        $livro = $this->livroModel->find(99999);
        $this->assertNull($livro);
    }

    /** Testa criação de livro */
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

    /** Testa atualização de livro */
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

    /** Testa exclusão de livro */
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

    /** Testa associação e busca de autores com livro */
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
        $this->assertEquals($autorId, $autores[0]);
    }

    /** Testa busca de assuntos de um livro */
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
        $this->assertEquals($assuntoId, $assuntos[0]);
    }

    /** Testa associação de assuntos com livro */
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

    /** Testa busca de todos os livros */
    public function testFindAll(): void
    {
        $this->livroModel->create([
            'Titulo' => 'Livro 1',
            'Editora' => 'Editora Teste',
            'Edicao' => 1,
            'AnoPublicacao' => '2024',
            'Valor' => 50.00
        ]);
        $this->livroModel->create([
            'Titulo' => 'Livro 2',
            'Editora' => 'Editora Teste',
            'Edicao' => 1,
            'AnoPublicacao' => '2024',
            'Valor' => 50.00
        ]);
        
        $livros = $this->livroModel->findAll();
        $this->assertGreaterThanOrEqual(2, count($livros));
    }

    /** Testa busca de livros com relações */
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

    /** Testa associação de múltiplos autores com livro */
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

    /** Testa associação de múltiplos assuntos com livro */
    public function testSetAssuntosWithMultiple(): void
    {
        $assunto1 = $this->assuntoModel->create(['Descricao' => 'Assunto 1']);
        $assunto2 = $this->assuntoModel->create(['Descricao' => 'Assunto 2']);
        $livroId = $this->livroModel->create([
            'Titulo' => 'Livro Múltiplos Assuntos',
            'Editora' => 'Editora Teste',
            'Edicao' => 1,
            'AnoPublicacao' => '2024',
            'Valor' => 50.00
        ]);
        
        $this->livroModel->setAssuntos($livroId, [$assunto1, $assunto2]);
        
        $assuntos = $this->livroModel->getAssuntos($livroId);
        $this->assertGreaterThanOrEqual(2, count($assuntos));
    }
}
