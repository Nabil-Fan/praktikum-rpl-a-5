# Test Cases — EcoEats
# P9 — Rekayasa Perangkat Lunak Kelas A

Dokumen ini berisi test case manual yang dibuat berdasarkan acceptance criteria dari user story dan SRS EcoEats. Test case mencakup happy path dan unhappy path untuk seluruh fitur inti yang sudah diimplementasikan.

---

## Daftar Test Case

| TC-ID | Judul | Fitur | Status |
|---|---|---|---|
| TC-001 | Login merchant dengan kredensial valid | Authentication | Pass |
| TC-002 | Login merchant dengan password salah | Authentication | Pass |
| TC-003 | Login merchant yang belum terdaftar | Authentication | Pass |
| TC-004 | Registrasi merchant baru dengan data lengkap | Registration | Pass |
| TC-005 | Registrasi merchant dengan email yang sudah terdaftar | Registration | Pass |
| TC-006 | Registrasi merchant dengan password tidak cocok | Registration | Pass |
| TC-007 | Admin approve merchant yang pending | Merchant Verification | Pass |
| TC-008 | Admin reject merchant tanpa mengisi alasan | Merchant Verification | Pass |
| TC-009 | Admin reject merchant dengan alasan | Merchant Verification | Pass |
| TC-010 | Merchant menambah food listing baru | Food Listing | Pass |
| TC-011 | Merchant menambah listing dengan harga surplus lebih tinggi dari harga normal | Food Listing | Pass |
| TC-012 | Merchant menambah listing dengan stok 0 | Food Listing | Pass |
| TC-013 | Merchant yang belum approved mencoba tambah listing | Food Listing | Pass |
| TC-014 | Merchant mengkonfirmasi pesanan masuk | Order Management | Pass |
| TC-015 | Merchant menolak pesanan tanpa mengisi alasan | Order Management | Pass |
| TC-016 | Merchant menyelesaikan pesanan dengan kode pickup yang benar | Order Management | Pass |
| TC-017 | Merchant menyelesaikan pesanan dengan kode pickup yang salah | Order Management | Pass |
| TC-018 | Peta admin menampilkan merchant aktif | Map | Pass |
| TC-019 | Peta merchant menampilkan lokasi usaha sendiri | Map | Pass |
| TC-020 | Merchant mengajukan withdrawal melebihi saldo | Withdrawal | Pass |
| TC-021 | Admin logout dari sistem | Authentication | Pass |
| TC-022 | Akses halaman admin tanpa login | Authentication | Pass |

---

## Detail Test Case

---

### TC-001 — Login Merchant dengan Kredensial Valid

| Field | Detail |
|---|---|
| **TC-ID** | TC-001 |
| **Judul** | Login merchant dengan kredensial valid |
| **Fitur** | Authentication — FR-03 |
| **Precondition** | Akun merchant sudah terdaftar di database. Halaman /merchant/login terbuka di browser. |

**Steps:**
1. Buka halaman `/merchant/login`
2. Isi field Email dengan `alena.bakery@ecoeats.id`
3. Isi field Password dengan `alena1234`
4. Klik tombol "Masuk"

| Field | Detail |
|---|---|
| **Expected Result** | Sistem memvalidasi kredensial, membuat session, dan melakukan redirect ke `/merchant/dashboard` |
| **Actual Result** | Berhasil login dan diarahkan ke halaman Dashboard Merchant |
| **Status** | ✅ Pass |

---

### TC-002 — Login Merchant dengan Password Salah

| Field | Detail |
|---|---|
| **TC-ID** | TC-002 |
| **Judul** | Login merchant dengan password salah |
| **Fitur** | Authentication — FR-03 |
| **Precondition** | Akun merchant sudah terdaftar. Halaman /merchant/login terbuka. |

**Steps:**
1. Buka halaman `/merchant/login`
2. Isi field Email dengan `alena.bakery@ecoeats.id`
3. Isi field Password dengan `passwordsalah`
4. Klik tombol "Masuk"

