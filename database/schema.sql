-- ============================================
-- ESTRUTURA DO BANCO DE DADOS
-- ============================================
-- Este script cria todas as tabelas do sistema
-- Executado automaticamente pelo Docker na inicialização

USE livros_db;

-- Tabela: Autor
-- Armazena os autores dos livros
CREATE TABLE IF NOT EXISTS Autor (
    CodAu INT AUTO_INCREMENT PRIMARY KEY,  -- ID único do autor
    Nome VARCHAR(40) NOT NULL,              -- Nome do autor
    INDEX idx_nome (Nome)                   -- Índice para buscas rápidas por nome
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela: Assunto
-- Armazena as categorias/temas dos livros (ex: Romance, Ficção, Drama)
CREATE TABLE IF NOT EXISTS Assunto (
    codAs INT AUTO_INCREMENT PRIMARY KEY,  -- ID único do assunto
    Descricao VARCHAR(20) NOT NULL,         -- Nome do assunto
    INDEX idx_descricao (Descricao)         -- Índice para buscas rápidas
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela: Livro
-- Armazena as informações dos livros
CREATE TABLE IF NOT EXISTS Livro (
    Codl INT AUTO_INCREMENT PRIMARY KEY,        -- ID único do livro
    Titulo VARCHAR(40) NOT NULL,                 -- Título do livro
    Editora VARCHAR(40) NOT NULL,              -- Editora
    Edicao INT NOT NULL DEFAULT 1,              -- Número da edição
    AnoPublicacao VARCHAR(4) NOT NULL,          -- Ano de publicação (YYYY)
    Valor DECIMAL(10,2) NOT NULL DEFAULT 0.00,  -- Valor em reais (R$)
    INDEX idx_titulo (Titulo),                  -- Índices para otimizar buscas
    INDEX idx_editora (Editora),
    INDEX idx_ano (AnoPublicacao)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela: Livro_Autor (Tabela de Junção)
-- Relaciona livros com autores (relacionamento muitos-para-muitos)
-- Um livro pode ter vários autores e um autor pode ter vários livros
CREATE TABLE IF NOT EXISTS Livro_Autor (
    Livro_Codl INT NOT NULL,    -- ID do livro
    Autor_CodAu INT NOT NULL,   -- ID do autor
    PRIMARY KEY (Livro_Codl, Autor_CodAu),  -- Chave primária composta
    CONSTRAINT fk_livro_autor_livro FOREIGN KEY (Livro_Codl) 
        REFERENCES Livro(Codl) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_livro_autor_autor FOREIGN KEY (Autor_CodAu) 
        REFERENCES Autor(CodAu) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX Livro_Autor_FKIndex1 (Livro_Codl),
    INDEX Livro_Autor_FKIndex2 (Autor_CodAu)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela: Livro_Assunto (Tabela de Junção)
-- Relaciona livros com assuntos (relacionamento muitos-para-muitos)
-- Um livro pode ter vários assuntos e um assunto pode estar em vários livros
CREATE TABLE IF NOT EXISTS Livro_Assunto (
    Livro_Codl INT NOT NULL,      -- ID do livro
    Assunto_codAs INT NOT NULL,    -- ID do assunto
    PRIMARY KEY (Livro_Codl, Assunto_codAs),  -- Chave primária composta
    CONSTRAINT fk_livro_assunto_livro FOREIGN KEY (Livro_Codl) 
        REFERENCES Livro(Codl) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_livro_assunto_assunto FOREIGN KEY (Assunto_codAs) 
        REFERENCES Assunto(codAs) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX Livro_Assunto_FKIndex1 (Livro_Codl),
    INDEX Livro_Assunto_FKIndex2 (Assunto_codAs)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
