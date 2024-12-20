# Используем базовый образ PHP
FROM php:8.3.14-fpm

# Устанавливаем необходимые пакеты и расширения
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*