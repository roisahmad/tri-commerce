### - Nama: Ahmad Lutfi Rois
### - Email: ahmadlutfirois@gmail.com
### - No. Hp: 087760280832

# Tripay E-Commerce

Terdapat 3 branch utama:
- **main** → hanya berisi dokumentasi (README ini).
- **frontend** → aplikasi **Nuxt 4**.
- **backend** → aplikasi **Laravel 12** + database **MySQL**.

---

## Cara Menjalankan Project

### 1. Clone Repository
```
git clone https://github.com/username/repo-xyz.git
cd repo-xyz

```
#### Backend
```
- Checkout branch
  git checkout backend
- Instal dependency
  composer install
- Membuat file .env dan isinya dengan copy file .env.example
  cp .env.example .env
- Generate key
  php artisan key:generate
- membuat database baru di local
  'CREATE DATABASE nama_database;'
- Mengubah koneksi database
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=nama_database
  DB_USERNAME=root
  DB_PASSWORD=
- Migrasi
  php artisan migrate
- Run
  php artisan serve
```

#### Frontend
```
- Checkout branch
  git checkout frontend
- Instal dependency
  yarn / npm install
- Membuat file .env dengan isi:
  API_BASE_URL=http://localhost:8000/api
- Run
  yarn dev / npm run dev
```



