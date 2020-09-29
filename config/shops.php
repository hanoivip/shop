<?php

/**
 * Module Shop
 * 
 * Hỗ trợ việc mua bán vật phẩm trong trường hợp
 * + Không thể/khó chỉnh sửa shop trong game
 * + Tìm cách kích cầu việc nạp thẻ từ web
 * + Tìm cách bán combo từ web
 * + Có thể sử dụng để free theo ngày (reset)
 * 
 * 
 */

// Configure Shop Detail
return [
    1 => [
        'id' => 1,
        'name' => 'Normal Web Shop',
        'unlock' => [],
        'items' => 'common'
    ],
    2 => [
        'id' => 2,
        'name' => 'Vip Shop',
        'unlock' => [
            [
                'type' => 'VipLevel',
                'value' => 1,
            ]
        ],
        'items' => 'shopvip1'
    ],
    3 => [
        'id' => 3,
        'name' => 'Rare Shop',
        'unlock' => [
            [
                'type' => 'AfterTime',
                'value' => 9999999,
            ],
            [
                'type' => 'BeforeTime',
                'value' => 0,
            ],
        ],
        'items' => 'rare',
        'start_time' => '2020-09-18 00:00:00', 
        'end_time' => '2020-10-18 00:00:00',
    ],
    4 => [
        'id' => 4,
        'name' => 'Rare Shop Vip',
        'unlock' => [
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
        ],
        'items' => 'rarevip',
    ]
];