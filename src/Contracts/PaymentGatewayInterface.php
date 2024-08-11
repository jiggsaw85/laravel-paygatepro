<?php

namespace JiggsawPhp\PayGatePro\Contracts;

use Stripe\Charge;
use Stripe\Refund;

interface PaymentGatewayInterface
{
    /**
     * Charge the amount using the payment gateway.
     *
     * @param float $amount
     * @param string $currency
     * @param array $options
     * @return \Stripe\Charge|null
     */
    public function charge(float $amount, string $currency, array $options = []): ?Charge;

    /**
     * Refund a transaction using the payment gateway.
     *
     * @param string $transactionId
     * @param float $amount
     * @param array $options
     * @return \Stripe\Refund|null
     */
    public function refund(string $transactionId, float $amount, array $options = []): ?Refund;
}
