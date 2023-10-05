#!/bin/bash

FROM --platform=linux/amd64 node:14 as node

WORKDIR /app

FROM --platform=linux/amd64 php:8.2-fpm as base

WORKDIR /app

RUN apt-get update \
    && apt-get install -y unzip git libfcgi0ldbl gettext

# php-opcache
RUN docker-php-ext-install opcache \
    && docker-php-ext-enable opcache

# php-pgsql
RUN apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# php-gd
RUN apt-get install -y libpng-dev \
    && docker-php-ext-install gd

# php-intl
RUN apt-get install -y libicu-dev \
    && docker-php-ext-install intl

# php-zip
RUN apt-get install -y libzip-dev \
    && docker-php-ext-install zip

# php-memcached
RUN apt-get install -y libzip-dev libmemcached-dev \
    && pecl install memcached \
    && docker-php-ext-enable memcached

# php-redis
RUN pecl install redis \
    && docker-php-ext-enable redis

# php-bcmath
RUN docker-php-ext-install \
    bcmath \
    sockets

# php-amqp
RUN apt-get install -y librabbitmq-dev \
    && pecl install amqp \
    && docker-php-ext-enable amqp

# composer
RUN curl --silent --show-error https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --2

FROM base as modules

COPY --from=node /usr/local/lib/node_modules /usr/local/lib/node_modules
COPY --from=node /usr/local/bin/node /usr/local/bin/node
COPY --from=node /opt /opt
RUN ln -s /usr/local/lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm \
    && yarn_dir=$(ls -d /opt/yarn*) \
    && ln -sf ${yarn_dir}/bin/yarn /usr/local/bin/yarn \
    && ln -sf ${yarn_dir}/bin/yarnpkg /usr/local/bin/yarnpkg

ARG run_env

COPY . .

COPY docker/production/php/php.ini /usr/local/etc/php/php.ini
COPY docker/production/php/.env.local /app/.env.local

RUN composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction --no-scripts \
    && bin/console cache:clear \
    && php bin/console assets:install \
    && yarn \
    && yarn encore prod \
    && rm -rf node_modules

FROM modules as app

#FROM base as app
#
#COPY --from=modules /usr/local/bin/composer /usr/local/bin/composer
#COPY --from=modules /usr/local/etc/php/conf.d /usr/local/etc/php/conf.d
#COPY --from=modules /usr/local/lib/php/extensions /usr/local/lib/php/extensions
#COPY --from=modules /app /app
