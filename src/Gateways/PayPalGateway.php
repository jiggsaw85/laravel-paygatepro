<?php

namespace JiggsawPhp\PayGatePro\Gateways;

use JiggsawPhp\PayGatePro\Contracts\PaymentGatewayInterface;

class PayPalGateway implements PaymentGatewayInterface
{
    /**
     * @param float $amount
     * @param string $currency
     * @param array $options
     * @return bool
     */
    public function charge(float $amount, string $currency, array $options = []): bool
    {
        // Implement Stripe charge logic here
    }

    /**
     * @param string $transactionId
     * @param float $amount
     * @param array $options
     * @return bool
     */
    public function refund(string $transactionId, float $amount, array $options = []): bool
    {
        // Implement Stripe refund logic here
    }
}
