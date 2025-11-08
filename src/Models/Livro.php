<?php

namespace App\Models;

/** Model para a entidade Livro */
class Livro extends Model
{
    protected $table = 'Livro';
    protected $primaryKey = 'Codl';

    /** Busca IDs dos autores associados a um livro */
    public function getAutores($codl)
    {
        return $this->getRelacao($codl, 'Livro_Autor', 'Autor_CodAu');
    }

    /** Busca IDs dos assuntos associados a um livro */
    public function getAssuntos($codl)
    {
        return $this->getRelacao($codl, 'Livro_Assunto', 'Assunto_codAs');
    }

    /** Define os autores associados a um livro */
    public function setAutores($codl, $autores)
    {
        $this->setRelacao($codl, $autores, 'Livro_Autor', 'Autor_CodAu');
    }

    /** Define os assuntos associados a um livro */
    public function setAssuntos($codl, $assuntos)
    {
        $this->setRelacao($codl, $assuntos, 'Livro_Assunto', 'Assunto_codAs');
    }

    /** Busca todos os livros com autores e assuntos agregados para listagem */
    public function findAllForIndex()
    {
        // GROUP_CONCAT agrega múltiplos valores em string separada por vírgula
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

