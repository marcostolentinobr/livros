# Sistema de Cadastro de Livros

Sistema web para cadastro e gerenciamento de livros, autores e assuntos.

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

- PHP 8.2, PDO, MySQL
- HTML5, CSS3, Bootstrap 5, jQuery
- Docker, Apache
- PHPUnit, TCPDF

## Funcionalidades

- CRUD de Livros, Autores e Assuntos
- Relatório em PDF agrupado por autor
- Interface responsiva
- Testes unitários
- Máscaras para valores monetários
