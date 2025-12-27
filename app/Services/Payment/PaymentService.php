<?php

namespace App\Services\Payment;

use App\Models\PaymentSetting;

class PaymentService
{
    /**
     * Get the active payment gateway
     */
    public static function getGateway(): ?PaymentGatewayInterface
    {
        $settings = PaymentSetting::getEnabledProvider();
        
        if (!$settings) {
            return null;
        }

        return match($settings->provider) {
            'paystack' => new PaystackGateway(),
            'flutterwave' => new FlutterwaveGateway(),
            'moniepoint' => new MoniepointGateway(),
            default => null,
        };
    }

    /**
     * Get specific gateway by provider name
     */
    public static function getGatewayByProvider(string $provider): ?PaymentGatewayInterface
    {
        return match($provider) {
            'paystack' => new PaystackGateway(),
            'flutterwave' => new FlutterwaveGateway(),
            'moniepoint' => new MoniepointGateway(),
            default => null,
        };
    }

    /**
     * Get enabled provider name
     */
    public static function getEnabledProvider(): ?string
    {
        $settings = PaymentSetting::getEnabledProvider();
        return $settings?->provider;
    }
}
