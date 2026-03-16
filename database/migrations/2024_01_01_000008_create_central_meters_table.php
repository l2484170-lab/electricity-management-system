<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('central_meters', function (Blueprint $table) {
            $table->id();
            $table->string('meter_number', 100)->unique();
            $table->foreignId('group_id')->constrained('groups')->cascadeOnDelete();
            $table->string('location', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('central_meter_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('central_meter_id')->constrained('central_meters')->cascadeOnDelete();
            $table->double('reading_value');
            $table->double('previous_reading')->default(0.0);
            $table->double('consumption')->default(0.0);
            $table->string('month', 7);
            $table->dateTime('reading_date');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('central_meter_readings');
        Schema::dropIfExists('central_meters');
    }
};
