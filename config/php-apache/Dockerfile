FROM php:7.4-apache
RUN apt-get update && apt-get upgrade -y && apt-get install -y apt-utils libonig-dev
RUN docker-php-ext-install pdo pdo_mysql exif
RUN ["/bin/bash", "-c", "a2enmod rewrite"]
RUN ["/bin/bash", "-c", "service apache2 restart"]

COPY ./php.ini /usr/local/etc/php
