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
                'id'       => $item['id'],
                'name'     => $item['name'],
                'price'    => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $lineTotal
            ];
        }

        $shipping = 0.50;
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
        if ($appliedCoupon && isset($appliedCoupon['CodeTitle']) && strcasecmp($appliedCoupon['CodeTitle'], 'FREESHIP') === 0) {
            $shipping = 0.00;
        }
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
}
