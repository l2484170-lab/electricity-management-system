<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sms_templates', function (Blueprint $table) {
            $table->id();
            $table->enum('event_type', ['invoice_created', 'payment_received', 'payment_overdue'])->unique();
            $table->text('template_text');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('sms_logs', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number', 50);
            $table->text('message');
            $table->string('event_type', 50)->nullable();
            $table->string('status', 20)->default('sent');
            $table->string('customer_name', 255)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sms_logs');
        Schema::dropIfExists('sms_templates');
    }
};
