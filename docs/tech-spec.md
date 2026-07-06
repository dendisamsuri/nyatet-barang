# Tech Spec — Nyatet Barang

## 1. Ringkasan Teknis

**Nyatet Barang** adalah aplikasi web multi-user berbasis Laravel dan MariaDB untuk mencatat barang, servis, detail biaya, jarak, pengisian bensin, dan token listrik.

Stack utama:

* Backend: Laravel
* Database: MariaDB
* Frontend: Blade
* Styling: Tailwind CSS
* Chart: Chart.js
* Auth: Laravel Breeze
* Server: Linux VPS
* Web server: Nginx atau Apache
* PHP: 8.2 atau 8.3
* Database: MariaDB 10.6+

---

## 2. Arsitektur Sistem

```text
Browser
  ↓
Laravel Web App
  ↓
MariaDB
```

Komponen Laravel:

```text
routes/web.php
Controllers
Requests / Validation
Models
Migrations
Blade Views
Chart.js
MariaDB
```

---

## 3. Modul Teknis

Aplikasi terdiri dari modul:

1. Auth
2. Items
3. Services
4. Service Details
5. Distance Logs
6. Fuel Types
7. Fuel Logs
8. Electricity Logs
9. Dashboard
10. Reports
11. Settings

---

## 4. Database Schema

## 4.1 `users`

Tabel bawaan Laravel untuk user.

Field utama:

```text
id
name
email
password
remember_token
created_at
updated_at
```

---

## 4.2 `items`

Menyimpan data barang milik user.

```sql
CREATE TABLE items (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(150) NOT NULL,
    notes TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    CONSTRAINT fk_items_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
);
```

Index:

```sql
CREATE INDEX idx_items_user_id ON items(user_id);
```

---

## 4.3 `services`

Menyimpan catatan servis barang.

```sql
CREATE TABLE services (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    item_id BIGINT UNSIGNED NOT NULL,
    service_date DATE NOT NULL,
    notes TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    CONSTRAINT fk_services_item
        FOREIGN KEY (item_id) REFERENCES items(id)
        ON DELETE CASCADE
);
```

Index:

```sql
CREATE INDEX idx_services_item_date ON services(item_id, service_date);
```

---

## 4.4 `service_details`

Menyimpan detail biaya servis.

```sql
CREATE TABLE service_details (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    service_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(150) NOT NULL,
    price DECIMAL(12,2) NOT NULL DEFAULT 0,
    notes TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    CONSTRAINT fk_service_details_service
        FOREIGN KEY (service_id) REFERENCES services(id)
        ON DELETE CASCADE
);
```

Index:

```sql
CREATE INDEX idx_service_details_service_id ON service_details(service_id);
CREATE INDEX idx_service_details_name ON service_details(name);
```

Catatan:

* `price` boleh 0.
* Nama detail bebas, misalnya `Kampas rem`, `Tromol belakang`, atau `Jasa servis`.

---

## 4.5 `distance_logs`

Menyimpan catatan jarak manual.

```sql
CREATE TABLE distance_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    item_id BIGINT UNSIGNED NOT NULL,
    log_date DATE NOT NULL,
    distance DECIMAL(10,2) NOT NULL DEFAULT 0,
    notes TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    CONSTRAINT fk_distance_logs_item
        FOREIGN KEY (item_id) REFERENCES items(id)
        ON DELETE CASCADE
);
```

Index:

```sql
CREATE INDEX idx_distance_logs_item_date ON distance_logs(item_id, log_date);
```

Catatan:

* Satuan distance adalah kilometer.
* Boleh desimal.
* Data bersifat estimasi.

---

## 4.6 `fuel_types`

Menyimpan daftar jenis BBM milik user.

```sql
CREATE TABLE fuel_types (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id BIGINT UNSIGNED NOT NULL,
    name VARCHAR(100) NOT NULL,
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    CONSTRAINT fk_fuel_types_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
);
```

Index:

