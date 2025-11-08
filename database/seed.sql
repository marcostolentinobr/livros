USE livros_db;

INSERT INTO Autor (Nome) VALUES 
('Machado de Assis'),
('Clarice Lispector'),
('Jorge Amado'),
('Carlos Drummond de Andrade'),
('Paulo Coelho');

INSERT INTO Assunto (Descricao) VALUES 
('Romance'),
('Poesia'),
('Ficção'),
('Drama'),
('Aventura'),
('Biografia');

INSERT INTO Livro (Titulo, Editora, Edicao, AnoPublicacao, Valor) VALUES 
('Dom Casmurro', 'Companhia das Letras', 1, '1899', 45.90),
('Memórias Póstumas de Brás Cubas', 'Companhia das Letras', 2, '1881', 42.50),
('A Hora da Estrela', 'Rocco', 1, '1977', 35.00),
('Capitães da Areia', 'Companhia das Letras', 3, '1937', 48.90),
('O Alquimista', 'Editora Rocco', 1, '1988', 29.90);

INSERT INTO Livro_Autor (Livro_Codl, Autor_CodAu) VALUES 
(1, 1),
(2, 1),
(3, 2),
(4, 3),
(5, 5);

INSERT INTO Livro_Assunto (Livro_Codl, Assunto_codAs) VALUES 
(1, 1),
(1, 4),
(2, 1),
(3, 3),
(4, 1),
(5, 3);
