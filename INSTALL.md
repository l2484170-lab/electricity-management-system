# Installation Guide

## Quick Start

```bash
# 1. Extract
unzip electricity-management-system.zip
cd electricity-management-system

# 2. Install dependencies
composer install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Create SQLite database
touch database/database.sqlite

# 5. Run migrations + seed
php artisan migrate
php artisan db:seed

# 6. Start server
php artisan serve
```

## Login at http://localhost:8000
- Username: `admin`
- Password: `admin123`

## Using MySQL Instead of SQLite

1. Create a MySQL database:
```sql
CREATE DATABASE electricity_management CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

2. Update `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=electricity_management
DB_USERNAME=root
DB_PASSWORD=your_password
```

3. Run migrations:
```bash
php artisan migrate
php artisan db:seed
```

**Note:** When using MySQL, the `strftime` functions in DashboardService and EmployeeController should be changed to `DATE_FORMAT`. The SQLite version is provided by default for easy setup.

## Troubleshooting

### Permission errors
```bash
chmod -R 775 storage bootstrap/cache
```

### Cache issues
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

### Reset database
```bash
php artisan migrate:fresh --seed
```
