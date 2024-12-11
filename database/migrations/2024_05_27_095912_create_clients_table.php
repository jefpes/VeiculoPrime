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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(App\Models\Tenant::class)->nullable()->cascadeOnDelete();
            $table->string('name');
            $table->string('gender');
            $table->string('rg', 20)->nullable();
            $table->string('client_type');
            $table->string('client_id');
            $table->string('marital_status')->nullable();
            $table->string('spouse')->nullable();
            $table->string('phone_one', 20)->nullable();
            $table->string('phone_two', 20)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('father')->nullable();
            $table->string('father_phone', 20)->nullable();
            $table->string('mother')->nullable();
            $table->string('mother_phone', 20)->nullable();
            $table->string('affiliated_one')->nullable();
            $table->string('affiliated_one_phone')->nullable();
            $table->string('affiliated_two')->nullable();
            $table->string('affiliated_two_phone')->nullable();
            $table->string('description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
