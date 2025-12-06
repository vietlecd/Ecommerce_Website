<?php
require_once __DIR__ . '/../config/payos.php';

class PayosModel
{
    private $config;
    private $apiUrl;

    public function __construct()
    {
        $this->config = require __DIR__ . '/../config/payos.php';
        $this->apiUrl = $this->config['sandbox'] 
            ? 'https://api-merchant.payos.vn' 
            : 'https://api-merchant.payos.vn';
    }

    /**
     * Create a payment link via PayOS
     * @param array $orderData - Order information
     * @return array|null - Payment link data or null on failure
     */
    public function createPaymentLink($orderData)
    {
        if (empty($this->config['api_key']) || empty($this->config['checksum_key'])) {
            error_log('PayOS: Missing API credentials');
            return null;
        }

        $orderCode = $orderData['order_id'] ?? time();
        $amount = (int)($orderData['amount'] ?? 0);
        $description = $orderData['description'] ?? 'Payment for order #' . $orderCode;
        $buyerName = $orderData['buyer_name'] ?? 'Customer';
        $buyerEmail = $orderData['buyer_email'] ?? '';
        $buyerPhone = $orderData['buyer_phone'] ?? '';
        $items = $orderData['items'] ?? [];

        // Prepare payment data
        $paymentData = [
            'orderCode' => $orderCode,
            'amount' => $amount,
            'description' => $description,
            'buyerName' => $buyerName,
            'buyerEmail' => $buyerEmail,
            'buyerPhone' => $buyerPhone,
            'buyerAddress' => $orderData['buyer_address'] ?? '',
            'items' => $items,
            'cancelUrl' => $this->config['cancel_url'],
            'returnUrl' => $this->config['return_url'],
            'expiredAt' => time() + (15 * 60), // 15 minutes expiration
            'signature' => ''
        ];

        // Generate signature/checksum
        $paymentData['signature'] = $this->generateSignature($paymentData);

        // Make API request
        $response = $this->makeRequest('/v2/payment-requests', 'POST', $paymentData);

        if ($response && isset($response['data'])) {
            return $response['data'];
        }

        return null;
    }

    /**
     * Get payment link information
     * @param int $orderCode - Order code
     * @return array|null - Payment information or null
     */
    public function getPaymentLinkInfo($orderCode)
    {
        if (empty($this->config['api_key'])) {
            error_log('PayOS: Missing API key');
            return null;
        }

        $response = $this->makeRequest('/v2/payment-requests/' . $orderCode, 'GET');
        
        if ($response && isset($response['data'])) {
            return $response['data'];
        }

        return null;
    }

    /**
     * Cancel a payment link
     * @param int $orderCode - Order code
     * @return bool - Success status
     */
    public function cancelPaymentLink($orderCode)
    {
        if (empty($this->config['api_key'])) {
            error_log('PayOS: Missing API key');
            return false;
        }

        $response = $this->makeRequest('/v2/payment-requests/' . $orderCode, 'DELETE');
        
        return $response && isset($response['data']);
    }

    /**
     * Verify webhook signature
     * @param array $data - Webhook data
     * @param string $signature - Received signature
     * @return bool - Verification result
     */
    public function verifyWebhookSignature($data, $signature)
    {
        $computedSignature = $this->generateSignature($data);
        return hash_equals($computedSignature, $signature);
    }

    /**
     * Generate signature for payment data
     * @param array $data - Data to sign
     * @return string - Generated signature
     */
    private function generateSignature($data)
    {
        // Create signature string according to PayOS specification
        $signatureData = [
            'amount' => $data['amount'] ?? 0,
            'cancelUrl' => $data['cancelUrl'] ?? '',
            'description' => $data['description'] ?? '',
            'orderCode' => $data['orderCode'] ?? 0,
            'returnUrl' => $data['returnUrl'] ?? ''
        ];

        ksort($signatureData);
        $dataStr = http_build_query($signatureData);
        
        return hash_hmac('sha256', $dataStr, $this->config['checksum_key']);
    }

    /**
     * Make HTTP request to PayOS API
     * @param string $endpoint - API endpoint
     * @param string $method - HTTP method
     * @param array $data - Request data
     * @return array|null - Response data or null
     */
    private function makeRequest($endpoint, $method = 'GET', $data = [])
    {
        $url = $this->apiUrl . $endpoint;
        
        $headers = [
            'Content-Type: application/json',
            'x-client-id: ' . $this->config['client_id'],
            'x-api-key: ' . $this->config['api_key']
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        } elseif ($method === 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            error_log("PayOS API Error: $error");
            return null;
        }

        if ($httpCode >= 200 && $httpCode < 300) {
            return json_decode($response, true);
        }

        error_log("PayOS API returned HTTP $httpCode: $response");
        return null;
    }

    /**
     * Verify payment return data
     * @param array $returnData - Data from return URL
     * @return bool - Verification result
     */
    public function verifyReturnData($returnData)
    {
        if (!isset($returnData['orderCode']) || !isset($returnData['status'])) {
            return false;
        }

        // Get payment info from PayOS to verify
        $paymentInfo = $this->getPaymentLinkInfo($returnData['orderCode']);
        
        if (!$paymentInfo) {
            return false;
        }

        return $paymentInfo['status'] === 'PAID';
    }
}
