# Sistema de Cadastro de Livros

Sistema web completo para gerenciamento de livros, autores e assuntos com relatórios em PDF.

## 🚀 Instalação Rápida

```bash
# 1. Subir os containers (PHP + Apache + MySQL)
# O Docker cria automaticamente o arquivo .env a partir do .env.example
docker-compose up -d

# 2. Acessar a aplicação
# http://localhost:8888
```

**Nota:** O arquivo `.env` é criado automaticamente pelo Docker na primeira inicialização. Se precisar configurar manualmente, copie o `.env.example` para `.env` e ajuste os valores.

## 📋 Pré-requisitos

- Docker e Docker Compose instalados
- Portas 8888 e 3307 disponíveis

## 🧪 Executar Testes

```bash
# Executar todos os testes unitários
docker-compose exec php vendor/bin/phpunit tests/
```

## 🛠️ Tecnologias Utilizadas

### Backend
- **PHP 8.2** - Linguagem de programação
- **PDO** - Camada de persistência (abstração de banco)
- **MySQL/MariaDB** - Banco de dados relacional

### Frontend
- **HTML5** - Estrutura
- **CSS3** - Estilização
- **Bootstrap 5** - Framework CSS (interface responsiva)
- **jQuery** - Biblioteca JavaScript (AJAX, máscaras)

### Infraestrutura
- **Docker** - Containerização
- **Apache** - Servidor web
- **Docker Compose** - Orquestração de containers

### Ferramentas
- **PHPUnit** - Framework de testes (TDD)
- **TCPDF** - Geração de relatórios em PDF

## ✨ Funcionalidades Implementadas

### CRUD Completo
- ✅ **Livros**: Criar, Listar, Editar, Excluir
- ✅ **Autores**: Criar, Listar, Editar, Excluir
- ✅ **Assuntos**: Criar, Listar, Editar, Excluir

### Relatórios
- ✅ **PDF agrupado por autor** usando VIEW do banco
- ✅ Informações das 3 tabelas principais (Autor, Livro, Assunto)
- ✅ Suporte a múltiplos autores por livro

### Interface
- ✅ Design responsivo (Bootstrap 5)
- ✅ Formatação de campos (moeda, ano)
- ✅ Máscaras de entrada (jQuery Mask)
- ✅ Mensagens de feedback (sucesso/erro)

### Qualidade
- ✅ Testes unitários (PHPUnit)
- ✅ Tratamento de erros específicos (PDOException)
- ✅ Validação de dados

## 📁 Estrutura do Projeto

```
livros/
├── database/          # Scripts SQL
│   ├── schema.sql     # Estrutura das tabelas
│   ├── views.sql      # VIEW para relatório
│   └── seed.sql       # Dados de exemplo
├── docker/            # Configuração Docker
│   ├── Dockerfile     # Imagem PHP + Apache
│   └── entrypoint.sh  # Script de inicialização
├── public/            # Ponto de entrada público
│   ├── index.php      # Roteador principal
│   └── .htaccess      # Regras de reescrita de URL
├── src/               # Código fonte
│   ├── Config/        # Configurações
│   ├── Controllers/   # Controladores (lógica)
│   ├── Models/        # Modelos (banco de dados)
│   ├── Services/      # Serviços (relatórios)
│   └── Views/         # Templates HTML
├── tests/             # Testes unitários
└── vendor/            # Dependências (Composer)
```

## 🔧 Comandos Úteis

```bash
# Ver logs do PHP
docker-compose logs -f php

# Ver logs do banco
docker-compose logs -f db

# Parar containers
docker-compose down

# Parar e remover volumes (apaga banco)
docker-compose down -v

# Acessar shell do container PHP
docker-compose exec php bash

# Acessar MySQL via linha de comando (dentro do container)
docker-compose exec db mysql -u livros_user -plivros_pass livros_db

# Acessar MySQL externamente (ferramentas como MySQL Workbench)
# Host: localhost | Porta: 3307 | Usuário: livros_user | Senha: livros_pass | Banco: livros_db
```
