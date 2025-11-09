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

    public function api()
    {
        header('Content-Type: application/json');
        
        try {
            $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
            $category = isset($_GET['category']) ? trim($_GET['category']) : '';
            $minPrice = isset($_GET['min_price']) && is_numeric($_GET['min_price']) ? (float)$_GET['min_price'] : null;
            $maxPrice = isset($_GET['max_price']) && is_numeric($_GET['max_price']) ? (float)$_GET['max_price'] : null;
            $page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) && is_numeric($_GET['limit']) && $_GET['limit'] > 0 ? (int)$_GET['limit'] : 20;
            $offset = ($page - 1) * $limit;

            $products = $this->productModel->getProducts($keyword, $category, $limit, $offset, $minPrice, $maxPrice);
            $total = $this->productModel->getTotalProducts($keyword, $category, $minPrice, $maxPrice);
            $totalPages = ceil($total / $limit);

            if (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) {
                $scheme = $_SERVER['HTTP_X_FORWARDED_PROTO'];
            } elseif (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
                $scheme = 'https';
            } elseif (isset($_SERVER['REQUEST_SCHEME'])) {
                $scheme = $_SERVER['REQUEST_SCHEME'];
            } else {
                $scheme = 'http';
            }
            
            if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) {
                $host = $_SERVER['HTTP_X_FORWARDED_HOST'];
            } else {
                $host = $_SERVER['HTTP_HOST'] ?? 'localhost:8080';
            }
            
            $baseUrl = $scheme . '://' . $host;

            foreach ($products as &$product) {
                if (!empty($product['image']) && !filter_var($product['image'], FILTER_VALIDATE_URL)) {
                    $product['image_url'] = '/assets/images/shoes/' . $product['image'];
                } else {
                    $product['image_url'] = $product['image'] ?? '/public/placeholder.jpg';
                }
                $product['price'] = (float)$product['price'];
                $product['final_price'] = (float)$product['final_price'];
                $product['product_url'] = $baseUrl . '/index.php?controller=products&action=detail&id=' . $product['id'];
            }

            echo json_encode([
                'success' => true,
                'data' => [
                    'products' => $products,
                    'pagination' => [
                        'current_page' => $page,
                        'total_pages' => $totalPages,
                        'total_items' => $total,
                        'items_per_page' => $limit,
                        'has_next' => $page < $totalPages,
                        'has_prev' => $page > 1
                    ],
                    'filters' => [
                        'keyword' => $keyword,
                        'category' => $category,
                        'min_price' => $minPrice,
                        'max_price' => $maxPrice
                    ]
                ]
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => 'Internal server error',
                'message' => $e->getMessage()
            ], JSON_UNESCAPED_UNICODE);
        }
        exit;
    }
}