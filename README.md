# laravel-docker
Setup Docker environment untuk Laravel dengan Nginx, PHP 8.4, MySQL 8, dan phpMyAdmin.

## Detail Skema
Ini adalah skema docker compose yang bisa digunakan untuk menjalankan Laravel. Beberapa program yang digunakan adalah:
1. Nginx (Alpine latest)
2. PHP 8.4 (FPM)
3. Laravel Versi 13
4. MySQL 8.0

## Cara Menggunakan
1. Pastikan anda sudah menginstall docker dan docker-compose di komputer anda.
2. Download atau clone repository ini.
3. Buka terminal dan masuk ke direktori tempat anda menyimpan file ini.
4. Jalankan perintah berikut untuk membangun dan menjalankan container:
```bash
docker compose up -d --build
```
5. Tunggu proses build dan inisialisasi selesai. Container akan otomatis:
- Menginstall Laravel jika belum ada
- Mengatur environment database
- Menjalankan migrasi database
6. Setelah container berjalan, buka browser anda dan akses Aplikasi Laravel: [http://localhost:8000](http://localhost:8000)
