# Data Dictionary — EcoEats

## Tabel: `users`

Menyimpan semua akun pengguna platform (user biasa, merchant, dan admin). Peran dibedakan menggunakan kolom `role`.

| Kolom | Tipe Data | Constraint | Keterangan |
|---|---|---|---|
| `id` | INT | PK, AUTO_INCREMENT | ID unik pengguna |
| `name` | VARCHAR(100) | NOT NULL | Nama lengkap pengguna |
| `email` | VARCHAR(255) | UNIQUE, NOT NULL | Email untuk login dan notifikasi |
| `password_hash` | VARCHAR(255) | NOT NULL | Password yang di-hash menggunakan bcrypt (cost factor ≥ 10) |
| `phone` | VARCHAR(20) | NULL | Nomor HP (opsional, untuk notifikasi) |
| `role` | ENUM('user', 'merchant', 'admin') | NOT NULL, DEFAULT 'user' | Peran pengguna dalam sistem |
| `created_at` | TIMESTAMP | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Waktu akun dibuat |
| `updated_at` | TIMESTAMP | NOT NULL, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP | Waktu terakhir data diperbarui |
| `deleted_at` | TIMESTAMP | NULL | Soft delete — NULL berarti aktif. Diisi timestamp saat akun dihapus. Semua query aktif **wajib** filter `WHERE deleted_at IS NULL` |

---

## Tabel: `categories`

Menyimpan daftar kategori makanan yang digunakan oleh `food_listings`.

| Kolom | Tipe Data | Constraint | Keterangan |
|---|---|---|---|
| `id` | INT | PK, AUTO_INCREMENT | ID unik kategori |
| `name` | VARCHAR(80) | UNIQUE, NOT NULL | Nama kategori makanan, misal: Nasi & Mie, Roti & Kue, Minuman. UNIQUE mencegah duplikasi |
| `created_at` | TIMESTAMP | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Waktu kategori dibuat |

---

## Tabel: `merchant_profiles`

Menyimpan informasi detail dan status verifikasi mitra merchant. Setiap merchant memiliki tepat satu profil (relasi 1:1 dengan `users`).

| Kolom | Tipe Data | Constraint | Keterangan |
|---|---|---|---|
| `id` | INT | PK, AUTO_INCREMENT | ID unik profil merchant |
| `user_id` | INT | FK → users.id, UNIQUE, NOT NULL | Referensi ke akun pengguna. UNIQUE memastikan 1 user hanya punya 1 profil merchant |
| `business_name` | VARCHAR(150) | NOT NULL | Nama usaha kuliner |
| `business_address` | TEXT | NOT NULL | Alamat lengkap tempat usaha |
| `latitude` | DECIMAL(10,7) | NOT NULL, CHECK(-90 ≤ value ≤ 90) | Koordinat lintang lokasi merchant. Validasi range wajib diterapkan di level aplikasi dan DB |
| `longitude` | DECIMAL(10,7) | NOT NULL, CHECK(-180 ≤ value ≤ 180) | Koordinat bujur lokasi merchant. Validasi range wajib diterapkan di level aplikasi dan DB |
| `business_license_url` | VARCHAR(500) | NULL | URL file foto surat izin usaha yang diunggah |
| `halal_cert_url` | VARCHAR(500) | NULL | URL file sertifikat halal (jika ada) |
| `verification_status` | ENUM('pending', 'approved', 'rejected') | NOT NULL, DEFAULT 'pending' | Status verifikasi dokumen oleh admin |
| `verified_at` | TIMESTAMP | NULL | Waktu akun merchant disetujui admin |
| `created_at` | TIMESTAMP | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Waktu profil dibuat |

---

## Tabel: `food_listings`

Menyimpan daftar makanan surplus yang dipublikasikan oleh merchant.

