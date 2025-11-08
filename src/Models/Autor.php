<?php

namespace App\Models;

use PDO;
use PDOException;

class Autor extends Model
{
    protected string $table = 'Autor';
    protected string $primaryKey = 'CodAu';

    /**
     * Busca autores associados a um livro específico
     * 
     * @param int $codl Código do livro
     * @return array Lista de autores do livro
     * @throws \RuntimeException Em caso de erro
     */
    public function findByLivro(int $codl): array
    {
        try {
            $sql = "
                SELECT a.CodAu, a.Nome 
                  FROM Autor a 
                  JOIN Livro_Autor la ON a.CodAu = la.Autor_CodAu 
                 WHERE la.Livro_Codl = :codl
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['codl' => $codl]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error finding authors by livro: " . $e->getMessage());
            throw new \RuntimeException("Erro ao buscar autores do livro.", 500);
        }
    }
}

