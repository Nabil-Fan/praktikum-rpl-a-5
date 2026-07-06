# EcoEats — Platform Makanan Surplus Kota Solo

> Repositori resmi kelompok untuk mata kuliah **Rekayasa Perangkat Lunak**  
> Semester Genap 2025/2026 — Universitas Sebelas Maret

---

## Tentang Proyek

**EcoEats** adalah platform digital yang menghubungkan pelaku usaha kuliner di Kota Solo dengan konsumen yang ingin membeli makanan surplus dengan harga terjangkau. Terinspirasi dari konsep *Too Good To Go*, namun dirancang khusus untuk skala dan kebutuhan lokal Kota Solo.

Platform ini bertujuan untuk:
- Mengurangi pemborosan makanan (*food waste*) di sektor kuliner
- Memberikan akses makanan berkualitas dengan harga diskon kepada konsumen
- Membuka peluang bagi pelaku usaha untuk menjual produk surplus daripada terbuang

Seluruh transaksi diselesaikan melalui mekanisme **self-pickup** — tidak ada layanan pengiriman.

---

## Status MVP

**MVP dengan 3 fitur inti telah tercapai dan dapat didemonstrasikan end-to-end.**

| # | Fitur Inti | Status |
|---|---|---|
| 1 | Registrasi & Verifikasi Merchant | Selesai |
| 2 | Manajemen Food Listing Surplus | Selesai |
| 3 | Order Flow (pesanan → konfirmasi → pickup) | Selesai |

---

## Informasi Kelompok

| Atribut | Detail |
|---|---|
| Kelas | A |
| Kelompok | 5 |
| Semester | Genap 2025/2026 |
| Branch Utama | `dev` |

### Anggota

