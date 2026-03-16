<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number', 50)->unique();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('reading_id')->nullable()->constrained('meter_readings')->nullOnDelete();
            $table->string('month', 7);
            $table->double('consumption')->default(0.0);
            $table->double('unit_price');
            $table->double('consumption_amount')->default(0.0);
            $table->double('fixed_fee')->default(0.0);
            $table->double('total_amount');
            $table->double('paid_amount')->default(0.0);
            $table->double('balance')->default(0.0);
            $table->enum('status', ['paid', 'unpaid', 'overdue', 'partial'])->default('unpaid');
            $table->dateTime('due_date')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
