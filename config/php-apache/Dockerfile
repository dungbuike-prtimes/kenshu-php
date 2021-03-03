FROM php:7.4-apache
RUN apt-get update && apt-get upgrade -y
RUN docker-php-ext-install pdo pdo_mysql
RUN ["/bin/bash", "-c", "a2enmod rewrite"]
RUN ["/bin/bash", "-c", "service apache2 restart"]

COPY ./php.ini /usr/local/etc/php
