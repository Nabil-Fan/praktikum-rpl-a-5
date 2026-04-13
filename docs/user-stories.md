# User Stories — EcoEats

## Aktor / Role Pengguna

| Role | Deskripsi |
|------|-----------|
| **User** | Masyarakat umum, komunitas, atau lembaga sosial yang mencari dan memesan makanan surplus di Kota Solo |
| **Mitra Merchant** | Pelaku usaha kuliner (restoran, kafe, katering, toko roti, warung, hotel) yang menjual makanan surplus melalui EcoEats |
| **Admin** | Pengelola platform EcoEats yang memverifikasi akun merchant dan memantau aktivitas platform |

---

## Fitur 1 — Autentikasi Pengguna

### US-01 — Registrasi Akun Baru

**User Story:**
As a **user**, I want to register a new account using my email and password, so that I can access the EcoEats platform and start ordering surplus food.

**Acceptance Criteria:**
- **AC-1:** Given saya belum memiliki akun, When saya mengisi form registrasi dengan email valid dan password, Then sistem membuat akun baru dan mengarahkan saya ke halaman utama.
- **AC-2:** Given saya mengisi email yang sudah terdaftar, When saya menekan tombol daftar, Then sistem menampilkan pesan error "Email sudah digunakan".

---

### US-02 — Login ke Akun

**User Story:**
As a **user**, I want to log in to my account, so that I can access my profile and place orders.

**Acceptance Criteria:**
- **AC-1:** Given saya sudah memiliki akun terdaftar, When saya memasukkan email dan password yang benar, Then sistem mengarahkan saya ke halaman utama sebagai pengguna terautentikasi.
- **AC-2:** Given saya memasukkan password yang salah, When saya menekan tombol login, Then sistem menampilkan pesan error "Email atau password salah".

---

### US-03 — Login sebagai Mitra Merchant

**User Story:**
As a **mitra merchant**, I want to log in to my merchant account, so that I can manage my surplus menu and confirm incoming orders.

**Acceptance Criteria:**
- **AC-1:** Given akun merchant saya sudah terverifikasi oleh admin, When saya login dengan kredensial merchant, Then sistem mengarahkan saya ke dashboard merchant.
- **AC-2:** Given akun merchant saya belum terverifikasi, When saya mencoba login, Then sistem menampilkan pesan "Akun Anda sedang dalam proses verifikasi".

---

## Fitur 2 — Manajemen Menu Surplus

### US-04 — Menambahkan Menu Surplus

**User Story:**
As a **mitra merchant**, I want to add surplus menu items with discounted prices and available stock, so that users can see and order my remaining food before it goes to waste.

**Acceptance Criteria:**
- **AC-1:** Given saya sudah login sebagai merchant terverifikasi, When saya mengisi nama menu, harga diskon, stok, dan foto lalu menekan simpan, Then menu surplus baru tampil di daftar menu aktif saya.
- **AC-2:** Given saya tidak mengisi salah satu kolom wajib, When saya menekan simpan, Then sistem menampilkan pesan validasi pada kolom yang belum diisi.

---

### US-05 — Mengedit atau Menonaktifkan Menu Surplus

**User Story:**
As a **mitra merchant**, I want to edit or deactivate a surplus menu item, so that the information displayed on the platform always reflects actual availability.

**Acceptance Criteria:**
- **AC-1:** Given menu surplus sudah habis terjual, When saya menekan tombol nonaktifkan pada menu tersebut, Then menu tidak lagi tampil di daftar yang dilihat user.
- **AC-2:** Given saya ingin mengubah harga atau stok, When saya mengedit dan menyimpan perubahan, Then data terbaru langsung tampil di platform.

---

### US-06 — Melihat Daftar Menu Surplus

**User Story:**
As a **user**, I want to browse the list of available surplus menu items, so that I can choose food that I want to order.

**Acceptance Criteria:**
- **AC-1:** Given saya sudah login sebagai user, When saya membuka halaman daftar menu, Then sistem menampilkan semua menu surplus yang masih aktif beserta harga diskon dan stok tersisa.
- **AC-2:** Given tidak ada menu surplus yang tersedia, When saya membuka halaman daftar menu, Then sistem menampilkan pesan "Belum ada menu surplus tersedia saat ini".

---

## Fitur 3 — Peta Lokasi Merchant

### US-07 — Melihat Lokasi Merchant di Peta

**User Story:**
As a **user**, I want to see merchant locations on a map, so that I can find the nearest merchant from my current position.

**Acceptance Criteria:**
- **AC-1:** Given saya mengizinkan akses lokasi, When saya membuka halaman peta, Then sistem menampilkan pin lokasi semua merchant aktif beserta nama dan jarak dari posisi saya.
- **AC-2:** Given saya tidak mengizinkan akses lokasi, When saya membuka halaman peta, Then sistem tetap menampilkan peta dengan seluruh merchant tanpa informasi jarak.

---

### US-08 — Mendapatkan Rute ke Merchant

**User Story:**
As a **user**, I want to get navigation directions to a merchant, so that I can easily reach the pickup location.

