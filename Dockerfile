FROM php:7.2-alpine

RUN apk add --no-cache postgresql-dev libmcrypt-dev && \
    docker-php-ext-install pgsql

COPY [ "./src", "/usr/src/app" ]

WORKDIR /usr/src/app

CMD [ "php", "-S", "0.0.0.0:80" ]
