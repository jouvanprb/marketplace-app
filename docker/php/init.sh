#!/bin/bash
set -e

# Entrypoint untuk container PHP-FPM Laravel

cd /var/www/html

# 1. Cek apakah Laravel sudah ada, jika belum install otomatis
if [ ! -f /var/www/html/composer.json ]; then
  echo "Laravel belum ada, melakukan instalasi..."
  composer create-project laravel/laravel="13.*" .
else
  echo "Laravel sudah terpasang, skip instalasi."
fi

# 2. Sesuaikan .env dengan environment variable dari Docker
echo "Menyesuaikan .env dengan environment Docker..."

# Jika .env belum ada, copy dari .env.example
if [ ! -f .env ]; then
    cp .env.example .env
    php artisan key:generate
fi

sed -i "s/^DB_CONNECTION=.*/DB_CONNECTION=${DB_CONNECTION}/" .env
sed -i "s/^#* *DB_HOST=.*/DB_HOST=${DB_HOST}/" .env
sed -i 's/^#* *DB_PORT=.*/DB_PORT=3306/' .env
sed -i "s/^#* *DB_DATABASE=.*/DB_DATABASE=${DB_DATABASE}/" .env
sed -i "s/^#* *DB_USERNAME=.*/DB_USERNAME=${DB_USERNAME}/" .env
sed -i "s/^#* *DB_PASSWORD=.*/DB_PASSWORD=${DB_PASSWORD}/" .env

echo "Isi .env (DB only):"
grep ^DB_ .env

# 3. Atur permission storage & cache
echo "Mengatur permission..."

# Tentukan user ID jika ada, fallback ke www-data
USER_ID=${USER_ID:-www-data}
GROUP_ID=${GROUP_ID:-www-data}

mkdir -p storage/logs storage/framework/{cache,sessions,views} bootstrap/cache
touch storage/logs/laravel.log

chown -R ${USER_ID}:${GROUP_ID} /var/www/html
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# 4. Install dependency & optimasi Laravel
echo "Menginstall dependency Composer..."
composer install --optimize-autoloader --no-interaction --no-dev


# 5. Ambil APP_ENV untuk menentukan langkah selanjutnya
APP_ENV=$(grep ^APP_ENV= .env | cut -d '=' -f2 | tr -d '\r')
if [[ -z "$APP_ENV" ]]; then
    APP_ENV=local
fi
echo "Environment: $APP_ENV"

# 6. Jalankan migrasi database (hati-hati di production!)
echo "Menjalankan migrasi database..."
php artisan migrate --force

# 7. Cache/clear sesuai environment
if [ "$APP_ENV" = "production" ]; then
    echo "Mode production: caching config, route, view..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
else
    echo "Mode development: membersihkan cache..."
    php artisan config:clear
    php artisan cache:clear
    php artisan view:clear
    php artisan route:clear
fi

echo "Inisialisasi selesai. Menjalankan PHP-FPM..."

# 8. Eksekusi perintah utama (CMD dari Dockerfile, yaitu php-fpm)
exec "$@"