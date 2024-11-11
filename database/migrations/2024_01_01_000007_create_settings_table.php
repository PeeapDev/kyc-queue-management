<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Insert default settings
        DB::table('settings')->insert([
            [
                'key' => 'welcome_sms_template',
                'value' => 'Welcome {name}! Thank you for registering with us. Your account has been created successfully.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'welcome_email_template',
                'value' => 'Welcome {name}! Thank you for registering with us. Your account has been created successfully.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'queue_sms_template',
                'value' => 'Hello {name}, your queue number is {number}. Estimated wait time: {wait_time} minutes.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'queue_email_template',
                'value' => 'Hello {name}, your queue number is {number}. Estimated wait time: {wait_time} minutes.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
