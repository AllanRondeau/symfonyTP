FROM debian:11

ARG SYSTEM_PROCESSOR=x64

ENV LANG="en_US.UTF-8" \
    LC_ALL="en_US.UTF-8" \
    PHP_VERSION=8.2 \
    NODE_VERSION=20.12.2

WORKDIR /app

RUN apt-get update -q && \
    apt-get install -qy software-properties-common locales locales-all && \
    export LC_ALL=en_US.UTF-8 && \
    export LANG=en_US.UTF-8 && \
    apt-get update -q && \
    apt-get install --no-install-recommends -qy \
    wget \
    gnupg \
    build-essential \
    apt-transport-https \
    curl \
    nano \
    nginx \
    git \
    supervisor

# PHP
RUN curl -sSL -o /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg && \
    echo "deb https://packages.sury.org/php/ bullseye main" | tee /etc/apt/sources.list.d/php.list && \
    apt-get update -q && \
    apt-get install -qy \
    php-pear \
    php${PHP_VERSION} \
    php${PHP_VERSION}-apcu \
    php${PHP_VERSION}-cli \
    php${PHP_VERSION}-mbstring \
    php${PHP_VERSION}-zip \
    php${PHP_VERSION}-curl \
    php${PHP_VERSION}-dom \
    php${PHP_VERSION}-gd \
    php${PHP_VERSION}-mysql \
    php${PHP_VERSION}-fpm \
    php${PHP_VERSION}-intl

RUN apt-get update && apt-get install -qy default-mysql-client
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY docker/php.ini /etc/php/${PHP_VERSION}/cli/conf.d/50-setting.ini
COPY docker/php.ini /etc/php/${PHP_VERSION}/fpm/conf.d/50-setting.ini
COPY docker/pool.conf /etc/php/${PHP_VERSION}/fpm/pool.d/www.conf
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/symfony.conf /etc/nginx/symfony.conf
COPY docker/certificates /etc/nginx/ssl

RUN mkdir -p /app/var/log /app/var/cache /run/php

CMD ["supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
