<?php

/**
 * PayOS Payment Gateway Integration
 * Documentation: https://payos.vn/docs/
 */
class PayOSService
{
    private $clientId;
    private $apiKey;
    private $checksumKey;
    private $apiUrl = 'https://api-merchant.payos.vn';
    private $logFile;
    
    public function __construct()
    {
        // Load config
        $config = require __DIR__ . '/../config/payos.php';
        $this->clientId = $config['client_id'];
        $this->apiKey = $config['api_key'];
        $this->checksumKey = $config['checksum_key'];
        $this->logFile = dirname(__DIR__) . '/logs/errors.log';
    }
    
    /**
     * Create a payment link
     * 
     * @param array $orderData Order information
     * @return array Payment link response
     */
    public function createPaymentLink($orderData)
    {
        $orderCode = $orderData['orderCode'];
        $amount = $orderData['amount'];
        $description = $orderData['description'] ?? "Thanh toán đơn hàng #{$orderCode}";
        $returnUrl = $orderData['returnUrl'];
        $cancelUrl = $orderData['cancelUrl'] ?? $returnUrl;
        
        $items = $orderData['items'] ?? [];
        
        $data = [
            'orderCode' => $orderCode,
            'amount' => (int)$amount,
            'description' => $description,
            'items' => $items,
            'returnUrl' => $returnUrl,
            'cancelUrl' => $cancelUrl
        ];
        
        // Generate signature
        $data['signature'] = $this->generateSignature($data);
        
        try {
            $this->logDebug('PayOS createPaymentLink request', $data);
            $response = $this->sendRequest('/v2/payment-requests', 'POST', $data);
            $this->logDebug('PayOS createPaymentLink response', $response);
            return [
                'success' => true,
                'data' => $response
            ];
        } catch (Exception $e) {
            $this->logDebug('PayOS createPaymentLink error', ['error' => $e->getMessage(), 'exception' => get_class($e)]);
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'exception' => get_class($e)
            ];
        }
    }
    
    /**
     * Get payment information
     */
    public function getPaymentInfo($orderCode)
    {
        try {
            $this->logDebug('PayOS getPaymentInfo request', ['orderCode' => $orderCode]);
            $response = $this->sendRequest("/v2/payment-requests/{$orderCode}", 'GET');
            $this->logDebug('PayOS getPaymentInfo response', $response);
            return [
                'success' => true,
                'data' => $response
            ];
        } catch (Exception $e) {
            $this->logDebug('PayOS getPaymentInfo error', ['error' => $e->getMessage(), 'exception' => get_class($e)]);
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'exception' => get_class($e)
            ];
        }
    }
    
    /**
     * Cancel payment
     */
    public function cancelPayment($orderCode, $reason = null)
    {
        $data = [];
        if ($reason) {
            $data['cancellationReason'] = $reason;
        }
        
        try {
            $this->logDebug('PayOS cancelPayment request', ['orderCode' => $orderCode, 'data' => $data]);
            $response = $this->sendRequest("/v2/payment-requests/{$orderCode}/cancel", 'POST', $data);
            $this->logDebug('PayOS cancelPayment response', $response);
            return [
                'success' => true,
                'data' => $response
            ];
        } catch (Exception $e) {
            $this->logDebug('PayOS cancelPayment error', ['error' => $e->getMessage(), 'exception' => get_class($e)]);
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'exception' => get_class($e)
            ];
        }
    }
    
    /**
     * Verify webhook signature
     */
    public function verifyWebhookSignature($webhookData, $signature)
    {
        $dataString = $this->sortAndStringify($webhookData);
        $expectedSignature = hash_hmac('sha256', $dataString, $this->checksumKey);
        
        return hash_equals($expectedSignature, $signature);
    }
    
    /**
     * Generate signature for request
     * According to PayOS documentation: signature = HMAC_SHA256(checksumKey, "amount={amount}&cancelUrl={cancelUrl}&description={description}&orderCode={orderCode}&returnUrl={returnUrl}")
     */
    private function generateSignature($data)
    {
        // Remove signature field if exists
        unset($data['signature']);

        // PayOS requires specific order: amount, cancelUrl, description, orderCode, returnUrl
        $signatureData = [
            'amount' => isset($data['amount']) ? $data['amount'] : '',
            'cancelUrl' => isset($data['cancelUrl']) ? $data['cancelUrl'] : '',
            'description' => isset($data['description']) ? $data['description'] : '',
            'orderCode' => isset($data['orderCode']) ? $data['orderCode'] : '',
            'returnUrl' => isset($data['returnUrl']) ? $data['returnUrl'] : ''
        ];

        // Build signature string in exact order
        $parts = [];
        foreach ($signatureData as $key => $value) {
            $parts[] = "{$key}={$value}";
        }
        $payload = implode('&', $parts);
        
        $signature = hash_hmac('sha256', $payload, $this->checksumKey);
        
        // Log payload to aid debugging
        $this->logDebug('PayOS signature payload', ['payload' => $payload, 'signature' => $signature]);
        return $signature;
    }
    
    /**
     * Sort and stringify data for signature
     */
    private function sortAndStringify($data)
    {
        ksort($data);
        $parts = [];
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $value = json_encode($value);
            }
            $parts[] = "{$key}={$value}";
        }
        return implode('&', $parts);
    }
    
    /**
     * Send HTTP request to PayOS API
     */
    private function sendRequest($endpoint, $method = 'GET', $data = null)
    {
        $url = $this->apiUrl . $endpoint;
        
        $headers = [
            'x-client-id: ' . $this->clientId,
            'x-api-key: ' . $this->apiKey,
            'Content-Type: application/json'
        ];
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            if ($data) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
        } elseif ($method === 'PUT') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            if ($data) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }
        }
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            $this->logDebug('PayOS CURL error', ['endpoint' => $endpoint, 'method' => $method, 'error' => $error]);
            throw new Exception("CURL Error: {$error}");
        }

        $responseData = json_decode($response, true);

        if ($httpCode >= 400) {
            $errorMsg = $responseData['message'] ?? ($responseData['error'] ?? 'Unknown error');
            // Include raw body for debugging in dev
            $raw = is_string($response) ? $response : json_encode($responseData);
            $this->logDebug('PayOS API error', ['endpoint' => $endpoint, 'method' => $method, 'httpCode' => $httpCode, 'error' => $errorMsg, 'body' => $raw]);
            throw new Exception("PayOS API Error ({$httpCode}): {$errorMsg} | Body: {$raw}");
        }

        return $responseData;
    }

    /**
     * Minimal file logger for debugging PayOS integration
     */
    private function logDebug($message, $context = [])
    {
        try {
            $ts = date('Y-m-d H:i:s');
            // Redact secrets
            if (isset($context['api_key'])) {
                $context['api_key'] = '[REDACTED]';
            }
            if (isset($context['checksum_key'])) {
                $context['checksum_key'] = '[REDACTED]';
            }
            $line = "[{$ts}] {$message} " . (!empty($context) ? json_encode($context) : '') . PHP_EOL;
            // Ensure directory exists
            $dir = dirname($this->logFile);
            if (!is_dir($dir)) {
                @mkdir($dir, 0777, true);
            }
            @file_put_contents($this->logFile, $line, FILE_APPEND);
        } catch (\Throwable $e) {
            // Silently ignore logging failures
        }
    }
}
