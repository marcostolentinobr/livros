<?php

namespace Tests\Controllers;

use Tests\TestCase;
use App\Controllers\AutorController;
use ReflectionMethod;

/**
 * Testes para o AutorController
 * Verifica o processamento de dados e validações
 */
class AutorControllerTest extends TestCase
{
    private AutorController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new AutorController();
    }

    /**
     * Testa se o prepareData converte corretamente os dados do POST
     */
    public function testPrepareData(): void
    {
        $_POST['nome'] = 'Autor Teste';
        
        $method = new ReflectionMethod($this->controller, 'prepareData');
        $method->setAccessible(true);
        $result = $method->invoke($this->controller);
        
        $this->assertEquals(['Nome' => 'Autor Teste'], $result);
    }

    /**
     * Testa se o prepareData lança exceção quando o nome está vazio
     */
    public function testPrepareDataThrowsExceptionWhenEmpty(): void
    {
        $_POST['nome'] = '';
        
        $method = new ReflectionMethod($this->controller, 'prepareData');
        $method->setAccessible(true);
        
        $this->expectException(\RuntimeException::class);
        $method->invoke($this->controller);
    }

    /**
     * Testa se o prepareData remove espaços em branco do início e fim
     */
    public function testPrepareDataTrimsWhitespace(): void
    {
        $_POST['nome'] = '  Autor com Espaços  ';
        
        $method = new ReflectionMethod($this->controller, 'prepareData');
        $method->setAccessible(true);
        $result = $method->invoke($this->controller);
        
        $this->assertEquals(['Nome' => 'Autor com Espaços'], $result);
    }
}

