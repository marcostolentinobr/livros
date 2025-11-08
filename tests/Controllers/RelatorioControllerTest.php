<?php

namespace Tests\Controllers;

use Tests\TestCase;
use App\Controllers\RelatorioController;

class RelatorioControllerTest extends TestCase
{
    private RelatorioController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new RelatorioController();
    }

    public function testControllerCanBeInstantiated(): void
    {
        $this->assertInstanceOf(RelatorioController::class, $this->controller);
    }
}

