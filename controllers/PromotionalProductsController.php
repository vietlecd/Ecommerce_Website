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
            echo "Lỗi khi lấy danh sách sản phẩm khuyến mãi: " . $e->getMessage() . "<br>";
            error_log("Lỗi lấy danh sách sản phẩm khuyến mãi: " . $e->getMessage(), 3, 'logs/errors.log');
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
            echo "Lỗi khi lấy danh sách sản phẩm: " . $e->getMessage() . "<br>";
            error_log("Lỗi lấy danh sách sản phẩm: " . $e->getMessage(), 3, 'logs/errors.log');
            exit;
        }

        require_once 'views/components/header.php';
        require_once 'views/pages/promotional-products.php';
        require_once 'views/components/footer.php';
    }


    public function detail()
    {
        // Validate id
        if (!isset($_GET['id']) || !ctype_digit($_GET['id'])) {
            header('Location: index.php?controller=promotionalProducts&action=index');
            exit;
        }

        $id = (int)$_GET['id'];

        try {
            // Lấy sản phẩm (đã kèm promotion + final_price nếu có)
            $product = $this->productModel->getProductById($id);
        } catch (Exception $e) {
            error_log("Lỗi lấy thông tin sản phẩm (id=$id): " . $e->getMessage(), 3, 'logs/errors.log');
            header('Location: index.php?controller=promotionalProducts&action=index');
            exit;
        }

        if (!$product) {
            // Không echo gì trước khi redirect
            header('Location: index.php?controller=promotionalProducts&action=index');
            exit;
        }

        // Nếu submit form "Add to cart"
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {

            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
            if ($quantity < 1) $quantity = 1;

            // Đảm bảo session đã start ở đây nếu chưa start ở chỗ khác
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if (!isset($_SESSION['cart'])) {
                $_SESSION['cart'] = [];
            }

            // Giá dùng để thêm vào cart: ưu tiên final_price, fallback về price
            $price = isset($product['final_price']) && $product['final_price'] !== null
                ? (float)$product['final_price']
                : (float)$product['price'];

            if (isset($_SESSION['cart'][$id])) {
                // Cộng dồn số lượng
                $_SESSION['cart'][$id]['quantity'] += $quantity;
                // Cập nhật giá + promotion cho chắc
                $_SESSION['cart'][$id]['price']     = $price;
                $_SESSION['cart'][$id]['promotion'] = $product['promotion'] ?? null;
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

        // Gọi view
        require_once 'views/components/header.php';
        require_once 'views/pages/promotional-product-detail.php';
    }
}
