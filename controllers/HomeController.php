<?php
require_once __DIR__ . '/../models/ProductModelv2.php';
require_once __DIR__ . '/../models/CategoryModel.php';

class HomeController {
    private $productModel;
    private $categoryModel;

    public function __construct() {
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index() {
        $featuredProducts = $this->productModel->getRandomProducts(4);

        $categories = $this->categoryModel->getAllCategories();

        $headerPath = dirname(__DIR__) . '/views/components/header.php';
        $viewPath = dirname(__DIR__) . '/views/pages/home.php';
        $footerPath = dirname(__DIR__) . '/views/components/footer.php';

        if (file_exists($headerPath)) {
            require_once $headerPath;
        } else {
            die("Header file not found: $headerPath");
        }

        if (file_exists($viewPath)) {
            $renderView = function ($featuredProducts, $categories) use ($viewPath) {
                require $viewPath;
            };
            $renderView($featuredProducts, $categories);
        } else {
            die("View file not found: $viewPath");
        }

        if (file_exists($footerPath)) {
            require_once $footerPath;
        } else {
            die("Footer file not found: $footerPath");
        }
    }
    
    public function chat() {
        $viewPath = dirname(__DIR__) . '/views/pages/chat-render.php';
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            die("Chat view file not found: $viewPath");
        }
        exit;
    }
}
