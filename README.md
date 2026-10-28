# Zeflix — Laravel 13 + MySQL

Zeflix adalah MVP streaming film dengan akses satu kali bayar Rp1, Laravel 13,
MySQL, dan Midtrans Sandbox.

## Jalankan lokal dengan Laragon

1. Pastikan Laragon MySQL aktif dan buat database `zeflix` dengan collation `utf8mb4_unicode_ci`.
2. Jalankan `composer install`, salin `.env.example` menjadi `.env`, lalu isi `ADMIN_PASSWORD` dengan password lokal minimal 12 karakter.
3. Generate application key, migrate schema, dan buat akun admin:
```bash
php artisan key:generate
php artisan migrate --seed
php artisan serve
```
4. Buka `http://127.0.0.1:8000`. Admin memakai email/password dari `ADMIN_EMAIL` dan `ADMIN_PASSWORD`; halaman CMS ada di `/admin`.
5. Untuk memulai pembayaran, isi `MIDTRANS_SERVER_KEY` dengan Server Key Sandbox dari Midtrans. Tanpa key, pembayaran sengaja ditolak.

Katalog, detail film, dan pemutar dikunci sampai webhook Midtrans memverifikasi pembayaran.
Akses Rp1 berlaku tanpa batas waktu dan tidak diperpanjang otomatis.

## MySQL untuk hosting

Gunakan layanan MySQL/MariaDB yang dikelola, misalnya TiDB Cloud atau Aiven, lalu isi
`DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD`. Untuk koneksi
TLS, isi `MYSQL_ATTR_SSL_CA` sesuai path sertifikat pada host. Jangan mengunggah `.env`
atau memakai kredensial lokal Laragon di hosting.
Jalankan `php artisan migrate --seed` dari lingkungan yang terhubung ke database tersebut.

## 3. Video gratis
- **YouTube Unlisted** (default): isi ID video di admin. ID hanya dikirim ke pengguna yang berhak, tetapi
  siapa pun yang tahu ID-nya bisa menontonnya langsung di YouTube. Ini proteksi lemah.
- **MP4/HLS lewat URL** bisa dibagikan jika origin tidak membatasi akses. Untuk Cloudflare R2 privat,
  pilih provider R2 agar aplikasi membuat signed URL. Vercel tidak cocok untuk menyimpan file video.
Cek ketentuan YouTube dan penyedia lain sebelum memakainya untuk konten berbayar.

## 3b. Video di Cloudflare R2 (disarankan)
1. Cloudflare Dashboard > R2 > buat bucket **privat** (jangan aktifkan akses publik).
2. R2 > Manage API Tokens > buat token dengan akses Object Read untuk bucket video.
3. Isi `R2_ACCESS_KEY_ID`, `R2_SECRET_ACCESS_KEY`, `R2_BUCKET`, `R2_ENDPOINT`, dan `R2_REGION=auto` di `.env` lokal serta Environment Variables di Vercel.
4. Kompres video: `ffmpeg -i asli.mov -c:v libx264 -crf 24 -preset slow -vf scale=-2:720 -c:a aac -b:a 128k -movflags +faststart film.mp4`
5. Upload video lewat dashboard R2 atau `rclone`/AWS CLI. File video tetap berada di R2; aplikasi hanya menyimpan key objeknya di kolom `films.video_ref`.
6. Di admin pilih sumber **Cloudflare R2**, lalu isi key objek (mis. `films/film-1.mp4`).

Laravel menggunakan disk `r2` dari `config/filesystems.php` untuk membuat URL bertanda tangan yang berlaku 3 jam. Tidak perlu migrasi database baru karena tabel `films` sudah memiliki kolom `video_provider` dan `video_ref`. Paket Flysystem S3 sudah menjadi dependency Composer aplikasi.

## 3c. Poster privat di Cloudflare R2
1. Buat atau gunakan bucket poster privat yang berbeda dari bucket video.
2. Buat API token R2 khusus bucket poster dengan izin **Object Read & Write**. Token video yang hanya memiliki izin baca tetap digunakan terpisah.
3. Isi `R2_POSTER_ACCESS_KEY_ID`, `R2_POSTER_SECRET_ACCESS_KEY`, dan `R2_POSTER_BUCKET` di `.env` lokal serta Environment Variables di Vercel. `R2_ENDPOINT` dan `R2_REGION` sama seperti konfigurasi R2 video.
4. Di Admin Film, unggah poster JPG, PNG, atau WebP maksimal 4 MB. Aplikasi mengunggah langsung ke disk R2 privat, menyimpan key objek di `films.poster_url`, lalu menghasilkan signed URL 30 menit saat poster ditampilkan.
5. Poster URL publik yang sudah tersimpan tetap didukung. Saat mengganti poster R2, objek lama tidak otomatis dihapus dari bucket.

R2 tidak wajib untuk tugas kuliah. Jika `R2_POSTER_BUCKET` kosong, upload poster otomatis disimpan di server lokal. Jalankan `php artisan storage:link` agar poster lokal bisa ditampilkan melalui web. Poster lokal bersifat publik; jangan gunakan untuk konten yang harus privat.

## Midtrans
Ambil Server Key Sandbox dan atur `MIDTRANS_PRODUCTION=false`. Di dashboard Midtrans set **Payment Notification URL** ke
`https://DOMAIN-ANDA/midtrans/webhook`. Produksi: `MIDTRANS_PRODUCTION=true` + key produksi.

## 5. Deploy ke Vercel
```bash
npm i -g vercel && vercel
```
Isi semua variabel di `.env.vercel.example` pada Settings > Environment Variables, lalu `vercel --prod`.
Runtime PHP memakai `vercel-php` (komunitas). **Pastikan versinya mendukung PHP 8.3+** (syarat Laravel 13);
bila belum, ubah versi di `vercel.json` atau pakai host PHP lain. Verifikasi di dokumentasi vercel-php.

## Belum ada
Reset password (butuh SMTP), perpanjangan otomatis (perpanjangan manual saja),
teks final kebijakan privasi/ketentuan/refund, dan test otomatis.
