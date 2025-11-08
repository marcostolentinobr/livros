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

    public function testUrl(): void
    {
        App::init();
        $url = App::url('livro');
        $this->assertStringContainsString('livro', $url);
    }

    public function testUrlWithSlash(): void
    {
        App::init();
        $url1 = App::url('livro');
        $url2 = App::url('/livro/');
        $this->assertEquals($url1, $url2);
    }

    public function testUrlEmpty(): void
    {
        App::init();
        $url = App::url();
        $this->assertNotEmpty($url);
    }
}

