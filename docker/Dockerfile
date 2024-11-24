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
    supervisor \
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

# Copy entrypoint script, supervisord configuration, and nginx configuration
COPY ./docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

COPY ./docker/nginx.conf /etc/nginx/http.d/default.conf
COPY ./docker/supervisord.conf /etc/supervisord.conf

# Expose port 80
EXPOSE 80

# Define entrypoint command
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
