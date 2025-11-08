-- ============================================
-- DADOS DE EXEMPLO (SEED)
-- ============================================
-- Este script insere dados de exemplo para testar o sistema
-- Executado automaticamente pelo Docker apenas se o banco estiver vazio

USE livros_db;

-- Inserir Autores
INSERT INTO Autor (Nome) VALUES 
('Machado de Assis'),
('Clarice Lispector'),
('Jorge Amado'),
('Carlos Drummond de Andrade'),
('Paulo Coelho');

-- Inserir Assuntos
INSERT INTO Assunto (Descricao) VALUES 
('Romance'),
('Poesia'),
('Ficção'),
('Drama'),
('Aventura'),
('Biografia');

-- Inserir Livros
INSERT INTO Livro (Titulo, Editora, Edicao, AnoPublicacao, Valor) VALUES 
('Dom Casmurro', 'Companhia das Letras', 1, '1899', 45.90),
('Memórias Póstumas de Brás Cubas', 'Companhia das Letras', 2, '1881', 42.50),
('A Hora da Estrela', 'Rocco', 1, '1977', 35.00),
('Capitães da Areia', 'Companhia das Letras', 3, '1937', 48.90),
('O Alquimista', 'Editora Rocco', 1, '1988', 29.90);

-- Associar Autores aos Livros (relacionamento muitos-para-muitos)
INSERT INTO Livro_Autor (Livro_Codl, Autor_CodAu) VALUES 
(1, 1), -- Dom Casmurro - Machado de Assis
(2, 1), -- Memórias Póstumas - Machado de Assis
(3, 2), -- A Hora da Estrela - Clarice Lispector
(4, 3), -- Capitães da Areia - Jorge Amado
(5, 5); -- O Alquimista - Paulo Coelho

-- Associar Assuntos aos Livros (relacionamento muitos-para-muitos)
INSERT INTO Livro_Assunto (Livro_Codl, Assunto_codAs) VALUES 
(1, 1), -- Dom Casmurro - Romance
(1, 4), -- Dom Casmurro - Drama (um livro pode ter vários assuntos)
(2, 1), -- Memórias Póstumas - Romance
(3, 3), -- A Hora da Estrela - Ficção
(4, 1), -- Capitães da Areia - Romance
(5, 3); -- O Alquimista - Ficção
