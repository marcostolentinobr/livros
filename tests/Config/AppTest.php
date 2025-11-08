<?php

namespace Tests\Config;

use Tests\TestCase;
use App\Config\App;

class AppTest extends TestCase
{
    public function testInit(): void
    {
        App::init();
        $this->assertTrue(true);
    }

    public function testBaseUrl(): void
    {
        App::init();
        $this->assertNotEmpty(App::$baseUrl);
    }

    public function testDefaultModule(): void
    {
        App::init();
        $this->assertNotEmpty(App::$defaultModule);
    }

    public function testUrlConcatenation(): void
    {
        App::init();
        $url = App::$baseUrl . '/' . trim('livro', '/');
        $this->assertStringContainsString('livro', $url);
    }
}

