FROM php:8.3-apache

WORKDIR /var/www/html

RUN a2enmod rewrite

COPY public/ /var/www/html/public/
COPY src/ /var/www/html/src/
COPY storage/ /var/www/html/storage/
COPY docker/apache.conf /etc/apache2/sites-available/000-default.conf

RUN chown -R www-data:www-data /var/www/html/storage

EXPOSE 80
