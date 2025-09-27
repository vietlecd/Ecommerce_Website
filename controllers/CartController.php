<?php
require_once 'models/PromotionalProductModel.php';

class CartController {
    private $promotionModel;

    public function __construct() {
        $this->promotionModel = new PromotionalProductModel();
    }

    public function index() {
        $cartItems = [];
        $subtotal = 0;
        $shipping = 10.00; // Giả định phí vận chuyển là $10, có thể thay đổi

        if (!empty($_SESSION['cart'])) {
            foreach ($_SESSION['cart'] as $id => $item) {
                // Lấy thông tin sản phẩm mới nhất từ cơ sở dữ liệu
                $product = $this->promotionModel->getProductById($id);
                if ($product) {
                    $currentPrice = $product['final_price'];
                    // Cập nhật giá trong session để đồng bộ
                    $_SESSION['cart'][$id]['price'] = $currentPrice;

                    // Tính toán subtotal dựa trên final_price mới nhất
                    $subtotalForItem = $currentPrice * $item['quantity'];
                    $cartItems[] = [
                        'product' => [
                            'id' => $product['id'],
                            'name' => $product['name'],
                            'price' => $product['price'], // Giá gốc
                            'final_price' => $currentPrice, // Giá đã giảm
                            'image' => $product['image']
                        ],
                        'quantity' => $item['quantity'],
                        'subtotal' => $subtotalForItem
                    ];
                    $subtotal += $subtotalForItem;
                } else {
                    // Nếu sản phẩm không còn tồn tại, xóa khỏi giỏ hàng
                    unset($_SESSION['cart'][$id]);
                }
            }
        }

        $total = $subtotal + $shipping;

        require_once 'views/pages/cart.php';
    }

    public function update() {
        if (isset($_POST['update_cart']) && !empty($_POST['quantity'])) {
            foreach ($_POST['quantity'] as $id => $quantity) {
                $quantity = (int)$quantity;
                if ($quantity < 1) {
                    unset($_SESSION['cart'][$id]);
                } elseif (isset($_SESSION['cart'][$id])) {
                    // Lấy giá mới nhất từ cơ sở dữ liệu
                    $product = $this->promotionModel->getProductById($id);
                    if ($product) {
                        $_SESSION['cart'][$id]['price'] = $product['final_price']; // Cập nhật giá mới
                    }
                    $_SESSION['cart'][$id]['quantity'] = $quantity;
                }
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