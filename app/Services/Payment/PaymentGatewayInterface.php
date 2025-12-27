<?php

namespace App\Services\Payment;

interface PaymentGatewayInterface
{
    /**
     * Initialize a payment
     *
     * @param array $data Payment data (amount, email, reference, etc.)
     * @return array Payment initialization response
     */
    public function initializePayment(array $data): array;

    /**
     * Verify a payment transaction
     *
     * @param string $reference Transaction reference
     * @return array Verification response
     */
    public function verifyPayment(string $reference): array;

    /**
     * Get payment status
     *
     * @param string $reference Transaction reference
     * @return string Payment status
     */
    public function getPaymentStatus(string $reference): string;

    /**
     * Handle webhook callback
     *
     * @param array $payload Webhook payload
     * @return array Processed webhook data
     */
    public function handleWebhook(array $payload): array;

    /**
     * Get authorization URL for payment
     *
     * @param array $data Payment data
     * @return string Authorization URL
     */
    public function getAuthorizationUrl(array $data): string;
}
