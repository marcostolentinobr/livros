-- Script de inserção de dados de exemplo (seed)
-- Sistema de Cadastro de Livros
-- Executado automaticamente pelo Docker na inicialização do container 'db'
-- APENAS SE O BANCO ESTIVER VAZIO

USE livros_db;

-- Inserir Autores
-- Dados de exemplo para a tabela 'Autor'
INSERT INTO Autor (Nome) VALUES 
('Machado de Assis'),
('Clarice Lispector'),
('Jorge Amado'),
('Carlos Drummond de Andrade'),
('Paulo Coelho');

-- Inserir Assuntos
-- Dados de exemplo para a tabela 'Assunto'
INSERT INTO Assunto (Descricao) VALUES 
('Romance'),
('Poesia'),
('Ficção'),
('Drama'),
('Aventura'),
('Biografia');

-- Inserir Livros
-- Dados de exemplo para a tabela 'Livro'
INSERT INTO Livro (Titulo, Editora, Edicao, AnoPublicacao, Valor) VALUES 
('Dom Casmurro', 'Companhia das Letras', 1, '1899', 45.90),
('Memórias Póstumas de Brás Cubas', 'Companhia das Letras', 2, '1881', 42.50),
('A Hora da Estrela', 'Rocco', 1, '1977', 35.00),
('Capitães da Areia', 'Companhia das Letras', 3, '1937', 48.90),
('O Alquimista', 'Editora Rocco', 1, '1988', 29.90);

-- Associar Autores aos Livros
-- Estabelece os relacionamentos muitos-para-muitos entre livros e autores
INSERT INTO Livro_Autor (Livro_Codl, Autor_CodAu) VALUES 
(1, 1), -- Dom Casmurro - Machado de Assis
(2, 1), -- Memórias Póstumas de Brás Cubas - Machado de Assis
(3, 2), -- A Hora da Estrela - Clarice Lispector
(4, 3), -- Capitães da Areia - Jorge Amado
(5, 5); -- O Alquimista - Paulo Coelho

-- Associar Assuntos aos Livros
-- Estabelece os relacionamentos muitos-para-muitos entre livros e assuntos
INSERT INTO Livro_Assunto (Livro_Codl, Assunto_codAs) VALUES 
(1, 1), -- Dom Casmurro - Romance
(1, 4), -- Dom Casmurro - Drama
(2, 1), -- Memórias Póstumas de Brás Cubas - Romance
(3, 3), -- A Hora da Estrela - Ficção
(4, 1), -- Capitães da Areia - Romance
(5, 3); -- O Alquimista - Ficção
