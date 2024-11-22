# backend-rizki-saputra

## Cara Instalasi

Ikuti langkah-langkah berikut untuk menginstal dan menjalankan proyek ini:

1. **Clone Repository**
   Clone repository ini ke dalam direktori lokal Anda:
   ```bash
   git clone <repository-url>
   ```

2. **Install Dependencies**
   Masuk ke direktori proyek dan jalankan perintah berikut untuk menginstal semua dependensi yang diperlukan:
   ```bash
   composer install
   ```

3. **Migrasi Database**
   Jalankan perintah berikut untuk melakukan migrasi database:
   ```bash
   php artisan migrate
   ```

4. **Seed Database**
   Untuk mengisi database dengan data awal, gunakan perintah berikut:
   ```bash
   php artisan db:seed
   ```

5. **Jika Migrasi Gagal**
   Gunakan file `tst_ats.sql` untuk mengimpor struktur dan data database secara manual.

   - Buka aplikasi manajemen database Anda (seperti phpMyAdmin).
   - Impor file `tst_ats.sql` ke dalam database Anda.

6. **Jalankan Server**
   Setelah semua langkah di atas selesai, jalankan server dengan perintah berikut:
   ```bash
   php artisan serve
   ```