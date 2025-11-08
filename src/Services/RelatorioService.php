<?php

namespace App\Services;

use App\Database\Connection;
use PDO;

class RelatorioService
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    public function getLivrosPorAutor(): array
    {
        $sql = "SELECT * FROM vw_livros_por_autor ORDER BY NomeAutor, Titulo";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }
}
