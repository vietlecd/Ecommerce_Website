<?php
require_once __DIR__ . '/../models/OrderModel.php';
require_once __DIR__ . '/../models/ProductModelv2.php';
require_once __DIR__ . '/../models/CouponModel.php';

class CheckoutController {
    private $orderModel;
    private $productModel;
    private $couponModel;

    public function __construct() {
        $this->orderModel = new OrderModel();
        $this->productModel = new ProductModel();
        $this->couponModel = new CouponModel();
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
            $paymentMethod = isset($_POST['payment_method']) ? trim($_POST['payment_method']) : '';

            if (empty($name) || empty($email) || empty($address) || empty($city) || empty($zip)) {
                $error = 'All required fields must be filled';
            } elseif (empty($paymentMethod)) {
                $error = 'Please select a payment method';
            } else {
                if (isset($_SESSION['user_id'])) {
                    $memberId = $_SESSION['user_id'];
                    $orderResult = $this->orderModel->addOrder($memberId, $total, $totalQuantity, $paymentMethod);

                    if ($orderResult && isset($orderResult['order_id'])) {
                        $orderId = $orderResult['order_id'];
                        $trackingId = $orderResult['tracking_id'];

                        foreach ($_SESSION['cart'] as $item) {
                            for ($i = 0; $i < $item['quantity']; $i++) {
                                $this->orderModel->addOrderShoes($orderId, $item['id']);
                            }
                        }

                        unset($_SESSION['cart'], $_SESSION['cart_coupon']);
                        $_SESSION['earned_vip'] = $earnedVip;
                        $success = "Order placed successfully! Your tracking ID is <strong>$trackingId</strong>. Save this code to track your order.";
                    } else {
                        $error = 'Failed to place the order. Please try again.';
                    }
                } else {
                    $guestInfo = [
                        'name' => $name,
                        'email' => $email,
                        'address' => $address,
                        'city' => $city,
                        'zip' => $zip
                    ];
                    $orderResult = $this->orderModel->addGuestOrder($guestInfo, $total, $totalQuantity, $paymentMethod);

                    if ($orderResult && isset($orderResult['order_id'])) {
                        $orderId = $orderResult['order_id'];
                        $trackingId = $orderResult['tracking_id'];

                        foreach ($_SESSION['cart'] as $item) {
                            for ($i = 0; $i < $item['quantity']; $i++) {
                                $this->orderModel->addOrderShoes($orderId, $item['id']);
                            }
                        }

                        unset($_SESSION['cart'], $_SESSION['cart_coupon']);
                        $success = "Order placed successfully! Your tracking ID is <strong>$trackingId</strong>. Save this code to track your order.";
                        header('Refresh: 5; URL=/index.php?controller=home&action=index');
                    } else {
                        $error = 'Failed to place the order. Please try again.';
                    }
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
            $selectedPaymentMethod = isset($_POST['payment_method']) ? $_POST['payment_method'] : (isset($error) && isset($_POST['payment_method']) ? $_POST['payment_method'] : 'Card');
            $renderView = function ($cartItems, $subtotal, $shipping, $discountAmount, $appliedCoupon, $availableCoupons, $total, $error, $success, $selectedPaymentMethod) use ($viewPath) {
                require $viewPath;
            };
            $renderView($cartItems, $subtotal, $shipping, $discountAmount, $appliedCoupon, $availableCoupons, $total, $error, $success, $selectedPaymentMethod);
        } else {
            die("View file not found: $viewPath");
        }

        if (file_exists($footerPath)) {
            require_once $footerPath;
        } else {
            die("Footer file not found: $footerPath");
        }
    }
}