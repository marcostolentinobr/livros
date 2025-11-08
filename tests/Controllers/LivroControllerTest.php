<?php

namespace Tests\Controllers;

use Tests\TestCase;
use App\Controllers\LivroController;
use App\Models\Autor;
use App\Models\Assunto;
use ReflectionMethod;

class LivroControllerTest extends TestCase
{
    private LivroController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new LivroController();
    }

    public function testPrepareData(): void
    {
        $_POST['titulo'] = 'Livro Teste';
        $_POST['editora'] = 'Editora Teste';
        $_POST['ano_publicacao'] = '2024';
        $_POST['edicao'] = '1';
        $_POST['valor'] = 'R$ 50,00';
        
        $method = new ReflectionMethod($this->controller, 'prepareData');
        $method->setAccessible(true);
        $result = $method->invoke($this->controller);
        
        $this->assertEquals('Livro Teste', $result['Titulo']);
        $this->assertEquals('Editora Teste', $result['Editora']);
        $this->assertEquals('2024', $result['AnoPublicacao']);
        $this->assertEquals(1, $result['Edicao']);
        $this->assertEquals(50.0, $result['Valor']);
    }

    public function testPrepareDataThrowsExceptionWhenTituloEmpty(): void
    {
        $_POST['titulo'] = '';
        $_POST['editora'] = 'Editora';
        $_POST['ano_publicacao'] = '2024';
        
        $method = new ReflectionMethod($this->controller, 'prepareData');
        $method->setAccessible(true);
        
        $this->expectException(\RuntimeException::class);
        $method->invoke($this->controller);
    }

    public function testPrepareDataThrowsExceptionWhenEditoraEmpty(): void
    {
        $_POST['titulo'] = 'Título';
        $_POST['editora'] = '';
        $_POST['ano_publicacao'] = '2024';
        
        $method = new ReflectionMethod($this->controller, 'prepareData');
        $method->setAccessible(true);
        
        $this->expectException(\RuntimeException::class);
        $method->invoke($this->controller);
    }

    public function testPrepareDataThrowsExceptionWhenAnoEmpty(): void
    {
        $_POST['titulo'] = 'Título';
        $_POST['editora'] = 'Editora';
        $_POST['ano_publicacao'] = '';
        
        $method = new ReflectionMethod($this->controller, 'prepareData');
        $method->setAccessible(true);
        
        $this->expectException(\RuntimeException::class);
        $method->invoke($this->controller);
    }

    public function testFormatCurrencyToDb(): void
    {
        $_POST['titulo'] = 'Título';
        $_POST['editora'] = 'Editora';
        $_POST['ano_publicacao'] = '2024';
        $_POST['valor'] = 'R$ 1.234,56';
        
        $method = new ReflectionMethod($this->controller, 'prepareData');
        $method->setAccessible(true);
        $result = $method->invoke($this->controller);
        
        $this->assertEquals(1234.56, $result['Valor']);
    }

    public function testAfterSave(): void
    {
        $autorModel = new Autor();
        $assuntoModel = new Assunto();
        $livroModel = new \App\Models\Livro();
        
        $autorId = $autorModel->create(['Nome' => 'Autor Teste']);
        $assuntoId = $assuntoModel->create(['Descricao' => 'Assunto Teste']);
        $livroId = $livroModel->create([
            'Titulo' => 'Livro Teste',
            'Editora' => 'Editora',
            'Edicao' => 1,
            'AnoPublicacao' => '2024',
            'Valor' => 50.00
        ]);
        
        $_POST['autores'] = [$autorId];
        $_POST['assuntos'] = [$assuntoId];
        
        $method = new ReflectionMethod($this->controller, 'afterSave');
        $method->setAccessible(true);
        $method->invoke($this->controller, $livroId);
        
        $autores = $livroModel->getAutores($livroId);
        $assuntos = $livroModel->getAssuntos($livroId);
        
        $this->assertCount(1, $autores);
        $this->assertCount(1, $assuntos);
    }

    public function testGetFields(): void
    {
        $method = new ReflectionMethod($this->controller, 'getFields');
        $method->setAccessible(true);
        $result = $method->invoke($this->controller);
        
        $this->assertIsArray($result);
        $this->assertCount(3, $result);
    }
}