| Field | Detail |
|---|---|
| **Expected Result** | Sistem menolak login, menampilkan pesan error "These credentials do not match our records" atau sejenisnya, tetap di halaman login |
| **Actual Result** | Pesan error muncul, user tetap di halaman login |
| **Status** | ✅ Pass |

---

### TC-003 — Login Merchant dengan Email Tidak Terdaftar

| Field | Detail |
|---|---|
| **TC-ID** | TC-003 |
| **Judul** | Login merchant dengan email yang belum terdaftar |
| **Fitur** | Authentication — FR-03 |
| **Precondition** | Halaman /merchant/login terbuka. Email tidak ada di database. |

**Steps:**
1. Buka halaman `/merchant/login`
2. Isi field Email dengan `tidakada@email.com`
3. Isi field Password dengan `password123`
4. Klik tombol "Masuk"

| Field | Detail |
|---|---|
| **Expected Result** | Sistem menolak login dengan pesan error, tetap di halaman login |
| **Actual Result** | Pesan error muncul, user tetap di halaman login |
| **Status** | ✅ Pass |

---

### TC-004 — Registrasi Merchant Baru dengan Data Lengkap

| Field | Detail |
|---|---|
| **TC-ID** | TC-004 |
| **Judul** | Registrasi merchant baru dengan semua data lengkap |
| **Fitur** | Registration — FR-03, FR-12 |
| **Precondition** | Email yang akan didaftarkan belum ada di database. Halaman /merchant/register terbuka. |

**Steps:**
1. Buka halaman `/merchant/register`
2. Isi Nama Lengkap: `Toko Baru Test`
3. Isi No. HP: `081234567890`
4. Isi Email: `toko.baru@test.com`
5. Isi Password: `password123`
6. Isi Konfirmasi Password: `password123`
7. Isi Nama Usaha: `Toko Baru`
8. Isi Alamat: `Jl. Test No. 1, Solo`
9. Isi Latitude: `-7.5695`
10. Isi Longitude: `110.8270`
11. Klik tombol "Daftar & Ajukan Verifikasi"

| Field | Detail |
|---|---|
| **Expected Result** | Akun berhasil dibuat dengan role merchant, profil merchant dibuat dengan status pending, diarahkan ke halaman login merchant |
| **Actual Result** | Akun merchant berhasil terdaftar, profil tercatat dengan status pending di database |
| **Status** | ✅ Pass |

---

### TC-005 — Registrasi Merchant dengan Email yang Sudah Terdaftar

| Field | Detail |
|---|---|
| **TC-ID** | TC-005 |
| **Judul** | Registrasi merchant dengan email yang sudah digunakan |
| **Fitur** | Registration — FR-03 |
| **Precondition** | Email `alena.bakery@ecoeats.id` sudah terdaftar di database. |

**Steps:**
1. Buka halaman `/merchant/register`
2. Isi semua field dengan data valid
3. Isi Email dengan `alena.bakery@ecoeats.id` (sudah terdaftar)
4. Klik tombol "Daftar & Ajukan Verifikasi"

| Field | Detail |
|---|---|
| **Expected Result** | Sistem menolak registrasi, menampilkan error validasi "The email has already been taken", tetap di halaman register |
| **Actual Result** | Pesan error validasi email muncul, form tidak diproses |
| **Status** | ✅ Pass |

---

### TC-006 — Registrasi Merchant dengan Password Tidak Cocok

| Field | Detail |
|---|---|
| **TC-ID** | TC-006 |
| **Judul** | Registrasi merchant dengan konfirmasi password tidak sesuai |
| **Fitur** | Registration — FR-03 |
| **Precondition** | Halaman /merchant/register terbuka. |

**Steps:**
1. Buka halaman `/merchant/register`
2. Isi semua field dengan data valid
3. Isi Password: `password123`
4. Isi Konfirmasi Password: `berbeda456`
5. Klik tombol "Daftar & Ajukan Verifikasi"

