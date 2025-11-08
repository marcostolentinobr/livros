<?php

namespace Tests\Controllers;

use Tests\TestCase;
use App\Controllers\AutorController;
use ReflectionMethod;

/**
 * Testes para a classe base BaseController
 * Verifica funcionalidades comuns compartilhadas por todos os controllers
 */
class BaseControllerTest extends TestCase
{
    private AutorController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new AutorController();
    }

    /**
     * Testa se o método getModel retorna uma instância do model correto
     */
    public function testGetModel(): void
    {
        $method = new ReflectionMethod($this->controller, 'getModel');
        $method->setAccessible(true);
        $model = $method->invoke($this->controller);
        
        $this->assertInstanceOf(\App\Models\Autor::class, $model);
    }

    /**
     * Testa se o model é cacheado (mesma instância em múltiplas chamadas)
     */
    public function testGetModelIsCached(): void
    {
        $method = new ReflectionMethod($this->controller, 'getModel');
        $method->setAccessible(true);
        
        $model1 = $method->invoke($this->controller);
        $model2 = $method->invoke($this->controller);
        
        $this->assertSame($model1, $model2);
    }

    /**
     * Testa se o método getEntityName retorna o nome correto da entidade
     */
    public function testGetEntityName(): void
    {
        $method = new ReflectionMethod($this->controller, 'getEntityName');
        $method->setAccessible(true);
        $name = $method->invoke($this->controller);
        
        $this->assertEquals('Autor', $name);
    }

    /**
     * Testa se o método getPluralName retorna o nome no plural correto
     */
    public function testGetPluralName(): void
    {
        $method = new ReflectionMethod($this->controller, 'getPluralName');
        $method->setAccessible(true);
        $plural = $method->invoke($this->controller);
        
        $this->assertEquals('autores', $plural);
    }
}

