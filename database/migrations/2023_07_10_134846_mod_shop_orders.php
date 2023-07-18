<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModShopOrders extends Migration
{
    public function up()
    {
        Schema::table('shop_orders', function (Blueprint $table) {
            $table->dropColumn('receiver_id');
            $table->dropColumn('server');
            $table->dropColumn('role');
            $table->dropColumn('shop');
            $table->dropColumn('item');
            $table->dropColumn('count');
            $table->dropColumn('status');
            $table->dropColumn('send_status');
            $table->integer('user_id');
            $table->text('cart')->nullable()->comment('cart detail');
            $table->integer('payment_status');
            $table->integer('delivery_status');
            $table->string('delivery_reason')->nullable();
            $table->string('currency');
        });
    }

    public function down()
    {
        Schema::table('shop_orders', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->dropColumn('cart');
            $table->dropColumn('payment_status');
            $table->dropColumn('delivery_status');
            $table->dropColumn('delivery_reason');
            $table->dropColumn('currency');
            $table->integer('receiver_id');
            $table->string('server');
            $table->string('role');
            $table->string('shop');
            $table->string('item');
            $table->integer('count');
            $table->integer('status');
            $table->integer('send_status');
        });
    }
}
