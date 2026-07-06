# Nyatet Barang

**Nyatet Barang** adalah aplikasi web multi-user untuk mencatat barang, servis, detail biaya, jarak pemakaian, pengisian bensin, dan pembelian token listrik.

Aplikasi ini dibuat untuk membantu pengguna melihat riwayat perawatan dan biaya operasional barang secara lebih rapi. Contoh penggunaan awal adalah mencatat biaya servis motor, part yang sering diganti, pengeluaran bensin, estimasi jarak tempuh, dan pembelian token listrik.

---

## Fitur Utama

* Registrasi dan login user
* Manajemen barang
* Pencatatan servis barang
* Detail biaya servis
* Pencatatan part, jasa servis, atau biaya lain
* Harga detail servis boleh 0 rupiah
* Catatan jarak manual dalam kilometer
* Catatan pengisian bensin
* Pengaturan jenis BBM per user
* Field liter bensin opsional
* Catatan token listrik prabayar
* Perhitungan estimasi kWh bertambah
* Dashboard ringkasan
* Laporan per barang atau semua barang
* Filter periode bulan ini dan custom tanggal
* Visualisasi data menggunakan chart

---

## Contoh Penggunaan

### Catatan Servis

Contoh satu catatan servis motor:

| Detail          |     Harga |
| --------------- | --------: |
| Tromol belakang | Rp350.000 |
| Kampas rem      |  Rp75.000 |
| Jasa servis     |  Rp50.000 |

Total servis dihitung otomatis dari detail biaya.

---

### Catatan Jarak

Catatan jarak bisa dibuat beberapa kali dalam satu hari.

| Tanggal     |  Jarak | Catatan           |
| ----------- | -----: | ----------------- |
| 6 Juli 2026 |  12 km | Rumah ke kantor   |
| 6 Juli 2026 | 4.5 km | Kantor ke bengkel |
| 6 Juli 2026 |   9 km | Bengkel ke rumah  |

Data jarak bersifat estimasi, misalnya berdasarkan Google Maps Timeline.

---

### Catatan Bensin

| Tanggal      |  Nominal | BBM       | Liter |
| ------------ | -------: | --------- | ----: |
| 6 Juli 2026  | Rp25.000 | Pertalite |  2.38 |
| 10 Juli 2026 | Rp30.000 | Pertalite |     - |

Field liter bersifat opsional.

---

### Catatan Token Listrik

| Tanggal     | Sebelum Isi |   Nominal | Setelah Isi |
| ----------- | ----------: | --------: | ----------: |
| 6 Juli 2026 |    12.5 kWh | Rp100.000 |    78.4 kWh |

Sistem dapat menghitung estimasi kWh bertambah:

```text
78.4 - 12.5 = 65.9 kWh
```

---

## Tech Stack

* Laravel
* MariaDB
* Blade
* Tailwind CSS
* Chart.js
* Laravel Breeze
* PHP 8.3
* Apache
* Docker
* Docker Compose
* phpMyAdmin

---

## Service Docker

Project ini menggunakan Docker Compose dengan service berikut:

| Service    | Fungsi                  | Port |
| ---------- | ----------------------- | ---: |
| app        | Laravel + PHP Apache    | 8000 |
| db         | MariaDB                 | 3306 |
| phpmyadmin | Database management     | 8080 |
| node       | Vite development server | 5173 |

---

## Modul Aplikasi

* Auth
* Barang
* Servis
* Detail Biaya Servis
* Catatan Jarak
* Jenis BBM
* Pengisian Bensin
* Token Listrik
* Dashboard
* Laporan
* Pengaturan

---

## Struktur Data Utama

```text
users
  ├── items
  │     ├── services
  │     │     └── service_details
  │     ├── distance_logs
  │     ├── fuel_logs
  │     └── electricity_logs
  │
  └── fuel_types
```

---

## Database Tables

### `users`

Tabel user bawaan Laravel.

### `items`

Menyimpan data barang milik user.

### `services`

Menyimpan riwayat servis barang.

### `service_details`

Menyimpan detail biaya servis, seperti part, jasa servis, atau biaya lain.

### `distance_logs`

Menyimpan catatan jarak manual dalam kilometer.

### `fuel_types`

Menyimpan daftar jenis BBM milik user.

### `fuel_logs`

Menyimpan catatan pengisian bensin.

### `electricity_logs`

Menyimpan catatan pembelian token listrik.

---

## Struktur Project

Contoh struktur folder project:

