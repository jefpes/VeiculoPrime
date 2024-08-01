<?php

use App\Models\{Sale, User};
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
            $table->id();

            $table->foreignIdFor(User::class)->nullable()->constrained();
            $table->foreignIdFor(Sale::class)->constrained();
            $table->date('due_date');
            $table->decimal('value', 10, 2);
            $table->string('status');
            $table->date('payment_date')->nullable();
            $table->decimal('payment_value', 10, 2)->nullable();
            $table->string('payment_method')->nullable();
            $table->decimal('discount', 10, 2)->nullable();
            $table->decimal('surcharge', 10, 2)->nullable();

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
