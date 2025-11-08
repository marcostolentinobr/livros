# Sistema de Cadastro de Livros

Sistema web para gerenciamento de livros, autores e assuntos com relatórios em PDF.

## Instalação

```bash
docker-compose up -d
```

Acesse: http://localhost:8888

## Testes

```bash
docker-compose exec php vendor/bin/phpunit tests/
```

## Tecnologias

- PHP 8.2, PDO, MySQL/MariaDB
- HTML5, CSS3, Bootstrap 5, jQuery
- Docker, Apache, PHPUnit, TCPDF

## Estrutura

```
livros/
├── database/     # Scripts SQL
├── docker/       # Configuração Docker
├── public/       # Ponto de entrada
├── src/          # Código fonte
├── tests/        # Testes unitários
└── vendor/       # Dependências
```

## Comandos

```bash
docker-compose logs -f php      # Logs PHP
docker-compose logs -f db       # Logs banco
docker-compose down             # Parar containers
docker-compose exec php bash    # Shell PHP
```
