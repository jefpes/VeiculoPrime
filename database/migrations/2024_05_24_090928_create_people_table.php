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
            $table->foreignUlid('user_id')->unique()->nullable()->constrained(table: 'users', column: 'id');
            $table->string('name');
            $table->string('sex');
            $table->string('person_type')->nullable();
            $table->string('person_id')->nullable();
            $table->string('rg', 20)->nullable();
            $table->string('email')->nullable();
            $table->date('birthday')->nullable();
            $table->string('father')->nullable();
            $table->string('mother')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('spouse')->nullable();
            $table->boolean('client')->default(false);
            $table->boolean('supplier')->default(false);
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
