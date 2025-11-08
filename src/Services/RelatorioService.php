<?php

namespace App\Services;

use App\Database\Connection;
use PDO;

/**
 * Service para geração de relatórios
 * Gerencia consultas e processamento de dados para relatórios do sistema
 */
class RelatorioService
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    /**
     * Busca livros agrupados por autor
     * Utiliza a view vw_livros_por_autor do banco de dados
     * 
     * @return array Lista de livros com informações do autor, ordenados por nome do autor e título
     */
    public function getLivrosPorAutor(): array
    {
        $sql = "SELECT * FROM vw_livros_por_autor ORDER BY NomeAutor, Titulo";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}
