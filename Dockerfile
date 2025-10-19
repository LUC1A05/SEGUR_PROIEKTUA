FROM php:7.2.2-apache

# PHP egiteko
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Aplikazioa kopiatu
COPY src/ /var/www/html/

# Apacheri baimen egokiak eman
RUN chown -R www-data:www-data /var/www/html

# Rewrite egokitu
RUN a2enmod rewrite

