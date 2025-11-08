<?php

namespace Tests\Models;

use Tests\TestCase;
use App\Models\Autor;

/** Testes para a classe Autor */
class AutorTest extends TestCase
{
    private Autor $model;

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new Autor();
    }

    /** Testa criação de autor */
    public function testCreate(): void
    {
        $id = $this->model->create(['Nome' => 'Autor de Teste']);
        $this->assertGreaterThan(0, $id);
        
        $autor = $this->model->find($id);
        $this->assertEquals('Autor de Teste', $autor['Nome']);
    }

    /** Testa busca de todos os autores */
    public function testFindAll(): void
    {
        $this->model->create(['Nome' => 'Autor 1']);
        $this->model->create(['Nome' => 'Autor 2']);
        
        $autores = $this->model->findAll();
        $this->assertGreaterThanOrEqual(2, count($autores));
    }

    /** Testa atualização de autor */
    public function testUpdate(): void
    {
        $id = $this->model->create(['Nome' => 'Original']);
        $this->model->update($id, ['Nome' => 'Atualizado']);
        
        $autor = $this->model->find($id);
        $this->assertEquals('Atualizado', $autor['Nome']);
    }

    /** Testa exclusão de autor */
    public function testDelete(): void
    {
        $id = $this->model->create(['Nome' => 'Para Excluir']);
        $this->model->delete($id);
        
        $this->assertNull($this->model->find($id));
    }

    /** Testa associação de autor com livro */
    public function testGetAutoresFromLivro(): void
    {
        $autorId = $this->model->create(['Nome' => 'Autor para Livro']);
        $livroModel = new \App\Models\Livro();
        $livroId = $livroModel->create([
            'Titulo' => 'Livro Teste',
            'Editora' => 'Editora',
            'Edicao' => 1,
            'AnoPublicacao' => '2024',
            'Valor' => 50.00
        ]);
        
        $livroModel->setAutores($livroId, [$autorId]);
        
        $autores = $livroModel->getAutores($livroId);
        $this->assertIsArray($autores);
        $this->assertGreaterThanOrEqual(1, count($autores));
        $this->assertContains($autorId, $autores);
    }
}
