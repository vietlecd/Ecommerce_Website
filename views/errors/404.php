<?php
http_response_code(404);
header('Content-Type: application/json');
echo json_encode([
    'success' => false,
    'error' => 'Not Found',
    'message' => 'The requested resource was not found'
], JSON_UNESCAPED_UNICODE);
exit;

