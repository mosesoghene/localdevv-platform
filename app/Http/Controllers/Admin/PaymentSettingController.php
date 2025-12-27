<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use Illuminate\Http\Request;

class PaymentSettingController extends Controller
{
    /**
     * Display payment settings
     */
    public function index()
    {
        $providers = ['paystack', 'flutterwave', 'moniepoint'];
        $settings = PaymentSetting::all()->keyBy('provider');

        return view('admin.payment-settings.index', compact('providers', 'settings'));
    }

    /**
     * Update or create payment settings
     */
    public function update(Request $request, string $provider)
    {
        $request->validate([
            'public_key' => 'nullable|string',
            'secret_key' => 'nullable|string',
            'merchant_id' => 'nullable|string',
            'encryption_key' => 'nullable|string',
            'is_enabled' => 'boolean',
            'is_test_mode' => 'boolean',
        ]);

        // If enabling this provider, disable all others
        if ($request->boolean('is_enabled')) {
            PaymentSetting::where('provider', '!=', $provider)->update(['is_enabled' => false]);
        }

        $setting = PaymentSetting::updateOrCreate(
            ['provider' => $provider],
            [
                'public_key' => $request->public_key,
                'secret_key' => $request->secret_key,
                'merchant_id' => $request->merchant_id,
                'encryption_key' => $request->encryption_key,
                'is_enabled' => $request->boolean('is_enabled'),
                'is_test_mode' => $request->boolean('is_test_mode'),
            ]
        );

        return redirect()->route('admin.payment-settings.index')
            ->with('success', ucfirst($provider) . ' settings updated successfully!');
    }

    /**
     * Toggle provider status
     */
    public function toggle(string $provider)
    {
        $setting = PaymentSetting::where('provider', $provider)->first();

        if ($setting) {
            if (!$setting->is_enabled) {
                // Disable all other providers
                PaymentSetting::where('provider', '!=', $provider)->update(['is_enabled' => false]);
                $setting->update(['is_enabled' => true]);
                $message = ucfirst($provider) . ' has been enabled.';
            } else {
                $setting->update(['is_enabled' => false]);
                $message = ucfirst($provider) . ' has been disabled.';
            }

            return redirect()->back()->with('success', $message);
        }

        return redirect()->back()->with('error', 'Provider not found.');
    }
}
