<?php

namespace JiggsawPhp\PayGatePro\Contracts;

interface PaymentGatewayInterface
{
    /**
     * @param float $amount
     * @param string $currency
     * @param array $options
     * @return bool
     */
    public function charge(float $amount, string $currency, array $options = []): bool;

    /**
     * @param string $transactionId
     * @param float $amount
     * @param array $options
     * @return bool
     */
    public function refund(string $transactionId, float $amount, array $options = []): bool;
}
