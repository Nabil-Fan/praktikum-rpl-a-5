# Software Requirements Specification (SRS) - EcoEats 

## BAB I — Pendahuluan

### 1.1 Tujuan Dokumen

Dokumen ini merupakan Software Requirements Specification (SRS) untuk platform **EcoEats**, sebuah aplikasi yang menghubungkan pelaku usaha kuliner di Kota Solo dengan konsumen yang ingin membeli makanan surplus dengan harga terjangkau. SRS ini menjadi acuan bagi tim pengembang, pihak-pihak terkait, dan asisten praktikum dalam memahami ruang lingkup, kebutuhan fungsional, dan kebutuhan non-fungsional sistem selama siklus pengembangan berlangsung.

Dokumen ini disusun sebagai bagian dari laporan Praktikum Rekayasa Perangkat Lunak P3 dan merujuk pada artefak P2 berupa problem statement, user stories, dan backlog yang telah divalidasi sebelumnya.

### 1.2 Ruang Lingkup

EcoEats adalah platform digital yang dirancang untuk memfasilitasi jual-beli makanan surplus dari mitra merchant (restoran, kafe, katering, toko roti, warung, hotel) kepada pengguna umum di wilayah Kota Solo. Platform ini bertujuan untuk:

- Mengurangi pemborosan makanan (food waste) di sektor kuliner Kota Solo.
- Memberikan akses makanan berkualitas dengan harga diskon kepada konsumen.
- Membuka peluang bagi pelaku usaha kuliner untuk menjual produk surplus mereka daripada terbuang sia-sia.

Sistem ini **tidak mencakup** pengiriman makanan (delivery). Seluruh transaksi diselesaikan melalui mekanisme pengambilan langsung (self-pickup) oleh pengguna di lokasi merchant.

### 1.3 Definisi dan Akronim

| Istilah / Akronim | Definisi |
|---|---|
| Backlog | Daftar prioritas fitur atau tugas yang harus diselesaikan selama pengembangan perangkat lunak. |
| EcoEats | Platform digital penghubung merchant kuliner dan konsumen makanan surplus di Kota Solo. |
| Food Waste | Sampah makanan; makanan yang sebenarnya masih layak dikonsumsi oleh manusia tetapi dibuang begitu saja. |
| Makanan Surplus | Produk kuliner yang masih layak konsumsi namun belum terjual hingga mendekati waktu tutup operasional atau batas waktu. |
| Merchant | Pelaku usaha kuliner (restoran, kafe, hotel, dll.) yang telah terdaftar dan memiliki otoritas untuk menjual makanan surplus di platform. |
| P2 | Kode untuk modul praktikum kedua dalam mata kuliah Rekayasa Perangkat Lunak. |
| P3 | Kode untuk modul praktikum ketiga dalam mata kuliah Rekayasa Perangkat Lunak. |
| Problem Statement | Pernyataan yang mendeskripsikan masalah utama yang ingin diselesaikan oleh sistem. |
| Self-Pickup | Metode pengambilan pesanan di mana konsumen datang langsung ke lokasi merchant setelah melakukan pemesanan di aplikasi. |
| SRS | Software Requirements Specification; dokumen yang menjelaskan fungsi dan kemampuan yang diharapkan dari sistem. |
| User | Pengguna aplikasi yang mencari dan membeli makanan surplus melalui platform EcoEats. |
| User Stories | Penjelasan fitur dari perspektif pengguna untuk mempermudah pemahaman kebutuhan. |

---

## BAB II — Deskripsi Umum

### 2.1 Perspektif Produk

EcoEats merupakan produk inovasi baru yang berdiri sendiri (standalone application), tidak terintegrasi langsung dengan sistem point-of-sale (POS) milik merchant. Platform ini beroperasi sebagai perantara digital antara merchant dan konsumen, di mana seluruh alur dari pendaftaran merchant hingga penyelesaian pengambilan pesanan dikelola dalam satu ekosistem aplikasi.

Platform ini diinspirasi oleh konsep aplikasi anti-food-waste seperti Too Good To Go, namun dirancang khusus untuk konteks dan skala Kota Solo dengan mempertimbangkan kebiasaan dan infrastruktur lokal.

### 2.2 Fungsi Produk

