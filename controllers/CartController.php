<?php
require_once 'models/PromotionalProductModel.php';

class CartController {
    private $promotionModel;

    public function __construct() {
        $this->promotionModel = new PromotionalProductModel();
    }

    private function calculateShipping(float $subtotal): float {
        if ($subtotal <= 0) {
            return 0.0;
        }
        return $subtotal >= 100 ? 0.0 : 10.0;
    }

    private function pushMessage(string $message, string $type = 'info'): void {
        if (!isset($_SESSION['cart_messages'])) {
            $_SESSION['cart_messages'] = [];
        }
        $_SESSION['cart_messages'][] = ['type' => $type, 'text' => $message];
    }

    public function index() {
        $cartItems = [];
        $subtotal = 0.0;

        $messages = isset($_SESSION['cart_messages']) ? $_SESSION['cart_messages'] : [];
        unset($_SESSION['cart_messages']);

        if (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $id => $item) {
                $product = $this->promotionModel->getProductById($id);

                if (!$product) {
                    unset($_SESSION['cart'][$id]);
                    $messages[] = ['type' => 'warning', 'text' => 'A product is no longer available and was removed from your cart.'];
                    continue;
                }

                $currentPrice = $product['final_price'];
                $stock = isset($product['Stock']) ? (int)$product['Stock'] : 0;
                $quantity = (int)$item['quantity'];

                if ($stock <= 0) {
                    unset($_SESSION['cart'][$id]);
                    $messages[] = ['type' => 'warning', 'text' => 'Product "' . $product['name'] . '" is out of stock and was removed from your cart.'];
                    continue;
                }

                if ($quantity > $stock) {
                    $quantity = $stock;
                    $_SESSION['cart'][$id]['quantity'] = $stock;
                    $messages[] = ['type' => 'warning', 'text' => 'Quantity for "' . $product['name'] . '" was limited to current stock (' . $stock . ').'];
                }

                $_SESSION['cart'][$id]['price'] = $currentPrice;

                $subtotalForItem = $currentPrice * $quantity;
                $cartItems[] = [
                    'product' => [
                        'id' => $product['id'],
                        'name' => $product['name'],
                        'price' => $product['price'],
                        'final_price' => $currentPrice,
                        'image' => $product['image'],
                        'stock' => $stock,
                    ],
                    'quantity' => $quantity,
                    'subtotal' => $subtotalForItem,
                ];
                $subtotal += $subtotalForItem;
            }
        }

        $shipping = $this->calculateShipping($subtotal);
        $total = $subtotal + $shipping;

        require_once 'views/pages/cart.php';
    }

    public function update() {
        if (isset($_POST['update_cart']) && !empty($_POST['quantity'])) {
            foreach ($_POST['quantity'] as $id => $quantity) {
                $quantity = (int)$quantity;

                if ($quantity < 1) {
                    unset($_SESSION['cart'][$id]);
                    $this->pushMessage('An item was removed from your cart.', 'info');
                    continue;
                }

                if (!isset($_SESSION['cart'][$id])) {
                    continue;
                }

                $product = $this->promotionModel->getProductById($id);
                if (!$product) {
                    unset($_SESSION['cart'][$id]);
                    $this->pushMessage('A product is no longer available and was removed from your cart.', 'warning');
                    continue;
                }

                $stock = isset($product['Stock']) ? (int)$product['Stock'] : 0;
                if ($stock <= 0) {
                    unset($_SESSION['cart'][$id]);
                    $this->pushMessage('Product "' . $product['name'] . '" is out of stock and was removed from your cart.', 'warning');
                    continue;
                }

                if ($quantity > $stock) {
                    $quantity = $stock;
                    $this->pushMessage('Quantity for "' . $product['name'] . '" was reduced to ' . $stock . ' due to limited stock.', 'warning');
                }

                $_SESSION['cart'][$id]['price'] = $product['final_price'];
                $_SESSION['cart'][$id]['quantity'] = $quantity;
            }
        }

        header('Location: index.php?controller=cart&action=index');
        exit;
    }

    public function remove() {
        if (isset($_GET['id']) && isset($_SESSION['cart'][$_GET['id']])) {
            unset($_SESSION['cart'][$_GET['id']]);
            $this->pushMessage('Item removed from your cart.', 'info');
        }
        header('Location: index.php?controller=cart&action=index');
        exit;
    }
}
