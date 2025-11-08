-- Script de criação de Views do banco de dados
-- Sistema de Cadastro de Livros
-- Executado automaticamente pelo Docker após a criação das tabelas

USE livros_db;

-- View: vw_livros_por_autor
-- Agrupa livros por autor principal, incluindo assuntos e coautores
-- Útil para gerar relatórios com uma visão consolidada dos livros e suas relações
DROP VIEW IF EXISTS vw_livros_por_autor;

CREATE VIEW vw_livros_por_autor AS
SELECT 
    a.CodAu AS CodAutor,    -- Código do autor principal
    a.Nome AS NomeAutor,    -- Nome do autor principal
    l.Codl AS CodLivro,     -- Código do livro
    l.Titulo,               -- Título do livro
    l.Editora,              -- Editora do livro
    l.Edicao,               -- Edição do livro
    l.AnoPublicacao,        -- Ano de publicação do livro
    l.Valor,                -- Valor do livro
    
    -- Concatena todos os assuntos distintos de um livro em uma única string separada por vírgula
    GROUP_CONCAT(DISTINCT ass.Descricao ORDER BY ass.Descricao SEPARATOR ', ') AS Assuntos,
    
    -- Concatena outros autores (coautores) do mesmo livro em uma única string separada por vírgula
    GROUP_CONCAT(DISTINCT a2.Nome ORDER BY a2.Nome SEPARATOR ', ') AS OutrosAutores
    
FROM Autor a
    -- Relacionamento: Autor -> Livro_Autor -> Livro
    INNER JOIN Livro_Autor la ON a.CodAu = la.Autor_CodAu
    INNER JOIN Livro l ON la.Livro_Codl = l.Codl
    
    -- LEFT JOINs para incluir assuntos e outros autores, mesmo que não existam
    LEFT JOIN Livro_Assunto las ON l.Codl = las.Livro_Codl
    LEFT JOIN Assunto ass ON las.Assunto_codAs = ass.codAs
    LEFT JOIN Livro_Autor la2 ON l.Codl = la2.Livro_Codl AND la2.Autor_CodAu != a.CodAu
    LEFT JOIN Autor a2 ON la2.Autor_CodAu = a2.CodAu
    
GROUP BY a.CodAu, a.Nome, l.Codl, l.Titulo, l.Editora, l.Edicao, l.AnoPublicacao, l.Valor
ORDER BY a.Nome, l.Titulo;
