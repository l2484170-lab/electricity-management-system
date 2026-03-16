<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\CustomerController;
use App\Http\Controllers\Web\MeterController;
use App\Http\Controllers\Web\ReadingController;
use App\Http\Controllers\Web\InvoiceController;
use App\Http\Controllers\Web\PaymentController;
use App\Http\Controllers\Web\GroupController;
use App\Http\Controllers\Web\ExpenseController;
use App\Http\Controllers\Web\EmployeeController;
use App\Http\Controllers\Web\SmsController;
use App\Http\Controllers\Web\SettingController;

// Auth
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/', function () { return redirect('/dashboard'); });

// Protected routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Customers
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::post('/customers/{customer}/archive', [CustomerController::class, 'archive'])->name('customers.archive');

    // Meters
    Route::get('/meters', [MeterController::class, 'index'])->name('meters.index');
    Route::get('/meters/create', [MeterController::class, 'create'])->name('meters.create');
    Route::post('/meters', [MeterController::class, 'store'])->name('meters.store');
    Route::get('/meters/{meter}/edit', [MeterController::class, 'edit'])->name('meters.edit');
    Route::put('/meters/{meter}', [MeterController::class, 'update'])->name('meters.update');

    // Readings
    Route::get('/readings', [ReadingController::class, 'index'])->name('readings.index');
    Route::get('/readings/create', [ReadingController::class, 'create'])->name('readings.create');
    Route::post('/readings', [ReadingController::class, 'store'])->name('readings.store');

    // Invoices
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::post('/invoices/generate', [InvoiceController::class, 'generate'])->name('invoices.generate');
    Route::get('/invoices/manual-create', [InvoiceController::class, 'manualCreate'])->name('invoices.manualCreate');
    Route::post('/invoices/manual', [InvoiceController::class, 'manualStore'])->name('invoices.manualStore');
    Route::get('/invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    Route::post('/invoices/check-overdue', [InvoiceController::class, 'checkOverdue'])->name('invoices.checkOverdue');

    // Payments
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/payments', [PaymentController::class, 'store'])->name('payments.store');

    // Groups
    Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
    Route::get('/groups/create', [GroupController::class, 'create'])->name('groups.create');
    Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
    Route::get('/groups/{group}', [GroupController::class, 'show'])->name('groups.show');
    Route::post('/groups/{group}/members', [GroupController::class, 'addMember'])->name('groups.addMember');
    Route::delete('/groups/{group}/members/{customerId}', [GroupController::class, 'removeMember'])->name('groups.removeMember');
    Route::get('/groups/{group}/loss', [GroupController::class, 'calculateLoss'])->name('groups.loss');

    // Expenses
    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::post('/expense-categories', [ExpenseController::class, 'createCategory'])->name('expenses.createCategory');

    // Employees
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('/employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('/employees/{employee}', [EmployeeController::class, 'show'])->name('employees.show');
    Route::get('/employees/{employee}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::put('/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');

    // SMS
    Route::get('/sms', [SmsController::class, 'index'])->name('sms.index');
    Route::put('/sms/templates/{template}', [SmsController::class, 'updateTemplate'])->name('sms.updateTemplate');
    Route::post('/sms/send', [SmsController::class, 'send'])->name('sms.send');

    // Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    Route::post('/settings/users', [SettingController::class, 'createUser'])->name('settings.createUser');
});
