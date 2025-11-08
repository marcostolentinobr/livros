<?php

namespace Tests\Config;

use Tests\TestCase;
use App\Config\App;

/** Testes para a classe App */
class AppTest extends TestCase
{
    /** Testa se o método init executa sem erros */
    public function testInit(): void
    {
        App::init();
        $this->assertTrue(true);
    }

    /** Testa se o atributo baseUrl é carregado corretamente */
    public function testBaseUrl(): void
    {
        App::init();
        $this->assertNotEmpty(App::$baseUrl);
    }

    /** Testa concatenação de URL com rota */
    public function testUrlConcatenation(): void
    {
        App::init();
        $url = App::$baseUrl . '/' . trim('livro', '/');
        $this->assertStringContainsString('livro', $url);
    }

    /** Testa se a concatenação normaliza barras */
    public function testUrlWithSlash(): void
    {
        App::init();
        $url1 = App::$baseUrl . '/' . trim('livro', '/');
        $url2 = App::$baseUrl . '/' . trim('/livro/', '/');
        $this->assertEquals($url1, $url2);
    }
}

