# Aqlam Mural Kaligrafi

Aplikasi manajemen pemesanan mural kaligrafi berbasis Laravel untuk alur pelanggan dan admin:
- Katalog desain
- Pemesanan katalog/custom
- Pembayaran (full / DP)
- Monitoring status pesanan
- Upload hasil proyek + link unduh
- Laporan admin

## Tech Stack

- Laravel 12
- PHP 8.2+
- MySQL
- Blade + Bootstrap 5
- Midtrans (opsional sesuai konfigurasi)

## Struktur Peran

- **Guest**: lihat beranda, katalog, portofolio, konsultasi.
- **Customer**: registrasi/login, buat pesanan, lihat status, lakukan pembayaran.
- **Admin**: kelola katalog, kategori, pesanan, pembayaran, pelanggan, laporan.

## Setup Lokal

1. Install dependency:
   - `composer install`
   - `npm install`
2. Salin environment:
   - `cp .env.example .env`
3. Set konfigurasi di `.env`:
   - `APP_URL`
   - `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`
   - `MIDTRANS_*` (jika dipakai)
4. Generate key & migrate:
   - `php artisan key:generate`
   - `php artisan migrate`
5. Jalankan aplikasi:
   - `php artisan serve`

## Kredensial Uji

- Admin: `admin@aqlamMural.com` / `password`
- Customer: `pelanggan@example.com` / `password`

## Alur Sistem End-to-End

### 1) Alur Katalog (Public)

1. User membuka `/katalog`.
2. Sistem menampilkan list desain + filter kategori, pencarian, sorting harga/terbaru.
3. User membuka detail produk `/katalog/{slug}` untuk melihat deskripsi lengkap, harga, kategori, dan CTA pemesanan.

### 2) Alur Katalog (Admin)

1. Admin masuk ke `/admin/katalog`.
2. Admin bisa:
   - tambah desain (`/admin/katalog/tambah`)
   - edit desain (`/admin/katalog/{id}/edit`)
   - hapus desain
   - filter berdasarkan kategori / pencarian nama
3. Admin kelola kategori di `/admin/katalog/kategori`.

### 3) Alur Rich Text Katalog

Deskripsi produk pada katalog sudah menggunakan **rich text**:
- Input rich text pada form **create/edit desain** (admin).
- Tampil rich text pada:
  - detail katalog user
  - daftar katalog user (preview)
  - daftar katalog admin (preview)
- HTML deskripsi disanitasi saat simpan untuk menjaga konsistensi konten.

### 4) Alur Pesanan Katalog (Customer)

1. Customer login.
2. Buka `/pesanan/buat`.
3. Pilih desain katalog, isi detail teknis (bahan, ornament, ukuran, lokasi), catatan, dan metode bayar.
4. Submit pesanan.
5. Sistem membuat order + item order + status awal.

### 5) Alur Pesanan Custom (Customer)

1. Customer buka `/pesanan-custom/buat`.
2. Isi kebutuhan custom (tema, ukuran, lokasi, detail).
3. Upload file referensi jika ada.
4. Submit pesanan custom.
5. Admin meninjau dan dapat menetapkan quote harga.

### 6) Alur Pembayaran

1. Customer membuka halaman pembayaran order `/pembayaran/{order}`.
2. Pilih metode sesuai aturan order (full/DP).
3. Sistem membuat data payment dan memproses gateway (jika aktif).
4. Status pembayaran ter-update (pending/success/failed).
5. Admin bisa konfirmasi/tolak pembayaran dari panel admin.

### 7) Alur Manajemen Pesanan (Admin)

1. Admin membuka `/admin/pesanan`.
2. Admin melihat seluruh pesanan (katalog + custom).
3. Admin memperbarui status pesanan (proses, selesai, dll).
4. Untuk custom order, admin bisa set quote.

### 8) Alur Upload Hasil Proyek (Admin)

1. Admin membuka detail pesanan `/admin/pesanan/{order}`.
2. Admin upload file hasil proyek.
3. Sistem menyimpan `order_results`:
   - file URL
   - token unduh
   - masa berlaku link (`expires_at`)
4. Customer dapat mengakses hasil via mekanisme link/token sesuai implementasi order result.

### 9) Alur Monitoring Customer

Customer dapat memantau:
- daftar pesanan (`/pesanan`)
- detail pesanan
- status progress
- status pembayaran
- hasil proyek jika sudah diunggah admin

### 10) Alur Laporan (Admin)

Admin mengakses `/admin/laporan` untuk:
- ringkasan data operasional
- cetak laporan (`/admin/laporan/cetak`)
- export laporan (`/admin/laporan/export`)

## Daftar Endpoint Web Utama

### Public
- `GET /`
- `GET /katalog`
- `GET /katalog/{slug}`
- `GET /portofolio`
- `GET /konsultasi`

### Customer (auth)
- `GET /pesanan`
- `GET /pesanan/buat`
- `POST /pesanan`
- `GET /pesanan/{order}`
- `DELETE /pesanan/{order}`
- `GET /pesanan-custom/buat`
- `POST /pesanan-custom`
- `GET /pesanan-custom/{order}`
- `POST /pesanan-custom/{customOrder}/upload`
- `GET /pembayaran/{order}`
- `POST /pembayaran/{order}`

### Admin (auth + admin)
- `GET /admin`
- `GET /admin/katalog`
- `GET /admin/katalog/tambah`
- `POST /admin/katalog`
- `GET /admin/katalog/{design}/edit`
- `PUT /admin/katalog/{design}`
- `DELETE /admin/katalog/{design}`
- `GET /admin/katalog/kategori`
- `GET /admin/pesanan`
- `GET /admin/pesanan/{order}`
- `POST /admin/pesanan/{order}/hasil`
- `POST /admin/pesanan/{order}/quote`
- `GET /admin/pembayaran`
- `GET /admin/laporan`

## Modul Inti

- `App\Http\Controllers\Customer\CatalogController`
- `App\Http\Controllers\Admin\CatalogController`
- `App\Http\Controllers\Customer\OrderController`
- `App\Http\Controllers\Customer\CustomOrderController`
- `App\Http\Controllers\Admin\OrderController`
- `App\Services\OrderService`
- `App\Services\PaymentService`
- `App\Services\FileUploadService`

## Catatan Operasional

- Pastikan MySQL aktif sebelum menjalankan aplikasi.
- Jika mengubah schema lama, gunakan migration baru (hindari edit migration yang sudah dieksekusi di environment aktif).
- Untuk production, atur `APP_ENV=production`, `APP_DEBUG=false`, dan kredensial sensitif via environment.

## Lisensi

Project ini menggunakan lisensi yang tercantum pada file `LICENSE`.
