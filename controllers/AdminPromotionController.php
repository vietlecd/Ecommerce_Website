<?php
require_once 'models/PromotionalProductModel.php';
require_once 'models/ProductModelv2.php';
require_once 'models/PromotionModel.php';

class AdminPromotionController
{
    private $promotionModel;
    private $productModel;
    private $promotionalProductModel;

    public function __construct()
    {
        $this->promotionalProductModel = new PromotionalProductModel();
        $this->promotionModel = new PromotionModel();
        $this->productModel = new ProductModel();
    }

    public function create()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        $jsonError = function (string $message) use ($isAjax) {
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'success' => false,
                    'error'   => $message,
                ]);
                exit;
            }
            $_SESSION['error'] = $message;
            header('Location: index.php?controller=adminPromotion&action=manage');
            exit;
        };

        $jsonSuccess = function (string $message) use ($isAjax) {
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'success' => true,
                    'message' => $message,
                ]);
                exit;
            }
            $_SESSION['message'] = $message;
            header('Location: index.php?controller=adminPromotion&action=manage');
            exit;
        };

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                echo json_encode([
                    'success' => false,
                    'error'   => 'Invalid request method.',
                ]);
                exit;
            }
            header('Location: index.php?controller=adminPromotion&action=manage');
            exit;
        }

        $promotionName = isset($_POST['promotion_name']) ? trim($_POST['promotion_name']) : '';
        $startDateRaw  = isset($_POST['start_date']) ? trim($_POST['start_date']) : '';
        $endDateRaw    = isset($_POST['end_date']) ? trim($_POST['end_date']) : '';
        $promotionType = isset($_POST['promotion_type']) ? trim($_POST['promotion_type']) : '';

        $discountPercentage = null;
        $fixedPrice         = null;

        if ($promotionType === 'discount') {
            $discountPercentage = isset($_POST['discount_percentage'])
                ? (float)$_POST['discount_percentage']
                : 0.0;
        } elseif ($promotionType === 'fixed') {
            $fixedPrice = isset($_POST['fixed_price'])
                ? (float)$_POST['fixed_price']
                : 0.0;
        }

        $validationError = $this->validatePromotionData(
            $promotionName,
            $promotionType,
            $startDateRaw,
            $endDateRaw,
            $discountPercentage,
            $fixedPrice
        );

        if ($validationError !== null) {
            $jsonError($validationError);
        }

        $startDateObj = DateTime::createFromFormat('Y-m-d', $startDateRaw);
        $endDateObj   = DateTime::createFromFormat('Y-m-d', $endDateRaw);
        $startDate = $startDateObj->format('Y-m-d 00:00:00');
        $endDate   = $endDateObj->format('Y-m-d 23:59:59');

        try {
            $this->promotionModel->createPromotion(
                $promotionName,
                $startDate,
                $endDate,
                $discountPercentage,
                $fixedPrice,
                $promotionType
            );

            $jsonSuccess("Promotion created successfully.");
        } catch (Exception $e) {
            error_log("AdminPromotionController::create() - Error creating promotion: " . $e->getMessage());
            $jsonError("Failed to create promotion. Please try again.");
        }
    }


    public function edit()
    {
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            if ($isAjax) {
                http_response_code(401);
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'error'   => 'Unauthorized.'
                ]);
                exit;
            }

            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            if ($isAjax) {
                http_response_code(405);
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false,
                    'error'   => 'Invalid request method.'
                ]);
                exit;
            }

            header('Location: index.php?controller=adminPromotion&action=manage');
            exit;
        }

        $promotionId = 0;
        if (isset($_GET['promotion_id']) && is_numeric($_GET['promotion_id'])) {
            $promotionId = (int) $_GET['promotion_id'];
        } elseif (isset($_POST['promotion_id']) && is_numeric($_POST['promotion_id'])) {
            $promotionId = (int) $_POST['promotion_id'];
        }

        if ($promotionId <= 0) {
            return $this->editRespondError($isAjax, 'Invalid promotion id.');
        }

        $promotion = $this->promotionModel->getPromotionById($promotionId);
        if (!$promotion) {
            return $this->editRespondError($isAjax, 'Promotion not found.');
        }

        $promotionName = isset($_POST['promotion_name']) ? trim($_POST['promotion_name']) : '';
        $startDateRaw  = isset($_POST['start_date']) ? trim($_POST['start_date']) : '';
        $endDateRaw    = isset($_POST['end_date']) ? trim($_POST['end_date']) : '';
        $promotionType = isset($_POST['promotion_type']) ? trim($_POST['promotion_type']) : '';

        $discountPercentage = null;
        $fixedPrice         = null;

        if ($promotionType === 'discount') {
            $discountPercentage = isset($_POST['discount_percentage'])
                ? (float) $_POST['discount_percentage']
                : 0.0;
        } elseif ($promotionType === 'fixed') {
            $fixedPrice = isset($_POST['fixed_price'])
                ? (float) $_POST['fixed_price']
                : 0.0;
        }

        $validationError = $this->validatePromotionData(
            $promotionName,
            $promotionType,
            $startDateRaw,
            $endDateRaw,
            $discountPercentage,
            $fixedPrice
        );

        if ($validationError !== null) {
            return $this->editRespondError($isAjax, $validationError, $promotionId);
        }

        if ($startDateRaw === '' || $endDateRaw === '') {
            return $this->editRespondError($isAjax, 'All fields are required.', $promotionId);
        }

        $startDateObj = DateTime::createFromFormat('Y-m-d', $startDateRaw);
        $endDateObj   = DateTime::createFromFormat('Y-m-d', $endDateRaw);
        $startDate = $startDateObj->format('Y-m-d 00:00:00');
        $endDate   = $endDateObj->format('Y-m-d 23:59:59');

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

            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'message' => 'Promotion updated successfully.'
                ]);
                exit;
            }

            $_SESSION['message'] = 'Promotion updated successfully.';
            header('Location: index.php?controller=adminPromotion&action=manage');
            exit;
        } catch (Exception $e) {
            error_log("AdminPromotionController::edit() - Error updating promotion ID {$promotionId}: " . $e->getMessage());
            return $this->editRespondError(
                $isAjax,
                'Failed to update promotion. Please try again.',
                $promotionId,
                500
            );
        }
    }

    private function validatePromotionData(
        string $promotionName,
        string $promotionType,
        string $startDateRaw,
        string $endDateRaw,
        ?float $discountPercentage,
        ?float $fixedPrice
    ): ?string {
        if (mb_strlen($promotionName) > 100) {
            return "Promotion name must not exceed 100 characters.";
        }

        if (mb_strlen($promotionType) > 50) {
            return "Promotion type must not exceed 50 characters.";
        }

        if (!in_array($promotionType, ['discount', 'fixed'], true)) {
            return "Invalid promotion type.";
        }

        $startDateObj = DateTime::createFromFormat('Y-m-d', $startDateRaw);
        $endDateObj   = DateTime::createFromFormat('Y-m-d', $endDateRaw);

        if ($startDateObj === false) {
            return "Invalid start date.";
        }

        if ($endDateObj === false) {
            return "Invalid end date.";
        }

        if ($endDateObj <= $startDateObj) {
            return "End date must be after start date.";
        }

        if ($promotionType === 'discount') {
            if ($discountPercentage === null || $discountPercentage <= 0 || $discountPercentage > 100) {
                return "Discount percentage must be between 0 and 100.";
            }

            if ($discountPercentage >= 1000) {
                return "Discount percentage must be less than 1000.";
            }
        } elseif ($promotionType === 'fixed') {
            if ($fixedPrice === null || $fixedPrice <= 0) {
                return "Fixed price must be greater than 0.";
            }

            if ($fixedPrice >= 100000000) {
                return "Fixed price must be less than 100,000,000.";
            }
        }

        if ($promotionName === '' || $promotionType === '') {
            return "All fields are required.";
        }

        return null;
    }

    private function editRespondError(bool $isAjax, string $message, ?int $promotionId = null, int $statusCode = 400)
    {
        if ($isAjax) {
            http_response_code($statusCode);
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'error'   => $message
            ]);
            exit;
        }

        $_SESSION['error'] = $message;

        if ($promotionId) {
            header('Location: index.php?controller=adminPromotion&action=edit&promotion_id=' . (int) $promotionId);
        } else {
            header('Location: index.php?controller=adminPromotion&action=manage');
        }
        exit;
    }


    public function delete()
    {
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
            error_log("AdminPromotionController::delete() - Error deleting promotion ID {$promotionId}: " . $e->getMessage());
            $_SESSION['error'] = "Failed to delete promotion. Please try again.";
        }

        header('Location: index.php?controller=adminPromotion&action=manage');
        exit;
    }


    public function manageProducts()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        if (!isset($_GET['promotion_id']) || !is_numeric($_GET['promotion_id'])) {
            header('Location: index.php?controller=adminPromotion&action=manage');
            exit;
        }

        $promotionId = (int)$_GET['promotion_id'];
        $promotion   = $this->promotionModel->getPromotionById($promotionId);

        if (!$promotion) {
            header('Location: index.php?controller=adminPromotion&action=manage');
            exit;
        }

        $products = $this->productModel->getAllProducts();

        $assignedProducts = $this->promotionModel->getProductsByPromotionId($promotionId);
        $assignedProducts = array_map('intval', $assignedProducts);

        $allAssignedProducts = [];
        $allPromotions       = $this->promotionModel->getAllPromotions(null, 0, '', 'ASC', null, null);

        foreach ($allPromotions as $promo) {
            $pId   = (int)($promo['PromotionID']   ?? $promo['promotion_id']   ?? 0);
            $pName =        $promo['PromotionName'] ?? $promo['promotion_name'] ?? '';

            if ($pId === 0 || $pId === $promotionId) {
                continue;
            }

            $promoProducts = $this->promotionModel->getProductsByPromotionId($pId);
            foreach ($promoProducts as $productId) {
                $productId = (int)$productId;
                if (!isset($allAssignedProducts[$productId])) {
                    $allAssignedProducts[$productId] = [];
                }
                $allAssignedProducts[$productId][] = $pName;
            }
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $selectedProducts = isset($_POST['products']) && is_array($_POST['products'])
                ? array_map('intval', $_POST['products'])
                : [];

            $this->promotionModel->removeAllProductsFromPromotion($promotionId);

            foreach ($selectedProducts as $productId) {
                $this->promotionModel->assignProductToPromotion($promotionId, $productId);
            }

            $_SESSION['message'] = "Products updated successfully.";
            header('Location: index.php?controller=adminPromotion&action=manageProducts&promotion_id=' . $promotionId);
            exit;
        }

        require_once 'views/admin/components/header.php';
        require_once 'views/admin/pages/promotion-manage-products.php';
        require_once 'views/admin/components/admin_footer.php';
    }



    public function manage()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        $limit = isset($_GET['limit']) ? max(1, (int)$_GET['limit']) : 20;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page);
        $offset = ($page - 1) * $limit;

        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $sort = isset($_GET['sort']) && in_array($_GET['sort'], ['ASC', 'DESC']) ? $_GET['sort'] : 'ASC';

        $fromDate = isset($_GET['from']) ? trim($_GET['from']) : '';
        $toDate   = isset($_GET['to'])   ? trim($_GET['to'])   : '';

        $promotions = $this->promotionModel->getAllPromotions(
            $limit,
            $offset,
            $keyword,
            $sort,
            $fromDate ?: null,
            $toDate   ?: null
        );

        $totalPromotions = $this->promotionModel->getPromotionsCount(
            $keyword,
            $fromDate ?: null,
            $toDate   ?: null
        );
        $totalPages = ceil($totalPromotions / $limit);

        $keywordSafe = htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8');
        $sortSafe    = htmlspecialchars($sort, ENT_QUOTES, 'UTF-8');
        $fromSafe    = htmlspecialchars($fromDate, ENT_QUOTES, 'UTF-8');
        $toSafe      = htmlspecialchars($toDate, ENT_QUOTES, 'UTF-8');
        require_once 'views/admin/components/header.php';
        require_once 'views/admin/pages/manage-promotion.php';
        require_once 'views/admin/components/admin_footer.php';
    }
}
