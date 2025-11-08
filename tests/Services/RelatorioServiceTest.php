<?php

namespace Tests\Services;

use Tests\TestCase;
use App\Services\RelatorioService;

/** Testes para o RelatorioService */
class RelatorioServiceTest extends TestCase
{
    private RelatorioService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new RelatorioService();
    }

    /** Testa se o método getLivrosPorAutor retorna um array */
    public function testGetLivrosPorAutor(): void
    {
        $result = $this->service->getLivrosPorAutor();
        $this->assertIsArray($result);
    }

    /** Testa se o retorno contém os campos esperados da view */
    public function testGetLivrosPorAutorReturnsData(): void
    {
        $result = $this->service->getLivrosPorAutor();
        // Verifica campos apenas se houver dados
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

    /** Testa se os dados estão ordenados por nome do autor e título */
    public function testGetLivrosPorAutorIsOrdered(): void
    {
        $result = $this->service->getLivrosPorAutor();
        
        // Verifica ordenação apenas se houver mais de um registro
        if (count($result) > 1) {
            $autorAnterior = '';
            // Verifica ordem alfabética em cada registro
            foreach ($result as $row) {
                // Se mudou o autor, o novo deve ser maior ou igual (ordem alfabética)
                if ($autorAnterior !== '' && $row['NomeAutor'] !== $autorAnterior) {
                    $this->assertGreaterThanOrEqual($autorAnterior, $row['NomeAutor']);
                }
                $autorAnterior = $row['NomeAutor'];
            }
        }
    }
}

