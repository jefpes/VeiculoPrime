<?php

use App\Models\{City, Supplier};
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
            $table->string('rg', 20)->unique();
            $table->string('cpf', 20)->unique();
            $table->string('marital_status');
            $table->string('phone_one', 20);
            $table->string('phone_two', 20)->nullable();
            $table->date('birth_date');
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
            $table->string('zip_code');
            $table->string('street');
            $table->string('number');
            $table->string('neighborhood');
            $table->foreignIdFor(City::class)->constrained()->cascadeOnDelete();
            $table->string('state');
            $table->string('complement')->nullable();
            $table->timestamps();
        });

        Schema::create('supplier_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Supplier::class)->constrained()->cascadeOnDelete();
            $table->string('photo_name', 255);
            $table->string('format', 5);
            $table->string('full_path', 255);
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
