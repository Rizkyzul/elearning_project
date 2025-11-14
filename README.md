

# 📘 **README — Laravel Project Setup Guide**

## 🚀 **Requirements**

Pastikan sudah terinstall di komputer Anda:

* **PHP 8.1+**
* **Composer**
* **MySQL / MariaDB**
* **Node.js & NPM**
* **Git** (opsional tapi direkomendasikan)
* **Laravel 10+** (atau sesuai project)

---

## 📂 **Clone Project**

Jika project sudah di Git:

```bash
git clone https://github.com/username/nama-project.git
cd nama-project
```

---

## ⚙️ **1. Copy File Environment**

Laravel membutuhkan file `.env`.

```bash
cp .env.example .env
```

Atau Windows:

```powershell
copy .env.example .env
```

---

## 🔑 **2. Generate Application Key**

Agar enkripsi, session, dan hashing Laravel berjalan aman.

```bash
php artisan key:generate
```

---

## 💾 **3. Setup Database**

Edit file `.env`:

```
DB_DATABASE=nama_database
DB_USERNAME=root
DB_PASSWORD=
```

Setelah itu jalankan migrate:

```bash
php artisan migrate
```

Jika project punya seeder:

```bash
php artisan db:seed
```

Atau jalankan bersamaan:

```bash
php artisan migrate --seed
```

---

## 🗂️ **4. Storage Link (Wajib jika upload file)**

Untuk membuat link symbol ke `storage/app/public`:

```bash
php artisan storage:link
```

---

## 🧼 **5. Clear & Rebuild Cache (Opsional tapi direkomendasikan)**

```bash
php artisan optimize:clear
php artisan optimize
```

---

## 📦 **6. Install Composer Dependencies**

Pastikan semua library backend terinstall:

```bash
composer install
```

Jika error permission di Linux/macOS:

```bash
composer install --no-scripts
```

---

## 🎨 **7. Install Frontend Dependencies**

Untuk Vite, Tailwind, Bootstrap, Vue, React, dsb.

```bash
npm install
```

Build untuk development:

```bash
npm run dev
```

Build untuk production:

```bash
npm run build
```

---

## 🔥 **8. Jalankan Server Laravel**

```bash
php artisan serve
```

Default di:

```
http://127.0.0.1:8000
```

---

## 🧪 **Testing (Opsional)**

Jika project memiliki unit test:

```bash
php artisan test
```

---

## 🚚 **Deployment / Production Notes**

* Gunakan `php artisan config:cache` untuk optimasi.
* Pastikan permission folder:

```
storage/
bootstrap/cache/
```

Linux:

```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

---

## 💡 **Troubleshooting**

**Error: “APP_KEY missing”**
→ Jalankan:

```bash
php artisan key:generate
```

---

**Error saat migrate**
→ Cek DB di `.env` atau buat DB baru manual.

---

**CSS/JS tidak tampil**
→ Jalankan:

```bash
npm install
npm run dev
```

---

