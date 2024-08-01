<?php

use App\Models\{Client, User, Vehicle};
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
            $table->id();
            $table->foreignIdFor(User::class)->constrained();
            $table->foreignIdFor(Vehicle::class)->constrained();
            $table->foreignIdFor(Client::class)->constrained();
            $table->string('payment_method');
            $table->string('status');
            $table->date('date_sale');
            $table->date('date_payment')->nullable();
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('surcharge', 10, 2)->default(0);
            $table->decimal('down_payment', 10, 2)->default(0);
            $table->integer('number_installments')->default(1);
            $table->decimal('reimbursement', 10, 2)->nullable();
            $table->date('date_cancel')->nullable();
            $table->decimal('total', 10, 2);

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
