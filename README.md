<p align="center">
  <h1 align="center">Arneta Collaction - Point of Sale (POS) & ERP System</h1>
</p>

## 📌 Tentang Proyek

**Arneta Collaction** adalah sistem manajemen Point of Sale (POS) dan Enterprise Resource Planning (ERP) berskala ringan yang dibangun menggunakan framework **Laravel 11**. Aplikasi ini dirancang khusus untuk mempermudah proses transaksi kasir, pelacakan inventaris gudang, pencatatan pengeluaran, serta manajemen pelaporan keuangan harian dan bulanan secara menyeluruh.

## ✨ Fitur Utama

- **Point of Sale (POS)**: Antarmuka kasir yang interaktif, mendukung pencarian produk, scan barcode/QR code, dan penambahan multi-item.
- **Manajemen Inventaris (Produk & Kategori)**: Kelola stok barang dengan varian ukuran (sizes) dan hitung revaluasi stok otomatis (berdasarkan Harga Pokok Penjualan/Cost).
- **Proses Retur Cerdas (Pengembalian Barang)**: Sistem alur persetujuan admin untuk produk yang diretur. Secara otomatis akan mengembalikan stok produk fisik, dan menambahkan nilai *Refund* sebagai 'Pengeluaran' untuk menyeimbangkan Laporan Keuangan.
- **Laporan Keuangan Menyeluruh**: Lacak total pendapatan kotor, total diskon, pengeluaran kas otomatis/manual, nilai persediaan saat ini, serta **Laba Bersih** (Net Profit).
- **Export Laporan (PDF & Excel/CSV)**: Unduh rekap pesanan dan mutasi finansial ke bentuk PDF (menggunakan DomPDF) dan file komputasi spreadsheet (CSV).
- **Pelanggan & Metode Pembayaran**: Melacak metode bayar (Tunai, Transfer, E-Wallet) dan menyimpan histori pembelian per pelanggan.

## 🛠️ Stack Teknologi

- **Backend**: [Laravel 11.x](https://laravel.com/) (PHP 8.2+)
- **Frontend / Styling**: Blade Templating, Tailwind CSS, Bootstrap 5, Vite, Vue 3 (Parsial)
- **Database**: MySQL / SQLite (Sesuai profil `.env`)
- **Library Tambahan**: 
  - `barryvdh/laravel-dompdf` (Cetak PDF)
  - `milon/barcode` & `html5-qrcode` (Scan & Generate Barcode/QR)
  - `livewire/livewire` (Reaktivitas Komponen)
  - `realrashid/sweet-alert` (Notifikasi UI)

## 🚀 Cara Instalasi (Local Development)

Ikuti langkah-langkah di bawah ini untuk menjalankan project ini di komputer lokal Anda:

1. **Clone repositori** atau ekstrak *source code* ke direktori lokal (misal: `C:\laragon\www\arneta-collaction`).
2. Buka terminal pada direktori aplikasi.
3. Jalankan perinah berikut untuk menginstal dependensi PHP (Pastikan Composer telah terinstal):
   ```bash
   composer install
   ```
4. Salin file konfigurasi *environment*:
   ```bash
   cp .env.example .env
   ```
5. Buka file `.env` dan atur koneksi Database Anda (Jika memakai MySQL pastikan DB berjalan, atau biarkan default `DB_CONNECTION=sqlite` jika belum mempunyai server SQL mandiri).
6. Bangkitkan Application Key:
   ```bash
   php artisan key:generate
   ```
7. Jalankan migrasi database beserta seeder (Jika ada):
   ```bash
   php artisan migrate --seed
   ```
8. Install dependensi antarmuka (NPM) dan *build* aset CSS/JS:
   ```bash
   npm install
   npm run build
   ```
9. Jalankan Service Server:
   ```bash
   php artisan serve
   ```
   Aplikasi kini dapat diakses melalui browser pada `http://127.0.0.1:8000`.

## 📜 Lisensi & Properti

Dikembangkan secara khusus untuk operasional **Arneta Collaction**. Hak Cipta dilindungi. Kerangka dasar menggunakan [MIT License](https://opensource.org/licenses/MIT) bawaan dari Laravel Framework.
