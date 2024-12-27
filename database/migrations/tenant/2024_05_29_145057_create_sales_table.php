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
        Schema::create('sales', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('store_id')->nullable()->constrained(table: 'stores', column: 'id')->cascadeOnDelete();
            $table->foreignUlid('seller_id')->nullable()->constrained(table: 'people', column: 'id');
            $table->foreignUlid('vehicle_id')->nullable()->constrained(table: 'vehicles', column: 'id');
            $table->foreignUlid('client_id')->nullable()->constrained(table: 'people', column: 'id');
            $table->string('payment_method');
            $table->string('status');
            $table->date('date_sale');
            $table->date('date_payment')->nullable();
            $table->decimal('interest_rate', 10, 2)->nullable();
            $table->decimal('discount', 10, 2)->nullable();
            $table->decimal('interest', 10, 2)->nullable();
            $table->decimal('down_payment', 10, 2)->nullable();
            $table->integer('number_installments')->nullable();
            $table->decimal('reimbursement', 10, 2)->nullable();
            $table->date('date_cancel')->nullable();
            $table->decimal('total', 10, 2);
            $table->decimal('total_with_interest', 10, 2)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
