<?php

use App\Models\{Supplier};
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('gender');
            $table->string('rg', 20)->unique()->nullable();
            $table->string('supplier_type');
            $table->string('supplier_id')->unique();
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

        Schema::create('supplier_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Supplier::class)->constrained()->cascadeOnDelete();
            $table->string('zip_code')->nullable();
            $table->string('street')->nullable();
            $table->string('number')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('complement')->nullable();
            $table->timestamps();
        });

        Schema::create('supplier_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Supplier::class)->constrained()->cascadeOnDelete();
            $table->string('path', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
