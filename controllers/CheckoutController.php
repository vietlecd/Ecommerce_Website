<?php
require_once __DIR__ . '/../models/OrderModel.php';
require_once __DIR__ . '/../models/ProductModelv2.php';

class CheckoutController {
    private $orderModel;
    private $productModel;

    public function __construct() {
        $this->orderModel = new OrderModel();
        $this->productModel = new ProductModel();
    }

    private function calculateShipping(float $subtotal): float {
        if ($subtotal <= 0) {
            return 0.0;
        }
        return $subtotal >= 100 ? 0.0 : 10.0;
    }

    private function redirectWithCartMessage(string $message, string $type = 'warning'): void {
        if (!isset($_SESSION['cart_messages'])) {
            $_SESSION['cart_messages'] = [];
        }
        $_SESSION['cart_messages'][] = ['type' => $type, 'text' => $message];
        header('Location: /index.php?controller=cart&action=index');
        exit;
    }

    public function index() {
        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            header('Location: /index.php?controller=cart&action=index');
            exit;
        }

        $success = '';
        $error = '';
        $cartSnapshot = [];
        $subtotal = 0.0;
        $totalQuantity = 0;
        $cartAdjustedMessages = [];

        foreach ($_SESSION['cart'] as $productId => $cartItem) {
            $product = $this->productModel->getProductById($productId);

            if (!$product) {
                unset($_SESSION['cart'][$productId]);
                $cartAdjustedMessages[] = 'One of the products in your cart is no longer available.';
                continue;
            }

            $stock = isset($product['Stock']) ? (int)$product['Stock'] : 0;
            if ($stock <= 0) {
                unset($_SESSION['cart'][$productId]);
                $cartAdjustedMessages[] = 'Product "' . $product['name'] . '" is no longer in stock.';
                continue;
            }

            $quantity = isset($cartItem['quantity']) ? (int)$cartItem['quantity'] : 1;
            if ($quantity > $stock) {
                $quantity = $stock;
                $_SESSION['cart'][$productId]['quantity'] = $stock;
                $cartAdjustedMessages[] = 'Quantity for "' . $product['name'] . '" was reduced to ' . $stock . ' (current stock).';
            } elseif ($quantity < 1) {
                unset($_SESSION['cart'][$productId]);
                $cartAdjustedMessages[] = 'Product "' . $product['name'] . '" had an invalid quantity and was removed.';
                continue;
            }

            $_SESSION['cart'][$productId]['price'] = $product['final_price'];

            $lineTotal = $product['final_price'] * $quantity;
            $cartSnapshot[] = [
                'product' => $product,
                'quantity' => $quantity,
                'line_total' => $lineTotal,
            ];

            $subtotal += $lineTotal;
            $totalQuantity += $quantity;
        }

        if (empty($cartSnapshot)) {
            $this->redirectWithCartMessage('Your cart is empty. Please add items before checking out.');
        }

        if (!empty($cartAdjustedMessages)) {
            $_SESSION['cart_messages'] = isset($_SESSION['cart_messages'])
                ? array_merge($_SESSION['cart_messages'], array_map(function ($msg) {
                    return ['type' => 'warning', 'text' => $msg];
                }, $cartAdjustedMessages))
                : array_map(function ($msg) {
                    return ['type' => 'warning', 'text' => $msg];
                }, $cartAdjustedMessages);

            header('Location: /index.php?controller=cart&action=index');
            exit;
        }

        $shipping = $this->calculateShipping($subtotal);
        $total = $subtotal + $shipping;

        $earnedVip = isset($_SESSION['earned_vip']) && is_numeric($_SESSION['earned_vip'])
            ? (float)$_SESSION['earned_vip']
            : 0.00;

        if (isset($_POST['place_order'])) {
            $name = isset($_POST['name']) ? trim($_POST['name']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $address = isset($_POST['address']) ? trim($_POST['address']) : '';
            $city = isset($_POST['city']) ? trim($_POST['city']) : '';
            $zip = isset($_POST['zip']) ? trim($_POST['zip']) : '';
            $cardNumber = isset($_POST['card_number']) ? trim($_POST['card_number']) : '';

            if (empty($name) || empty($email) || empty($address) || empty($city) || empty($zip) || empty($cardNumber)) {
                $error = 'Please fill in all required fields.';
            } elseif (!isset($_SESSION['user_id'])) {
                $error = 'Please log in before placing an order.';
            } else {
                $memberId = $_SESSION['user_id'];
                $orderId = $this->orderModel->addOrder($memberId, $total, $totalQuantity);

                if ($orderId) {
                    foreach ($cartSnapshot as $item) {
                        for ($i = 0; $i < $item['quantity']; $i++) {
                            $this->orderModel->addOrderShoes($orderId, $item['product']['id']);
                        }
                    }

                    unset($_SESSION['cart']);
                    $_SESSION['earned_vip'] = $earnedVip;

                    $success = "Order placed successfully! Your order ID is #$orderId.";
                    header('Refresh: 3; URL=/index.php?controller=home&action=index');
                } else {
                    $error = 'Could not place the order. Please try again later.';
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
            $renderView = function ($subtotal, $shipping, $total, $error, $success) use ($viewPath) {
                require $viewPath;
            };
            $renderView($subtotal, $shipping, $total, $error, $success);
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
