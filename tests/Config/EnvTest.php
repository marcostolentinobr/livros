<?php

namespace Tests\Config;

use Tests\TestCase;
use App\Config\Env;

class EnvTest extends TestCase
{
    public function testLoad(): void
    {
        Env::load();
        $this->assertTrue(true);
    }

    public function testGet(): void
    {
        Env::load();
        $value = Env::get('DB_HOST');
        $this->assertNotEmpty($value);
    }

    public function testGetThrowsExceptionWhenNotFound(): void
    {
        $this->expectException(\RuntimeException::class);
        Env::get('VARIAVEL_INEXISTENTE_XYZ');
    }

    public function testGetOptional(): void
    {
        Env::load();
        $value = Env::getOptional('DB_HOST');
        $this->assertNotEmpty($value);
    }

    public function testGetOptionalReturnsDefault(): void
    {
        $value = Env::getOptional('VARIAVEL_INEXISTENTE_XYZ', 'default');
        $this->assertEquals('default', $value);
    }
}

