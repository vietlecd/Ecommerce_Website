<?php
class AdminDashboardController {
    public function dashboard() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        require_once 'views/admin/components/header.php';
        require_once 'views/admin/pages/dashboard.php';
    }
}