<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModShopItems extends Migration
{
    public function up()
    {
        Schema::table('shop_items', function (Blueprint $table) {
            $table->dropColumn('price_type');
            $table->dropColumn('image');
            $table->text('images')->nullable();
            $table->string('limit')->text()->nullable()->change();
            $table->string('currency')->default('VND');
            $table->integer('delivery_type')->default(0)->comment('Delivery type: game role items, game role currency, game account..');
            $table->text('description')->nullable();
        });
    }

    public function down()
    {
        Schema::table('shop_items', function (Blueprint $table) {
            $table->dropColumn('images');
            $table->dropColumn('currency');
            $table->dropColumn('delivery_type');
            $table->dropColumn('description');
            $table->text('limit')->string()->nullable()->change();
            $table->integer('price_type');
            $table->string('image');
        });
    }
}
