# OTEWE: Laravel + Vue

Website travel dengan backend Laravel 12, frontend Vue 3, dan Inertia.js 2. Semua halaman publik, autentikasi, dan admin menggunakan Vue. Blade hanya menyediakan dokumen awal Inertia.

## Menjalankan aplikasi

Persyaratan: PHP 8.2+, Composer, Node.js 22+, serta MySQL/MariaDB atau SQLite.

```bash
composer install
npm ci
cp .env.example .env
php artisan key:generate
```

Atur koneksi database, `APP_URL`, `ADMIN_EMAIL`, dan `ADMIN_PASSWORD` di `.env`. Kata sandi admin harus minimal 8 karakter. Nomor kontak menggunakan konfigurasi `TRAVEL_PHONE` dan `TRAVEL_WHATSAPP`; nilai awal mengikuti kontak yang sudah ada di proyek.

Untuk database baru:

```bash
php artisan migrate
php artisan db:seed
php artisan storage:link
npm run build
php artisan serve
```

Login di `/login` menggunakan akun dari `.env`. Seeder hanya membuat admin jika email tersebut belum terdaftar dan tidak mengganti password akun yang sudah ada. Katalog dan jurnal kosong sampai admin menambahkan konten. Untuk pengembangan frontend, jalankan `npm run dev` di terminal lain.

Jika memakai SQLite, buat file database dan atur path absolutnya di `.env`:

```bash
touch database/database.sqlite
```

```dotenv
DB_CONNECTION=sqlite
DB_DATABASE=/path/absolut/proyek/database/database.sqlite
```

## Memperbarui instalasi lama

Simpan cadangan database dan `storage/app/public`, pertahankan `.env` dan `APP_KEY` yang lama, lalu jalankan:

```bash
composer install
npm ci
php artisan migrate
php artisan optimize:clear
npm run build
```

Gunakan `migrate`, bukan `migrate:fresh`: migration baru hanya menambahkan kolom status booking dengan nilai awal `pending`. Data lama dan rute utama tetap digunakan. Akun admin lama tetap bisa dipakai; ubah kata sandi bawaan melalui halaman profil. Dump `laravel_otewe.sql` tetap tersedia sebagai sumber data lama dan tidak diimpor otomatis. Gambar unggahan harus dipulihkan bersama database; jika gambar belum tersedia, UI menampilkan placeholder yang jelas.

## Fitur

- Publik: beranda editorial, pencarian destinasi, filter durasi, pengurutan harga, pagination, detail paket, galeri dengan lightbox, booking, jurnal berdasarkan kategori, dan kontak WhatsApp/telepon.
- Admin: ringkasan booking dari database, jadwal keberangkatan terkonfirmasi, pencarian data, CRUD paket/artikel/kategori, unggahan dan pratinjau gambar, editor teks, status booking, daftar pengguna, dan profil.
- Interaksi: navigasi Inertia, indikator pemuatan, pesan sukses/error, dialog penghapusan, navigasi mobile, transisi foto, dan morphing kontur hero. Animasi mengikuti `prefers-reduced-motion`.
- Integritas: request tervalidasi, slug unik, pengecekan kepemilikan galeri, pembersihan file saat dihapus/diganti, penolakan penghapusan kategori yang masih digunakan, dan perlindungan paket yang memiliki booking.

Status booking: `pending`, `confirmed`, `completed`, `cancelled`. Booking publik selalu mulai dengan `pending`. Harga adalah harga paket sebagaimana tersimpan di database, bukan per peserta. Pengiriman formulir merupakan permintaan booking; pembayaran, pengiriman notifikasi booking melalui email, dan kuota perjalanan belum tersedia. Registrasi publik tetap dinonaktifkan.

Pemulihan kata sandi memakai mailer Laravel; atur SMTP sebelum menggunakan pengiriman email sungguhan. Frontend menggunakan rendering di browser; SSR belum diaktifkan.

## Struktur

```text
app/Http/Controllers/     Routing, kueri, dan penyimpanan Laravel
app/Http/Requests/        Validasi formulir
app/Http/Resources/       Bentuk data untuk Vue
app/Http/Middleware/     Autentikasi admin dan shared props Inertia
app/Support/             Pembuatan slug unik
resources/js/Pages/      Halaman publik, Admin/, Auth/
resources/js/Layouts/    Layout publik, admin, autentikasi
resources/js/Components/ Komponen formulir, kartu, galeri, dan feedback
resources/js/lib/        Format tanggal, rupiah, dan status
resources/css/app.css    Token desain, komponen, breakpoint, animasi
resources/views/         Dokumen awal Inertia
lang/id/                 Pesan autentikasi dan validasi
tests/Feature/           Pengujian perilaku backend
tests/Browser/           Pengujian interaksi desktop dan mobile
```

Arah visual dan alasan penggunaan animasi tercatat di `DESIGN.md`. Foto hero dan kontak memakai aset proyek yang sudah ada. Font menggunakan font sistem dan Georgia, sehingga tidak memerlukan layanan font eksternal.

## Pengujian

```bash
php artisan test
npm run build
```

Test PHP memakai SQLite di memori melalui `phpunit.xml` dan `.env.testing`, tanpa menyentuh database aplikasi. Kunci di `.env.testing` khusus data uji dan tidak boleh digunakan untuk aplikasi sungguhan.

Untuk pengujian browser:

```bash
npx playwright install chromium
npm run build
npm run test:e2e
```

Playwright menjalankan server port 8020 dengan database SQLite sementara yang baru, kemudian menghentikannya setelah test selesai. Pastikan port tersebut kosong. Untuk melihat tampilan secara manual menggunakan data uji yang sama:

```bash
npm run preview:test
```

Preview tersedia di `http://127.0.0.1:8020`. Fixture browser diberi label `data uji`, menggunakan akun `browser@example.test` / `BrowserTest123!`, dan hanya dibuat di lingkungan `testing` dengan SQLite. Database ini terpisah dari data bisnis dan bukan data yang akan diterbitkan. Pengujian browser membuat serta menghapus konten uji. `E2E_BASE_URL` dapat mengganti alamat server jika menunjuk ke server khusus pengujian; jangan arahkan ke aplikasi sungguhan. Trace kegagalan dan screenshot disimpan di `test-results/`.

Untuk merapikan kode frontend, gunakan `npm run format`; untuk memeriksa format tanpa mengubah file, gunakan `npm run format:check`.

## Preview dengan konten destinasi

```bash
npm run build
npm run preview
```

Buka `http://127.0.0.1:8020`. Preview ini memakai `database/preview.sqlite` yang menetap setelah server dihentikan. Tersedia 14 paket destinasi lengkap dengan itinerary dan galeri, serta 22 artikel jurnal dalam kategori Panduan, Tips, dan Cerita perjalanan. Login lokal: `admin@otewe.local` / `OtewePreview123!`. Akun ini hanya untuk preview lokal. Tidak ada booking atau peserta buatan. Harga adalah estimasi contoh yang perlu dikonfirmasi; foto koleksi lama digunakan sebagai ilustrasi.

Untuk menambahkan konten awal ke database aplikasi yang sudah dikonfigurasi di `.env`:

```bash
php artisan db:seed --class=TravelContentSeeder
```

Seeder dapat dijalankan ulang: slug yang sudah ada tidak ditimpa dan konten tidak digandakan. Konten selanjutnya dapat diedit melalui admin. `npm run preview:test` tetap tersedia untuk fixture pengujian browser yang terpisah.
