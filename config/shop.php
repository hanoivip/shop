<?php 

// Configure Shop Platforms
return [
    // Support: array, database
    'cfg' => 'array',
    // Define db table name, platfrom for each group
    // If not defined, group will be deactived
    // Common platform name: web, game:s1, game:s2 ...
    'platforms' => [
        'web' => [
            'table' => 'user_shops',
            'shop_table' => 'shops',
            'item_table' => 'shop_items',
            'platform' => 'web'
        ],
        's1' => [
            'table' => 's1_user_shops',
            'shop_table' => 's1_shops',
            'item_table' => 's1_shop_items',
            'platform' => 's1'
        ]
    ]
];