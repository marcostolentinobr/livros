<?php

namespace App\Models;

/**
 * Model para a entidade Livro
 * Gerencia operações relacionadas a livros e seus relacionamentos com autores e assuntos
 */
class Livro extends Model
{
    protected string $table = 'Livro';
    protected string $primaryKey = 'Codl';

    /**
     * Busca todos os autores associados a um livro
     * 
     * @param int $codl Código do livro
     * @return array Lista de autores com CodAu e Nome
     */
    public function getAutores(int $codl): array
    {
        // Busca autores através da tabela de junção Livro_Autor
        $sql = "SELECT a.CodAu, a.Nome 
                  FROM Autor a 
                  JOIN Livro_Autor la ON a.CodAu = la.Autor_CodAu 
                 WHERE la.Livro_Codl = :codl";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['codl' => $codl]);
        return $stmt->fetchAll();
    }

    /**
     * Busca todos os assuntos associados a um livro
     * 
     * @param int $codl Código do livro
     * @return array Lista de assuntos com codAs e Descricao
     */
    public function getAssuntos(int $codl): array
    {
        // Busca assuntos através da tabela de junção Livro_Assunto
        $sql = "SELECT a.codAs, a.Descricao 
                  FROM Assunto a 
                  JOIN Livro_Assunto las ON a.codAs = las.Assunto_codAs 
                 WHERE las.Livro_Codl = :codl";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['codl' => $codl]);
        return $stmt->fetchAll();
    }

    /**
     * Define os autores associados a um livro
     * Remove todas as associações anteriores e cria novas
     * 
     * @param int $codl Código do livro
     * @param array $autores Array com os IDs dos autores
     */
    public function setAutores(int $codl, array $autores): void
    {
        // Normaliza e valida os IDs: converte para inteiro, remove inválidos, duplicatas e reindexa
        $autores = array_map('intval', $autores);
        $autores = array_filter($autores, fn($v) => $v > 0);
        $autores = array_unique($autores);
        $autores = array_values($autores);
        
        $this->db->beginTransaction();
        
        // Remove todas as associações existentes
        $this->db->prepare("DELETE FROM Livro_Autor WHERE Livro_Codl = :codl")->execute(['codl' => $codl]);
        
        // Insere as novas associações
        if (!empty($autores)) {
            $stmt = $this->db->prepare("INSERT INTO Livro_Autor (Livro_Codl, Autor_CodAu) VALUES (:codl, :id)");
            foreach ($autores as $id) {
                $stmt->execute(['codl' => $codl, 'id' => $id]);
            }
        }
        
        $this->db->commit();
    }

    /**
     * Define os assuntos associados a um livro
     * Remove todas as associações anteriores e cria novas
     * 
     * @param int $codl Código do livro
     * @param array $assuntos Array com os IDs dos assuntos
     */
    public function setAssuntos(int $codl, array $assuntos): void
    {
        // Normaliza e valida os IDs: converte para inteiro, remove inválidos, duplicatas e reindexa
        $assuntos = array_map('intval', $assuntos);
        $assuntos = array_filter($assuntos, fn($v) => $v > 0);
        $assuntos = array_unique($assuntos);
        $assuntos = array_values($assuntos);
        
        $this->db->beginTransaction();
        
        // Remove todas as associações existentes
        $this->db->prepare("DELETE FROM Livro_Assunto WHERE Livro_Codl = :codl")->execute(['codl' => $codl]);
        
        // Insere as novas associações
        if (!empty($assuntos)) {
            $stmt = $this->db->prepare("INSERT INTO Livro_Assunto (Livro_Codl, Assunto_codAs) VALUES (:codl, :id)");
            foreach ($assuntos as $id) {
                $stmt->execute(['codl' => $codl, 'id' => $id]);
            }
        }
        
        $this->db->commit();
    }

    /**
     * Busca todos os livros com seus autores e assuntos agregados
     * Retorna os autores e assuntos como strings concatenadas separadas por vírgula
     * 
     * @return array Lista de livros com campos Autores e Assuntos
     */
    public function findAllWithRelations(): array
    {
        // Agrega autores e assuntos usando GROUP_CONCAT para criar strings separadas por vírgula
        $sql = "SELECT l.*, 
                       GROUP_CONCAT(DISTINCT a.Nome ORDER BY a.Nome SEPARATOR ', ') as Autores,
                       GROUP_CONCAT(DISTINCT ass.Descricao ORDER BY ass.Descricao SEPARATOR ', ') as Assuntos
                  FROM Livro l
             LEFT JOIN Livro_Autor la ON l.Codl = la.Livro_Codl
             LEFT JOIN Autor a ON la.Autor_CodAu = a.CodAu
             LEFT JOIN Livro_Assunto las ON l.Codl = las.Livro_Codl
             LEFT JOIN Assunto ass ON las.Assunto_codAs = ass.codAs
              GROUP BY l.Codl
              ORDER BY l.Titulo";
        return $this->db->query($sql)->fetchAll();
    }
}

