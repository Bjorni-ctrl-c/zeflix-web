<?php
return [
    'price' => (int) env('SVOD_PRICE', 10000),
    'midtrans' => [
        'server_key' => env('MIDTRANS_SERVER_KEY'),
        'production' => filter_var(env('MIDTRANS_PRODUCTION', false), FILTER_VALIDATE_BOOL),
    ],
];
