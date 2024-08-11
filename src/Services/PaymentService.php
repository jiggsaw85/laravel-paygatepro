<?php

namespace JiggsawPhp\PayGatePro\Services;

use JiggsawPhp\PayGatePro\Contracts\PaymentGatewayInterface;

class PaymentService
{
    protected PaymentGatewayInterface $gateway;

    public function __construct(PaymentGatewayInterface $gateway)
    {
        $this->gateway = $gateway;
    }

    /**
     * @param float $amount
     * @param string $currency
     * @param array $options
     * @return bool
     */
    public function charge(float $amount, string $currency, array $options = []): bool
    {
        return $this->gateway->charge($amount, $currency, $options);
    }

    /**
     * @param string $transactionId
     * @param float $amount
     * @param array $options
     * @return bool
     */
    public function refund(string $transactionId, float $amount, array $options = []): bool
    {
        return $this->gateway->refund($transactionId, $amount, $options);
    }
}
