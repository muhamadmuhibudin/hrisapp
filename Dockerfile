FROM php:8.2-cli

# Install dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    curl \
    git \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-interaction --prefer-dist --optimize-autoloader

RUN chmod -R 777 storage bootstrap/cache

CMD php -S 0.0.0.0:${PORT} -t public