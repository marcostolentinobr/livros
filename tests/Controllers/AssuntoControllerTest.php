<?php

namespace Tests\Controllers;

use Tests\TestCase;
use App\Controllers\AssuntoController;
use ReflectionMethod;

class AssuntoControllerTest extends TestCase
{
    private AssuntoController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new AssuntoController();
    }

    public function testPrepareData(): void
    {
        $_POST['descricao'] = 'Assunto Teste';
        
        $method = new ReflectionMethod($this->controller, 'prepareData');
        $method->setAccessible(true);
        $result = $method->invoke($this->controller);
        
        $this->assertEquals(['Descricao' => 'Assunto Teste'], $result);
    }

    public function testPrepareDataThrowsExceptionWhenEmpty(): void
    {
        $_POST['descricao'] = '';
        
        $method = new ReflectionMethod($this->controller, 'prepareData');
        $method->setAccessible(true);
        
        $this->expectException(\RuntimeException::class);
        $method->invoke($this->controller);
    }

    public function testGetFields(): void
    {
        $method = new ReflectionMethod($this->controller, 'getFields');
        $method->setAccessible(true);
        $result = $method->invoke($this->controller);
        
        $this->assertIsArray($result);
        $this->assertCount(1, $result);
    }
}

