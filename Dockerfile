# STAGE 1: Install Dependency (The "Cook")
FROM php:8.3-fpm-alpine AS builder

# Install system dependencies
RUN apk add --no-cache \
    zip \
    libzip-dev \
    libpng-dev \
    oniguruma-dev \
    icu-dev

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql zip gd intl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

# Install dependencies & optimize
RUN composer install --no-dev --optimize-autoloader --no-scripts

RUN php artisan package:discover --ansi

# ---------------------------------------------------------

# STAGE 2: Production Image (The "Box")
FROM php:8.3-fpm-alpine

WORKDIR /var/www/html

# Copy extensions dari builder
RUN apk add --no-cache \
    libzip \
    libpng \
    icu-libs

RUN docker-php-ext-install pdo_mysql

# Copy kodingan dan vendor dari stage builder
COPY --from=builder /app /var/www/html

# Set Permission (Sesuai kebutuhan Laravel)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port fpm
EXPOSE 9000

CMD ["php-fpm"]