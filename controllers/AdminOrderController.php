<?php
require_once __DIR__ . '/../models/OrderModel.php';

class AdminOrderController {
    private $orderModel;

    public function __construct() {
        $this->orderModel = new OrderModel();
    }

    // Hiển thị danh sách đơn hàng
    public function orders() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /views/admin/index.php?controller=auth&action=login');
            exit;
        }

        $orders = $this->orderModel->getOrders();
        require_once __DIR__ . '/../views/admin/components/header.php';
        require_once __DIR__ . '/../views/admin/pages/orders.php';
    }

    // Hiển thị chi tiết đơn hàng
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
        $order = $this->orderModel->getOrderById($orderId);

        if (!$order) {
            header('Location: /views/admin/index.php?controller=adminOrder&action=orders');
            exit;
        }

        require_once __DIR__ . '/../views/admin/components/header.php';
        require_once __DIR__ . '/../views/admin/pages/order-detail.php';
    }

    // Cập nhật trạng thái đơn hàng
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
        $status = $_GET['status'];

        if ($this->orderModel->updateOrderStatus($orderId, $status)) {
            header('Location: /views/admin/index.php?controller=adminOrder&action=orders');
        } else {
            header('Location: /views/admin/index.php?controller=adminOrder&action=orders');
        }
        exit;
    }
}