<?php
require_once 'models/PromotionalProductModel.php';

class PromotionalProductsController {
    private $productModel;

    public function __construct() {
        try {
            $this->productModel = new PromotionalProductModel();
        } catch (Exception $e) {
            error_log("Lỗi khởi tạo PromotionalProductModel: " . $e->getMessage(), 3, 'logs/errors.log');
            exit;
        }
    }

    public function index() {

        // Lấy promotion_id từ URL nếu có
        $promotionId = isset($_GET['promotion_id']) ? (int)$_GET['promotion_id'] : 0;

        try {
            // Lấy danh sách sản phẩm
            $products = $this->productModel->getAllProducts();

            // Nếu có promotion_id, lọc sản phẩm theo promotion_id
            if ($promotionId > 0) {
                $filteredProducts = array_filter($products, function($product) use ($promotionId) {
                    return !empty($product['promotion']) && $product['promotion']['promotion_id'] == $promotionId;
                });
                $products = array_values($filteredProducts); // Chuyển mảng về dạng chỉ số
            }
        } catch (Exception $e) {
            echo "Lỗi khi lấy danh sách sản phẩm: " . $e->getMessage() . "<br>";
            error_log("Lỗi lấy danh sách sản phẩm: " . $e->getMessage(), 3, 'logs/errors.log');
            exit;
        }

        // Gọi file giao diện
        require_once 'views/components/header.php';
        require_once 'views/pages/promotional-products.php';
    }

    public function detail() {

        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            header('Location: index.php?controller=promotionalProducts&action=index');
            exit;
        }

        $id = intval($_GET['id']);

        try {
            $product = $this->productModel->getProductById($id);
            if (!$product) {
                echo "Không tìm thấy sản phẩm với ID: $id.<br>";
                header('Location: index.php?controller=promotionalProducts&action=index');
                exit;
            }
        } catch (Exception $e) {
            echo "Lỗi khi lấy thông tin sản phẩm: " . $e->getMessage() . "<br>";
            error_log("Lỗi lấy thông tin sản phẩm: " . $e->getMessage(), 3, 'logs/errors.log');
            exit;
        }

        if (isset($_POST['add_to_cart'])) {
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
            if ($quantity < 1) {
                $quantity = 1;
            }
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }
            if (isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$id] = [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'price' => $product['final_price'],
                    'image' => $product['image'],
                    'quantity' => $quantity,
                    'promotion' => $product['promotion']
                ];
            }
            header('Location: index.php?controller=cart&action=index');
            exit;
        }

        // Gọi file giao diện
        require_once 'views/components/header.php';
        require_once 'views/pages/promotional-product-detail.php';
    }
}