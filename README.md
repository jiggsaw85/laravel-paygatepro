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

Package supports Stripe, Paysera and PayPal payment gateways.

In your .env add:

   ```bash
   # jiggsawphp/paygatepro
   PAYMENT_GATEWAY=stripe
   STRIPE_API_KEY=your_stripe_key
   PAYPAL_CLIENT_ID=your_paypal_client_id
   PAYPAL_SECRET=your_paypal_secret
   ```

PAYMENT_GATEWAY variable defines payment gateway you want to use (stripe, paysera, paypal).

1. Include PaymentService in your code and charge or refund methods from it.
2. Example using PaymentService from PayGatePro package in controller:

   ```bash
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    /**
     * Handle a payment charge request.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function charge(Request $request): JsonResponse
    {
        $amount = $request->input('amount'); // Amount to charge
        $currency = $request->input('currency', 'USD'); // Currency for the transaction
        
        try {
            $result = $this->paymentService->charge($amount, $currency);

            if ($result) {
                return response()->json(['message' => 'Payment successful'], Response::HTTP_OK);
            } else {
                return response()->json(['message' => 'Payment failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Handle a payment refund request.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function refund(Request $request)
    {
        $transactionId = $request->input('transaction_id'); // ID of the transaction to refund
        $amount = $request->input('amount'); // Amount to refund
        
        try {
            $result = $this->paymentService->refund($transactionId, $amount);

            if ($result) {
                return response()->json(['message' => 'Refund successful']);
            } else {
                return response()->json(['message' => 'Refund failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
