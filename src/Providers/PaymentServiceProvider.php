<?php

namespace JiggsawPhp\PayGatePro\Providers;

use Illuminate\Support\ServiceProvider;
use JiggsawPhp\PayGatePro\Services\PaymentService;
use JiggsawPhp\PayGatePro\Contracts\PaymentGatewayInterface;

class PaymentServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton(PaymentService::class, function ($app) {
            $gatewayKey = config('payment.default');
            $gatewayClass = config("payment.gateways.$gatewayKey");

            if (!$gatewayClass || !class_exists($gatewayClass)) {
                throw new \Exception("Unsupported payment gateway: $gatewayKey");
            }

            $gatewayInstance = app($gatewayClass);

            if (!$gatewayInstance instanceof PaymentGatewayInterface) {
                throw new \Exception("Gateway class must implement PaymentGatewayInterface");
            }

            return new PaymentService($gatewayInstance);
        });
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../../config/payment.php' => config_path('payment.php'),
        ]);
    }
}
