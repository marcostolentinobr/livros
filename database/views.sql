-- ============================================
-- VIEW PARA RELATÓRIO
-- ============================================
-- Esta VIEW agrupa livros por autor para o relatório em PDF
-- Atende ao requisito: "relatório agrupado por autor com dados das 3 tabelas principais"

USE livros_db;

DROP VIEW IF EXISTS vw_livros_por_autor;

CREATE VIEW vw_livros_por_autor AS
SELECT 
    -- Dados do Autor (tabela principal 1)
    a.CodAu AS CodAutor,
    a.Nome AS NomeAutor,
    
    -- Dados do Livro (tabela principal 2)
    l.Codl AS CodLivro,
    l.Titulo,
    l.Editora,
    l.Edicao,
    l.AnoPublicacao,
    l.Valor,
    
    -- Dados do Assunto (tabela principal 3) - concatenados
    GROUP_CONCAT(DISTINCT ass.Descricao ORDER BY ass.Descricao SEPARATOR ', ') AS Assuntos,
    
    -- Coautores (outros autores do mesmo livro)
    GROUP_CONCAT(DISTINCT a2.Nome ORDER BY a2.Nome SEPARATOR ', ') AS OutrosAutores
    
FROM Autor a
    -- Junta Autor com Livro através da tabela de junção
    INNER JOIN Livro_Autor la ON a.CodAu = la.Autor_CodAu
    INNER JOIN Livro l ON la.Livro_Codl = l.Codl
    
    -- LEFT JOIN para incluir assuntos (pode não ter)
    LEFT JOIN Livro_Assunto las ON l.Codl = las.Livro_Codl
    LEFT JOIN Assunto ass ON las.Assunto_codAs = ass.codAs
    
    -- LEFT JOIN para incluir coautores (outros autores do mesmo livro)
    LEFT JOIN Livro_Autor la2 ON l.Codl = la2.Livro_Codl AND la2.Autor_CodAu != a.CodAu
    LEFT JOIN Autor a2 ON la2.Autor_CodAu = a2.CodAu
    
-- Agrupa por autor e livro (requisito do desafio)
GROUP BY a.CodAu, a.Nome, l.Codl, l.Titulo, l.Editora, l.Edicao, l.AnoPublicacao, l.Valor
ORDER BY a.Nome, l.Titulo;
