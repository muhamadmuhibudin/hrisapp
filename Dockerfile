FROM php:8.2-cli

# Install system dependencies + libpq-dev for PostgreSQL & Node.js for Vite
RUN apt-get update && apt-get install -y \
    unzip \
    libzip-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libpq-dev \
    zip \
    curl \
    git \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo_mysql pdo_pgsql pgsql mbstring zip exif pcntl gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Install PHP dependencies & build Vite frontend assets during the build stage
RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader \
    && npm ci && npm run build \
    && rm -rf node_modules

RUN chmod -R 775 storage bootstrap/cache

CMD php artisan optimize:clear && php artisan config:cache && php -S 0.0.0.0:${PORT:-8080} -t public
