<?php

namespace Tests\Controllers;

use Tests\TestCase;
use App\Controllers\AssuntoController;
use ReflectionMethod;

/** Testes para o AssuntoController */
class AssuntoControllerTest extends TestCase
{
    private AssuntoController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new AssuntoController();
    }

    /** Testa se o prepareData converte corretamente os dados do POST */
    public function testPrepareData(): void
    {
        $_POST['descricao'] = 'Assunto Teste';
        
        $method = new ReflectionMethod($this->controller, 'prepareData');
        $method->setAccessible(true);
        $result = $method->invoke($this->controller);
        
        $this->assertEquals(['Descricao' => 'Assunto Teste'], $result);
    }

    /** Testa se o prepareData lança exceção quando a descrição está vazia */
    public function testPrepareDataThrowsExceptionWhenEmpty(): void
    {
        $_POST['descricao'] = '';
        
        $method = new ReflectionMethod($this->controller, 'prepareData');
        $method->setAccessible(true);
        
        $this->expectException(\RuntimeException::class);
        $method->invoke($this->controller);
    }
}

