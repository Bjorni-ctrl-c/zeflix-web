# Zeflix — Platform Streaming Film Pendek (SVOD)

Zeflix adalah aplikasi web streaming film pendek (*Subscription Video On Demand*) dengan model akses sekali bayar seumur hidup, dibangun menggunakan **Laravel 13**, antarmuka tema gelap ala **Netflix**, gateway pembayaran **Midtrans**, dan siap dideploy serverless ke **Vercel** dengan cloud database **Aiven**.

---

## 🚀 Fitur Utama

- **Katalog & Streaming Film**:
  - Filter genre, pencarian judul, dan pengurutan (Terbaru & Populer).
  - Quick-view popup modal saat mengklik poster di katalog.
  - Dukungan video fleksibel: YouTube Unlisted, direct MP4/HLS, dan Cloudflare R2 private signed URL.
  - Pemutar video dengan pencatatan durasi tontonan (*Watch Progress*) otomatis.
- **Daftar Saya (Watchlist)**:
  - Bookmark film favorit dengan 1-klik AJAX tanpa reload halaman.
- **Pembayaran Sekali Bayar (Midtrans)**:
  - Integrasi Midtrans Snap Popup resmi (QRIS, GoPay, ShopeePay, Virtual Account BCA/Mandiri/BNI/BRI).
  - Akses seluruh katalog premium terbuka otomatis setelah status pembayaran berhasil.
- **Panel Administrator**:
  - Ringkasan statistik (total film, pengguna, pelanggan aktif, pendapatan kotor).
  - Manajemen katalog film (Create, Read, Update, Delete).
  - Manajemen kategori genre dengan update berantai ke film terkait.
  - Pemantauan riwayat transaksi dan data pengguna.
  - **Log Audit Administrator**: Rekam jejak seluruh aktivitas krusial, perubahan data, dan histori login/logout admin.
- **Keamanan & Validasi**:
  - Validasi ketat form pendaftaran dan login (email wajib berdomain lengkap, pembatasan nama 3–50 karakter hanya huruf, password minimal 8 karakter).
  - Regenerasi session ID saat login/register untuk mencegah *session fixation*.
  - Proteksi CSRF (`@csrf`) pada seluruh form.
  - Proteksi rute admin (`/admin`) dengan pengalihan intended URL dan penanganan error 403/404/500 kustom bertema Zeflix.

---

## 💻 Panduan Instalasi Lokal (Laragon / PHP 8.3+)

### 1. Prasyarat
- PHP >= 8.3 (dengan ekstensi `pdo_mysql`, `fileinfo`, `mbstring`, `curl`)
- Composer >= 2.x
- MySQL / MariaDB (misal via Laragon)

### 2. Langkah Setup
1. **Clone repository**:
   ```bash
   git clone https://github.com/Bjorni-ctrl-c/zeflix-web.git
   cd zeflix-web
   ```

2. **Instal dependensi Composer**:
   ```bash
   composer install
   ```

3. **Buat file `.env`**:
   Salin `.env.example` menjadi `.env` (**catatan: file `.env` bersifat privat dan jangan pernah di-commit ke Git**):
   ```bash
   cp .env.example .env
   ```

4. **Konfigurasi Database & Kunci Aplikasi**:
   Sesuaikan `DB_DATABASE`, `DB_USERNAME`, dan `DB_PASSWORD` di `.env`, lalu generate app key:
   ```bash
   php artisan key:generate
   ```

5. **Jalankan Migrasi & Seeder Awal**:
   ```bash
   php artisan migrate --seed
   ```
   *Perintah ini membuat tabel database, akun admin awal (`admin@zeflix.test`), genre default, dan demo film.*

6. **Jalankan Server Lokal**:
   ```bash
   php artisan serve
   ```
   Akses di browser: `http://127.0.0.1:8000`.

---

## ☁️ Panduan Deploy Gratis (Vercel + Aiven)

Aplikasi ini sudah dikonfigurasi sepenuhnya untuk hosting serverless di **Vercel** dengan database cloud gratis di **Aiven**.

### 1. Buat Database MySQL di Aiven (aiven.io)
1. Buat akun gratis di [aiven.io](https://aiven.io).
2. Buat service baru **MySQL** (pilih cloud provider terdekat, misal: Singapore).
3. Catat data koneksi: **Host**, **Port**, **User** (`avnadmin`), **Password**, dan **Database** (`defaultdb`).

### 2. Deploy di Vercel (vercel.com)
1. Hubungkan repository GitHub ke [vercel.com](https://vercel.com).
2. Pada **Settings > Build & Development Settings**:
   - **Framework Preset**: Pilih **Other**.
   - **Output Directory**: Aktifkan toggle Override, isi dengan **`public`**.
3. Pada **Settings > Environment Variables**, tambahkan variabel berikut (lihat panduan lengkap di `.env.vercel.example`):
   - `APP_NAME`: `Zeflix`
   - `APP_KEY`: *(Key dari file `.env` lokal Anda)*
   - `APP_URL`: `https://zeflix-web.vercel.app`
   - `DB_CONNECTION`: `mysql`
   - `DB_HOST`: *(Host database Aiven)*
   - `DB_PORT`: *(Port database Aiven)*
   - `DB_DATABASE`: `defaultdb`
   - `DB_USERNAME`: `avnadmin`
   - `DB_PASSWORD`: *(Password database Aiven)*
   - `MYSQL_ATTR_SSL_VERIFY_SERVER_CERT`: `false`
   - `DEPLOY_SECRET`: `token-rahasia-migrasi-bebas`
   - `ADMIN_EMAIL`: `admin@zeflix.com`
   - `ADMIN_PASSWORD`: `AdminZeflix123!`
   - `MIDTRANS_SERVER_KEY`: *(Server Key Sandbox Midtrans Anda)*
   - `MIDTRANS_CLIENT_KEY`: *(Client Key Sandbox Midtrans Anda)*
   - `MIDTRANS_SIGNATURE_KEY`: *(Signature Key Midtrans Anda)*
   - `MIDTRANS_PRODUCTION`: `false`
   - `SVOD_PRICE`: `1`
4. Klik **Deploy**.

### 3. Eksekusi Migrasi Database Cloud (Sekali Klik)
Setelah deployment selesai berstatus *Ready*, buka URL ini di browser untuk mengisi database Aiven:
```text
https://zeflix-web.vercel.app/up/migrate?secret=token-rahasia-migrasi-bebas&seed=true
```
Setelah muncul pesan **BERHASIL**, web sudah aktif penuh di **[https://zeflix-web.vercel.app](https://zeflix-web.vercel.app)**.

---

## 🧪 Pengujian Otomatis

Aplikasi dilengkapi dengan rangkaian automated test (PHPUnit) yang mencakup seluruh alur autentikasi, akses katalog, transaksi Midtrans, dan hak akses admin:

```bash
php vendor/bin/phpunit
```

Untuk pengecekan standar format kode (Laravel Pint):
```bash
php vendor/bin/pint --test
```

---

## 🔒 Catatan Keamanan
- File `.env` lokal Anda telah tercantum di `.gitignore` dan **tidak akan ter-push ke GitHub**.
- Jangan pernah membagikan nilai `DEPLOY_SECRET` atau `MIDTRANS_SERVER_KEY` ke publik.
