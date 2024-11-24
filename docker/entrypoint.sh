#!/bin/sh

set -e

# Set working directory
cd /var/www/html

# Copy .env.example to .env if .env does not exist
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Install composer dependencies if vendor directory is empty
if [ ! -d vendor ] || [ -z "$(ls -A vendor)" ]; then
    composer install --prefer-dist --no-progress --no-suggest --no-interaction
fi

# Install npm dependencies if node_modules directory is empty
if [ ! -d node_modules ] || [ -z "$(ls -A node_modules)" ]; then
    npm install --silent
fi

# Generate application key if APP_KEY is not set
if ! grep -q "^APP_KEY=" .env || [ -z "$(grep '^APP_KEY=' .env | cut -d '=' -f2)" ]; then
    php artisan key:generate --ansi
fi

# Check if database/database.sqlite exists, if not create it
if [ ! -f database/database.sqlite ]; then
    mkdir -p database
    touch database/database.sqlite
fi

chmod 664 database/database.sqlite
chown -R www-data:www-data database
chmod -R 775 database

# Set appropriate permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Run migrations unconditionally
php artisan migrate --force

# Check if the database has any tables
DB_TABLES=$(sqlite3 database/database.sqlite ".tables" || echo "")

if [ -z "$DB_TABLES" ]; then
    echo "Database is empty. Running seeders..."
    php artisan db:seed --force
else
    echo "Database already seeded. Skipping seeding."
fi

# Build npm for Vue
npm run build --silent

# Start supervisord
exec /usr/bin/supervisord -c /etc/supervisord.conf
