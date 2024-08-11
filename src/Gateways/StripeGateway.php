<?php

namespace JiggsawPhp\PayGatePro\Gateways;

use JiggsawPhp\PayGatePro\Contracts\PaymentGatewayInterface;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;
use Stripe\Charge;
use Stripe\Refund;
use Stripe\Exception\ApiErrorException;

class StripeGateway implements PaymentGatewayInterface
{
    protected StripeClient $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('payment.stripe.api_key'));
    }

    /**
     * Charge the amount using Stripe.
     *
     * @param float $amount
     * @param string $currency
     * @param array $options
     * @return \Stripe\Charge|null
     */
    public function charge(float $amount, string $currency, array $options = []): ?Charge
    {
        try {
            $charge = $this->stripe->charges->create([
                'amount' => $amount * 100,
                'currency' => $currency,
                'source' => $options['source'],
                'description' => $options['description'] ?? 'Charge',
            ]);
            return $charge;
        } catch (ApiErrorException $e) {
            Log::error("Error in Stripe charge: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * Refund a transaction using Stripe.
     *
     * @param string $transactionId
     * @param float $amount
     * @param array $options
     * @return \Stripe\Refund|null
     */
    public function refund(string $transactionId, float $amount, array $options = []): ?Refund
    {
        try {
            $refund = $this->stripe->refunds->create([
                'charge' => $transactionId,
                'amount' => $amount * 100,
            ]);
            return $refund;
        } catch (ApiErrorException $e) {
            Log::error("Error in Stripe refund: {$e->getMessage()}");
            return null;
        }
    }
}
