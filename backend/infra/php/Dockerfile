FROM hyperf/hyperf:8.1-alpine-v3.18-swoole


RUN apk update && apk add --no-cache \
    autoconf \
    g++ \
    gcc \
    linux-headers \
    make \
    php81-dev \
    php81-pear \
    && pecl install pcov \
    && echo "extension=pcov.so" > /etc/php81/conf.d/pcov.ini

ENV PCOV_ENABLED=1

WORKDIR /var/www