| Field | Detail |
|---|---|
| **Expected Result** | Sistem menampilkan error validasi bahwa konfirmasi password tidak sesuai, akun tidak dibuat |
| **Actual Result** | Pesan error konfirmasi password muncul, form tidak diproses |
| **Status** | ✅ Pass |

---

### TC-007 — Admin Approve Merchant yang Pending

| Field | Detail |
|---|---|
| **TC-ID** | TC-007 |
| **Judul** | Admin menyetujui pengajuan verifikasi merchant |
| **Fitur** | Merchant Verification — FR-13 |
| **Precondition** | Login sebagai admin. Ada minimal satu merchant dengan status pending di database. |

**Steps:**
1. Login sebagai admin di `/admin/login`
2. Buka menu "Verifikasi Merchant"
3. Klik tab "Pending"
4. Klik tombol "Tinjau" pada salah satu merchant pending
5. Tinjau dokumen yang tersedia
6. Klik tombol "Setujui"
7. Isi catatan (opsional)
8. Konfirmasi approval

| Field | Detail |
|---|---|
| **Expected Result** | Status merchant berubah menjadi approved, record keputusan tersimpan di tabel merchant_verifications, merchant dapat mulai publish listing |
| **Actual Result** | Status merchant berubah ke approved, tercatat di merchant_verifications dengan admin_id yang benar |
| **Status** | ✅ Pass |

---

### TC-008 — Admin Reject Merchant Tanpa Mengisi Alasan

| Field | Detail |
|---|---|
| **TC-ID** | TC-008 |
| **Judul** | Admin menolak merchant tanpa mengisi alasan penolakan |
| **Fitur** | Merchant Verification — FR-13 |
| **Precondition** | Login sebagai admin. Ada merchant dengan status pending. |

**Steps:**
1. Login sebagai admin
2. Buka halaman detail merchant pending
3. Klik tombol "Tolak"
4. Biarkan field alasan penolakan kosong
5. Klik "Konfirmasi Penolakan"

| Field | Detail |
|---|---|
| **Expected Result** | Sistem menolak aksi, menampilkan pesan validasi bahwa alasan penolakan wajib diisi |
| **Actual Result** | Validasi aktif, form tidak tersubmit tanpa alasan |
| **Status** | ✅ Pass |

---

### TC-009 — Admin Reject Merchant dengan Alasan

| Field | Detail |
|---|---|
| **TC-ID** | TC-009 |
| **Judul** | Admin menolak merchant dengan mengisi alasan penolakan |
| **Fitur** | Merchant Verification — FR-13 |
| **Precondition** | Login sebagai admin. Ada merchant dengan status pending. |

**Steps:**
1. Login sebagai admin
2. Buka halaman detail merchant pending
3. Klik tombol "Tolak"
4. Isi alasan: `Dokumen surat izin usaha tidak terbaca/buram`
5. Klik "Konfirmasi Penolakan"

| Field | Detail |
|---|---|
| **Expected Result** | Status merchant berubah menjadi rejected, alasan tersimpan di merchant_verifications, merchant tidak dapat publish listing |
| **Actual Result** | Status berubah ke rejected, catatan alasan tersimpan dengan benar |
| **Status** | ✅ Pass |

---

### TC-010 — Merchant Menambah Food Listing Baru

| Field | Detail |
|---|---|
| **TC-ID** | TC-010 |
| **Judul** | Merchant menambahkan food listing surplus baru dengan data lengkap |
| **Fitur** | Food Listing — FR-04 |
| **Precondition** | Login sebagai merchant dengan status approved. Ada minimal satu kategori di database. |

