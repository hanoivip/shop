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
                'name' => 'Normal Testing Shop',
            ]);
        DB::table('shops')->insert(
            [
                'name' => 'Vip Testing Shop',
                'unlock' => json_encode([
                    [
                        'type' => 'VipLevel',
                        'value' => 1,
                    ]]),
            ]);
        DB::table('shops')->insert(
            [
                'name' => 'Time limited Testing Shop',
                'unlock' => json_encode([
                    [
                        'type' => 'AfterTime',
                        'value' => Carbon::parse('2023-07-01 00:00:00')->timestamp,
                    ],
                    [
                        'type' => 'BeforeTime',
                        'value' => Carbon::parse('2023-08-01 00:00:00')->timestamp,
                    ],
                ]),
            ]);
        DB::table('shops')->insert(
            [
                'name' => 'Rare Vip Testing Shop',
                'unlock' => json_encode([
                    [
                        'type' => 'AfterTime',
                        'value' => Carbon::parse('2023-07-01 00:00:00')->timestamp,
                    ],
                    [
                        'type' => 'BeforeTime',
                        'value' => Carbon::parse('2023-08-01 00:00:00')->timestamp,
                    ],
                    [
                        'type' => 'VipLevel',
                        'value' => 3,
                    ]
                ]),
            ]);
        $normalShop = Shop::where('name', 'Normal Testing Shop')->first();
        DB::table('shop_items')->insert([
            [
                'shop_id' => $normalShop->id,
                'title' => 'Diamond Package 1',
                'code' => 'CommonItem1',
                'origin_price' => 15,
                'price' => 10,
                'images' => json_encode(['/shop/diamond1.png']),
                'description' => 'Test for selling diamonds...',
                '$delivery_type' => 1,
            ],
            2 => [
                'shop_id' => $normalShop->id,
                'title' => 'Diamond Package 2',
                'code' => 'CommonItem2',
                'origin_price' => 15,
                'price' => 11,
                'images' => json_encode(['/shop/diamond2.png']),
                'description' => 'Test for selling diamonds...',
                '$delivery_type' => 1,
            ],
        ]);
        $vipShop = Shop::where('name', 'Vip Testing Shop')->first();
        DB::table('shop_items')->insert([
            [
                'shop_id' => $vipShop->id,
                'title' => 'Item Name in Shop',
                'code' => 'VipItem1',
                'origin_price' => 15,
                'price' => 10,
                'images' => json_encode(['/shop/VipItem1.png']),
                'limit' => json_encode([
                    'TotalLimit' => 10,
                    'DailyLimit' => 5
                ]),
                'description' => 'Test for selling VIP item...',
                '$delivery_type' => 1,
            ],
        ]);
    }
}
