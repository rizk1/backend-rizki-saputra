# backend-rizki-saputra

## Cara Instalasi

Ikuti langkah-langkah berikut untuk menginstal dan menjalankan proyek ini:

1. **Jalankan Server**
   ```bash
   php artisan serve
   ```

2. **Migrasi Database**
   Jalankan perintah berikut untuk melakukan migrasi database:
   ```bash
   php artisan migrate
   ```

3. **Seed Database**
   Untuk mengisi database dengan data awal, gunakan perintah berikut:
   ```bash
   php artisan db:seed
   ```

4. **Jika Migrasi Gagal**
   Gunakan file `tst_ats.sql` untuk mengimpor struktur dan data database secara manual.

   - Buka aplikasi manajemen database Anda (seperti phpMyAdmin).
   - Impor file `tst_ats.sql` ke dalam database Anda.
