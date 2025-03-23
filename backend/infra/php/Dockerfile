FROM hyperf/hyperf:8.1-alpine-v3.18-swoole

RUN apk update && apk add --no-cache php81-dev \ 
    php81-pear \
    gcc \
    g++ \
    make \
    autoconf \
    linux-headers \
    && pecl install pcov \
    && echo "extension=pcov.so" > /etc/php81/conf.d/pcov.ini

ENV PCOV_ENABLED=1

WORKDIR /var/www