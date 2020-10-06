<?php

use Carbon\Carbon;
use Hanoivip\Shop\Models\Shop;
use Hanoivip\Shop\Models\ShopItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FakeShop extends Seeder
{
    public function run()
    {
        ShopItem::query()->truncate();
        Shop::query()->truncate();
        DB::table('shops')->insert(
            [
                'name' => 'Normal Web Shop',
            ]);
        DB::table('shops')->insert(
            [
                'name' => 'Vip Shop',
                'unlock' => json_encode([
                    [
                        'type' => 'VipLevel',
                        'value' => 1,
                    ]]),
            ]);
        DB::table('shops')->insert(
            [
                'name' => 'Rare Shop',
                'unlock' => json_encode([
                    [
                        'type' => 'AfterTime',
                        'value' => 9999999,
                    ],
                    [
                        'type' => 'BeforeTime',
                        'value' => 0,
                    ],
                ]),
                'start_time' => Carbon::parse('2020-10-01 00:00:00')->timestamp,
                'end_time' => Carbon::parse('2020-12-18 00:00:00')->timestamp,
            ]);
        DB::table('shops')->insert(
            [
                'name' => 'Rare Shop Vip',
                'unlock' => json_encode([
                    [
                        'type' => 'AfterTime',
                        'value' => 9999999,
                    ],
                    [
                        'type' => 'BeforeTime',
                        'value' => 0,
                    ],
                    [
                        'type' => 'VipLevel',
                        'value' => 3,
                    ]
                ]),
            ]);
        $normalShop = Shop::where('name', 'Normal Web Shop')->first();
        DB::table('shop_items')->insert([
            [
                'shop_id' => $normalShop->id,
                'title' => 'Item Name in Shop',
                'code' => 'CommonItem1',
                'origin_price' => 15,
                'price' => 10,
                'price_type' => 0,
                'image' => '/shop/CommonItem1.png',
            ],
            2 => [
                'shop_id' => $normalShop->id,
                'title' => 'Item Name 2 in Shop',
                'code' => 'CommonItem2',
                'origin_price' => 15,
                'price' => 11,
                'price_type' => 0,
                'image' => '/shop/CommonItem2.png',
            ],
        ]);
        $vipShop = Shop::where('name', 'Vip Shop')->first();
        DB::table('shop_items')->insert([
            [
                'shop_id' => $vipShop->id,
                'title' => 'Item Name in Shop',
                'code' => 'VipItem1',
                'origin_price' => 15,
                'price' => 10,
                'price_type' => 0,
                'image' => '/shop/VipItem1.png',
                'limit' => json_encode([
                    'TotalLimit' => 10,
                    'DailyLimit' => 5
                ])
            ],
        ]);
    }
}
