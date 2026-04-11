# Uangku API

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-12-red.svg" alt="Laravel Version">
  <img src="https://img.shields.io/badge/PHP-8.2+-blue.svg" alt="PHP Version">
  <img src="https://img.shields.io/badge/License-MIT-green.svg" alt="License">
</p>

## Tentang Proyek

**Uangku API** adalah RESTful API untuk aplikasi pelacak keuangan pribadi (personal finance tracker). Aplikasi ini memungkinkan pengguna untuk mengelola pemasukan, pengeluaran, dan data master terkait dengan sistem periode akuntansi yang fleksibel. Dibangun menggunakan Laravel 12, dengan fokus pada keamanan, performa, dan skalabilitas.

### Fitur Utama
- **Autentikasi**: Menggunakan Laravel Sanctum untuk API token-based authentication, termasuk dukungan Google OAuth.
- **Master Data**: Kelola periode akuntansi, tipe pemasukan/pengeluaran, kategori, metode pembayaran, dan tag.
- **Transaksi**:
  - **Pemasukan (Incomes)**: Catat pemasukan dengan tipe, periode, dan metode pembayaran.
  - **Pengeluaran (Outcomes)**: Sistem hybrid - bisa sederhana atau dengan detail sub-item. Amount otomatis tersinkronisasi.
- **Dashboard**: Ringkasan keuangan dengan chart data, balance bulanan, dan wallet aktif. Menggunakan caching untuk performa optimal.
- **Authorization**: Policies ketat - pengguna hanya bisa akses data miliknya sendiri.
- **Soft Deletes**: Semua data dapat dihapus sementara dan dipulihkan.
- **API Documentation**: Lengkap dengan OpenAPI 3.0 spec.

### Tech Stack
- **Backend**: Laravel 12 (PHP 8.2+)
- **Database**: MySQL/PostgreSQL (dengan migrations dan seeders)
- **Authentication**: Laravel Sanctum
- **Frontend Build**: Vite + Tailwind CSS (untuk UI jika ada)
- **Testing**: PHPUnit untuk unit dan feature tests
- **Containerization**: Laravel Sail (Docker)
- **Code Quality**: Laravel Pint untuk linting

## Instalasi & Setup

### Prasyarat
- PHP 8.2 atau lebih tinggi
- Composer
- Node.js & npm (untuk frontend build)
- Docker & Docker Compose (untuk Sail)

### Langkah Instalasi
1. **Clone Repository**:
   ```bash
   git clone https://github.com/hafiizhekom/uang-gue.git
   cd uang-gue
   ```

2. **Install Dependencies**:
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup**:
   - Copy `.env.example` ke `.env`:
     ```bash
     cp .env.example .env
     ```
   - Generate application key:
     ```bash
     php artisan key:generate
     ```
   - Konfigurasi database di `.env` (gunakan Sail atau database lokal).

4. **Database Migration & Seeding**:
   ```bash
   php artisan migrate
   php artisan db:seed  # Jika ada seeders
   ```

5. **Build Assets**:
   ```bash
   npm run build
   ```

6. **Jalankan Aplikasi**:
   - Menggunakan Sail:
     ```bash
     ./vendor/bin/sail up -d
     ```
   - Atau langsung:
     ```bash
     php artisan serve
     npm run dev  # Untuk hot reload frontend
     ```

### Script Otomatis
Gunakan script setup bawaan:
```bash
composer run setup
```

Untuk development dengan semua service:
```bash
composer run dev
```

## Penggunaan API

API tersedia di `http://localhost:8000/api` (saat menggunakan Sail).

### Autentikasi
- **Register**: `POST /register` - Body: `{name, email, password}`
- **Login**: `POST /login` - Body: `{email, password}` - Return: token
- **Google Auth**: `POST /auth/google` - Body: `{token}` (dari Google OAuth)
- **Logout**: `POST /logout` - Header: `Authorization: Bearer {token}`
- **Me**: `GET /me` - Header: `Authorization: Bearer {token}`

Semua endpoint selain register/login memerlukan header `Authorization: Bearer {token}`.

### Endpoint Utama
- **Master Data**:
  - `GET/POST/PUT/DELETE /master-periods`
  - `GET/POST/PUT/DELETE /master-income-types`
  - `GET/POST/PUT/DELETE /master-outcome-categories`
  - `GET/POST/PUT/DELETE /master-outcome-types`
  - `GET/POST/PUT/DELETE /master-payments`
  - `GET/POST/PUT/DELETE /master-outcome-detail-tags`

- **Transaksi**:
  - `GET/POST/PUT/DELETE /incomes` - Query: `?master_period_id={id}`
  - `GET/POST/PUT/DELETE /outcomes`
  - `GET/POST/PUT/DELETE /outcome-details` (untuk sub-item outcomes)

- **Dashboard**:
  - `GET /dashboard` - Query: `?active_period={id}` - Return: balance, charts, wallets

### Contoh Request
```bash
# Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password"}'

# Get Incomes
curl -X GET http://localhost:8000/api/incomes?master_period_id=1 \
  -H "Authorization: Bearer {token}"
```

### Dokumentasi Lengkap
Lihat [OpenAPI Spec](openapi.yaml) untuk detail schema, responses, dan contoh.

## Testing
Jalankan tests dengan:
```bash
php artisan test
# Atau dengan Sail
./vendor/bin/sail test
```

## Kontribusi
1. Fork repository ini.
2. Buat branch fitur baru (`git checkout -b feature/AmazingFeature`).
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`).
4. Push ke branch (`git push origin feature/AmazingFeature`).
5. Buat Pull Request.

Pastikan kode mengikuti standar Laravel Pint dan memiliki tests.

## Lisensi
Proyek ini menggunakan lisensi MIT. Lihat file [LICENSE](LICENSE) untuk detail.

## Kontak
- **Owner**: [hafiizhekom](https://github.com/hafiizhekom)
- **Repository**: [uang-gue](https://github.com/hafiizhekom/uang-gue)

---

*Dibangun dengan ❤️ menggunakan Laravel.*
