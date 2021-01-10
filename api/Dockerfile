ARG api
FROM existenz/webstack:7.3

ENV COMPOSER_ALLOW_SUPERUSER 1

COPY ./.docker/nginx.conf /etc/nginx/nginx.conf

#VOLUME ./ /www

WORKDIR /www

# chown -R php:nginx /www \
#    && find /www -type d -exec chmod -R 555 {} \; \
#    && find /www -type f -exec chmod -R 444 {} \; \
#    && find /www/public -type d -exec chmod -R 755 {} \; \
#    && find /www/public -type f -exec chmod -R 644 {} \; \
#    &&
RUN chown -R php:nginx /www \
    && apk -U --no-cache add \
    php7-pear \
    php7-json \
    php7-ctype \
    php7-openssl \
    php7-mbstring \
    php7-common \
    php7-session \
    php7-pecl-redis \
    php7-pecl-xdebug  \
    php7-phar \
    php7-zip \
    php7-dom \
    php7-xmlwriter \
    php7-fileinfo \
    php7-zlib \
    php7-tokenizer \
    php7-pdo \
    php7-pdo_mysql \
    php7-pdo_sqlite \
    php7-iconv \
    && echo "zend_extension=/usr/lib/php7/modules/xdebug.so" > /etc/php7/conf.d/xdebug.ini \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && rm -rf /root/.composer/cache
