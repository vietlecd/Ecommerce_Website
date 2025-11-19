<?php
require_once 'models/NewsModel.php';
require_once 'models/PromotionModel.php';

class AdminNewsController {
    private $newsModel;
    private $promotionModel;

    public function __construct() {
        $this->newsModel = new NewsModel();
        $this->promotionModel = new PromotionModel();
    }

    public function manage() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $status = isset($_GET['status']) ? trim($_GET['status']) : 'all';
        $limit = 5;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        $news = $this->newsModel->getNewsWithAdmin($search, $limit, $offset, $status);
        $totalNews = $this->newsModel->getNewsCount($search, $status);
        $totalPages = ceil($totalNews / $limit);

        require_once 'views/admin/components/header.php';
        require_once 'views/admin/pages/manage-news.php';
        require_once 'views/admin/components/admin_footer.php';
    }

    public function stats() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $limit = 5;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        $clickStats = $this->newsModel->getClickStats($search, $limit, $offset);
        $totalStats = $this->newsModel->getClickStatsCount($search);
        $totalPages = ceil($totalStats / $limit);

        require_once 'views/admin/components/header.php';
        require_once 'views/admin/pages/news-stats.php';
        require_once 'views/admin/components/admin_footer.php';
    }

    public function addNews() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        $promotions = $this->promotionModel->getAllPromotions();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title']);
            $description = trim($_POST['description']);
            $content = trim($_POST['content']);
            $admin_id = $_SESSION['user_id'];
            $news_type = trim($_POST['news_type']);
            $promotion_id = !empty($_POST['promotion_id']) ? (int)$_POST['promotion_id'] : null;
            $thumbnail = null;

            // Xử lý upload ảnh
            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                $maxFileSize = 5 * 1024 * 1024;

                $fileType = $_FILES['thumbnail']['type'];
                $fileSize = $_FILES['thumbnail']['size'];
                $fileTmp = $_FILES['thumbnail']['tmp_name'];

                if (!in_array($fileType, $allowedTypes)) {
                    $error = 'Chỉ cho phép upload file ảnh (JPEG, PNG, GIF).';
                } elseif ($fileSize > $maxFileSize) {
                    $error = 'Kích thước file không được vượt quá 5MB.';
                } else {
                    $fileExt = pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION);
                    $fileName = 'news_' . time() . '.' . $fileExt;
                    $uploadPath = 'assets/images/news/' . $fileName;

                    if (move_uploaded_file($fileTmp, $uploadPath)) {
                        $thumbnail = $uploadPath;
                    } else {
                        $error = 'Không thể upload ảnh. Vui lòng thử lại.';
                    }
                }
            }

            if (empty($title) || empty($description) || empty($content) || empty($news_type)) {
                $error = 'Vui lòng điền đầy đủ các trường bắt buộc.';
            } else {
                if ($this->newsModel->addNews($title, $description, $content, $admin_id, $news_type, $promotion_id, $thumbnail)) {
                    $success = 'Thêm bài viết thành công!';
                } else {
                    $error = 'Không thể thêm bài viết. Vui lòng thử lại.';
                }
            }
        }

        require_once 'views/admin/components/header.php';
        require_once 'views/admin/pages/add-news.php';
        require_once 'views/admin/components/admin_footer.php';
    }

    public function editNews() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            header('Location: index.php?controller=adminNews&action=manage');
            exit;
        }

        $news_id = intval($_GET['id']);
        $edit_news = $this->newsModel->getNewsById($news_id);

        if (!$edit_news) {
            header('Location: index.php?controller=adminNews&action=manage');
            exit;
        }

        $promotions = $this->promotionModel->getAllPromotions();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $title = trim($_POST['title']);
            $description = trim($_POST['description']);
            $content = trim($_POST['content']);
            $news_type = trim($_POST['news_type']);
            $promotion_id = !empty($_POST['promotion_id']) ? (int)$_POST['promotion_id'] : null;
            $thumbnail = $edit_news['thumbnail'];

            if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                $maxFileSize = 5 * 1024 * 1024;

                $fileType = $_FILES['thumbnail']['type'];
                $fileSize = $_FILES['thumbnail']['size'];
                $fileTmp = $_FILES['thumbnail']['tmp_name'];

                if (!in_array($fileType, $allowedTypes)) {
                    $error = 'Chỉ cho phép upload file ảnh (JPEG, PNG, GIF).';
                } elseif ($fileSize > $maxFileSize) {
                    $error = 'Kích thước file không được vượt quá 5MB.';
                } else {
                    $fileExt = pathinfo($_FILES['thumbnail']['name'], PATHINFO_EXTENSION);
                    $fileName = 'news_' . $news_id . '_' . time() . '.' . $fileExt;
                    $uploadPath = 'assets/images/news/' . $fileName;

                    if ($thumbnail && file_exists($thumbnail)) {
                        unlink($thumbnail);
                    }

                    if (move_uploaded_file($fileTmp, $uploadPath)) {
                        $thumbnail = $uploadPath;
                    } else {
                        $error = 'Không thể upload ảnh. Vui lòng thử lại.';
                    }
                }
            }

            if (empty($title) || empty($description) || empty($content) || empty($news_type)) {
                $error = 'Vui lòng điền đầy đủ các trường bắt buộc.';
            } else {
                if ($this->newsModel->updateNews($news_id, $title, $description, $content, $news_type, $promotion_id, $thumbnail)) {
                    $success = 'Cập nhật bài viết thành công!';
                    $edit_news = $this->newsModel->getNewsById($news_id);
                } else {
                    $error = 'Không thể cập nhật bài viết. Vui lòng thử lại.';
                }
            }
        }

        require_once 'views/admin/components/header.php';
        require_once 'views/admin/pages/edit-news.php';
        require_once 'views/admin/components/admin_footer.php';
    }

    public function deleteNews() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            header('Location: index.php?controller=adminNews&action=manage');
            exit;
        }

        $news_id = intval($_GET['id']);
        $news = $this->newsModel->getNewsById($news_id);

        if ($news && $news['thumbnail'] && file_exists($news['thumbnail'])) {
            unlink($news['thumbnail']);
        }

        if ($this->newsModel->deleteNews($news_id)) {
            $success = 'Xóa bài viết thành công!';
        } else {
            $error = 'Không thể xóa bài viết. Vui lòng thử lại.';
        }

        header('Location: index.php?controller=adminNews&action=manage');
        exit;
    }
}