<?php

namespace Tests\Controllers;

use Tests\TestCase;
use App\Controllers\AutorController;
use ReflectionMethod;
use ReflectionProperty;

class BaseControllerTest extends TestCase
{
    private AutorController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new AutorController();
    }

    public function testGetModel(): void
    {
        $method = new ReflectionMethod($this->controller, 'getModel');
        $method->setAccessible(true);
        $model = $method->invoke($this->controller);
        
        $this->assertInstanceOf(\App\Models\Autor::class, $model);
    }

    public function testGetModelIsCached(): void
    {
        $method = new ReflectionMethod($this->controller, 'getModel');
        $method->setAccessible(true);
        
        $model1 = $method->invoke($this->controller);
        $model2 = $method->invoke($this->controller);
        
        $this->assertSame($model1, $model2);
    }

    public function testGetEntityName(): void
    {
        $method = new ReflectionMethod($this->controller, 'getEntityName');
        $method->setAccessible(true);
        $name = $method->invoke($this->controller);
        
        $this->assertEquals('Autor', $name);
    }

    public function testGetPluralName(): void
    {
        $method = new ReflectionMethod($this->controller, 'getPluralName');
        $method->setAccessible(true);
        $plural = $method->invoke($this->controller);
        
        $this->assertEquals('autores', $plural);
    }
}

