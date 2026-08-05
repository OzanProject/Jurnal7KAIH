# 🚀 Panduan Lengkap Deployment Aplikasi Laravel ke Hosting (cPanel)

Dokumen ini berisi langkah-langkah detail untuk mengunggah (deploy) aplikasi Laravel Anda ke hosting, baik menggunakan metode **Upload ZIP (Manual)** maupun **Terminal / SSH**. 

---

## METODE 1: Menggunakan Upload ZIP (Jika Hosting Tidak Punya Terminal)

Gunakan cara ini jika cPanel hosting Anda tidak menyediakan fitur Terminal atau Anda tidak memiliki akses SSH.

### Tahap 1: Persiapan di Komputer Lokal
1. **Buka terminal lokal Anda** (di direktori proyek, misalnya `D:\laragon\www\jurnal7kaih`).
2. **Build Aset Frontend**:
   ```bash
   npm run build
   ```
   *(Pastikan folder `public/build` sudah terbentuk jika Anda menggunakan Vite).*
3. **Bersihkan Cache dan Install Vendor (Siap Produksi)**:
   ```bash
   php artisan optimize:clear
   composer install --optimize-autoloader --no-dev
   ```
4. **Export Database**:
   Buka **phpMyAdmin** lokal (biasanya `localhost/phpmyadmin`), pilih database Anda, lalu klik **Export** untuk mengunduh file `.sql`.
5. **Buat File ZIP**:
   Buka folder proyek Anda di File Explorer. Blok semua file dan folder **KECUALI** folder `node_modules` dan `.git`. 
   Klik kanan -> jadikan file `.zip` (misalnya `project.zip`).
   > **PENTING**: Pastikan folder `vendor` dan `public/build` ikut ter-zip.

### Tahap 2: Pengaturan di cPanel Hosting
1. **Buat Subdomain / Domain**:
   - Di cPanel, masuk ke menu **Domains** atau **Subdomains**.
   - Buat nama domain (misal: `jurnal.sekolah.sch.id`).
   - Arahkan **Document Root** ke `/namafolder/public` (contoh: `/jurnal/public`). Penambahan `/public` ini **sangat penting** demi keamanan Laravel.
2. **Siapkan Database**:
   - Masuk ke menu **MySQL Databases**.
   - Buat **New Database** (misal: `sekolah_jurnal`).
   - Buat **New User** beserta *Password*-nya (misal: `sekolah_user`).
   - Scroll ke **Add User To Database**, pasangkan user dan database yang baru dibuat, lalu centang **ALL PRIVILEGES**.
3. **Import Database**:
   - Kembali ke cPanel utama, buka **phpMyAdmin**.
   - Pilih database yang baru dibuat, klik **Import**, dan unggah file `.sql` dari komputer Anda.

### Tahap 3: Upload File
1. Buka **File Manager** di cPanel.
2. Masuk ke folder *document root* domain Anda (misalnya folder `jurnal`).
3. Pastikan kosong, lalu klik **Upload** dan pilih file `project.zip` Anda.
4. Setelah 100%, klik kanan file ZIP tersebut di File Manager dan pilih **Extract**. Pastikan isi file langsung berada di dalam folder tersebut (tidak terbungkus folder lain lagi).
5. Hapus file `project.zip` untuk menghemat ruang.

### Tahap 4: Penyesuaian Lingkungan (.env)
1. Di File Manager, cari file `.env` (Jika tidak terlihat, klik *Settings* di pojok kanan atas File Manager dan centang "Show Hidden Files").
2. Edit file `.env` dan ubah menjadi seperti ini:
   ```env
   APP_NAME="Jurnal 7KAIH"
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://jurnal.sekolah.sch.id

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sekolah_jurnal      # Nama DB cPanel
   DB_USERNAME=sekolah_user        # User DB cPanel
   DB_PASSWORD=password_db_anda
   ```
3. Simpan perubahan.

