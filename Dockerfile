FROM php:7.2.2-apache
COPY config/apache-security.conf /etc/apache2/conf-enabled/security.conf

RUN a2enmod headers

RUN docker-php-ext-install mysqli pdo pdo_mysql

COPY src/ /var/www/html/

RUN chown -R www-data:www-data /var/www/html

RUN a2enmod rewrite


RUN printf '%s\n' \
'ServerSignature Off' \
'ServerTokens Prod' \
'ServerName localhost' \
'' \
'<IfModule mod_headers.c>' \
'    Header always unset X-Powered-By' \
'    Header always set X-Content-Type-Options "nosniff"' \
'</IfModule>' \
> /etc/apache2/conf-available/hide_server.conf \
&& a2enconf hide_server
