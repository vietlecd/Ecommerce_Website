<?php
require_once 'models/PromotionalProductModel.php';
require_once 'models/ProductModelv2.php';

class AdminPromotionController {
    private $promotionModel;
    private $productModel;

    public function __construct() {
        $this->promotionModel = new PromotionalProductModel();
        $this->productModel = new ProductModel();
    }

    public function index() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        $limit = 5;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page);
        $offset = ($page - 1) * $limit;

        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $sort = isset($_GET['sort']) && in_array($_GET['sort'], ['ASC', 'DESC']) ? $_GET['sort'] : 'ASC';

        $promotions = $this->promotionModel->getAllPromotions($limit, $offset, $keyword, $sort);
        $totalPromotions = $this->promotionModel->getPromotionsCount($keyword);
        $totalPages = ceil($totalPromotions / $limit);

        require_once 'views/admin/pages/promotion-list.php';
    }

    public function create() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $promotionName = isset($_POST['promotion_name']) ? trim($_POST['promotion_name']) : '';
            $startDate = isset($_POST['start_date']) ? trim($_POST['start_date']) : '';
            $endDate = isset($_POST['end_date']) ? trim($_POST['end_date']) : '';
            $promotionType = isset($_POST['promotion_type']) ? trim($_POST['promotion_type']) : '';
            
            $discountPercentage = null;
            $fixedPrice = null;

            if (!in_array($promotionType, ['discount', 'fixed'])) {
                $_SESSION['error'] = "Invalid promotion type.";
                header('Location: index.php?controller=adminPromotion&action=create');
                exit;
            }

            if ($promotionType === 'discount') {
                $discountPercentage = isset($_POST['discount_percentage']) ? (float)$_POST['discount_percentage'] : 0;
                if ($discountPercentage <= 0 || $discountPercentage > 100) {
                    $_SESSION['error'] = "Discount percentage must be between 0 and 100.";
                    header('Location: index.php?controller=adminPromotion&action=create');
                    exit;
                }
            } elseif ($promotionType === 'fixed') {
                $fixedPrice = isset($_POST['fixed_price']) ? (float)$_POST['fixed_price'] : 0;
                if ($fixedPrice <= 0) {
                    $_SESSION['error'] = "Fixed price must be greater than 0.";
                    header('Location: index.php?controller=adminPromotion&action=create');
                    exit;
                }
            }

            if (empty($promotionName) || empty($startDate) || empty($endDate) || empty($promotionType)) {
                $_SESSION['error'] = "All fields are required.";
                header('Location: index.php?controller=adminPromotion&action=create');
                exit;
            }

            if (strtotime($endDate) <= strtotime($startDate)) {
                $_SESSION['error'] = "End date must be after start date.";
                header('Location: index.php?controller=adminPromotion&action=create');
                exit;
            }

            try {
                $this->promotionModel->createPromotion(
                    $promotionName,
                    $startDate,
                    $endDate,
                    $discountPercentage,
                    $fixedPrice,
                    $promotionType
                );
                $_SESSION['message'] = "Promotion created successfully.";
                header('Location: index.php?controller=adminPromotion&action=index');
                exit;
            } catch (Exception $e) {
                $_SESSION['error'] = "Error creating promotion: " . $e->getMessage();
                header('Location: index.php?controller=adminPromotion&action=create');
                exit;
            }
        }

        require_once 'views/admin/pages/promotion-create.php';
    }

    public function edit() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        if (!isset($_GET['promotion_id']) || !is_numeric($_GET['promotion_id'])) {
            header('Location: index.php?controller=adminPromotion&action=index');
            exit;
        }

        $promotionId = (int)$_GET['promotion_id'];
        $promotion = $this->promotionModel->getPromotionById($promotionId);

        if (!$promotion) {
            $_SESSION['error'] = "Promotion not found.";
            header('Location: index.php?controller=adminPromotion&action=index');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $promotionName = isset($_POST['promotion_name']) ? trim($_POST['promotion_name']) : '';
            $startDate = isset($_POST['start_date']) ? trim($_POST['start_date']) : '';
            $endDate = isset($_POST['end_date']) ? trim($_POST['end_date']) : '';
            $promotionType = isset($_POST['promotion_type']) ? trim($_POST['promotion_type']) : '';
            
            $discountPercentage = null;
            $fixedPrice = null;

            if (!in_array($promotionType, ['discount', 'fixed'])) {
                $_SESSION['error'] = "Invalid promotion type.";
                header('Location: index.php?controller=adminPromotion&action=edit&promotion_id=' . $promotionId);
                exit;
            }

            if ($promotionType === 'discount') {
                $discountPercentage = isset($_POST['discount_percentage']) ? (float)$_POST['discount_percentage'] : 0;
                if ($discountPercentage <= 0 || $discountPercentage > 100) {
                    $_SESSION['error'] = "Discount percentage must be between 0 and 100.";
                    header('Location: index.php?controller=adminPromotion&action=edit&promotion_id=' . $promotionId);
                    exit;
                }
            } elseif ($promotionType === 'fixed') {
                $fixedPrice = isset($_POST['fixed_price']) ? (float)$_POST['fixed_price'] : 0;
                if ($fixedPrice <= 0) {
                    $_SESSION['error'] = "Fixed price must be greater than 0.";
                    header('Location: index.php?controller=adminPromotion&action=edit&promotion_id=' . $promotionId);
                    exit;
                }
            }

            if (empty($promotionName) || empty($startDate) || empty($endDate) || empty($promotionType)) {
                $_SESSION['error'] = "All fields are required.";
                header('Location: index.php?controller=adminPromotion&action=edit&promotion_id=' . $promotionId);
                exit;
            }

            if (strtotime($endDate) <= strtotime($startDate)) {
                $_SESSION['error'] = "End date must be after start date.";
                header('Location: index.php?controller=adminPromotion&action=edit&promotion_id=' . $promotionId);
                exit;
            }

            try {
                $this->promotionModel->updatePromotion(
                    $promotionId,
                    $promotionName,
                    $startDate,
                    $endDate,
                    $discountPercentage,
                    $fixedPrice,
                    $promotionType
                );
                $_SESSION['message'] = "Promotion updated successfully.";
                header('Location: index.php?controller=adminPromotion&action=index');
                exit;
            } catch (Exception $e) {
                $_SESSION['error'] = "Error updating promotion: " . $e->getMessage();
                header('Location: index.php?controller=adminPromotion&action=edit&promotion_id=' . $promotionId);
                exit;
            }
        }

        require_once 'views/admin/pages/promotion-edit.php';
    }

    public function delete() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        if (!isset($_GET['promotion_id']) || !is_numeric($_GET['promotion_id'])) {
            header('Location: index.php?controller=adminPromotion&action=index');
            exit;
        }

        $promotionId = (int)$_GET['promotion_id'];
        try {
            $this->promotionModel->deletePromotion($promotionId);
            $_SESSION['message'] = "Promotion deleted successfully.";
        } catch (Exception $e) {
            $_SESSION['error'] = "Error deleting promotion: " . $e->getMessage();
        }

        header('Location: index.php?controller=adminPromotion&action=index');
        exit;
    }

    public function manageProducts() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        if (!isset($_GET['promotion_id']) || !is_numeric($_GET['promotion_id'])) {
            header('Location: index.php?controller=adminPromotion&action=index');
            exit;
        }

        $promotionId = (int)$_GET['promotion_id'];
        $promotion = $this->promotionModel->getPromotionById($promotionId);

        if (!$promotion) {
            header('Location: index.php?controller=adminPromotion&action=index');
            exit;
        }

        $products = $this->productModel->getAllProducts();
        $assignedProducts = $this->promotionModel->getProductsByPromotionId($promotionId);

        $allAssignedProducts = [];
        $allPromotions = $this->promotionModel->getAllPromotions();
        foreach ($allPromotions as $promo) {
            if ($promo['promotion_id'] != $promotionId) {
                $promoProducts = $this->promotionModel->getProductsByPromotionId($promo['promotion_id']);
                foreach ($promoProducts as $productId) {
                    $allAssignedProducts[$productId] = $promo['promotion_name'];
                }
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $selectedProducts = isset($_POST['products']) ? $_POST['products'] : [];

            $this->promotionModel->removeAllProductsFromPromotion($promotionId);

            foreach ($selectedProducts as $productId) {
                $this->promotionModel->assignProductToPromotion($promotionId, $productId);
            }

            $_SESSION['message'] = "Products updated successfully.";
            header('Location: index.php?controller=adminPromotion&action=index');
            exit;
        }

        require_once 'views/admin/pages/promotion-manage-products.php';
    }
}