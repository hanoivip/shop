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
                'slug' => 'normal-shop',
            ]);
        DB::table('shops')->insert(
            [
                'name' => 'Account Testing Shop',
                'slug' => 'acc-shop',
            ]);
        DB::table('shops')->insert(
            [
                'name' => 'Vip Testing Shop',
                'slug' => 'vip-shop',
                'unlock' => json_encode([
                    [
                        'type' => 'VipLevel',
                        'value' => 4,
                    ]]),
            ]);
        DB::table('shops')->insert(
            [
                'name' => 'Time limited Testing Shop',
                'slug' => 'time-shop',
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
                'slug' => 'time-vip-shop',
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
                'code' => 'com.wyd.gunpow.item200',
                'origin_price' => 30000,
                'price' => 20000,
                'images' => json_encode([
                    '/images/shop/combo1.png',
                    '/images/shop/payment_1.png',
                    '/images/shop/payment_2.png',
                ]),
                'description' => 'Test for selling diamonds...',
                'delivery_type' => 1,
            ],
            2 => [
                'shop_id' => $normalShop->id,
                'title' => 'Diamond Package 2',
                'code' => 'com.wyd.gunpow.item500',
                'origin_price' => 100000,
                'price' => 50000,
                'images' => json_encode([
                    '/images/shop/combo1.png',
                    '/images/shop/payment_3.png',
                    '/images/shop/payment_4.png',
                ]),
                'description' => 'Test for selling diamonds...',
                'delivery_type' => 1,
            ],
        ]);
        $vipShop = Shop::where('name', 'Vip Testing Shop')->first();
        DB::table('shop_items')->insert([
            [
                'shop_id' => $vipShop->id,
                'title' => 'Item Name in Shop',
                'code' => 'VipItem1',
                'origin_price' => 40000,
                'price' => 25000,
                'images' => json_encode(['/images/shop/payment_5.png']),
                'limit' => json_encode([
                    'TotalLimit' => 10,
                    'DailyLimit' => 5
                ]),
                'description' => 'Test for selling VIP item...',
                'delivery_type' => 1,
            ],
        ]);
        $accShop = Shop::where('name', 'Account Testing Shop')->first();
        DB::table('shop_items')->insert([
            [
                'shop_id' => $accShop->id,
                'title' => 'Account Name 1',
                'code' => '23883',//account id here
                'origin_price' => 300000,
                'price' => 200000,
                'images' => json_encode([
                    '/images/shop/combo1.png',
                    '/images/shop/payment_1.png',
                    '/images/shop/payment_2.png',
                ]),
                'description' => 'LC: 10000, Star: 2, Vu khi: abcdef...',
                'delivery_type' => 1,
                'meta' => json_encode([
                    'LC' => 10000,
                    'Star' => 2,
                ]),
            ],
            2 => [
                
                'shop_id' => $accShop->id,
                'title' => 'Account Name 2',
                'code' => '23883',//account id here
                'origin_price' => 400000,
                'price' => 300000,
                'images' => json_encode([
                    '/images/shop/combo1.png',
                    '/images/shop/payment_1.png',
                    '/images/shop/payment_2.png',
                ]),
                'description' => 'LC: 20000, Star: 3, Vu khi: defgh...',
                'delivery_type' => 1,
                'meta' => json_encode([
                    'LC' => 20000,
                    'Star' => 3,
                ]),
            ],
        ]);
    }
}
