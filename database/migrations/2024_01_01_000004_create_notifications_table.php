<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        Schema::create('queue_monitors', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->integer('current_serving')->default(0)->comment('Nomor antrian yang sedang dilayani');
            $table->integer('total_queue')->default(0)->comment('Total antrian hari ini');
            $table->integer('available_slots')->default(20)->comment('Slot tersedia per hari');
            $table->boolean('is_open')->default(true);
            $table->time('open_time')->default('08:00:00');
            $table->time('close_time')->default('20:00:00');
            $table->timestamps();
        });

        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_code')->unique();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['cash', 'transfer', 'qris'])->default('cash');
            $table->enum('status', ['pending', 'success', 'failed', 'refunded'])->default('pending');
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
        Schema::dropIfExists('queue_monitors');
        Schema::dropIfExists('notifications');
    }
};
