<?php
require_once __DIR__ . '/../models/MemberModel.php';

class AdminCustomerController {
    private $memberModel;

    public function __construct() {
        $this->memberModel = new MemberModel();
    }

    public function customers() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /index.php?controller=auth&action=login');
            exit;
        }

        $members = $this->memberModel->getAllMembers();

        $headerPath = dirname(__DIR__) . '/views/admin/components/header.php';
        $viewPath = dirname(__DIR__) . '/views/admin/pages/customers.php';

        if (file_exists($headerPath)) {
            require_once $headerPath;
        } else {
            die("Header file not found: $headerPath");
        }

        if (file_exists($viewPath)) {
            $renderView = function ($members) use ($viewPath) {
                require $viewPath;
            };
            $renderView($members);
        } else {
            die("View file not found: $viewPath");
        }
    }

    public function customerDetail() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /index.php?controller=auth&action=login');
            exit;
        }

        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            header('Location: /index.php?controller=adminCustomer&action=customers');
            exit;
        }

        $memberId = intval($_GET['id']);
        $member = $this->memberModel->getMemberById($memberId);

        if (!$member) {
            header('Location: /index.php?controller=adminCustomer&action=customers');
            exit;
        }

        $headerPath = dirname(__DIR__) . '/views/admin/components/header.php';
        $viewPath = dirname(__DIR__) . '/views/admin/pages/customers-detail.php';

        if (file_exists($headerPath)) {
            require_once $headerPath;
        } else {
            die("Header file not found: $headerPath");
        }

        if (file_exists($viewPath)) {
            $renderView = function ($member) use ($viewPath) {
                require $viewPath;
            };
            $renderView($member);
        } else {
            die("View file not found: $viewPath");
        }
    }

    public function resetPassword() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /index.php?controller=auth&action=login');
            exit;
        }

        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            header('Location: /index.php?controller=adminCustomer&action=customers');
            exit;
        }

        $memberId = intval($_GET['id']);
        if ($this->memberModel->resetPassword($memberId)) {
            header('Location: /index.php?controller=adminCustomer&action=customers');
        } else {
            header('Location: /index.php?controller=adminCustomer&action=customers');
        }
        exit;
    }
}