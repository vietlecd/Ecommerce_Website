<?php
require_once 'models/ProductModelv2.php';

class ProductsController {
    private $productModel;

    public function __construct() {
        $this->productModel = new ProductModel();
    }

    public function index() {
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $category = isset($_GET['category']) ? trim($_GET['category']) : '';

        $perPage = 8; // Số sản phẩm trên mỗi trang
        $currentPage = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
        $offset = ($currentPage - 1) * $perPage;

        // Fetch products with pagination
        $products = $this->productModel->getProducts($keyword, $category, $perPage, $offset);

        // Get total number of products
        $totalProducts = $this->productModel->getTotalProducts($keyword, $category);
        $totalPages = ceil($totalProducts / $perPage);

        // Pass data to view
        require_once 'views/components/header.php';
        require_once 'views/pages/products.php';
    }

    public function detail() {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            header('Location: index.php?controller=products&action=index');
            exit;
        }

        $id = intval($_GET['id']);
        $product = $this->productModel->getProductById($id);

        if (!$product) {
            header('Location: index.php?controller=products&action=index');
            exit;
        }

        // Handle add to cart
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
                    'price' => $product['final_price'], // Sử dụng final_price thay vì price
                    'image' => $product['image'],
                    'quantity' => $quantity
                ];
            }
            
            header('Location: index.php?controller=cart&action=index');
            exit;
        }

        require_once 'views/components/header.php';
        require_once 'views/pages/product-detail.php';
    }
}