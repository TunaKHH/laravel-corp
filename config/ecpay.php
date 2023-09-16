<?php

return [
    'hash_key' => env('ECPAY_HASH_KEY', 'default_value'),
    'hash_iv' => env('ECPAY_HASH_IV', 'default_value'),
    'redirect_url' => env('APP_URL') . '/api/ecpay/callback',
    'pay_api_url' => env('ECPAY_API_URL', 'https://payment-stage.ecpay.com.tw/Cashier/AioCheckOut/V5'),
    'query_api_url' => env('ECPAY_QUERY_API_URL', 'https://payment-stage.ecpay.com.tw/Cashier/QueryTradeInfo/V5'),
];