```text
nyatet-barang/
├── app/
├── bootstrap/
├── config/
├── database/
├── docker/
│   └── apache/
│       └── 000-default.conf
├── public/
├── resources/
├── routes/
├── storage/
├── tests/
├── docker-compose.yml
├── Dockerfile
├── .env
├── .env.example
├── composer.json
├── package.json
└── README.md
```

---

## File Docker

### `docker-compose.yml`

Buat file `docker-compose.yml` di root project:

```yaml
services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: nyatet_barang_app
    ports:
      - "8000:80"
    volumes:
      - .:/var/www/html
    depends_on:
      - db
    networks:
      - nyatet_barang_network

  db:
    image: mariadb:10.11
    container_name: nyatet_barang_db
    restart: unless-stopped
    environment:
      MARIADB_DATABASE: nyatet_barang
      MARIADB_USER: nyatet_user
      MARIADB_PASSWORD: nyatet_password
      MARIADB_ROOT_PASSWORD: root_password
    ports:
      - "3306:3306"
    volumes:
      - nyatet_barang_db_data:/var/lib/mysql
    networks:
      - nyatet_barang_network

  phpmyadmin:
    image: phpmyadmin:latest
    container_name: nyatet_barang_phpmyadmin
    restart: unless-stopped
    ports:
      - "8080:80"
    environment:
      PMA_HOST: db
      PMA_PORT: 3306
    depends_on:
      - db
    networks:
      - nyatet_barang_network

  node:
    image: node:20-alpine
    container_name: nyatet_barang_node
    working_dir: /var/www/html
    volumes:
      - .:/var/www/html
    ports:
      - "5173:5173"
    command: sh -c "npm install && npm run dev -- --host 0.0.0.0"
    networks:
      - nyatet_barang_network

volumes:
  nyatet_barang_db_data:

networks:
  nyatet_barang_network:
    driver: bridge
```

---

### `Dockerfile`

Buat file `Dockerfile` di root project:

```dockerfile
FROM php:8.3-apache

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    zip \
    curl \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        zip \
        bcmath \
        gd \
        xml \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html

EXPOSE 80
```

---

### `docker/apache/000-default.conf`

Buat folder:

```bash
mkdir -p docker/apache
```

Lalu buat file `docker/apache/000-default.conf`:

