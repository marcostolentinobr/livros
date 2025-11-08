<?php

namespace Tests\Controllers;

use Tests\TestCase;
use App\Controllers\LivroController;
use App\Models\Autor;
use App\Models\Assunto;
use ReflectionMethod;

/**
 * Testes para o LivroController
 * Verifica o processamento de dados, validações e relacionamentos
 */
class LivroControllerTest extends TestCase
{
    private LivroController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new LivroController();
    }

    /**
     * Testa se o prepareData converte corretamente os dados do POST
     * Inclui conversão de moeda (R$ 50,00 -> 50.0)
     */
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

    /**
     * Testa se o prepareData lança exceção quando o título está vazio
     */
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

    /**
     * Testa se o prepareData lança exceção quando a editora está vazia
     */
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

    /**
     * Testa se o prepareData lança exceção quando o ano está vazio
     */
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

    /**
     * Testa se o prepareData converte corretamente valores monetários complexos
     * Exemplo: R$ 1.234,56 -> 1234.56
     */
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

    /**
     * Testa se o afterSave associa corretamente autores e assuntos ao livro
     */
    public function testAfterSave(): void
    {
        $autorModel = new Autor();
        $assuntoModel = new Assunto();
        $livroModel = new \App\Models\Livro();
        
        // Cria registros de teste
        $autorId = $autorModel->create(['Nome' => 'Autor Teste']);
        $assuntoId = $assuntoModel->create(['Descricao' => 'Assunto Teste']);
        $livroId = $livroModel->create([
            'Titulo' => 'Livro Teste',
            'Editora' => 'Editora',
            'Edicao' => 1,
            'AnoPublicacao' => '2024',
            'Valor' => 50.00
        ]);
        
        // Simula dados do POST
        $_POST['autores'] = [$autorId];
        $_POST['assuntos'] = [$assuntoId];
        
        // Executa o método afterSave
        $method = new ReflectionMethod($this->controller, 'afterSave');
        $method->setAccessible(true);
        $method->invoke($this->controller, $livroId);
        
        // Verifica se as associações foram criadas
        $autores = $livroModel->getAutores($livroId);
        $assuntos = $livroModel->getAssuntos($livroId);
        
        $this->assertCount(1, $autores);
        $this->assertCount(1, $assuntos);
    }
}

