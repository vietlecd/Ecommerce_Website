<?php
require_once __DIR__ . '/../models/OrderModel.php';

class AdminOrderController {
    private $orderModel;

    public function __construct() {
        $this->orderModel = new OrderModel();
    }

    // Orders list with filter + sort
    public function orders() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /views/admin/index.php?controller=auth&action=login');
            exit;
        }

        // Filters
        $keyword      = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $statusFilter = isset($_GET['status']) ? trim($_GET['status']) : '';
        $sort         = isset($_GET['sort']) ? trim($_GET['sort']) : 'date_desc';

        $perPage     = 10;
        $currentPage = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0
            ? (int)$_GET['page']
            : 1;
        $offset      = ($currentPage - 1) * $perPage;

        $totalOrders = $this->orderModel->getTotalOrders($keyword, $statusFilter);
        $totalPages  = $totalOrders > 0 ? (int)ceil($totalOrders / $perPage) : 1;

        if ($currentPage > $totalPages) {
            $currentPage = $totalPages;
            $offset      = ($currentPage - 1) * $perPage;
        }

        $orders = $this->orderModel->getOrders(
            $perPage,
            $offset,
            $keyword,
            $statusFilter,
            $sort
        );

        $pageTitle = 'Order management';

        // truyền filter xuống view
        require_once __DIR__ . '/../views/admin/components/header.php';
        require_once __DIR__ . '/../views/admin/pages/orders.php';
    }

    // Order detail
    public function orderDetail() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /views/admin/index.php?controller=auth&action=login');
            exit;
        }

        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            header('Location: /views/admin/index.php?controller=adminOrder&action=orders');
            exit;
        }

        $orderId = intval($_GET['id']);
        $order   = $this->orderModel->getOrderById($orderId);

        if (!$order) {
            header('Location: /views/admin/index.php?controller=adminOrder&action=orders');
            exit;
        }

        $pageTitle = 'Order #' . $orderId;
        require_once __DIR__ . '/../views/admin/components/header.php';
        require_once __DIR__ . '/../views/admin/pages/order-detail.php';
    }

    // Update order status
    public function updateOrderStatus() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /views/admin/index.php?controller=auth&action=login');
            exit;
        }

        if (!isset($_GET['id']) || !isset($_GET['status'])) {
            header('Location: /views/admin/index.php?controller=adminOrder&action=orders');
            exit;
        }

        $orderId = intval($_GET['id']);
        $status  = $_GET['status'];

        $this->orderModel->updateOrderStatus($orderId, $status);

        // Giữ nguyên filter khi reload nếu muốn thì có thể build query, tạm thời quay lại list đơn
        header('Location: /views/admin/index.php?controller=adminOrder&action=orders');
        exit;
    }
}
