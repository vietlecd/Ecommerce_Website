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
        $shipping = 10.00;
        $discountAmount = 0;
        $appliedCoupon = null;

        if (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $id => $item) {
                $product = $this->promotionModel->getProductById($id);
                if ($product) {
                    $currentPrice = $product['final_price'];
                    $_SESSION['cart'][$id]['price'] = $currentPrice;

                    $subtotalForItem = $currentPrice * $item['quantity'];
                    $cartItems[] = [
                        'product' => [
                            'id' => $product['id'],
                            'name' => $product['name'],
                            'price' => $product['price'],
                            'final_price' => $currentPrice,
                            'image' => $product['image']
                        ],
                        'quantity' => $item['quantity'],
                        'subtotal' => $subtotalForItem
                    ];
                    $subtotal += $subtotalForItem;
                } else {
                    unset($_SESSION['cart'][$id]);
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

        $recommendedProducts = $this->promotionModel->getTopSaleProducts(4);
        $total = max(0, $subtotal - $discountAmount + $shipping);

        require_once 'views/pages/cart.php';
    }

    public function update() {
        if (!empty($_POST['quantity'])) {
            foreach ($_POST['quantity'] as $id => $quantity) {
                $quantity = (int) $quantity;
                if ($quantity < 1) {
                    unset($_SESSION['cart'][$id]);
                } elseif (isset($_SESSION['cart'][$id])) {
                    $product = $this->promotionModel->getProductById($id);
                    if ($product) {
                        $_SESSION['cart'][$id]['price'] = $product['final_price'];
                    }
                    $_SESSION['cart'][$id]['quantity'] = $quantity;
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