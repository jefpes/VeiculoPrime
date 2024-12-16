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
        Schema::create('people', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->nullable()->constrained(table: 'tenants', column: 'id')->cascadeOnDelete();
            $table->foreignUlid('user_id')->unique()->nullable()->constrained(table: 'users', column: 'id');
            $table->string('name');
            $table->string('gender');
            $table->string('email')->nullable();
            $table->decimal('salary', places: 2);
            $table->string('rg', 20)->nullable();
            $table->string('person_type')->nullable();
            $table->string('person_id')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('father')->nullable();
            $table->string('mother')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('spouse')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreignUlid('people_id')->nullable()->constrained(table: 'people', column: 'id')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('people_id');
        });

        Schema::dropIfExists('people');
    }
};
