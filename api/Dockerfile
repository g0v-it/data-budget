FROM composer as build-stage

COPY ./ /app
RUN composer install --no-dev

FROM php:7.2-cli

COPY --from=build-stage /app /app
WORKDIR /app

CMD php -S "0.0.0.0:8000" index.php
