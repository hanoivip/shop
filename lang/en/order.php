<?php

return [
    'validate' => [
        'svname-missing' => 'Need to select the server you want to transfer',
        'roleid-missing' => 'Need to select the character you want to transfer',
    ],
    'payment_status' => [
        0 => 'Unpaid',
        1 => 'Cancel',
        2 => 'Paid',
        3 => 'Error'
    ],
    'delivery_status' => [
        0 => 'Wait',
        1 => 'Sending',
        2 => 'Sent',
        3 => 'Send error'
    ],
];