# AI Usage Log — EcoEats

Dokumen ini mencatat penggunaan AI (Claude — Anthropic) selama pengembangan proyek EcoEats sebagai bentuk transparansi sesuai prinsip Responsible AI Use.

**Tool yang digunakan:** Claude (Anthropic)  
**Periode:** Mei — Juli 2026

---

## Log Penggunaan

| Tanggal | Anggota | Tools + Prompt | Output + Verifikasi |
|---|---|---|---|
| 21 Mei 2026 | Alena | Claude — Buatkan Bagian A berupa ringkasan masalah dan solusi berdasarkan problem statement yang sudah saya berikan sebelumnya | Output: Dua paragraf ringkasan masalah dari sisi konsumen (kesulitan menemukan informasi makanan surplus menjelang jam tutup) dan sisi merchant (kerugian finansial akibat makanan tidak terjual). Verifikasi: Draft ditinjau dan disesuaikan redaksinya oleh tim sebelum dimasukkan ke dokumen. |
| 21 Mei 2026 | Alena | Claude — Lengkapi tabel task breakdown Bagian D untuk fitur Login User dan Pemesanan | Output: Tabel task breakdown dengan 5 task untuk Login User (membuat form autentikasi, membuat database user, endpoint auth, JWT token) dan 5 task untuk Pemesanan (dashboard mobile, backend pembayaran, endpoint orders, database order, notifikasi). Verifikasi: Estimasi dan PIC disesuaikan dengan pembagian kerja tim. |
| 21 Mei 2026 | Alena | Claude — Buatkan timeline 2 sprint berdasarkan user story yang telah dibuat | Output: Sprint 1 Minggu 1 (setup repo, auth), Minggu 2 (manajemen menu, verifikasi merchant). Sprint 2 Minggu 3 (pemesanan, GPS), Minggu 4 (rating, testing, dokumentasi). Verifikasi: Deliverable per minggu disesuaikan dengan jadwal akademik tim. |
| 3 Juni 2026 | Nabil | Claude — Buatkan struktur migration Laravel untuk tabel `orders` dan `order_items` berdasarkan data dictionary ERD yang sudah kami buat | Output: Dua file migration lengkap dengan kolom, tipe data, foreign key, dan index yang sesuai. Verifikasi: Migration dicek terhadap ERD dan dijalankan ke database lokal untuk memastikan tidak ada error. |
| 3 Juni 2026 | Nabil | Claude — Buatkan model Order Laravel dengan konstanta status, helper methods (isPending, canMarkReady, canComplete, statusLabel), dan scope forMerchant | Output: Model Order lengkap dengan semua helper method dan scope. Verifikasi: Setiap method dicek logikanya secara manual dan diuji melalui unit test di P10. |
| 10 Juni 2026 | Maria | Claude — Saya punya sidebar merchant yang sama persis di 9 file Blade. Bagaimana cara ekstrak ke satu layout file? Berikan contoh implementasi `@extends` dan `@yield` untuk kasus ini | Output: Penjelasan konsep Blade layout beserta contoh file `layouts/merchant.blade.php` lengkap dan cara migrasi salah satu view. Verifikasi: Diimplementasikan secara manual ke seluruh 9 view, setiap view dicek di browser untuk memastikan tampilan tidak berubah. |
| 17 Juni 2026 | Wiwid | Claude — Buatkan 22 test case manual untuk fitur-fitur EcoEats web (authentication, food listing, order management, map, withdrawal) dengan format TC-ID, precondition, steps, expected result | Output: 22 test case lengkap dalam format tabel markdown mencakup happy path dan unhappy path. Verifikasi: Setiap test case dieksekusi secara manual di aplikasi yang berjalan, actual result dicatat sendiri oleh tim. |
| 24 Juni 2026 | Nabil | Claude — Buatkan unit test PHPUnit untuk method `statusLabel()`, `isPending()`, `canMarkReady()`, `canComplete()` di model Order menggunakan pola AAA | Output: File `OrderTest.php` dengan 21 test mengikuti pola AAA. Verifikasi: Dijalankan dengan `php artisan test`, ditemukan satu issue terkait Eloquent cast pada `FoodListingTest` yang kemudian di-debug dan diselesaikan sendiri. |
| 1 Juli 2026 | Alena | Claude — Review kode `SignIn.kt` saya. Apakah ada bagian yang perlu diperbaiki terkait state management dan error handling di Jetpack Compose? | Output: Review dengan catatan: (1) `LaunchedEffect` perlu tambahan `authViewModel.resetState()` setelah navigasi agar state tidak stale, (2) loading state sebaiknya disable tombol submit. Verifikasi: Saran (1) dan (2) diimplementasikan dan diuji langsung di emulator. |

---

## Refleksi Penggunaan AI

**Yang berjalan baik:**
- AI sangat membantu untuk scaffolding awal (struktur migration, model, controller) sehingga tim bisa fokus pada logika bisnis spesifik proyek
- Penjelasan konsep teknis (Blade layout, Sanctum token, Eloquent cast) lebih cepat dipahami dengan contoh kode yang kontekstual
- Review kode memberikan perspektif tambahan yang kadang terlewat saat coding sendiri

**Yang perlu diwaspadai:**
- Output AI tidak selalu langsung bisa dipakai — beberapa kode perlu disesuaikan dengan struktur database dan konvensi proyek yang spesifik
- AI tidak mengetahui konteks penuh proyek, sehingga beberapa output memerlukan penyesuaian signifikan (misalnya nama kolom, relasi antar model)
- Bug pada unit test (`setRawAttributes` untuk bypass Eloquent cast) tidak langsung terdeteksi oleh AI dan harus di-debug secara manual oleh tim

**Prinsip yang diikuti:**
- Tidak ada credential, password, atau data sensitif yang dimasukkan ke prompt AI
- Setiap output AI diverifikasi sebelum digunakan — tidak ada kode yang dipakai mentah-mentah
- Tim memastikan setiap anggota memahami kode yang dihasilkan AI, bukan sekadar copy-paste