```sql
CREATE INDEX idx_fuel_types_user_active ON fuel_types(user_id, is_active);
```

Unique optional:

```sql
CREATE UNIQUE INDEX uq_fuel_types_user_name ON fuel_types(user_id, name);
```

---

## 4.7 `fuel_logs`

Menyimpan catatan pengisian bensin.

```sql
CREATE TABLE fuel_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    item_id BIGINT UNSIGNED NOT NULL,
    fuel_type_id BIGINT UNSIGNED NOT NULL,
    fuel_date DATE NOT NULL,
    amount DECIMAL(12,2) NOT NULL DEFAULT 0,
    liter DECIMAL(8,3) NULL,
    notes TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    CONSTRAINT fk_fuel_logs_item
        FOREIGN KEY (item_id) REFERENCES items(id)
        ON DELETE CASCADE,

    CONSTRAINT fk_fuel_logs_fuel_type
        FOREIGN KEY (fuel_type_id) REFERENCES fuel_types(id)
        ON DELETE RESTRICT
);
```

Index:

```sql
CREATE INDEX idx_fuel_logs_item_date ON fuel_logs(item_id, fuel_date);
CREATE INDEX idx_fuel_logs_fuel_type ON fuel_logs(fuel_type_id);
```

Catatan:

* `liter` opsional.
* `fuel_type_id` tidak boleh mengarah ke BBM milik user lain.
* Validasi ownership dilakukan di backend.

---

## 4.8 `electricity_logs`

Menyimpan catatan pembelian token listrik.

```sql
CREATE TABLE electricity_logs (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    item_id BIGINT UNSIGNED NOT NULL,
    log_date DATE NOT NULL,
    before_kwh DECIMAL(10,2) NOT NULL DEFAULT 0,
    amount DECIMAL(12,2) NOT NULL DEFAULT 0,
    purchased_kwh DECIMAL(10,2) NULL,
    after_kwh DECIMAL(10,2) NOT NULL DEFAULT 0,
    notes TEXT NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL,

    CONSTRAINT fk_electricity_logs_item
        FOREIGN KEY (item_id) REFERENCES items(id)
        ON DELETE CASCADE
);
```

Index:

```sql
CREATE INDEX idx_electricity_logs_item_date ON electricity_logs(item_id, log_date);
```

Catatan:

* `before_kwh` adalah sisa token sebelum isi.
* `amount` adalah nominal pembelian token.
* `purchased_kwh` opsional.
* `after_kwh` adalah sisa token setelah isi.
* Jika `purchased_kwh` kosong, sistem menghitung estimasi dari `after_kwh - before_kwh`.

---

## 5. Relasi Model

## 5.1 User

```php
public function items()
{
    return $this->hasMany(Item::class);
}

public function fuelTypes()
{
    return $this->hasMany(FuelType::class);
}
```

---

## 5.2 Item

```php
public function user()
{
    return $this->belongsTo(User::class);
}

public function services()
{
    return $this->hasMany(Service::class);
}

public function distanceLogs()
{
    return $this->hasMany(DistanceLog::class);
}

public function fuelLogs()
{
    return $this->hasMany(FuelLog::class);
}

public function electricityLogs()
{
    return $this->hasMany(ElectricityLog::class);
}
```

---

## 5.3 Service

```php
public function item()
{
    return $this->belongsTo(Item::class);
}

public function details()
{
    return $this->hasMany(ServiceDetail::class);
}
```

---

## 5.4 ServiceDetail

```php
public function service()
{
    return $this->belongsTo(Service::class);
}
```

---

## 5.5 DistanceLog

```php
public function item()
{
    return $this->belongsTo(Item::class);
}
```

---

## 5.6 FuelType

```php
public function user()
{
    return $this->belongsTo(User::class);
}

public function fuelLogs()
{
    return $this->hasMany(FuelLog::class);
}
```

---

## 5.7 FuelLog

