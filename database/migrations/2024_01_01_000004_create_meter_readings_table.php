<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('meter_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('meter_id')->constrained('meters')->cascadeOnDelete();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->double('reading_value');
            $table->double('previous_reading')->default(0.0);
            $table->double('consumption')->default(0.0);
            $table->dateTime('reading_date');
            $table->string('month', 7); // YYYY-MM
            $table->boolean('is_opening')->default(false);
            $table->string('notes', 500)->nullable();
            $table->foreignId('read_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('meter_readings');
    }
};