| Kolom | Tipe Data | Constraint | Keterangan |
|---|---|---|---|
| `id` | INT | PK, AUTO_INCREMENT | ID unik listing makanan |
| `merchant_id` | INT | FK → merchant_profiles.id, NOT NULL | Referensi ke profil merchant pemilik listing |
| `category_id` | INT | FK → categories.id, NOT NULL | Menggantikan kolom `category VARCHAR(50)` bebas. Referensi ke tabel `categories` |
| `name` | VARCHAR(150) | NOT NULL | Nama produk makanan surplus |
| `description` | TEXT | NULL | Deskripsi singkat produk |
| `original_price` | DECIMAL(10,2) | NOT NULL | Harga normal produk sebelum diskon |
| `discount_price` | DECIMAL(10,2) | NOT NULL | Harga jual surplus (harga diskon) |
| `stock_qty` | INT | NOT NULL, DEFAULT 0 | Jumlah stok tersedia saat ini |
| `photo_url` | VARCHAR(500) | NULL | URL foto produk yang diunggah merchant |
| `pickup_start` | TIMESTAMP | NOT NULL | Waktu mulai pengambilan tersedia |
| `pickup_end` | TIMESTAMP | NOT NULL | Batas waktu pengambilan (deadline) |
| `status` | ENUM('available', 'unavailable', 'sold_out') | NOT NULL, DEFAULT 'available' | Status ketersediaan listing |
| `created_at` | TIMESTAMP | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Waktu listing dibuat |
| `updated_at` | TIMESTAMP | NOT NULL, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP | Waktu terakhir listing diperbarui |
| `deleted_at` | TIMESTAMP | NULL | Soft delete listing. NULL = aktif. Query aktif wajib filter `WHERE deleted_at IS NULL` |

---

## Tabel: `orders`

Menyimpan transaksi pemesanan yang dilakukan user kepada merchant.

> **Changelog:** Ditambahkan kolom `cancelled_at`, `cancelled_by`, dan `cancellation_reason` untuk mendukung audit trail pembatalan pesanan. Kolom ditempatkan di tabel ini (bukan `merchant_profiles`) karena alasan pembatalan bersifat per-transaksi — satu merchant dapat memiliki banyak pesanan dengan alasan pembatalan yang berbeda.

| Kolom | Tipe Data | Constraint | Keterangan |
|---|---|---|---|
| `id` | INT | PK, AUTO_INCREMENT | ID unik pesanan |
| `user_id` | INT | FK → users.id, NOT NULL | Referensi ke pengguna yang memesan |
| `merchant_id` | INT | FK → merchant_profiles.id, NOT NULL | Referensi ke merchant yang menerima pesanan |
| `pickup_code` | VARCHAR(10) | UNIQUE, NOT NULL | Kode unik pengambilan yang diberikan ke user setelah checkout |
| `status` | ENUM('pending', 'confirmed', 'ready', 'completed', 'rejected', 'expired') | NOT NULL, DEFAULT 'pending' | Status pesanan saat ini |
| `total_amount` | DECIMAL(10,2) | NOT NULL | Total harga yang dibayarkan user |
| `payment_method` | ENUM('transfer', 'ewallet', 'cash', 'qris') | NOT NULL | Metode pembayaran. Diubah dari VARCHAR bebas ke ENUM untuk mencegah data kotor |
| `ordered_at` | TIMESTAMP | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Waktu pesanan dibuat |
| `expires_at` | TIMESTAMP | NOT NULL | Deadline konfirmasi merchant. Jika terlampaui dan status masih `pending`, scheduler mengubah status ke `expired` |
| `confirmed_at` | TIMESTAMP | NULL | Waktu merchant mengkonfirmasi pesanan |
| `completed_at` | TIMESTAMP | NULL | Waktu pesanan selesai (pickup berhasil) |
| `rejected_at` | TIMESTAMP | NULL | Waktu merchant menolak pesanan. Diisi saat status berubah ke `rejected` |
| `expired_at` | TIMESTAMP | NULL | Waktu pesanan kadaluarsa secara aktual. Diisi oleh scheduler saat status diubah ke `expired` |
| `cancelled_at` | TIMESTAMP | NULL | ✨ **Baru** — Waktu pembatalan dilakukan. NULL jika pesanan tidak dibatalkan |
| `cancelled_by` | ENUM('user', 'merchant', 'system') | NULL | ✨ **Baru** — Aktor yang melakukan pembatalan. NULL jika pesanan tidak dibatalkan |
| `cancellation_reason` | TEXT | NULL | ✨ **Baru** — Alasan pembatalan pesanan untuk keperluan audit. Diisi saat status berubah ke `rejected`. NULL jika pesanan tidak dibatalkan |
| `updated_at` | TIMESTAMP | NOT NULL, DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP | Waktu terakhir record diperbarui |

