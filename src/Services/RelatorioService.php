<?php

namespace App\Services;

use App\Database\Connection;

/** Service para buscar dados dos relatórios */
class RelatorioService
{
    /** Busca livros agrupados por autor */
    public function getLivrosPorAutor(): array
    {
        $db = Connection::getInstance();
        $sql = "SELECT * FROM vw_livros_por_autor ORDER BY NomeAutor, Titulo";
        $stmt = $db->query($sql);
        return $stmt->fetchAll();
    }
}
