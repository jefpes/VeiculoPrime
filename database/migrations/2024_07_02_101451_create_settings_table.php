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
        Schema::create('settings', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid('tenant_id')->nullable()->constrained(table: 'tenants', column: 'id')->cascadeOnDelete();
            $table->foreignUlid('employee_id')->nullable()->constrained(table: 'people', column: 'id');
            $table->string('name')->nullable();
            $table->string('cnpj')->nullable();
            $table->date('opened_in')->nullable();
            $table->longText('about')->nullable();
            $table->string('email')->nullable();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('x')->nullable();
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('youtube')->nullable();
            $table->string('whatsapp')->nullable();
            $table->decimal('interest_rate_sale', 10, 2)->nullable();
            $table->decimal('interest_rate_installment', 10, 2)->nullable();
            $table->decimal('late_fee', 10, 2)->nullable();

            //Styles frontend
            $table->string('font_family')->nullable();

            $table->string('primary_color')->default('#000000')->nullable();
            $table->string('secondary_color')->default('#10b981')->nullable();
            $table->string('tertiary_color')->default('#dc2626')->nullable();

            $table->string('nav_color')->nullable()->default('#ffffff');
            $table->string('nav_border_color')->nullable()->default('#d1d5db');

            $table->string('footer_color')->nullable()->default('#f3f4f6');

            $table->string('body_bg_color')->nullable()->default('#f3f4f6');

            $table->string('text_variant_color_1')->nullable()->default('#f3f4f6');
            $table->string('text_variant_color_2')->nullable()->default('#e5e7eb');
            $table->string('text_variant_color_3')->nullable()->default('#d1d5db');
            $table->string('text_variant_color_4')->nullable()->default('#9ca3af');
            $table->string('text_variant_color_5')->nullable()->default('#6b7280');
            $table->string('text_variant_color_6')->nullable()->default('#4b5563');
            $table->string('text_variant_color_7')->nullable()->default('#374151');
            $table->string('text_variant_color_8')->nullable()->default('#1f2937');
            $table->string('text_variant_color_9')->nullable()->default('#111827');
            $table->string('text_variant_color_10')->nullable()->default('#030712');

            $table->string('card_color')->nullable()->default('#f3f4f6');

            $table->string('variant_color_1')->nullable()->default('#fafafa');
            $table->string('variant_color_2')->nullable()->default('#f5f5f5');
            $table->string('variant_color_3')->nullable()->default('#e5e7eb');
            $table->string('variant_color_4')->nullable()->default('#d4d4d4');
            $table->string('variant_color_5')->nullable()->default('#a3a3a3');
            $table->string('variant_color_6')->nullable()->default('#737373');
            $table->string('variant_color_7')->nullable()->default('#525252');
            $table->string('variant_color_8')->nullable()->default('#404040');
            $table->string('variant_color_9')->nullable()->default('#262626');
            $table->string('variant_color_10')->nullable()->default('#171717');
            $table->string('variant_color_11')->nullable()->default('#0a0a0a');

            $table->string('bg_img')->nullable();
            $table->string('bg_img_opacity')->nullable()->default('0.3');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company');
    }
};
