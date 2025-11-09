<?php

class ChatController {
    
    public function webhook()
    {
        header('Content-Type: text/html; charset=utf-8');
        
        $input = file_get_contents('php://input');
        $data = json_decode($input, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            $data = $_POST;
        }
        
        if (empty($data)) {
            echo '<div style="padding: 10px; color: #666;">Không có dữ liệu</div>';
            exit;
        }
        
        $this->renderProductResponse($data);
        exit;
    }
    
    private function renderProductResponse($data)
    {
        $p = $data['p'] ?? '';
        $h2 = $data['h2'] ?? '';
        $items = $data['items'] ?? [];
        $note = $data['note'] ?? '';
        
        echo '<div style="padding: 8px; font-size: 13px; line-height: 1.4; color: #333;">';
        
        if (!empty($p)) {
            echo '<div style="margin-bottom: 8px; color: #555;">' . htmlspecialchars($p) . '</div>';
        }
        
        if (!empty($h2)) {
            echo '<h2 style="margin: 8px 0; font-size: 16px; font-weight: 600; color: #ff6b6b;">' . htmlspecialchars($h2) . '</h2>';
        }
        
        if (!empty($items) && is_array($items)) {
            echo '<div style="display: flex; flex-direction: column; gap: 8px; margin-top: 10px;">';
            
            foreach ($items as $item) {
                $h3 = $item['h3'] ?? '';
                $img = $item['img'] ?? '';
                $price = $item['price'] ?? '';
                $size = $item['size'] ?? '';
                $stock = $item['stock'] ?? '';
                $desc = $item['desc'] ?? '';
                $link = $item['link'] ?? '';
                
                echo '<div style="border: 1px solid #e0e0e0; border-radius: 6px; padding: 8px; background: #f9f9f9; display: flex; gap: 8px;">';
                
                if (!empty($img)) {
                    echo '<div style="flex-shrink: 0; width: 60px; height: 60px; overflow: hidden; border-radius: 4px;">';
                    echo '<img src="' . htmlspecialchars($img) . '" alt="' . htmlspecialchars($h3) . '" style="width: 100%; height: 100%; object-fit: cover;">';
                    echo '</div>';
                }
                
                echo '<div style="flex: 1; min-width: 0;">';
                
                if (!empty($h3)) {
                    echo '<div style="font-weight: 600; font-size: 14px; margin-bottom: 4px; color: #333;">' . htmlspecialchars($h3) . '</div>';
                }
                
                if (!empty($desc)) {
                    echo '<div style="font-size: 12px; color: #666; margin-bottom: 4px;">' . htmlspecialchars($desc) . '</div>';
                }
                
                echo '<div style="display: flex; gap: 8px; flex-wrap: wrap; font-size: 11px; color: #888; margin-bottom: 4px;">';
                if (!empty($price)) {
                    echo '<span><strong style="color: #ff6b6b;">' . htmlspecialchars($price) . '</strong></span>';
                }
                if (!empty($size)) {
                    echo '<span>Size: ' . htmlspecialchars($size) . '</span>';
                }
                if (!empty($stock)) {
                    echo '<span>Còn: ' . htmlspecialchars($stock) . '</span>';
                }
                echo '</div>';
                
                if (!empty($link)) {
                    echo '<a href="' . htmlspecialchars($link) . '" target="_blank" style="display: inline-block; padding: 4px 8px; background: #ff6b6b; color: white; text-decoration: none; border-radius: 4px; font-size: 11px; margin-top: 4px;">Xem chi tiết</a>';
                }
                
                echo '</div>';
                echo '</div>';
            }
            
            echo '</div>';
        }
        
        if (!empty($note)) {
            echo '<div style="margin-top: 10px; padding: 8px; background: #e8f4f8; border-radius: 4px; font-size: 12px; color: #555;">' . htmlspecialchars($note) . '</div>';
        }
        
        echo '</div>';
    }
    
    public function api()
    {
        header('Content-Type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type');
        
        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(200);
            exit;
        }
        
        $chatInput = isset($_GET['chatInput']) ? trim($_GET['chatInput']) : '';
        
        if (empty($chatInput)) {
            http_response_code(200);
            echo json_encode([
                'success' => false,
                'error' => 'Invalid request',
                'message' => 'chatInput parameter is required'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        $webhookUrl = 'https://agqiom.ezn8n.com/webhook/8b29fa41-a30e-4fd0-9710-f4e589eb2e97?chatInput=' . urlencode($chatInput);
        
        $ch = curl_init($webhookUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Accept: application/json',
            'Content-Type: application/json',
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);
        
        if ($curlError) {
            http_response_code(200);
            echo json_encode([
                'success' => false,
                'error' => 'Connection error',
                'message' => $curlError
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        if ($httpCode !== 200) {
            http_response_code(200);
            $errorData = null;
            if (!empty($response)) {
                $errorData = json_decode($response, true);
            }
            
            echo json_encode([
                'success' => false,
                'error' => 'API error',
                'http_code' => $httpCode,
                'message' => $errorData && isset($errorData['message']) ? $errorData['message'] : 'API returned error code ' . $httpCode,
                'response' => $response
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        if (empty($response)) {
            http_response_code(200);
            echo json_encode([
                'success' => false,
                'error' => 'Empty response from API',
                'message' => 'API returned empty response'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        $data = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(200);
            echo json_encode([
                'success' => false,
                'error' => 'Invalid JSON response',
                'message' => 'API returned invalid JSON: ' . json_last_error_msg(),
                'raw_response' => substr($response, 0, 500)
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
        
        if (isset($data['output'])) {
            if (is_string($data['output'])) {
                $outputData = json_decode($data['output'], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $data = $outputData;
                } else {
                    $data = ['p' => $data['output'], 'items' => []];
                }
            } elseif (is_array($data['output'])) {
                $data = $data['output'];
            }
        }
        
        echo json_encode([
            'success' => true,
            'data' => $data
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

