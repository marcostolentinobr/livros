#!/bin/bash
set -e

cd /var/www/html

# Cria o arquivo .env a partir do .env.example se ele não existir
if [ ! -f ".env" ] && [ -f ".env.example" ]; then
    # Remove BOM e converte CRLF para LF
    sed '1s/^\xEF\xBB\xBF//' .env.example | sed 's/\r$//' > .env
fi

# Instala as dependências do Composer se ainda não foram instaladas
if [ ! -f "vendor/autoload.php" ]; then
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# Inicia o servidor Apache
exec apache2-foreground