```php
public function item()
{
    return $this->belongsTo(Item::class);
}

public function fuelType()
{
    return $this->belongsTo(FuelType::class);
}
```

---

## 5.8 ElectricityLog

```php
public function item()
{
    return $this->belongsTo(Item::class);
}
```

---

## 6. Struktur Folder Laravel

```text
app/
  Models/
    Item.php
    Service.php
    ServiceDetail.php
    DistanceLog.php
    FuelType.php
    FuelLog.php
    ElectricityLog.php

  Http/
    Controllers/
      DashboardController.php
      ItemController.php
      ServiceController.php
      DistanceLogController.php
      FuelTypeController.php
      FuelLogController.php
      ElectricityLogController.php
      ReportController.php

    Requests/
      StoreItemRequest.php
      UpdateItemRequest.php
      StoreServiceRequest.php
      UpdateServiceRequest.php
      StoreDistanceLogRequest.php
      UpdateDistanceLogRequest.php
      StoreFuelTypeRequest.php
      UpdateFuelTypeRequest.php
      StoreFuelLogRequest.php
      UpdateFuelLogRequest.php
      StoreElectricityLogRequest.php
      UpdateElectricityLogRequest.php

database/
  migrations/
    create_items_table.php
    create_services_table.php
    create_service_details_table.php
    create_distance_logs_table.php
    create_fuel_types_table.php
    create_fuel_logs_table.php
    create_electricity_logs_table.php

resources/
  views/
    dashboard/
      index.blade.php

    items/
      index.blade.php
      create.blade.php
      edit.blade.php
      show.blade.php

    services/
      index.blade.php
      create.blade.php
      edit.blade.php
      show.blade.php

    distance_logs/
      index.blade.php
      create.blade.php
      edit.blade.php

    fuel_types/
      index.blade.php
      create.blade.php
      edit.blade.php

    fuel_logs/
      index.blade.php
      create.blade.php
      edit.blade.php

    electricity_logs/
      index.blade.php
      create.blade.php
      edit.blade.php

    reports/
      index.blade.php
```

---

## 7. Routing

```php
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\DistanceLogController;
use App\Http\Controllers\FuelTypeController;
use App\Http\Controllers\FuelLogController;
use App\Http\Controllers\ElectricityLogController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('items', ItemController::class);

    Route::resource('services', ServiceController::class);

    Route::resource('distance-logs', DistanceLogController::class);

    Route::resource('fuel-types', FuelTypeController::class);

    Route::resource('fuel-logs', FuelLogController::class);

    Route::resource('electricity-logs', ElectricityLogController::class);

    Route::get('/reports', [ReportController::class, 'index'])
        ->name('reports.index');
});
```

---

## 8. Controller Responsibility

## 8.1 `DashboardController`

Tanggung jawab:

* Menampilkan ringkasan dashboard.
* Menerima filter barang.
* Menerima filter periode.
* Menghitung total servis, bensin, listrik, jarak.
* Mengirim data chart ke Blade.

Input query:

```text
item_id
period
start_date
end_date
```

`item_id` bisa kosong atau bernilai `all`.

---

## 8.2 `ItemController`

Tanggung jawab:

* CRUD barang.
* Memastikan barang milik user login.
* Menampilkan detail barang.

Method:

```text
index
create
store
show
edit
update
destroy
```

---

## 8.3 `ServiceController`

Tanggung jawab:

* CRUD servis.
* Simpan detail biaya servis.
* Update detail biaya servis.
* Hapus servis beserta detailnya.
* Memastikan barang milik user login.

Method:

```text
index
create
store
show
edit
update
destroy
```

---

## 8.4 `DistanceLogController`

Tanggung jawab:

* CRUD catatan jarak.
* Memastikan barang milik user login.

---

## 8.5 `FuelTypeController`

Tanggung jawab:

* CRUD jenis BBM.
* Aktif/nonaktif jenis BBM.
* Memastikan jenis BBM milik user login.

Catatan:

