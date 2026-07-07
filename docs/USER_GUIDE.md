# User Guide — EcoEats
## Panduan Penggunaan Platform

Dokumen ini menjelaskan cara menggunakan EcoEats dari sudut pandang pengguna akhir. Tersedia dua portal web (Admin dan Merchant) dan satu aplikasi mobile (Android untuk pembeli).

---

## Portal Merchant (Web)

### 1. Registrasi & Login

**Registrasi akun baru:**
1. Buka `/merchant/register` di browser
2. Isi data akun: nama, email, password
3. Isi profil usaha: nama usaha, alamat, koordinat lokasi
4. Upload dokumen legalitas (opsional saat daftar, wajib untuk verifikasi)
5. Klik **Daftar & Ajukan Verifikasi**
6. Akun aktif dengan status *Menunggu Verifikasi* — tunggu persetujuan admin

**Login:**
1. Buka `/merchant/login`
2. Masukkan email dan password
3. Klik **Masuk ke Dashboard**

> Akun demo: `merchant@test.com` / `password`  
> Akun approved dengan listing: `alena.bakery@ecoeats.id` / `alena1234`

---

### 2. Kelola Menu Surplus

> Fitur ini hanya tersedia setelah akun disetujui admin.

**Tambah menu baru:**
1. Klik **+ Tambah Menu** di Dashboard atau halaman Menu Surplus
2. Isi nama, kategori, deskripsi, harga normal, harga surplus, stok, waktu pickup
3. Upload foto (opsional, JPG/PNG/WebP maks. 2MB)
4. Set status *Langsung Tersedia* atau *Simpan Draft*
5. Klik **Simpan Menu**

**Edit menu:**
1. Buka halaman **Menu Surplus**
2. Klik **Edit** pada menu yang ingin diubah
3. Ubah field yang diperlukan → klik **Simpan Perubahan**

**Hapus menu:**
- Klik ikon 🗑 pada menu → konfirmasi penghapusan

---

### 3. Kelola Pesanan Masuk

**Alur pesanan:**
```
Menunggu Konfirmasi → Dikonfirmasi → Siap Diambil → Selesai
```

**Konfirmasi pesanan:**
1. Buka **Pesanan Masuk** → tab **Menunggu**
2. Klik **✓ Konfirmasi** pada pesanan yang ingin diterima

**Tolak pesanan:**
1. Klik **✕ Tolak** pada pesanan
2. Isi alasan penolakan (wajib)
3. Klik **Tolak Pesanan** — stok otomatis dikembalikan

**Tandai siap diambil:**
- Pada pesanan berstatus *Dikonfirmasi*, klik **🔔 Siap Diambil**

**Selesaikan pesanan (verifikasi pickup):**
1. Buka halaman **Detail Pesanan** berstatus *Siap Diambil*
2. Minta user menunjukkan kode pickup dari aplikasi mobile
3. Masukkan kode di field yang tersedia
4. Klik **✔ Selesaikan Pesanan**

---

### 4. Penarikan Dana (Withdrawal)

1. Buka menu **Penarikan Dana** di sidebar
2. Pastikan saldo tersedia ≥ Rp 10.000
3. Isi nominal, nama bank, nomor rekening, nama pemilik rekening
4. Klik **Ajukan Penarikan**
5. Pantau status di daftar riwayat withdrawal

> Hanya bisa ada satu pengajuan aktif dalam satu waktu.

---

### 5. Peta Lokasi Usaha

- Buka menu **Lokasi Usaha** di sidebar
- Peta menampilkan pin lokasi usaha berdasarkan koordinat yang didaftarkan
- Jika koordinat tidak tepat, perbarui melalui menu **Profil Usaha**

---

## Portal Admin (Web)

> Akun demo: `admin@test.com` / `password`

### 1. Login

1. Buka `/admin/login`
2. Masukkan email dan password admin
3. Klik **Masuk**

---

### 2. Verifikasi Merchant

1. Buka menu **Verifikasi Merchant** → tab **Pending**
2. Klik **Tinjau** pada merchant yang ingin di-review
3. Tinjau dokumen yang diunggah
4. Klik **Setujui** untuk approve, atau **Tolak** (isi alasan) untuk reject
5. Merchant yang disetujui langsung bisa mempublikasikan listing

---

### 3. Monitoring Pesanan

- Buka menu **Semua Pesanan**
- Gunakan tab filter untuk melihat pesanan berdasarkan status
- Gunakan kolom pencarian untuk mencari berdasarkan kode pickup, nama user, atau merchant
- Klik **Detail** untuk melihat informasi lengkap pesanan

