<?php

namespace App\Models;

use PDO;
use PDOException;

/**
 * Model para a entidade Assunto
 * Representa a tabela 'Assunto' no banco de dados
 * 
 * Herda todas as operações CRUD da classe Model base
 */
class Assunto extends Model
{
    /**
     * Nome da tabela no banco de dados
     */
    protected string $table = 'Assunto';

    /**
     * Nome da chave primária da tabela
     */
    protected string $primaryKey = 'codAs';

    /**
     * Busca assuntos associados a um livro específico
     * 
     * @param int $codl Código do livro
     * @return array Lista de assuntos do livro
     * @throws \RuntimeException Em caso de erro
     */
    public function findByLivro(int $codl): array
    {
        try {
            $sql = "
                SELECT a.codAs, a.Descricao 
                  FROM Assunto a 
                  JOIN Livro_Assunto las ON a.codAs = las.Assunto_codAs 
                 WHERE las.Livro_Codl = :codl
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['codl' => $codl]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error finding subjects by livro: " . $e->getMessage());
            throw new \RuntimeException("Erro ao buscar assuntos do livro.", 500);
        }
    }
}