> **Catatan:** Kolom `payment_status` dihapus dari tabel ini dan dipindahkan ke tabel `payments` yang lebih lengkap.

---

## Tabel: `payments`

Menyimpan riwayat attempt pembayaran untuk setiap pesanan. Dipisahkan dari `orders` agar mendukung multi-gateway (Midtrans, Xendit, manual), retry pembayaran, dan audit riwayat.

| Kolom | Tipe Data | Constraint | Keterangan |
|---|---|---|---|
| `id` | INT | PK, AUTO_INCREMENT | ID unik record pembayaran |
| `order_id` | INT | FK → orders.id, NOT NULL | Referensi ke pesanan terkait. Satu order dapat memiliki lebih dari satu record (retry) |
| `payment_gateway` | VARCHAR(50) | NOT NULL | Nama gateway yang digunakan, misal: `midtrans`, `xendit`, `manual` |
| `transaction_id` | VARCHAR(255) | NULL | ID transaksi dari pihak payment gateway. NULL jika pembayaran manual atau belum diproses |
| `amount` | DECIMAL(10,2) | NOT NULL | Nominal yang dibayarkan pada attempt ini |
| `status` | ENUM('pending', 'paid', 'failed', 'refunded', 'expired') | NOT NULL, DEFAULT 'pending' | Status pembayaran pada attempt ini |
| `payment_proof_url` | VARCHAR(500) | NULL | URL bukti transfer untuk metode manual. NULL jika menggunakan gateway otomatis |
| `paid_at` | TIMESTAMP | NULL | Waktu pembayaran dikonfirmasi berhasil. NULL jika belum lunas |
| `expired_at` | TIMESTAMP | NULL | Batas waktu pembayaran dari gateway. NULL jika tidak ada batas dari gateway |
| `created_at` | TIMESTAMP | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Waktu attempt pembayaran dibuat |

---

## Tabel: `order_items`

Junction table antara `orders` dan `food_listings`. Satu pesanan dapat berisi banyak item makanan.

| Kolom | Tipe Data | Constraint | Keterangan |
|---|---|---|---|
| `id` | INT | PK, AUTO_INCREMENT | ID unik record item pesanan |
| `order_id` | INT | FK → orders.id, NOT NULL | Referensi ke pesanan induk |
| `food_listing_id` | INT | FK → food_listings.id, NOT NULL | Referensi ke listing makanan yang dipesan |
| `listing_name` | VARCHAR(150) | NOT NULL | Snapshot nama produk saat transaksi. Mencegah data historis rusak jika listing diubah atau di-soft-delete |
| `quantity` | INT | NOT NULL, DEFAULT 1 | Jumlah porsi yang dipesan |
| `unit_price` | DECIMAL(10,2) | NOT NULL | Harga satuan pada saat transaksi (snapshot harga) |
| `subtotal` | DECIMAL(10,2) | NOT NULL | Hasil perkalian quantity × unit_price |

---

## Tabel: `merchant_verifications`

Menyimpan log riwayat keputusan verifikasi yang dilakukan admin terhadap pengajuan merchant. Mendukung audit trail.

| Kolom | Tipe Data | Constraint | Keterangan |
|---|---|---|---|
| `id` | INT | PK, AUTO_INCREMENT | ID unik record verifikasi |
| `merchant_id` | INT | FK → merchant_profiles.id, NOT NULL | Referensi ke profil merchant yang diajukan |
| `admin_id` | INT | FK → users.id, NOT NULL | Referensi ke akun admin (role = admin) yang mengambil keputusan |
| `action` | ENUM('approved', 'rejected') | NOT NULL | Keputusan admin: disetujui atau ditolak |
| `notes` | TEXT | NULL | Catatan atau alasan keputusan dari admin |
| `actioned_at` | TIMESTAMP | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Waktu keputusan diambil |

---

## Tabel: `withdrawals`