**Steps:**
1. Login sebagai `alena.bakery@ecoeats.id`
2. Buka menu "Menu Surplus"
3. Klik tombol "+ Tambah Menu"
4. Isi Nama Menu: `Croissant Sisa Sore`
5. Pilih Kategori: `Roti & Kue`
6. Isi Deskripsi: `Croissant fresh dipanggang pagi, sisa stok sore hari`
7. Isi Harga Normal: `25000`
8. Isi Harga Surplus: `12000`
9. Isi Stok: `5`
10. Isi Mulai Pickup: hari ini jam 16:00
11. Isi Batas Pickup: hari ini jam 20:00
12. Set Status: `Langsung Tersedia`
13. Klik "Simpan Menu"

| Field | Detail |
|---|---|
| **Expected Result** | Listing berhasil tersimpan di database dengan status available, muncul di halaman daftar listing merchant |
| **Actual Result** | Listing berhasil dibuat dan tampil di daftar menu surplus |
| **Status** | ✅ Pass |

---

### TC-011 — Merchant Menambah Listing dengan Harga Surplus Lebih Tinggi dari Harga Normal

| Field | Detail |
|---|---|
| **TC-ID** | TC-011 |
| **Judul** | Validasi harga: harga surplus tidak boleh lebih tinggi dari harga normal |
| **Fitur** | Food Listing — FR-04 |
| **Precondition** | Login sebagai merchant approved. Halaman tambah listing terbuka. |

**Steps:**
1. Buka form tambah menu surplus
2. Isi Harga Normal: `15000`
3. Isi Harga Surplus: `20000` (lebih tinggi dari harga normal)
4. Isi field lain dengan data valid
5. Klik "Simpan Menu"

| Field | Detail |
|---|---|
| **Expected Result** | Sistem menolak form, menampilkan pesan validasi bahwa harga surplus harus lebih rendah dari harga normal |
| **Actual Result** | Validasi Laravel (rule: lt:original_price) aktif, form tidak tersubmit |
| **Status** | ✅ Pass |

---

### TC-012 — Merchant Menambah Listing dengan Stok 0

| Field | Detail |
|---|---|
| **TC-ID** | TC-012 |
| **Judul** | Validasi stok: stok tidak boleh 0 atau negatif saat membuat listing baru |
| **Fitur** | Food Listing — FR-04 |
| **Precondition** | Login sebagai merchant approved. Halaman tambah listing terbuka. |

**Steps:**
1. Buka form tambah menu surplus
2. Isi semua field dengan data valid
3. Isi Stok: `0`
4. Klik "Simpan Menu"

| Field | Detail |
|---|---|
| **Expected Result** | Sistem menolak form dengan pesan validasi bahwa stok minimal 1 |
| **Actual Result** | Validasi aktif (min:1), listing tidak disimpan |
| **Status** | ✅ Pass |

---

### TC-013 — Merchant Belum Approved Mencoba Tambah Listing

| Field | Detail |
|---|---|
| **TC-ID** | TC-013 |
| **Judul** | Merchant dengan status pending tidak dapat mempublikasikan listing |
| **Fitur** | Food Listing — FR-04, Business Rule |
| **Precondition** | Login sebagai merchant dengan verification_status = pending. |

**Steps:**
1. Login sebagai merchant dengan status pending
2. Perhatikan halaman dashboard
3. Coba klik tombol "Tambah Menu Surplus" di dashboard
4. Coba akses langsung `/merchant/listings/create`

| Field | Detail |
|---|---|
| **Expected Result** | Tombol di dashboard tidak aktif (disabled). Akses langsung ke URL create diarahkan kembali ke dashboard dengan pesan error bahwa akun belum terverifikasi |
| **Actual Result** | Tombol disabled di dashboard, controller cek isApproved() dan redirect dengan pesan error |
| **Status** | ✅ Pass |

---

### TC-014 — Merchant Mengkonfirmasi Pesanan Masuk

| Field | Detail |
|---|---|
| **TC-ID** | TC-014 |
| **Judul** | Merchant mengkonfirmasi pesanan yang berstatus pending |
| **Fitur** | Order Management — FR-10 |
| **Precondition** | Login sebagai merchant. Ada pesanan dengan status pending di database. |

