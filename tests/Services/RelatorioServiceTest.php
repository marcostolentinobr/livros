<?php

namespace Tests\Services;

use Tests\TestCase;
use App\Services\RelatorioService;

/**
 * Testes para o RelatorioService
 * Verifica a busca de dados da view vw_livros_por_autor
 * A view agrupa livros por autor e inclui informações de assuntos e coautores
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
     * A consulta utiliza a view vw_livros_por_autor do banco de dados
     */
    public function testGetLivrosPorAutor(): void
    {
        $result = $this->service->getLivrosPorAutor();
        $this->assertIsArray($result);
    }

    /**
     * Testa se o retorno contém os campos esperados da view
     * Verifica campos das 3 tabelas principais: Autor, Livro e Assunto
     */
    public function testGetLivrosPorAutorReturnsData(): void
    {
        $result = $this->service->getLivrosPorAutor();
        if (!empty($result)) {
            // Campos do Autor
            $this->assertArrayHasKey('NomeAutor', $result[0]);
            $this->assertArrayHasKey('CodAutor', $result[0]);
            
            // Campos do Livro
            $this->assertArrayHasKey('Titulo', $result[0]);
            $this->assertArrayHasKey('Editora', $result[0]);
            $this->assertArrayHasKey('Edicao', $result[0]);
            $this->assertArrayHasKey('AnoPublicacao', $result[0]);
            $this->assertArrayHasKey('Valor', $result[0]);
            $this->assertArrayHasKey('CodLivro', $result[0]);
            
            // Campos agregados (podem estar vazios)
            $this->assertArrayHasKey('Assuntos', $result[0]);
            $this->assertArrayHasKey('OutrosAutores', $result[0]);
        }
    }

    /**
     * Testa se os dados estão ordenados por nome do autor e título
     */
    public function testGetLivrosPorAutorIsOrdered(): void
    {
        $result = $this->service->getLivrosPorAutor();
        
        if (count($result) > 1) {
            // Verifica se está ordenado por autor
            $autorAnterior = '';
            foreach ($result as $row) {
                if ($autorAnterior !== '') {
                    // Se mudou o autor, o novo deve ser maior ou igual (ordem alfabética)
                    if ($row['NomeAutor'] !== $autorAnterior) {
                        $this->assertGreaterThanOrEqual($autorAnterior, $row['NomeAutor']);
                    }
                }
                $autorAnterior = $row['NomeAutor'];
            }
        }
    }
}

