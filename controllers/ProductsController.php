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

        $perPage = 8; // number of products per page
        $currentPage = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
        $offset = ($currentPage - 1) * $perPage;

        $products = $this->productModel->getProducts($keyword, $category, $perPage, $offset);
        $totalProducts = $this->productModel->getTotalProducts($keyword, $category);
        $totalPages = (int)ceil($totalProducts / $perPage);
        $categories = $this->productModel->getCategories();
        $selectedCategory = $category;

        require_once 'views/components/header.php';
        require_once 'views/pages/products.php';
    }

    public function detail() {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            header('Location: index.php?controller=products&action=index');
            exit;
        }

        $id = (int)$_GET['id'];
        $product = $this->productModel->getProductById($id);

        if (!$product) {
            header('Location: index.php?controller=products&action=index');
            exit;
        }

        $errorMessage = '';
        $selectedQuantity = isset($_POST['quantity']) && (int)$_POST['quantity'] > 0 ? (int)$_POST['quantity'] : 1;

        if (isset($_POST['add_to_cart'])) {
            $availableStock = isset($product['Stock']) ? (int)$product['Stock'] : 0;

            if ($availableStock <= 0) {
                $errorMessage = 'This product is currently out of stock.';
            } else {
                if (!isset($_SESSION['cart'])) {
                    $_SESSION['cart'] = [];
                }

                $selectedQuantity = min($selectedQuantity, $availableStock);
                $existingQuantity = isset($_SESSION['cart'][$id]) ? (int)$_SESSION['cart'][$id]['quantity'] : 0;
                $newQuantity = $existingQuantity + $selectedQuantity;

                if ($newQuantity > $availableStock) {
                    $maxAdditional = max(0, $availableStock - $existingQuantity);
                    $errorMessage = $maxAdditional > 0
                        ? 'Not enough stock. You can only add ' . $maxAdditional . ' more.'
                        : 'Cannot add more items because the cart already holds the maximum stock.';
                } else {
                    $_SESSION['cart'][$id] = [
                        'id' => $product['id'],
                        'name' => $product['name'],
                        'price' => $product['final_price'],
                        'image' => $product['image'],
                        'quantity' => $newQuantity,
                    ];

                    header('Location: index.php?controller=cart&action=index');
                    exit;
                }
            }
        }

        require_once 'views/components/header.php';
        require_once 'views/pages/product-detail.php';
    }
}
