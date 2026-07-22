# Panduan Teknis & Kisi-Kisi Project DesaSehat 🌿
**DesaSehat – Portal Posyandu Digital Terintegrasi**

Panduan ini berisi penjelasan lengkap mengenai arsitektur web, fitur-fitur utama, cara menjalankan proyek, serta informasi akun untuk keperluan demonstrasi/uji coba.

---

## 🛠️ Stack Teknologi & Dependensi
* **Backend**: Laravel 11 (PHP 8.2+)
* **Frontend**: Tailwind CSS, HTML5 semantic layout, Vanilla JavaScript (ES6)
* **Build Tool**: Vite (Laravel Vite Plugin)
* **Database**: MySQL / MariaDB (melalui Laragon)
* **Grafik**: Chart.js (Grafik Kunjungan Bulanan)
* **Suara**: Web Speech Synthesis API (Panggilan Antrean Suara)

---

## 🗄️ Konfigurasi Basis Data (.env)
Berikut adalah konfigurasi koneksi database yang digunakan pada lingkungan lokal Laragon:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=desasehat
DB_USERNAME=root
DB_PASSWORD=
```

---

## ⚙️ Cara Menjalankan Aplikasi di Komputer Baru
Ikuti langkah-langkah di bawah ini untuk menjalankan proyek ini dari awal:

1. **Instalasi Paket Dependensi**:
   Buka terminal di direktori root `DesaSehat` dan jalankan:
   ```bash
   composer install
   npm install
   ```

2. **Migrasi Database & Seeding Data**:
   Untuk membuat tabel-tabel baru beserta data demo (kader, warga, anggota keluarga), jalankan:
   ```bash
   php artisan migrate:fresh --seed
   ```

3. **Kompilasi Aset Frontend (Vite/Tailwind)**:
   Lakukan kompilasi aset CSS dan JavaScript produksi dengan perintah:
   ```bash
   npm run build
   ```

4. **Menjalankan Server Lokal (Bisa Diakses HP & Laptop)**:
   Jalankan server Artisan dengan konfigurasi host agar bisa diakses oleh perangkat lain (HP) dalam satu jaringan Wi-Fi:
   ```bash
   php artisan serve --host=0.0.0.0 --port=8000
   ```
   * *Akses Laptop/PC*: [http://127.0.0.1:8000](http://127.0.0.1:8000)
   * *Akses Smartphone*: Hubungkan HP ke Wi-Fi yang sama, lalu buka `http://<IP-LAPTOP-ANDA>:8000` (contoh: `http://192.168.1.15:8000`).

---

## 🔑 Akun Uji Coba (Demo Credentials)

### 1. Akun Kader (Dashboard Petugas)
* **Username**: `kader`
* **Password**: `password`
* **Fungsi**: Mengelola antrean hari ini, memanggil antrean suara, menginput data pemeriksaan kesehatan warga, memantau riwayat kunjungan bulanan, dan mencari data warga secara global.

### 2. Akun Warga 1 (Keluarga Budi Utomo)
* **Username (No. KK)**: `1234567890123456`
* **Password**: `password`
* **Anggota Keluarga**: Budi Utomo (KK), Siti Aminah (Ibu Hamil), Andi Utomo (Balita), Mbah Joyo (Lansia).

### 3. Akun Warga 2 (Keluarga Slamet Riyadi)
* **Username (No. KK)**: `6543210987654321`
* **Password**: `password`
* **Anggota Keluarga**: Slamet Riyadi (KK), Rina Riyadi (Bayi - 8 Bulan).

---

## 💡 Fitur-Fitur Utama & Mekanisme Kerja

### A. Dashboard Kader ("Posyandu Command Center")
1. **Hero Header & Real-time Clock**: Menampilkan salam dinamis berdasarkan jam, penunjuk waktu/kalender, serta rangkuman agenda posyandu terdekat.
2. **Smart Priority Panel**: Panel otomatis yang menyaring warga/keluarga yang sudah lama tidak periksa (30 hari bagi Ibu Hamil & Balita, 60 hari bagi Lansia, 90 hari bagi Keluarga Pasif) untuk diprioritaskan kader.
3. **Panggilan Suara Otomatis (Web Speech API)**: Tombol "Panggil Suara" akan menyuarakan panggilan antrean dalam bahasa Indonesia (`Nomor antrean A-1. Keluarga Bapak Budi...`) menggunakan suara sintesis bawaan peramban (browser).
4. **Pencarian Global Instan (Asynchronous Search)**: Fitur pencarian otomatis pada kolom header. Cukup ketik nama warga, nomor NIK, KK, atau telepon, maka hasil pencarian akan langsung muncul secara asinkron di bawah input pencarian.

### B. Dashboard Warga ("Family Health Home")
1. **Digital Queue Progress Card**:
   * Jika belum mengambil antrean, tombol "Ambil Antrean" akan muncul.
   * Jika sudah mengambil antrean, kartu akan menampilkan kode tiket beserta **ProgressBar Visual** yang melacak status antrean warga (Menunggu -> Dilayani -> Selesai) secara otomatis melalui polling asinkron setiap **3 detik**.
2. **Kartu Anggota Keluarga & Rekam Medis Modal**:
   * Setiap anggota keluarga ditampilkan dalam bentuk kartu dengan warna penanda khusus (Ibu hamil merah muda, bayi cyan, dll.).
   * Klik pada kartu anggota keluarga untuk membuka popup (*modal*) berisi data detail riwayat rekam medis lengkap (berat badan, tinggi, keluhan, tindakan kader).
3. **Health Timeline**: Catatan gabungan seluruh riwayat pemeriksaan keluarga yang disajikan dalam bentuk garis waktu (timeline) vertikal yang indah dan kronologis.
4. **Reminder & Hubungi Kader**: 
   * Pengingat cerdas otomatis (contoh: membawa Buku KIA jika punya balita).
   * Tombol "Hubungi" langsung membuka tautan WhatsApp ke nomor Kader aktif terkait dengan template pesan otomatis.

---

## ⚠️ Catatan Penting untuk Pengembangan
* **Model Serialization (`AnggotaKeluarga.php`)**:
  Semua atribut dinamis seperti `umur`, `kategori_formatted`, `hubungan_keluarga`, dan `tanggal_lahir_formatted` didaftarkan ke properti `$appends` agar otomatis ter-serialize saat model diubah ke format JSON untuk JavaScript.
* **Auto-Dismiss Alerts**:
  Semua pesan sukses/notifikasi melayang akan otomatis memudar (*fade-out*) dan menghilang setelah tepat **5 detik** demi kebersihan tampilan UI.
* **Optimization Cache**:
  Jika ada perubahan pada file layout atau rute, selalu jalankan:
  `php artisan optimize:clear` dan `php artisan optimize` agar perubahan segera diterapkan oleh Laravel.
