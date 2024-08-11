<?php

namespace JiggsawPhp\PayGatePro\Services;

use JiggsawPhp\PayGatePro\Contracts\PaymentGatewayInterface;
use Stripe\Charge;
use Stripe\Refund;

class PaymentService
{
    protected PaymentGatewayInterface $gateway;

    public function __construct(PaymentGatewayInterface $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * Charge the amount using the configured gateway.
     *
     * @param float $amount
     * @param string $currency
     * @param array $options
     * @return \Stripe\Charge|null
     */
    public function charge(float $amount, string $currency, array $options = []): ?Charge
    {
        return $this->gateway->charge($amount, $currency, $options);
    }

    /**
     * Refund a transaction using the configured gateway.
     *
     * @param string $transactionId
     * @param float $amount
     * @param array $options
     * @return \Stripe\Refund|null
     */
    public function refund(string $transactionId, float $amount, array $options = []): ?Refund
    {
        return $this->gateway->refund($transactionId, $amount, $options);
    }
}
