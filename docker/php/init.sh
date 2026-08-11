#!/bin/bash
set -e

cd /var/www/html

# 1. Install Composer dependencies
if [ ! -d "vendor" ] && [ -f "composer.json" ]; then
    echo "Installing Composer dependencies..."
    composer install --optimize-autoloader --no-interaction --no-dev
fi

# 2. Frontend build
if [ -f "package.json" ]; then
    echo "Installing & building frontend assets..."
    npm ci --no-audit --no-fund
    npm run build
fi

# 3. Setting folder permissions
echo "Setting folder permissions..."
mkdir -p storage/logs storage/framework/{cache,sessions,views} bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# 4. Wait for MySQL (without hardcoded fallback)
echo "Waiting for MySQL database connection..."
until php -r "
    try {
        \$pdo = new PDO('mysql:host=${DB_HOST:?err};port=${DB_PORT:-3306}', '${DB_USERNAME:?err}', '${DB_PASSWORD:?err}');
        exit(0);
    } catch (Exception \$e) {
        exit(1);
    }
" 2>/dev/null; do
    echo "MySQL is not ready yet - sleeping 3 seconds..."
    sleep 3
done
echo "MySQL connection established!"

# 5. Clear cache & run migrations
echo "Clearing cache & running database migrations..."
php artisan config:clear
php artisan migrate --force

# 6. Start PHP-FPM
echo "Initialization complete. Starting PHP-FPM..."
exec "$@"