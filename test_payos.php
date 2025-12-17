<?php
/**
 * PayOS Testing File
 * File này để test các chức năng của PayOS một cách độc lập
 * Chạy file này trực tiếp qua browser hoặc CLI
 */

// Load PayOS Service
require_once __DIR__ . '/models/PayOSService.php';

// Set timezone
date_default_timezone_set('Asia/Ho_Chi_Minh');

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PayOS Test - Kiểm Tra Tích Hợp PayOS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        h1 {
            color: white;
            text-align: center;
            margin-bottom: 30px;
            font-size: 2.5em;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        .test-section {
            background: white;
            border-radius: 10px;
            padding: 25px;
            margin-bottom: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        .test-section h2 {
            color: #667eea;
            margin-bottom: 15px;
            border-bottom: 2px solid #667eea;
            padding-bottom: 10px;
        }
        .btn {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            transition: transform 0.2s;
            margin: 5px;
        }
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        .btn-danger {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }
        .btn-success {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
        }
        .result {
            margin-top: 15px;
            padding: 15px;
            border-radius: 5px;
            background: #f8f9fa;
            border-left: 4px solid #667eea;
        }
        .success {
            background: #d4edda;
            border-left-color: #28a745;
            color: #155724;
        }
        .error {
            background: #f8d7da;
            border-left-color: #dc3545;
            color: #721c24;
        }
        .info {
            background: #d1ecf1;
            border-left-color: #17a2b8;
            color: #0c5460;
        }
        pre {
            background: #2d3748;
            color: #68d391;
            padding: 15px;
            border-radius: 5px;
            overflow-x: auto;
            margin-top: 10px;
            font-size: 12px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        input[type="text"],
        input[type="number"],
        textarea {
            width: 100%;
            padding: 10px;
            border: 2px solid #e2e8f0;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        input:focus, textarea:focus {
            outline: none;
            border-color: #667eea;
        }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .config-info {
            background: #fff3cd;
            border: 1px solid #ffc107;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .config-info h3 {
            color: #856404;
            margin-bottom: 10px;
        }
        .config-item {
            margin: 5px 0;
            font-size: 14px;
        }
        .config-item strong {
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🧪 PayOS Testing Dashboard</h1>

        <?php
        // Initialize PayOS Service
        try {
            $payos = new PayOSService();
            $configPath = __DIR__ . '/config/payos.php';
            $config = require $configPath;
            ?>
            
            <div class="config-info">
                <h3>📋 Thông Tin Cấu Hình PayOS</h3>
                <div class="config-item"><strong>Client ID:</strong> <?php echo htmlspecialchars($config['client_id']); ?></div>
                <div class="config-item"><strong>API Key:</strong> <?php echo substr($config['api_key'], 0, 10) . '...'; ?></div>
                <div class="config-item"><strong>Environment:</strong> <?php echo htmlspecialchars($config['environment']); ?></div>
                <div class="config-item"><strong>Return URL:</strong> <?php echo htmlspecialchars($config['return_url']); ?></div>
                <div class="config-item"><strong>USD to VND:</strong> <?php echo number_format($config['usd_to_vnd']); ?></div>
            </div>

        <?php } catch (Exception $e) { ?>
            <div class="result error">
                <strong>❌ Lỗi khởi tạo PayOS Service:</strong><br>
                <?php echo htmlspecialchars($e->getMessage()); ?>
            </div>
            <?php
            die();
        }
        ?>

        <!-- Test 1: Create Payment Link -->
        <div class="test-section">
            <h2>1️⃣ Tạo Link Thanh Toán (Create Payment Link)</h2>
            <form method="POST" action="">
                <input type="hidden" name="action" value="create_payment">
                <div class="grid">
                    <div class="form-group">
                        <label for="order_code">Mã đơn hàng (Order Code):</label>
                        <input type="number" id="order_code" name="order_code" value="<?php echo time(); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="amount">Số tiền (VND):</label>
                        <input type="number" id="amount" name="amount" value="10000" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="description">Mô tả:</label>
                    <input type="text" id="description" name="description" value="Test thanh toán PayOS" required>
                </div>
                <div class="form-group">
                    <label for="buyer_name">Tên người mua:</label>
                    <input type="text" id="buyer_name" name="buyer_name" value="Nguyễn Văn A">
                </div>
                <button type="submit" class="btn">🚀 Tạo Link Thanh Toán</button>
            </form>

            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'create_payment') {
                $orderCode = (int)$_POST['order_code'];
                $amount = (int)$_POST['amount'];
                $description = $_POST['description'];
                $buyerName = $_POST['buyer_name'] ?? '';

                $orderData = [
                    'orderCode' => $orderCode,
                    'amount' => $amount,
                    'description' => $description,
                    'returnUrl' => 'http://localhost:8080/test_payos.php?action=payment_return',
                    'cancelUrl' => 'http://localhost:8080/test_payos.php?action=payment_cancel',
                    'items' => [
                        [
                            'name' => 'Sản phẩm test',
                            'quantity' => 1,
                            'price' => $amount
                        ]
                    ]
                ];

                if (!empty($buyerName)) {
                    $orderData['buyerName'] = $buyerName;
                }

                $result = $payos->createPaymentLink($orderData);

                if ($result['success']) {
                    $data = $result['data'];
                    
                    // PayOS response có thể có structure: {code, desc, data: {...}} hoặc trực tiếp {...}
                    // Kiểm tra nếu có nested data object
                    if (isset($data['data'])) {
                        $paymentData = $data['data'];
                    } else {
                        $paymentData = $data;
                    }
                    
                    echo '<div class="result success">';
                    echo '<h3 style="color: #28a745; margin-bottom: 15px;">✅ Tạo Link Thanh Toán Thành Công!</h3>';
                    
                    // Display key information
                    if (isset($paymentData['orderCode']) || isset($paymentData['amount']) || isset($paymentData['status'])) {
                        echo '<div style="background: white; padding: 15px; border-radius: 5px; margin: 15px 0;">';
                        if (isset($paymentData['orderCode'])) {
                            echo '<p><strong>Mã đơn hàng:</strong> ' . htmlspecialchars($paymentData['orderCode']) . '</p>';
                        }
                        if (isset($paymentData['amount'])) {
                            echo '<p><strong>Số tiền:</strong> ' . number_format($paymentData['amount']) . ' VND</p>';
                        }
                        if (isset($paymentData['status'])) {
                            echo '<p><strong>Trạng thái:</strong> <span style="color: #ffc107; font-weight: bold;">' . htmlspecialchars($paymentData['status']) . '</span></p>';
                        }
                        echo '</div>';
                    }
                    
                    // QR Code Display
                    if (isset($paymentData['qrCode'])) {
                        echo '<div style="background: white; padding: 20px; border-radius: 10px; text-align: center; margin: 20px 0; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">';
                        echo '<h4 style="color: #667eea; margin-bottom: 15px;">📱 Quét Mã QR Để Thanh Toán</h4>';
                        echo '<img src="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=' . urlencode($paymentData['qrCode']) . '" alt="QR Code" style="max-width: 300px; border: 2px solid #e2e8f0; border-radius: 10px; padding: 10px;">';
                        echo '<p style="margin-top: 10px; color: #666; font-size: 14px;">Mở app ngân hàng và quét mã QR này</p>';
                        echo '</div>';
                    }
                    
                    // Payment Button
                    if (isset($paymentData['checkoutUrl'])) {
                        echo '<div style="text-align: center; margin: 20px 0;">';
                        echo '<a href="' . htmlspecialchars($paymentData['checkoutUrl']) . '" target="_blank" class="btn btn-success" style="font-size: 18px; padding: 15px 40px; text-decoration: none; display: inline-block;">💳 THANH TOÁN NGAY</a>';
                        echo '<p style="margin-top: 10px; color: #666; font-size: 14px;">Click vào nút trên để mở trang thanh toán PayOS</p>';
                        echo '</div>';
                    }
                    
                    // Raw JSON data (collapsible)
                    echo '<details style="margin-top: 20px;">';
                    echo '<summary style="cursor: pointer; font-weight: bold; padding: 10px; background: #f8f9fa; border-radius: 5px;">📋 Xem dữ liệu JSON đầy đủ</summary>';
                    echo '<pre style="margin-top: 10px;">' . json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</pre>';
                    echo '</details>';
                    
                    echo '</div>';
                } else {
                    echo '<div class="result error">';
                    echo '<strong>❌ Lỗi tạo link thanh toán:</strong><br>';
                    echo htmlspecialchars($result['error']);
                    echo '</div>';
                }
            }
            ?>
        </div>

        <!-- Test 2: Get Payment Info -->
        <div class="test-section">
            <h2>2️⃣ Tra Cứu Thông Tin Thanh Toán (Get Payment Info)</h2>
            <form method="POST" action="">
                <input type="hidden" name="action" value="get_payment">
                <div class="form-group">
                    <label for="get_order_code">Mã đơn hàng cần tra cứu:</label>
                    <input type="number" id="get_order_code" name="get_order_code" placeholder="Nhập mã đơn hàng" required>
                </div>
                <button type="submit" class="btn btn-success">🔍 Tra Cứu</button>
            </form>

            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'get_payment') {
                $orderCode = (int)$_POST['get_order_code'];
                $result = $payos->getPaymentInfo($orderCode);

                if ($result['success']) {
                    echo '<div class="result success">';
                    echo '<strong>✅ Lấy thông tin thanh toán thành công!</strong>';
                    echo '<pre>' . json_encode($result['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</pre>';
                    echo '</div>';
                } else {
                    echo '<div class="result error">';
                    echo '<strong>❌ Lỗi lấy thông tin:</strong><br>';
                    echo htmlspecialchars($result['error']);
                    echo '</div>';
                }
            }
            ?>
        </div>

        <!-- Test 3: Cancel Payment -->
        <div class="test-section">
            <h2>3️⃣ Hủy Thanh Toán (Cancel Payment)</h2>
            <form method="POST" action="">
                <input type="hidden" name="action" value="cancel_payment">
                <div class="form-group">
                    <label for="cancel_order_code">Mã đơn hàng cần hủy:</label>
                    <input type="number" id="cancel_order_code" name="cancel_order_code" placeholder="Nhập mã đơn hàng" required>
                </div>
                <div class="form-group">
                    <label for="cancel_reason">Lý do hủy:</label>
                    <textarea id="cancel_reason" name="cancel_reason" rows="3" placeholder="Nhập lý do hủy (optional)"></textarea>
                </div>
                <button type="submit" class="btn btn-danger">❌ Hủy Thanh Toán</button>
            </form>

            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'cancel_payment') {
                $orderCode = (int)$_POST['cancel_order_code'];
                $reason = !empty($_POST['cancel_reason']) ? $_POST['cancel_reason'] : null;
                $result = $payos->cancelPayment($orderCode, $reason);

                if ($result['success']) {
                    echo '<div class="result success">';
                    echo '<strong>✅ Hủy thanh toán thành công!</strong>';
                    echo '<pre>' . json_encode($result['data'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</pre>';
                    echo '</div>';
                } else {
                    echo '<div class="result error">';
                    echo '<strong>❌ Lỗi hủy thanh toán:</strong><br>';
                    echo htmlspecialchars($result['error']);
                    echo '</div>';
                }
            }
            ?>
        </div>

        <!-- Return Handler -->
        <?php
        if (isset($_GET['action']) && $_GET['action'] === 'payment_return') {
            echo '<div class="test-section">';
            echo '<h2>✅ Thanh Toán Thành Công</h2>';
            echo '<div class="result success">';
            echo '<strong>Cảm ơn bạn đã thanh toán!</strong><br>';
            echo 'Thông tin callback từ PayOS:<br>';
            echo '<pre>' . json_encode($_GET, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</pre>';
            echo '</div>';
            echo '</div>';
        }

        if (isset($_GET['action']) && $_GET['action'] === 'payment_cancel') {
            echo '<div class="test-section">';
            echo '<h2>❌ Thanh Toán Bị Hủy</h2>';
            echo '<div class="result error">';
            echo '<strong>Bạn đã hủy giao dịch thanh toán.</strong><br>';
            echo 'Thông tin callback từ PayOS:<br>';
            echo '<pre>' . json_encode($_GET, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</pre>';
            echo '</div>';
            echo '</div>';
        }
        ?>

        <!-- Test Info -->
        <div class="test-section">
            <h2>📖 Hướng Dẫn Sử Dụng</h2>
            <div class="result info">
                <h3>Các bước test PayOS:</h3>
                <ol style="margin-left: 20px; margin-top: 10px;">
                    <li><strong>Tạo Link Thanh Toán:</strong> Nhập thông tin đơn hàng và nhấn "Tạo Link Thanh Toán". Sau đó click vào nút "Thanh Toán Ngay" để mở cổng thanh toán.</li>
                    <li><strong>Tra Cứu Thông Tin:</strong> Nhập mã đơn hàng để xem trạng thái và thông tin chi tiết thanh toán.</li>
                    <li><strong>Hủy Thanh Toán:</strong> Nhập mã đơn hàng để hủy giao dịch thanh toán (chỉ với đơn hàng chưa thanh toán).</li>
                </ol>
                <br>
                <h3>Lưu ý:</h3>
                <ul style="margin-left: 20px; margin-top: 10px;">
                    <li>Đảm bảo đã cấu hình đúng thông tin Client ID, API Key, Checksum Key trong file <code>config/payos.php</code></li>
                    <li>Mã đơn hàng (Order Code) phải là số nguyên duy nhất</li>
                    <li>Số tiền phải >= 2000 VND</li>
                    <li>File này chỉ dùng để test, không dùng trong production</li>
                </ul>
            </div>
        </div>

        <!-- Quick Test -->
        <div class="test-section">
            <h2>⚡ Quick Test</h2>
            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="action" value="create_payment">
                    <input type="hidden" name="order_code" value="<?php echo time(); ?>">
                    <input type="hidden" name="amount" value="10000">
                    <input type="hidden" name="description" value="Quick Test - 10,000 VND">
                    <input type="hidden" name="buyer_name" value="Test User">
                    <button type="submit" class="btn">💳 Test 10,000 VND</button>
                </form>
                
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="action" value="create_payment">
                    <input type="hidden" name="order_code" value="<?php echo time() + 1; ?>">
                    <input type="hidden" name="amount" value="50000">
                    <input type="hidden" name="description" value="Quick Test - 50,000 VND">
                    <input type="hidden" name="buyer_name" value="Test User">
                    <button type="submit" class="btn">💳 Test 50,000 VND</button>
                </form>
                
                <form method="POST" style="display: inline;">
                    <input type="hidden" name="action" value="create_payment">
                    <input type="hidden" name="order_code" value="<?php echo time() + 2; ?>">
                    <input type="hidden" name="amount" value="100000">
                    <input type="hidden" name="description" value="Quick Test - 100,000 VND">
                    <input type="hidden" name="buyer_name" value="Test User">
                    <button type="submit" class="btn">💳 Test 100,000 VND</button>
                </form>
            </div>
        </div>

    </div>
</body>
</html>
