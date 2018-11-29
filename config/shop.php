<?php 

// Configure Shop Platforms
return [
    
    // Support: array, database
    'cfg' => 'array',
    // When use database to store shop config,
    'shop_table' => 'shops',
    'item_table' => 'shop_items',
    
    // Define db table name, platfrom for each group
    // If not defined, group will be deactived
    // Common platform name: web, game:s1, game:s2 ...
    'web' => [
        'table' => 'user_shops',
        'platform' => 'web'
    ],
    's1' => [
        'table' => 's1_user_shops',
        'platform' => 's1'
    ]
];