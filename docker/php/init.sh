#!/bin/bash
set -e

cd /var/www/html

# 1. Install dependency Composer hanya jika folder vendor belum ada
if [ ! -d "vendor" ] && [ -f "composer.json" ]; then
    echo "Installing Composer dependencies..."
    composer install --optimize-autoloader --no-interaction --no-dev
fi

# 2. Pastikan file .env tersedia
if [ ! -f .env ]; then
    echo "Creating .env from .env.example..."
    cp .env.example .env
fi

# 3. Generate APP_KEY jika belum terkonfigurasi
if ! grep -q "^APP_KEY=base64:" .env 2>/dev/null; then
    echo "Generating Application Key..."
    php artisan key:generate --force
fi

# 4. Atur Permission Folder Storage dan Cache
echo "Setting folder permissions..."
mkdir -p storage/logs storage/framework/{cache,sessions,views} bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# 5. WAITING FOR MYSQL (Menunggu Database Siap Menggunakan PDO)
echo "Waiting for MySQL database connection..."
until php -r "
    try {
        \$pdo = new PDO('mysql:host=${DB_HOST:-mysql};port=${DB_PORT:-3306}', '${DB_USERNAME:-marketplace_user}', '${DB_PASSWORD:-secret_password}');
        exit(0);
    } catch (Exception \$e) {
        exit(1);
    }
" 2>/dev/null; do
    echo "MySQL is not ready yet - sleeping 3 seconds..."
    sleep 3
done
echo "MySQL connection established!"

# 6. Clear Config Cache & Jalankan Migrasi Database
echo "Clearing cache & running database migrations..."
php artisan config:clear
php artisan migrate --force

# 7. Eksekusi Perintah Utama (PHP-FPM)
echo "Initialization complete. Starting PHP-FPM..."
exec "$@"