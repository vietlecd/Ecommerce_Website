<?php
require_once 'models/PromotionalProductModel.php';
require_once 'models/CouponModel.php';

class CartController {
    private $promotionModel;
    private $couponModel;

    public function __construct() {
        $this->promotionModel = new PromotionalProductModel();
        $this->couponModel = new CouponModel();
    }

    public function index() {
        $cartItems = [];
        $subtotal = 0;
        $shipping = 0.50;
        $discountAmount = 0;
        $appliedCoupon = null;

        if (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $key => $item) {
                $productId = $item['product_id'] ?? $key;
                $product = $this->promotionModel->getProductById($productId);
                if ($product) {
                    $currentPrice = $product['final_price'];
                    $_SESSION['cart'][$key]['price'] = $currentPrice;

                    $subtotalForItem = $currentPrice * $item['quantity'];
                    $cartItems[] = [
                        'key' => (string)$key,
                        'product' => [
                            'id' => $product['id'],
                            'name' => $product['name'],
                            'price' => $product['price'],
                            'final_price' => $currentPrice,
                            'image' => $product['image']
                        ],
                        'size_label' => $item['size_label'] ?? ($item['size'] ?? null),
                        'quantity' => $item['quantity'],
                        'subtotal' => $subtotalForItem
                    ];
                    $subtotal += $subtotalForItem;
                } else {
                    unset($_SESSION['cart'][$key]);
                }
            }
        }

        $availableCoupons = $this->couponModel->getActiveCoupons();
        $selectedCouponId = isset($_SESSION['cart_coupon']) ? (int) $_SESSION['cart_coupon'] : null;
        if ($selectedCouponId && $subtotal > 0) {
            $appliedCoupon = $this->couponModel->getCouponById($selectedCouponId);
            if ($appliedCoupon) {
                $discountAmount = $subtotal * ($appliedCoupon['CodePercent'] / 100);
            } else {
                unset($_SESSION['cart_coupon']);
            }
        }
        if ($appliedCoupon && isset($appliedCoupon['CodeTitle']) && strcasecmp($appliedCoupon['CodeTitle'], 'FREESHIP') === 0) {
            $shipping = 0.00;
        }

        $recommendedProducts = $this->promotionModel->getTopSaleProducts(4);
        $total = max(0, $subtotal - $discountAmount + $shipping);

        require_once 'views/pages/cart.php';
    }

    public function update() {
        if (!empty($_POST['quantity'])) {
            foreach ($_POST['quantity'] as $key => $quantity) {
                $quantity = (int) $quantity;
                if ($quantity < 1) {
                    unset($_SESSION['cart'][$key]);
                } elseif (isset($_SESSION['cart'][$key])) {
                    $productId = $_SESSION['cart'][$key]['product_id'] ?? $key;
                    $product = $this->promotionModel->getProductById($productId);
                    if ($product) {
                        $_SESSION['cart'][$key]['price'] = $product['final_price'];
                        $sizeLimit = null;
                        if (!empty($product['sizes']) && isset($_SESSION['cart'][$key]['size'])) {
                            $selectedSize = $_SESSION['cart'][$key]['size'];
                            foreach ($product['sizes'] as $sizeRow) {
                                if (abs((float)$sizeRow['size'] - (float)$selectedSize) < 0.01) {
                                    $sizeLimit = (int)$sizeRow['quantity'];
                                    break;
                                }
                            }
                        }
                        if ($sizeLimit !== null && $sizeLimit > 0 && $quantity > $sizeLimit) {
                            $quantity = $sizeLimit;
                        }
                    }
                    $_SESSION['cart'][$key]['quantity'] = $quantity;
                }
            }
        }

        if (array_key_exists('selected_coupon', $_POST)) {
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
        }

        header('Location: index.php?controller=cart&action=index');
        exit;
    }

    public function remove() {
        if (isset($_GET['id']) && isset($_SESSION['cart'][$_GET['id']])) {
            unset($_SESSION['cart'][$_GET['id']]);
        }
        header('Location: index.php?controller=cart&action=index');
        exit;
    }
}
