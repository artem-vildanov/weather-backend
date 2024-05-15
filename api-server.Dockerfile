FROM php:8.2-fpm

RUN apt update && apt install -y openssl

RUN docker-php-ext-install pdo pdo_mysql opcache