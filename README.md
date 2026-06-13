# 🏠 Toko Randu Mekar — Panduan Instalasi

## Deskripsi
Website toko meubel profesional dengan fitur:
- Halaman Beranda, Produk, Detail Produk, Tentang, Kontak
- CRUD Produk lengkap (tambah, edit, hapus)
- Login Admin dengan Session PHP
- Dashboard Admin dengan statistik
- Manajemen Pesan Masuk dari pengunjung
- Desain responsif Bootstrap 5 (Cream & Coklat)
- Animasi modern, WA Float Button, Canvas Illustration

---

## ⚙️ Instalasi di XAMPP (Lokal)

### Langkah 1 — Persiapan
Pastikan XAMPP sudah terinstall dan **Apache** + **MySQL** sudah aktif di XAMPP Control Panel.

### Langkah 2 — Copy Project
Ekstrak folder `randu-mekar` ke dalam:
```
C:\xampp\htdocs\randu-mekar\
```
Atau di Linux/Mac:
```
/opt/lampp/htdocs/randu-mekar/
```

### Langkah 3 — Import Database
1. Buka browser → akses **http://localhost/phpmyadmin**
2. Klik **"New"** → buat database baru bernama `randu_mekar`
3. Klik database `randu_mekar` → tab **"Import"**
4. Klik **"Choose File"** → pilih file `database/randu_mekar.sql`
5. Klik **"Go"** / **"Import"**

### Langkah 4 — Konfigurasi Koneksi
Buka file `config/koneksi.php` dan sesuaikan:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');       // username MySQL XAMPP
define('DB_PASS', '');           // password MySQL (kosong jika default)
define('DB_NAME', 'randu_mekar');
define('WHATSAPP_NUMBER', '6281234567890'); // Ganti nomor WA toko
define('TOKO_ALAMAT', 'Jl. Randu Mekar No. 15, Sukoharjo, Jawa Tengah');
```

### Langkah 5 — Jalankan Website
Buka browser → akses:
```
http://localhost/randu-mekar/
```

Admin Panel:
```
http://localhost/randu-mekar/login.php
Username: admin
Password: password
```
> ⚠️ **Ganti password setelah login pertama!**

---

## 🌐 Deploy ke Hosting (InfinityFree / Hostinger)

### Persiapan File
1. Zip seluruh folder project
2. Upload via **File Manager** hosting atau **FTP** (FileZilla)
3. Ekstrak di folder `public_html` atau `htdocs`

### Database di Hosting
1. Masuk ke **cPanel** hosting → **MySQL Databases**
2. Buat database baru (catat nama, user, password)
3. Import file `database/randu_mekar.sql` via **phpMyAdmin**

### Sesuaikan Koneksi
Edit `config/koneksi.php` dengan data database hosting:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'username_db_hosting');
define('DB_PASS', 'password_db_hosting');
define('DB_NAME', 'nama_db_hosting');
define('SITE_URL', 'https://domainanda.com');
```

---

## 📁 Struktur Folder
```
randu-mekar/
├── index.php            ← Beranda
├── produk.php           ← Halaman produk
├── detail_produk.php    ← Detail produk
├── tentang.php          ← Tentang kami
├── kontak.php           ← Kontak & form pesan
├── login.php            ← Login admin
├── logout.php           ← Logout admin
│
├── admin/
│   ├── dashboard.php    ← Dashboard admin
│   ├── produk.php       ← Kelola produk
│   ├── tambah_produk.php
│   ├── edit_produk.php
│   ├── hapus_produk.php
│   ├── pesan.php        ← Pesan masuk
│   ├── admin_header.php
│   └── admin_footer.php
│
├── config/
│   ├── koneksi.php      ← Konfigurasi DB
│   ├── header.php       ← Header publik
│   └── footer.php       ← Footer publik
│
├── uploads/             ← Foto produk (auto)
│
├── assets/
│   ├── css/style.css
│   ├── js/main.js
│   └── images/
│
└── database/
    └── randu_mekar.sql  ← File SQL lengkap
```

---

## 🔐 Akun Default Admin
| Field    | Value      |
|----------|------------|
| Username | `admin`    |
| Password | `password` |

> Ganti password di phpMyAdmin menggunakan:
> ```sql
> UPDATE admin SET password = '$2y$10$...' WHERE username = 'admin';
> ```
> Generate hash baru dengan `password_hash('password_baru', PASSWORD_DEFAULT)` di PHP.

---

## 📞 Kustomisasi
| Hal yang perlu diganti | File | Variabel |
|---|---|---|
| Nomor WhatsApp | `config/koneksi.php` | `WHATSAPP_NUMBER` |
| Alamat toko | `config/koneksi.php` | `TOKO_ALAMAT` |
| Koordinat Maps | `kontak.php` | URL embed Google Maps |
| Link medsos | `config/footer.php` | href Instagram/FB/TikTok |
| URL website | `config/koneksi.php` | `SITE_URL` |

---

## 🛠️ Teknologi
- **PHP** Native (tanpa framework)
- **MySQL** database
- **Bootstrap 5.3** UI framework
- **Bootstrap Icons** ikon
- **Google Fonts** (Poppins)
- **Canvas API** untuk ilustrasi hero
- **Vanilla JavaScript** animasi & interaksi

---

Dibuat dengan ❤️ untuk Toko Randu Mekar
