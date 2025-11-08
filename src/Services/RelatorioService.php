<?php

namespace App\Services;

use App\Database\Connection;
use PDO;

/**
 * Service para buscar dados dos relatórios
 * Utiliza a view vw_livros_por_autor do banco de dados
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
     * Utiliza a view vw_livros_por_autor que já faz o agrupamento
     * e inclui informações de assuntos e coautores
     * 
     * @return array Lista de livros ordenados por nome do autor e título
     */
    public function getLivrosPorAutor(): array
    {
        $sql = "SELECT * FROM vw_livros_por_autor ORDER BY NomeAutor, Titulo";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}
