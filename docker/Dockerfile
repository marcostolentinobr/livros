# ============================================
# DOCKERFILE - IMAGEM PHP + APACHE
# ============================================
# Define a imagem Docker que será usada pelo container PHP
# Base: php:8.2-apache (PHP 8.2 com Apache pré-instalado)

FROM php:8.2-apache

# Instalar dependências do sistema e extensões PHP necessárias
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev \
    && docker-php-ext-configure zip \
    && docker-php-ext-install pdo pdo_mysql zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Habilitar mod_rewrite do Apache (necessário para URLs limpas via .htaccess)
RUN a2enmod rewrite

# Configurar VirtualHost do Apache
# Define que o diretório 'public/' será a raiz do servidor web
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Instalar Composer (gerenciador de dependências PHP)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar script de entrada e torná-lo executável
# Este script roda automaticamente quando o container inicia
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Definir diretório de trabalho padrão
WORKDIR /var/www/html
