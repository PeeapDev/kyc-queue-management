<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('queues', function (Blueprint $table) {
            $table->id();
            $table->string('token')->unique();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('counter_id')->nullable()->constrained();
            $table->string('service_type');
            $table->enum('status', ['waiting', 'serving', 'completed', 'cancelled'])->default('waiting');
            $table->integer('wait_time')->nullable(); // in minutes
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('queues');
    }
};
