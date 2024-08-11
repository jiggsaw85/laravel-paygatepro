<?php

return [
    'default' => env('PAYMENT_GATEWAY', 'stripe'),

    'gateways' => [
        'stripe' => JiggsawPhp\PayGatePro\Gateways\StripeGateway::class,
        'paysera' => JiggsawPhp\PayGatePro\Gateways\PayseraGateway::class,
        'paypal' => JiggsawPhp\PayGatePro\Gateways\PayPalGateway::class,
    ],

    'stripe' => [
        'api_key' => env('STRIPE_API_KEY'),
    ],
];