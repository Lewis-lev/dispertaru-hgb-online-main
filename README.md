# Layanan Perpanjangan Hak Tanah — Dispertaru DIY

Portal pengajuan dan pemantauan perpanjangan / pembaruan Hak Guna Bangunan
(HGB) dan Hak Pakai. Aplikasi menggunakan CodeIgniter 4 dan MariaDB/MySQL.
Runtime aplikasi ini ditargetkan untuk PHP 8.2 atau yang lebih baru.

## Menjalankan aplikasi

1. Arahkan document root web server ke folder `public`.
2. Salin `env` menjadi `.env`, lalu atur `app.baseURL`, kredensial database,
   dan `CI_ENVIRONMENT`. Jangan commit `.env`.
3. Buat database dan tabel dasar aplikasi, lalu jalankan `php spark migrate
   --all` untuk menambahkan akun pemohon, akun manajer, kaitan aplikasi dengan
   akun, catatan audit, dan status/kehadiran akun petugas.
4. Buat **satu** akun manajer secara manual sebelum aplikasi dibuka. Buat hash
   kata sandi dengan PHP:

   ```sh
   php -r "echo password_hash('GANTI_DENGAN_KATA_SANDI_KUAT', PASSWORD_DEFAULT), PHP_EOL;"
   ```

   Simpan hash yang dihasilkan di database:

   ```sql
   INSERT INTO manager_accounts (username, password_hash, created_at)
   VALUES ('nama_manajer', 'TEMPEL_HASH_DI_SINI', NOW());
   ```

   Semua peran masuk melalui `/login`. Akun manajer tidak memiliki pendaftaran
   publik; jaga agar hanya ada satu akun manajer dan jangan bagikan aksesnya.
   Setelah masuk, manajer membuat akun petugas dari dasbor. Hanya pemohon yang
   dapat mendaftar sendiri melalui `/daftar`. Dasbor manajer menampilkan catatan
   audit penugasan dan setiap perubahan status petugas. Riwayat sebelum migrasi
   hanya dapat dimulai dari status terakhir yang tersedia di database.
5. Setelah manajer membuat akun petugas, setidaknya satu petugas harus tersedia
   sebelum menerima permohonan; setiap permohonan verifikasi akan ditugaskan
   ke akun petugas dengan ID terkecil. Petugas dapat mengganti kata sandinya
   dari panel.
6. Pastikan PHP dapat menulis ke `writable`, termasuk folder `writable/uploads`.
   Berkas baru disimpan di luar document root dan hanya dapat dibuka petugas
   yang sudah masuk. Berkas lama yang ada di `public/berkas` perlu dipindahkan
   ke `writable/uploads` dengan nama file yang sama.
   `public/.user.ini` sets the PHP upload limit to 10 MB per file and 16 MB per
   request, matching the form's 5 MB KTP and 10 MB PDF limits. This requires
   PHP-FPM/FastCGI with per-directory INI enabled; restart PHP or wait for
   `user_ini.cache_ttl` after changing it. If the host disables `.user.ini`,
   configure the same values in the PHP-FPM pool or hosting control panel.
   Herd on Windows may route requests through its own PHP front controller
   without applying the project's `.user.ini`; in that case, set these values
   in the active PHP version's `php.ini` (`herd ini`) and restart PHP with
   `herd restart php`.
7. Untuk Apache, aturan di `public/.htaccess` menolak akses langsung ke
   `/berkas/` dan halaman HTML lama. Untuk Nginx, tambahkan aturan setara:

   ```nginx
   location ~ ^/(berkas|admin/html|user/html)(/|$) {
       return 404;
   }
   ```

   Document root tetap harus mengarah ke `public`.
8. Gunakan HTTPS untuk deployment. Cookie sesi disetel `Secure` ketika
   `CI_ENVIRONMENT` bernilai `production`.

Kata sandi MD5 lama masih dapat dipakai untuk satu kali login; setelah berhasil,
aplikasi menggantinya dengan hash modern. Segera ganti kata sandi lama sebelum
mempublikasikan layanan.

## Keamanan dan privasi

- Pemohon mendaftar dengan email dan kata sandi; identitas lengkap dan dokumen
  wajib dimasukkan untuk setiap permohonan. Akun menampilkan semua permohonan
  baru yang dikirim saat sudah masuk.
- Pemohon, petugas, dan manajer menggunakan satu halaman masuk. Kredensial
  menentukan peran dan tujuan setelah login. Pendaftaran publik hanya membuat
  akun pemohon; hanya manajer yang dapat membuat akun petugas.
- Hanya satu akun manajer yang digunakan. Buat akun ini secara manual saat
  menyiapkan sistem dan jangan membuka proses pembuatan akun manajer ke publik.
- Manajer dapat membuat akun petugas dan membaca dasbor serta riwayat audit,
  tetapi tidak dapat mengubah permohonan. Perubahan status petugas dicatat
  bersama akun petugas dan waktu perubahan.
- Manajer dapat mengubah nama pengguna dan mereset kata sandi petugas,
  menonaktifkan/mengaktifkan akun tanpa menghapus riwayat audit, serta melihat
  kehadiran berdasarkan permintaan admin dalam lima menit terakhir.
- Status permohonan publik hanya dapat dicari dengan kode acak milik pemohon;
  daftar permohonan dan data kontak tidak ditampilkan kepada publik.
- Pengiriman formulir, login, dan perubahan oleh petugas dilindungi CSRF.
- Perubahan data, perubahan status, penghapusan, dan akses dokumen memerlukan
  sesi petugas. Penghapusan hanya memakai POST.
- Foto KTP dan surat permohonan baru tidak disimpan di folder publik.
- Tambahkan `admin.password` menggunakan hash kata sandi, bukan kata sandi
  teks biasa. Batasi akses kredensial database dan file `.env`.
- `.env` dan `writable/uploads` dikecualikan dari Git agar pengaturan lokal dan
  dokumen pemohon tidak ikut masuk ke commit.
 
