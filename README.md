# Electricity Station Management System - Laravel

A comprehensive electricity station management system built with Laravel. This system manages customers, meters, meter readings, invoices, payments, groups, electricity loss calculations, expenses, employees, SMS notifications, and system settings.

## Features

- **Dashboard** - Real-time analytics with charts (revenue, expenses, consumption, losses)
- **Customer Management** - CRUD, search, archive, activity logs
- **Meter Management** - Track meters with types and status
- **Meter Readings** - Record readings, auto-calculate consumption
- **Invoice Generation** - Auto-generate from readings or create manually
- **Payment Processing** - Record payments, auto-update invoice status
- **Groups & Central Meters** - Group customers, track central meter readings
- **Electricity Loss Calculation** - Compare central vs customer consumption
- **Expense Tracking** - Categories and expense records
- **Employee Management** - Attendance, salary, advances/deductions
- **SMS Notifications** - Template-based notifications for invoices/payments
- **System Settings** - Unit price, fixed fees, station info
- **Role-Based Access** - Admin, Accountant, Meter Reader, Customer Service
- **RESTful API** - Full API with Sanctum token authentication

## Requirements

- PHP 8.1 or higher
- Composer
- Node.js & NPM (optional, for asset compilation)
- SQLite (default) or MySQL/PostgreSQL

## Installation

### 1. Extract the project

```bash
unzip electricity-management-system.zip
cd electricity-management-system
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Configure environment

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Set up database

#### SQLite (default - easiest):
```bash
touch database/database.sqlite
```

#### MySQL:
Edit `.env` and set:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=electricity_management
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 5. Run migrations and seed

```bash
php artisan migrate
php artisan db:seed
```

### 6. Start the development server

```bash
php artisan serve
```

Visit: http://localhost:8000

## Default Login Credentials

| Username | Password | Role |
|----------|----------|------|
| admin | admin123 | Admin |
| accountant | account123 | Accountant |
| reader | reader123 | Meter Reader |
| cs | cs123456 | Customer Service |

## API Usage

### Authentication
```bash
# Login to get token
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"username": "admin", "password": "admin123"}'

# Use the token
curl http://localhost:8000/api/dashboard \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| POST | /api/auth/login | Login |
| GET | /api/auth/me | Current user |
| GET | /api/dashboard | Dashboard stats |
| GET | /api/customers | List customers |
| POST | /api/customers | Create customer |
| GET | /api/meters | List meters |
| POST | /api/readings | Create reading |
| POST | /api/invoices/generate | Generate invoices |
| POST | /api/payments | Record payment |
| GET | /api/groups | List groups |
| GET | /api/groups/{id}/loss | Calculate loss |
| GET | /api/expenses | List expenses |
| GET | /api/employees | List employees |
| POST | /api/salaries/generate | Generate salary |
| GET | /api/sms/logs | SMS logs |
| GET | /api/settings | System settings |

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/          # API controllers (token auth)
│   │   └── Web/          # Web controllers (session auth)
│   └── Middleware/
│       └── RoleMiddleware.php
├── Models/               # Eloquent models
└── Services/             # Business logic services

database/
├── migrations/           # Database schema
├── seeders/              # Sample data
└── factories/            # Test factories

resources/views/
├── layouts/app.blade.php # Main layout with sidebar
├── auth/                 # Login page
├── dashboard.blade.php   # Dashboard with charts
├── customers/            # Customer CRUD views
├── meters/               # Meter views
├── readings/             # Reading views
├── invoices/             # Invoice views
├── payments/             # Payment views
├── groups/               # Group & loss views
├── expenses/             # Expense views
├── employees/            # Employee views
├── sms/                  # SMS template & log views
└── settings/             # Settings & user management

routes/
├── api.php               # API routes (Sanctum protected)
└── web.php               # Web routes (session protected)
```

## Business Logic

### Invoice Generation
- Reads meter readings for a given month
- Calculates: consumption * unit_price + fixed_fee
- Prevents duplicate invoices per customer per month
- Sends SMS notification on creation

### Payment Processing
- Updates invoice paid_amount and balance
- Auto-updates status: unpaid -> partial -> paid
- Sends SMS notification on payment

### Electricity Loss Calculation
- Central consumption = sum of central meter readings
- Customer consumption = sum of member meter readings
- Loss = central - customer consumption
- Loss % = (loss / central) * 100

### Overdue Detection
- Checks unpaid invoices past due date
- Updates status to overdue
- Sends SMS notification

## License

This project is proprietary software.
