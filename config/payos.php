<?php

/**
 * PayOS Configuration
 * Get your credentials from: https://my.payos.vn/
 */

return [
    // Client ID from PayOS dashboard
    'client_id' => getenv('PAYOS_CLIENT_ID') ?: 'e02b07a5-58a4-42e1-b11a-ab66193adabc',
    
    // API Key from PayOS dashboard
    'api_key' => getenv('PAYOS_API_KEY') ?: '32cb82f3-0b69-4821-970b-830ea14dfe1e',
    
    // Checksum Key for signature verification
    'checksum_key' => getenv('PAYOS_CHECKSUM_KEY') ?: '517f96d9707845c1eb30244cf2983f3895e22466ebbe322a02243cd86ede981e',
    
    // Environment: 'production' or 'sandbox'
    'environment' => getenv('PAYOS_ENV') ?: 'sandbox',
    
    // Return URL after successful payment
    'return_url' => 'http://localhost:8080/index.php?controller=checkout&action=payos_return',
    
    // Cancel URL when user cancels payment
    'cancel_url' => 'http://localhost:8080/index.php?controller=checkout&action=payos_cancel',
    
    // Webhook URL for payment notifications (cần expose ra internet)
    'webhook_url' => 'http://localhost:8080/index.php?controller=checkout&action=payos_webhook',

    // Currency used by PayOS (expects integer, typically VND)
    'currency' => getenv('PAYOS_CURRENCY') ?: 'VND',

    // Exchange rate to convert site USD amounts to VND for PayOS
    // Adjust to current rate or set via env `PAYOS_USD_TO_VND`
    'usd_to_vnd' => (int)(getenv('PAYOS_USD_TO_VND') ?: 25000),
];
