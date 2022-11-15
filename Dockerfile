ARG TARGET_PHP_VERSION=8.1
FROM php:${TARGET_PHP_VERSION}-fpm

RUN apt-get update -yqq && apt-get -f install -yyq wget

RUN wget -q -O /usr/local/bin/install-php-extensions https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions \
    || (echo "Failed while downloading php extension installer!"; exit 1)

RUN chmod uga+x /usr/local/bin/install-php-extensions && install-php-extensions @composer-2.0.2

COPY . /var/www/myapp
WORKDIR /var/www/myapp