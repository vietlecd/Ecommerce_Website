<?php
require_once 'models/OrderModel.php';
require_once 'models/MemberModel.php';
require_once 'models/ProductModelv2.php';

class AdminDashboardController {
    public function dashboard() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        $orderModel = new OrderModel();
        $memberModel = new MemberModel();
        $productModel = new ProductModel();

        $totalOrders = $orderModel->getTotalOrders();
        $monthlyRevenue = $orderModel->getMonthlyRevenue();
        $totalCustomers = $memberModel->getTotalCustomers();
        $totalProducts = $productModel->getTotalProductsCount();
        $recentOrders = $orderModel->getOrders(5, 0);

        require_once 'views/admin/components/header.php';
        require_once 'views/admin/pages/dashboard.php';
    }
}