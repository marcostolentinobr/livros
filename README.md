# Sistema de Cadastro de Livros

Sistema web para cadastro e gerenciamento de livros, autores e assuntos desenvolvido em PHP 8.2 com arquitetura MVC.

## 🚀 Instalação

### Pré-requisitos
- Docker e Docker Compose

### Executar

```bash
docker-compose up -d
```

Acesse: **http://localhost:8888**

O arquivo `.env` será criado automaticamente a partir do `.env.example` na primeira execução.

## 🧪 Testes

```bash
docker-compose exec php vendor/bin/phpunit tests/
```

## 🔧 Tecnologias

- **Backend**: PHP 8.2, PDO, MySQL/MariaDB
- **Frontend**: HTML5, CSS3, Bootstrap 5, jQuery, Ajax
- **Infraestrutura**: Docker, Docker Compose, Apache
- **Testes**: PHPUnit 10

## 📁 Estrutura

```
livros/
├── database/        # Scripts SQL
├── docker/          # Dockerfile e entrypoint
├── public/          # Ponto de entrada
├── src/
│   ├── Controllers/ # Controllers MVC
│   ├── Models/      # Modelos de dados
│   ├── Views/       # Templates HTML
│   ├── Services/    # Lógica de negócio
│   └── Config/      # Configurações
└── tests/           # Testes unitários
```

## 📝 Funcionalidades

- CRUD completo de Livros, Autores e Assuntos
- Relatório agrupado por autor
- Interface responsiva com Bootstrap
- Testes unitários