* Delete permanen tidak disarankan jika data sudah dipakai.
* Tombol yang disarankan: Aktifkan / Nonaktifkan.

---

## 8.6 `FuelLogController`

Tanggung jawab:

* CRUD pengisian bensin.
* Memastikan barang milik user login.
* Memastikan fuel type milik user login.
* Menampilkan BBM aktif pada form.

---

## 8.7 `ElectricityLogController`

Tanggung jawab:

* CRUD token listrik.
* Menghitung estimasi kWh bertambah.
* Memastikan barang milik user login.

---

## 8.8 `ReportController`

Tanggung jawab:

* Menampilkan laporan yang lebih detail daripada dashboard.
* Filter barang.
* Filter periode.
* Menampilkan tabel data dan chart.

---

## 9. Validasi

## 9.1 Item

```php
[
    'name' => ['required', 'string', 'max:150'],
    'notes' => ['nullable', 'string'],
]
```

---

## 9.2 Service

```php
[
    'item_id' => ['required', 'exists:items,id'],
    'service_date' => ['required', 'date'],
    'notes' => ['nullable', 'string'],
    'details' => ['nullable', 'array'],
    'details.*.name' => ['required_with:details', 'string', 'max:150'],
    'details.*.price' => ['required_with:details', 'numeric', 'min:0'],
    'details.*.notes' => ['nullable', 'string'],
]
```

Catatan:

* Servis boleh dibuat tanpa detail.
* Jika detail diisi, nama dan harga wajib.
* Harga boleh 0.

---

## 9.3 Distance Log

```php
[
    'item_id' => ['required', 'exists:items,id'],
    'log_date' => ['required', 'date'],
    'distance' => ['required', 'numeric', 'min:0'],
    'notes' => ['nullable', 'string'],
]
```

---

## 9.4 Fuel Type

```php
[
    'name' => ['required', 'string', 'max:100'],
    'is_active' => ['nullable', 'boolean'],
]
```

---

## 9.5 Fuel Log

```php
[
    'item_id' => ['required', 'exists:items,id'],
    'fuel_type_id' => ['required', 'exists:fuel_types,id'],
    'fuel_date' => ['required', 'date'],
    'amount' => ['required', 'numeric', 'min:0'],
    'liter' => ['nullable', 'numeric', 'gt:0'],
    'notes' => ['nullable', 'string'],
]
```

---

## 9.6 Electricity Log

```php
[
    'item_id' => ['required', 'exists:items,id'],
    'log_date' => ['required', 'date'],
    'before_kwh' => ['required', 'numeric', 'min:0'],
    'amount' => ['required', 'numeric', 'min:0'],
    'purchased_kwh' => ['nullable', 'numeric', 'min:0'],
    'after_kwh' => ['required', 'numeric', 'min:0'],
    'notes' => ['nullable', 'string'],
]
```

Tambahan validasi logic:

```text
after_kwh sebaiknya >= before_kwh
```

Jika tidak, tampilkan warning atau validation error.

Untuk MVP, lebih aman dijadikan error agar data tidak membingungkan.

---

## 10. Authorization dan Data Ownership

Karena aplikasi multi-user, semua data harus dibatasi berdasarkan user login.

## 10.1 Cek Item Milik User

Contoh:

```php
$item = Item::where('user_id', auth()->id())
    ->findOrFail($id);
```

## 10.2 Query Service Milik User

```php
$services = Service::whereHas('item', function ($query) {
    $query->where('user_id', auth()->id());
});
```

## 10.3 Query Fuel Type Milik User

```php
$fuelType = FuelType::where('user_id', auth()->id())
    ->findOrFail($id);
```

## 10.4 Rule Penting

User tidak boleh melakukan:

* Melihat barang user lain.
* Mengedit barang user lain.
* Menghapus barang user lain.
* Memilih fuel type milik user lain.
* Melihat laporan user lain.

---

## 11. Filter Dashboard

## 11.1 Default Periode

Default periode adalah bulan berjalan.

