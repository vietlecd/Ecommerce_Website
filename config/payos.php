<?php
// PayOS Configuration
// Get credentials from environment variables or use sandbox defaults

return [
    'client_id' => $_ENV['PAYOS_CLIENT_ID'] ?? '',
    'api_key' => $_ENV['PAYOS_API_KEY'] ?? '',
    'checksum_key' => $_ENV['PAYOS_CHECKSUM_KEY'] ?? '',
    'sandbox' => $_ENV['PAYOS_SANDBOX'] ?? true,
    'return_url' => $_ENV['PAYOS_RETURN_URL'] ?? 'http://localhost:8080/index.php?controller=checkout&action=paymentReturn',
    'cancel_url' => $_ENV['PAYOS_CANCEL_URL'] ?? 'http://localhost:8080/index.php?controller=checkout&action=paymentCancel',
    'webhook_url' => $_ENV['PAYOS_WEBHOOK_URL'] ?? 'http://localhost:8080/index.php?controller=checkout&action=paymentWebhook'
];
