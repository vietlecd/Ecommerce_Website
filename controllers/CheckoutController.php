<?php
require_once __DIR__ . '/../models/OrderModel.php';
require_once __DIR__ . '/../models/ProductModelv2.php';
require_once __DIR__ . '/../models/CouponModel.php';
require_once __DIR__ . '/../models/PayosModel.php';

class CheckoutController {
    private $orderModel;
    private $productModel;
    private $couponModel;
    private $payosModel;

    public function __construct() {
        $this->orderModel = new OrderModel();
        $this->productModel = new ProductModel();
        $this->couponModel = new CouponModel();
        $this->payosModel = new PayosModel();
    }

    private function getMemberIdForOrder() {
        if (isset($_SESSION['user_id'])) {
            return $_SESSION['user_id'];
        }
        return null;
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
                'id' => $item['id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $lineTotal
            ];
        }

        $shipping = 10.00;
        $availableCoupons = $this->couponModel->getActiveCoupons();

        if (isset($_POST['apply_coupon']) && array_key_exists('selected_coupon', $_POST)) {
            $couponId = (int) $_POST['selected_coupon'];
            if ($couponId > 0) {
                $coupon = $this->couponModel->getCouponById($couponId);
                if ($coupon) {
                    $_SESSION['cart_coupon'] = $couponId;
                } else {
                    unset($_SESSION['cart_coupon']);
                }
            } else {
                unset($_SESSION['cart_coupon']);
            }
            header('Location: /index.php?controller=checkout&action=index');
            exit;
        }

        $discountAmount = 0;
        $appliedCoupon = null;

        if (!empty($_SESSION['cart_coupon'])) {
            $coupon = $this->couponModel->getCouponById((int) $_SESSION['cart_coupon']);
            if ($coupon) {
                $appliedCoupon = $coupon;
                $discountAmount = $subtotal * ($coupon['CodePercent'] / 100);
            } else {
                unset($_SESSION['cart_coupon']);
            }
        }

        $availableCoupons = $this->couponModel->getActiveCoupons();
        $total = max(0, $subtotal - $discountAmount + $shipping);
        $earnedVip = isset($_SESSION['earned_vip']) && is_numeric($_SESSION['earned_vip']) ? (float) $_SESSION['earned_vip'] : 0.00;

        if (isset($_POST['place_order'])) {
            $name = isset($_POST['name']) ? trim($_POST['name']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $address = isset($_POST['address']) ? trim($_POST['address']) : '';
            $city = isset($_POST['city']) ? trim($_POST['city']) : '';
            $zip = isset($_POST['zip']) ? trim($_POST['zip']) : '';
            $card_number = isset($_POST['card_number']) ? trim($_POST['card_number']) : '';
            $paymentMethod = isset($_POST['payment_method']) ? trim($_POST['payment_method']) : 'card';

            if (empty($name) || empty($email) || empty($address) || empty($city) || empty($zip)) {
                $error = 'All fields are required';
            } else {
                $memberId = $this->getMemberIdForOrder();
                $shippingData = [
                    'name' => $name,
                    'email' => $email,
                    'address' => $address,
                    'city' => $city,
                    'zip' => $zip,
                    'payment_method' => $paymentMethod
                ];
                $orderId = $this->orderModel->addOrder($memberId, $total, $totalQuantity, $shippingData);

                if ($orderId) {
                    foreach ($_SESSION['cart'] as $item) {
                        for ($i = 0; $i < $item['quantity']; $i++) {
                            $this->orderModel->addOrderShoes($orderId, $item['id']);
                        }
                    }

                    // If PayOS payment method selected, create payment link
                    if ($paymentMethod === 'payos') {
                        $paymentItems = [];
                        foreach ($_SESSION['cart'] as $item) {
                            $paymentItems[] = [
                                'name' => $item['name'],
                                'quantity' => $item['quantity'],
                                'price' => (int)($item['price'])
                            ];
                        }

                        $paymentData = [
                            'order_id' => $orderId,
                            'amount' => (int)$total,
                            'description' => "Payment for order #$orderId",
                            'buyer_name' => $name,
                            'buyer_email' => $email,
                            'buyer_phone' => '',
                            'buyer_address' => "$address, $city, $zip",
                            'items' => $paymentItems
                        ];

                        $paymentLink = $this->payosModel->createPaymentLink($paymentData);

                        if ($paymentLink) {
                            // Store payment link info in session
                            $_SESSION['pending_order_id'] = $orderId;
                            $_SESSION['payment_link'] = $paymentLink;
                            
                            // Clear cart after creating payment
                            unset($_SESSION['cart'], $_SESSION['cart_coupon']);
                            
                            // Redirect to payment page
                            header('Location: /index.php?controller=checkout&action=payment&order_id=' . $orderId);
                            exit;
                        } else {
                            $error = 'Failed to create payment link. Please try another payment method.';
                        }
                    } else {
                        // Traditional payment methods (card, COD)
                        unset($_SESSION['cart'], $_SESSION['cart_coupon']);
                        $_SESSION['earned_vip'] = $earnedVip;
                        $success = "Order placed successfully! Your order ID is #$orderId.";
                        header('Refresh: 3; URL=/index.php?controller=home&action=index');
                    }
                } else {
                    $error = 'Failed to place the order. Please try again.';
                }
            }
        }

        $headerPath = dirname(__DIR__) . '/views/components/header.php';
        $viewPath = dirname(__DIR__) . '/views/pages/checkout.php';
        $footerPath = dirname(__DIR__) . '/views/components/footer.php';

        if (file_exists($headerPath)) {
            require_once $headerPath;
        } else {
            die("Header file not found: $headerPath");
        }

        if (file_exists($viewPath)) {
            $renderView = function ($cartItems, $subtotal, $shipping, $discountAmount, $appliedCoupon, $availableCoupons, $total, $error, $success) use ($viewPath) {
                require $viewPath;
            };
            $renderView($cartItems, $subtotal, $shipping, $discountAmount, $appliedCoupon, $availableCoupons, $total, $error, $success);
        } else {
            die("View file not found: $viewPath");
        }

        if (file_exists($footerPath)) {
            require_once $footerPath;
        } else {
            die("Footer file not found: $footerPath");
        }
    }

