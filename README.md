# 🏍️ SPEEDWASH — Aplikasi Cuci Motor Cepat Berbasis Teknologi

Aplikasi web manajemen layanan cuci motor express dibangun dengan **Laravel 12**, **MySQL**, **Eloquent ORM**, dan **Bootstrap 5**.

## ✨ Fitur Utama

### Pelanggan
- ✅ Registrasi & Login (Laravel Authentication)
- ✅ Dashboard pelanggan (statistik, booking aktif, antrean)
- ✅ Booking cuci motor (pilih motor, paket, jadwal)
- ✅ Pemilihan paket layanan (Express, Reguler, Premium, Salon Motor)
- ✅ Monitoring antrean real-time (auto-refresh)
- ✅ Riwayat transaksi & status booking
- ✅ Profil pengguna (data diri, ganti password, kelola motor)

### Admin
- ✅ Dashboard admin (statistik, grafik pendapatan, status antrean)
- ✅ Kelola antrean (update nomor antrean, buka/tutup layanan)
- ✅ Kelola booking (filter, ubah status, catat pembayaran)
- ✅ Kelola paket layanan (CRUD lengkap)
- ✅ Kelola pelanggan (lihat detail, aktif/nonaktifkan akun)
- ✅ Laporan (mingguan/bulanan/tahunan, grafik, export data transaksi)

## 🛠️ Tech Stack
- **Backend**: Laravel 12 (PHP 8.2+)
- **Database**: MySQL + Eloquent ORM + Migration
- **Auth**: Laravel built-in Authentication (session-based)
- **Frontend**: Blade Templates + Bootstrap 5.3 + Bootstrap Icons + Chart.js
- **Font**: Inter & Space Grotesk (Google Fonts)

## 📦 Instalasi

### 1. Clone / Extract project
```bash
cd speedwash
```

### 2. Install dependencies
```bash
composer install
```

### 3. Setup environment
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Konfigurasi Database
Edit file `.env` dan sesuaikan koneksi database MySQL:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=speedwash
DB_USERNAME=root
DB_PASSWORD=
```

Buat database baru:
```sql
CREATE DATABASE speedwash;
```

### 5. Jalankan migration & seeder
```bash
php artisan migrate --seed
```

### 6. Buat symbolic link storage (untuk upload avatar)
```bash
php artisan storage:link
```

### 7. Jalankan server
```bash
php artisan serve
```

Akses aplikasi di `http://localhost:8000`

## 🔑 Akun Demo

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@speedwash.id | password123 |
| Pelanggan | budi@example.com | password123 |
| Pelanggan | siti@example.com | password123 |

## 📁 Struktur Database

| Tabel | Deskripsi |
|-------|-----------|
| `users` | Data pengguna (admin & pelanggan) |
| `vehicles` | Data motor milik pelanggan |
| `service_packages` | Paket layanan cuci motor |
| `bookings` | Data booking/pemesanan |
| `transactions` | Riwayat transaksi pembayaran |
| `queue_monitors` | Status antrean harian |

## 🎨 Desain
- Sidebar navigation responsif (collapse di mobile)
- Tema warna: Navy (#0F172A) + Sky Blue (#0EA5E9)
- Font: Space Grotesk (heading) + Inter (body)
- Komponen: stat cards, status badges, progress steps, queue board

## 📝 Catatan
- Gunakan PHP >= 8.2
- Pastikan ekstensi PHP: `pdo_mysql`, `mbstring`, `xml`, `curl`, `zip` aktif
- Untuk produksi, jalankan `php artisan config:cache` dan `php artisan route:cache`
