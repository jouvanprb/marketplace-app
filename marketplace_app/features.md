# Fitur Aplikasi Web Top-Up & Storefront Game

Dokumen ini menjelaskan seluruh fitur yang ada pada aplikasi, cara kerjanya, serta langkah-langkah implementasi teknis di belakang layar secara mendalam.

---

## 1. Katalog Berbasis Kategori (Storefront Utama)
Sistem katalog tidak menampilkan seluruh produk secara acak, melainkan dikelompokkan secara rapi berdasarkan kategori game.

*   **Cara Kerja:**
    1. Pembeli masuk ke halaman beranda (`/`) dan melihat daftar game yang tersedia (misal: Mobile Legends, Free Fire, Valorant) dalam bentuk kartu grid yang responsif.
    2. Saat salah satu game diklik, pembeli diarahkan ke halaman kategori game tersebut (`/category/{slug}`).
    3. Halaman kategori menampilkan pilihan paket koin/diamond yang khusus dijual untuk game tersebut.
*   **Implementasi Teknis:**
    *   Menggunakan relasi database *One-to-Many* (`Category` memiliki banyak `Product`).
    *   Pengambilan data menggunakan eager loading (`with('products')`) untuk mengoptimalkan performa database.

---

## 2. Formulir Dinamis Sesuai Tipe Produk (Checkout Flow)
Aplikasi mendukung dua jenis penjualan: isi ulang langsung (**Top-Up**) dan penjualan akun game (**Account**). Formulir input akan menyesuaikan secara dinamis tergantung produk yang dipilih.

*   **Cara Kerja:**
    *   **Tipe Top-Up:** Jika produk yang dipilih adalah top-up, pembeli wajib mengisi kolom **ID Game** dan **Zone/Server ID** sebelum lanjut ke pembayaran.
    *   **Tipe Akun:** Jika produk berupa akun game fisik, kolom input ID Game & Zone ID otomatis disembunyikan. Pembeli hanya perlu memasukkan data kontak dasar (email & nomor HP).
*   **Implementasi Teknis:**
    *   Kolom `type` pada tabel `products` bernilai `'topup'` atau `'account'`.
    *   Di halaman detail produk, blade directive `@if($product->type === 'topup')` digunakan untuk menampilkan atau menyembunyikan input ID Game secara kondisional.

---

## 3. Pembelian Tanpa Login (Guest Checkout)
Pembeli tidak diwajibkan untuk mendaftar atau masuk akun terlebih dahulu untuk melakukan transaksi. Pembelian instan dapat langsung dilakukan sebagai tamu.

*   **Cara Kerja:**
    1. Pembeli memilih paket, mengisi data game (jika top-up), email, dan nomor WhatsApp.
    2. Setelah klik beli, sistem membuat record pesanan baru dengan status pending.
*   **Implementasi Teknis:**
    *   Kolom `user_id` pada tabel `orders` diatur sebagai `nullable()` agar bisa menerima nilai `null` ketika pembeli tidak masuk akun.
    *   Kontak pembeli (email & nomor WhatsApp) disimpan dengan aman di dalam kolom JSON `payment_details` pada tabel `orders`.

---

## 4. Pelacakan Transaksi Dua Jalur (Dual-Path Tracking)
Halaman `/track-order` memungkinkan pembeli memantau status semua pesanan mereka dengan sangat mudah tanpa perlu mengetik kode invoice yang panjang dan rumit.

*   **Cara Kerja:**
    *   **Pengguna yang Login:** Sistem otomatis membaca alamat email pembeli yang sedang aktif dan menampilkan tabel seluruh riwayat transaksi mereka.
    *   **Pengguna Tamu (Guest):** Tamu cukup memasukkan nomor WhatsApp yang mereka gunakan saat checkout. Sistem akan mencari semua pesanan yang terhubung dengan nomor tersebut dan menyajikannya dalam tabel yang sama.
*   **Implementasi Teknis:**
    *   Pencarian nomor HP guest menggunakan query JSON Laravel pada kolom `payment_details`:
        ```php
        Order::where('payment_details->customer_details->phone', $phone)->get();
        ```
    *   Tampilan riwayat disajikan secara konsisten menggunakan tabel responsif dengan lencana warna status (Kuning untuk Pending, Hijau untuk Paid, Merah untuk Failed).

---

## 5. Integrasi Otomatis Webhook Midtrans & Expire 15 Menit
Sistem pembayaran menggunakan Midtrans Snap API yang terhubung dengan sistem webhook asinkron untuk memperbarui status transaksi secara otomatis dan aman.

*   **Cara Kerja:**
    1. Saat checkout, token Snap dibuat dan masa berlaku pembayaran dibatasi tepat **15 menit**.
    2. Pembeli melakukan pembayaran melalui metode pilihan mereka (QRIS, GoPay, Transfer Bank, dll.).
    3. Setelah dibayar (atau jika waktu 15 menit habis/dibatalkan), server Midtrans mengirimkan POST request ke webhook website kita di `/payment/notification`.
    4. Status pesanan di database langsung berubah menjadi **Paid** atau **Failed** secara otomatis.
