<?php

use App\Models\{Employee};
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('company', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Employee::class, 'employee_id')->nullable();
            $table->string('name')->nullable();
            $table->string('cnpj')->nullable();
            $table->date('opened_in')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('street')->nullable();
            $table->string('number')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('complement')->nullable();
            $table->string('about')->nullable();
            $table->string('phone')->nullable();
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

            $table->string('font_family')->nullable();
            $table->string('font_color')->nullable()->default('black');
            $table->string('body_bg_color')->nullable()->default('white');
            $table->string('card_color')->nullable()->default('white');
            $table->string('card_text_color')->nullable();
            $table->string('nav_color')->nullable()->default('#dedede');
            $table->string('footer_color')->nullable()->default('#dedede');
            $table->string('link_color')->nullable()->default('green');
            $table->string('link_text_color')->nullable()->default('white');
            $table->string('btn_1_color')->nullable()->default('blue');
            $table->string('btn_1_text_color')->nullable()->default('white');
            $table->string('btn_2_color')->nullable()->default('red');
            $table->string('btn_2_text_color')->nullable()->default('white');
            $table->string('btn_3_color')->nullable()->default('green');
            $table->string('btn_3_text_color')->nullable()->default('white');

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
