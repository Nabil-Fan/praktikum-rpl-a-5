# Kontrak Tim — Kelompok 5 Kelas A

## Identitas Tim

| Atribut     | Detail                              |
|-------------|-------------------------------------|
| Mata Kuliah | Rekayasa Perangkat Lunak            |
| Universitas | Universitas Sebelas Maret           |
| Kelas       | Kelas A                             |
| Kelompok    | 5                                   |
| Semester    | Genap 2025/2026                     |

---

## Peran dan Tanggung Jawab

### Struktur Peran

| No | Nama Lengkap | NIM | Peran | Tanggung Jawab Utama |
|----|--------------|-----|-------|----------------------|
| 1 | Muhamad Nabil Fannani | L0124135 | **Ketua / Project Manager** | Koordinasi tim, memimpin meeting, memastikan deadline terpenuhi, mengelola isu di GitHub |
| 2 | Wiwid Widyaningsih | L0124123 | **Developer** | Pengembangan sistem frontend dan backend  |
| 3 | Alena Mashia Qolby | L0124139 | **Developer** | Pengembangan sistem frontend dan backend  |
| 4 | Maria Dewi Handayani | L0124132 | **QA & Dokumentasi** | Penulisan test, review kode, dan pemeliharaan dokumentasi |

### Catatan Peran
- Peran bersifat fleksibel, jadi anggota bisa saling membantu di luar peran masing-masing
- Rotasi peran dapat dilakukan per praktikum jika disepakati bersama
- Setiap anggota tetap bertanggung jawab penuh atas bagian yang sudah ditugaskan 

---

## Jadwal Pertemuan Rutin

### Meeting Mingguan

| Tipe Meeting | Hari | Waktu | Platform |
|---|---|---|---|
| Meeting Rutin | Senin | Jam: 08.00-17:00 WIB | Offline | 

### Aturan Meeting
- Hadir tepat waktu. Maksimal keterlambatan maksimal 20 menit
- Jika tidak bisa hadir, wajib konfirmasi minimal H-3 jam sebelum meeting.
- Setiap meeting wajib menghasilkan **notulensi singkat** yang dikirim ke grup.
- Ketua memimpin agenda, anggota lain bergantian menjadi notulis.

---

## Channel Komunikasi

| Keperluan | Platform |
|---|---|
| Komunikasi harian | WhatsApp |
| Meeting & diskusi | Google Meet |
| Kolaborasi kode | GitHub |

---

## Aturan Komunikasi

### 1. Jam Operasional Kelompok
Karna setiap anggota memiliki kesibukan lain, waktu operasional tim disepakati pada:
- **Senin - Jumat:** Pukul 18.00 - 22.00 WIB
- **Sabtu - Minggu:** Fleksibel 
- *Catatan:* Pesan yang dikirim di luar jam operasional tetap dipersilakan, namun anggota tidak diwajibkan untuk langsung membalas hingga jam operasional berikutnya dimulai.

### 2. Standar Waktu Respon
Setiap anggota tim berkomitmen untuk mematuhi batas waktu respons berikut:
- **Pesan Biasa / Informasi Umum:** Wajib dibalas atau minimal diberikan reaksi (emoji jempol/OK) maksimal dalam **12 jam**.
- **Pesan dengan *Mention* (@nama):** Jika nama spesifik di-*tag*, wajib merespons maksimal dalam **6 jam** (pada jam operasional).
- **Pull Request (PR) Review:** Anggota yang ditugaskan untuk melakukan *code review* wajib menyelesaikannya maksimal dalam **24 jam** sejak pr dibuat
- **Keadaan Darurat/Urgent:** Jika ada error kritis atau tenggat waktu tugas tinggal 24 jam, anggota wajib merespons maksimal dalam **2 jam**. Pemanggil berhak menelepon langsung jika tidak ada respon sama sekali

### 3. Budaya Komunikasi & Etika
- Setiap kali membaca pesan yang berisi instruksi atau pembagian tugas, anggota wajib merespons agar pengirim tau pesannya sudah tersampaikan
- **Pemberitahuan Ketidakhadiran (Izin):** Jika seorang anggota berhalangan hadir dalam rapat, sedang sakit, atau tidak bisa mengerjakan tugas selama lebih dari 24 jam, **wajib** menginformasikan ke grup maksimal 12 jam sebelumnya atau sesegera mungkin.

### 4. Mekanisme Eskalasi & Sanksi 
Jika ada anggota yang melanggar aturan respons misalnya *ghosting* atau tidak mengerjakan tugas tanpa kabar, tim akan melakukan langkah-langkah eskalasi berikut:
1. **Peringatan 1 :** Ketua kelompok akan menghubungi secara personal (*Direct Message*) untuk menanyakan kendala yang sedang dialami (maksimal 1x24 jam setelah tidak ada respons di grup).
2. **Peringatan 2 :** Jika teguran personal diabaikan, anggota tersebut akan di-*mention* di grup utama dan tugasnya akan diambil alih sementara agar proyek tidak terhambat.
3. **Eskalasi ke Dosen/Asprak:** Jika selama **3x24 jam** tetap tidak ada respons dan kontribusi tanpa alasan yang jelas, tim berhak melaporkan anggota tersebut kepada dosen/asisten praktikum sebagai laporan *free rider*, yang berpotensi memengaruhi nilai akhir yang bersangkutan.

---

## Standar Pengerjaan Tugas

### Alur Pengerjaan

```
Terima Tugas → Diskusi & Pembagian → Pengerjaan di Branch Sendiri
     → Commit Berkala → Push → Buat Pull Request → Review oleh Anggota Lain
          → Revisi (jika ada) → Merge ke dev
```

### Aturan Pull Request
- Setiap PR **wajib direview minimal 1 anggota lain** sebelum di-merge
- PR tidak boleh di-merge sendiri tanpa persetujuan anggota lain
- PR yang sudah disetujui harus di-merge maksimal **24 jam** setelah approval

---

## Standar Commit Message

Seluruh anggota **wajib** menggunakan format berikut:

```
<type>(<scope>): <deskripsi singkat>
```

### Tabel Type

| Type | Kapan Digunakan |
|---|---|
| `feat` | Menambahkan fitur baru |
| `fix` | Memperbaiki bug |
| `docs` | Perubahan pada dokumentasi |
| `style` | Perubahan format/styling (bukan logika) |
| `refactor` | Refactoring kode tanpa mengubah perilaku |
| `test` | Menambah atau memperbaiki test |
| `chore` | Konfigurasi, dependency, atau tugas rutin |

## Contoh 
```
feat(auth): tambah fitur login dengan email dan password
fix(ui): perbaiki tampilan button yang overflow di mobile
docs(readme): update daftar anggota tim
test(api): tambah unit test untuk endpoint registrasi
```

---





