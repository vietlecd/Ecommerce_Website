<?php
require_once __DIR__ . '/../models/ProductModelv2.php';
require_once __DIR__ . '/../models/CategoryModel.php';
require_once __DIR__ . '/../models/NewsModel.php';

class HomeController {
    private $productModel;
    private $categoryModel;
    private $newsModel;

    public function __construct() {
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
        $this->newsModel = new NewsModel();
    }

    public function index() {
        $featuredProducts = $this->productModel->getRandomProducts(4);
        $categories = $this->categoryModel->getAllCategories();
        $categoryIds = array_column($categories, 'CategoryID');
        $categoryStats = $this->productModel->getCategoryStats($categoryIds);
        $highDiscountSales = $this->productModel->getHighDiscountSales(50, 12);
        $weeklySales = $this->productModel->getSalesEndingSoon(7, 8);
        // Get latest 3 news for widget
        $latestNews = $this->newsModel->getAllNews('', 3, 0);

        $headerPath = dirname(__DIR__) . '/views/components/header.php';
        $viewPath = dirname(__DIR__) . '/views/pages/home.php';
        $footerPath = dirname(__DIR__) . '/views/components/footer.php';

        if (file_exists($headerPath)) {
            require_once $headerPath;
        } else {
            die("Header file not found: $headerPath");
        }

        if (file_exists($viewPath)) {
            $renderView = function ($featuredProducts, $categories, $latestNews, $highDiscountSales, $weeklySales, $categoryStats) use ($viewPath) {
                require $viewPath;
            };
            $renderView($featuredProducts, $categories, $latestNews, $highDiscountSales, $weeklySales, $categoryStats);
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