### Tahap 5: Mengatasi Gambar Tidak Muncul (Storage Link)
Karena tidak ada terminal untuk mengetik `php artisan storage:link`, gunakan trik ini:
1. Buat file baru bernama `symlink.php` di dalam folder `public/`.
2. Isi file tersebut dengan kode berikut:
   ```php
   <?php
   $targetFolder = $_SERVER['DOCUMENT_ROOT'].'/../storage/app/public';
   $linkFolder = $_SERVER['DOCUMENT_ROOT'].'/storage';
   symlink($targetFolder, $linkFolder);
   echo 'Symlink sukses dibuat!';
   ```
3. Buka browser dan akses `https://jurnal.sekolah.sch.id/symlink.php`.
4. Jika muncul pesan sukses, **segera hapus** file `symlink.php` demi keamanan.

Selesai! Website sudah online.

---
---

## METODE 2: Menggunakan Terminal Server (SSH)

Gunakan cara ini jika Anda merasa lebih nyaman dengan perintah berbasis teks dan hosting Anda mendukung akses **Terminal** di dalam cPanel atau SSH jarak jauh. Metode ini jauh lebih cepat.

### Tahap 1: Pengaturan Domain dan Database
Lakukan pembuatan **Subdomain (dengan Document Root ke folder `/public`)** dan pembuatan **MySQL Database & User** persis seperti *Tahap 2 pada Metode 1*.

### Tahap 2: Eksekusi Terminal
1. Buka fitur **Terminal** di cPanel (atau gunakan PuTTY/aplikasi SSH dari komputer Anda).
2. Pindah ke direktori *home* pengguna Anda:
   ```bash
   cd ~
   ```
3. **Kloning Repositori**:
   *(Pastikan folder belum ada. Jika folder bawaan cPanel sudah ada dan kosong, Anda bisa menghapusnya dulu dengan `rm -rf namafolder`)*
   ```bash
   git clone https://github.com/username/repo-anda.git namafolder
   cd namafolder
   ```
4. **Instalasi Dependensi PHP (Composer)**:
   ```bash
   composer install --optimize-autoloader --no-dev
   ```
5. **Siapkan File .env**:
   ```bash
   cp .env.example .env
   nano .env
   ```
   *Edit bagian ini:*
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `APP_URL=https://domainanda.com`
   - Isi `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` sesuai data dari cPanel.
   *Simpan dengan `Ctrl+X`, lalu `Y`, lalu `Enter`.*
6. **Eksekusi Laravel Commands**:
   Jalankan serangkaian perintah wajib ini:
   ```bash
   # Membuat kunci aplikasi
   php artisan key:generate

   # Melakukan migrasi struktur tabel (ketik "yes" jika ditanya)
   php artisan migrate --force

   # Menghubungkan folder storage gambar
   php artisan storage:link

   # Optimasi aplikasi (wajib untuk production)
   php artisan optimize
   ```

### Tahap 3: Build Aset Frontend (Opsional)
Jika server Anda mendukung Node.js, Anda bisa membangun aset langsung di server:
```bash
npm install
npm run build
```
*(Namun jika hosting Anda sangat terbatas spesifikasinya (shared hosting murah), **disarankan** Anda tetap melakukan `npm run build` di komputer lokal, lalu mem-push folder `public/build` ke Git sebelum melakukan clone di server).*

### Mengimpor Data Lama (Opsional)
Jika Anda punya data dari *localhost* yang ingin dibawa:
- Jangan jalankan `php artisan migrate`.
- Langsung masuk ke **phpMyAdmin** cPanel dan Import file `.sql` Anda. 

Selesai! Aplikasi sudah dapat diakses.

---
> **💡 TIP CARA UPDATE KE DEPANNYA (Jika Pakai Terminal)**:
> Tiap kali Anda selesai memodifikasi kode di lokal dan sudah di-push ke GitHub, Anda cukup buka Terminal cPanel dan ketik:
> ```bash
> cd namafolder
> git pull origin main
> php artisan optimize
> ```
> Simpel, bukan?
