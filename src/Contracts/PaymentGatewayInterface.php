<?php

namespace JiggsawPhp\PayGatePro\Contracts;

interface PaymentGatewayInterface
{
    /**
     * Charge the amount using the payment gateway.
     *
     * @param float $amount
     * @param string $currency
     * @param array $options
     * @return mixed
     */
    public function charge(float $amount, string $currency, array $options = []): mixed;

    /**
     * Refund a transaction using the payment gateway.
     *
     * @param string $transactionId
     * @param float $amount
     * @param array $options
     * @return mixed
     */
    public function refund(string $transactionId, float $amount, array $options = []): mixed;
}
