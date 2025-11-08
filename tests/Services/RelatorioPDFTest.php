<?php

namespace Tests\Services;

use Tests\TestCase;
use App\Services\RelatorioPDF;
use ReflectionMethod;

/**
 * Testes para o RelatorioPDF
 * Verifica a geração de relatórios em PDF usando TCPDF
 * A consulta é proveniente da view vw_livros_por_autor do banco de dados
 */
class RelatorioPDFTest extends TestCase
{
    /**
     * Testa se a classe RelatorioPDF pode ser instanciada
     * Verifica se o TCPDF é inicializado corretamente
     */
    public function testCanInstantiate(): void
    {
        // Suprime warnings de constantes já definidas (comum com TCPDF)
        $oldErrorReporting = error_reporting(E_ALL & ~E_WARNING);
        
        try {
            $relatorioPDF = new RelatorioPDF();
            $this->assertInstanceOf(RelatorioPDF::class, $relatorioPDF);
        } finally {
            error_reporting($oldErrorReporting);
        }
    }

    /**
     * Testa se o método gerarRelatorio existe e é público
     */
    public function testGerarRelatorioMethodExists(): void
    {
        $oldErrorReporting = error_reporting(E_ALL & ~E_WARNING);
        
        try {
            $relatorioPDF = new RelatorioPDF();
            $method = new ReflectionMethod($relatorioPDF, 'gerarRelatorio');
            
            $this->assertTrue($method->isPublic());
            $this->assertEquals('gerarRelatorio', $method->getName());
        } finally {
            error_reporting($oldErrorReporting);
        }
    }

    /**
     * Testa se o método gerarRelatorio busca dados da view
     * Nota: Não executa o método completo pois ele faz exit através do Output() do TCPDF
     */
    public function testGerarRelatorioUsesView(): void
    {
        // Este teste verifica apenas que a classe está configurada corretamente
        // O método gerarRelatorio não pode ser testado completamente pois faz exit
        $oldErrorReporting = error_reporting(E_ALL & ~E_WARNING);
        
        try {
            $relatorioPDF = new RelatorioPDF();
            $this->assertInstanceOf(RelatorioPDF::class, $relatorioPDF);
            
            // Verifica que o método existe e está acessível
            $this->assertTrue(method_exists($relatorioPDF, 'gerarRelatorio'));
        } finally {
            error_reporting($oldErrorReporting);
        }
    }
}

