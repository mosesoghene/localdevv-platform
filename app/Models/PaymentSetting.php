<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    protected $fillable = [
        'provider',
        'public_key',
        'secret_key',
        'merchant_id',
        'encryption_key',
        'is_enabled',
        'is_test_mode',
        'additional_settings',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'is_test_mode' => 'boolean',
        'additional_settings' => 'array',
    ];

    /**
     * Get enabled payment provider
     */
    public static function getEnabledProvider(): ?self
    {
        return self::where('is_enabled', true)->first();
    }

    /**
     * Get specific provider settings
     */
    public static function getProvider(string $provider): ?self
    {
        return self::where('provider', $provider)->first();
    }
}
