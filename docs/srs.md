# Software Requirements Specification (SRS) - EcoEats
---

## BAB I — Pendahuluan

### 1.1 Tujuan Dokumen

Dokumen ini merupakan Software Requirements Specification (SRS) ringkas untuk platform **EcoEats**, sebuah aplikasi yang menghubungkan pelaku usaha kuliner di Kota Solo dengan konsumen yang ingin membeli makanan surplus dengan harga terjangkau. SRS ini menjadi acuan bagi tim pengembang, pihak-pihak terkait, dan asisten praktikum dalam memahami ruang lingkup, kebutuhan fungsional, dan kebutuhan non-fungsional sistem selama siklus pengembangan berlangsung.

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