Contoh:

```text
start_date = awal bulan ini
end_date = hari ini
```

## 11.2 Custom Periode

User dapat memilih:

```text
start_date
end_date
```

Validasi:

```text
start_date <= end_date
```

## 11.3 Filter Barang

Opsi:

```text
all
item_id tertentu
```

Jika `all`, query mengambil semua barang milik user.

Jika `item_id`, query hanya mengambil satu barang milik user.

---

## 12. Query Laporan

## 12.1 Base Item Filter

Untuk barang tertentu:

```sql
SELECT id
FROM items
WHERE user_id = :user_id
  AND id = :item_id;
```

Untuk semua barang:

```sql
SELECT id
FROM items
WHERE user_id = :user_id;
```

---

## 12.2 Total Biaya Servis

```sql
SELECT COALESCE(SUM(sd.price), 0) AS total_service_cost
FROM service_details sd
JOIN services s ON s.id = sd.service_id
JOIN items i ON i.id = s.item_id
WHERE i.user_id = :user_id
  AND s.service_date BETWEEN :start_date AND :end_date
  AND (:item_id IS NULL OR i.id = :item_id);
```

---

## 12.3 Total Biaya Bensin

```sql
SELECT COALESCE(SUM(fl.amount), 0) AS total_fuel_cost
FROM fuel_logs fl
JOIN items i ON i.id = fl.item_id
WHERE i.user_id = :user_id
  AND fl.fuel_date BETWEEN :start_date AND :end_date
  AND (:item_id IS NULL OR i.id = :item_id);
```

---

## 12.4 Total Liter Bensin

```sql
SELECT COALESCE(SUM(fl.liter), 0) AS total_fuel_liter
FROM fuel_logs fl
JOIN items i ON i.id = fl.item_id
WHERE i.user_id = :user_id
  AND fl.fuel_date BETWEEN :start_date AND :end_date
  AND (:item_id IS NULL OR i.id = :item_id);
```

---

## 12.5 Total Jarak

```sql
SELECT COALESCE(SUM(dl.distance), 0) AS total_distance
FROM distance_logs dl
JOIN items i ON i.id = dl.item_id
WHERE i.user_id = :user_id
  AND dl.log_date BETWEEN :start_date AND :end_date
  AND (:item_id IS NULL OR i.id = :item_id);
```

---

## 12.6 Total Biaya Listrik

```sql
SELECT COALESCE(SUM(el.amount), 0) AS total_electricity_cost
FROM electricity_logs el
JOIN items i ON i.id = el.item_id
WHERE i.user_id = :user_id
  AND el.log_date BETWEEN :start_date AND :end_date
  AND (:item_id IS NULL OR i.id = :item_id);
```

---

## 12.7 Total kWh Bertambah

```sql
SELECT COALESCE(SUM(
    CASE
        WHEN el.purchased_kwh IS NOT NULL THEN el.purchased_kwh
        ELSE el.after_kwh - el.before_kwh
    END
), 0) AS total_added_kwh
FROM electricity_logs el
JOIN items i ON i.id = el.item_id
WHERE i.user_id = :user_id
  AND el.log_date BETWEEN :start_date AND :end_date
  AND (:item_id IS NULL OR i.id = :item_id);
```

---

## 12.8 Detail Servis Paling Sering

```sql
SELECT sd.name, COUNT(*) AS total_count
FROM service_details sd
JOIN services s ON s.id = sd.service_id
JOIN items i ON i.id = s.item_id
WHERE i.user_id = :user_id
  AND s.service_date BETWEEN :start_date AND :end_date
  AND (:item_id IS NULL OR i.id = :item_id)
GROUP BY sd.name
ORDER BY total_count DESC
LIMIT 10;
```

---

## 12.9 Total Biaya per Detail Servis

