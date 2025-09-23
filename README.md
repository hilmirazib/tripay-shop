# TriPay Shop

Mini e-commerce (Laravel 12 + Inertia + Vue 3 + MySQL) untuk integrasi **TriPay**.

## Kontak
- **Nama**: Hilmi Razib Yusuf
- **Email**: hilmiyusuf197@gmail.com
- **No HP**: 0897-866-3783

## Tech Stack
- Laravel 12, Inertia.js, Vue 3, Vite
- MySQL/MariaDB
- Node 22, PNPM

## Cara Menjalankan (Local)
1. `composer install`
2. `cp .env.example .env` lalu isi DB
3. `php artisan key:generate`
4. Buat DB `tripay_shop`, lalu `php artisan migrate`
5. Jalankan:
   - Backend: `php artisan serve` (http://localhost:8000)
   - Frontend: `pnpm dev`
6. Buka `http://localhost:8000`
