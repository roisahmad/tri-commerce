<?php

return [
    'api_key' => env('TRIPAY_API_KEY'),
    'private_key' => env('TRIPAY_PRIVATE_KEY'),
    'merchant_code' => env('TRIPAY_MERCHANT_CODE'),
    'base_url' => env('TRIPAY_BASE_URL', 'https://tripay.co.id/api-sandbox'),
];
