<?php
require_once 'models/ProductModelv2.php';
require_once 'models/CategoryModel.php';
require_once 'models/CommentModel.php';
require_once 'models/MemberModel.php';

class ProductsController {
    private $productModel;
    private $categoryModel;
    private $commentModel;
    private $memberModel;

    public function __construct() {
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
        $this->commentModel = new CommentModel();
        $this->memberModel = new MemberModel();
    }

    public function index() {
        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $category = isset($_GET['category']) ? trim($_GET['category']) : '';
        $minPrice = isset($_GET['min_price']) && is_numeric($_GET['min_price']) ? (float)$_GET['min_price'] : null;
        $maxPrice = isset($_GET['max_price']) && is_numeric($_GET['max_price']) ? (float)$_GET['max_price'] : null;
        $minSize = isset($_GET['min_size']) && is_numeric($_GET['min_size']) ? (float)$_GET['min_size'] : null;
        $maxSize = isset($_GET['max_size']) && is_numeric($_GET['max_size']) ? (float)$_GET['max_size'] : null;
        $saleOnly = isset($_GET['sale_only']) && $_GET['sale_only'] === '1';

        $perPage = 8;
        $currentPage = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
        $offset = ($currentPage - 1) * $perPage;

        $products = $this->productModel->getProducts($keyword, $category, $perPage, $offset, $minPrice, $maxPrice, $minSize, $maxSize, $saleOnly);

        $totalProducts = $this->productModel->getTotalProducts($keyword, $category, $minPrice, $maxPrice, $minSize, $maxSize, $saleOnly);
        $totalPages = ceil($totalProducts / $perPage);

        $categories = $this->categoryModel->getAllCategories();
        $availableSizes = $this->productModel->getAvailableSizes();
        $topSellers = $this->productModel->getTopSellers(4);
        $topPriced = $this->productModel->getTopPricedProducts(4);

        $productSlides = [];
        $randomCategories = $this->categoryModel->getRandomCategories(2);
        foreach ($randomCategories as $categoryRow) {
            $categoryProducts = $this->productModel->getProducts('', $categoryRow['CategoryID'], 10, 0);
            if (!empty($categoryProducts)) {
                $productSlides[] = [
                    'eyebrow' => 'Category',
                    'title' => $categoryRow['CategoryName'],
                    'description' => $categoryRow['Description'] ?? '',
                    'products' => $categoryProducts
                ];
            }
        }

        $newArrivals = $this->productModel->getLatestProducts(10);
        if (!empty($newArrivals)) {
            $productSlides[] = [
                'eyebrow' => 'New Arrivals',
                'title' => 'Fresh drops for you',
                'description' => 'Just landed in the last batch. Don’t miss out.',
                'products' => $newArrivals
            ];
        }

        $recentlyReviewed = $this->productModel->getRecentlyReviewedProducts(10);
        if (!empty($recentlyReviewed)) {
            $productSlides[] = [
                'eyebrow' => 'Loved Recently',
                'title' => 'Most talked-about pairs',
                'description' => 'These sneakers just earned new reviews.',
                'products' => $recentlyReviewed
            ];
        }

        $bestValue = $this->productModel->getLowestPriceProducts(10);
        if (!empty($bestValue)) {
            $productSlides[] = [
                'eyebrow' => 'Lowest Price For You',
                'title' => 'Value picks under the radar',
                'description' => 'Wallet-friendly, style-approved.',
                'products' => $bestValue
            ];
        }

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

        $productAddError = $_SESSION['product_error'] ?? null;
        unset($_SESSION['product_error']);

        // Get related products (same category)
        $relatedProducts = $this->productModel->getRelatedProducts($product['category_id'], $id, 4);

        // Get comments
        $comments = $this->commentModel->getCommentsByProductId($id);
        $ratingStats = $this->commentModel->getAverageRating($id);

        // Get member info if logged in
        $member = null;
        if (isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'member') {
            $member = $this->memberModel->getMemberById($_SESSION['user_id']);
        }

        // Handle submit comment
        if (isset($_POST['submit_comment'])) {
            $rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
            $content = isset($_POST['content']) ? trim($_POST['content']) : '';
            
            if ($rating < 1 || $rating > 5) {
                $_SESSION['comment_error'] = 'Vui lòng chọn đánh giá từ 1 đến 5 sao.';
            } elseif (empty($content)) {
                $_SESSION['comment_error'] = 'Vui lòng nhập nội dung comment.';
            } elseif (mb_strlen($content) > 65535) {
                $_SESSION['comment_error'] = 'Nội dung comment quá dài (tối đa 65535 ký tự).';
            } else {
                $memId = isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'member' 
                    ? (int)$_SESSION['user_id'] 
                    : null;
                $guestName = !$memId && isset($_POST['guest_name']) ? trim($_POST['guest_name']) : null;
                
                if (!$memId && empty($guestName)) {
                    $_SESSION['comment_error'] = 'Vui lòng nhập tên của bạn.';
                } elseif ($guestName !== null && mb_strlen($guestName) > 100) {
                    $_SESSION['comment_error'] = 'Tên của bạn quá dài (tối đa 100 ký tự).';
                } else {
                    $result = $this->commentModel->addComment($id, $memId, $rating, $content, $guestName);
                    
                    if (is_array($result) && isset($result['success']) && $result['success']) {
                        $_SESSION['comment_success'] = 'Cảm ơn bạn đã đánh giá sản phẩm!';
                        header('Location: index.php?controller=products&action=detail&id=' . $id);
                        exit;
                    } else {
                        $errorMsg = 'Có lỗi xảy ra khi gửi comment.';
                        if (is_array($result) && isset($result['error'])) {
                            $errorMsg .= ' ' . $result['error'];
                        }
                        $_SESSION['comment_error'] = $errorMsg;
                    }
                }
            }
        }

        // Handle add to cart
        if (isset($_POST['add_to_cart'])) {
            $selectedSize = isset($_POST['selected_size']) ? trim($_POST['selected_size']) : '';
            $sizeLookup = [];
            if (!empty($product['sizes'])) {
                foreach ($product['sizes'] as $sizeRow) {
                    $key = $this->normalizeSizeKey($sizeRow['size']);
                    $sizeLookup[$key] = $sizeRow;
                }
            }

            if ($selectedSize === '' || empty($sizeLookup)) {
                $_SESSION['product_error'] = 'Please choose a size before adding to cart.';
                header('Location: index.php?controller=products&action=detail&id=' . $id);
                exit;
            }

            $selectedKey = $this->normalizeSizeKey($selectedSize);
            if (!isset($sizeLookup[$selectedKey])) {
                $_SESSION['product_error'] = 'Selected size is not available.';
                header('Location: index.php?controller=products&action=detail&id=' . $id);
                exit;
            }

            $sizeData = $sizeLookup[$selectedKey];
            $maxQuantity = max(0, (int)$sizeData['quantity']);
            if ($maxQuantity <= 0) {
                $_SESSION['product_error'] = 'Selected size is out of stock.';
                header('Location: index.php?controller=products&action=detail&id=' . $id);
                exit;
            }

            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
            if ($quantity < 1) {
                $quantity = 1;
            }
            if ($quantity > $maxQuantity) {
                $quantity = $maxQuantity;
            }

            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            $cartKey = $product['id'] . ':' . $selectedKey;
            if (isset($_SESSION['cart'][$cartKey])) {
                $newQuantity = min($maxQuantity, $_SESSION['cart'][$cartKey]['quantity'] + $quantity);
                $_SESSION['cart'][$cartKey]['quantity'] = $newQuantity;
            } else {
                $_SESSION['cart'][$cartKey] = [
                    'id' => $product['id'],
                    'product_id' => $product['id'],
                    'name' => $product['name'],
                    'price' => $product['final_price'],
                    'image' => $product['image'],
                    'size' => $sizeData['size'],
                    'size_label' => $sizeData['label'] ?? $selectedSize,
                    'quantity' => $quantity
                ];
            }

            header('Location: index.php?controller=cart&action=index');
            exit;
        }

        require_once 'views/components/header.php';
        
        $renderView = function($product, $relatedProducts, $comments, $ratingStats, $member) use ($productAddError) {
            require 'views/pages/product-detail.php';
        };
        $renderView($product, $relatedProducts, $comments, $ratingStats, $member);
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

    private function normalizeSizeKey($value): string
    {
        return rtrim(rtrim(number_format((float)$value, 2, '.', ''), '0'), '.');
    }
}
