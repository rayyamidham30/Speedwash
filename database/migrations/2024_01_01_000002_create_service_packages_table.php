<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('duration_minutes')->default(30)->comment('Estimasi waktu pengerjaan dalam menit');
            $table->json('features')->nullable()->comment('Fitur-fitur yang termasuk dalam paket');
            $table->string('color', 7)->default('#3B82F6')->comment('Warna badge paket');
            $table->string('icon')->default('bi-droplet')->comment('Bootstrap icon class');
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_packages');
    }
};
