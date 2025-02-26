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
        Schema::create('vehicle_expenses', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->nullable()->constrained(table: 'tenants', column: 'id')->cascadeOnDelete();
            $table->foreignUlid('store_id')->nullable()->constrained(table: 'stores', column: 'id')->cascadeOnDelete();
            $table->foreignUlid('vehicle_id')->nullable()->constrained(table: 'vehicles', column: 'id')->cascadeOnDelete();
            $table->foreignUlid('vehicle_expense_category_id')->nullable()->constrained();
            $table->foreignUlid('user_id')->nullable()->constrained(table: 'users', column: 'id');
            $table->date('date');
            $table->string('description');
            $table->decimal('value', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicle_expenses');
    }
};