    public function payment() {
        if (!isset($_GET['order_id'])) {
            header('Location: /index.php?controller=home&action=index');
            exit;
        }

        $orderId = (int)$_GET['order_id'];
        $paymentLink = $_SESSION['payment_link'] ?? null;

        if (!$paymentLink) {
            // Try to get payment link from PayOS
            $paymentInfo = $this->payosModel->getPaymentLinkInfo($orderId);
            if ($paymentInfo) {
                $paymentLink = $paymentInfo;
            } else {
                header('Location: /index.php?controller=home&action=index');
                exit;
            }
        }

        $orderDetails = $this->orderModel->getOrderById($orderId);

        $headerPath = dirname(__DIR__) . '/views/components/header.php';
        $viewPath = dirname(__DIR__) . '/views/pages/payment.php';
        $footerPath = dirname(__DIR__) . '/views/components/footer.php';

        if (file_exists($headerPath)) {
            require_once $headerPath;
        }

        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            die("Payment view file not found: $viewPath");
        }

        if (file_exists($footerPath)) {
            require_once $footerPath;
        }
    }

    public function paymentReturn() {
        $returnData = $_GET;
        
        if (!isset($returnData['orderCode'])) {
            header('Location: /index.php?controller=home&action=index');
            exit;
        }

        $orderId = (int)$returnData['orderCode'];
        $status = $returnData['status'] ?? 'CANCELLED';

        // Verify payment with PayOS
        $paymentInfo = $this->payosModel->getPaymentLinkInfo($orderId);

        if ($paymentInfo && $paymentInfo['status'] === 'PAID') {
            // Update order status
            $this->orderModel->updateOrderStatus($orderId, 'Processing');
            
            // Clear session data
            unset($_SESSION['pending_order_id'], $_SESSION['payment_link']);
            
            // Show success message
            $_SESSION['payment_success'] = "Payment successful! Your order #$orderId is being processed.";
            header('Location: /index.php?controller=checkout&action=success&order_id=' . $orderId);
        } else {
            // Payment failed or cancelled
            $_SESSION['payment_error'] = "Payment was not completed. Please try again.";
            header('Location: /index.php?controller=checkout&action=failed&order_id=' . $orderId);
        }
        exit;
    }

    public function paymentCancel() {
        $orderId = isset($_GET['orderCode']) ? (int)$_GET['orderCode'] : 0;
        
        if ($orderId) {
            // You might want to cancel the order or mark it as cancelled
            $this->orderModel->updateOrderStatus($orderId, 'Cancelled');
        }

        $_SESSION['payment_error'] = "Payment was cancelled.";
        header('Location: /index.php?controller=cart&action=index');
        exit;
    }

    public function paymentWebhook() {
        // Get webhook data
        $payload = file_get_contents('php://input');
        $data = json_decode($payload, true);
        
        // Get signature from header
        $signature = $_SERVER['HTTP_X_PAYOS_SIGNATURE'] ?? '';

        // Verify signature
        if (!$this->payosModel->verifyWebhookSignature($data, $signature)) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid signature']);
            exit;
        }

        // Process webhook
        if (isset($data['data']) && isset($data['data']['orderCode'])) {
            $orderCode = $data['data']['orderCode'];
            $status = $data['data']['status'] ?? '';

            if ($status === 'PAID') {
                $this->orderModel->updateOrderStatus($orderCode, 'Processing');
            } elseif ($status === 'CANCELLED') {
                $this->orderModel->updateOrderStatus($orderCode, 'Cancelled');
            }

            // Log webhook for debugging
            error_log("PayOS Webhook: Order $orderCode status updated to $status");
        }

        // Return success response
        http_response_code(200);
        echo json_encode(['success' => true]);
        exit;
    }

    public function success() {
        $orderId = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
        $message = $_SESSION['payment_success'] ?? 'Order placed successfully!';
        unset($_SESSION['payment_success']);

        $headerPath = dirname(__DIR__) . '/views/components/header.php';
        $viewPath = dirname(__DIR__) . '/views/pages/payment_success.php';
        $footerPath = dirname(__DIR__) . '/views/components/footer.php';

        if (file_exists($headerPath)) {
            require_once $headerPath;
        }

        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            echo "<h2>$message</h2>";
            echo "<p>Order ID: #$orderId</p>";
            echo '<a href="/index.php?controller=home&action=index">Return to Home</a>';
        }

        if (file_exists($footerPath)) {
            require_once $footerPath;
        }
    }

    public function failed() {
        $orderId = isset($_GET['order_id']) ? (int)$_GET['order_id'] : 0;
        $message = $_SESSION['payment_error'] ?? 'Payment failed. Please try again.';
        unset($_SESSION['payment_error']);

        $headerPath = dirname(__DIR__) . '/views/components/header.php';
        $viewPath = dirname(__DIR__) . '/views/pages/payment_failed.php';
        $footerPath = dirname(__DIR__) . '/views/components/footer.php';

        if (file_exists($headerPath)) {
            require_once $headerPath;
        }

        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            echo "<h2>$message</h2>";
            echo "<p>Order ID: #$orderId</p>";
            echo '<a href="/index.php?controller=checkout&action=index">Try Again</a>';
        }

        if (file_exists($footerPath)) {
            require_once $footerPath;
        }
    }
}