# Delivery Tracking Application - XAMPP Version

Aplikasi pencatatan pengiriman barang yang kompatibel dengan XAMPP, dengan interface bahasa Indonesia dan fitur auto-generated document numbers serta ID form.

## Features

- ✅ Interface bahasa Indonesia
- ✅ **Auto-generated ID Form (FORM-YYYYMMDD-XXXX)** - Mengelompokkan item pengiriman
- ✅ Auto-generated document numbers (DOK-YYYYMMDD-XXXX)
- ✅ **Pencarian berdasarkan ID Form** - Temukan semua item dalam satu form
- ✅ Form input pengiriman barang dengan multiple items
- ✅ Pencarian dan filter berdasarkan cabang, driver, no polisi
- ✅ Print surat jalan lengkap
- ✅ Database MySQL dengan relasi
- ✅ Responsive design
- ✅ Fallback API endpoints untuk kompatibilitas maksimal

## Key Features - ID Form System

### ID Form Auto-Generation
- Format: `FORM-YYYYMMDD-XXXX` (contoh: FORM-20250119-0001)
- Otomatis ter-generate saat membuat pengiriman baru
- Mengelompokkan semua item barang dalam satu form pengiriman

### Form ID Search
- Pencarian khusus berdasarkan ID Form
- Menampilkan detail lengkap pengiriman dan semua item
- Modal popup dengan informasi komprehensif
- Fungsi print terintegrasi

### Database Structure
```sql
shipments:
- id (Primary Key)
- id_form (Unique, Auto-generated)
- no_dokumen (Auto-generated)
- no_polisi, no_segel
- nama_driver, nama_co_driver
- nama_staff, cabang_tujuan
- catatan_pengiriman
- created_at, updated_at

shipment_items:
- id (Primary Key)
- shipment_id (Foreign Key)
- nama_barang, no_dokumen
- tanggal_kirim, jumlah_barang
- catatan
```

## Installation

1. Download file `delivery-tracking-final-with-form-id.zip` dan extract ke folder `htdocs/delivery-tracking` di XAMPP
2. Start Apache dan MySQL di XAMPP Control Panel
3. Buka phpMyAdmin dan buat database baru bernama `delivery_tracking`
4. Import file `database.sql` ke database tersebut
5. Update konfigurasi database di `config/database.php` jika diperlukan
6. Akses aplikasi melalui `http://localhost/delivery-tracking`

## Database Configuration

File konfigurasi database terletak di `config/database.php`:

```php
$host = 'localhost';
$dbname = 'delivery_tracking';
$username = 'root';
$password = '';
```

## Migration untuk Instalasi Existing

### Menambahkan ID Form ke Database Existing
Jalankan `migrate-add-form-id.php` untuk:
- Menambahkan kolom `id_form` ke tabel `shipments`
- Generate ID Form untuk data yang sudah ada
- Membuat kolom unique dan not null

### Menambahkan Document Number ke Database Existing
Jalankan `migrate-add-document-number.php` untuk menambahkan field `no_dokumen`

## API Endpoints

- `GET api/shipments.php` - Mendapatkan daftar pengiriman
- `POST api/shipments.php` - Membuat pengiriman baru
- `PUT api/shipments.php?id={id}` - Update pengiriman
- `DELETE api/shipments.php?id={id}` - Hapus pengiriman
- `GET get-form-details.php?id_form={form_id}` - Detail pengiriman berdasarkan ID Form

## Usage Examples

### Pencarian Berdasarkan ID Form
1. Masuk ke tab "Daftar Pengiriman"
2. Gunakan field "Cari berdasarkan ID Form"
3. Masukkan ID Form (contoh: FORM-20250119-0001)
4. Klik "Cari Form"
5. Modal akan menampilkan detail lengkap pengiriman dan semua item

### Membuat Pengiriman Baru
1. Masuk ke tab "Input Pengiriman"
2. Klik "Generate ID Form" untuk membuat ID otomatis
3. Klik "Generate No. Dokumen" untuk nomor dokumen otomatis
4. Isi data pengiriman dan tambahkan item barang
5. Simpan pengiriman

## Troubleshooting

### Error 404 saat Pencarian
1. Pastikan file `api/shipments.php` ada dan dapat diakses
2. Jalankan `test-connection.php` untuk mengecek koneksi database
3. Cek console browser untuk error JavaScript

### ID Form Tidak Muncul
1. Jalankan `migrate-add-form-id.php` untuk menambahkan field
2. Pastikan kolom `id_form` ada di tabel `shipments`
3. Refresh halaman aplikasi

### Database Connection Error
1. Cek konfigurasi di `config/database.php`
2. Pastikan MySQL service aktif di XAMPP
3. Verifikasi nama database dan kredensial

## File Structure

```
delivery-tracking/
├── api/
│   └── shipments.php          # Main API endpoint
├── assets/
│   ├── css/
│   │   └── style.css          # Styling
│   └── js/
│       └── app.js             # JavaScript aplikasi
├── config/
│   └── database.php           # Konfigurasi database
├── includes/
│   └── functions.php          # Database functions
├── database.sql               # Database schema
├── get-form-details.php       # API untuk detail form
├── migrate-add-form-id.php    # Migration untuk ID form
├── migrate-add-document-number.php # Migration untuk no dokumen
├── index.php                  # Main application
└── README.md                  # Dokumentasi
```