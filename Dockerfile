FROM php:8.3.14-fpm

RUN apt-get update && \
    apt-get install -y libpq-dev libzip-dev && \
    docker-php-ext-install zip calendar pdo pdo_pgsql && \
    apt-get clean && rm -rf /var/lib/apt/lists/*