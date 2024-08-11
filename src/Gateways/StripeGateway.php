<?php

namespace JiggsawPhp\PayGatePro\Gateways;

use JiggsawPhp\PayGatePro\Contracts\PaymentGatewayInterface;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;

class StripeGateway implements PaymentGatewayInterface
{
    protected $stripe;

    public function __construct()
    {
        $this->stripe = new StripeClient(config('payment.stripe.api_key'));
    }

    /**
     * @param float $amount
     * @param string $currency
     * @param array $options
     * @return bool
     */
    public function charge(float $amount, string $currency, array $options = []): bool
    {
        try {
            $this->stripe->charges->create([
                'amount' => $amount * 100,
                'currency' => $currency,
                'source' => $options['source'],
                'description' => $options['description'] ?? 'Charge',
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error("Error in Stripe charge: {$e->getMessage()}");
            return false;
        }
    }

    /**
     * @param string $transactionId
     * @param float $amount
     * @param array $options
     * @return bool
     */
    public function refund(string $transactionId, float $amount, array $options = []): bool
    {
        try {
            $this->stripe->refunds->create([
                'charge' => $transactionId,
                'amount' => $amount * 100,
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error("Error in Stripe charge: {$e->getMessage()}");
            return false;
        }
    }
}
