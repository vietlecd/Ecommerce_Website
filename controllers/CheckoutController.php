<?php
require_once __DIR__ . '/../models/OrderModel.php';
require_once __DIR__ . '/../models/ProductModelv2.php';
require_once __DIR__ . '/../models/CouponModel.php';
require_once __DIR__ . '/../models/MemberModel.php';
require_once __DIR__ . '/../models/PayOSService.php';

class CheckoutController {
    private $orderModel;
    private $productModel;
    private $couponModel;
    private $memberModel;
    private $payosService;
    private $addressSessionKey;

    public function __construct() {
        $this->orderModel = new OrderModel();
        $this->productModel = new ProductModel();
        $this->couponModel = new CouponModel();
        $this->memberModel = new MemberModel();
        $this->payosService = new PayOSService();

        $userKey = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : 'guest';
        $this->addressSessionKey = 'saved_addresses_user_' . $userKey;
    }

    public function index() {
        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            header('Location: /index.php?controller=cart&action=index');
            exit;
        }

        $success = '';
        $error = '';
        $cartItems = [];
        $subtotal = 0;
        $totalQuantity = 0;

        foreach ($_SESSION['cart'] as $item) {
            $lineTotal = $item['price'] * $item['quantity'];
            $subtotal += $lineTotal;
            $totalQuantity += $item['quantity'];
            $cartItems[] = [
                'id'       => $item['id'],
                'name'     => $item['name'],
                'price'    => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $lineTotal
            ];
        }

        $shipping = 10.00;
        $availableCoupons = $this->couponModel->getActiveCoupons();
        $memberProfile = null;
        $prefillName = '';
        $prefillEmail = '';
        $prefillAddress = '';
        $prefillCity = '';
        $prefillZip = '';
        $prefillPhone = '';

        $prefillFromSession = isset($_SESSION['checkout_prefill']) && is_array($_SESSION['checkout_prefill'])
            ? $_SESSION['checkout_prefill']
            : null;

        $savedAddresses = isset($_SESSION[$this->addressSessionKey]) && is_array($_SESSION[$this->addressSessionKey])
            ? $_SESSION[$this->addressSessionKey]
            : [];

        $addressNotice = isset($_SESSION['address_notice']) ? $_SESSION['address_notice'] : '';
        unset($_SESSION['address_notice']);

        $couponNotice = isset($_SESSION['coupon_notice']) ? $_SESSION['coupon_notice'] : '';
        unset($_SESSION['coupon_notice']);

        // Surface checkout error/success messages coming from PayOS redirects
        if (isset($_SESSION['checkout_error']) && is_string($_SESSION['checkout_error'])) {
            $error = $_SESSION['checkout_error'];
            unset($_SESSION['checkout_error']);
        }
        if (isset($_SESSION['checkout_success']) && is_string($_SESSION['checkout_success'])) {
            $success = $_SESSION['checkout_success'];
            unset($_SESSION['checkout_success']);
        }

        if (isset($_SESSION['user_id'])) {
            $memberProfile = $this->memberModel->getMemberById((int) $_SESSION['user_id']);
            if ($memberProfile) {
                $prefillName    = $memberProfile['Name'] ?? ($memberProfile['Username'] ?? '');
                $prefillEmail   = $memberProfile['Email'] ?? '';
                $prefillAddress = $memberProfile['Address'] ?? '';
                $prefillCity    = $memberProfile['City'] ?? '';
                $prefillZip     = $memberProfile['Zip'] ?? ($memberProfile['ZIP'] ?? '');
                $prefillPhone   = $memberProfile['Phone'] ?? ($memberProfile['PhoneNumber'] ?? '');
            }
        }

        if ($prefillFromSession) {
            $prefillName    = $prefillFromSession['name']    ?? $prefillName;
            $prefillEmail   = $prefillFromSession['email']   ?? $prefillEmail;
            $prefillAddress = $prefillFromSession['address'] ?? $prefillAddress;
            $prefillCity    = $prefillFromSession['city']    ?? $prefillCity;
            $prefillZip     = $prefillFromSession['zip']     ?? $prefillZip;
            $prefillPhone   = $prefillFromSession['phone']   ?? $prefillPhone;
        }