```apache
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html/public

    <Directory /var/www/html/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

---

## Instalasi Development dengan Docker

### 1. Clone Repository

```bash
git clone https://github.com/username/nyatet-barang.git
cd nyatet-barang
```

Jika project belum dibuat, buat Laravel project terlebih dahulu:

```bash
composer create-project laravel/laravel nyatet-barang
cd nyatet-barang
```

---

### 2. Copy File Environment

```bash
cp .env.example .env
```

---

### 3. Sesuaikan `.env`

Gunakan konfigurasi berikut:

```env
APP_NAME="Nyatet Barang"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=nyatet_barang
DB_USERNAME=nyatet_user
DB_PASSWORD=nyatet_password
```

Catatan penting:

```text
DB_HOST harus menggunakan db, bukan 127.0.0.1.
```

Karena Laravel berjalan di container `app`, sedangkan MariaDB berjalan di service Docker bernama `db`.

---

### 4. Build dan Jalankan Container

```bash
docker compose up -d --build
```

---

### 5. Install Dependency Laravel

```bash
docker compose exec app composer install
```

---

### 6. Generate App Key

```bash
docker compose exec app php artisan key:generate
```

---

### 7. Install Laravel Breeze

Jika project belum memiliki auth:

```bash
docker compose exec app composer require laravel/breeze --dev
docker compose exec app php artisan breeze:install blade
```

Setelah itu install dependency frontend melalui container node:

```bash
docker compose exec node npm install
```

---

### 8. Jalankan Migration

```bash
docker compose exec app php artisan migrate
```

---

### 9. Jalankan Vite

Jika service `node` sudah berjalan, Vite otomatis dijalankan oleh Docker Compose.

Jika ingin menjalankan manual:

```bash
docker compose exec node npm run dev -- --host 0.0.0.0
```

---

## Akses Aplikasi

Setelah container berjalan, akses:

```text
Laravel app  : http://localhost:8000
phpMyAdmin   : http://localhost:8080
Vite         : http://localhost:5173
```

Login phpMyAdmin:

```text
Server   : db
Username : nyatet_user
Password : nyatet_password
Database : nyatet_barang
```

Atau login sebagai root:

```text
Server   : db
Username : root
Password : root_password
```

---

## Command Docker yang Sering Dipakai

### Menjalankan Container

```bash
docker compose up -d
```

### Build Ulang Container

```bash
docker compose up -d --build
```

### Melihat Container

```bash
docker compose ps
```

### Melihat Log Semua Service

```bash
docker compose logs -f
```

### Melihat Log App

```bash
docker compose logs -f app
```

### Masuk ke Container App

```bash
docker compose exec app bash
```

### Masuk ke Container Database

```bash
docker compose exec db mariadb -u nyatet_user -p nyatet_barang
```

### Stop Container

```bash
docker compose down
```

### Stop dan Hapus Volume Database

Perintah ini akan menghapus data database lokal.

```bash
docker compose down -v
```

---

## Command Laravel via Docker

### Artisan

```bash
docker compose exec app php artisan
```

### Migration

```bash
docker compose exec app php artisan migrate
```

### Rollback Migration

```bash
docker compose exec app php artisan migrate:rollback
```

### Fresh Migration

Perintah ini akan menghapus semua tabel dan membuat ulang database.

```bash
docker compose exec app php artisan migrate:fresh
```

### Fresh Migration dengan Seeder

```bash
docker compose exec app php artisan migrate:fresh --seed
```

### Clear Cache

```bash
docker compose exec app php artisan optimize:clear
```

### Composer Install

```bash
docker compose exec app composer install
```

### Composer Update

```bash
docker compose exec app composer update
```

### NPM Install

```bash
docker compose exec node npm install
```

### NPM Build

```bash
docker compose exec node npm run build
```

---

## Permission Laravel

Jika terjadi error permission pada folder `storage` atau `bootstrap/cache`, jalankan:

```bash
docker compose exec app chmod -R 775 storage bootstrap/cache
```

Jika di Linux masih bermasalah, jalankan dari host:

```bash
sudo chmod -R 775 storage bootstrap/cache
```

---

## Environment Production

Contoh `.env` production:

```env
APP_NAME="Nyatet Barang"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://domainkamu.com

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=nyatet_barang
DB_USERNAME=nyatet_user
DB_PASSWORD=nyatet_password
```

Untuk production, sebaiknya:

* Gunakan password database yang kuat.
* Jangan expose port database ke publik.
* Matikan `APP_DEBUG`.
* Gunakan HTTPS.
* Gunakan backup database rutin.
* Gunakan reverse proxy seperti Nginx atau Cloudflare Tunnel jika diperlukan.

---

## Deployment dengan Docker

Contoh flow deployment:

```bash
git pull origin main
docker compose up -d --build
docker compose exec app composer install --no-dev --optimize-autoloader
docker compose exec node npm install
docker compose exec node npm run build
docker compose exec app php artisan migrate --force
docker compose exec app php artisan config:cache
docker compose exec app php artisan route:cache
docker compose exec app php artisan view:cache
docker compose exec app php artisan storage:link
```

---

## Backup Database

Backup database dari container MariaDB:

```bash
docker compose exec db mariadb-dump -u nyatet_user -p nyatet_barang > backup_nyatet_barang.sql
```

Restore database:

```bash
docker compose exec -T db mariadb -u nyatet_user -p nyatet_barang < backup_nyatet_barang.sql
```

---

## Perhitungan Utama

### Total Biaya Servis

```text
SUM(service_details.price)
```

### Total Biaya Bensin

```text
SUM(fuel_logs.amount)
```

### Total Liter Bensin

```text
SUM(fuel_logs.liter)
```

### Total Jarak

```text
SUM(distance_logs.distance)
```

### Estimasi Biaya Bensin per KM

```text
total_biaya_bensin / total_jarak
```

Jika total jarak 0, tampilkan `-`.

---

### Estimasi KM per Liter

```text
total_jarak / total_liter_bensin
```

Jika total liter 0 atau kosong, tampilkan `-`.

---

### Total Biaya Token Listrik

```text
SUM(electricity_logs.amount)
```

---

### Estimasi kWh Bertambah

Jika `purchased_kwh` diisi:

```text
kwh_bertambah = purchased_kwh
```

Jika `purchased_kwh` kosong:

```text
kwh_bertambah = after_kwh - before_kwh
```

---

### Estimasi Biaya per kWh

```text
total_biaya_listrik / total_kwh_bertambah
```

Jika total kWh bertambah 0, tampilkan `-`.

---

### Total Semua Pengeluaran

```text
total_servis + total_bensin + total_listrik
```

---

## Visualisasi Dashboard

Dashboard menampilkan visualisasi:

* Detail servis paling sering dicatat
* Total biaya per detail servis
* Perbandingan biaya servis, bensin, dan listrik
* Total jarak per tanggal
* Total kWh bertambah per tanggal

---

## Aturan Data Ownership

Karena aplikasi ini multi-user, semua data harus terikat ke user login.

User hanya boleh mengakses:

* Barang miliknya sendiri
* Servis dari barang miliknya sendiri
* Catatan jarak dari barang miliknya sendiri
* Jenis BBM miliknya sendiri
* Catatan bensin dari barang miliknya sendiri
* Catatan listrik dari barang miliknya sendiri
* Dashboard dan laporan miliknya sendiri

Contoh aturan query:

```php
Item::where('user_id', auth()->id())->get();
```

Untuk data yang terhubung ke barang:

```php
Service::whereHas('item', function ($query) {
    $query->where('user_id', auth()->id());
})->get();
```

---

## Validasi Penting

### Barang

* Nama barang wajib diisi.
* Catatan opsional.

### Servis

* Barang wajib dipilih.
* Tanggal servis wajib diisi.
* Catatan opsional.
* Detail biaya boleh kosong.
* Jika detail biaya diisi, nama detail wajib diisi.
* Harga detail minimal 0.

### Jarak

* Barang wajib dipilih.
* Tanggal wajib diisi.
* Jarak wajib diisi.
* Jarak minimal 0.
* Jarak menggunakan kilometer.
* Jarak boleh desimal.

### Jenis BBM

* Nama BBM wajib diisi.
* Nama BBM milik user tidak boleh duplikat.
* BBM bisa aktif atau nonaktif.

### Bensin

* Barang wajib dipilih.
* Tanggal wajib diisi.
* Jenis BBM wajib dipilih.
* Nominal minimal 0.
* Liter opsional.
* Jika liter diisi, nilainya harus lebih dari 0.

### Token Listrik

* Barang wajib dipilih.
* Tanggal wajib diisi.
* Sisa kWh sebelum isi minimal 0.
* Nominal pembelian minimal 0.
* kWh token/pembelian opsional.
* Sisa kWh setelah isi minimal 0.
* Sisa kWh setelah isi sebaiknya tidak lebih kecil dari sisa kWh sebelum isi.

---

## Roadmap

### Versi 1.1

* Export Excel
* Export PDF
* Upload foto nota
* Soft delete
* Autocomplete nama detail servis
* Kategori barang
* Kategori biaya

### Versi 1.2

* Reminder servis
* Reminder pajak kendaraan
* Reminder isi token listrik
* Reminder ganti oli
* Grafik tren bulanan
* Perbandingan antar barang

### Versi 2.0

* PWA offline-first
* Import CSV
* Import Google Maps Timeline jika memungkinkan
* Multi role
* Sharing barang ke user lain
* Aplikasi mobile
* Insight otomatis
* Rekomendasi berdasarkan pola pengeluaran

---

## Troubleshooting

### Error `SQLSTATE[HY000] [2002] Connection refused`

Pastikan `.env` menggunakan:

```env
DB_HOST=db
```

Bukan:

```env
DB_HOST=127.0.0.1
```

Lalu jalankan:

```bash
docker compose restart app
```

---

### Error `The stream or file could not be opened`

Biasanya permission folder `storage`.

Jalankan:

```bash
docker compose exec app chmod -R 775 storage bootstrap/cache
```

---

### Halaman Laravel 404 untuk route selain `/`

Pastikan Apache rewrite aktif dan `DocumentRoot` mengarah ke folder `public`.

Cek file:

```text
docker/apache/000-default.conf
```

Harus berisi:

```apache
DocumentRoot /var/www/html/public
```

Dan Dockerfile harus menjalankan:

```dockerfile
RUN a2enmod rewrite
```

---

### Vite tidak bisa diakses

Pastikan service `node` berjalan:

```bash
docker compose ps
```

Jika belum, jalankan:

```bash
docker compose up -d node
```

Pastikan Vite dijalankan dengan host `0.0.0.0`:

```bash
npm run dev -- --host 0.0.0.0
```

---

### Database ingin direset total

Perintah ini akan menghapus semua data database lokal:

```bash
docker compose down -v
docker compose up -d --build
docker compose exec app php artisan migrate
```

---

## Catatan

Data jarak, konsumsi bensin, dan token listrik pada aplikasi ini bersifat manual dan estimasi.

Karena itu, hasil perhitungan seperti biaya bensin per km, km per liter, dan biaya listrik per kWh sebaiknya dianggap sebagai perkiraan untuk membantu melihat pola pengeluaran, bukan angka mutlak.

---

## Lisensi

Project ini dapat menggunakan lisensi sesuai kebutuhan pengembang.

Contoh:

```text
MIT License
```
