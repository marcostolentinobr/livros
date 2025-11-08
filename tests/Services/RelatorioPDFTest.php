<?php

namespace Tests\Services;

use Tests\TestCase;
use App\Services\RelatorioPDF;
use ReflectionMethod;

class RelatorioPDFTest extends TestCase
{
    public function testCanInstantiate(): void
    {
        $oldErrorReporting = error_reporting(E_ALL & ~E_WARNING);
        
        try {
            $relatorioPDF = new RelatorioPDF();
            $this->assertInstanceOf(RelatorioPDF::class, $relatorioPDF);
        } finally {
            error_reporting($oldErrorReporting);
        }
    }

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

