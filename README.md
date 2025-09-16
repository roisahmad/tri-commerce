# Tripay E-Commerce

Terdapat 3 branch utama:
- **main** → hanya berisi dokumentasi (README ini).
- **frontend** → aplikasi **Nuxt 4**.
- **backend** → aplikasi **Laravel** + database MySQL.

---

## Cara Menjalankan Project

### 1. Clone Repository
```
git clone https://github.com/username/repo-xyz.git
cd repo-xyz

```
#### Backend
```
- git checkout backend
- composer install
- cp .env.example .env
- php artisan key:generate
- create database in local 'CREATE DATABASE nama_database;'
- change database connection
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=nama_database
  DB_USERNAME=root
  DB_PASSWORD=
- php artisan migrate
- php artisan serve
```

#### Frontend
```
- git checkout frontend
- yarn / npm install
- yarn dev / npm run dev
```