| No | Nama Lengkap | NIM | Peran | GitHub |
|---|---|---|---|---|
| 1 | Muhamad Nabil Fannani | L0124135 | Ketua / Project Manager | [@Nabil-Fan](https://github.com/Nabil-Fan) |
| 2 | Wiwid Widyaningsih | L0124123 | Developer | [@wiwidw](https://github.com/midnightbluee2) |
| 3 | Alena Mashia Qolby | L0124129 | Developer | [@midnightbluee2](https://github.com/wiwidw) |
| 4 | Maria Dewi Handayani | L0124132 | QA / Dokumentasi | [@hanihan1](https://github.com/hanihan1) |

---

## Teknologi

| Kategori | Teknologi |
|---|---|
| Backend | Laravel 11 (PHP) |
| Frontend Web | Blade Template Engine (`@extends`/`@yield`) |
| Mobile | Kotlin + Jetpack Compose (Android) |
| Database | MySQL — MariaDB 10.4.32 |
| Autentikasi Web | Laravel Session Auth |
| Autentikasi Mobile | Laravel Sanctum (token-based) |
| Peta (Web) | Leaflet.js + OpenStreetMap |
| Peta (Mobile) | OSMDroid + OpenStreetMap |
| HTTP Client Mobile | Retrofit 2 |
| QR Code | ZXing (generate kode pickup) |
| Image Loading | Coil |
| Arsitektur Mobile | MVVM + StateFlow |
| Version Control | Git & GitHub |
| Editor | VS Code / Android Studio |

---

## Fitur Utama

### Portal Admin
- Dashboard dengan statistik real-time (merchant, user, listing, pesanan)
- Verifikasi merchant: tinjau dokumen, setujui, atau tolak pendaftaran
- Manajemen akun pengguna (tambah manual, aktifkan/nonaktifkan)
- Monitoring seluruh pesanan di platform
- Manajemen kategori makanan
- Proses pencairan dana (withdrawal) merchant
- Peta lokasi seluruh merchant aktif

### Portal Merchant
- Registrasi dan manajemen profil usaha
- Upload dokumen legalitas untuk verifikasi
- Kelola menu surplus: tambah, edit, hapus, upload foto
- Terima dan proses pesanan masuk (konfirmasi, siap diambil, selesai)
- Verifikasi pickup menggunakan kode unik
- Ajukan pencairan saldo dari pesanan yang telah selesai
- Peta lokasi usaha sendiri

### Aplikasi Mobile (Android)
- Login dan registrasi akun pembeli
- Jelajahi katalog makanan surplus
- Detail listing: foto, harga, kuantitas, lokasi merchant
- Alur pemesanan lengkap: pilih item → checkout → pilih metode pembayaran
- Layar pembayaran: QRIS (gambar QR statis), transfer bank, atau e-wallet
- Upload bukti pembayaran dari galeri
- Layar menunggu verifikasi pembayaran oleh merchant dengan polling status otomatis
- QR Code pickup: di-generate di sisi mobile menggunakan kode unik dari server (ZXing)
- Detail pesanan: peta lokasi merchant (OSMDroid), estimasi waktu pickup, daftar item + total
- Lacak status pesanan secara real-time

---

## Screenshots

### Admin Panel

**Dashboard & Monitoring**
<div align="center">
  <img src="https://github.com/user-attachments/assets/53163ad5-3f5b-4870-bbea-35ff6f58b788" alt="Admin Dashboard" width="49%" />
</div>

**Verifikasi Merchant**
<div align="center">
  <img src="https://github.com/user-attachments/assets/f8a1b6ef-95d6-4068-b766-31337a217213" alt="Admin Map View" width="49%" />
</div>

**Monitoring Pesanan**
<div align="center">
  <img src="https://github.com/user-attachments/assets/93699ff7-7a2c-4e1b-a6e8-fc040fcada97" alt="Admin Map View" width="49%" />
</div>


**Peta Merchant**
<div align="center">
  <img src="https://github.com/user-attachments/assets/c6c4a8d0-e1ba-491e-a761-379efe28f0ce" alt="Admin Map View" width="49%" />
</div>

**Withdrawal**
<div align="center">
  <img src="https://github.com/user-attachments/assets/d5c95253-1d91-4a93-aa85-fd3f0ed37daf" alt="Admin Map View" width="49%" />
</div>

---

### Portal Merchant

**Dashboard Merchant**
<div align="center">
  <img src="https://github.com/user-attachments/assets/8bdafa00-ac7e-4971-afe8-e7f1b9c2a9bb" alt="Admin Map View" width="49%" />
</div>

**Kelola Menu Surplus**
<div align="center">
  <img src="https://github.com/user-attachments/assets/3001491b-f8d9-4460-81af-7cfdde3a9a69" alt="Admin Map View" width="49%" />
</div>

**Pesanan Masuk**
<div align="center">
  <img src="https://github.com/user-attachments/assets/bbe95e7c-c47f-45fc-97b2-6e122e0ea54c" alt="Admin Map View" width="49%" />
</div>

**Detail Pesanan & Verifikasi Pickup**
<div align="center">
  <img src="https://github.com/user-attachments/assets/249da52b-f55d-4a7b-853f-60074708bfa1" alt="Admin Map View" width="49%" />
</div>

**Penarikan Dana**
<div align="center">
  <img src="https://github.com/user-attachments/assets/063e2efd-33ad-48f7-855a-306ff744e481" alt="Admin Map View" width="49%" />
</div>


**Peta Lokasi Usaha**
<div align="center">
  <img src="https://github.com/user-attachments/assets/6c8fe3c2-cbaa-40fe-9448-8715f6c9f42f" alt="Admin Map View" width="49%" />
</div>

---

## Struktur Folder

```
praktikum-rpl-a-5/
├── docs/
│   ├── backlog.md
│   ├── data-dictionary.md
│   ├── erd.png
│   ├── problem-statement.md
│   ├── srs.md
│   ├── team-contract.md
│   ├── user-stories.md
│   ├── requirements/
│   ├── uml/
│   │   ├── activity-diagram.png
│   │   ├── class-diagram.png
│   │   └── use-case-diagram.png
│   └── wireframes/
│       └── figma.md
├── src/
│   ├── backend/                ← Project Laravel (PHP)
│   └── mobile/                 ← Project Android (Kotlin)
├── tests/
├── .gitignore
└── README.md
```

### Struktur Backend (`src/backend/`)

```
app/Http/Controllers/
├── Auth/           ← Login & registrasi (3 portal: user, merchant, admin)
├── Admin/          ← Semua fitur panel admin
├── Merchant/       ← Semua fitur portal merchant
└── Api/            ← Endpoint untuk aplikasi mobile (Sanctum)

resources/views/
├── layouts/
│   └── merchant.blade.php      ← Layout master merchant (sidebar + header)
├── admin/
└── merchant/                   ← Semua view @extends('layouts.merchant')
```

---
### Struktur Mobile (`src/mobile/`)

```text
com.week3.ecoeats/
├── data/
│   ├── local/
│   │   └── TokenManager.kt
│   ├── model/
│   │   ├── AuthModels.kt
│   │   ├── DashboardModels.kt
│   │   ├── OrderModels.kt
│   │   └── ProfileModels.kt
│   ├── remote/
│   │   ├── AuthApi.kt
│   │   ├── AuthRepository.kt
│   │   ├── DashboardRepository.kt
│   │   ├── FoodListingApi.kt
│   │   ├── OrderApi.kt
│   │   ├── OrderRepository.kt
│   │   └── RetrofitInstance.kt
│   └── util/
│
├── screens/
│   ├── Auth/
│   │   ├── AuthScreen.kt
│   │   ├── SignIn.kt
│   │   └── SignUp.kt
│   ├── Category/
│   │   └── CategoryDetailScreen.kt
│   ├── Checkout/
│   │   ├── CheckoutScreen.kt
│   │   ├── OrderDetailScreen.kt
│   │   ├── PaymentScreen.kt
│   │   ├── QrCodeScreen.kt
│   │   └── WaitingVerificationScreen.kt
│   ├── Component/
│   ├── Dashboard/
│   │   ├── component/
│   │   │   ├── CategoryRow.kt
│   │   │   ├── MenuSection.kt
│   │   │   └── SearchBar.kt
│   │   └── DashboardScreen.kt
│   ├── FoodDetail/
│   │   └── FoodDetailScreen.kt
│   ├── Maps/
│   │   └── MapsScreen.kt
│   ├── Navigations/
│   │   └── NavGraph.kt
│   ├── OrderHistory/
│   │   └── OrderHistoryScreen.kt
│   └── Profile/
│
├── ui/theme/
│   ├── Color.kt
│   ├── Theme.kt
│   └── Type.kt
│
├── viewmodel/
│   ├── AuthViewModel.kt
│   ├── CategoryDetailViewModel.kt
│   ├── DashboardViewModel.kt
│   ├── FoodDetailViewModel.kt
│   ├── OrderHistoryViewModel.kt
│   ├── OrderViewModel.kt
│   └── ProfileViewModel.kt
│
└── MainActivity.kt
```

## Setup & Instalasi

### Prasyarat
- PHP >= 8.2
- Composer
- MySQL / MariaDB
- Node.js (opsional, untuk asset build)
- Android Studio (untuk mobile)

### Backend (Laravel)

```bash
# Clone repositori
git clone https://github.com/Nabil-Fan/praktikum-rpl-a-5.git
cd praktikum-rpl-a-5/src/backend

# Install dependensi
composer install

# Salin file environment
cp .env.example .env

# Generate application key
php artisan key:generate

# Konfigurasi database di .env
# DB_DATABASE=ecoeats
# DB_USERNAME=root
# DB_PASSWORD=

# Import database
php artisan migrate

# Buat symlink storage
php artisan storage:link

# Isi data testing
php artisan db:seed

# Jalankan server
php artisan serve
```

Akun testing:

| Email | Password | Role |
|---|---|---|
| `admin@test.com` | `password` | Admin |
| `merchant@test.com` | `password` | Merchant |
| `user@test.com` | `password` | User |

### Mobile (Android)

```
1. Buka folder src/mobile/ di Android Studio
2. Sesuaikan BASE_URL di konfigurasi network dengan IP server Laravel
3. Build dan jalankan di emulator atau perangkat fisik
```

---

## Cara Kontribusi

```bash
# 1. Pastikan branch dev terbaru
git checkout dev
git pull origin dev

# 2. Buat branch baru
git checkout -b feature/nama-fitur

# 3. Lakukan perubahan, lalu commit
git add .
git commit -m "feat: deskripsi perubahan singkat"

# 4. Push ke GitHub
git push origin feature/nama-fitur

# 5. Buat Pull Request ke branch dev di GitHub
#    Minta minimal 1 anggota lain untuk review sebelum merge
```

### Standar Commit Message

Format: `<type>: <deskripsi singkat>`

| Type | Digunakan untuk |
|---|---|
| `feat` | Menambahkan fitur baru |
| `fix` | Memperbaiki bug |
| `docs` | Perubahan dokumentasi |
| `style` | Perubahan format/tampilan (bukan logika) |
| `refactor` | Refactoring tanpa mengubah fungsi |
| `test` | Menambah atau mengubah test |
| `chore` | Update konfigurasi, dependency, dll. |

**Contoh:**
```
feat: tambah halaman verifikasi merchant
fix: perbaiki kalkulasi saldo withdrawal
refactor: extract layout merchant ke layouts/merchant.blade.php
docs: update README dengan screenshot MVP
```

---

## Progress

### Praktikum — Implementasi
| Fitur | Status |
|---|---|
| Multi-portal autentikasi (login & registrasi) | ✔ |
| Dashboard admin & merchant | ✔ |
| Verifikasi merchant oleh admin | ✔ |
| Manajemen akun pengguna | ✔ |
| CRUD food listing surplus | ✔ |
| Manajemen kategori | ✔ |
| Order flow web (konfirmasi hingga pickup) | ✔ |
| Peta lokasi merchant — web (Leaflet.js) | ✔ |
| Sistem withdrawal merchant | ✔ |
| Refactoring layout merchant (`@extends`/`@yield`) | ✔ |
| API mobile — Sanctum auth + order endpoints | ✔ |
| Mobile: Login & registrasi | ✔ |
| Mobile: Katalog & detail listing | ✔ |
| Mobile: Checkout & pilih metode pembayaran | ✔ |
| Mobile: Upload bukti pembayaran | ✔ |
| Mobile: Waiting verification (polling status) | ✔ |
| Mobile: QR Code pickup (ZXing) | ✔ |
| Mobile: Order detail + peta merchant (OSMDroid) | ✔ |
| Review & rating | ❌ |

>  ✔ Selesai &nbsp;|&nbsp; 🔄 Dalam Proses &nbsp;|&nbsp; ❌ Belum Dimulai

---

## Lisensi

Proyek ini dibuat untuk keperluan akademik mata kuliah Rekayasa Perangkat Lunak, Universitas Sebelas Maret.
