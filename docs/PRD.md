# PRD — Nyatet Barang

## 1. Ringkasan Produk

**Nyatet Barang** adalah aplikasi web untuk mencatat barang, servis, detail biaya, jarak pemakaian, pengisian bahan bakar, dan pembelian token listrik.

Aplikasi ini dirancang untuk membantu pengguna mencatat pengeluaran dan riwayat pemakaian barang secara sederhana, terutama barang yang membutuhkan biaya perawatan atau biaya operasional rutin.

Contoh penggunaan:

* Mencatat servis motor.
* Mencatat part yang sering diganti.
* Mencatat biaya jasa servis.
* Mencatat jarak tempuh manual karena speedometer rusak.
* Mencatat pengisian bensin.
* Mencatat pembelian token listrik.
* Melihat visualisasi biaya berdasarkan periode.

Aplikasi dari awal disiapkan untuk **multi-user**, sehingga banyak pengguna dapat mendaftar dan login. Setiap pengguna hanya dapat melihat dan mengelola data miliknya sendiri.

---

## 2. Latar Belakang

Banyak orang memiliki barang yang rutin menimbulkan biaya, tetapi pencatatannya sering tidak rapi. Contohnya kendaraan pribadi yang sering servis, atau listrik prabayar yang sering diisi token.

Masalah yang sering terjadi:

* Tidak tahu total biaya servis dalam satu bulan.
* Tidak tahu part apa yang paling sering diganti.
* Tidak tahu biaya bensin per bulan.
* Tidak tahu estimasi biaya bensin per kilometer.
* Tidak tahu pemakaian token listrik dari waktu ke waktu.
* Sulit mengambil keputusan apakah barang masih layak dipertahankan.
* Nota servis atau bukti pembelian sering hilang.
* Data hanya berdasarkan ingatan.

Nyatet Barang dibuat agar pengguna bisa melihat pengeluaran dan pola pemakaian berdasarkan data yang dicatat sendiri.

---

## 3. Tujuan Produk

Tujuan utama aplikasi:

1. Membantu pengguna mencatat barang yang dimiliki.
2. Membantu pengguna mencatat riwayat servis barang.
3. Membantu pengguna mencatat detail biaya servis, termasuk part dan jasa servis.
4. Membantu pengguna mencatat jarak pemakaian barang secara manual.
5. Membantu pengguna mencatat pengisian bahan bakar.
6. Membantu pengguna mencatat pembelian token listrik.
7. Menampilkan ringkasan biaya berdasarkan periode.
8. Menampilkan visualisasi part/biaya yang sering muncul.
9. Membantu pengguna melihat apakah suatu barang boros biaya atau masih wajar.
10. Menyediakan fondasi aplikasi yang bisa dikembangkan menjadi aplikasi pencatatan barang yang lebih luas.

---

## 4. Target Pengguna

### Target Pengguna Awal

* Pemilik motor pribadi.
* Pemilik kendaraan bekas.
* Pengguna yang ingin mencatat servis kendaraan.
* Pengguna yang ingin mencatat bensin dan jarak manual.
* Pengguna listrik prabayar yang ingin mencatat pembelian token.

### Target Pengguna Lanjutan

* Driver ojol.
* Kurir.
* Pemilik banyak kendaraan.
* Pemilik alat kerja.
* Pemilik mesin usaha kecil.
* Pemilik kos, kontrakan, atau toko yang ingin mencatat token listrik.
* Pengguna yang ingin mencatat biaya operasional barang pribadi.

---

## 5. Prinsip Produk

Nyatet Barang harus mengikuti prinsip berikut:

1. **Sederhana dulu**

   Aplikasi tidak boleh terlalu banyak field yang tidak dibutuhkan.

2. **Fleksibel**

   Istilah utama adalah “barang”, bukan hanya kendaraan.

3. **Data manual tetap valid**

   Aplikasi menerima data estimasi, misalnya jarak dari Google Maps Timeline.

4. **Multi-user dari awal**

   Setiap user memiliki data sendiri.

5. **Visualisasi harus membantu keputusan**

   Grafik bukan hanya hiasan, tetapi harus membantu pengguna memahami pengeluaran.

6. **Tidak memaksa data sempurna**

   Beberapa field boleh opsional, seperti liter bensin atau kWh token.

---

## 6. Scope MVP

MVP Nyatet Barang mencakup modul berikut:

