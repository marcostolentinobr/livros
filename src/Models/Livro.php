<?php

namespace App\Models;

use PDO;
use PDOException;

class Livro extends Model
{
    protected string $table = 'Livro';
    protected string $primaryKey = 'Codl';

    public function getAutores(int $codl): array
    {
        return (new \App\Models\Autor())->findByLivro($codl);
    }

    public function getAssuntos(int $codl): array
    {
        return (new \App\Models\Assunto())->findByLivro($codl);
    }

    public function setAutores(int $codl, array $autores): void
    {
        try {
            // Garantir que todos os valores são inteiros e remover duplicatas
            $autores = array_map('intval', $autores);
            $autores = array_filter($autores, function($v) { return $v > 0; });
            $autores = array_unique($autores); // Remove duplicatas
            $autores = array_values($autores); // Reindexa array
            
            $this->db->beginTransaction();

            // Remove TODAS as associações existentes primeiro
            $deleteStmt = $this->db->prepare("DELETE FROM Livro_Autor WHERE Livro_Codl = :codl");
            $deleteStmt->execute(['codl' => $codl]);

            // Adiciona novas associações
            if (!empty($autores)) {
                $stmt = $this->db->prepare("INSERT INTO Livro_Autor (Livro_Codl, Autor_CodAu) VALUES (:codl, :autor)");
                foreach ($autores as $autorId) {
                    $stmt->execute(['codl' => $codl, 'autor' => $autorId]);
                }
            }

            $this->db->commit();
        } catch (PDOException $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log("Error setting authors for livro {$codl}: " . $e->getMessage());
            error_log("Autores recebidos: " . print_r($autores, true));
            error_log("SQL Error Code: " . $e->getCode());
            throw new \RuntimeException("Erro ao associar autores ao livro: " . $e->getMessage(), 500);
        }
    }

    public function setAssuntos(int $codl, array $assuntos): void
    {
        try {
            // Garantir que todos os valores são inteiros e remover duplicatas
            $assuntos = array_map('intval', $assuntos);
            $assuntos = array_filter($assuntos, function($v) { return $v > 0; });
            $assuntos = array_unique($assuntos); // Remove duplicatas
            $assuntos = array_values($assuntos); // Reindexa array
            
            $this->db->beginTransaction();

            // Remove TODAS as associações existentes primeiro
            $deleteStmt = $this->db->prepare("DELETE FROM Livro_Assunto WHERE Livro_Codl = :codl");
            $deleteStmt->execute(['codl' => $codl]);

            // Adiciona novas associações
            if (!empty($assuntos)) {
                $stmt = $this->db->prepare("INSERT INTO Livro_Assunto (Livro_Codl, Assunto_codAs) VALUES (:codl, :assunto)");
                foreach ($assuntos as $assuntoId) {
                    $stmt->execute(['codl' => $codl, 'assunto' => $assuntoId]);
                }
            }

            $this->db->commit();
        } catch (PDOException $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log("Error setting subjects for livro {$codl}: " . $e->getMessage());
            error_log("Assuntos recebidos: " . print_r($assuntos, true));
            error_log("SQL Error Code: " . $e->getCode());
            throw new \RuntimeException("Erro ao associar assuntos ao livro: " . $e->getMessage(), 500);
        }
    }

    public function findAllWithRelations(): array
    {
        try {
            $sql = "
                SELECT l.*, 
                       GROUP_CONCAT(DISTINCT a.Nome ORDER BY a.Nome SEPARATOR ', ') as Autores,
                       GROUP_CONCAT(DISTINCT ass.Descricao ORDER BY ass.Descricao SEPARATOR ', ') as Assuntos
                  FROM Livro l
             LEFT JOIN Livro_Autor la ON l.Codl = la.Livro_Codl
             LEFT JOIN Autor a ON la.Autor_CodAu = a.CodAu
             LEFT JOIN Livro_Assunto las ON l.Codl = las.Livro_Codl
             LEFT JOIN Assunto ass ON las.Assunto_codAs = ass.codAs
              GROUP BY l.Codl
              ORDER BY l.Titulo
            ";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Error finding all with relations: " . $e->getMessage());
            throw new \RuntimeException("Erro ao buscar livros com relações.", 500);
        }
    }
}