        // Áp coupon
        if (isset($_POST['apply_coupon']) && array_key_exists('selected_coupon', $_POST)) {
            $couponId = (int) $_POST['selected_coupon'];

            $_SESSION['checkout_prefill'] = [
                'name'    => trim($_POST['name']    ?? ''),
                'email'   => trim($_POST['email']   ?? ''),
                'address' => trim($_POST['address'] ?? ''),
                'city'    => trim($_POST['city']    ?? ''),
                'zip'     => trim($_POST['zip']     ?? ''),
                'phone'   => trim($_POST['phone']   ?? ''),
            ];

            if ($couponId > 0) {
                $coupon = $this->couponModel->getCouponById($couponId);
                if ($coupon) {
                    $_SESSION['cart_coupon']  = $couponId;
                    $_SESSION['coupon_notice'] = 'Coupon has been applied.';
                } else {
                    unset($_SESSION['cart_coupon']);
                    $_SESSION['coupon_notice'] = 'Coupon is invalid or has expired.';
                }
            } else {
                unset($_SESSION['cart_coupon']);
                $_SESSION['coupon_notice'] = 'Coupon has been removed.';
            }

            header('Location: /index.php?controller=checkout&action=index');
            exit;
        }

        $discountAmount = 0;
        $appliedCoupon  = null;

        if (!empty($_SESSION['cart_coupon'])) {
            $coupon = $this->couponModel->getCouponById((int) $_SESSION['cart_coupon']);
            if ($coupon) {
                $appliedCoupon = $coupon;
                $percent = is_numeric($coupon['CodePercent']) ? (float)$coupon['CodePercent'] : 0;
                if ($percent > 0) {
                    $discountAmount = min($subtotal, $subtotal * ($percent / 100));
                }
            } else {
                unset($_SESSION['cart_coupon']);
            }
        }

        $availableCoupons = $this->couponModel->getActiveCoupons();
        $total = max(0, $subtotal - $discountAmount + $shipping);
        $earnedVip = isset($_SESSION['earned_vip']) && is_numeric($_SESSION['earned_vip'])
            ? (float) $_SESSION['earned_vip']
            : 0.00;

        // Lưu địa chỉ
        if (isset($_POST['save_address'])) {
            $saveName    = trim($_POST['name']    ?? '');
            $saveEmail   = trim($_POST['email']   ?? '');
            $saveAddress = trim($_POST['address'] ?? '');
            $saveCity    = trim($_POST['city']    ?? '');
            $saveZip     = trim($_POST['zip']     ?? '');
            $savePhone   = trim($_POST['phone']   ?? '');

            if ($saveName && $saveEmail && $saveAddress && $saveCity && $saveZip && $savePhone) {
                $savedAddresses[] = [
                    'id'      => uniqid('addr_', true),
                    'name'    => $saveName,
                    'email'   => $saveEmail,
                    'address' => $saveAddress,
                    'city'    => $saveCity,
                    'zip'     => $saveZip,
                    'phone'   => $savePhone,
                ];
                $savedAddresses = array_slice($savedAddresses, -5);
                $_SESSION[$this->addressSessionKey] = $savedAddresses;
                $_SESSION['address_notice'] = 'Address has been saved to your address book.';
                header('Location: /index.php?controller=checkout&action=index');
                exit;
            } else {
                $error = 'Please fill in all required fields before saving the address.';
            }
        }

        // Xóa địa chỉ lưu
        if (isset($_POST['delete_address'])) {
            $deleteId = $_POST['delete_address'];
            $savedAddresses = array_values(array_filter($savedAddresses, function ($addr) use ($deleteId) {
                return isset($addr['id']) && $addr['id'] !== $deleteId;
            }));
            $_SESSION[$this->addressSessionKey] = $savedAddresses;
            $_SESSION['address_notice'] = 'Saved address has been deleted.';
            header('Location: /index.php?controller=checkout&action=index');
            exit;
        }

