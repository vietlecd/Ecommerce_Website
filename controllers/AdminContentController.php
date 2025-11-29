<?php
require_once 'models/ContentModel.php';

class AdminContentController {
    private $contentModel;

    public function __construct() {
        $this->contentModel = new ContentModel();
    }

    public function edit() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        $key = isset($_GET['key']) ? trim($_GET['key']) : '';
        
        if (empty($key)) {
            header('Location: index.php?controller=adminDashboard&action=dashboard');
            exit;
        }

        $allowedKeys = ['about', 'qna'];
        if (!in_array($key, $allowedKeys)) {
            header('Location: index.php?controller=adminDashboard&action=dashboard');
            exit;
        }

        $content = $this->contentModel->getContentByKey($key);
        $htmlContent = $content ? $content['html_content'] : '';

        require_once 'views/admin/components/header.php';
        require_once 'views/admin/content/edit.php';
        require_once 'views/admin/components/admin_footer.php';
    }

    public function update() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=adminDashboard&action=dashboard');
            exit;
        }

        $key = isset($_POST['key']) ? trim($_POST['key']) : '';
        $content = isset($_POST['content']) ? $_POST['content'] : '';

        if (empty($key)) {
            $_SESSION['error'] = 'Page key is required.';
            header('Location: index.php?controller=adminContent&action=edit&key=' . urlencode($key));
            exit;
        }

        $allowedKeys = ['about', 'qna'];
        if (!in_array($key, $allowedKeys)) {
            $_SESSION['error'] = 'Invalid page key.';
            header('Location: index.php?controller=adminDashboard&action=dashboard');
            exit;
        }

        if ($this->contentModel->saveContent($key, $content)) {
            $_SESSION['success'] = 'Content updated successfully!';
        } else {
            $_SESSION['error'] = 'Failed to update content. Please try again.';
        }

        header('Location: index.php?controller=adminContent&action=edit&key=' . urlencode($key));
        exit;
    }
}

