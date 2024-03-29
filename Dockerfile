FROM php:5.6-apache

RUN buildDeps='gcc python3-dev' \
    && apt update \
    && apt install -y --no-install-recommends python3 python3-pip \
    && apt install -y cron \
    && apt-get install wget \
    && apt install -y --no-install-recommends $buildDeps \
    && pip3 install wheel setuptools \
    && docker-php-ext-install mysqli \
    && apt purge -y --auto-remove $buildDeps \
    && rm -rf /var/lib/apt/lists/* \
    && rm -rf /root/.cache

COPY php.ini $PHP_INI_DIR/php.ini

COPY . /var/www/html/
RUN  mkdir -p /var/www/html/logs && chown -R www-data:www-data /var/www/html

ENTRYPOINT /var/www/html/scripts/entrypoint.sh