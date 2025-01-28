<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('vehicle_expenses', function (Blueprint $table) {
            $table->foreignUlid('vehicle_expense_category_id')->nullable()->constrained();
        });
    }

    public function down(): void
    {
        Schema::table('vehicle_expenses', function (Blueprint $table) {
            $table->dropColumn('vehicle_expense_category_id');
        });
    }
};
