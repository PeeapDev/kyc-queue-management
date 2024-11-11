<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_other')->nullable();
            $table->text('description')->nullable();
            $table->string('acronym');
            $table->string('level')->default('Level 1');
            $table->boolean('display_on_transfer')->default(true);
            $table->boolean('display_on_ticket')->default(true);
            $table->boolean('display_on_backend')->default(true);
            $table->integer('priority')->default(1);
            $table->boolean('is_active')->default(true);
            $table->string('image_path')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
