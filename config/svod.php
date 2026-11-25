<?php

return [
    'price' => (int) env('SVOD_PRICE', 1),
    'midtrans' => [
        'server_key' => env('MIDTRANS_SERVER_KEY'),
        'client_key' => env('MIDTRANS_CLIENT_KEY'),
        'signature_key' => env('MIDTRANS_SIGNATURE_KEY'),
        'production' => filter_var(env('MIDTRANS_PRODUCTION', false), FILTER_VALIDATE_BOOL),
    ],
];
