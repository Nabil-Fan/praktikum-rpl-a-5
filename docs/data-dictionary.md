# Data Dictionary — EcoEats

## Tabel: `users`

Menyimpan semua akun pengguna platform (user biasa, merchant, dan admin). Peran dibedakan menggunakan kolom `role`.

| Tabel | Kolom | Tipe Data | Constraint | Keterangan |
|---|---|---|---|---|
| users | `id` | INT | PK, AUTO_INCREMENT | ID unik pengguna |
| users | `name` | VARCHAR(100) | NOT NULL | Nama lengkap pengguna |
| users | `email` | VARCHAR(255) | UNIQUE, NOT NULL | Email untuk login dan notifikasi |
| users | `password_hash` | VARCHAR(255) | NOT NULL | Password yang di-hash menggunakan bcrypt (cost factor ≥ 10) |
| users | `phone` | VARCHAR(20) | NULL | Nomor HP (opsional, untuk notifikasi) |
| users | `role` | ENUM('user', 'merchant', 'admin') | NOT NULL, DEFAULT 'user' | Peran pengguna dalam sistem |
| users | `created_at` | TIMESTAMP | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Waktu akun dibuat |
| users | `updated_at` | TIMESTAMP | NOT NULL, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP | Waktu terakhir data diperbarui |

---

## Tabel: `merchant_profiles`

Menyimpan informasi detail dan status verifikasi mitra merchant. Setiap merchant memiliki tepat satu profil (relasi 1:1 dengan `users`).

| Tabel | Kolom | Tipe Data | Constraint | Keterangan |
|---|---|---|---|---|
| merchant_profiles | `id` | INT | PK, AUTO_INCREMENT | ID unik profil merchant |
| merchant_profiles | `user_id` | INT | FK → users.id, UNIQUE, NOT NULL | Referensi ke akun pengguna |
| merchant_profiles | `business_name` | VARCHAR(150) | NOT NULL | Nama usaha kuliner |
| merchant_profiles | `business_address` | TEXT | NOT NULL | Alamat lengkap tempat usaha |
| merchant_profiles | `latitude` | DECIMAL(10,7) | NOT NULL | Koordinat lintang lokasi merchant |
| merchant_profiles | `longitude` | DECIMAL(10,7) | NOT NULL | Koordinat bujur lokasi merchant |
| merchant_profiles | `business_license_url` | VARCHAR(500) | NULL | URL file foto surat izin usaha yang diunggah |
| merchant_profiles | `halal_cert_url` | VARCHAR(500) | NULL | URL file sertifikat halal (jika ada) |
| merchant_profiles | `verification_status` | ENUM('pending', 'approved', 'rejected') | NOT NULL, DEFAULT 'pending' | Status verifikasi dokumen oleh admin |
| merchant_profiles | `verified_at` | TIMESTAMP | NULL | Waktu akun merchant disetujui admin |
| merchant_profiles | `created_at` | TIMESTAMP | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Waktu profil dibuat |

---

## Tabel: `food_listings`

Menyimpan daftar makanan surplus yang dipublikasikan oleh merchant.

| Tabel | Kolom | Tipe Data | Constraint | Keterangan |
|---|---|---|---|---|
| food_listings | `id` | INT | PK, AUTO_INCREMENT | ID unik listing makanan |
| food_listings | `merchant_id` | INT | FK → merchant_profiles.id, NOT NULL | Referensi ke profil merchant pemilik listing |
| food_listings | `name` | VARCHAR(150) | NOT NULL | Nama produk makanan surplus |
| food_listings | `description` | TEXT | NULL | Deskripsi singkat produk |
| food_listings | `category` | VARCHAR(50) | NOT NULL | Kategori makanan (misal: Nasi & Mie, Roti & Kue, Minuman) |
| food_listings | `original_price` | DECIMAL(10,2) | NOT NULL | Harga normal produk sebelum diskon |
| food_listings | `discount_price` | DECIMAL(10,2) | NOT NULL | Harga jual surplus (harga diskon) |
| food_listings | `stock_qty` | INT | NOT NULL, DEFAULT 0 | Jumlah stok tersedia saat ini |
| food_listings | `photo_url` | VARCHAR(500) | NULL | URL foto produk yang diunggah merchant |
| food_listings | `pickup_start` | TIMESTAMP | NOT NULL | Waktu mulai pengambilan tersedia |
| food_listings | `pickup_end` | TIMESTAMP | NOT NULL | Batas waktu pengambilan (deadline) |
| food_listings | `status` | ENUM('available', 'unavailable', 'sold_out') | NOT NULL, DEFAULT 'available' | Status ketersediaan listing |
| food_listings | `created_at` | TIMESTAMP | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Waktu listing dibuat |
| food_listings | `updated_at` | TIMESTAMP | NOT NULL, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP | Waktu terakhir listing diperbarui |

