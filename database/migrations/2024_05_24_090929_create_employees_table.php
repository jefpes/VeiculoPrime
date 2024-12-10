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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(App\Models\Tenant::class)->nullable()->cascadeOnDelete();
            $table->string('name');
            $table->string('gender');
            $table->string('email')->nullable();
            $table->string('phone_one')->nullable();
            $table->string('phone_two')->nullable();
            $table->decimal('salary', places: 2);
            $table->string('rg', 20)->nullable();
            $table->string('cpf', 20)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('father')->nullable();
            $table->string('mother')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('spouse')->nullable();
            $table->date('admission_date');
            $table->date('resignation_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
