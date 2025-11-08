<?php

namespace Tests\Config;

use Tests\TestCase;
use App\Config\App;

/**
 * Testes para a classe App
 * Verifica inicialização e geração de URLs
 */
class AppTest extends TestCase
{
    /**
     * Testa se o método init executa sem erros
     */
    public function testInit(): void
    {
        App::init();
        $this->assertTrue(true);
    }

    /**
     * Testa se o método url gera URLs corretas
     */
    public function testUrl(): void
    {
        App::init();
        $url = App::url('livro');
        $this->assertStringContainsString('livro', $url);
    }

    /**
     * Testa se o método url normaliza barras (remove barras extras)
     */
    public function testUrlWithSlash(): void
    {
        App::init();
        $url1 = App::url('livro');
        $url2 = App::url('/livro/');
        $this->assertEquals($url1, $url2);
    }

    /**
     * Testa se o método url retorna a URL base quando chamado sem parâmetros
     */
    public function testUrlEmpty(): void
    {
        App::init();
        $url = App::url();
        $this->assertNotEmpty($url);
    }
}

