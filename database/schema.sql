USE livros_db;

CREATE TABLE IF NOT EXISTS Autor (
    CodAu INT AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(40) NOT NULL,
    INDEX idx_nome (Nome)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS Assunto (
    codAs INT AUTO_INCREMENT PRIMARY KEY,
    Descricao VARCHAR(20) NOT NULL,
    INDEX idx_descricao (Descricao)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS Livro (
    Codl INT AUTO_INCREMENT PRIMARY KEY,
    Titulo VARCHAR(40) NOT NULL,
    Editora VARCHAR(40) NOT NULL,
    Edicao INT NOT NULL DEFAULT 1,
    AnoPublicacao VARCHAR(4) NOT NULL,
    Valor DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    INDEX idx_titulo (Titulo),
    INDEX idx_editora (Editora),
    INDEX idx_ano (AnoPublicacao)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de junção: Livro-Autor (muitos-para-muitos)
CREATE TABLE IF NOT EXISTS Livro_Autor (
    Livro_Codl INT NOT NULL,
    Autor_CodAu INT NOT NULL,
    PRIMARY KEY (Livro_Codl, Autor_CodAu),
    -- CASCADE: exclui/atualiza registros relacionados automaticamente
    CONSTRAINT fk_livro_autor_livro FOREIGN KEY (Livro_Codl) 
        REFERENCES Livro(Codl) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_livro_autor_autor FOREIGN KEY (Autor_CodAu) 
        REFERENCES Autor(CodAu) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX Livro_Autor_FKIndex1 (Livro_Codl),
    INDEX Livro_Autor_FKIndex2 (Autor_CodAu)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de junção: Livro-Assunto (muitos-para-muitos)
CREATE TABLE IF NOT EXISTS Livro_Assunto (
    Livro_Codl INT NOT NULL,
    Assunto_codAs INT NOT NULL,
    PRIMARY KEY (Livro_Codl, Assunto_codAs),
    -- CASCADE: exclui/atualiza registros relacionados automaticamente
    CONSTRAINT fk_livro_assunto_livro FOREIGN KEY (Livro_Codl) 
        REFERENCES Livro(Codl) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_livro_assunto_assunto FOREIGN KEY (Assunto_codAs) 
        REFERENCES Assunto(codAs) ON DELETE CASCADE ON UPDATE CASCADE,
    INDEX Livro_Assunto_FKIndex1 (Livro_Codl),
    INDEX Livro_Assunto_FKIndex2 (Assunto_codAs)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
