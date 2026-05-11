<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fare_rates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('base_fare', 10, 2)->default(40.00);
            $table->decimal('per_km_rate', 10, 2)->default(10.00);
            $table->decimal('per_minute_rate', 10, 2)->default(2.00);
            $table->decimal('booking_fee', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fare_rates');
    }
};