        // PLACE ORDER
        if (isset($_POST['place_order'])) {
            $paymentMethod = $_POST['payment_method'] ?? 'cod';
            $name    = trim($_POST['name']    ?? '');
            $email   = trim($_POST['email']   ?? '');
            $address = trim($_POST['address'] ?? '');
            $city    = trim($_POST['city']    ?? '');
            $zip     = trim($_POST['zip']     ?? '');
            $phone   = trim($_POST['phone']   ?? '');
            $card_number = trim($_POST['card_number'] ?? '');
            $expiry      = trim($_POST['expiry']      ?? '');
            $cvv         = trim($_POST['cvv']         ?? '');

            if (empty($name) || empty($email) || empty($address) || empty($city) || empty($zip) || empty($phone)) {
                $error = 'All required fields must be filled.';
            } elseif ($paymentMethod === 'card' && (empty($card_number) || empty($expiry) || empty($cvv))) {
                $error = 'Card payment is not available yet. Please choose Cash on Delivery.';
            } elseif ($paymentMethod === 'payos') {
                // Handle PayOS payment
                $this->processPayOSPayment($name, $email, $phone, $address, $city, $zip, $total, $totalQuantity, $cartItems);
                exit;
            } else {
                // member/guest
                if (isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id']) && (int)$_SESSION['user_id'] > 0) {
                    $memberId = (int)$_SESSION['user_id'];
                } else {
                    $memberId = null; // cho phép guest checkout, MemberID = NULL
                }

                $shippingData = [
                    'name'           => $name,
                    'email'          => $email,
                    'address'        => $address,
                    'city'           => $city,
                    'zip'            => $zip,
                    'phone'          => $phone,
                    'payment_method' => $paymentMethod,
                ];

                $orderId = $this->orderModel->addOrder($memberId, $total, $totalQuantity, $shippingData);

                if ($orderId) {
                    // lưu chi tiết order_shoes
                    $cartStockItems = [];

                    foreach ($_SESSION['cart'] as $item) {
                        // chèn từng đôi vào order_shoes
                        for ($i = 0; $i < $item['quantity']; $i++) {
                            $this->orderModel->addOrderShoes($orderId, $item['id']);
                        }

                        // chuẩn bị dữ liệu trừ tồn theo size
                        if (!empty($item['size'])) {
                            $cartStockItems[] = [
                                'product_id' => (int)$item['id'],
                                'size'       => $item['size'],
                                'quantity'   => (int)$item['quantity'],
                            ];
                        }
                    }

                    // trừ stock trong shoe_sizes + cập nhật shoes.Stock
                    if (!empty($cartStockItems)) {
                        $stockOK = $this->productModel->decrementStockForCartItems($cartStockItems);
                        if (!$stockOK) {
                            // nếu cần thì log hoặc show warn, ở đây chỉ báo nhẹ
                            $error = 'Order placed but stock could not be updated correctly for some items.';
                        }
                    }

                    unset($_SESSION['cart'], $_SESSION['cart_coupon'], $_SESSION['checkout_prefill']);
                    $_SESSION['earned_vip'] = $earnedVip;

                    $success = "Order placed successfully! Your order ID is #$orderId.";
                    header('Refresh: 3; URL=/index.php?controller=home&action=index');
                } else {
                    $error = 'Failed to place the order. Please try again.';
                }
            }
        }

        $headerPath = dirname(__DIR__) . '/views/components/header.php';
        $viewPath   = dirname(__DIR__) . '/views/pages/checkout.php';
        $footerPath = dirname(__DIR__) . '/views/components/footer.php';

        if (file_exists($headerPath)) {
            require_once $headerPath;
        } else {
            die("Header file not found: $headerPath");
        }

        if (file_exists($viewPath)) {
            $renderView = function (
                $cartItems,
                $subtotal,
                $shipping,
                $discountAmount,
                $appliedCoupon,
                $availableCoupons,
                $total,
                $error,
                $success,
                $prefillName,
                $prefillEmail,
                $prefillAddress,
                $prefillCity,
                $prefillZip,
                $prefillPhone,
                $savedAddresses,
                $addressNotice,
                $couponNotice
            ) use ($viewPath) {
                require $viewPath;
            };

            $renderView(
                $cartItems,
                $subtotal,
                $shipping,
                $discountAmount,
                $appliedCoupon,
                $availableCoupons,
                $total,
                $error,
                $success,
                $prefillName,
                $prefillEmail,
                $prefillAddress,
                $prefillCity,
                $prefillZip,
                $prefillPhone,
                $savedAddresses,
                $addressNotice,
                $couponNotice
            );
        } else {
            die("View file not found: $viewPath");
        }

