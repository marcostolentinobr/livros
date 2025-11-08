#!/bin/bash
# ============================================
# SCRIPT DE INICIALIZAÇÃO DO CONTAINER
# ============================================
# Executado automaticamente quando o container PHP inicia
# Prepara o ambiente antes de iniciar o Apache

set -e  # Para execução se houver erro

cd /var/www/html

# Criar arquivo .env a partir do .env.example se não existir
# Remove BOM (Byte Order Mark) e converte CRLF para LF (compatibilidade Windows/Linux)
if [ ! -f ".env" ] && [ -f ".env.example" ]; then
    sed '1s/^\xEF\xBB\xBF//' .env.example | sed 's/\r$//' > .env
fi

# Instalar dependências do Composer se ainda não foram instaladas
# --no-interaction: não pede confirmações
# --prefer-dist: baixa versões compactadas (mais rápido)
# --optimize-autoloader: otimiza o autoloader para produção
if [ ! -f "vendor/autoload.php" ]; then
    composer install --no-interaction --prefer-dist --optimize-autoloader
fi

# Iniciar o servidor Apache em modo foreground (mantém container rodando)
exec apache2-foreground