1. Auth user.
2. Manajemen barang.
3. Pencatatan servis.
4. Detail biaya servis.
5. Catatan jarak.
6. Pengaturan jenis BBM.
7. Catatan pengisian bensin.
8. Catatan token listrik.
9. Dashboard ringkasan.
10. Laporan dengan filter barang dan periode.
11. Visualisasi data.

---

## 7. Role Pengguna

### User

User adalah pengguna aplikasi yang dapat:

* Registrasi.
* Login.
* Logout.
* Membuat barang.
* Mengubah barang.
* Menghapus barang.
* Mencatat servis.
* Mencatat detail biaya servis.
* Mencatat jarak.
* Mengatur daftar BBM.
* Mencatat pengisian bensin.
* Mencatat token listrik.
* Melihat dashboard.
* Melihat laporan.

### Admin

Admin belum masuk scope MVP.

Untuk MVP, cukup gunakan role user biasa.

---

## 8. Modul dan Fitur

# 8.1 Auth

## Deskripsi

Pengguna dapat membuat akun dan login ke aplikasi.

## Fitur

* Registrasi user.
* Login user.
* Logout user.
* Reset password bisa ditambahkan jika memakai auth bawaan Laravel.
* Semua data terikat ke user login.

## Aturan

* User tidak boleh melihat data user lain.
* Semua query data harus dibatasi berdasarkan `user_id`.

---

# 8.2 Barang

## Deskripsi

Barang adalah entitas utama yang dicatat pengguna.

Contoh barang:

* Yamaha Fino 125
* Motor Beat
* Mobil Avanza
* Laptop kerja
* Printer kantor
* Token listrik rumah
* Meteran listrik toko
* Mesin potong
* Pompa air

## Field Barang

* Nama barang
* Catatan

## Fitur

* Tambah barang.
* Lihat daftar barang.
* Lihat detail barang.
* Edit barang.
* Hapus barang.

## Aturan

* Nama barang wajib diisi.
* Catatan opsional.
* Barang dimiliki oleh user tertentu.
* Barang bisa memiliki servis, jarak, bensin, dan catatan listrik.
* Jika barang dihapus, data terkait bisa ikut dihapus.
* Pada MVP, penghapusan permanen diperbolehkan.
* Pada versi lanjutan, soft delete lebih disarankan.

---

# 8.3 Servis

## Deskripsi

Servis adalah catatan kejadian perawatan atau perbaikan barang.

Contoh:

* Perbaikan rem belakang.
* Ganti oli.
* Servis CVT.
* Cek printer.
* Ganti keyboard laptop.
* Perbaikan pompa air.

## Field Servis

* Barang
* Tanggal servis
* Catatan servis

## Fitur

* Tambah servis.
* Lihat daftar servis.
* Lihat detail servis.
* Edit servis.
* Hapus servis.

## Aturan

* Barang wajib dipilih.
* Tanggal servis wajib diisi.
* Catatan servis opsional.
* Servis boleh memiliki satu atau banyak detail biaya.
* Servis boleh memiliki detail biaya bernilai 0 rupiah.
* Total biaya servis dihitung dari total detail biaya.

---

# 8.4 Detail Biaya Servis

## Deskripsi

Detail biaya servis adalah rincian biaya dalam satu catatan servis.

Detail ini tidak hanya untuk part, tetapi juga untuk jasa servis dan biaya lain.

Contoh:

| Nama Detail     |     Harga | Catatan        |
| --------------- | --------: | -------------- |
| Tromol belakang | Rp350.000 | Diganti        |
| Kampas rem      |  Rp75.000 | Diganti        |
| Jasa servis     |  Rp50.000 | Ongkos bengkel |
| Cek rem         |       Rp0 | Belum diganti  |

## Field Detail Biaya

* Nama detail
* Harga
* Catatan

## Fitur

* Tambah lebih dari satu detail dalam satu servis.
* Edit detail biaya.
* Hapus detail biaya.
* Harga boleh 0.

## Aturan

* Nama detail wajib diisi.
* Harga wajib diisi, minimal 0.
* Catatan opsional.
* Jasa servis dimasukkan sebagai detail biasa.
* Nama detail diketik bebas oleh user.

## Catatan Risiko

Karena nama detail diketik bebas, data bisa tidak konsisten.

Contoh:

* Kampas rem
* Kampas rem belakang
* Kampas belakang

Untuk MVP, hal ini diterima. Pada versi lanjutan, bisa ditambahkan autocomplete atau master data detail biaya.

---

# 8.5 Catatan Jarak

## Deskripsi

