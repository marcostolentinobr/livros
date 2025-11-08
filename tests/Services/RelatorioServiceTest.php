<?php

namespace Tests\Services;

use Tests\TestCase;
use App\Services\RelatorioService;

class RelatorioServiceTest extends TestCase
{
    private RelatorioService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new RelatorioService();
    }

    public function testGetLivrosPorAutor(): void
    {
        $result = $this->service->getLivrosPorAutor();
        $this->assertIsArray($result);
    }

    public function testGetLivrosPorAutorReturnsData(): void
    {
        $result = $this->service->getLivrosPorAutor();
        if (!empty($result)) {
            $this->assertArrayHasKey('NomeAutor', $result[0]);
            $this->assertArrayHasKey('Titulo', $result[0]);
        }
    }
}

