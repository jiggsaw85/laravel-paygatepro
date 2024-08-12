# PayGatePro - Handle Payments in Your Laravel Application

## Introduction

PayGatePro is a Laravel package designed to streamline the integration of multiple payment gateways into your Laravel application. Whether you need to handle payments via Stripe, PayPal, or any other supported gateway, PayGatePro provides a unified interface for managing transactions.

## Features

- **Multi-Gateway Support:** Easily switch between different payment gateways.
- **Simple Integration:** Quick setup with minimal configuration.
- **Flexible Configuration:** Define payment gateways and settings through configuration files.
- **Laravel Integration:** Built to work seamlessly with Laravel applications.

## Requirements

- PHP 8.0 or higher
- Laravel 9.x, 10.x, or 11.x

## Installation

To install PayGatePro, follow these steps:

1. **Add the Package to Your Project**

   Add the package to your `composer.json` file or run the following command:

   ```bash
   composer require jiggsawphp/paygatepro
   
2. **Publish the configuration file to customize the payment gateways and settings**

   ```bash
   php artisan vendor:publish --provider="JiggsawPhp\PayGatePro\Providers\PaymentServiceProvider"
   
## Usage

Package supports Stripe, Authorize.net and PayPal payment gateways.

Get your credentials for payment gateway you want to use.

In your .env add:

   ```bash
   # jiggsawphp/paygatepro
   PAYMENT_GATEWAY=stripe
   # stripe
   STRIPE_API_KEY=your_stripe_key
   # authorize.net
   AUTHORIZE_NET_API_LOGIN_ID=your_api_login_id
   AUTHORIZE_NET_TRANSACTION_KEY=your_transaction_key
   # paypal
   PAYPAL_MODE=sandbox_or_live
   PAYPAL_CLIENT_ID=your_paypal_client_id
   PAYPAL_SECRET=your_paypal_secret
   ```

PAYMENT_GATEWAY variable defines payment gateway you want to use (default is stripe) (stripe, paypal or authorize).

1. Include PaymentService in your code and use charge or refund methods from it.
2. Examples:

   ```bash
    use JiggsawPhp\PayGatePro\Services\PaymentService;
   
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Charge with Stripe
     * @return JsonResponse
     */
    public function chargeWithStripe(): JsonResponse
    {
        $response = $this->paymentService->charge(100, 'USD', ['source' => 'tok_visa']);

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Refund with Stripe
     * @return JsonResponse
     */
    public function refundWithStripe(): JsonResponse
    {
        $response = $this->paymentService->charge(100, 'USD', ['source' => 'tok_visa']);
        $refund = $this->paymentService->refund($response->id, 50, []);

        return response()->json($refund, Response::HTTP_OK);
    }

    /**
     * Charge with PayPal
     * @return JsonResponse
     */
    public function chargeWithPayPal(): JsonResponse
    {
        $response = $this->paymentService->charge(100.00, 'USD', [
            'return_url' => route('payment.success'),
            'cancel_url' => route('payment.cancel'),
            'description' => 'Payment for Order #12345',
        ]);

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Refund with PayPal
     * @return JsonResponse
     */
    public function refundWithPayPal(): JsonResponse
    {
        $charge = $this->paymentService->charge(100.00, 'USD', [
            'return_url' => route('payment.success'),
            'cancel_url' => route('payment.cancel'),
            'description' => 'Payment for Order #12345',
        ]);
        $refund = $this->paymentService->refund($charge->id, 50.00);

        return response()->json($refund, Response::HTTP_OK);
    }

    /**
     * Charge with Authorize.net
     * @return JsonResponse
     */
    public function chargeWithAuthorize(): JsonResponse
    {
        $response = $this->paymentService->charge(100.00, 'USD', [
            'card_number' => '4111111111111111',
            'expiration_date' => '2024-12',
            'cvv' => '123',
        ]);

        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Refund with Authorize.net
     * @return JsonResponse
     */
    public function refundWithAuthorize(): JsonResponse
    {
        $charge = $this->paymentService->charge(100.00, 'USD', [
            'card_number' => '4111111111111111',
            'expiration_date' => '2024-12',
            'cvv' => '123',
        ]);
        $refund = $this->paymentService->refund($charge->id, 50.00, [
            'card_number' => '4111111111111111',
        ]);

        return response()->json($refund, Response::HTTP_OK);
    }
