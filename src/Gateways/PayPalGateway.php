<?php

namespace JiggsawPhp\PayGatePro\Gateways;

use JiggsawPhp\PayGatePro\Contracts\PaymentGatewayInterface;
use Illuminate\Support\Facades\Log;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\Refund;
use PayPal\Api\Sale;
use PayPal\Api\RefundRequest;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;
use PayPal\Exception\PayPalConnectionException;

class PayPalGateway implements PaymentGatewayInterface
{
    protected ApiContext $apiContext;

    public function __construct()
    {
        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                config('payment.paypal.client_id'),
                config('payment.paypal.secret')
            )
        );
        $this->apiContext->setConfig([
            'mode' => config('payment.paypal.mode', 'sandbox'),
            'log.LogEnabled' => true,
            'log.FileName' => storage_path('logs/paypal.log'),
            'log.LogLevel' => 'DEBUG',
        ]);
    }

    /**
     * @param float $amount
     * @param string $currency
     * @param array $options
     * @return Payment|null
     */
    public function charge(float $amount, string $currency, array $options = []): ?Payment
    {
        try {
            $payer = new Payer();
            $payer->setPaymentMethod('paypal');

            $amountObj = new Amount();
            $amountObj->setTotal($amount);
            $amountObj->setCurrency($currency);

            $transaction = new \PayPal\Api\Transaction();
            $transaction->setAmount($amountObj);
            $transaction->setDescription($options['description'] ?? 'Charge');

            $redirectUrls = new \PayPal\Api\RedirectUrls();
            $redirectUrls->setReturnUrl($options['return_url']);
            $redirectUrls->setCancelUrl($options['cancel_url']);

            $payment = new Payment();
            $payment->setIntent('sale');
            $payment->setPayer($payer);
            $payment->setTransactions([$transaction]);
            $payment->setRedirectUrls($redirectUrls);

            $payment->create($this->apiContext);
            return $payment;
        } catch (PayPalConnectionException $e) {
            Log::error("Error in PayPal charge: {$e->getData()}");
            return null;
        } catch (\Exception $e) {
            Log::error("General error in PayPal charge: {$e->getMessage()}");
            return null;
        }
    }

    /**
     * @param string $transactionId
     * @param float $amount
     * @param array $options
     * @return Refund|null
     */
    public function refund(string $transactionId, float $amount, array $options = []): ?Refund
    {
        try {
            $sale = Sale::get($transactionId, $this->apiContext);

            $amountObj = new Amount();
            $amountObj->setCurrency($options['currency']);
            $amountObj->setTotal($amount);

            $refundRequest = new RefundRequest();
            $refundRequest->setAmount($amountObj);

            $refund = $sale->refundSale($refundRequest, $this->apiContext);
            return $refund;
        } catch (PayPalConnectionException $e) {
            Log::error("Error in PayPal refund: {$e->getData()}");
            return null;
        } catch (\Exception $e) {
            Log::error("General error in PayPal refund: {$e->getMessage()}");
            return null;
        }
    }
}