**Steps:**
1. Login sebagai merchant
2. Buka menu "Pesanan Masuk"
3. Pilih tab "Menunggu"
4. Pada salah satu pesanan pending, klik tombol "✓ Konfirmasi"

| Field | Detail |
|---|---|
| **Expected Result** | Status pesanan berubah menjadi confirmed, kolom confirmed_at terisi timestamp saat ini, flash message sukses muncul |
| **Actual Result** | Status berubah ke confirmed, timestamp tercatat, pesan sukses tampil |
| **Status** | ✅ Pass |

---

### TC-015 — Merchant Menolak Pesanan Tanpa Mengisi Alasan

| Field | Detail |
|---|---|
| **TC-ID** | TC-015 |
| **Judul** | Validasi penolakan pesanan: alasan wajib diisi |
| **Fitur** | Order Management — FR-10 |
| **Precondition** | Login sebagai merchant. Ada pesanan dengan status pending. |

**Steps:**
1. Login sebagai merchant
2. Buka halaman Pesanan Masuk
3. Klik tombol "✕ Tolak" pada pesanan pending
4. Modal muncul — biarkan textarea alasan kosong
5. Klik tombol "Tolak Pesanan" di modal

| Field | Detail |
|---|---|
| **Expected Result** | Form tidak tersubmit karena field alasan required, browser menampilkan validasi HTML5 |
| **Actual Result** | Validasi required pada textarea aktif, form tidak dikirim |
| **Status** | ✅ Pass |

---

### TC-016 — Merchant Menyelesaikan Pesanan dengan Kode Pickup yang Benar

| Field | Detail |
|---|---|
| **TC-ID** | TC-016 |
| **Judul** | Verifikasi pickup berhasil dengan kode yang benar |
| **Fitur** | Order Management — FR-09 |
| **Precondition** | Login sebagai merchant. Ada pesanan dengan status ready. Kode pickup pesanan diketahui. |

**Steps:**
1. Login sebagai merchant
2. Buka halaman detail pesanan berstatus "Siap Diambil"
3. Input kode pickup yang benar di field verifikasi
4. Klik "✔ Selesaikan Pesanan"

| Field | Detail |
|---|---|
| **Expected Result** | Pesanan berubah status menjadi completed, completed_at terisi, flash message sukses tampil, saldo merchant bertambah |
| **Actual Result** | Status berubah ke completed, timestamp tercatat, pesan sukses tampil |
| **Status** | ✅ Pass |

---

### TC-017 — Merchant Menyelesaikan Pesanan dengan Kode Pickup yang Salah

| Field | Detail |
|---|---|
| **TC-ID** | TC-017 |
| **Judul** | Verifikasi pickup gagal dengan kode yang salah |
| **Fitur** | Order Management — FR-09 |
| **Precondition** | Login sebagai merchant. Ada pesanan dengan status ready. |

**Steps:**
1. Login sebagai merchant
2. Buka halaman detail pesanan berstatus "Siap Diambil"
3. Input kode pickup yang salah, misalnya `SALAH123`
4. Klik "✔ Selesaikan Pesanan"

| Field | Detail |
|---|---|
| **Expected Result** | Sistem menolak aksi, menampilkan flash error "Kode pickup tidak cocok", status pesanan tidak berubah |
| **Actual Result** | Pesan error tampil, pesanan tetap berstatus ready |
| **Status** | ✅ Pass |

---

### TC-018 — Peta Admin Menampilkan Merchant Aktif

| Field | Detail |
|---|---|
| **TC-ID** | TC-018 |
| **Judul** | Peta admin menampilkan pin untuk merchant yang memiliki listing aktif |
| **Fitur** | Map — FR-07 |
| **Precondition** | Login sebagai admin. Minimal ada satu merchant approved dengan food listing status available di database. |

