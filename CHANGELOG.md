# Changelog

Semua perubahan penting pada proyek EcoEats didokumentasikan di file ini.

Format mengikuti [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
dan proyek ini mengikuti [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

---

## [1.0.0] - 2026-07-05

### Added

#### Autentikasi & Registrasi
- Multi-portal login terpisah untuk tiga role: User, Merchant, dan Admin (`/login`, `/merchant/login`, `/admin/login`)
- Registrasi akun user baru dengan validasi email unik
- Registrasi merchant dengan form tiga langkah: data akun, profil usaha (nama, alamat, koordinat), dan upload dokumen legalitas
- Role middleware untuk proteksi route per portal — akses silang antar role otomatis diblokir
- Soft-delete pada model User: akun yang dihapus tidak bisa login, data historis tetap terjaga
- Logout yang menghapus session dan mereturn ke halaman login sesuai role

#### Verifikasi Merchant
- Dashboard admin untuk meninjau pengajuan merchant dengan status Pending
- Fitur approve, reject (wajib isi alasan), dan reset verifikasi ke Pending
- Audit log setiap keputusan verifikasi tersimpan di tabel `merchant_verifications` beserta `admin_id` dan catatan
- Merchant yang belum approved tidak dapat mempublikasikan listing (guard di controller dan UI)

#### Manajemen Food Listing
- CRUD food listing surplus oleh merchant: tambah, edit, nonaktifkan, hapus (soft-delete)
- Upload foto produk dengan validasi format (JPG/PNG/WebP) dan ukuran maksimal 2MB
- Field: nama, kategori, deskripsi, harga normal, harga surplus, stok, waktu pickup, status
- Validasi: harga surplus harus lebih rendah dari harga normal, stok minimal 1
- Perhitungan otomatis persentase diskon (`discountPercent()`) ditampilkan di card listing
- Scope `active()` untuk query listing yang tersedia (status available, stok > 0, tidak soft-deleted)
- Admin dapat memonitor dan force-delete listing dari seluruh merchant

#### Manajemen Kategori
- CRUD kategori makanan oleh admin
- Proteksi hapus: kategori yang masih digunakan listing tidak bisa dihapus

#### Order Flow
- Model Order dengan auto-generate kode pickup unik 8 karakter (tanpa karakter ambigu: 0, O, 1, I)
- Alur status pesanan: `pending` → `confirmed` → `ready` → `completed` (atau `rejected`/`expired`)
- Merchant dapat konfirmasi atau tolak pesanan (penolakan wajib isi alasan, stok dikembalikan otomatis via `DB::transaction`)
- Merchant menandai pesanan siap diambil (`markReady`)
- Verifikasi kode pickup saat penyelesaian pesanan — kode diinput merchant, dicocokkan di server
- Snapshot harga dan nama listing di `order_items` untuk menjaga akurasi data historis
- Monitoring pesanan read-only untuk admin dengan filter status dan pencarian
- Status helper methods: `isPending()`, `canMarkReady()`, `canComplete()`, `statusLabel()`, `statusColor()`

#### Peta Lokasi (Leaflet.js + OpenStreetMap)
- Peta admin: menampilkan seluruh merchant approved yang memiliki listing aktif, dengan marker dan popup info
- Peta merchant: menampilkan pin lokasi usaha sendiri berdasarkan koordinat yang didaftarkan
- Integrasi Leaflet.js via CDN tanpa API key berbayar
- Data koordinat di-pass dari controller ke view via `@json()` directive Blade

#### Withdrawal (Pencairan Dana)
- Merchant dapat mengajukan withdrawal dengan data rekening (bank, nomor rekening, nama pemilik)
- Kalkulasi saldo otomatis: total pesanan completed dikurangi total withdrawal completed/processing
- Validasi: minimum withdrawal Rp 10.000, nominal tidak boleh melebihi saldo, tidak bisa ajukan baru jika ada yang pending/processing
- Admin dapat approve (ubah ke processing), complete (upload bukti transfer), atau reject withdrawal
- Upload bukti transfer oleh admin (JPG/PNG/PDF, maks. 4MB)

#### Dashboard
- Dashboard admin: statistik real-time (pending verifikasi, merchant aktif, total user, listing aktif)
- Dashboard merchant: menu aktif, pesanan pending, selesai hari ini, dan 5 listing terbaru
- Notifikasi banner status verifikasi di dashboard merchant (pending/rejected)

#### Manajemen Pengguna (Admin)
- Daftar seluruh user dengan filter status (aktif/nonaktif) dan pencarian
- Toggle aktif/nonaktif akun user
- Soft-delete dan restore akun user
- Tambah akun manual oleh admin (user, merchant dengan status approved otomatis, atau admin baru)

#### Refactoring & Arsitektur
- Layout master `resources/views/layouts/merchant.blade.php` menggunakan `@extends`/`@yield`
- Eliminasi duplikasi sidebar HTML di 9 view merchant (sekitar 450 baris kode duplikat)
- CSS shared (variabel, sidebar, flash, utilities, badge, pagination) dipindah ke layout
- Active nav state via `@section('active_nav')` yang dievaluasi di layout

#### Aplikasi Mobile (Android/Kotlin)
- Login dan registrasi user melalui API Laravel Sanctum dengan penyimpanan token menggunakan DataStore Preferences
- Dashboard yang menampilkan katalog food listing dengan fitur pencarian dan filter kategori
- Halaman detail makanan yang menampilkan foto, informasi produk, quantity selector, dan pilihan metode pembayaran
- Alur pemesanan end-to-end: Food Detail → Waiting Verification → Checkout → Payment → Order Detail → QR Code Pickup
- Polling status pesanan secara real-time selama proses verifikasi merchant
- Upload bukti pembayaran dari galeri perangkat menggunakan multipart request
- Riwayat pesanan beserta pelacakan status pesanan
- Halaman profil pengguna dengan fitur edit profil dan ganti password
- Menampilkan lokasi merchant menggunakan OSMDroid (OpenStreetMap)
- Navigation Compose dengan lebih dari 15 route yang terdefinisi di NavGraph

#### API (Laravel Sanctum)
- `POST /api/auth/register` — registrasi user baru
- `POST /api/auth/login` — login dan return token
- `POST /api/auth/logout` — cabut token dari server
- `GET /api/auth/me` — profil user yang sedang login
- `GET /api/categories` — daftar kategori
- `GET /api/food-listings` — listing aktif dengan filter category dan search
- `GET /api/food-listings/{id}` — detail listing
- `POST /api/orders` — buat pesanan baru
- `GET /api/orders` — riwayat pesanan user
- `GET /api/orders/{id}` — detail dan status pesanan
- `POST /api/orders/{id}/upload-proof` — upload bukti pembayaran

#### Pengujian
- 22 test case manual (P9): authentication, registration, merchant verification, food listing, order management, map, withdrawal, security
- 33 unit test otomatis PHPUnit (P10): `OrderTest` (21 test) dan `FoodListingTest` (12 test)
- Semua test mengikuti pola AAA (Arrange-Act-Assert)

### Fixed

- Bug session hilang saat `SESSION_DOMAIN` diisi string `"null"` — diperbaiki dengan mengosongkan nilai
- Bug `Admin\UserController::restore()` menggunakan `withTrashed()` pada model yang tidak punya trait `SoftDeletes` — diperbaiki ke query biasa dengan `whereNotNull('deleted_at')`
- Bug sidebar portal merchant tidak dapat diakses di viewport mobile (< 860px) karena `display: none` tanpa toggle alternatif — diperbaiki dengan hamburger button dan overlay menggunakan CSS `transform: translateX(-100%)`
- Bug `Merchant\FoodListingController` yang merupakan copy-paste dari `DashboardController` — ditulis ulang dengan logika CRUD yang benar
- Bug `@yield('styles')` diletakkan di dalam tag `<style>` di layout merchant — diperbaiki ke luar `</style>` agar CSS per-halaman tidak konflik
- Bug kalkulasi `discountPercent()` dan `isAvailable()` pada unit test — diselesaikan dengan `setRawAttributes()` untuk bypass Eloquent cast

---

## Catatan Versi

Versi 1.0.0 adalah rilis pertama EcoEats sebagai produk MVP yang dapat didemonstrasikan end-to-end. Fitur review & rating (FR-14, prioritas Low di SRS) tidak diimplementasikan pada versi ini dan dijadwalkan untuk rilis berikutnya.
