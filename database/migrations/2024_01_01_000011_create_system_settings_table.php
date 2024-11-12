<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::dropIfExists('system_settings');

        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('google_maps_api_key')->nullable();
            $table->string('recaptcha_site_key')->nullable();
            $table->string('recaptcha_secret_key')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_logo')->nullable();
            $table->string('company_favicon')->nullable();
            $table->string('hero_image')->nullable();
            $table->string('default_country')->nullable();
            $table->text('welcome_message')->nullable();
            $table->text('queue_message')->nullable();
            $table->text('kyc_instructions')->nullable();
            $table->text('chatbot_script')->nullable();
            $table->string('google_client_id')->nullable();
            $table->string('google_client_secret')->nullable();
            $table->string('site_title')->nullable();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('hero_title')->nullable();
            $table->string('hero_subtitle')->nullable();
            $table->string('primary_color')->nullable();
            $table->string('secondary_color')->nullable();
            $table->json('features')->nullable();
            $table->string('cta_text')->nullable();
            $table->string('cta_button_text')->nullable();
            $table->string('footer_text')->nullable();
            $table->text('custom_css')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('system_settings');
    }
};
