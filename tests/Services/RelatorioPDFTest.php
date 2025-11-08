<?php

namespace Tests\Services;

use Tests\TestCase;
use App\Services\RelatorioPDF;
use ReflectionMethod;

/** Testes para o RelatorioPDF */
class RelatorioPDFTest extends TestCase
{
    /** Testa se a classe RelatorioPDF pode ser instanciada */
    public function testCanInstantiate(): void
    {
        // Suprime warnings de constantes já definidas (TCPDF)
        $oldErrorReporting = error_reporting(E_ALL & ~E_WARNING);
        
        try {
            $relatorioPDF = new RelatorioPDF();
            $this->assertInstanceOf(RelatorioPDF::class, $relatorioPDF);
        } finally {
            error_reporting($oldErrorReporting);
        }
    }

    /** Testa se o método gerarRelatorio existe e é público */
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
}