```sql
SELECT sd.name, SUM(sd.price) AS total_cost
FROM service_details sd
JOIN services s ON s.id = sd.service_id
JOIN items i ON i.id = s.item_id
WHERE i.user_id = :user_id
  AND s.service_date BETWEEN :start_date AND :end_date
  AND (:item_id IS NULL OR i.id = :item_id)
GROUP BY sd.name
ORDER BY total_cost DESC
LIMIT 10;
```

---

## 12.10 Total Jarak per Tanggal

```sql
SELECT dl.log_date, SUM(dl.distance) AS total_distance
FROM distance_logs dl
JOIN items i ON i.id = dl.item_id
WHERE i.user_id = :user_id
  AND dl.log_date BETWEEN :start_date AND :end_date
  AND (:item_id IS NULL OR i.id = :item_id)
GROUP BY dl.log_date
ORDER BY dl.log_date ASC;
```

---

## 12.11 Total kWh per Tanggal

```sql
SELECT el.log_date,
       SUM(
           CASE
               WHEN el.purchased_kwh IS NOT NULL THEN el.purchased_kwh
               ELSE el.after_kwh - el.before_kwh
           END
       ) AS total_added_kwh
FROM electricity_logs el
JOIN items i ON i.id = el.item_id
WHERE i.user_id = :user_id
  AND el.log_date BETWEEN :start_date AND :end_date
  AND (:item_id IS NULL OR i.id = :item_id)
GROUP BY el.log_date
ORDER BY el.log_date ASC;
```

---

## 13. Perhitungan Backend

## 13.1 Total Pengeluaran

```php
$totalExpense = $totalServiceCost + $totalFuelCost + $totalElectricityCost;
```

## 13.2 Biaya Bensin per KM

```php
$fuelCostPerKm = $totalDistance > 0
    ? $totalFuelCost / $totalDistance
    : null;
```

## 13.3 KM per Liter

```php
$kmPerLiter = $totalFuelLiter > 0
    ? $totalDistance / $totalFuelLiter
    : null;
```

## 13.4 kWh Bertambah per Record

```php
$addedKwh = $electricityLog->purchased_kwh ?? (
    $electricityLog->after_kwh - $electricityLog->before_kwh
);
```

## 13.5 Biaya Listrik per kWh

```php
$electricityCostPerKwh = $totalAddedKwh > 0
    ? $totalElectricityCost / $totalAddedKwh
    : null;
```

---

## 14. Chart

Library:

```text
Chart.js
```

Chart MVP:

1. Bar chart detail servis paling sering.
2. Bar chart total biaya per detail servis.
3. Doughnut/bar chart servis vs bensin vs listrik.
4. Line chart total jarak per tanggal.
5. Line chart total kWh bertambah per tanggal.

---

## 15. Data untuk Chart

## 15.1 Detail Servis Paling Sering

```json
[
  {
    "name": "Kampas rem",
    "total_count": 3
  },
  {
    "name": "Jasa servis",
    "total_count": 3
  },
  {
    "name": "Oli mesin",
    "total_count": 2
  }
]
```

---

## 15.2 Total Biaya per Detail

```json
[
  {
    "name": "Tromol belakang",
    "total_cost": 350000
  },
  {
    "name": "Kampas rem",
    "total_cost": 225000
  }
]
```

---

## 15.3 Servis vs Bensin vs Listrik

```json
[
  {
    "category": "Servis",
    "amount": 650000
  },
  {
    "category": "Bensin",
    "amount": 390000
  },
  {
    "category": "Listrik",
    "amount": 200000
  }
]
```

---

## 15.4 Jarak per Tanggal

```json
[
  {
    "date": "2026-07-06",
    "total_distance": 25.5
  },
  {
    "date": "2026-07-07",
    "total_distance": 18
  }
]
```

---

## 15.5 kWh per Tanggal

```json
[
  {
    "date": "2026-07-06",
    "total_added_kwh": 65.9
  },
  {
    "date": "2026-07-20",
    "total_added_kwh": 66.2
  }
]
```

---

## 16. UI Layout

## 16.1 Sidebar Menu

