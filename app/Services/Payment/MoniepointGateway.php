<?php

namespace App\Services\Payment;

use App\Models\PaymentSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MoniepointGateway implements PaymentGatewayInterface
{
    protected $secretKey;
    protected $publicKey;
    protected $merchantId;
    protected $baseUrl;

    public function __construct()
    {
        $settings = PaymentSetting::getProvider('moniepoint');
        
        if ($settings) {
            $this->secretKey = $settings->secret_key;
            $this->publicKey = $settings->public_key;
            $this->merchantId = $settings->merchant_id;
            $this->baseUrl = $settings->is_test_mode 
                ? 'https://sandbox.moniepoint.com/api/v1' 
                : 'https://api.moniepoint.com/api/v1';
        }
    }

    public function initializePayment(array $data): array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->secretKey,
                'Content-Type' => 'application/json',
            ])->post("{$this->baseUrl}/merchant/transactions/init-transaction", [
                'amount' => $data['amount'],
                'currency' => $data['currency'] ?? 'NGN',
                'reference' => $data['reference'],
                'customerEmail' => $data['email'],
                'customerName' => $data['name'] ?? '',
                'paymentDescription' => $data['description'] ?? 'Payment',
                'redirectUrl' => $data['callback_url'] ?? route('payment.callback'),
                'metadata' => $data['metadata'] ?? [],
            ]);

            $responseData = $response->json();

            if ($response->successful() && isset($responseData['responseBody'])) {
                $body = $responseData['responseBody'];
                return [
                    'status' => 'success',
                    'data' => $body,
                    'authorization_url' => $body['checkoutUrl'] ?? '',
                    'reference' => $data['reference'],
                ];
            }

            return [
                'status' => 'error',
                'message' => $responseData['responseMessage'] ?? 'Payment initialization failed',
            ];
        } catch (\Exception $e) {
            Log::error('Moniepoint initialization error: ' . $e->getMessage());
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
            ])->get("{$this->baseUrl}/merchant/transactions/query", [
                'transactionReference' => $reference,
            ]);

            $responseData = $response->json();

            if ($response->successful() && isset($responseData['responseBody'])) {
                $data = $responseData['responseBody'];
                
                return [
                    'status' => 'success',
                    'data' => $data,
                    'payment_status' => $data['paymentStatus'] ?? 'failed',
                    'amount' => $data['amount'],
                    'currency' => $data['currency'] ?? 'NGN',
                    'paid_at' => $data['paidOn'] ?? null,
                ];
            }

            return [
                'status' => 'error',
                'message' => $responseData['responseMessage'] ?? 'Verification failed',
            ];
        } catch (\Exception $e) {
            Log::error('Moniepoint verification error: ' . $e->getMessage());
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
            $status = $verification['payment_status'];
            return strtolower($status) === 'paid' ? 'success' : 'failed';
        }
        
        return 'failed';
    }

    public function handleWebhook(array $payload): array
    {
        // Moniepoint webhook validation
        $signature = request()->header('x-monnify-signature');
        
        if (!$signature) {
            return ['status' => 'error', 'message' => 'No signature found'];
        }

        // Compute signature hash
        $body = request()->getContent();
        $computedSignature = hash_hmac('sha512', $body, $this->secretKey);

        if ($signature !== $computedSignature) {
            return ['status' => 'error', 'message' => 'Invalid signature'];
        }

        $event = $payload['eventType'] ?? '';
        $data = $payload['eventData'] ?? [];

        return [
            'status' => 'success',
            'event' => $event,
            'data' => $data,
            'reference' => $data['transactionReference'] ?? null,
            'payment_status' => $data['paymentStatus'] ?? 'unknown',
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