**Tabel baru** — Menyimpan riwayat permintaan pencairan dana (withdrawal) dari saldo merchant ke rekening bank. Saldo merchant bertambah setiap kali pesanan berstatus `completed` dan pembayaran user telah dikonfirmasi. Merchant kemudian mengajukan withdrawal untuk mencairkan saldo tersebut.

> **Catatan:** Saldo merchant yang dapat ditarik dihitung dari total `payments` berstatus `paid` dikurangi total `withdrawals` berstatus `completed` per merchant.

| Kolom | Tipe Data | Constraint | Keterangan |
|---|---|---|---|
| `id` | INT | PK, AUTO_INCREMENT | ID unik record penarikan dana |
| `merchant_id` | INT | FK → merchant_profiles.id, NOT NULL | Referensi ke merchant yang mengajukan penarikan |
| `amount` | DECIMAL(10,2) | NOT NULL | Nominal dana yang diminta untuk dicairkan |
| `bank_name` | VARCHAR(100) | NOT NULL | Nama bank tujuan pencairan, misal: BCA, BRI, Mandiri |
| `bank_account_number` | VARCHAR(50) | NOT NULL | Nomor rekening bank tujuan pencairan |
| `bank_account_name` | VARCHAR(150) | NOT NULL | Nama pemilik rekening sesuai data bank (untuk verifikasi) |
| `status` | ENUM('pending', 'processing', 'completed', 'rejected') | NOT NULL, DEFAULT 'pending' | Status proses pencairan dana. `pending` = menunggu diproses admin; `processing` = sedang ditransfer; `completed` = dana berhasil dikirim; `rejected` = ditolak |
| `admin_id` | INT | FK → users.id, NULL | Referensi ke admin yang memproses atau menolak withdrawal. NULL jika belum diproses |
| `admin_notes` | TEXT | NULL | Catatan dari admin, misal alasan penolakan. NULL jika tidak ada catatan |
| `transfer_proof_url` | VARCHAR(500) | NULL | URL bukti transfer dari admin setelah dana berhasil dikirim. NULL jika belum selesai |
| `requested_at` | TIMESTAMP | NOT NULL, DEFAULT CURRENT_TIMESTAMP | Waktu merchant mengajukan permintaan withdrawal |
| `processed_at` | TIMESTAMP | NULL | Waktu admin mulai memproses withdrawal. NULL jika masih pending |
| `completed_at` | TIMESTAMP | NULL | Waktu dana berhasil dikirim ke rekening merchant. NULL jika belum selesai |

---

## Ringkasan Relasi Antar Tabel

| Dari | Ke | Kardinalitas | Keterangan |
|---|---|---|---|
| `users` | `merchant_profiles` | 1 : 0..1 | Satu user (merchant) memiliki nol atau satu profil merchant |
| `categories` | `food_listings` | 1 : N | Satu kategori dapat digunakan oleh banyak listing makanan |
| `merchant_profiles` | `food_listings` | 1 : N | Satu merchant dapat memposting banyak listing makanan |
| `users` | `orders` | 1 : N | Satu user dapat membuat banyak pesanan |
| `merchant_profiles` | `orders` | 1 : N | Satu merchant dapat menerima banyak pesanan |
| `orders` | `order_items` | 1 : N | Satu pesanan berisi satu atau lebih item |
| `food_listings` | `order_items` | 1 : N | Satu listing dapat muncul di banyak pesanan berbeda |
| `orders` | `payments` | 1 : N | Satu pesanan dapat memiliki beberapa record pembayaran (retry) |
| `merchant_profiles` | `merchant_verifications` | 1 : N | Satu merchant bisa memiliki riwayat beberapa kali verifikasi |
| `users` (admin) | `merchant_verifications` | 1 : N | Satu admin dapat menangani banyak verifikasi |
| `merchant_profiles` | `withdrawals` | 1 : N | Satu merchant dapat mengajukan banyak permintaan pencairan dana |
| `users` (admin) | `withdrawals` | 1 : N | Satu admin dapat memproses banyak permintaan pencairan dana |
| `food_listings` | `orders` | M : N | Satu listing dapat dipesan di banyak pesanan, satu pesanan dapat berisi banyak listing (ditangani oleh junction table order_items) |
