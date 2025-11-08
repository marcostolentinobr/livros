<?php

namespace Tests\Controllers;

use Tests\TestCase;
use App\Controllers\RelatorioController;

/** Testes para o RelatorioController */
class RelatorioControllerTest extends TestCase
{
    private RelatorioController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new RelatorioController();
    }

    /** Testa se o controller pode ser instanciado */
    public function testControllerCanBeInstantiated(): void
    {
        $this->assertInstanceOf(RelatorioController::class, $this->controller);
    }
}