Catatan jarak digunakan untuk mencatat estimasi jarak pemakaian barang.

Fitur ini penting untuk kendaraan yang speedometer atau odometernya rusak. Pengguna dapat mencatat jarak secara manual berdasarkan Google Maps Timeline atau estimasi pribadi.

## Field Catatan Jarak

* Barang
* Tanggal
* Jarak dalam kilometer
* Catatan

## Contoh

| Tanggal     |  Jarak | Catatan           |
| ----------- | -----: | ----------------- |
| 6 Juli 2026 |  12 km | Rumah ke kantor   |
| 6 Juli 2026 | 4.5 km | Kantor ke bengkel |
| 6 Juli 2026 |   9 km | Bengkel ke rumah  |

## Fitur

* Tambah catatan jarak.
* Lihat daftar catatan jarak.
* Edit catatan jarak.
* Hapus catatan jarak.
* Mencatat beberapa jarak dalam tanggal yang sama.

## Aturan

* Barang wajib dipilih.
* Tanggal wajib diisi.
* Jarak wajib diisi.
* Jarak menggunakan satuan kilometer.
* Jarak boleh desimal.
* Catatan opsional.
* Data jarak dianggap estimasi.

---

# 8.6 Pengaturan Jenis BBM

## Deskripsi

Pengguna dapat mengatur daftar jenis BBM yang muncul pada dropdown pengisian bensin.

Setiap user memiliki daftar BBM masing-masing.

## Contoh Jenis BBM

* Pertalite
* Pertamax
* Shell Super
* BP 92
* Revvo 92

## Field Jenis BBM

* Nama BBM
* Status aktif/nonaktif

## Fitur

* Tambah jenis BBM.
* Edit jenis BBM.
* Nonaktifkan jenis BBM.
* Aktifkan kembali jenis BBM.
* Jenis BBM aktif muncul di dropdown pengisian bensin.

## Aturan

* Nama BBM wajib diisi.
* Nama BBM tidak boleh kosong.
* Jenis BBM yang sudah pernah dipakai tidak disarankan dihapus permanen.
* Untuk MVP, gunakan status aktif/nonaktif.
* Dropdown pengisian bensin hanya menampilkan jenis BBM aktif.

---

# 8.7 Pengisian Bensin

## Deskripsi

Pengisian bensin digunakan untuk mencatat pembelian bahan bakar untuk barang tertentu.

Walaupun fitur ini paling cocok untuk kendaraan, tetap dikaitkan ke barang agar fleksibel.

## Field Pengisian Bensin

* Barang
* Tanggal
* Nominal
* Jenis BBM
* Liter
* Catatan

## Catatan

Field liter bersifat opsional.

## Contoh

| Tanggal      | Barang          |  Nominal | BBM       |  Liter |
| ------------ | --------------- | -------: | --------- | -----: |
| 6 Juli 2026  | Yamaha Fino 125 | Rp25.000 | Pertalite |   2.38 |
| 10 Juli 2026 | Yamaha Fino 125 | Rp30.000 | Pertalite | kosong |

## Fitur

* Tambah pengisian bensin.
* Lihat daftar pengisian bensin.
* Edit pengisian bensin.
* Hapus pengisian bensin.
* Pilih BBM dari dropdown.
* Liter boleh kosong.

## Aturan

* Barang wajib dipilih.
* Tanggal wajib diisi.
* Nominal wajib diisi.
* Nominal minimal 0.
* Jenis BBM wajib dipilih.
* Liter opsional.
* Jika liter diisi, nilai minimal lebih dari 0.
* Catatan opsional.

---

# 8.8 Catatan Token Listrik

## Deskripsi

Catatan token listrik digunakan untuk mencatat pembelian token listrik prabayar.

Pengguna dapat mencatat sisa kWh sebelum isi token, nominal pembelian, kWh setelah isi token, dan catatan tambahan.

## Contoh Kasus

Sebelum isi token, sisa listrik adalah 12.5 kWh.

Pengguna membeli token Rp100.000.

Setelah token dimasukkan ke meteran, sisa listrik menjadi 78.4 kWh.

Sistem dapat menghitung estimasi kWh bertambah:

```text
78.4 - 12.5 = 65.9 kWh
```

## Field Catatan Token Listrik

* Barang
* Tanggal
* Sisa kWh sebelum isi
* Nominal pembelian
* kWh setelah isi
* kWh token/pembelian
* Catatan

## Field Opsional

