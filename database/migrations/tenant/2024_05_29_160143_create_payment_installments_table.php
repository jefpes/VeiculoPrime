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
        Schema::create('payment_installments', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('store_id')->nullable()->constrained(table: 'stores', column: 'id')->cascadeOnDelete();
            $table->foreignUlid('received_by')->nullable()->constrained(table: 'people', column: 'id');
            $table->foreignUlid('sale_id')->nullable()->constrained(table: 'sales', column: 'id');
            $table->date('due_date');
            $table->decimal('value', 10, 2);
            $table->string('status');
            $table->date('payment_date')->nullable();
            $table->decimal('late_fee', 10, 2)->nullable();
            $table->decimal('interest_rate', 10, 2)->nullable();
            $table->decimal('interest', 10, 2)->nullable();
            $table->decimal('payment_value', 10, 2)->nullable();
            $table->string('payment_method')->nullable();
            $table->decimal('discount', 10, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_installments');
    }
};