```text
Dashboard
Barang
Servis
Jarak
Bensin
Listrik
Laporan
Pengaturan
  - Jenis BBM
```

---

## 16.2 Dashboard Cards

Card yang ditampilkan:

```text
Total servis
Total bensin
Total listrik
Total pengeluaran
Total jarak
Biaya bensin per km
KM per liter
Biaya listrik per kWh
```

---

## 16.3 Form Barang

Field:

```text
Nama barang
Catatan
```

---

## 16.4 Form Servis

Field utama:

```text
Barang
Tanggal servis
Catatan servis
```

Detail biaya dinamis:

```text
Nama detail
Harga
Catatan
```

Tombol:

```text
Tambah Detail
Hapus Detail
Simpan
```

---

## 16.5 Form Jarak

Field:

```text
Barang
Tanggal
Jarak kilometer
Catatan
```

---

## 16.6 Form Jenis BBM

Field:

```text
Nama BBM
Status aktif
```

---

## 16.7 Form Bensin

Field:

```text
Barang
Tanggal
Jenis BBM
Nominal
Liter
Catatan
```

---

## 16.8 Form Listrik

Field:

```text
Barang
Tanggal
Sisa kWh sebelum isi
Nominal pembelian
kWh token/pembelian
Sisa kWh setelah isi
Catatan
```

Catatan UI:

* `kWh token/pembelian` diberi label opsional.
* Sistem bisa menampilkan preview:

```text
Estimasi kWh bertambah: after_kwh - before_kwh
```

---

## 17. Format Tampilan

## 17.1 Rupiah

```text
Rp350.000
Rp1.250.000
```

## 17.2 Kilometer

```text
12 km
12,5 km
```

## 17.3 Liter

```text
2,38 liter
```

## 17.4 kWh

```text
65,9 kWh
```

## 17.5 Estimasi

Gunakan label estimasi untuk data yang dihitung dari input manual:

```text
Estimasi biaya bensin per km
Estimasi km per liter
Estimasi biaya listrik per kWh
Estimasi kWh bertambah
```

---

## 18. Seeder Awal

Karena jenis BBM harus diatur user, tidak wajib membuat seed global.

Namun setelah user registrasi, sistem boleh membuat default fuel types:

```text
Pertalite
Pertamax
Solar
```

Alternatif MVP:

* Tidak perlu default.
* User harus membuat sendiri di Pengaturan.

Rekomendasi MVP:

* Buat default sederhana setelah registrasi agar user langsung bisa mencoba fitur bensin.
* User tetap bisa edit/nonaktifkan.

---

## 19. Migration Order

Urutan migration:

```text
users
items
services
service_details
distance_logs
fuel_types
fuel_logs
electricity_logs
```

---

## 20. Security

Keamanan minimal:

1. Auth wajib untuk semua halaman data.
2. CSRF protection aktif.
3. Validasi semua input.
4. Query harus berdasarkan user login.
5. Jangan percaya `item_id` dari form tanpa cek ownership.
6. Jangan percaya `fuel_type_id` dari form tanpa cek ownership.
7. Gunakan mass assignment protection.
8. Gunakan prepared query/Eloquent.
9. Password menggunakan hashing Laravel.
10. Matikan debug di production.

---

## 21. Environment

Contoh `.env`:

```env
APP_NAME="Nyatet Barang"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://domainkamu.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nyatet_barang
DB_USERNAME=nyatet_user
DB_PASSWORD=secret
```

---

## 22. Deployment

Target server:

```text
Ubuntu Server
Nginx/Apache
PHP 8.2+
MariaDB 10.6+
Composer
Git
```

Contoh path:

```text
/var/www/nyatet-barang
```

Flow deploy:

```bash
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan storage:link
```

Permission:

```bash
sudo chown -R www-data:www-data /var/www/nyatet-barang
sudo chmod -R 775 storage bootstrap/cache
```

---

## 23. Testing Manual

## 23.1 Auth

