<?php

/**
 * Liệt kê các đồ trong các nhóm,
 */

// Configure Items in each Shop
// limit => DailyLimit, TotalLimit
return [
    'common' => [
        1 => [
            'id' => 1,
            'title' => 'Item Name in Shop',
            'code' => 'CommonItem1',
            'price' => 10,
            'price_type' => 'xu',
            'image' => '/shop/CommonItem1.png',
            'limit' => [
            ]
        ],
    ],
    'daily' => [],
    'shopvip1' => [
        1 => [
            'id' => 1,
            'title' => 'Item Name in Shop',
            'code' => 'VipItem1',
            'price' => 10,
            'price_type' => 'xu',
            'image' => '/shop/VipItem1.png',
            'limit' => [
                'TotalLimit' => 10,
                'DailyLimit' => 5
            ]
        ],
    ],
    'shopvip2' => [],
    'shopvip3' => [],
    'rare' => [],
    'rarevip' => [
        1 => [
            'id' => 1,
            'title' => 'Item Name in Shop',
            'code' => 'RareItem1',
            'price' => 10,
            'price_type' => 'xu',
            'daily_limit' => 1,
            'image' => '/shop/RareItem1.png',
            'limit' => [
                'TotalLimit' => 10,
            ]
        ],
    ],
];