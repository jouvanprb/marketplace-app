<<<<<<< HEAD
# 🎮 Johen Gaming - Portal Top-Up & Storefront Game Premium

Selamat datang di **Johen Gaming**, sebuah portal storefront dan top-up game premium yang modern, berkecepatan tinggi, dan aman. Terinspirasi dari platform kelas dunia seperti Midasbuy, aplikasi ini menggabungkan keindahan visual *glassmorphism* dengan keandalan rekayasa backend Laravel 11.

Portal ini menawarkan alur pembelian tamu (*guest checkout*) tanpa hambatan, pelacakan status transaksi otomatis, integrasi gerbang pembayaran instan, serta panel admin yang sepenuhnya terisolasi dan aman.

=======
# Gaming Store - Web Top-Up & Storefront Game

Aplikasi web untuk top-up game dan storefront bertema gelap/glassmorphic. Dibuat menggunakan Laravel 11, aplikasi ini mendukung guest checkout (pembelian tanpa login), pelacakan transaksi otomatis, dan integrasi pembayaran Midtrans.

>>>>>>> 7aaaa5b8134a3a2c86e5c3aac69f6e3e41a67f19
---

## 🛠️ Teknologi yang Digunakan

<<<<<<< HEAD
Aplikasi ini dibangun menggunakan kombinasi teknologi modern yang sangat optimal:

