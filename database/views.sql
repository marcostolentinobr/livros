USE livros_db;

DROP VIEW IF EXISTS vw_livros_por_autor;

-- VIEW para relatório agrupado por autor
CREATE VIEW vw_livros_por_autor AS
SELECT 
    a.CodAu AS CodAutor,
    a.Nome AS NomeAutor,
    l.Codl AS CodLivro,
    l.Titulo,
    l.Editora,
    l.Edicao,
    l.AnoPublicacao,
    l.Valor,
    GROUP_CONCAT(DISTINCT ass.Descricao ORDER BY ass.Descricao SEPARATOR ', ') AS Assuntos,
    GROUP_CONCAT(DISTINCT a2.Nome ORDER BY a2.Nome SEPARATOR ', ') AS OutrosAutores
FROM Autor a
    INNER JOIN Livro_Autor la ON a.CodAu = la.Autor_CodAu
    INNER JOIN Livro l ON la.Livro_Codl = l.Codl
    LEFT JOIN Livro_Assunto las ON l.Codl = las.Livro_Codl
    LEFT JOIN Assunto ass ON las.Assunto_codAs = ass.codAs
    -- Busca coautores (outros autores do mesmo livro, excluindo o autor principal)
    LEFT JOIN Livro_Autor la2 ON l.Codl = la2.Livro_Codl AND la2.Autor_CodAu != a.CodAu
    LEFT JOIN Autor a2 ON la2.Autor_CodAu = a2.CodAu
GROUP BY a.CodAu, a.Nome, l.Codl, l.Titulo, l.Editora, l.Edicao, l.AnoPublicacao, l.Valor
ORDER BY a.Nome, l.Titulo;
