<?php
class AdminDashboardController {
    public function dashboard() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        // Chuẩn bị dữ liệu thật cho Dashboard
        require_once __DIR__ . '/../models/Database.php';
        $database = new Database();
        $pdo = $database->getConnection();

        // Thống kê tổng quan
        $stats = [
            'totalOrders' => 0,
            'monthlyRevenue' => 0,
            'totalCustomers' => 0,
            'totalProducts' => 0,
        ];

        // Tổng số đơn hàng
        $stats['totalOrders'] = (int)$pdo->query("SELECT COUNT(*) FROM `order`")->fetchColumn();

        // Doanh thu tháng hiện tại
        $stmtRevenue = $pdo->prepare("SELECT COALESCE(SUM(Total_price), 0) FROM `order` WHERE DATE_FORMAT(`Date`, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')");
        $stmtRevenue->execute();
        $stats['monthlyRevenue'] = (float)$stmtRevenue->fetchColumn();

        // Tổng số khách hàng
        $stats['totalCustomers'] = (int)$pdo->query("SELECT COUNT(*) FROM `member`")->fetchColumn();

        // Tổng số sản phẩm (bảng shoes)
        $stats['totalProducts'] = (int)$pdo->query("SELECT COUNT(*) FROM `shoes`")->fetchColumn();

        // Đơn hàng gần đây
        $recentOrdersStmt = $pdo->query(
            "SELECT o.OrderID, m.Name AS customer_name, o.Date, o.Total_price, o.Status
             FROM `order` o
             JOIN member m ON o.MemberID = m.MemberID
             ORDER BY o.Date DESC
             LIMIT 5"
        );
        $recentOrders = $recentOrdersStmt->fetchAll();

        require_once 'views/admin/components/header.php';
        require_once 'views/admin/pages/dashboard.php';
    }
}