<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShops extends Migration
{
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment('Just display name');
            $table->string('unlock')->default('[]')->comment('Unlock conditions. Array of condition: time, vip, ..');
            $table->integer('start_time')->default(0);
            $table->integer('end_time')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shops');
    }
}
