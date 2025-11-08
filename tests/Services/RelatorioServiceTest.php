<?php

namespace Tests\Services;

use Tests\TestCase;
use App\Services\RelatorioService;

/**
 * Testes para o RelatorioService
 * Verifica a geração de relatórios de livros agrupados por autor
 */
class RelatorioServiceTest extends TestCase
{
    private RelatorioService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new RelatorioService();
    }

    /**
     * Testa se o método getLivrosPorAutor retorna um array
     */
    public function testGetLivrosPorAutor(): void
    {
        $result = $this->service->getLivrosPorAutor();
        $this->assertIsArray($result);
    }

    /**
     * Testa se o retorno contém os campos esperados (NomeAutor e Titulo)
     */
    public function testGetLivrosPorAutorReturnsData(): void
    {
        $result = $this->service->getLivrosPorAutor();
        if (!empty($result)) {
            $this->assertArrayHasKey('NomeAutor', $result[0]);
            $this->assertArrayHasKey('Titulo', $result[0]);
        }
    }
}

