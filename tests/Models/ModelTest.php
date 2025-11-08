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

    /** Testa método find */
    public function testFind(): void
    {
        $id = $this->model->create(['Nome' => 'Autor para Find']);
        $autor = $this->model->find($id);
        
        $this->assertIsArray($autor);
        $this->assertEquals($id, $autor['CodAu']);
    }

    /** Testa método find retorna null quando registro não existe */
    public function testFindReturnsNullWhenNotFound(): void
    {
        $autor = $this->model->find(99999);
        $this->assertNull($autor);
    }

    /** Testa método create */
    public function testCreate(): void
    {
        $id = $this->model->create(['Nome' => 'Novo Autor']);
        $this->assertGreaterThan(0, $id);
    }

    /** Testa método update */
    public function testUpdate(): void
    {
        $id = $this->model->create(['Nome' => 'Original']);
        $result = $this->model->update($id, ['Nome' => 'Atualizado']);
        
        $this->assertTrue($result);
        $autor = $this->model->find($id);
        $this->assertEquals('Atualizado', $autor['Nome']);
    }

    /** Testa método delete */
    public function testDelete(): void
    {
        $id = $this->model->create(['Nome' => 'Para Excluir']);
        $result = $this->model->delete($id);
        
        $this->assertTrue($result);
        $this->assertNull($this->model->find($id));
    }

    /** Testa método delete retorna false quando registro não existe */
    public function testDeleteReturnsFalseWhenNotFound(): void
    {
        $result = $this->model->delete(99999);
        $this->assertFalse($result);
    }

    /** Testa busca de todos os registros */
    public function testFindAll(): void
    {
        $this->model->create(['Nome' => 'Autor 1']);
        $this->model->create(['Nome' => 'Autor 2']);
        
        $autores = $this->model->findAll();
        $this->assertIsArray($autores);
        $this->assertGreaterThanOrEqual(2, count($autores));
    }

    /** Testa método getRelacao */
    public function testGetRelacao(): void
    {
        $livroModel = new \App\Models\Livro();
        $autorId = $this->model->create(['Nome' => 'Autor Relação']);
        $livroId = $livroModel->create([
            'Titulo' => 'Livro Teste',
            'Editora' => 'Editora',
            'Edicao' => 1,
            'AnoPublicacao' => '2024',
            'Valor' => 50.00
        ]);
        
        $livroModel->setRelacao($livroId, [$autorId], 'Livro_Autor', 'Autor_CodAu');
        
        $autores = $livroModel->getRelacao($livroId, 'Livro_Autor', 'Autor_CodAu');
        $this->assertIsArray($autores);
        $this->assertContains($autorId, $autores);
    }

    /** Testa método setRelacao */
    public function testSetRelacao(): void
    {
        $livroModel = new \App\Models\Livro();
        $autorId = $this->model->create(['Nome' => 'Autor Relação']);
        $livroId = $livroModel->create([
            'Titulo' => 'Livro Teste',
            'Editora' => 'Editora',
            'Edicao' => 1,
            'AnoPublicacao' => '2024',
            'Valor' => 50.00
        ]);
        
        $livroModel->setRelacao($livroId, [$autorId], 'Livro_Autor', 'Autor_CodAu');
        
        $autores = $livroModel->getRelacao($livroId, 'Livro_Autor', 'Autor_CodAu');
        $this->assertCount(1, $autores);
        $this->assertEquals($autorId, $autores[0]);
    }

    /** Testa setRelacao com array vazio */
    public function testSetRelacaoWithEmptyArray(): void
    {
        $livroModel = new \App\Models\Livro();
        $autorId = $this->model->create(['Nome' => 'Autor Teste']);
        $livroId = $livroModel->create([
            'Titulo' => 'Livro Teste',
            'Editora' => 'Editora',
            'Edicao' => 1,
            'AnoPublicacao' => '2024',
            'Valor' => 50.00
        ]);
        
        $livroModel->setRelacao($livroId, [$autorId], 'Livro_Autor', 'Autor_CodAu');
        $livroModel->setRelacao($livroId, [], 'Livro_Autor', 'Autor_CodAu');
        
        $autores = $livroModel->getRelacao($livroId, 'Livro_Autor', 'Autor_CodAu');
        $this->assertCount(0, $autores);
    }
}