> Admin hanya dapat memonitor — perubahan status dilakukan oleh merchant.

---

### 4. Proses Withdrawal

**Approve (ubah ke Diproses):**
1. Buka menu **Withdrawal** → tab **Pending**
2. Klik **Detail** pada withdrawal yang ingin diproses
3. Verifikasi info rekening dan nominal
4. Klik **Setujui & Proses** → lakukan transfer manual ke rekening merchant

**Selesaikan (upload bukti transfer):**
1. Buka detail withdrawal berstatus *Diproses*
2. Klik **Selesai & Upload Bukti**
3. Upload foto/PDF bukti transfer (JPG/PNG/PDF maks. 4MB)
4. Klik **Konfirmasi Selesai**

**Tolak:**
1. Klik **Tolak** → isi alasan (wajib) → konfirmasi

---

### 5. Peta Merchant

- Buka menu **Peta** di sidebar
- Peta menampilkan seluruh merchant approved yang memiliki listing aktif
- Klik pin untuk melihat nama usaha, alamat, dan jumlah listing aktif

---

### 6. Manajemen Lainnya

| Menu | Fungsi |
|---|---|
| Pengguna | Lihat daftar user, aktifkan/nonaktifkan akun |
| Food Listing | Monitor semua listing, hapus yang melanggar |
| Kategori | Tambah, edit, atau hapus kategori makanan |
| Tambah Akun | Buat akun user, merchant, atau admin baru secara manual |

---

## Aplikasi Mobile (Android)

### 1. Login & Registrasi

1. Buka aplikasi EcoEats
2. Tap **DAFTAR** untuk buat akun baru, atau **MASUK** untuk login
3. Isi email dan password → tap tombol konfirmasi
4. Setelah login, masuk ke halaman Dashboard

> Akun demo: `user@test.com` / `password`

---

### 2. Browse Katalog

1. Di Dashboard, lihat daftar makanan surplus yang tersedia
2. Gunakan baris kategori untuk filter (Semua, Nasi, Roti, Mie, dll.)
3. Gunakan search bar untuk cari nama menu atau merchant
4. Tap **Detail** pada card untuk melihat informasi lengkap

---

### 3. Pesan Makanan

1. Di halaman detail listing, pilih jumlah (quantity)
2. Pilih metode pembayaran: QRIS, Transfer, atau E-Wallet
3. Tap **Place Order**
4. Halaman *Menunggu Verifikasi* tampil — tunggu merchant mengkonfirmasi pesanan
5. Setelah dikonfirmasi, lanjut ke halaman **Checkout** → **Pembayaran**

---

### 4. Pembayaran

1. Di halaman Pembayaran, lihat instruksi sesuai metode yang dipilih:
   - **QRIS**: scan QR code yang ditampilkan
   - **Transfer**: transfer ke nomor rekening yang tertera
   - **E-Wallet**: transfer ke nomor yang tertera
2. Upload bukti pembayaran dengan tap area upload → pilih foto dari galeri
3. Tap **Next** setelah upload berhasil

---

### 5. QR Code Pickup

1. Setelah pembayaran dikonfirmasi, halaman **Detail Pesanan** tampil
2. Tap tombol QR Code untuk menampilkan kode pickup dalam format QR
3. Datang ke lokasi merchant → tunjukkan QR code
4. Merchant scan/input kode → pesanan selesai

---

### 6. Riwayat Pesanan & Profil

- **Riwayat Pesanan**: tap ikon Pesanan di bottom navigation bar
- **Profil**: tap ikon Profile → lihat data akun, edit profil, atau ganti password

---

## FAQ

**Merchant tidak bisa tambah listing setelah daftar**
Status verifikasi masih *Pending*. Tunggu admin menyetujui akun terlebih dahulu.

**Kode pickup tidak diterima saat verifikasi**
Pastikan kode diketik dengan benar (tidak case-sensitive). Minta user menunjukkan layar aplikasinya secara langsung.

**Saldo withdrawal 0 padahal ada pesanan selesai**
Cek apakah sudah ada withdrawal yang sedang diproses — tidak bisa ajukan baru jika ada yang aktif.

**Aplikasi mobile tidak bisa connect ke server**
Pastikan HP dan laptop/server berada di jaringan Wi-Fi yang sama, dan BASE_URL di aplikasi sudah diatur ke IP yang benar.
