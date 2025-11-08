<?php

namespace Tests\Controllers;

use Tests\TestCase;
use App\Controllers\AutorController;
use ReflectionClass;
use ReflectionMethod;

class BaseControllerTest extends TestCase
{
    private AutorController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new AutorController();
    }

    public function testModelIsLoaded(): void
    {
        $reflection = new ReflectionClass($this->controller);
        $modelProperty = $reflection->getProperty('model');
        $modelProperty->setAccessible(true);
        $model = $modelProperty->getValue($this->controller);
        
        $this->assertInstanceOf(\App\Models\Autor::class, $model);
    }

    public function testEntityName(): void
    {
        $reflection = new ReflectionClass($this->controller);
        $entityProperty = $reflection->getProperty('entityName');
        $entityProperty->setAccessible(true);
        $name = $entityProperty->getValue($this->controller);
        
        $this->assertEquals('Autor', $name);
    }

    public function testPluralName(): void
    {
        $reflection = new ReflectionClass($this->controller);
        $pluralProperty = $reflection->getProperty('pluralName');
        $pluralProperty->setAccessible(true);
        $plural = $pluralProperty->getValue($this->controller);
        
        $this->assertEquals('autores', $plural);
    }

    public function testValidateFields(): void
    {
        $_POST['nome'] = 'Teste';
        
        $method = new ReflectionMethod($this->controller, 'validateFields');
        $method->setAccessible(true);
        $result = $method->invoke($this->controller, [['nome', 'Nome', true, 40]]);
        
        $this->assertEquals(['Nome' => 'Teste'], $result);
    }

    public function testValidateFieldsThrowsExceptionWhenEmpty(): void
    {
        $_POST['nome'] = '';
        
        $method = new ReflectionMethod($this->controller, 'validateFields');
        $method->setAccessible(true);
        
        $this->expectException(\RuntimeException::class);
        $method->invoke($this->controller, [['nome', 'Nome', true, 40]]);
    }

    public function testValidateFieldsTruncatesMaxLength(): void
    {
        $_POST['nome'] = str_repeat('a', 50);
        
        $method = new ReflectionMethod($this->controller, 'validateFields');
        $method->setAccessible(true);
        $result = $method->invoke($this->controller, [['nome', 'Nome', false, 40]]);
        
        $this->assertEquals(40, mb_strlen($result['Nome']));
    }

    public function testValidateFieldsIgnoresPrimaryKey(): void
    {
        $_POST['CodAu'] = '123';
        $_POST['nome'] = 'Teste';
        
        $method = new ReflectionMethod($this->controller, 'validateFields');
        $method->setAccessible(true);
        $result = $method->invoke($this->controller, [['nome', 'Nome', true, 40]]);
        
        $this->assertArrayNotHasKey('CodAu', $result);
    }

    public function testGetFields(): void
    {
        $method = new ReflectionMethod($this->controller, 'getFields');
        $method->setAccessible(true);
        $result = $method->invoke($this->controller);
        
        $this->assertIsArray($result);
    }
}