*   **Backend Core:** [Laravel 11.x](https://laravel.com) (PHP 8.3+) — memanfaatkan fitur-fitur mutakhir Laravel seperti konfigurasi middleware terpusat dan arsitektur OOP yang elegan.
*   **Sistem Autentikasi:** [Laravel Breeze (Edisi Livewire / Volt)](https://laravel.com/docs/11.x/starter-kits#laravel-breeze-and-blade) — menyajikan antarmuka autentikasi yang sangat interaktif dan responsif menggunakan komponen Livewire Volt berkas tunggal (*single-file components*).
*   **Desain Antarmuka (UI Engine):** 
    *   [Tailwind CSS 3.x](https://tailwindcss.com) — digunakan untuk mendesain tema game premium kustom dengan palet warna zinc gelap, efek neon untuk indikator status, serta lapisan *glassmorphism* yang mewah.
    *   [Alpine.js 3.x](https://alpinejs.dev) — menangani mikro-animasi, dropdown interaktif, dan transisi modal di sisi klien.
    *   [FontAwesome 6.x](https://fontawesome.com) — ikon premium untuk mempercantik seluruh tata letak halaman.
*   **Database:** MySQL / MariaDB (atau PostgreSQL) menggunakan Eloquent ORM bawaan Laravel dengan indeks kolom JSON yang dioptimalkan.
*   **Integrasi Pembayaran:** [Midtrans Snap API & Webhooks](https://midtrans.com) — mendukung pembayaran overlay Snap yang aman serta pembaruan status transaksi otomatis melalui webhook asinkron.

---

## 📊 Skema & Arsitektur Database

Database dirancang dengan sangat fleksibel untuk mendukung transaksi dari akun pelanggan terdaftar maupun pembelian langsung oleh tamu (*guest checkout*).

### 🧬 Diagram Hubungan Entitas (ERD)
=======
*   **Backend Core:** Laravel 11.x (PHP 8.3+)
*   **Autentikasi:** Laravel Breeze (Livewire Volt)
*   **Frontend:** Tailwind CSS, Alpine.js, dan FontAwesome 6
*   **Database:** MySQL / MariaDB
*   **Payment Gateway:** Midtrans Snap API & Webhooks

---

## 📊 Skema Database

### Diagram ERD
>>>>>>> 7aaaa5b8134a3a2c86e5c3aac69f6e3e41a67f19

```mermaid
erDiagram
    USERS ||--o{ ORDERS : places
    USERS ||--o{ PRODUCTS : manages
    CATEGORIES ||--o{ PRODUCTS : has
    ORDERS ||--|{ ORDER_ITEMS : contains
    PRODUCTS ||--o{ ORDER_ITEMS : includes
    
    USERS {
        bigint id PK
        string name
        string email
        string password
        enum role "admin, customer"
        timestamp created_at
        timestamp updated_at
    }
    
    CATEGORIES {
        bigint id PK
        string name
        string slug
        string image "nullable"
        timestamp created_at
        timestamp updated_at
    }
    
    PRODUCTS {
        bigint id PK
        string name
        bigint category_id FK
        string type "topup, account"
        text description
        decimal price
        int stock
        string image "nullable"
        boolean is_active
        timestamp created_at
        timestamp updated_at
    }
    
    ORDERS {
        bigint id PK
        bigint user_id FK "nullable"
        string order_code UK
        decimal total_price
        enum status "pending, paid, failed"
        string payment_method "nullable"
        json payment_details "nullable"
        string payment_token "nullable"
        timestamp created_at
        timestamp updated_at
    }
    
    ORDER_ITEMS {
        bigint id PK
        bigint order_id FK
        bigint product_id FK
<<<<<<< HEAD
        string game_account "nullable (ID Akun Game untuk Top-Up)"
        string user_id_game "nullable (Zone Server ID untuk Top-Up)"
=======
        string game_account "nullable"
        string user_id_game "nullable"
>>>>>>> 7aaaa5b8134a3a2c86e5c3aac69f6e3e41a67f19
        decimal price
        int quantity
        timestamp created_at
        timestamp updated_at
    }
```

<<<<<<< HEAD
### 🗄️ Deskripsi Kolom Tabel Secara Rinci

#### 1. Tabel `users`
Menyimpan data akun pengguna dan hak akses aplikasi.
*   `id` (BIGINT, PK, Auto Increment)
*   `name` (VARCHAR) - Nama lengkap pengguna.
*   `email` (VARCHAR, Unique) - Alamat email unik untuk login dan korespondensi.
*   `password` (VARCHAR) - Hash sandi akun.
*   `role` (ENUM: `'admin'`, `'customer'`) - Tingkat hak akses sistem.
*   `created_at` / `updated_at` (TIMESTAMP)

#### 2. Tabel `categories`
Menampung daftar kategori game (seperti Mobile Legends, Free Fire, Valorant).
*   `id` (BIGINT, PK, Auto Increment)
*   `name` (VARCHAR) - Nama tampilan kategori game.
*   `slug` (VARCHAR, Unique) - Slug URL yang ramah SEO.
*   `image` (VARCHAR, Nullable) - Jalur file gambar banner/kartu kategori.
=======
### Detail Tabel

#### 1. Tabel `users`
Menyimpan data pengguna dan role akses.
*   `id` (BIGINT, PK, Auto Increment)
*   `name` (VARCHAR) - Nama lengkap.
*   `email` (VARCHAR, Unique) - Email login.
*   `password` (VARCHAR) - Hash password.
*   `role` (ENUM: `'admin'`, `'customer'`) - Role akses.
*   `created_at` / `updated_at` (TIMESTAMP)

#### 2. Tabel `categories`
Menyimpan kategori game (seperti Mobile Legends, Free Fire, dll).
*   `id` (BIGINT, PK, Auto Increment)
*   `name` (VARCHAR) - Nama kategori game.
*   `slug` (VARCHAR, Unique) - Slug URL.
*   `image` (VARCHAR, Nullable) - File gambar kategori.
>>>>>>> 7aaaa5b8134a3a2c86e5c3aac69f6e3e41a67f19
*   `created_at` / `updated_at` (TIMESTAMP)

#### 3. Tabel `products`
Menyimpan daftar paket top-up atau akun game yang dijual.
*   `id` (BIGINT, PK, Auto Increment)
<<<<<<< HEAD
*   `category_id` (BIGINT, FK) - Menghubungkan ke `categories.id`.
*   `name` (VARCHAR) - Nama paket (misalnya, "500 Diamonds").
*   `type` (VARCHAR) - Jenis produk: `'topup'` (isi ulang langsung ke ID game) atau `'account'` (pembelian data kredensial akun fisik).
*   `description` (TEXT, Nullable) - Deskripsi atau detail isi paket produk.
*   `price` (DECIMAL 15,2) - Harga jual produk.
*   `stock` (INT) - Jumlah stok produk yang tersedia.
*   `image` (VARCHAR, Nullable) - Gambar ikon kecil produk.
*   `is_active` (BOOLEAN, Default: `true`) - Status aktif untuk menampilkan/menyembunyikan produk di katalog.
*   `created_at` / `updated_at` (TIMESTAMP)

#### 4. Tabel `orders`
Mencatat riwayat transaksi pembelian.
*   `id` (BIGINT, PK, Auto Increment)
*   `user_id` (BIGINT, FK, Nullable) - Menghubungkan ke `users.id`. Bernilai `null` apabila pembeli melakukan transaksi sebagai tamu (*guest checkout*).
*   `order_code` (VARCHAR, Unique) - Kode invoice unik (contoh: `INV-YYYYMMDD-XXXX`).
*   `total_price` (DECIMAL 15,2) - Total nominal belanja pesanan.
*   `status` (ENUM: `'pending'`, `'paid'`, `'failed'`) - Status pembayaran transaksi saat ini.
*   `payment_method` (VARCHAR, Nullable) - Metode pembayaran spesifik yang dipilih (misalnya: `qris`, `gopay`, `bank_transfer`).
*   `payment_details` (JSON, Nullable) - Menyimpan payload lengkap dari Midtrans, termasuk nama pelanggan tamu, email, dan nomor WhatsApp.
*   `payment_token` (VARCHAR, Nullable) - Menyimpan token pembayaran Midtrans Snap agar pembeli dapat mengulang percobaan pembayaran jika terjadi kendala.
*   `created_at` / `updated_at` (TIMESTAMP)

#### 5. Tabel `order_items`
Menyimpan rincian item produk yang dibeli di setiap transaksi.
*   `id` (BIGINT, PK, Auto Increment)
*   `order_id` (BIGINT, FK) - Menghubungkan ke `orders.id` (otomatis terhapus jika pesanan dihapus / cascade).
*   `product_id` (BIGINT, FK) - Menghubungkan ke `products.id` (cascade).
*   `game_account` (VARCHAR, Nullable) - ID Akun Game pembeli (misal: `12345678`) yang diinput saat top-up.
*   `user_id_game` (VARCHAR, Nullable) - Server ID / Zone ID game (misal: `1234`) yang diinput saat top-up.
*   `price` (DECIMAL 15,2) - Harga produk saat waktu pembelian dikunci.
*   `quantity` (INT) - Jumlah barang yang dibeli.
=======
*   `category_id` (BIGINT, FK) - Relasi ke `categories.id`.
*   `name` (VARCHAR) - Nama produk (misal: "50 Diamonds").
*   `type` (VARCHAR) - Tipe produk: `'topup'` atau `'account'`.
*   `description` (TEXT, Nullable) - Deskripsi produk.
*   `price` (DECIMAL 15,2) - Harga produk.
*   `stock` (INT) - Jumlah stok.
*   `image` (VARCHAR, Nullable) - Gambar produk.
*   `is_active` (BOOLEAN) - Status aktif/nonaktif produk.
*   `created_at` / `updated_at` (TIMESTAMP)

#### 4. Tabel `orders`
Menyimpan data transaksi pembelian.
*   `id` (BIGINT, PK, Auto Increment)
*   `user_id` (BIGINT, FK, Nullable) - Relasi ke `users.id`. Null jika checkout sebagai guest.
*   `order_code` (VARCHAR, Unique) - Kode invoice unik.
*   `total_price` (DECIMAL 15,2) - Total nominal transaksi.
*   `status` (ENUM: `'pending'`, `'paid'`, `'failed'`) - Status transaksi.
*   `payment_method` (VARCHAR, Nullable) - Metode pembayaran (misal: gopay, qris).
*   `payment_details` (JSON, Nullable) - Payload respons pembayaran lengkap dari Midtrans.
*   `payment_token` (VARCHAR, Nullable) - Token Snap Midtrans untuk pembayaran ulang.
*   `created_at` / `updated_at` (TIMESTAMP)

#### 5. Tabel `order_items`
Rincian produk di dalam order.
*   `id` (BIGINT, PK, Auto Increment)
*   `order_id` (BIGINT, FK) - Relasi ke `orders.id` (cascade).
*   `product_id` (BIGINT, FK) - Relasi ke `products.id` (cascade).
*   `game_account` (VARCHAR, Nullable) - ID game pembeli (untuk produk top-up).
*   `user_id_game` (VARCHAR, Nullable) - Zone/Server ID game pembeli (untuk produk top-up).
*   `price` (DECIMAL 15,2) - Harga produk saat dibeli.
*   `quantity` (INT) - Jumlah pembelian.
>>>>>>> 7aaaa5b8134a3a2c86e5c3aac69f6e3e41a67f19
*   `created_at` / `updated_at` (TIMESTAMP)

---

<<<<<<< HEAD
## 🔒 Arsitektur Autentikasi & Keamanan

Sistem keamanan aplikasi ini dirancang secara matang untuk melindungi integritas data dan hak akses:

### 👤 Autentikasi Pelanggan (Customer)
*   **Pendaftaran & Masuk:** Seluruhnya ditangani secara asinkron oleh rute Volt Livewire (`login.blade.php`, `register.blade.php`).
*   **Perilaku Pengalihan (Redirect):** Setelah login atau register berhasil, pembeli langsung diarahkan ke halaman beranda katalog (`'/'`) demi kelancaran alur belanja, alih-alih dijebak di halaman dashboard kosong.
*   **Manajemen Profil:** Pengguna terdaftar dapat mengelola nama, kata sandi, dan meninjau riwayat transaksi pribadinya di menu `/profile`.

### 🛡️ Perisai Penyamaran Rute Admin (404 Shield)
Untuk menjamin keamanan maksimal, aplikasi kita menerapkan metode **Security by Obfuscation** untuk menyembunyikan eksistensi Panel Admin dari publik:
1.  **Pencegahan Deteksi Rute (404 Abort):** 
    *   Jika ada tamu yang belum masuk atau pelanggan biasa mencoba mengetikkan `/admin` atau `/admin/*` di bilah alamat browser, sistem **tidak akan pernah mengalihkan** mereka ke rute login biasa.
    *   Sebaliknya, middleware internal akan langsung memotong request dan menyuguhkan halaman **`404 Not Found`** standar browser. Penyerang akan mengira bahwa panel admin tidak ada di server ini.
2.  **Pintu Masuk Terisolasi:** Admin hanya dapat login secara eksklusif dengan mengakses alamat rahasia **`/admin/login`** secara langsung.

### 🔄 Sistem Keluar Kebal Kadaluwarsa (419 Session-Expired Prevention)
Logout standar menggunakan metode POST Laravel sering kali menghasilkan error menyebalkan **"419 Page Expired"** jika pembeli membiarkan halaman terbuka hingga masa sesi habis.
*   Kami memecahkannya dengan mendefinisikan rute hybrid **`Route::match(['get', 'post'], '/logout')`** yang bebas dari middleware autentikasi.
*   Ketika tombol Sign Out diklik setelah sesi habis, sistem akan langsung menghapus sisa cookie lokal dan mengalihkan pengguna kembali ke beranda dengan bersih tanpa memicu exception error.

---

## 🌐 API & Registrasi Rute

Berikut adalah rute-rute penting yang terdaftar di dalam berkas `routes/web.php`:

### 🛒 Rute Publik Storefront
| Metode | URI | Fungsi Controller | Deskripsi |
| :--- | :--- | :--- | :--- |
| **GET** | `/` | `FrontController@index` | Dashboard utama katalog game. |
| **GET** | `/category/{slug}` | `FrontController@category` | Menampilkan produk berdasarkan kategori game. |
| **GET** | `/product/{id}` | `FrontController@show` | Menampilkan detail paket produk game. |
| **GET** | `/track-order` | `FrontController@trackIndex` | Halaman pelacak pesanan (tampil otomatis untuk pelanggan, dan formulir pencarian HP untuk tamu). |
| **POST** | `/track-order/search` | `FrontController@trackSearch` | Melakukan pencarian transaksi tamu berdasarkan nomor WhatsApp. |

### 💳 Rute Pembayaran & Webhook
| Metode | URI | Fungsi Controller | Deskripsi |
| :--- | :--- | :--- | :--- |
| **POST** | `/checkout/{product}` | `CheckoutController@store` | Validasi input, pembuatan data pesanan, dan pembuatan token Snap Midtrans. |
| **POST** | `/payment/notification` | `CheckoutController@notification` | **Webhook Bebas CSRF** untuk memproses pembaruan transaksi otomatis dari Midtrans secara real-time. |
| **GET** | `/payment/finish` | `CheckoutController@finish` | Callback sukses dari Midtrans (diarahkan ke Nota digital). |
| **GET** | `/payment/unfinish` | `CheckoutController@unfinish` | Callback pending dari Midtrans (diarahkan ke Nota digital). |
| **GET** | `/payment/error` | `CheckoutController@error` | Callback gagal dari Midtrans (diarahkan ke Nota digital). |
| **GET** | `/order/receipt/{order_code}` | `CheckoutController@receipt` | Halaman Nota Pembelian digital interaktif yang siap cetak. |

### 🛡️ Rute Panel Admin (Diproteksi oleh middleware role:admin)
*Semua rute di bawah ini akan memicu error **404 Not Found** jika diakses oleh akun non-admin:*
| Metode | URI | Fungsi Controller | Deskripsi |
| :--- | :--- | :--- | :--- |
| **GET** | `/admin/login` | *Volt Component* | Gerbang masuk rahasia untuk masuk sebagai Administrator. |
| **GET** | `/admin/dashboard` | `DashboardController@index` | Ringkasan statistik dan grafik pendapatan penjualan. |
| **RESOURCE** | `/admin/categories`| `CategoryController` | Manajemen CRUD untuk data kategori game. |
| **RESOURCE** | `/admin/products` | `ProductController` | Manajemen CRUD untuk paket produk game. |
| **RESOURCE** | `/admin/orders` | `OrderController` | Pemantauan rincian transaksi otomatis. |

---

## ⚡ Validasi Input & Penanganan Error

Aplikasi ini menerapkan validasi ketat untuk menjamin keamanan dan kelancaran transaksi:

### 1. Validasi Input Pembelian
Saat pembeli mengirimkan permintaan checkout (`CheckoutController@store`), sistem akan memverifikasi input secara dinamis:
*   `email` -> Wajib berformat email yang valid dan panjang maksimal 255 karakter.
*   `phone` -> Wajib diisi sebagai string (nomor WhatsApp aktif pelanggan).
*   `game_id` / `zone_id` -> Wajib diisi secara kondisional hanya untuk tipe produk `'topup'`.

### 2. Verifikasi Keamanan Tanda Tangan Webhook
Guna mencegah upaya pemalsuan data pembayaran (*spoofing*) oleh pihak tidak bertanggung jawab, sistem melakukan validasi kecocokan tanda tangan SHA512 di rute Webhook:
=======
## 🔒 Sistem Autentikasi & Keamanan

### 1. Autentikasi Customer
*   **Sistem:** Menggunakan Laravel Breeze dengan Livewire Volt.
*   **Alur:** Pendaftaran dan masuk akun dilakukan secara asinkron. Setelah login berhasil, pengguna diarahkan ke halaman utama (`'/'`) agar langsung bisa belanja tanpa masuk ke dashboard kosong.
*   **Profil:** Pengguna dapat memperbarui nama, password, dan email melalui rute `/profile`.

### 2. Pengamanan Rute Admin (404 Shield)
Untuk menyembunyikan panel admin dari publik, rute admin diamankan dengan sistem pengalihan 404:
*   Jika guest (belum login) atau customer biasa mencoba mengakses `/admin` atau `/admin/*`, middleware akan langsung menghentikan request dan menampilkan halaman **`404 Not Found`**.
*   Login admin hanya bisa diakses melalui rute khusus `/admin/login`.

### 3. Logout Bebas Error 419 (Session Expired)
Tombol logout standar rawan memicu error "419 Page Expired" jika halaman dibiarkan terbuka terlalu lama hingga sesi habis.
*   Rute logout diubah menjadi tipe hybrid `match(['get', 'post'], '/logout')` tanpa proteksi auth. 
*   Ketika diklik, sistem langsung menghapus sesi dan cookie, lalu mengalihkan pengguna ke beranda dengan bersih.

---

## 🌐 Daftar Rute (Route Registry)

### Rute Publik
| Method | URI | Action | Keterangan |
| :--- | :--- | :--- | :--- |
| **GET** | `/` | `FrontController@index` | Halaman katalog game utama. |
| **GET** | `/category/{slug}` | `FrontController@category` | List produk berdasarkan kategori. |
| **GET** | `/product/{id}` | `FrontController@show` | Detail produk game. |
| **GET** | `/track-order` | `FrontController@trackIndex` | Halaman tracking (riwayat otomatis untuk customer / cari nomor HP untuk guest). |
| **POST** | `/track-order/search` | `FrontController@trackSearch` | Pencarian order guest berdasarkan nomor HP. |

### Rute Pembayaran & Webhook
| Method | URI | Action | Keterangan |
| :--- | :--- | :--- | :--- |
| **POST** | `/checkout/{product}` | `CheckoutController@store` | Validasi input, input order, dan generate token Snap Midtrans. |
| **POST** | `/payment/notification` | `CheckoutController@notification` | **Webhook (Bypass CSRF)** untuk update status pesanan otomatis via Midtrans. |
| **GET** | `/payment/finish` | `CheckoutController@finish` | Redirect sukses dari Midtrans ke halaman receipt. |
| **GET** | `/payment/unfinish` | `CheckoutController@unfinish` | Redirect pending dari Midtrans ke halaman receipt. |
| **GET** | `/payment/error` | `CheckoutController@error` | Redirect gagal dari Midtrans ke halaman receipt. |
| **GET** | `/order/receipt/{order_code}` | `CheckoutController@receipt` | Nota pembelian digital dengan layout cetak. |

### Rute Admin (Diproteksi role:admin)
| Method | URI | Action | Keterangan |
| :--- | :--- | :--- | :--- |
| **GET** | `/admin/login` | *Volt Component* | Login khusus akun admin. |
| **GET** | `/admin/dashboard` | `DashboardController@index` | Ringkasan statistik penjualan. |
| **RESOURCE** | `/admin/categories`| `CategoryController` | CRUD data kategori game. |
| **RESOURCE** | `/admin/products` | `ProductController` | CRUD paket produk game. |
| **RESOURCE** | `/admin/orders` | `OrderController` | Detail transaksi masuk. |

---

## ⚡ Validasi & Penanganan Error

### 1. Validasi Input Pembelian
Pada saat checkout (`CheckoutController@store`), input diverifikasi secara ketat:
*   `email` -> Wajib berformat email valid dan maksimal 255 karakter.
*   `phone` -> Wajib diisi (untuk nomor WhatsApp).
*   `game_id` / `zone_id` -> Wajib diisi jika produk bertipe `'topup'`.

### 2. Validasi Tanda Tangan Webhook
Untuk mencegah manipulasi status pembayaran, rute webhook memvalidasi tanda tangan SHA512 yang dikirim oleh Midtrans:
>>>>>>> 7aaaa5b8134a3a2c86e5c3aac69f6e3e41a67f19
```php
$localSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);
if ($signatureKey !== $localSignature) {
    return response()->json(['message' => 'Invalid signature key'], 403);
}
```
<<<<<<< HEAD
Jika kunci tidak cocok dengan kredensial server Anda, request akan langsung ditolak mentah-mentah dengan respons status `403 Forbidden`.

---

## 💻 Panduan Instalasi & Setup Lokal

Ikuti langkah-langkah di bawah ini untuk menjalankan aplikasi Johen Gaming di server lokal Anda:

### Persyaratan Sistem
*   PHP 8.3 atau versi lebih tinggi
*   Composer
*   Node.js & NPM
*   MySQL atau MariaDB Server aktif

### Langkah 1: Kloning & Unduh Dependensi
```bash
# Kloning repositori
git clone <url-repositori>
cd marketplace_app

# Pasang dependensi PHP
composer install

# Pasang paket JavaScript
npm install
```

### Langkah 2: Konfigurasi File Environment
Salin template berkas konfigurasi lingkungan:
```bash
cp .env.example .env
```
Buka file `.env` yang baru dibuat, lalu isi kredensial database lokal serta Server Key dari Midtrans Sandbox Anda:
=======
If the signature is not valid, the request is rejected with `403 Forbidden` status.

---

## 💻 Panduan Instalasi Lokal

### Persyaratan
*   PHP >= 8.3
*   Composer
*   Node.js & NPM
*   MySQL atau MariaDB

### Langkah-Langkah

#### 1. Install Dependensi
```bash
composer install
npm install
```

#### 2. Konfigurasi Environment
Salin berkas `.env.example` ke `.env`:
```bash
cp .env.example .env
```
Buka `.env` and atur database serta konfigurasi Midtrans Sandbox Anda:
>>>>>>> 7aaaa5b8134a3a2c86e5c3aac69f6e3e41a67f19
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
<<<<<<< HEAD
DB_DATABASE=johen_gaming_db
DB_USERNAME=root
DB_PASSWORD=sandikeduaanda

# Konfigurasi Midtrans
=======
DB_DATABASE=gaming_db
DB_USERNAME=root
DB_PASSWORD=your_password

# Midtrans
>>>>>>> 7aaaa5b8134a3a2c86e5c3aac69f6e3e41a67f19
MIDTRANS_MERCHANT_ID=merchant_id_anda
MIDTRANS_CLIENT_KEY=client_key_anda
MIDTRANS_SERVER_KEY=server_key_anda
MIDTRANS_IS_PRODUCTION=false
```

<<<<<<< HEAD
### Langkah 3: Migrasi Database & Seeder
Buat kunci enkripsi aplikasi baru, lalu buat tabel-tabel database beserta data awal katalog game secara otomatis:
```bash
# Membuat Kunci Aplikasi
php artisan key:generate

# Menjalankan Migrasi & Memasukkan Data Seeder
php artisan migrate:fresh --seed
```
*Catatan: File `StoreSeeder` akan secara otomatis membuat kategori game bawaan (Mobile Legends, Free Fire, Genshin Impact, Valorant, Point Blank) lengkap dengan produknya, serta satu akun Administrator default:*
*   **Email Admin:** `admin@gmail.com`
*   **Kata Sandi:** `password`

### Langkah 4: Jalankan Server Lokal
Nyalakan server lokal PHP Laravel beserta compiler aset Vite secara bersamaan di terminal terpisah:
```bash
# Terminal 1: Menjalankan Server Laravel
php artisan serve

# Terminal 2: Menjalankan Vite Asset Compiler
npm run dev
```
Buka browser Anda dan akses alamat `http://127.0.0.1:8000` untuk mulai menjelajahi portal premium Johen Gaming!

---

## 🌿 Alur Kerja Git & Penamaan Commit

Kami mengikuti standar penamaan commit yang rapi agar riwayat pengerjaan proyek selalu terorganisasi dengan baik:

*   **Format Pesan Commit:** Kami menggunakan prefix penanda untuk mengelompokkan perubahan kode:
    *   `feat:` — penambahan fitur baru (misalnya: `feat: integrasikan webhook midtrans untuk update status otomatis`).
    *   `fix:` — perbaikan bug atau error tampilan (misalnya: `fix: cegah error 419 page expired pada tombol keluar`).
    *   `refactor:` — penataan ulang kode tanpa mengubah fungsionalitas (misalnya: `refactor: bersihkan widget statistik dashboard admin`).
    *   `style:` — penyesuaian visual, pengaturan warna HSL, atau pembenahan *glassmorphism*.

Selamat mengembangkan dan memperluas ekosistem **Johen Gaming**! 🚀
=======
#### 3. Migrasi Database & Data Awal (Seed)
```bash
php artisan key:generate
php artisan migrate:fresh --seed
```
*Seed data akan otomatis membuat kategori default dan satu akun admin:*
*   **Email Admin:** `admin@gmail.com`
*   **Password:** `password`

#### 4. Jalankan Server
Jalankan kedua perintah ini di terminal terpisah:
```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev
```
Buka `http://127.0.0.1:8000` di browser Anda.

---

## 🌿 Format Commit Git

Riwayat pengerjaan repositori dikelompokkan dengan prefix commit berikut:
*   `feat:` — Penambahan fitur baru (misal: `feat: tambah webhook midtrans`).
*   `fix:` — Perbaikan bug (misal: `fix: atasi error 419 pada logout`).
*   `refactor:` — Struktur ulang kode tanpa mengubah fungsi (misal: `refactor: rapikan controller order`).
*   `style:` — Perbaikan styling visual CSS/HTML.
>>>>>>> 7aaaa5b8134a3a2c86e5c3aac69f6e3e41a67f19
