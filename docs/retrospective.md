# Retrospektif Tim — EcoEats
# P12 — Rekayasa Perangkat Lunak Kelas A
# Kelompok 5: Makan Murah Penting Kenyang

---

## What Went Well? (Apa yang Berjalan Baik)

- Pembagian tugas antara tim web (Laravel) dan tim mobile (Kotlin/Android) berjalan lancar — kedua sisi bisa dikerjakan paralel tanpa banyak konflik karena kontrak API sudah disepakati lebih awal 
- Fitur inti MVP (verifikasi merchant, food listing, order flow) selesai sesuai target dan dapat didemonstrasikan end-to-end
- Penggunaan Git branching (feature branches → dev → main) dan Pull Request dengan review membuat proses merge lebih terstruktur dan minim konflik besar
- Refactoring layout merchant di P8 memberikan manfaat nyata — perubahan navigasi cukup di satu file dan tidak perlu menyentuh 9 file sekaligus
- Pengujian manual 22 test case dan 33 unit test PHPUnit memberikan kepercayaan bahwa fitur utama tidak ada regression setelah setiap iterasi pengembangan
- Komunikasi tim cukup responsif sehingga kendala teknis seperti bug session dan bug sidebar mobile dapat diidentifikasi dan diselesaikan dengan cepat

## What Didn't Go Well? (Apa yang Tidak Berjalan Baik)

- Integrasi antara tim web dan tim mobile sempat tertunda karena endpoint API tidak selalu siap ketika tim mobile sudah membutuhkan, sehingga mobile harus menggunakan dummy data lebih lama dari yang direncanakan
- Estimasi waktu untuk beberapa fitur kurang akurat — fitur withdrawal dan peta yang awalnya diperkirakan selesai lebih cepat ternyata membutuhkan waktu lebih lama terutama karena edge case yang tidak terprediksi
- Dokumentasi teknis (comment kode, README awal) tidak selalu diperbarui seiring perubahan implementasi, sehingga ada periode di mana kode dan dokumen tidak sinkron
- Beberapa anggota mengalami learning curve yang cukup curam terutama untuk Jetpack Compose di sisi mobile dan pola Eloquent di sisi backend, yang berdampak pada kecepatan pengembangan awal

## What Can We Improve? (Apa yang Bisa Diperbaiki ke Depan)

- Definisikan dan dokumentasikan kontrak API di awal sprint — bukan setelah backend sudah jalan — agar tim mobile tidak perlu menunggu atau menggunakan mock terlalu lama
- Terapkan code review yang lebih ketat dengan checklist eksplisit sebelum merge, bukan hanya approval informal
- Alokasikan waktu khusus untuk technical debt dan bug fix di setiap sprint, tidak hanya fitur baru
- Buat standar comment dan dokumentasi kode sejak awal agar onboarding anggota baru atau context-switching lebih mudah
- Gunakan Postman Collection atau Swagger yang dibagikan ke seluruh tim sejak awal sebagai sumber kebenaran tunggal untuk API spec

## Shout-outs (Apresiasi untuk Anggota Tim)

- **Wiwid Widyaningsih** — konsisten mengerjakan bagian admin panel dan verifikasi merchant dengan detail yang rapi, termasuk audit log yang seringkali terlewat di proyek sejenis
- **Alena Mashia Qolby** — gigih mengerjakan integrasi API di sisi mobile meskipun harus menyesuaikan dengan perubahan backend yang terjadi di tengah jalan
- **Maria Dewi Handayani** — teliti dalam pengujian dan dokumentasi, termasuk menemukan BUG-001 yang tidak terdeteksi oleh anggota lain saat testing di desktop
- **Muhamad Nabil Fannani** — aktif menjaga konsistensi arsitektur dan memastikan keputusan teknis besar (model bisnis withdrawal, pemilihan Leaflet.js, pola Sanctum) terdokumentasi dengan baik
