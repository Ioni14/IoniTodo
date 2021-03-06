FROM php:7.4-fpm-buster as req_php

RUN apt-get update && apt-get install -y \
    unzip \
    libzip-dev \
    libxml2-dev \
    libxslt-dev \
    libgcrypt-dev \
    libpq-dev \
    librabbitmq-dev \
    && docker-php-ext-install -j$(nproc) intl zip pdo_pgsql xml xsl bcmath \
    && pecl channel-update pecl.php.net \
    && pecl install redis apcu amqp \
    && apt-get remove -y icu-devtools libicu-dev libzip-dev libxml2-dev libxslt-dev libgcrypt-dev libpq-dev librabbitmq-dev \
    && rm -rf /var/lib/apt/lists/* \
    && docker-php-ext-enable --ini-name 05-opcache.ini opcache \
    && docker-php-ext-enable --ini-name 10-apcu.ini apcu \
    && docker-php-ext-enable --ini-name 20-redis.ini redis \
    && docker-php-ext-enable --ini-name 20-amqp.ini amqp \
    && docker-php-source delete \
    && rm -rf /tmp/*

RUN mkdir -p /app /.composer && chown -R www-data:www-data /app /.composer
USER www-data

WORKDIR /app

########################################
FROM req_php as composer_deps

COPY --from=composer:2.0 /usr/bin/composer /usr/local/bin/composer

# prod|dev|test
ARG ENV=prod

COPY --chown=www-data:www-data src src
COPY --chown=www-data:www-data .env* composer.json composer.lock symfony.lock ./

ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_MEMORY_LIMIT -1
ENV COMPOSER_HOME /.composer
ENV APP_ENV $ENV
RUN if [ $ENV = 'prod' ]; then \
        composer install --no-scripts --no-suggest --optimize-autoloader --classmap-authoritative --prefer-dist --no-progress --no-interaction --no-dev; \
    else \
        composer install --no-scripts --no-suggest --optimize-autoloader --classmap-authoritative --prefer-dist --no-progress --no-interaction; \
    fi; \
    composer dump-env $ENV

####################################
FROM req_php as build_php

# prod|dev|test
ARG ENV=prod
ARG DUMP_ENV=0

USER root
COPY --chown=root:root docker/php/php.prod.ini /usr/local/etc/php/php-fpm.ini
COPY --chown=root:root docker/php/php-cli.prod.ini /usr/local/etc/php/php-cli.ini
COPY --chown=root:root docker/php/php-fpm.prod.conf /usr/local/etc/php-fpm.conf
COPY --chown=root:root docker/php/www.prod.conf /usr/local/etc/php-fpm.d/www.conf
COPY --chown=root:root docker/php/entrypoint.sh /usr/local/bin/docker-app-entrypoint
RUN chmod +x /usr/local/bin/docker-app-entrypoint

USER www-data
COPY --chown=www-data:www-data bin bin
COPY --chown=www-data:www-data config config
COPY --chown=www-data:www-data public/index.php public/
COPY --chown=www-data:www-data src src
COPY --chown=www-data:www-data templates templates
COPY --chown=www-data:www-data .env* composer.json composer.lock ./
COPY --chown=www-data:www-data --from=composer_deps /app/vendor vendor
COPY --chown=www-data:www-data --from=composer_deps /app/.env.local.php .env.local.php

RUN if [ ${DUMP_ENV} -eq 0 ]; then echo "<?php return ['APP_ENV'=>'${ENV}','APP_SECRET'=>''];" > .env.local.php; fi; \
    sed -i "s/^APP_ENV=.*$/APP_ENV=${ENV}/" .env; \
    if [ -e .env.local ]; then sed -i "s/^APP_ENV=.*$/APP_ENV=${ENV}/" .env.local; fi
RUN mkdir -p public/bundles \
    && rm -rf var/cache/* \
    && bin/console cache:warmup -v \
    && mkdir -p var/log

ENTRYPOINT ["docker-app-entrypoint"]
CMD ["php-fpm"]

RUN ls -la /app

##############################
FROM httpd:2.4 as build_apache

ENV APACHE_RUN_USER www-data
ENV APACHE_RUN_GROUP www-data

WORKDIR /app

RUN apt-get update && apt-get install -y curl \
    && rm -rf /var/lib/apt/lists/* \
    && rm -rf /tmp/* \
    && mkdir -p public && chown -R www-data:www-data public

COPY docker/apache/httpd.conf /usr/local/apache2/conf/httpd.conf

USER www-data
RUN touch public/index.php
COPY --chown=www-data:www-data --from=build_php /app/public/bundles public/bundles

USER root

###############################
FROM build_php as build_php_dev

# dev|test
ARG ENV=dev

USER root
COPY --from=composer:2.0 /usr/bin/composer /usr/local/bin/composer
ENV COMPOSER_MEMORY_LIMIT -1
ENV COMPOSER_ALLOW_SUPERUSER 1
ENV COMPOSER_HOME /.composer

COPY --chown=root:root docker/php/php.dev.ini /usr/local/etc/php/php-fpm.ini
COPY --chown=root:root docker/php/php-cli.dev.ini /usr/local/etc/php/php-cli.ini
COPY --chown=root:root docker/php/php-fpm.dev.conf /usr/local/etc/php-fpm.conf
COPY --chown=root:root docker/php/www.dev.conf /usr/local/etc/php-fpm.d/www.conf

# Xdebug install
ARG XDEBUG_VERSION=2.9.2
RUN set -eux; \
    apt-get update && apt-get install -y $PHPIZE_DEPS chromium git \
	&& pecl install xdebug-$XDEBUG_VERSION \
    && apt-get remove -y $PHPIZE_DEPS \
    && rm -rf /var/lib/apt/lists/* /tmp/*

ENV PANTHER_NO_SANDBOX 1

USER www-data
COPY --chown=www-data:www-data tests tests
COPY --chown=www-data:www-data phpunit.xml.dist phpunit.xml.dist
#COPY --chown=www-data:www-data public/index_test.php public/

# PHPUnit install
RUN if [ $ENV = 'test' ]; then /app/bin/phpunit --version; fi
