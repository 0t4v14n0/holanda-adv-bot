# Usar a imagem oficial do PHP com Apache
FROM php:8.0-apache

# Instalar extensões do PHP, incluindo mysqli
RUN docker-php-ext-install mysqli

# Instalar extensões necessárias
RUN docker-php-ext-install pdo pdo_mysql

# Copiar o código da aplicação para o diretório do Apache
COPY . /var/www/html/

# Ajustar permissões, se necessário
RUN chown -R www-data:www-data /var/www/html