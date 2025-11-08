<?php

namespace Tests\Database;

use Tests\TestCase;
use App\Database\Connection;
use PDO;

class ConnectionTest extends TestCase
{
    public function testGetInstance(): void
    {
        $instance = Connection::getInstance();
        $this->assertInstanceOf(PDO::class, $instance);
    }

    public function testGetInstanceIsSingleton(): void
    {
        $instance1 = Connection::getInstance();
        $instance2 = Connection::getInstance();
        $this->assertSame($instance1, $instance2);
    }

    public function testConnectionWorks(): void
    {
        $pdo = Connection::getInstance();
        $stmt = $pdo->query("SELECT 1");
        $result = $stmt->fetch();
        $this->assertEquals(1, $result[0]);
    }
}

