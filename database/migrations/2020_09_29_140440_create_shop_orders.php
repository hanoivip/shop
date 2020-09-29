<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopOrders extends Migration
{
    public function up()
    {
        Schema::create('shop_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('serial')->comment('Order serial number..');
            $table->integer('receiver_id');
            $table->string('server');
            $table->string('role');
            $table->string('shop');
            $table->string('item');
            $table->integer('count');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('shop_orders');
    }
}
