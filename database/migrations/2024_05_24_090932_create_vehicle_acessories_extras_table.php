<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('accessories', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('accessory_vehicle', function (Blueprint $table) {
            $table->foreignUlid('vehicle_id')->constrained(table: 'vehicles');
            $table->foreignUlid('accessory_id')->constrained(table: 'accessories');
        });

        Schema::create('extras', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('extra_vehicle', function (Blueprint $table) {
            $table->foreignUlid('vehicle_id')->constrained(table: 'vehicles');
            $table->foreignUlid('extra_id')->constrained(table: 'extras');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accessory_vehicle');
        Schema::dropIfExists('extra_vehicle');
        Schema::dropIfExists('vehicle_extras');
        Schema::dropIfExists('vehicle_acessories');
    }
};
