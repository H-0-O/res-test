FROM php:8.3-fpm-alpine3.20 AS build


RUN apk update

RUN apk update && apk add --no-cache \
    git \
    unzip \
    libpng-dev \
    libjpeg-turbo-dev \
    libxml2-dev \
    freetype-dev \
    linux-headers \
    libzip-dev \
    zip \
    zlib-dev \
    oniguruma-dev \
    autoconf \
    g++ \
    make



RUN docker-php-ext-install \
    pdo \
    pdo_mysql \
    mysqli \
    sockets \
    gd \
    zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


WORKDIR /var/www/html

# Stage 2

FROM build AS fpm-runner

EXPOSE 9000

ENTRYPOINT [ "php-fpm" ]
  