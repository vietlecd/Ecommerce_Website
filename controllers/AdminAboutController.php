<?php
require_once 'models/AboutModel.php';

class AdminAboutController {
    private $aboutModel;

    public function __construct() {
        $this->aboutModel = new AboutModel();
    }

    public function edit() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        $aboutContent = $this->aboutModel->getAboutContent();
        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = isset($_POST['title']) ? trim($_POST['title']) : '';
            $content = isset($_POST['content']) ? trim($_POST['content']) : '';
            $adminId = $_SESSION['user_id'];
            
            $image = $aboutContent['Image']; // Keep existing image by default

            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'assets/images/about/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }

                $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $fileName = 'about_' . time() . '.' . $fileExtension;
                $uploadPath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                    // Delete old image if exists
                    if ($aboutContent['Image'] && file_exists($aboutContent['Image'])) {
                        unlink($aboutContent['Image']);
                    }
                    $image = $uploadPath;
                }
            }

            if (empty($title) || empty($content)) {
                $error = 'Title and content are required.';
            } else {
                if ($this->aboutModel->updateAboutContent($title, $content, $image, $adminId)) {
                    $success = 'About page content updated successfully!';
                    $aboutContent = $this->aboutModel->getAboutContent();
                } else {
                    $error = 'Failed to update content. Please try again.';
                }
            }
        }

        require_once 'views/admin/components/header.php';
        require_once 'views/admin/pages/edit-about.php';
        require_once 'views/admin/components/admin_footer.php';
    }
}
