<?php

namespace Tests\Database;

use Tests\TestCase;
use App\Database\Connection;
use PDO;

/**
 * Testes para a classe Connection
 * Verifica a conexão com o banco de dados e o padrão Singleton
 */
class ConnectionTest extends TestCase
{
    /**
     * Testa se getInstance retorna uma instância de PDO
     */
    public function testGetInstance(): void
    {
        $instance1 = Connection::getInstance();
        $this->assertInstanceOf(PDO::class, $instance1);
    }

    /**
     * Testa se getInstance implementa o padrão Singleton
     * (retorna a mesma instância em múltiplas chamadas)
     */
    public function testGetInstanceIsSingleton(): void
    {
        $instance1 = Connection::getInstance();
        $instance2 = Connection::getInstance();
        $this->assertSame($instance1, $instance2);
    }

    /**
     * Testa se a conexão está funcionando corretamente
     * Executa uma query simples para verificar
     */
    public function testConnectionWorks(): void
    {
        $pdo = Connection::getInstance();
        $stmt = $pdo->query("SELECT 1");
        $result = $stmt->fetch();
        $this->assertEquals(1, $result[0]);
    }
}

