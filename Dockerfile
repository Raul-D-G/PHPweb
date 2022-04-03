FROM php:7.4-fpm-alpine

RUN apk update && apk add \
    build-base \
    curl \
    vim

RUN apk add bash
RUN sed -i 's/bin\/ash/bin\/bash/g' /etc/passwd

RUN addgroup -g 1000 -S www && \
    adduser -u 1000 -S www -G www

WORKDIR /var

COPY composer.json /var

RUN curl -s http://getcomposer.org/installer | php

RUN php composer.phar install

COPY --chown=www:www . /var

USER www

WORKDIR /var/www
COPY --chown=www:www . /var/www

EXPOSE 9000