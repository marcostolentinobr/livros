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
            $this->assertArrayHasKey('CodAutor', $result[0]);
            $this->assertArrayHasKey('Titulo', $result[0]);
            $this->assertArrayHasKey('Editora', $result[0]);
            $this->assertArrayHasKey('Edicao', $result[0]);
            $this->assertArrayHasKey('AnoPublicacao', $result[0]);
            $this->assertArrayHasKey('Valor', $result[0]);
            $this->assertArrayHasKey('CodLivro', $result[0]);
            $this->assertArrayHasKey('Assuntos', $result[0]);
            $this->assertArrayHasKey('OutrosAutores', $result[0]);
        }
    }

    public function testGetLivrosPorAutorIsOrdered(): void
    {
        $result = $this->service->getLivrosPorAutor();
        
        if (count($result) > 1) {
            $autorAnterior = '';
            foreach ($result as $row) {
                if ($autorAnterior !== '' && $row['NomeAutor'] !== $autorAnterior) {
                    $this->assertGreaterThanOrEqual($autorAnterior, $row['NomeAutor']);
                }
                $autorAnterior = $row['NomeAutor'];
            }
        }
    }
}