* kWh token/pembelian bersifat opsional.
* Jika kosong, sistem dapat menghitung dari `kWh setelah isi - kWh sebelum isi`.

## Contoh

| Tanggal     | Barang              |  Sebelum |   Nominal |  Setelah | Kenaikan |
| ----------- | ------------------- | -------: | --------: | -------: | -------: |
| 6 Juli 2026 | Token listrik rumah | 12.5 kWh | Rp100.000 | 78.4 kWh | 65.9 kWh |

## Fitur

* Tambah catatan token listrik.
* Lihat daftar token listrik.
* Edit catatan token listrik.
* Hapus catatan token listrik.
* Hitung estimasi kWh bertambah.
* Hitung estimasi biaya per kWh.

## Aturan

* Barang wajib dipilih.
* Tanggal wajib diisi.
* Sisa kWh sebelum isi wajib diisi.
* Nominal pembelian wajib diisi.
* kWh setelah isi wajib diisi.
* kWh token/pembelian opsional.
* Semua angka minimal 0.
* Jika `kWh setelah isi` lebih kecil dari `kWh sebelum isi`, sistem boleh menampilkan peringatan.
* Data listrik dianggap sebagai data manual.

---

# 8.9 Dashboard

## Deskripsi

Dashboard menampilkan ringkasan pengeluaran dan pemakaian berdasarkan filter barang dan periode.

## Filter Dashboard

### Filter Barang

* Semua barang
* Barang tertentu

### Filter Periode

* Bulan ini
* Custom tanggal mulai dan tanggal akhir

## Ringkasan yang Ditampilkan

* Total biaya servis.
* Total biaya bensin.
* Total biaya token listrik.
* Total semua pengeluaran.
* Total jarak.
* Total liter bensin, jika ada.
* Estimasi biaya bensin per km.
* Estimasi km per liter, jika data liter tersedia.
* Total kWh token listrik.
* Estimasi biaya listrik per kWh.
* Detail biaya/part paling sering dicatat.
* Detail biaya/part dengan total biaya terbesar.

---

# 8.10 Laporan dan Visualisasi

## Visualisasi MVP

### 1. Detail Biaya Paling Sering Dicatat

Menampilkan nama detail servis yang paling sering muncul.

Contoh:

| Nama Detail | Jumlah |
| ----------- | -----: |
| Kampas rem  |      3 |
| Jasa servis |      3 |
| Oli mesin   |      2 |

### 2. Total Biaya per Detail

Menampilkan total biaya berdasarkan nama detail servis.

Contoh:

| Nama Detail     | Total Biaya |
| --------------- | ----------: |
| Tromol belakang |   Rp350.000 |
| Kampas rem      |   Rp225.000 |
| Jasa servis     |   Rp150.000 |

### 3. Servis vs Bensin vs Listrik

Menampilkan perbandingan pengeluaran berdasarkan kategori.

Contoh:

| Kategori |     Total |
| -------- | --------: |
| Servis   | Rp650.000 |
| Bensin   | Rp390.000 |
| Listrik  | Rp200.000 |

### 4. Total Jarak per Tanggal

Menampilkan total estimasi jarak berdasarkan tanggal dalam periode yang dipilih.

Contoh:

| Tanggal     | Total Jarak |
| ----------- | ----------: |
| 6 Juli 2026 |     25.5 km |
| 7 Juli 2026 |       18 km |

### 5. Total Token Listrik per Tanggal

Menampilkan total estimasi kWh bertambah dari pembelian token listrik.

Contoh:

| Tanggal      | kWh Bertambah |
| ------------ | ------------: |
| 6 Juli 2026  |      65.9 kWh |
| 20 Juli 2026 |      66.2 kWh |

---

## 9. User Flow

# 9.1 Registrasi dan Login

1. User membuka aplikasi.
2. User membuat akun.
3. User login.
4. User masuk ke dashboard.
5. Dashboard masih kosong jika belum ada data.

---

# 9.2 Membuat Barang

1. User membuka menu Barang.
2. User klik Tambah Barang.
3. User mengisi nama barang.
4. User mengisi catatan jika diperlukan.
5. User klik Simpan.
6. Barang muncul di daftar barang.

---

# 9.3 Mencatat Servis

1. User membuka menu Servis.
2. User klik Tambah Servis.
3. User memilih barang.
4. User mengisi tanggal servis.
5. User mengisi catatan servis.
6. User menambahkan detail biaya.
7. User mengisi nama detail dan harga.
8. User dapat menambahkan lebih dari satu detail.
9. User klik Simpan.
10. Servis tersimpan dan total biaya dihitung otomatis.

