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

    public function index() {
        // Redirect nếu giỏ hàng rỗng
        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            header('Location: /index.php?controller=cart&action=index');
            exit;
        }

        $success = '';
        $error = '';

        // Tính tổng giỏ hàng và tổng số lượng
        $subtotal = 0;
        $totalQuantity = 0;
        foreach ($_SESSION['cart'] as $item) {
            $subtotal += $item['price'] * $item['quantity'];
            $totalQuantity += $item['quantity'];
        }

        $shipping = 10.00; // Chi phí vận chuyển cố định
        $total = $subtotal + $shipping;

        // Xử lý earned_vip
        $earnedVip = isset($_SESSION['earned_vip']) && is_numeric($_SESSION['earned_vip']) ? (float)$_SESSION['earned_vip'] : 0.00;

        if (isset($_POST['place_order'])) {
            // Xác thực đơn giản
            $name = isset($_POST['name']) ? trim($_POST['name']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $address = isset($_POST['address']) ? trim($_POST['address']) : '';
            $city = isset($_POST['city']) ? trim($_POST['city']) : '';
            $zip = isset($_POST['zip']) ? trim($_POST['zip']) : '';
            $card_number = isset($_POST['card_number']) ? trim($_POST['card_number']) : '';

            if (empty($name) || empty($email) || empty($address) || empty($city) || empty($zip) || empty($card_number)) {
                $error = 'All fields are required';
            } else {
                // Giả định địa chỉ giao hàng là chuỗi kết hợp (vì không có cột ShippingAddress)
                $shippingAddress = "$address, $city, $zip"; // Không lưu vào database vì không có cột

                // Thêm đơn hàng vào bảng `order`
                if (isset($_SESSION['user_id'])) {
                    $memberId = $_SESSION['user_id'];
                    $orderId = $this->orderModel->addOrder($memberId, $total, $totalQuantity);

                    if ($orderId) {
                        // Thêm chi tiết đơn hàng vào bảng `order_shoes`
                        foreach ($_SESSION['cart'] as $item) {
                            for ($i = 0; $i < $item['quantity']; $i++) {
                                $this->orderModel->addOrderShoes($orderId, $item['id']);
                            }
                        }

                        // Xóa giỏ hàng sau khi đặt hàng thành công
                        unset($_SESSION['cart']);

                        // Lưu earned_vip vào session (nếu cần sử dụng sau này)
                        $_SESSION['earned_vip'] = $earnedVip;

                        $success = "Order placed successfully! Your order ID is #$orderId.";
                        // Chuyển hướng về trang chủ sau 3 giây
                        header('Refresh: 3; URL=/index.php?controller=home&action=index');
                    } else {
                        $error = 'Failed to place the order. Please try again.';
                    }
                } else {
                    $error = 'Please log in to place an order.';
                }
            }
        }

        // Hiển thị giao diện checkout
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