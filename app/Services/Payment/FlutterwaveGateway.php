<?php

namespace App\Services\Payment;

use App\Models\PaymentSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FlutterwaveGateway implements PaymentGatewayInterface
{
    protected $secretKey;
    protected $publicKey;
    protected $baseUrl;

    public function __construct()
    {
        $settings = PaymentSetting::getProvider('flutterwave');
        
        if ($settings) {
            $this->secretKey = $settings->secret_key;
            $this->publicKey = $settings->public_key;
            $this->baseUrl = $settings->is_test_mode 
                ? 'https://api.flutterwave.com/v3' 
                : 'https://api.flutterwave.com/v3';
        }
    }

    public function initializePayment(array $data): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
            ])->post("{$this->baseUrl}/payments", [
                'tx_ref' => $data['reference'],
                'amount' => $data['amount'],
                'currency' => $data['currency'] ?? 'NGN',
                'redirect_url' => $data['callback_url'] ?? route('payment.callback'),
                'customer' => [
                    'email' => $data['email'],
                    'name' => $data['name'] ?? '',
                ],
                'customizations' => [
                    'title' => $data['title'] ?? config('app.name'),
                    'description' => $data['description'] ?? 'Payment',
                ],
                'meta' => $data['metadata'] ?? [],
            ]);

            $responseData = $response->json();

            if ($response->successful() && $responseData['status'] === 'success') {
                return [
                    'status' => 'success',
                    'data' => $responseData['data'],
                    'authorization_url' => $responseData['data']['link'],
                    'reference' => $data['reference'],
                ];
            }

            return [
                'status' => 'error',
                'message' => $responseData['message'] ?? 'Payment initialization failed',
            ];
        } catch (\Exception $e) {
            Log::error('Flutterwave initialization error: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Payment initialization failed: ' . $e->getMessage(),
            ];
        }
    }

    public function verifyPayment(string $reference): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
            ])->get("{$this->baseUrl}/transactions/verify_by_reference", [
                'tx_ref' => $reference,
            ]);

            $responseData = $response->json();

            if ($response->successful() && $responseData['status'] === 'success') {
                $data = $responseData['data'];
                
                return [
                    'status' => 'success',
                    'data' => $data,
                    'payment_status' => $data['status'],
                    'amount' => $data['amount'],
                    'currency' => $data['currency'],
                    'paid_at' => $data['created_at'] ?? null,
                ];
            }

            return [
                'status' => 'error',
                'message' => $responseData['message'] ?? 'Verification failed',
            ];
        } catch (\Exception $e) {
            Log::error('Flutterwave verification error: ' . $e->getMessage());
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
        // Verify Flutterwave signature
        $signature = request()->header('verif-hash');
        $webhookSecret = $this->secretKey;

        if (!$signature || $signature !== $webhookSecret) {
            return ['status' => 'error', 'message' => 'Invalid signature'];
        }

        $event = $payload['event'] ?? '';
        $data = $payload['data'] ?? [];

        return [
            'status' => 'success',
            'event' => $event,
            'data' => $data,
            'reference' => $data['tx_ref'] ?? null,
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
