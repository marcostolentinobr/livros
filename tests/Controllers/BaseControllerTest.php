<?php

namespace Tests\Controllers;

use Tests\TestCase;
use App\Controllers\AutorController;
use ReflectionClass;

/** Testes para a classe base BaseController */
class BaseControllerTest extends TestCase
{
    private AutorController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new AutorController();
    }

    /** Testa se o model é carregado automaticamente */
    public function testModelIsLoaded(): void
    {
        $reflection = new ReflectionClass($this->controller);
        $modelProperty = $reflection->getProperty('model');
        $modelProperty->setAccessible(true);
        $model = $modelProperty->getValue($this->controller);
        
        $this->assertInstanceOf(\App\Models\Autor::class, $model);
    }

    /** Testa se o entityName é definido corretamente */
    public function testEntityName(): void
    {
        $reflection = new ReflectionClass($this->controller);
        $entityProperty = $reflection->getProperty('entityName');
        $entityProperty->setAccessible(true);
        $name = $entityProperty->getValue($this->controller);
        
        $this->assertEquals('Autor', $name);
    }

    /** Testa se o pluralName é definido corretamente */
    public function testPluralName(): void
    {
        $reflection = new ReflectionClass($this->controller);
        $pluralProperty = $reflection->getProperty('pluralName');
        $pluralProperty->setAccessible(true);
        $plural = $pluralProperty->getValue($this->controller);
        
        $this->assertEquals('autores', $plural);
    }
}

