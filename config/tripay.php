<?php

return [
    'base'          => env('TRIPAY_BASE', 'https://tripay.co.id/api-sandbox'),
    'api_key'       => env('TRIPAY_API_KEY'),
    'private_key'   => env('TRIPAY_PRIVATE_KEY'),
    'merchant_code' => env('TRIPAY_MERCHANT_CODE'),
    'create_path'   => '/transaction/create',
];
