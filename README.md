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
| Frontend Web | Blade Template Engine |
| Mobile | Kotlin (Android) |
| Database | MySQL — MariaDB 10.4.32 |
| Autentikasi Web | Laravel Session Auth |
| Autentikasi Mobile | Laravel Sanctum (token-based) |
| Peta | Leaflet.js + OpenStreetMap |
| Version Control | Git & GitHub |
| Editor | VS Code |

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
- Pesan makanan dan dapatkan kode pickup unik
- Lacak status pesanan secara real-time

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

Mengikuti struktur standar Laravel 11 dengan pengorganisasian controller berdasarkan role:

```
app/Http/Controllers/
├── Auth/           ← Login & registrasi (3 portal: user, merchant, admin)
├── Admin/          ← Semua fitur panel admin
├── Merchant/       ← Semua fitur portal merchant
└── Api/            ← Endpoint untuk aplikasi mobile (Sanctum)
```

---

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

# Import database (gunakan file SQL di docs/ atau jalankan migrasi)
php artisan migrate

# Buat symlink storage
php artisan storage:link

# Jalankan server
php artisan serve
```

Akun testing yang tersedia setelah seeder:

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

Semua anggota tim wajib mengikuti alur kerja berikut:

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
docs: update data dictionary tabel orders
```

---

## Progress

### Praktikum — Implementasi
| Fitur | Status |
|---|---|
| Multi-portal autentikasi (login & registrasi) | ✅ |
| Dashboard admin & merchant | ✅ |
| Verifikasi merchant oleh admin | ✅ |
| Manajemen akun pengguna | ✅ |
| CRUD food listing surplus | ✅ |
| Manajemen kategori | ✅ |
| Order flow (konfirmasi hingga pickup) | ✅ |
| Peta lokasi merchant (Leaflet.js) | ✅ |
| Sistem withdrawal merchant | ✅ |
| API mobile (Sanctum) | 🔄 |
| Aplikasi Android | 🔄 |

> ✅ Selesai &nbsp;|&nbsp; 🔄 Dalam Proses &nbsp;|&nbsp; ❌ Belum Dimulai

---

## Lisensi

Proyek ini dibuat untuk keperluan akademik mata kuliah Rekayasa Perangkat Lunak, Universitas Sebelas Maret.
