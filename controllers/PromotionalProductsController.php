<?php
require_once 'models/PromotionalProductModel.php';
require_once 'models/PromotionModel.php';

class PromotionalProductsController
{
    private $productModel;
    private $promotionModel;

    public function __construct()
    {
        try {
            $this->productModel = new PromotionalProductModel();
            $this->promotionModel = new PromotionModel();
        } catch (Exception $e) {
            error_log("Lỗi khởi tạo PromotionalProductModel: " . $e->getMessage(), 3, 'logs/errors.log');
            exit;
        }
    }

    public function index()
    {

        $promotionId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

        try {
            $promotion = $promotionId > 0 ? $this->promotionModel->getPromotionById($promotionId) : null;
        } catch (Exception $e) {
            echo "Error while fetching promotion products: " . $e->getMessage() . "<br>";
            error_log("Error fetching promotion products: " . $e->getMessage(), 3, 'logs/errors.log');
            exit;
        }

        $page  = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 12;

        if ($page < 1)  $page  = 1;
        if ($limit < 1) $limit = 12;

        try {
            $products = $this->productModel->getAllProducts();

            if ($promotionId > 0) {
                $productIds = $this->productModel->getProductsByPromotionId($promotionId);

                if (!empty($productIds)) {
                    $products = array_values(
                        array_filter($products, function ($product) use ($productIds) {
                            return in_array((int)$product['id'], $productIds, true);
                        })
                    );
                } else {
                    $products = [];
                }
            }

            $totalItems = count($products);
            $totalPages = max(1, (int)ceil($totalItems / $limit));

            if ($page > $totalPages) {
                $page = $totalPages;
            }

            $offset   = ($page - 1) * $limit;
            $products = array_slice($products, $offset, $limit);

            $pagination = [
                'page'       => $page,
                'limit'      => $limit,
                'totalItems' => $totalItems,
                'totalPages' => $totalPages,
                'offset'     => $offset,
            ];
        } catch (Exception $e) {
            echo "Error while fetching products: " . $e->getMessage() . "<br>";
            error_log("Error fetching products: " . $e->getMessage(), 3, 'logs/errors.log');
            exit;
        }

        require_once 'views/components/header.php';
        require_once 'views/pages/promotional-products.php';
        require_once 'views/components/footer.php';
    }

    public function detail()
    {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            header('Location: index.php?controller=promotionalProducts&action=index');
            exit;
        }

        $id = intval($_GET['id']);

        try {
            $product = $this->productModel->getProductById($id);
        } catch (Exception $e) {
            error_log("Lỗi lấy thông tin sản phẩm (id=$id): " . $e->getMessage(), 3, 'logs/errors.log');
            header('Location: index.php?controller=promotionalProducts&action=index');
            exit;
        }

        if (!$product) {
            header('Location: index.php?controller=promotionalProducts&action=index');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {

            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
            if ($quantity < 1) $quantity = 1;

            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            $price = isset($product['final_price']) && $product['final_price'] !== null
                ? (float)$product['final_price']
                : (float)$product['price'];

            if (isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$id] = [
                    'id'        => $product['id'],
                    'name'      => $product['name'],
                    'price'     => $price,
                    'image'     => $product['image'] ?? null,
                    'quantity'  => $quantity,
                    'promotion' => $product['promotion'] ?? null,
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