---

## Tabel: `orders`

Menyimpan transaksi pemesanan yang dilakukan user kepada merchant.

| Tabel | Kolom | Tipe Data | Constraint | Keterangan |
|---|---|---|---|---|
| orders | `id` | INT | PK, AUTO_INCREMENT | ID unik pesanan |
| orders | `user_id` | INT | FK → users.id, NOT NULL | Referensi ke pengguna yang memesan |
| orders | `merchant_id` | INT | FK → merchant_profiles.id, NOT NULL | Referensi ke merchant yang menerima pesanan |
| orders | `pickup_code` | VARCHAR(10) | UNIQUE, NOT NULL | Kode unik pengambilan yang diberikan ke user setelah checkout |
| orders | `status` | ENUM('pending', 'confirmed', 'ready', 'completed', 'rejected', 'expired') | NOT NULL, DEFAULT 'pending' | Status pesanan saat ini |
| orders | `total_amount` | DECIMAL(10,2) | NOT NULL | Total harga yang dibayarkan user |
| orders | `payment_method` | VARCHAR(50) | NOT NULL | Metode pembayaran yang dipilih (misal: transfer, e-wallet) |
| orders | `payment_status` | ENUM('unpaid', 'paid', 'refunded') | NOT NULL, DEFAULT 'unpaid' | Status pembayaran |
| orders | `ordered_at` | TIMESTAMP | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Waktu pesanan dibuat |
| orders | `confirmed_at` | TIMESTAMP | NULL | Waktu merchant mengkonfirmasi pesanan |
| orders | `completed_at` | TIMESTAMP | NULL | Waktu pesanan selesai (pickup berhasil) |

---

## Tabel: `order_items`

Tabel penghubung (junction table) antara `orders` dan `food_listings`. Satu pesanan dapat berisi banyak item makanan.

| Tabel | Kolom | Tipe Data | Constraint | Keterangan |
|---|---|---|---|---|
| order_items | `id` | INT | PK, AUTO_INCREMENT | ID unik record item pesanan |
| order_items | `order_id` | INT | FK → orders.id, NOT NULL | Referensi ke pesanan induk |
| order_items | `food_listing_id` | INT | FK → food_listings.id, NOT NULL | Referensi ke listing makanan yang dipesan |
| order_items | `quantity` | INT | NOT NULL, DEFAULT 1 | Jumlah porsi yang dipesan |
| order_items | `unit_price` | DECIMAL(10,2) | NOT NULL | Harga satuan pada saat transaksi (snapshot harga) |
| order_items | `subtotal` | DECIMAL(10,2) | NOT NULL | Hasil perkalian quantity × unit_price |

---

## Tabel: `merchant_verifications`

Menyimpan log riwayat keputusan verifikasi yang dilakukan admin terhadap pengajuan merchant. Mendukung audit trail.

| Tabel | Kolom | Tipe Data | Constraint | Keterangan |
|---|---|---|---|---|
| merchant_verifications | `id` | INT | PK, AUTO_INCREMENT | ID unik record verifikasi |
| merchant_verifications | `merchant_id` | INT | FK → merchant_profiles.id, NOT NULL | Referensi ke profil merchant yang diajukan |
| merchant_verifications | `admin_id` | INT | FK → users.id, NOT NULL | Referensi ke akun admin yang mengambil keputusan |
| merchant_verifications | `action` | ENUM('approved', 'rejected') | NOT NULL | Keputusan admin: disetujui atau ditolak |
| merchant_verifications | `notes` | TEXT | NULL | Catatan atau alasan keputusan dari admin |
| merchant_verifications | `actioned_at` | TIMESTAMP | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Waktu keputusan diambil |

---

## Ringkasan Relasi Antar Tabel

| Dari | Ke | Kardinalitas | Keterangan |
|---|---|---|---|
| `users` |  | `merchant_profiles` | 1 : 0..1 | Satu user (merchant) memiliki satu profil merchant |
| `merchant_profiles` |  | `food_listings` | 1 : N | Satu merchant dapat memposting banyak listing makanan |
| `users` |  | `orders` | 1 : N | Satu user dapat membuat banyak pesanan |
| `merchant_profiles` |  | `orders` | 1 : N | Satu merchant dapat menerima banyak pesanan |
| `orders` |  | `order_items` | 1 : N | Satu pesanan berisi satu atau lebih item |
| `food_listings` |  | `order_items` | 1 : N | Satu listing dapat muncul di banyak pesanan berbeda |
| `merchant_profiles` |  | `merchant_verifications` | 1 : N | Satu merchant bisa memiliki riwayat beberapa kali verifikasi |
| `users` (admin) |  | `merchant_verifications` | 1 : N | Satu admin dapat menangani banyak verifikasi |