| Fungsi | Keterangan |
|---|---|
| Autentikasi | Registrasi dan login untuk User dan Mitra Merchant. |
| Manajemen Menu | Kemampuan Merchant untuk menambahkan, mengedit, dan memantau stok makanan surplus secara real-time. |
| Eksplorasi Berbasis Lokasi | Visualisasi lokasi merchant pada peta untuk memudahkan pencarian oleh User. |
| Sistem Pemesanan | Proses reservasi makanan, pembayaran, hingga validasi pengambilan pesanan menggunakan kode unik. |
| Verifikasi Merchant | Proses kurasi dokumen usaha oleh Admin untuk menjaga kualitas dan keamanan layanan. |

### 2.3 Karakteristik Pengguna

- **User:** Mencari makanan surplus, melihat lokasi di peta, melakukan pemesanan, dan mengambil pesanan di lokasi merchant.
- **Mitra Merchant:** Mengunggah informasi makanan surplus, mengelola stok, melakukan verifikasi pengambilan pesanan, dan mengelola profil usaha.
- **Admin:** Memverifikasi kelayakan dokumen merchant, memantau aktivitas platform, dan menangani kendala sistem.

### 2.4 Batasan

| Batasan | Keterangan |
|---|---|
| Wilayah | Layanan pemetaan dan merchant saat ini terbatas hanya untuk area Kota Solo. |
| Pengambilan | Sistem hanya mendukung pengambilan pesanan secara langsung di lokasi merchant (self-pickup), tidak mencakup layanan pengiriman. |
| Verifikasi | Merchant tidak dapat mempublikasikan makanan sebelum dokumen identitas dan izin usaha disetujui oleh Admin. |
| Status Makanan | Sistem hanya mengelola makanan yang dikategorikan sebagai "surplus" (bukan menu reguler harga normal). |

---

## BAB III — Kebutuhan Fungsional (FR)

| ID | Deskripsi | Prioritas | Ref US | Acceptance Criteria |
|---|---|---|---|---|
| FR-01 | Sistem harus menyediakan fitur pendaftaran (registrasi) akun untuk user baru menggunakan email. | High | US-01 | Akun berhasil dibuat jika email belum terdaftar; sistem menampilkan pesan error jika email sudah digunakan. |
| FR-02 | Sistem harus menyediakan fitur masuk (login) bagi user terdaftar menggunakan kredensial yang valid untuk mengakses aplikasi. | High | US-02 | User berhasil masuk dan diarahkan ke halaman utama; sistem menampilkan pesan error jika kredensial salah. |
| FR-03 | Sistem harus menyediakan portal autentikasi (login dan registrasi) terpisah yang dikhususkan untuk Mitra Merchant. | High | US-03 | Merchant dapat mendaftar dan masuk melalui portal khusus; portal tidak dapat diakses oleh user biasa. |
| FR-04 | Sistem harus memungkinkan Mitra Merchant untuk menambahkan daftar makanan surplus baru, termasuk detail seperti nama produk, deskripsi, harga diskon, dan stok ketersediaan. | High | US-04 | Makanan surplus berhasil tersimpan dan tampil di katalog setelah merchant mengisi semua field wajib. |
| FR-05 | Sistem harus memungkinkan Mitra Merchant untuk mengedit, menonaktifkan, atau menghapus menu makanan surplus yang sudah diunggah. | High | US-05 | Perubahan tersimpan dan langsung tercermin di katalog; item yang dihapus tidak lagi muncul pada pencarian user. |
| FR-06 | Sistem harus menampilkan katalog atau daftar menu makanan surplus dari berbagai merchant yang dapat dilihat dan dicari oleh user. | High | US-06 | Katalog menampilkan seluruh item surplus aktif; user dapat melakukan pencarian berdasarkan nama produk atau merchant. |
| FR-07 | Sistem harus menampilkan integrasi peta interaktif yang menunjukkan titik lokasi Mitra Merchant yang memiliki stok makanan surplus di sekitar User. | Medium | US-07 | Peta menampilkan pin lokasi merchant aktif; pin hanya muncul untuk merchant yang memiliki stok tersedia. |
| FR-08 | Sistem dapat menyediakan fitur kalkulasi jarak atau navigasi rute langsung dari lokasi user ke titik pengambilan (lokasi merchant) di dalam peta. | Medium | US-08 | Sistem menampilkan estimasi jarak dan rute dari posisi user ke lokasi merchant yang dipilih. |
| FR-09 | Sistem harus memungkinkan User untuk menambahkan makanan ke keranjang, memilih metode pembayaran, dan melakukan proses checkout pesanan. | High | US-09 | Pesanan berhasil dibuat dan user menerima konfirmasi beserta kode unik pengambilan setelah checkout selesai. |
| FR-10 | Sistem harus memberikan notifikasi kepada Mitra Merchant dan memungkinkan mereka untuk menerima atau menolak pesanan yang masuk dari User. | High | US-10 | Merchant menerima notifikasi real-time; pesanan masuk ke status "Diterima" atau "Ditolak" sesuai tindakan merchant. |
| FR-11 | Sistem sebaiknya memungkinkan Mitra Merchant untuk memperbarui status pesanan secara real-time (misalnya: "Pesanan Sedang Disiapkan", "Siap Diambil"). | Medium | US-11 | Perubahan status pesanan oleh merchant langsung terlihat oleh user di halaman detail pesanan. |
| FR-12 | Sistem harus menyediakan formulir bagi Mitra Merchant untuk mengunggah dokumen legalitas bisnis dan identitas sebagai syarat verifikasi pendaftaran merchant. | High | US-12 | Formulir berhasil mengunggah dokumen; pengajuan masuk ke antrian review Admin setelah submit. |
| FR-13 | Sistem harus menyediakan dashboard bagi Admin untuk meninjau dokumen, lalu menyetujui (approve) atau menolak (reject) pengajuan verifikasi Mitra Merchant. | High | US-13 | Admin dapat melihat seluruh pengajuan; merchant menerima notifikasi hasil keputusan approve atau reject. |
| FR-14 | Sistem menyediakan fitur bagi user untuk memberikan ulasan (review) dan rating kepada Mitra Merchant setelah transaksi selesai. | Low | US-14 | User dapat memberikan rating bintang dan teks ulasan; ulasan tampil di halaman profil merchant. |