---

# 9.4 Mencatat Jarak

1. User membuka menu Jarak.
2. User klik Tambah Jarak.
3. User memilih barang.
4. User mengisi tanggal.
5. User mengisi jarak dalam kilometer.
6. User mengisi catatan, misalnya rute perjalanan.
7. User klik Simpan.
8. Data jarak masuk ke laporan.

---

# 9.5 Mengatur Jenis BBM

1. User membuka menu Pengaturan.
2. User membuka submenu Jenis BBM.
3. User klik Tambah BBM.
4. User mengisi nama BBM.
5. User menyimpan data.
6. BBM aktif muncul di dropdown pengisian bensin.

---

# 9.6 Mencatat Pengisian Bensin

1. User membuka menu Bensin.
2. User klik Tambah Pengisian.
3. User memilih barang.
4. User mengisi tanggal.
5. User memilih jenis BBM dari dropdown.
6. User mengisi nominal.
7. User mengisi liter jika tahu.
8. User mengisi catatan jika perlu.
9. User klik Simpan.
10. Data bensin masuk ke laporan.

---

# 9.7 Mencatat Token Listrik

1. User membuka menu Listrik.
2. User klik Tambah Token Listrik.
3. User memilih barang, misalnya Token listrik rumah.
4. User mengisi tanggal.
5. User mengisi sisa kWh sebelum isi token.
6. User mengisi nominal pembelian.
7. User mengisi kWh setelah isi token.
8. User mengisi kWh token/pembelian jika ingin.
9. User mengisi catatan jika perlu.
10. User klik Simpan.
11. Sistem menghitung estimasi kWh bertambah.

---

# 9.8 Melihat Dashboard

1. User membuka Dashboard.
2. User memilih filter barang.
3. User memilih periode bulan ini atau custom.
4. User melihat ringkasan biaya dan grafik.
5. User dapat mengetahui kategori biaya terbesar.

---

## 10. Aturan Perhitungan

# 10.1 Total Biaya Servis

```text
total_biaya_servis = SUM(service_details.price)
```

# 10.2 Total Biaya Bensin

```text
total_biaya_bensin = SUM(fuel_logs.amount)
```

# 10.3 Total Liter Bensin

```text
total_liter_bensin = SUM(fuel_logs.liter)
```

Jika liter kosong, tidak dihitung.

# 10.4 Total Jarak

```text
total_jarak = SUM(distance_logs.distance)
```

# 10.5 Estimasi Biaya Bensin per KM

```text
biaya_bensin_per_km = total_biaya_bensin / total_jarak
```

Jika total jarak 0, tampilkan `-`.

# 10.6 Estimasi KM per Liter

```text
km_per_liter = total_jarak / total_liter_bensin
```

Jika total liter 0, tampilkan `-`.

# 10.7 Total Biaya Token Listrik

```text
total_biaya_listrik = SUM(electricity_logs.amount)
```

# 10.8 Estimasi kWh Bertambah

Jika field `purchased_kwh` diisi:

```text
kwh_bertambah = purchased_kwh
```

Jika field `purchased_kwh` kosong:

```text
kwh_bertambah = after_kwh - before_kwh
```

# 10.9 Estimasi Biaya per kWh

```text
biaya_per_kwh = total_biaya_listrik / total_kwh_bertambah
```

Jika total kWh bertambah 0, tampilkan `-`.

# 10.10 Total Semua Pengeluaran

```text
total_pengeluaran = total_biaya_servis + total_biaya_bensin + total_biaya_listrik
```

---

## 11. Halaman Aplikasi

## 11.1 Dashboard

Isi:

* Filter barang.
* Filter periode.
* Card total servis.
* Card total bensin.
* Card total listrik.
* Card total pengeluaran.
* Card total jarak.
* Card biaya bensin per km.
* Card km per liter.
* Card biaya listrik per kWh.
* Chart detail servis paling sering.
* Chart total biaya per detail.
* Chart servis vs bensin vs listrik.
* Chart total jarak.
* Chart token listrik.

---

## 11.2 Barang

Isi:

* Daftar barang.
* Tombol tambah barang.
* Edit barang.
* Hapus barang.
* Detail barang.

---

## 11.3 Servis

Isi:

* Daftar servis.
* Tambah servis.
* Detail servis.
* Edit servis.
* Hapus servis.
* Total biaya per servis.

---

## 11.4 Jarak

