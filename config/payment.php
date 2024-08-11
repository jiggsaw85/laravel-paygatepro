<?php

return [
    'default' => env('PAYMENT_GATEWAY', 'stripe'),

    'gateways' => [
        'stripe' => JiggsawPhp\PayGatePro\Gateways\StripeGateway::class,
        'authorize_net' => JiggsawPhp\PayGatePro\Gateways\AuthorizeNetGateway::class,
        'paypal' => JiggsawPhp\PayGatePro\Gateways\PayPalGateway::class,
    ],

    'stripe' => [
        'api_key' => env('STRIPE_API_KEY'),
    ],

    'authorize_net' => [
        'api_login_id' => env('AUTHORIZE_NET_API_LOGIN_ID'),
        'transaction_key' => env('AUTHORIZE_NET_TRANSACTION_KEY'),
    ],

    'paypal' => [
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'secret' => env('PAYPAL_SECRET'),
        'mode' => env('PAYPAL_MODE', 'sandbox'),
    ],
];