        if (file_exists($footerPath)) {
            require_once $footerPath;
        } else {
            die("Footer file not found: $footerPath");
        }
    }
    
    /**
     * Process PayOS payment
     */
    private function processPayOSPayment($name, $email, $phone, $address, $city, $zip, $total, $totalQuantity, $cartItems)
    {
        // Determine member ID
        $memberId = isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id']) && (int)$_SESSION['user_id'] > 0
            ? (int)$_SESSION['user_id']
            : null;

        // Create order in database with 'Pending' status
        $shippingData = [
            'name'           => $name,
            'email'          => $email,
            'address'        => $address,
            'city'           => $city,
            'zip'            => $zip,
            'phone'          => $phone,
            'payment_method' => 'payos',
        ];

        $orderId = $this->orderModel->addOrder($memberId, $total, $totalQuantity, $shippingData);

        if (!$orderId) {
            $_SESSION['checkout_error'] = 'Failed to create order. Please try again.';
            header('Location: /index.php?controller=checkout&action=index');
            exit;
        }

        // Add order items
        foreach ($_SESSION['cart'] as $item) {
            for ($i = 0; $i < $item['quantity']; $i++) {
                $this->orderModel->addOrderShoes($orderId, $item['id']);
            }
        }

        // Prepare PayOS payment data
        $config = require __DIR__ . '/../config/payos.php';
        // Prefer configured URLs to avoid mismatched host/port issues
        $returnUrl = $config['return_url'];
        $cancelUrl = $config['cancel_url'];

        // Convert USD totals to VND integer amounts for PayOS
        $rate = isset($config['usd_to_vnd']) && is_numeric($config['usd_to_vnd']) ? (int)$config['usd_to_vnd'] : 25000;
        $amountVnd = max(0, (int) round($total * $rate));
        
        $orderCode = (int)$orderId; // Use order ID as order code
        $items = [];
        
        foreach ($cartItems as $item) {
            $items[] = [
                'name' => $item['name'],
                'quantity' => (int)$item['quantity'],
                // Convert per-item unit price from USD to VND integer
                'price' => max(0, (int) round($item['price'] * $rate))
            ];
        }

        $paymentData = [
            'orderCode' => $orderCode,
            'amount' => $amountVnd,
            'description' => "Thanh toan don hang #{$orderId}",
            'items' => $items,
            'returnUrl' => $returnUrl,
            'cancelUrl' => $cancelUrl,
        ];

        $result = $this->payosService->createPaymentLink($paymentData);

        if ($result['success']) {
            // Store order ID in session
            $_SESSION['payos_order_id'] = $orderId;
            
            // Handle nested data structure from PayOS API response
            $responseData = $result['data'];
            
            // PayOS may return {code, desc, data: {...}} or direct {...}
            if (isset($responseData['data']) && is_array($responseData['data'])) {
                $paymentData = $responseData['data'];
            } else {
                $paymentData = $responseData;
            }
            
            // Redirect to PayOS payment page
            $checkoutUrl = $paymentData['checkoutUrl'] ?? null;
            if ($checkoutUrl) {
                header('Location: ' . $checkoutUrl);
                exit;
            } else {
                $_SESSION['checkout_error'] = 'Không thể tạo link thanh toán. Vui lòng thử lại.';
                header('Location: /index.php?controller=checkout&action=index');
                exit;
            }
        } else {
            $err = $result['error'] ?? 'Unknown error';
            $_SESSION['checkout_error'] = 'Lỗi cổng thanh toán: ' . $err;
            header('Location: /index.php?controller=checkout&action=index');
            exit;
        }
    }
    
    /**
     * Handle PayOS return callback (success/failed)
     */
    public function payos_return()
    {
        $orderCode = $_GET['orderCode'] ?? null;
        $status = $_GET['status'] ?? null;
        
        if (!$orderCode) {
            $_SESSION['checkout_error'] = 'Phản hồi thanh toán không hợp lệ.';
            header('Location: /index.php?controller=checkout&action=index');
            exit;
        }
        
        // Verify payment status with PayOS API
        $result = $this->payosService->getPaymentInfo($orderCode);
        
        if ($result['success']) {
            // Handle nested data structure
            $responseData = $result['data'];
            if (isset($responseData['data']) && is_array($responseData['data'])) {
                $paymentData = $responseData['data'];
            } else {
                $paymentData = $responseData;
            }
            
            $paymentStatus = $paymentData['status'] ?? null;
            
            if ($paymentStatus === 'PAID' || $status === 'PAID') {
                // Payment successful - update order and clear cart
                $orderId = (int)$orderCode;
                
                // Update order status to 'Processing'
                $this->orderModel->updateOrderStatus($orderId, 'Processing');
                
                // Process stock decrement
                if (isset($_SESSION['cart'])) {
                    $cartStockItems = [];
                    foreach ($_SESSION['cart'] as $item) {
                        if (!empty($item['size'])) {
                            $cartStockItems[] = [
                                'product_id' => (int)$item['id'],
                                'size'       => $item['size'],
                                'quantity'   => (int)$item['quantity'],
                            ];
                        }
                    }
                    
                    if (!empty($cartStockItems)) {
                        $this->productModel->decrementStockForCartItems($cartStockItems);
                    }
                }
                
                // Clear cart and session
                unset($_SESSION['cart'], $_SESSION['cart_coupon'], $_SESSION['checkout_prefill'], $_SESSION['payos_order_id']);
                
                $_SESSION['checkout_success'] = "Thanh toán thành công! Mã đơn hàng: #{$orderId}";
                header('Location: /index.php?controller=home&action=index');
                exit;
            } else {
                $_SESSION['checkout_error'] = 'Thanh toán thất bại hoặc bị hủy. Trạng thái: ' . ($paymentStatus ?? 'Unknown');
                header('Location: /index.php?controller=checkout&action=index');
                exit;
            }
        } else {
            $error = $result['error'] ?? 'Unknown error';
            $_SESSION['checkout_error'] = 'Không thể xác minh thanh toán: ' . $error;
            header('Location: /index.php?controller=checkout&action=index');
            exit;
        }
    }
    
    /**
     * Handle PayOS cancel callback
     */
    public function payos_cancel()
    {
        $_SESSION['checkout_error'] = 'Bạn đã hủy thanh toán.';
        header('Location: /index.php?controller=checkout&action=index');
        exit;
    }
    
    /**
     * Handle PayOS webhook (payment notifications)
     * This endpoint receives real-time payment status updates from PayOS
     */
    public function payos_webhook()
    {
        // Log webhook for debugging
        $logFile = dirname(__DIR__) . '/logs/payos_webhook.log';
        $webhookBody = file_get_contents('php://input');
        $timestamp = date('Y-m-d H:i:s');
        @file_put_contents($logFile, "[{$timestamp}] Webhook received: {$webhookBody}\n", FILE_APPEND);
        
        $webhookData = json_decode($webhookBody, true);
        $signature = $_SERVER['HTTP_X_PAYOS_SIGNATURE'] ?? '';
        
        // Verify webhook signature
        if (!$this->payosService->verifyWebhookSignature($webhookData, $signature)) {
            @file_put_contents($logFile, "[{$timestamp}] Invalid signature\n", FILE_APPEND);
            http_response_code(401);
            echo json_encode(['error' => 'Invalid signature']);
            exit;
        }
        
        // Handle nested data structure
        $data = $webhookData['data'] ?? $webhookData;
        $orderCode = $data['orderCode'] ?? null;
        $status = $data['status'] ?? null;
        
        if ($orderCode && $status === 'PAID') {
            $orderId = (int)$orderCode;
            $updated = $this->orderModel->updateOrderStatus($orderId, 'Processing');
            @file_put_contents($logFile, "[{$timestamp}] Order #{$orderId} updated to Processing: " . ($updated ? 'Success' : 'Failed') . "\n", FILE_APPEND);
        }
        
        http_response_code(200);
        echo json_encode(['success' => true]);
        exit;
    }
}