*   **Implementasi Teknis:**
    *   **Batas Waktu Expiry:** Ditentukan saat memanggil Snap API menggunakan parameter `'expiry'`:
        ```php
        'expiry' => ['duration' => 15, 'unit' => 'minute']
        ```
    *   **Webhook Aman:** Rute webhook dideklarasikan bebas dari proteksi CSRF di `bootstrap/app.php` agar bisa diakses oleh Midtrans.
    *   **Validasi SHA512:** Untuk mencegah manipulasi status, webhook memverifikasi keaslian request menggunakan kecocokan signature key SHA512.

---

## 6. Nota Pembelian Digital & Cetak (Receipt Page)
Halaman `/order/receipt/{order_code}` menyajikan rangkuman transaksi lengkap yang interaktif setelah pembeli menyelesaikan pembayaran.

*   **Cara Kerja:**
    *   Menampilkan informasi detail game (ID & Server), metode pembayaran, nominal total, dan status pembayaran.
    *   Jika produk yang dibeli berupa akun game fisik, halaman ini akan otomatis menampilkan **Data Kredensial Akun** (username & password) yang siap digunakan.
    *   Terdapat tombol "Cetak Nota" yang secara otomatis menyembunyikan elemen website yang tidak perlu (seperti header & footer) untuk menghasilkan format cetak kertas yang rapi.
*   **Implementasi Teknis:**
    *   Data kredensial diamankan dengan kondisi `@if($order->status === 'paid')` agar hanya tampil jika pembayaran sudah terverifikasi lunas.
    *   Media query CSS `@media print` digunakan untuk menyembunyikan tombol navigasi saat proses pencetakan nota berlangsung.

---

## 7. Pengamanan Rute Admin (404 Shield)
Untuk meminimalisir upaya peretasan atau brute-force pada panel belakang, rute admin sepenuhnya disamarkan dari jangkauan publik.

*   **Cara Kerja:**
    *   Setiap kali ada pengunjung biasa atau tamu yang mencoba mengetikkan `/admin` atau `/admin/dashboard` secara manual di browser, server akan langsung merespons dengan tampilan **`404 Page Not Found`**.
    *   Ini membuat publik mengira halaman admin tidak ada di website Anda. Admin hanya bisa masuk melalui tautan rahasia `/admin/login`.
*   **Implementasi Teknis:**
    *   Pada file `bootstrap/app.php`, callback `guests` dikustomisasi untuk memicu abort 404 apabila rute yang dituju adalah admin:
        ```php
        guests: function ($request) {
            if ($request->is('admin') || $request->is('admin/*')) {
                abort(404);
            }
            return '/login';
        }
        ```
    *   `RoleMiddleware` juga menerapkan `abort(404)` jika ada customer biasa yang sudah login mencoba mengakses area admin.

---

## 8. Proteksi Integritas Transaksi di Panel Admin
Panel admin dirancang agar kebal dari error data dan menjaga integritas pembayaran tetap valid.

*   **Cara Kerja:**
    *   **Null-Safe Safeguard:** Ketika admin melihat daftar pesanan atau detail pesanan, sistem tidak akan pernah crash jika pesanan tersebut dilakukan oleh guest (tanpa akun). Nama pembeli tamu otomatis diambil dari metadata JSON Midtrans.
    *   **Proteksi Status Otomatis:** Formulir ubah status transaksi manual dihapus dari halaman admin. Status pesanan murni hanya dikelola secara otomatis oleh server Midtrans. Ini mencegah admin salah menekan tombol status secara tidak sengaja.
*   **Implementasi Teknis:**
    *   Menggunakan operator null coalescing pada view admin untuk menampilkan nama:
        ```php
        $order->user->name ?? $order->payment_details['customer_details']['first_name'] ?? 'Guest Customer'
        ```

---

## 9. Tombol Keluar Kebal Kadaluwarsa (GET/POST Hybrid Logout)
Menghilangkan error "419 Page Expired" yang sering dikeluhkan oleh pengguna saat mencoba keluar dari akun setelah halaman dibiarkan diam terlalu lama hingga sesi habis.

*   **Cara Kerja:**
    *   Meskipun sesi login pembeli sudah kadaluwarsa (expired), tombol "Keluar" (Sign Out) akan tetap berfungsi secara normal dalam sekali klik dan langsung mengarahkan pembeli kembali ke halaman utama secara bersih.
*   **Implementasi Teknis:**
    *   Rute logout didaftarkan menggunakan metode hybrid:
        ```php
        Route::match(['get', 'post'], '/logout', ...)
        ```
    *   Rute ini dilepas dari middleware `auth` sehingga tidak akan memicu exception CSRF jika sesi login sudah mati sebelum tombol diklik.
