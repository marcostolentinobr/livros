<?php

namespace Tests\Config;

use Tests\TestCase;
use App\Config\Env;

/** Testes para a classe Env */
class EnvTest extends TestCase
{
    /** Testa se o método load executa sem erros */
    public function testLoad(): void
    {
        Env::load();
        $this->assertTrue(true);
    }

    /** Testa se o método get retorna valor existente */
    public function testGet(): void
    {
        Env::load();
        $value = Env::get('DB_HOST');
        $this->assertNotEmpty($value);
    }

    /** Testa se o método get lança exceção quando variável não existe */
    public function testGetThrowsExceptionWhenNotFound(): void
    {
        $this->expectException(\RuntimeException::class);
        Env::get('VARIAVEL_INEXISTENTE_XYZ');
    }

    /** Testa se o método getOptional retorna valor existente */
    public function testGetOptional(): void
    {
        Env::load();
        $value = Env::getOptional('DB_HOST');
        $this->assertNotEmpty($value);
    }

    /** Testa se o método getOptional retorna valor padrão quando variável não existe */
    public function testGetOptionalReturnsDefault(): void
    {
        $value = Env::getOptional('VARIAVEL_INEXISTENTE_XYZ', 'default');
        $this->assertEquals('default', $value);
    }
}

