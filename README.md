# Sumber Rezeki
Sistem web manajemen toko dan kasir untuk operasional harian Toko Sumber Rezeki.

## Tech Stack
- **PHP & Laravel 12**
- **MySQL & Eloquent** (Database & ORM)
- **Tailwind CSS & Alpine.js** (Frontend Styling & Interactivity)
- **Spatie Permission** (Role-Based Access Control)
- **Maatwebsite Excel** (Spreadsheet Export)
- **Laravel Breeze** (Authentication)

## Fitur
- 🔐 Login & Autentikasi yang aman
- 👥 Manajemen Pengguna (Role Admin & Kasir)
- 👤 Profil pengguna & update profil
- 📦 CRUD Data Barang & Kategori/Kode Barang
- 🛒 Transaksi Penjualan & Kasir
- 📊 Dashboard & Statistik Laporan Penjualan (Export to Excel)
- 💳 Manajemen Piutang Pelanggan
- 🏢 Manajemen Hutang Supplier

## Menu & Hak Akses (RBAC)
| Menu / Route Path | Deskripsi | Akses Role |
| :--- | :--- | :--- |
| `/login` | Autentikasi masuk ke sistem | Semua (Guest) |
| `/dashboard` | Ringkasan statistik & penjualan | Admin, Kasir |
| `/profile` | Lihat dan ubah data profil | Admin, Kasir |
| `/barang` | Modul inventori barang | Admin (Full CRUD), Kasir (Lihat saja) |
| `/transaksi` | Catat & lihat data transaksi masuk/keluar | Admin, Kasir |
| `/piutang` | Pencatatan piutang pelanggan | Admin |
| `/hutang-supplier`| Pencatatan hutang kepada supplier | Admin |
| `/kode-barang` | Pengaturan kategori/kode barang | Admin |
| `/transaksi/export`| Export riwayat transaksi ke Excel | Admin, Kasir |

## Instalasi

```bash
# Clone & install dependensi
git clone https://github.com/username/sumber-rezeki.git
cd sumber-rezeki
composer install
npm install

# Setup environment
cp .env.example .env
# Edit .env sesuai konfigurasi database jika tidak memakai SQLite default

# Generate application key & siapkan database
php artisan key:generate
touch database/database.sqlite
php artisan migrate --seed

# Jalankan server lokal & aset bunlder (vite) bersamaan
composer dev
```

## Environment Variables (.env)
Pastikan beberapa variabel penting berikut sudah sesuai di file `.env` Anda:
```env
APP_NAME="Sumber Rezeki"
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
# Jika menggunakan MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=sumber_rezeki
# DB_USERNAME=root
# DB_PASSWORD=
```

