<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->string('primary_color')
                ->default('#000000')
                ->nullable();

            $table->string('secondary_color')
                ->default('#10b981')
                ->nullable();

            $table->string('tertiary_color')
                ->default('#dc2626')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn('color_primary');
            $table->dropColumn('color_secondary');
            $table->dropColumn('color_tertiary');
        });
    }
};
