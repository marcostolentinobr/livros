<?php

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

/**
 * ============================================
 * CLASSE BASE PARA TODOS OS TESTES
 * ============================================
 * Todos os testes do sistema herdam desta classe
 * Fornece funcionalidades comuns para os testes
 * 
 * Exemplo de uso:
 * class LivroTest extends TestCase
 * {
 *     public function testCreate() { ... }
 * }
 */
abstract class TestCase extends BaseTestCase
{
    // Classe base vazia - pode ser estendida no futuro com métodos auxiliares
}
