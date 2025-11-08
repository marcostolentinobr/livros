-- Script de criação do banco de dados
-- Sistema de Cadastro de Livros
-- Executado automaticamente pelo Docker na inicialização do container 'db'

USE livros_db;

-- Tabela: Autor
-- Armazena informações sobre os autores dos livros
CREATE TABLE IF NOT EXISTS Autor (
    CodAu INT AUTO_INCREMENT PRIMARY KEY,  -- Código do Autor (chave primária, auto-incremento)
    Nome VARCHAR(40) NOT NULL,              -- Nome do Autor (obrigatório)
    INDEX idx_nome (Nome)                   -- Índice para otimizar buscas por nome
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela: Assunto
-- Armazena as categorias ou assuntos dos livros
CREATE TABLE IF NOT EXISTS Assunto (
    codAs INT AUTO_INCREMENT PRIMARY KEY,  -- Código do Assunto (chave primária, auto-incremento)
    Descricao VARCHAR(20) NOT NULL,         -- Descrição do Assunto (obrigatório)
    INDEX idx_descricao (Descricao)         -- Índice para otimizar buscas por descrição
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela: Livro
-- Armazena os detalhes dos livros
CREATE TABLE IF NOT EXISTS Livro (
    Codl INT AUTO_INCREMENT PRIMARY KEY,        -- Código do Livro (chave primária, auto-incremento)
    Titulo VARCHAR(40) NOT NULL,                 -- Título do Livro (obrigatório)
    Editora VARCHAR(40) NOT NULL,              -- Editora do Livro (obrigatório)
    Edicao INT NOT NULL DEFAULT 1,              -- Número da Edição (padrão: 1, obrigatório)
    AnoPublicacao VARCHAR(4) NOT NULL,          -- Ano de Publicação (formato YYYY, obrigatório)
    Valor DECIMAL(10,2) NOT NULL DEFAULT 0.00,  -- Valor do Livro (com 2 casas decimais, padrão: 0.00)
    INDEX idx_titulo (Titulo),                  -- Índice para otimizar buscas por título
    INDEX idx_editora (Editora),                 -- Índice para otimizar buscas por editora
    INDEX idx_ano (AnoPublicacao)                -- Índice para otimizar buscas por ano de publicação
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de Junção: Livro_Autor
-- Gerencia o relacionamento muitos-para-muitos entre Livros e Autores
-- Um livro pode ter vários autores e um autor pode ter vários livros
-- ON DELETE CASCADE: Se um livro/autor for excluído, suas associações também são excluídas
CREATE TABLE IF NOT EXISTS Livro_Autor (
    Livro_Codl INT NOT NULL,    -- Chave estrangeira referenciando Livro(Codl)
    Autor_CodAu INT NOT NULL,   -- Chave estrangeira referenciando Autor(CodAu)
    PRIMARY KEY (Livro_Codl, Autor_CodAu),  -- Chave primária composta para garantir unicidade
    
    CONSTRAINT fk_livro_autor_livro FOREIGN KEY (Livro_Codl) 
        REFERENCES Livro(Codl) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_livro_autor_autor FOREIGN KEY (Autor_CodAu) 
        REFERENCES Autor(CodAu) ON DELETE CASCADE ON UPDATE CASCADE,
    
    INDEX Livro_Autor_FKIndex1 (Livro_Codl),   -- Índice para otimizar buscas por livro
    INDEX Livro_Autor_FKIndex2 (Autor_CodAu)   -- Índice para otimizar buscas por autor
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de Junção: Livro_Assunto
-- Gerencia o relacionamento muitos-para-muitos entre Livros e Assuntos
-- Um livro pode ter vários assuntos e um assunto pode estar em vários livros
-- ON DELETE CASCADE: Se um livro/assunto for excluído, suas associações também são excluídas
CREATE TABLE IF NOT EXISTS Livro_Assunto (
    Livro_Codl INT NOT NULL,      -- Chave estrangeira referenciando Livro(Codl)
    Assunto_codAs INT NOT NULL,    -- Chave estrangeira referenciando Assunto(codAs)
    PRIMARY KEY (Livro_Codl, Assunto_codAs),  -- Chave primária composta para garantir unicidade
    
    CONSTRAINT fk_livro_assunto_livro FOREIGN KEY (Livro_Codl) 
        REFERENCES Livro(Codl) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_livro_assunto_assunto FOREIGN KEY (Assunto_codAs) 
        REFERENCES Assunto(codAs) ON DELETE CASCADE ON UPDATE CASCADE,
    
    INDEX Livro_Assunto_FKIndex1 (Livro_Codl),     -- Índice para otimizar buscas por livro
    INDEX Livro_Assunto_FKIndex2 (Assunto_codAs)  -- Índice para otimizar buscas por assunto
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