**Steps:**
1. Login sebagai admin di `/admin/login`
2. Klik menu "Peta" di sidebar
3. Tunggu peta Leaflet selesai dimuat
4. Perhatikan apakah pin marker muncul di peta

| Field | Detail |
|---|---|
| **Expected Result** | Peta muncul dengan tile OpenStreetMap, pin marker tampil di lokasi merchant yang memiliki listing aktif, klik pin membuka popup berisi nama usaha dan info listing |
| **Actual Result** | Peta berhasil dimuat, pin Alena Bakery muncul di koordinat Jl. Slamet Riyadi Solo, popup info tampil saat diklik |
| **Status** | ✅ Pass |

---

### TC-019 — Peta Merchant Menampilkan Lokasi Usaha Sendiri

| Field | Detail |
|---|---|
| **TC-ID** | TC-019 |
| **Judul** | Merchant dapat melihat lokasi usahanya sendiri di peta |
| **Fitur** | Map — FR-07 |
| **Precondition** | Login sebagai merchant. Profil merchant memiliki koordinat latitude dan longitude yang valid. |

**Steps:**
1. Login sebagai merchant
2. Klik menu "Lokasi Usaha" di sidebar
3. Tunggu peta selesai dimuat
4. Perhatikan pin yang muncul di peta

| Field | Detail |
|---|---|
| **Expected Result** | Peta muncul dengan satu pin di koordinat usaha merchant yang sedang login, popup berisi nama usaha dan alamat |
| **Actual Result** | Peta berhasil dimuat, satu pin muncul di lokasi usaha merchant |
| **Status** | ✅ Pass |

---

### TC-020 — Merchant Mengajukan Withdrawal Melebihi Saldo

| Field | Detail |
|---|---|
| **TC-ID** | TC-020 |
| **Judul** | Validasi withdrawal: nominal tidak boleh melebihi saldo yang tersedia |
| **Fitur** | Withdrawal |
| **Precondition** | Login sebagai merchant. Saldo merchant diketahui (misal Rp 50.000). |

**Steps:**
1. Login sebagai merchant
2. Buka menu "Penarikan Dana"
3. Isi field Nominal dengan angka melebihi saldo (misal `500000`)
4. Isi Bank, Nomor Rekening, dan Nama Pemilik Rekening dengan data valid
5. Klik "Ajukan Penarikan"

| Field | Detail |
|---|---|
| **Expected Result** | Sistem menolak form dengan pesan validasi bahwa jumlah melebihi saldo yang tersedia, withdrawal tidak dibuat |
| **Actual Result** | Validasi Laravel (max: balance) aktif, pesan error informatif tampil dengan saldo aktual |
| **Status** | ✅ Pass |

---

### TC-021 — Admin Logout dari Sistem

| Field | Detail |
|---|---|
| **TC-ID** | TC-021 |
| **Judul** | Admin dapat logout dan session dihapus |
| **Fitur** | Authentication |
| **Precondition** | Login sebagai admin, berada di halaman manapun di portal admin. |

**Steps:**
1. Login sebagai admin
2. Klik tombol "Keluar" di header
3. Setelah logout, coba akses kembali `/admin/dashboard` langsung di browser

| Field | Detail |
|---|---|
| **Expected Result** | Session dihapus, diarahkan ke halaman login. Akses langsung ke dashboard setelah logout diarahkan ke halaman login (tidak bisa bypass) |
| **Actual Result** | Logout berhasil, redirect ke login. Akses manual ke dashboard setelah logout diredirect ke login |
| **Status** | ✅ Pass |

---

### TC-022 — Akses Halaman Admin Tanpa Login

| Field | Detail |
|---|---|
| **TC-ID** | TC-022 |
| **Judul** | Proteksi route: halaman admin tidak bisa diakses tanpa autentikasi |
| **Fitur** | Authentication — Security |
| **Precondition** | Tidak ada session aktif di browser (belum login atau sudah logout). |

