
# Praktikum RPL — A - 5

> Repositori resmi kelompok untuk mata kuliah **Rekayasa Perangkat Lunak**  
> Semester Genap 2025/2026 — Universitas Universitas Sebelas Maret
---

## Tentang Repositori

| Atribut     | Detail                          |
|-------------|---------------------------------|
| Kelas       | A                               |
| Kelompok    | 5                               |
| Semester    | Genap 2025/2026                 |
| Branch Utama| `dev`                           |

---

## Anggota Kelompok

| No | Nama Lengkap | NIM    | Peran | GitHub |
|----|--------------|--------|-------|--------|
| 1  | Muhamad Nabil Fannani | L0124135 | Ketua / Project Manager | [@Nabil-Fan](https://github.com/Nabil-Fan) |
| 2  | Wiwid Widyaningsih | L0124123 | Developer | [@wiwidw](https://github.com/midnightbluee2) |
| 3  | Alena Mashia Qolby | L0124129 | Developer | [@midnightbluee2](https://github.com/wiwidw) |
| 4  | Maria Dewi Handayani | L0124132 | QA / Dokumentasi | [@hanihan1](https://github.com/hanihan1) |

---

## Struktur Folder

```
praktikum-rpl-a-5/
│
├── docs/                       # Dokumentasi
│   ├── team-contract.md        # Kontrak kelompok
│   ├── requirements/           # Dokumen kebutuhan sistem
│   └── diagram/                # Diagram UML, ERD, dll
│
├── src/                        # Kode aplikasi
│   ├── main/                   # Kode utama
│
├── tests/                      # File pengujian
│
├── .gitignore                  # File yang diabaikan git
└── README.md                   # Dokumentasi
```

---

## Topik Proyek

### Judul Sementara
> **PeduliLingkungan**

### Deskripsi Singkat
> *Sistem Pelaporan Masalah Lingkungan Warga*

| Atribut         | Detail                          |
|-----------------|---------------------------------|
| Domain       | Lingkungan |
| Aktor Utama   | Admin, User, dll.             |
| Fitur Inti   | Fitur 1, Fitur 2, Fitur 3     |

---

## Teknologi yang Digunakan

| Kategori     | Teknologi       |
|--------------|-----------------|
| Backend      | -               |
| Frontend     | -               |
| Database     | -               |
| Version Control | Git & GitHub |
| Editor       | VS Code         |

---

## Cara Kontribusi

Semua anggota tim wajib mengikuti alur kerja berikut sebelum membuat perubahan pada repositori:

1. **Pastikan branch `dev` sudah terbaru**
   ```bash
   git checkout dev
   git pull origin dev
   ```

2. **Buat branch baru untuk fitur/tugas**
   ```bash
   git checkout -b feature/nama-fitur
   ```

3. **Lakukan perubahan, lalu commit**
   ```bash
   git add .
   git commit -m "feat(auth): deskripsi perubahan singkat"
   ```

4. **Push branch ke GitHub**
   ```bash
   git push origin feature/nama-fitur
   ```

5. **Buat Pull Request ke branch `dev`** melalui GitHub  
   → Minta minimal **1 anggota lain** untuk melakukan review  
   → Merge setelah disetujui

---

## Standar Commit Message

Gunakan format berikut untuk semua commit:

```
<type>: <deskripsi singkat dalam bahasa Indonesia atau Inggris>
```

| Type       | Digunakan untuk                            |
|------------|--------------------------------------------|
| `feat`     | Menambahkan fitur baru                     |
| `fix`      | Memperbaiki bug                            |
| `docs`     | Perubahan dokumentasi                      |
| `style`    | Perubahan format/tampilan (bukan logika)   |
| `refactor` | Refactoring kode tanpa mengubah fungsi     |
| `test`     | Menambah atau mengubah test                |
| `chore`    | Update konfigurasi, dependency, dll.       |

**Contoh:**
```
feat: tambah halaman login pengguna
fix: perbaiki validasi input form registrasi
docs: update README dengan instruksi setup
```

---

## Progress & Status

### Praktikum 1 — Setup & Git Workflow

| Tugas                                      | Status |
|--------------------------------------------|--------|
| Kelompok terbentuk dan ketua ditentukan    | ✅ |
| Kontrak tim ditulis (`team-contract.md`)   | ✅ |
| Repositori GitHub dibuat                   | ✅ |
| Branch `dev` dibuat dan dijadikan default  | ✅ |
| Setiap anggota merge minimal 1 Pull Request| ✅ |
| Topik diregistrasi di Google Sheet         | ✅ |

> Keterangan: ✅ Selesai &nbsp;|&nbsp; 🔄 Dalam Proses &nbsp;|&nbsp; ❌ Belum Dimulai

