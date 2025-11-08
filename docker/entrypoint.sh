#!/bin/bash

set -e  # Para execução se houver erro

cd /var/www/html

# Cria .env a partir do .env.example (remove BOM e converte CRLF para LF)
if [ ! -f ".env" ] && [ -f ".env.example" ]; then
    sed '1s/^\xEF\xBB\xBF//' .env.example | sed 's/\r$//' > .env
fi

# Instala dependências se ainda não foram instaladas
if [ ! -f "vendor/autoload.php" ]; then
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

exec apache2-foreground
