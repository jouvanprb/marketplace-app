#!/bin/bash
set -e

cd /var/www/html

# 1. Pastikan Composer Install berjalan PERTAMA KALI
echo "Menginstall dependency Composer..."
if [ -f composer.json ]; then
    composer install --optimize-autoloader --no-interaction --no-dev
fi

# 2. Buat dan Sesuaikan .env dari .env.example
echo "Menyesuaikan .env dengan environment Docker..."
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Timpa nilai DB di .env
sed -i "s/^DB_CONNECTION=.*/DB_CONNECTION=${DB_CONNECTION:-mysql}/" .env
sed -i "s/^#* *DB_HOST=.*/DB_HOST=${DB_HOST:-db}/" .env
sed -i "s/^#* *DB_PORT=.*/DB_PORT=3306/" .env
sed -i "s/^#* *DB_DATABASE=.*/DB_DATABASE=${DB_DATABASE:-marketplace_db}/" .env
sed -i "s/^#* *DB_USERNAME=.*/DB_USERNAME=${DB_USERNAME:-marketplace_user}/" .env
sed -i "s/^#* *DB_PASSWORD=.*/DB_PASSWORD=${DB_PASSWORD:-secret_password}/" .env

# 3. Generate APP_KEY jika belum ada
if ! grep -q "^APP_KEY=base64:" .env; then
    echo "Generating Application Key..."
    php artisan key:generate --force
fi

# 4. Atur Permission Folder
echo "Mengatur permission..."
mkdir -p storage/logs storage/framework/{cache,sessions,views} bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# 5. Jalankan Migrasi Database
echo "Menjalankan migrasi database..."
php artisan migrate --force

# 6. Eksekusi Perintah Utama (PHP-FPM)
echo "Inisialisasi selesai. Menjalankan PHP-FPM..."
exec "$@"