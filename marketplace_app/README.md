# Panduan Instalasi Lokal (Tanpa Docker)

Berkas ini berisi petunjuk langkah-demi-langkah untuk menjalankan aplikasi secara langsung di komputer lokal Anda menggunakan server PHP bawaan, XAMPP/Laragon, Composer, dan Node.js/NPM lokal (tanpa menggunakan Docker).

*Untuk detail arsitektur sistem, skema database (ERD), rincian tabel, dan fitur keamanan, silakan merujuk pada file `README.md` yang berada di direktori root utama proyek.*

---

## 🛠️ Persyaratan Sistem

Sebelum memulai, pastikan komputer Anda telah terpasang:
*   **PHP** (Minimal versi 8.3)
*   **Composer** (Manajer dependensi PHP)
*   **Node.js & NPM** (Untuk kompilasi aset frontend)
*   **MySQL atau MariaDB** (Melalui XAMPP, Laragon, atau instalasi mandiri)

---

## 🚀 Langkah-Langkah Jalankan Aplikasi

#### 1. Masuk ke Folder Aplikasi
Buka terminal atau command prompt Anda, lalu arahkan direktori aktif ke dalam folder `marketplace_app` ini.

#### 2. Install Dependensi PHP & Frontend
Jalankan perintah berikut secara berurutan untuk memasang semua pustaka yang dibutuhkan:
```bash
# Mengunduh library backend PHP (Laravel)
composer install

# Mengunduh paket aset frontend (Tailwind, Alpine, dll)
npm install
```

#### 3. Konfigurasi Environment File
Salin file template `.env.example` untuk membuat file konfigurasi `.env` Anda sendiri:
```bash
cp .env.example .env
```
Buka file `.env` yang baru dibuat menggunakan text editor (seperti VS Code atau Notepad), kemudian sesuaikan baris koneksi database lokal Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nama_database_lokal_anda
DB_USERNAME=root
DB_PASSWORD=password_database_anda
```
*Catatan: Pastikan Anda telah membuat satu database kosong dengan nama yang sama seperti di `DB_DATABASE` di dalam MySQL/phpMyAdmin Anda.*

#### 4. Konfigurasi Pembayaran Midtrans
Pada file `.env` yang sama, masukkan Server Key & Client Key dari akun Midtrans Sandbox Anda agar fitur checkout dapat berfungsi:
```env
MIDTRANS_MERCHANT_ID=merchant_id_anda
MIDTRANS_CLIENT_KEY=client_key_anda
MIDTRANS_SERVER_KEY=server_key_anda
MIDTRANS_IS_PRODUCTION=false
```

#### 5. Generate Key & Migrasi Database
Jalankan perintah di bawah ini untuk membuat kunci keamanan Laravel dan membuat seluruh tabel database beserta data game awal secara otomatis:
```bash
# Membuat key keamanan aplikasi
php artisan key:generate

# Menjalankan migrasi struktur database dan mengisi seeder data game
php artisan migrate:fresh --seed
```
*Setelah migrasi selesai, Anda dapat login ke halaman pengelola menggunakan akun administrator default berikut:*
*   **Email Admin:** `admin@gmail.com`
*   **Password:** `password`

#### 6. Kompilasi Aset Frontend
Lakukan kompilasi file styling CSS dan JavaScript agar halaman web dapat ditampilkan dengan benar di browser:
```bash
# Untuk mode pengembangan (Live reload)
npm run dev

# ATAU compile permanen untuk deployment
npm run build
```

#### 7. Jalankan Server Lokal
Nyalakan server internal Laravel untuk menjalankan aplikasi web:
```bash
php artisan serve
```

Aplikasi Anda kini sudah aktif dan dapat diakses melalui browser pada alamat:
*   **Halaman Toko / Publik:** `http://127.0.0.1:8000`
*   **Halaman Panel Admin:** `http://127.0.0.1:8000/admin/login`
