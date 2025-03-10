# Aplikasi Billing Internet

Aplikasi manajemen billing internet dengan fitur WhatsApp notification dan pencatatan pembayaran.

## Persyaratan Sistem

- PHP >= 8.1
- Composer
- Node.js & NPM
- MySQL/MariaDB
- XAMPP/Laragon (opsional, untuk pengembangan lokal)
- Git

## Panduan Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/hasanbisri17/APK_BillingInternet.git
cd APK_BillingInternet
```

### 2. Install Dependencies

```bash
composer install
npm install
npm run build
```

### 3. Konfigurasi Environment

1. Copy file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```

2. Generate application key:
```bash
php artisan key:generate
```

3. Konfigurasi database di file `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=billing_internet
DB_USERNAME=root
DB_PASSWORD=
```

4. Konfigurasi WhatsApp Gateway di file `.env`:
```
WHATSAPP_API_URL=
WHATSAPP_API_KEY=
```

### 4. Migrasi Database

1. Buat database baru dengan nama `billing_internet`

2. Jalankan migrasi database:
```bash
php artisan migrate
```

3. Jalankan seeder untuk data awal:
```bash
php artisan db:seed
```

### 5. Menjalankan Aplikasi

1. Start server Laravel:
```bash
php artisan serve
```

2. Akses aplikasi melalui browser:
```
http://localhost:8000
```

### 6. Login Admin Default

```
Email: admin@admin.com
Password: password
```

## Fitur Utama

1. Manajemen Pelanggan
   - Pendaftaran pelanggan baru
   - Pengelolaan data pelanggan
   - Riwayat pembayaran pelanggan

2. Manajemen Paket Internet
   - Pembuatan paket internet
   - Pengaturan harga dan kecepatan
   - Status paket aktif/nonaktif

3. Pembayaran
   - Pencatatan pembayaran bulanan
   - Multiple metode pembayaran
   - Generate invoice otomatis
   - Riwayat pembayaran

4. Notifikasi WhatsApp
   - Pengingat pembayaran otomatis
   - Template pesan kustomisasi
   - Jadwal pengiriman pesan
   - Status pengiriman pesan

5. Laporan Keuangan
   - Laporan pembayaran
   - Laporan transaksi kas
   - Filter berdasarkan periode
   - Export laporan

## Panduan Penggunaan

### 1. Manajemen Pelanggan

1. Klik menu "Customers" di sidebar
2. Untuk menambah pelanggan baru, klik tombol "New Customer"
3. Isi formulir data pelanggan:
   - Nama lengkap
   - Nomor WhatsApp
   - Alamat
   - Pilih paket internet
4. Klik "Save" untuk menyimpan

### 2. Pencatatan Pembayaran

1. Klik menu "Payments" di sidebar
2. Klik tombol "New Payment"
3. Pilih pelanggan
4. Isi detail pembayaran:
   - Tanggal pembayaran
   - Periode pembayaran
   - Metode pembayaran
   - Jumlah pembayaran
5. Klik "Save" untuk menyimpan
6. Invoice akan ter-generate otomatis

### 3. Pengaturan WhatsApp

1. Klik menu "WhatsApp Settings"
2. Masukkan kredensial WhatsApp Gateway
3. Atur template pesan default
4. Aktifkan/nonaktifkan fitur reminder otomatis

### 4. Laporan

1. Klik menu "Reports" di sidebar
2. Pilih jenis laporan
3. Atur filter periode
4. Klik "Generate" untuk melihat laporan
5. Gunakan tombol "Export" untuk mengunduh laporan

## Pemeliharaan

### Backup Database

Jalankan perintah berikut untuk backup database:
```bash
php artisan backup:run
```

### Update Aplikasi

1. Pull perubahan terbaru:
```bash
git pull origin main
```

2. Update dependencies:
```bash
composer update
npm update
```

3. Jalankan migrasi jika ada perubahan database:
```bash
php artisan migrate
```

## Troubleshooting

### 1. Masalah Umum

1. **Error 500**
   - Periksa file `.env`
   - Periksa permission folder storage
   - Periksa log di `storage/logs`

2. **WhatsApp tidak terkirim**
   - Periksa kredensial WhatsApp Gateway
   - Periksa format nomor tujuan
   - Periksa log WhatsApp di menu "WhatsApp Messages"

### 2. Solusi

1. Reset permission folder:
```bash
chmod -R 775 storage bootstrap/cache
```

2. Clear cache:
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

## Dukungan

Jika mengalami masalah atau membutuhkan bantuan, silakan buat issue di repository GitHub atau hubungi tim support.

## Lisensi

Aplikasi ini bersifat private dan hanya untuk penggunaan yang diizinkan.
