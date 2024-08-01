<?php

use App\Models\{Brand, Supplier, Vehicle, VehicleModel, VehicleType};
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('vehicle_types', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('vehicle_models', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(VehicleType::class)->constrained(table: 'vehicle_types', column: 'id');
            $table->foreignIdFor(Brand::class)->constrained();
            $table->string('name')->unique();
            $table->timestamps();
        });

        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->date('purchase_date');
            $table->decimal('fipe_price', places: 2)->nullable()->default(null);
            $table->decimal('purchase_price', places: 2);
            $table->decimal('sale_price', places: 2);
            $table->decimal('promotional_price', places: 2)->nullable()->default(null);
            $table->foreignIdFor(VehicleModel::class)->constrained(table: 'vehicle_models', column: 'id');
            $table->foreignIdFor(Supplier::class)->nullable()->constrained(table: 'suppliers', column: 'id');
            $table->year('year_one');
            $table->year('year_two');
            $table->integer('km');
            $table->string('fuel');
            $table->string('engine_power');
            $table->string('steering')->nullable();
            $table->string('transmission');
            $table->string('doors')->nullable();
            $table->string('seats')->nullable();
            $table->string('traction')->nullable();
            $table->string('color');
            $table->string('plate')->unique();
            $table->string('chassi')->unique();
            $table->string('renavam')->unique();
            $table->date('sold_date')->nullable();
            $table->string('description')->nullable();
            $table->string('annotation')->nullable();

            $table->timestamps();
        });

        Schema::create('vehicle_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Vehicle::class)->constrained()->cascadeOnDelete();
            $table->string('photo_name', 255);
            $table->string('format', 5);
            $table->string('full_path', 255);
            $table->string('path', 255);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_photos');
        Schema::dropIfExists('vehicles');
        Schema::dropIfExists('vehicle_models');
        Schema::dropIfExists('vehicle_types');
        Schema::dropIfExists('brands');
    }
};
