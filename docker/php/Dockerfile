FROM composer:2.10 AS composer

FROM php:8.4-fpm-bookworm

ENV COMPOSER_ALLOW_SUPERUSER=1 \
    COMPOSER_MEMORY_LIMIT=-1

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        curl \
        default-mysql-client \
        git \
        iproute2 \
        jq \
        less \
        libcurl4-openssl-dev \
        libfreetype6-dev \
        libicu-dev \
        libjpeg62-turbo-dev \
        libonig-dev \
        libpng-dev \
        libxml2-dev \
        libxslt1-dev \
        libzip-dev \
        procps \
        unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        bcmath \
        curl \
        dom \
        ftp \
        gd \
        intl \
        mbstring \
        opcache \
        pcntl \
        pdo_mysql \
        simplexml \
        soap \
        sockets \
        xsl \
        zip \
    && pecl install redis xdebug \
    && docker-php-ext-enable redis xdebug \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/pear

COPY --from=composer /usr/bin/composer /usr/local/bin/composer
COPY docker/php/php.ini /usr/local/etc/php/conf.d/99-learning.ini

WORKDIR /workspace