**Steps:**
1. Pastikan tidak ada session login aktif (buka incognito atau logout dulu)
2. Akses langsung URL `/admin/dashboard` di browser
3. Akses langsung URL `/merchant/dashboard` di browser
4. Akses langsung URL `/admin/merchants` di browser

| Field | Detail |
|---|---|
| **Expected Result** | Semua URL diatas diarahkan ke halaman login yang sesuai, tidak ada data yang bocor |
| **Actual Result** | RoleMiddleware dan auth middleware aktif, redirect ke halaman login |
| **Status** | ✅ Pass |

---

## Bug Report

### BUG-001 — Sidebar Portal Merchant Tidak Bisa Diakses di Tampilan Mobile

| Field | Detail |
|---|---|
| **Bug ID** | BUG-001 |
| **Judul** | [BUG] Sidebar portal merchant hilang dan tidak ada toggle di tampilan mobile |
| **Severity** | High |
| **Fitur Terdampak** | Semua halaman portal merchant |
| **Ditemukan pada TC** | TC-010, TC-014 (saat pengujian di viewport mobile) |

**Steps to Reproduce:**
1. Buka aplikasi di browser
2. Login sebagai akun merchant
3. Ubah ukuran viewport menjadi mobile (width < 860px) atau buka di perangkat handphone
4. Perhatikan area kiri halaman
5. Coba akses menu navigasi dari sidebar

**Expected Behavior:**
- Sidebar tetap bisa diakses melalui tombol hamburger menu / toggle
- Pengguna bisa berpindah halaman tanpa hambatan di tampilan mobile

**Actual Behavior:**
- Sidebar tidak tampil sama sekali (`display: none` di breakpoint < 860px)
- Tidak ada tombol atau kontrol navigasi alternatif
- Pengguna tidak bisa mengakses fitur utama portal merchant di tampilan mobile

**Root Cause:**
CSS `@media (max-width: 860px)` di `layouts/merchant.blade.php` menyembunyikan sidebar tanpa menyediakan komponen toggle pengganti.

**Fix yang Diperlukan:**
Tambahkan hamburger button yang membuka sidebar sebagai overlay saat viewport mobile. Implementasi menggunakan CSS `transform: translateX(-100%)` untuk hide/show sidebar tanpa menghapusnya dari DOM.

**Environment:**
- Browser: Chrome / Edge / Firefox
- OS: Windows / Android / iOS
- Halaman: Semua halaman portal merchant

**Status:** Open — Assigned untuk diperbaiki di sprint berikutnya

---

## Ringkasan Hasil Eksekusi

| Kategori | Jumlah |
|---|---|
| Total Test Case | 22 |
| Pass | 21 |
| Fail | 0 |
| Bug Ditemukan | 1 |

### Distribusi per Fitur

| Fitur | Test Case | Pass | Fail |
|---|---|---|---|
| Authentication | 4 | 4 | 0 |
| Registration | 3 | 3 | 0 |
| Merchant Verification | 3 | 3 | 0 |
| Food Listing | 4 | 4 | 0 |
| Order Management | 4 | 4 | 0 |
| Map | 2 | 2 | 0 |
| Withdrawal | 1 | 1 | 0 |
| Security (Route Protection) | 1 | 1 | 0 |

### Catatan

- Seluruh test case dieksekusi secara manual menggunakan browser Chrome pada environment lokal (`http://127.0.0.1:8000`)
- Cross-testing dilakukan: anggota yang tidak mengimplementasikan fitur yang diuji bertindak sebagai penguji
- Bug BUG-001 ditemukan saat pengujian TC-010 dan TC-014 dengan viewport mobile
- Bug BUG-001 sudah dicatat sebagai GitHub Issue dengan label `bug` dan severity `High`
- Tidak ada test case dengan status Fail — bug yang ditemukan bersifat UI/UX pada kondisi mobile, bukan pada alur fungsional utama