---

## BAB IV — Kebutuhan Non-Fungsional (NFR)

**NFR-01 (Performance)**
Halaman utama dan katalog makanan harus termuat dalam waktu ≤ 3 detik pada koneksi internet minimal 10 Mbps.
Verifikasi: Dilakukan pengujian menggunakan tools Lighthouse atau PageSpeed Insights dengan minimal 3 kali percobaan pada perangkat dengan spesifikasi menengah (RAM ≥ 4 GB). Waktu muat dihitung sejak aplikasi dibuka hingga konten utama tampil.

---

**NFR-02 (Security)**
Password pengguna harus disimpan menggunakan hashing bcrypt dengan cost factor ≥ 10, tidak tersimpan dalam bentuk plaintext.
Verifikasi: Dilakukan pengecekan langsung pada database untuk memastikan tidak ada entri password dalam bentuk plaintext. Sistem diasumsikan menggunakan protokol HTTPS (TLS) untuk seluruh komunikasi data antara aplikasi dan server.

---

**NFR-03 (Usability)**
Antarmuka sistem harus dapat digunakan dengan baik pada perangkat mobile dengan lebar layar minimal 375px tanpa elemen utama saling bertumpuk atau terpotong.
Verifikasi: Dilakukan pengecekan menggunakan mode responsif pada browser (Chrome DevTools) pada lebar layar 375px. Tampilan dinyatakan layak apabila seluruh elemen utama pada halaman utama dan katalog masih terlihat dan dapat diakses dengan normal.

---

## BAB V — Catatan dan Asumsi

- Dokumen SRS ini merujuk pada artefak P2 (problem statement, user stories, dan product backlog) yang telah divalidasi oleh asisten praktikum.
- Fitur delivery (pengiriman makanan) secara eksplisit berada di luar ruang lingkup sistem pada fase pengembangan ini.
- Merchant diasumsikan memiliki akses internet yang memadai untuk mengelola stok dan menerima notifikasi pesanan secara real-time.
- Sistem diasumsikan berjalan di atas infrastruktur cloud dengan ketersediaan yang memadai untuk mendukung NFR yang telah ditetapkan.
- Fitur review dan rating (FR-14) memiliki prioritas Low dan dapat ditangguhkan ke rilis berikutnya apabila waktu pengembangan terbatas.
- Integrasi peta pada FR-07 dan FR-08 diasumsikan menggunakan layanan peta pihak ketiga (seperti Google Maps API atau OpenStreetMap).
