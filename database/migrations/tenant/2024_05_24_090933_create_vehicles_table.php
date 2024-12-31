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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('store_id')->nullable()->constrained(table: 'stores', column: 'id')->cascadeOnDelete();
            $table->foreignUlid('buyer_id')->nullable()->constrained(table: 'people', column: 'id')->cascadeOnDelete();
            $table->foreignUlid('supplier_id')->nullable()->constrained(table: 'people', column: 'id')->cascadeOnDelete();
            $table->foreignUlid('vehicle_model_id')->constrained(table: 'vehicle_models', column: 'id');
            $table->date('purchase_date');
            $table->decimal('fipe_price', places: 2)->nullable()->default(null);
            $table->decimal('purchase_price', places: 2);
            $table->decimal('sale_price', places: 2);
            $table->decimal('promotional_price', places: 2)->nullable()->default(null);
            $table->string('year_one');
            $table->string('year_two');
            $table->string('full_year')->virtualAs('CONCAT(year_one, "/", year_two)');
            $table->integer('km');
            $table->string('fuel');
            $table->string('engine_power');
            $table->string('steering')->nullable();
            $table->string('transmission');
            $table->string('doors')->nullable();
            $table->string('seats')->nullable();
            $table->string('color');
            $table->string('plate');
            $table->string('chassi')->nullable();
            $table->string('renavam')->nullable();
            $table->string('crv_number')->nullable();
            $table->string('crv_code')->nullable();
            $table->date('sold_date')->nullable();
            $table->boolean('emphasis')->default(false);
            $table->string('description')->nullable();
            $table->string('annotation')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
