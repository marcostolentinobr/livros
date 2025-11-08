<?php

namespace Tests\Controllers;

use Tests\TestCase;
use App\Controllers\AutorController;
use ReflectionMethod;

class AutorControllerTest extends TestCase
{
    private AutorController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new AutorController();
    }

    public function testPrepareData(): void
    {
        $_POST['nome'] = 'Autor Teste';
        
        $method = new ReflectionMethod($this->controller, 'prepareData');
        $method->setAccessible(true);
        $result = $method->invoke($this->controller);
        
        $this->assertEquals(['Nome' => 'Autor Teste'], $result);
    }

    public function testPrepareDataThrowsExceptionWhenEmpty(): void
    {
        $_POST['nome'] = '';
        
        $method = new ReflectionMethod($this->controller, 'prepareData');
        $method->setAccessible(true);
        
        $this->expectException(\RuntimeException::class);
        $method->invoke($this->controller);
    }

    public function testPrepareDataTrimsWhitespace(): void
    {
        $_POST['nome'] = '  Autor com Espaços  ';
        
        $method = new ReflectionMethod($this->controller, 'prepareData');
        $method->setAccessible(true);
        $result = $method->invoke($this->controller);
        
        $this->assertEquals(['Nome' => 'Autor com Espaços'], $result);
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

