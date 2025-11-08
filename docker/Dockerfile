FROM php:8.2-apache

# Instala dependências e extensões PHP
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev \
    && docker-php-ext-configure zip \
    && docker-php-ext-install pdo pdo_mysql zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Habilita mod_rewrite para URLs amigáveis
RUN a2enmod rewrite

# Configura VirtualHost apontando para pasta public/
# AllowOverride All permite uso de .htaccess
RUN echo '<VirtualHost *:80>\n\
    DocumentRoot /var/www/html/public\n\
    <Directory /var/www/html/public>\n\
        AllowOverride All\n\
        Require all granted\n\
    </Directory>\n\
</VirtualHost>' > /etc/apache2/sites-available/000-default.conf

# Copia Composer da imagem oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copia e torna executável script de inicialização
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

WORKDIR /var/www/html
