<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\MeterController;
use App\Http\Controllers\Api\ReadingController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\ExpenseController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\SmsController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\DashboardController;

// Public
Route::post('/auth/login', [AuthController::class, 'login']);

// Protected
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::get('/auth/me', [AuthController::class, 'me']);
    Route::post('/auth/register', [AuthController::class, 'register']);
    Route::get('/users', [AuthController::class, 'listUsers']);
    Route::put('/users/{userId}', [AuthController::class, 'updateUser']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Customers
    Route::get('/customers', [CustomerController::class, 'index']);
    Route::get('/customers/count', [CustomerController::class, 'count']);
    Route::post('/customers', [CustomerController::class, 'store']);
    Route::get('/customers/{id}', [CustomerController::class, 'show']);
    Route::put('/customers/{id}', [CustomerController::class, 'update']);
    Route::post('/customers/{id}/archive', [CustomerController::class, 'archive']);
    Route::get('/customers/{id}/activity', [CustomerController::class, 'activity']);

    // Meters
    Route::get('/meters', [MeterController::class, 'index']);
    Route::post('/meters', [MeterController::class, 'store']);
    Route::get('/meters/{id}', [MeterController::class, 'show']);
    Route::put('/meters/{id}', [MeterController::class, 'update']);

    // Readings
    Route::get('/readings', [ReadingController::class, 'index']);
    Route::post('/readings', [ReadingController::class, 'store']);
    Route::post('/readings/import', [ReadingController::class, 'import']);

    // Invoices
    Route::get('/invoices', [InvoiceController::class, 'index']);
    Route::post('/invoices/generate', [InvoiceController::class, 'generate']);
    Route::post('/invoices/manual', [InvoiceController::class, 'manualCreate']);
    Route::get('/invoices/{id}', [InvoiceController::class, 'show']);
    Route::put('/invoices/{id}', [InvoiceController::class, 'update']);
    Route::post('/invoices/check-overdue', [InvoiceController::class, 'checkOverdue']);

    // Payments
    Route::get('/payments', [PaymentController::class, 'index']);
    Route::post('/payments', [PaymentController::class, 'store']);
    Route::get('/payments/{id}', [PaymentController::class, 'show']);

    // Groups
    Route::get('/groups', [GroupController::class, 'index']);
    Route::post('/groups', [GroupController::class, 'store']);
    Route::get('/groups/{id}', [GroupController::class, 'show']);
    Route::put('/groups/{id}', [GroupController::class, 'update']);
    Route::post('/groups/{groupId}/members', [GroupController::class, 'addMember']);
    Route::get('/groups/{groupId}/members', [GroupController::class, 'listMembers']);
    Route::delete('/groups/{groupId}/members/{customerId}', [GroupController::class, 'removeMember']);
    Route::get('/groups/{groupId}/loss', [GroupController::class, 'calculateLoss']);

    // Central Meters
    Route::get('/central-meters', [GroupController::class, 'listCentralMeters']);
    Route::post('/central-meters', [GroupController::class, 'createCentralMeter']);
    Route::post('/central-meter-readings', [GroupController::class, 'createCentralMeterReading']);

    // Expenses
    Route::get('/expense-categories', [ExpenseController::class, 'listCategories']);
    Route::post('/expense-categories', [ExpenseController::class, 'createCategory']);
    Route::get('/expenses', [ExpenseController::class, 'index']);
    Route::post('/expenses', [ExpenseController::class, 'store']);
    Route::get('/expenses/{id}', [ExpenseController::class, 'show']);

    // Employees
    Route::get('/employees', [EmployeeController::class, 'index']);
    Route::post('/employees', [EmployeeController::class, 'store']);
    Route::get('/employees/{id}', [EmployeeController::class, 'show']);
    Route::put('/employees/{id}', [EmployeeController::class, 'update']);

    // Attendance
    Route::post('/attendance', [EmployeeController::class, 'recordAttendance']);
    Route::get('/attendance', [EmployeeController::class, 'listAttendance']);

    // Salary
    Route::post('/salaries/generate', [EmployeeController::class, 'generateSalary']);
    Route::get('/salaries', [EmployeeController::class, 'listSalaries']);
    Route::post('/salaries/{salaryId}/pay', [EmployeeController::class, 'paySalary']);

    // Advances & Deductions
    Route::post('/advances-deductions', [EmployeeController::class, 'createAdvanceDeduction']);
    Route::get('/advances-deductions', [EmployeeController::class, 'listAdvancesDeductions']);

    // SMS
    Route::get('/sms/templates', [SmsController::class, 'listTemplates']);
    Route::post('/sms/templates', [SmsController::class, 'createTemplate']);
    Route::put('/sms/templates/{id}', [SmsController::class, 'updateTemplate']);
    Route::post('/sms/send', [SmsController::class, 'send']);
    Route::get('/sms/logs', [SmsController::class, 'logs']);

    // Settings
    Route::get('/settings', [SettingController::class, 'index']);
    Route::post('/settings', [SettingController::class, 'store']);
    Route::put('/settings/{key}', [SettingController::class, 'update']);
    Route::get('/settings/{key}', [SettingController::class, 'show']);
});
