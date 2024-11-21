FROM php:8.3-fpm-alpine

# Install required system dependencies and PHP extensions
RUN apk add --no-cache \
    zip \
    libzip-dev \
    freetype \
    libjpeg-turbo \
    libpng \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    nodejs \
    npm \
    sqlite-dev \
    nginx \
    gettext-dev \
    && docker-php-ext-configure zip \
    && docker-php-ext-install zip pdo pdo_mysql pdo_sqlite \
    && docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-enable gd \
    && docker-php-ext-install bcmath exif gettext opcache \
    && docker-php-ext-enable bcmath exif gettext opcache \
    && rm -rf /var/cache/apk/*

# Install Composer
COPY --from=composer:2.7.6 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . .

# Set appropriate permissions for storage and cache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/vendor

# Install PHP and Node.js dependencies
RUN COMPOSER_ALLOW_SUPERUSER=1 composer install --prefer-dist \
    && npm install \
    && npm run build

# Copy Nginx configuration
COPY ./docker/nginx.conf /etc/nginx/http.d/default.conf

# Expose port 80
EXPOSE 80

# Define entrypoint command
CMD ["sh", "-c", "nginx && php-fpm"]
