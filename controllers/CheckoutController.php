<?php
require_once __DIR__ . '/../models/OrderModel.php';
require_once __DIR__ . '/../models/ProductModelv2.php';
require_once __DIR__ . '/../models/CouponModel.php';
require_once __DIR__ . '/../models/MemberModel.php';

class CheckoutController {
    private $orderModel;
    private $productModel;
    private $couponModel;
    private $memberModel;
    private $addressSessionKey;

    public function __construct() {
        $this->orderModel = new OrderModel();
        $this->productModel = new ProductModel();
        $this->couponModel = new CouponModel();
        $this->memberModel = new MemberModel();
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
                'id' => $item['id'],
                'name' => $item['name'],
                'price' => $item['price'],
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

        if (isset($_SESSION['user_id'])) {
            $memberProfile = $this->memberModel->getMemberById((int) $_SESSION['user_id']);
            if ($memberProfile) {
                $prefillName = $memberProfile['Name'] ?? ($memberProfile['Username'] ?? '');
                $prefillEmail = $memberProfile['Email'] ?? '';
                $prefillAddress = $memberProfile['Address'] ?? '';
                $prefillCity = $memberProfile['City'] ?? '';
                $prefillZip = $memberProfile['Zip'] ?? ($memberProfile['ZIP'] ?? '');
            }
        }

        if ($prefillFromSession) {
            $prefillName = $prefillFromSession['name'] ?? $prefillName;
            $prefillEmail = $prefillFromSession['email'] ?? $prefillEmail;
            $prefillAddress = $prefillFromSession['address'] ?? $prefillAddress;
            $prefillCity = $prefillFromSession['city'] ?? $prefillCity;
            $prefillZip = $prefillFromSession['zip'] ?? $prefillZip;
        }

        if (isset($_POST['apply_coupon']) && array_key_exists('selected_coupon', $_POST)) {
            $couponId = (int) $_POST['selected_coupon'];
            // Lưu form shipping để giữ lại khi refresh sau apply coupon
            $_SESSION['checkout_prefill'] = [
                'name' => trim($_POST['name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'address' => trim($_POST['address'] ?? ''),
                'city' => trim($_POST['city'] ?? ''),
                'zip' => trim($_POST['zip'] ?? '')
            ];
            if ($couponId > 0) {
                $coupon = $this->couponModel->getCouponById($couponId);
                if ($coupon) {
                    $_SESSION['cart_coupon'] = $couponId;
                    $_SESSION['coupon_notice'] = 'Coupon đã được áp dụng.';
                } else {
                    unset($_SESSION['cart_coupon']);
                    $_SESSION['coupon_notice'] = 'Coupon không hợp lệ hoặc đã hết hạn.';
                }
            } else {
                unset($_SESSION['cart_coupon']);
                $_SESSION['coupon_notice'] = 'Đã bỏ áp dụng coupon.';
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
                $percent = is_numeric($coupon['CodePercent']) ? (float) $coupon['CodePercent'] : 0;
                if ($percent > 0) {
                    $discountAmount = min($subtotal, $subtotal * ($percent / 100));
                }
            } else {
                unset($_SESSION['cart_coupon']);
            }
        }

        $availableCoupons = $this->couponModel->getActiveCoupons();
        $total = max(0, $subtotal - $discountAmount + $shipping);
        $earnedVip = isset($_SESSION['earned_vip']) && is_numeric($_SESSION['earned_vip']) ? (float) $_SESSION['earned_vip'] : 0.00;

        if (isset($_POST['save_address'])) {
            $saveName = isset($_POST['name']) ? trim($_POST['name']) : '';
            $saveEmail = isset($_POST['email']) ? trim($_POST['email']) : '';
            $saveAddress = isset($_POST['address']) ? trim($_POST['address']) : '';
            $saveCity = isset($_POST['city']) ? trim($_POST['city']) : '';
            $saveZip = isset($_POST['zip']) ? trim($_POST['zip']) : '';

            if ($saveName && $saveEmail && $saveAddress && $saveCity && $saveZip) {
                $savedAddresses[] = [
                    'id' => uniqid('addr_', true),
                    'name' => $saveName,
                    'email' => $saveEmail,
                    'address' => $saveAddress,
                    'city' => $saveCity,
                    'zip' => $saveZip
                ];
                // Giữ tối đa 5 địa chỉ gần nhất
                $savedAddresses = array_slice($savedAddresses, -5);
                $_SESSION[$this->addressSessionKey] = $savedAddresses;
                $_SESSION['address_notice'] = 'Đã lưu địa chỉ vào sổ địa chỉ của bạn.';
                header('Location: /index.php?controller=checkout&action=index');
                exit;
            } else {
                $error = 'Vui lòng điền đầy đủ thông tin trước khi lưu địa chỉ.';
            }
        }

        if (isset($_POST['delete_address'])) {
            $deleteId = $_POST['delete_address'];
            $savedAddresses = array_values(array_filter($savedAddresses, function ($addr) use ($deleteId) {
                return isset($addr['id']) && $addr['id'] !== $deleteId;
            }));
            $_SESSION[$this->addressSessionKey] = $savedAddresses;
            $_SESSION['address_notice'] = 'Đã xóa địa chỉ đã lưu.';
            header('Location: /index.php?controller=checkout&action=index');
            exit;
        }

        if (isset($_POST['place_order'])) {
            $paymentMethod = isset($_POST['payment_method']) ? $_POST['payment_method'] : 'cod';
            $name = isset($_POST['name']) ? trim($_POST['name']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $address = isset($_POST['address']) ? trim($_POST['address']) : '';
            $city = isset($_POST['city']) ? trim($_POST['city']) : '';
            $zip = isset($_POST['zip']) ? trim($_POST['zip']) : '';
            $card_number = isset($_POST['card_number']) ? trim($_POST['card_number']) : '';
            $expiry = isset($_POST['expiry']) ? trim($_POST['expiry']) : '';
            $cvv = isset($_POST['cvv']) ? trim($_POST['cvv']) : '';

            if (empty($name) || empty($email) || empty($address) || empty($city) || empty($zip)) {
                $error = 'All required fields must be filled.';
            } elseif ($paymentMethod === 'card' && (empty($card_number) || empty($expiry) || empty($cvv))) {
                $error = 'Card payment is not available yet. Please choose Cash on Delivery.';
            } else {
                if (isset($_SESSION['user_id'])) {
                    $memberId = $_SESSION['user_id'];
                    $orderId = $this->orderModel->addOrder($memberId, $total, $totalQuantity);

                    if ($orderId) {
                        foreach ($_SESSION['cart'] as $item) {
                            for ($i = 0; $i < $item['quantity']; $i++) {
                                $this->orderModel->addOrderShoes($orderId, $item['id']);
                            }
                        }

                        unset($_SESSION['cart'], $_SESSION['cart_coupon'], $_SESSION['checkout_prefill']);
                        $_SESSION['earned_vip'] = $earnedVip;
                        $success = "Order placed successfully! Your order ID is #$orderId.";
                        header('Refresh: 3; URL=/index.php?controller=home&action=index');
                    } else {
                        $error = 'Failed to place the order. Please try again.';
                    }
                } else {
                    $error = 'Please log in to place an order.';
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
            $renderView = function ($cartItems, $subtotal, $shipping, $discountAmount, $appliedCoupon, $availableCoupons, $total, $error, $success, $prefillName, $prefillEmail, $prefillAddress, $prefillCity, $prefillZip, $savedAddresses, $addressNotice, $couponNotice) use ($viewPath) {
                require $viewPath;
            };
            $renderView($cartItems, $subtotal, $shipping, $discountAmount, $appliedCoupon, $availableCoupons, $total, $error, $success, $prefillName, $prefillEmail, $prefillAddress, $prefillCity, $prefillZip, $savedAddresses, $addressNotice, $couponNotice);
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
