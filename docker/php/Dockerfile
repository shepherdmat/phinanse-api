FROM phpdockerio/php:8.5-fpm

RUN apt-get update \
    && apt-get -y --no-install-recommends install \
        git \
        supervisor \
        php8.5-bcmath \
        php8.5-bz2 \
        php8.5-http \
        php8.5-mcrypt \
        php8.5-memcache \
        php8.5-memcached \
        php8.5-mysql \
        php8.5-pspell \
        php8.5-raphf \
        php8.5-redis \
        php8.5-sqlite3 \
        php8.5-uuid \
        php8.5-xdebug \
        php8.5-xmlrpc \
        php8.5-yaml \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/*

RUN mkdir -p /var/log/supervisor /etc/supervisor/conf.d

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY supervisord.conf /etc/supervisor/supervisord.conf

WORKDIR /var/www/html

CMD ["/usr/bin/supervisord", "-n", "-c", "/etc/supervisor/supervisord.conf"]
