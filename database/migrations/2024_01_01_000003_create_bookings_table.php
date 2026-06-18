<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('brand');
            $table->string('model');
            $table->string('license_plate')->unique();
            $table->string('color')->nullable();
            $table->year('year')->nullable();
            $table->enum('type', ['matic', 'manual', 'sport', 'listrik'])->default('matic');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            $table->foreignId('service_package_id')->constrained()->onDelete('cascade');
            $table->dateTime('scheduled_at');
            $table->enum('status', [
                'pending',
                'confirmed',
                'in_queue',
                'in_progress',
                'completed',
                'cancelled'
            ])->default('pending');
            $table->integer('queue_number')->nullable();
            $table->text('notes')->nullable();
            $table->decimal('total_price', 10, 2);
            $table->enum('payment_status', ['unpaid', 'paid', 'refunded'])->default('unpaid');
            $table->enum('payment_method', ['cash', 'transfer', 'qris'])->nullable();
            $table->dateTime('started_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->string('rating')->nullable();
            $table->text('review')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('vehicles');
    }
};
