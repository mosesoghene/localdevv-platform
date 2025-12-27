<?php

namespace App\Services\Payment;

use App\Models\PaymentSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaystackGateway implements PaymentGatewayInterface
{
    protected $secretKey;
    protected $publicKey;
    protected $baseUrl;

    public function __construct()
    {
        $settings = PaymentSetting::getProvider('paystack');
        
        if ($settings) {
            $this->secretKey = $settings->secret_key;
            $this->publicKey = $settings->public_key;
            $this->baseUrl = $settings->is_test_mode 
                ? 'https://api.paystack.co' 
                : 'https://api.paystack.co';
        }
    }

    public function initializePayment(array $data): array
    {
        try {
            $response = Http::withToken($this->secretKey)
                ->post("{$this->baseUrl}/transaction/initialize", [
                    'email' => $data['email'],
                    'amount' => $data['amount'] * 100, // Convert to kobo
                    'reference' => $data['reference'],
                    'callback_url' => $data['callback_url'] ?? route('payment.callback'),
                    'metadata' => $data['metadata'] ?? [],
                ]);

            if ($response->successful()) {
                return [
                    'status' => 'success',
                    'data' => $response->json()['data'],
                    'authorization_url' => $response->json()['data']['authorization_url'],
                    'access_code' => $response->json()['data']['access_code'],
                    'reference' => $response->json()['data']['reference'],
                ];
            }

            return [
                'status' => 'error',
                'message' => $response->json()['message'] ?? 'Payment initialization failed',
            ];
        } catch (\Exception $e) {
            Log::error('Paystack initialization error: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Payment initialization failed: ' . $e->getMessage(),
            ];
        }
    }

    public function verifyPayment(string $reference): array
    {
        try {
            $response = Http::withToken($this->secretKey)
                ->get("{$this->baseUrl}/transaction/verify/{$reference}");

            if ($response->successful()) {
                $data = $response->json()['data'];
                
                return [
                    'status' => 'success',
                    'data' => $data,
                    'payment_status' => $data['status'],
                    'amount' => $data['amount'] / 100, // Convert from kobo
                    'currency' => $data['currency'],
                    'paid_at' => $data['paid_at'] ?? null,
                ];
            }

            return [
                'status' => 'error',
                'message' => $response->json()['message'] ?? 'Verification failed',
            ];
        } catch (\Exception $e) {
            Log::error('Paystack verification error: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Verification failed: ' . $e->getMessage(),
            ];
        }
    }

    public function getPaymentStatus(string $reference): string
    {
        $verification = $this->verifyPayment($reference);
        
        if ($verification['status'] === 'success') {
            return $verification['payment_status'];
        }
        
        return 'failed';
    }

    public function handleWebhook(array $payload): array
    {
        // Verify Paystack signature
        $signature = request()->header('x-paystack-signature');
        
        if (!$signature) {
            return ['status' => 'error', 'message' => 'No signature found'];
        }

        $body = request()->getContent();
        $computedSignature = hash_hmac('sha512', $body, $this->secretKey);

        if ($signature !== $computedSignature) {
            return ['status' => 'error', 'message' => 'Invalid signature'];
        }

        $event = $payload['event'] ?? '';
        $data = $payload['data'] ?? [];

        return [
            'status' => 'success',
            'event' => $event,
            'data' => $data,
            'reference' => $data['reference'] ?? null,
            'payment_status' => $data['status'] ?? 'unknown',
        ];
    }

    public function getAuthorizationUrl(array $data): string
    {
        $result = $this->initializePayment($data);
        return $result['authorization_url'] ?? '';
    }

    public function getPublicKey(): ?string
    {
        return $this->publicKey;
    }
}
