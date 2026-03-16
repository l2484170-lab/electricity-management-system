<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('phone', 50)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('position', 255)->nullable();
            $table->string('department', 255)->nullable();
            $table->double('base_salary')->default(0.0);
            $table->date('hire_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('national_id', 50)->nullable();
            $table->text('address')->nullable();
            $table->timestamps();
        });

        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->date('date');
            $table->enum('status', ['present', 'absent', 'late', 'leave'])->default('present');
            $table->dateTime('check_in')->nullable();
            $table->dateTime('check_out')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->string('month', 7);
            $table->double('base_salary');
            $table->double('deductions')->default(0.0);
            $table->double('advances')->default(0.0);
            $table->double('bonuses')->default(0.0);
            $table->double('net_salary');
            $table->boolean('is_paid')->default(false);
            $table->dateTime('paid_date')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('advances_deductions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->enum('type', ['advance', 'deduction']);
            $table->double('amount');
            $table->text('description')->nullable();
            $table->date('date');
            $table->string('month', 7)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advances_deductions');
        Schema::dropIfExists('salaries');
        Schema::dropIfExists('attendance');
        Schema::dropIfExists('employees');
    }
};
