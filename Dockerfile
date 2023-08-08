FROM php:8.2.8-fpm-alpine3.18

EXPOSE 80

WORKDIR /

RUN apk update \
    && apk add --no-cache \
        ca-certificates \
        curl \
        nginx \
        openssl \
        supervisor \
        tar \
        xz \
        libjpeg-turbo-dev \
        libpng-dev \
        libwebp-dev \
        freetype-dev \
        # 郵件功能
        # imap-dev \
        # SOAP 相關功能
        # krb5-dev \
        # libressl-dev \
        # libxml2-dev \
        # ZIP 功能
        # libzip-dev \
    && rm -rf /var/cache/apk/* \
    # 郵件功能
    # && docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
    && docker-php-ext-install \
        gd \
        pdo_mysql \
        mysqli \
        # 郵件功能
        # imap \
        # SOAP 相關功能
        # soap \
        # ZIP 功能
        # zip \
    && mkdir /var/run/php

WORKDIR /usr/share/nginx/html

COPY ./ ./

COPY ./.infrastructures/php-fpm/php.conf /usr/local/etc/php/php.ini
COPY ./.infrastructures/php-fpm/php-fpm.conf /usr/local/etc/php-fpm.conf
COPY ./.infrastructures/php-fpm/99-www.conf /usr/local/etc/php-fpm.d/99-www.conf
COPY ./.infrastructures/nginx/default.conf /etc/nginx/nginx.conf
COPY ./.infrastructures/nginx/nginx-custom.conf /etc/nginx/conf.d/default.conf
COPY ./.infrastructures/supervisord/supervisord.conf /etc/supervisord.conf

RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer \
    && cp .env.example .env \
    && composer install --no-dev --no-scripts \
    && composer clear-cache \
    && sed -i "/TinkerServiceProvider::class/d" ./bootstrap/app.php \
    && chown -R $USER:www-data . \
    && find . -type f -exec chmod 644 {} \; \
    && find . -type d -exec chmod 755 {} \; \
    && chgrp -R www-data storage \
    && chmod -R ug+rwx storage \
    && php artisan key:generate \
    && php artisan swagger-lume:generate \
    # 移除 key 產生器與開發用伺服器指令以避免安全性問題
    && rm ./app/Console/Commands/ApplicationKeyGenerator.php \
    && rm ./app/Console/Commands/DevelopmentServer.php \
    && sed -i "/ApplicationKeyGenerator::class/d" ./app/Console/Kernel.php \
    && sed -i "/DevelopmentServer::class/d" ./app/Console/Kernel.php \
    && sed -i "/operationId/d" ./storage/api-docs/api-docs.json \
    && rm -rf ./.infrastructures \
    && rm -f .env.example \
    && rm -f ./config/tinker.php \
    && composer dump-autoload \
    && rm -rf /var/cache/apk/* \
    && rm -f /usr/local/bin/composer

ENTRYPOINT ["supervisord", "-n", "-c", "/etc/supervisord.conf"]