Isi:

* Daftar catatan jarak.
* Tambah jarak.
* Edit jarak.
* Hapus jarak.

---

## 11.5 Bensin

Isi:

* Daftar pengisian bensin.
* Tambah bensin.
* Edit bensin.
* Hapus bensin.

---

## 11.6 Listrik

Isi:

* Daftar catatan token listrik.
* Tambah token listrik.
* Edit token listrik.
* Hapus token listrik.
* Estimasi kWh bertambah.

---

## 11.7 Pengaturan

Isi:

* Pengaturan jenis BBM.
* Tambah jenis BBM.
* Edit jenis BBM.
* Aktif/nonaktif jenis BBM.

---

## 12. Acceptance Criteria MVP

MVP dianggap selesai jika:

1. User bisa registrasi.
2. User bisa login.
3. User bisa logout.
4. User bisa membuat barang.
5. User bisa mengedit barang.
6. User bisa menghapus barang.
7. User hanya bisa melihat barang miliknya sendiri.
8. User bisa mencatat servis.
9. User bisa menambahkan detail biaya servis.
10. Detail biaya servis bisa bernilai 0 rupiah.
11. Jasa servis bisa dimasukkan sebagai detail biaya.
12. Total servis otomatis dihitung dari detail biaya.
13. User bisa mencatat jarak dengan satuan kilometer.
14. Jarak bisa desimal.
15. Dalam satu tanggal bisa ada banyak catatan jarak.
16. User bisa membuat daftar jenis BBM.
17. Jenis BBM aktif muncul di dropdown pengisian bensin.
18. User bisa mencatat pengisian bensin.
19. Field liter pada bensin bersifat opsional.
20. User bisa mencatat token listrik.
21. Sistem bisa menghitung estimasi kWh bertambah.
22. Dashboard bisa menampilkan data per barang.
23. Dashboard bisa menampilkan data semua barang.
24. Dashboard mendukung periode bulan ini.
25. Dashboard mendukung custom tanggal.
26. Dashboard menampilkan total servis, bensin, listrik, dan total pengeluaran.
27. Dashboard menampilkan total jarak.
28. Dashboard menampilkan estimasi biaya bensin per km.
29. Dashboard menampilkan estimasi biaya listrik per kWh.
30. Dashboard menampilkan visualisasi detail servis paling sering.
31. Dashboard menampilkan visualisasi total biaya per detail.
32. Dashboard menampilkan visualisasi servis vs bensin vs listrik.
33. Validasi input berjalan.
34. User tidak bisa mengakses data user lain.

---

## 13. Di Luar Scope MVP

Fitur berikut tidak masuk MVP:

* Upload foto nota.
* OCR nota.
* Reminder servis.
* Reminder isi token listrik.
* Reminder isi bensin.
* Import Google Maps Timeline otomatis.
* Export Excel/PDF.
* PWA offline.
* Multi role admin.
* Sharing barang ke user lain.
* Kategori barang.
* Kategori biaya.
* Notifikasi email.
* Notifikasi WhatsApp.
* Aplikasi Android/iOS native.

---

## 14. Roadmap

## Versi 1.1

* Export Excel.
* Export PDF.
* Upload foto nota.
* Soft delete.
* Autocomplete nama detail servis.
* Kategori barang.
* Kategori biaya.

## Versi 1.2

* Reminder servis.
* Reminder pajak kendaraan.
* Reminder isi token listrik.
* Reminder ganti oli.
* Grafik tren bulanan.
* Perbandingan antar barang.

## Versi 2.0

* PWA offline-first.
* Import CSV.
* Import Google Maps Timeline jika memungkinkan.
* Multi role.
* Sharing barang ke user lain.
* Aplikasi mobile.
* Insight otomatis.
* Rekomendasi berdasarkan pola pengeluaran.

---

## 15. Catatan Produk

Nyatet Barang harus tetap sederhana. Fokus MVP adalah mencatat data dan menampilkan ringkasan yang mudah dipahami.

Aplikasi tidak perlu langsung menjadi aplikasi keuangan yang kompleks. Tujuan awalnya adalah membantu pengguna menjawab pertanyaan seperti:

* Bulan ini barang ini sudah habis biaya berapa?
* Part apa yang paling sering diganti?
* Biaya terbesar berasal dari mana?
* Bensin habis berapa bulan ini?
* Estimasi biaya bensin per km berapa?
* Token listrik beli berapa kali bulan ini?
* Total pengeluaran semua barang berapa?
