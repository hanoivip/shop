<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopItems extends Migration
{
    public function up()
    {
        Schema::create('shop_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shop_id'); 
            $table->string('title');
            $table->string('code');
            $table->integer('origin_price');
            $table->integer('price');
            $table->integer('price_type');
            //$table->integer('count')->default(1);
            $table->string('limit')->default('[]')->comment('Array of limit object');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('shop_items');
    }
}