* User bisa registrasi.
* User bisa login.
* User bisa logout.
* User tidak bisa akses dashboard tanpa login.

## 23.2 Barang

* Tambah barang berhasil.
* Edit barang berhasil.
* Hapus barang berhasil.
* Barang user lain tidak terlihat.

## 23.3 Servis

* Tambah servis berhasil.
* Tambah servis tanpa detail berhasil.
* Tambah detail biaya harga 0 berhasil.
* Tambah banyak detail berhasil.
* Total biaya servis benar.
* Edit servis berhasil.
* Hapus servis menghapus detail biaya.

## 23.4 Jarak

* Tambah jarak berhasil.
* Jarak desimal berhasil.
* Banyak catatan dalam satu tanggal berhasil.
* Total jarak periode benar.

## 23.5 Jenis BBM

* Tambah jenis BBM berhasil.
* Jenis BBM muncul di dropdown.
* Jenis BBM nonaktif tidak muncul di dropdown.
* Jenis BBM user lain tidak muncul.

## 23.6 Bensin

* Tambah bensin berhasil.
* Liter kosong berhasil.
* Liter terisi berhasil.
* Total bensin benar.
* Total liter benar.

## 23.7 Listrik

* Tambah token listrik berhasil.
* Sistem menghitung kWh bertambah.
* Jika purchased_kwh kosong, pakai after-before.
* Jika purchased_kwh diisi, pakai purchased_kwh.
* Total biaya listrik benar.
* Biaya per kWh benar.

## 23.8 Dashboard

* Filter bulan ini berjalan.
* Filter custom tanggal berjalan.
* Filter semua barang berjalan.
* Filter satu barang berjalan.
* Total servis benar.
* Total bensin benar.
* Total listrik benar.
* Total pengeluaran benar.
* Chart tampil sesuai data.

---

## 24. Error Handling

Contoh error:

```text
Nama barang wajib diisi.
Tanggal wajib diisi.
Harga tidak boleh kurang dari 0.
Jarak tidak boleh kurang dari 0.
Jenis BBM wajib dipilih.
Jenis BBM tidak valid.
Barang tidak ditemukan.
Data tidak ditemukan.
Tanggal mulai tidak boleh lebih besar dari tanggal akhir.
Sisa kWh setelah isi tidak boleh lebih kecil dari sisa kWh sebelum isi.
```

---

## 25. Prioritas Development

Urutan pengerjaan yang disarankan:

1. Setup Laravel project.
2. Install Laravel Breeze.
3. Setup MariaDB.
4. Buat migration.
5. Buat model dan relasi.
6. CRUD Barang.
7. CRUD Servis dan Service Details.
8. CRUD Jarak.
9. CRUD Jenis BBM.
10. CRUD Bensin.
11. CRUD Listrik.
12. Dashboard ringkasan.
13. Chart detail servis paling sering.
14. Chart total biaya detail servis.
15. Chart servis vs bensin vs listrik.
16. Chart jarak per tanggal.
17. Chart kWh per tanggal.
18. Testing manual.
19. Deployment.

---

## 26. Catatan Teknis Penting

1. Nama aplikasi: **Nyatet Barang**.
2. Aplikasi multi-user dari awal.
3. Istilah utama adalah **barang**.
4. Servis memiliki detail biaya.
5. Detail biaya bisa berupa part, jasa, atau biaya lain.
6. Harga detail boleh 0.
7. Jarak menggunakan kilometer dan boleh desimal.
8. Tanggal cukup DATE, tidak perlu DATETIME.
9. BBM dipilih dari dropdown berdasarkan pengaturan user.
10. Liter bensin opsional.
11. Token listrik dicatat dengan sebelum isi, nominal, dan setelah isi.
12. Laporan mendukung semua barang atau satu barang.
13. Periode MVP: bulan ini dan custom tanggal.
14. Dashboard harus selalu filter berdasarkan user login.
15. Data estimasi harus ditampilkan sebagai estimasi.