**Acceptance Criteria:**
- **AC-1:** Given saya memilih salah satu merchant di peta, When saya menekan tombol "Petunjuk Arah", Then sistem membuka aplikasi navigasi dengan rute dari posisi saya ke lokasi merchant.

---

## Fitur 4 — Pemesanan Makanan

### US-09 — Memesan Makanan Surplus

**User Story:**
As a **user**, I want to place an order for surplus food from a merchant, so that I can secure the food before it runs out.

**Acceptance Criteria:**
- **AC-1:** Given saya memilih menu dan menekan pesan, When stok masih tersedia, Then sistem membuat pesanan dan mengirim notifikasi ke merchant.
- **AC-2:** Given stok menu sudah habis, When saya mencoba memesan, Then sistem menampilkan pesan "Stok habis" dan mencegah pesanan dibuat.

---

### US-10 — Konfirmasi Pesanan oleh Merchant

**User Story:**
As a **mitra merchant**, I want to confirm or reject incoming orders, so that I can ensure food availability before the user comes to pick it up.

**Acceptance Criteria:**
- **AC-1:** Given ada pesanan masuk, When saya menekan tombol konfirmasi, Then status pesanan berubah menjadi "Dikonfirmasi" dan user menerima notifikasi.
- **AC-2:** Given saya tidak dapat memenuhi pesanan, When saya menekan tolak dan mengisi alasan, Then status pesanan berubah menjadi "Ditolak" dan user mendapat notifikasi beserta alasannya.

---

### US-11 — Menyelesaikan Pengambilan Pesanan

**User Story:**
As a **mitra merchant**, I want to mark an order as completed after the user picks up the food, so that the transaction history is recorded accurately.

**Acceptance Criteria:**
- **AC-1:** Given user telah mengambil pesanan, When saya menekan tombol "Selesai", Then status pesanan berubah menjadi "Selesai" dan transaksi tercatat di riwayat kedua pihak.

---

## Fitur 5 — Verifikasi Merchant

### US-12 — Pengajuan Verifikasi oleh Merchant Baru

**User Story:**
As a **mitra merchant** baru, I want to submit a verification request for my account, so that I can start selling surplus food on the EcoEats platform.

**Acceptance Criteria:**
- **AC-1:** Given saya sudah mendaftar sebagai merchant, When saya mengunggah dokumen usaha dan menekan ajukan verifikasi, Then sistem menyimpan pengajuan dan menampilkan status "Menunggu Verifikasi".
- **AC-2:** Given dokumen yang diunggah tidak lengkap, When saya menekan ajukan verifikasi, Then sistem menampilkan pesan validasi dan mencegah pengajuan dikirim.

---

### US-13 — Verifikasi Merchant oleh Admin

**User Story:**
As an **admin**, I want to review and verify or reject new merchant account applications, so that only legitimate merchants can sell on the platform.

**Acceptance Criteria:**
- **AC-1:** Given ada pengajuan verifikasi merchant baru, When saya menekan setujui setelah meninjau dokumen, Then akun merchant diaktifkan dan merchant menerima notifikasi bahwa akunnya telah terverifikasi.
- **AC-2:** Given dokumen merchant tidak memenuhi syarat, When saya menekan tolak dan mengisi alasan penolakan, Then akun merchant tetap nonaktif dan merchant menerima notifikasi beserta alasan penolakannya.

---

## Others

### US-14 — Memberikan Rating setelah Pesanan Selesai

**User Story:**
As a **user**, I want memberikan rating dan ulasan kepada merchant setelah pesanan selesai diambil, so that pengguna lain dapat memilih merchant yang terpercaya dan berkualitas.

**Acceptance Criteria:**
- **AC-1:** Given pesanan saya berstatus "Selesai", When saya membuka riwayat pesanan dan menekan "Beri Rating", Then saya dapat memilih bintang 1–5 dan menuliskan ulasan lalu menekan "Kirim."
- **AC-2:** Given saya sudah memberikan rating pada pesanan tersebut, When saya membuka riwayat pesanan yang sama, Then tombol "Beri Rating" tidak muncul dan ulasan saya sudah tampil di halaman profil merchant.

---

## Rekap User Stories

| ID | Fitur | Role | Prioritas |
|----|-------|------|-----------|
| US-01 | Autentikasi | User | Must-have |
| US-02 | Autentikasi | User | Must-have |
| US-03 | Autentikasi | Mitra Merchant | Must-have |
| US-04 | Manajemen Menu Surplus | Mitra Merchant | Must-have |
| US-05 | Manajemen Menu Surplus | Mitra Merchant | Must-have |
| US-06 | Manajemen Menu Surplus | User | Must-have |
| US-07 | Peta Lokasi Merchant | User | Should-have |
| US-08 | Peta Lokasi Merchant | User | Could-have |
| US-09 | Pemesanan Makanan | User | Must-have |
| US-10 | Pemesanan Makanan | Mitra Merchant | Must-have |
| US-11 | Pemesanan Makanan | Mitra Merchant | Should-have |
| US-12 | Verifikasi Merchant | Mitra Merchant | Must-have |
| US-13 | Verifikasi Merchant | Admin | Must-have |
| US-14 | Memberikan Rating | User | Won't-have |