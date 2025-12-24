<?php
require_once 'models/NewsModel.php';
require_once 'models/PromotionModel.php';

class AdminNewsController
{
    private $newsModel;
    private $promotionModel;

    public function __construct()
    {
        $this->newsModel = new NewsModel();
        $this->promotionModel = new PromotionModel();
    }

    private function assertAdmin(): void
    {
        if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
            http_response_code(401);
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
    }

    public function manage()
    {
        $this->assertAdmin();

        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $type = isset($_GET['type'])
            ? trim($_GET['type'])
            : (isset($_GET['status']) ? trim($_GET['status']) : 'all');
        $limit = isset($_GET['limit']) ? max(1, (int)$_GET['limit']) : 20;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
        $allowedSorts = [
            'newest',
            'oldest',
            'views_desc',
            'views_asc',
            'author_asc',
            'author_desc',
            'title_asc',
            'title_desc',
            'id_asc',
            'id_desc'
        ];

        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'newest';
        }

        $newsTypes = $this->newsModel->getNewsTypes();
        if ($type !== 'all' && $type !== '' && !in_array($type, $newsTypes, true)) {
            $type = 'all';
        }

        $news = $this->newsModel->getNewsWithAdmin($search, $limit, $offset, $type, $sort);
        $totalNews = $this->newsModel->getNewsCount($search, $type);
        $totalPages = ceil($totalNews / $limit);

        require_once 'views/admin/components/header.php';
        require_once 'views/admin/pages/manage-news.php';
        require_once 'views/admin/components/admin_footer.php';
    }

    public function stats()
    {
        $this->assertAdmin();

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

    public function addNews()
    {
        $this->assertAdmin();

        $promotions = $this->promotionModel->getAllPromotions();

        $fieldErrors  = [];
        $old          = [];
        $toastError   = null;
        $toastSuccess = null;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require_once 'views/admin/components/header.php';
            require_once 'views/admin/pages/add-news.php';
            require_once 'views/admin/components/admin_footer.php';
            return;
        }

        $title       = trim($_POST['title']        ?? '');
        $description = trim($_POST['description']  ?? '');
        $content     = trim($_POST['content_html'] ?? '');
        $news_type   = trim($_POST['news_type']    ?? 'general');
        $draft_token = trim($_POST['draft_token']  ?? '');

        $promotionIdsRaw = $_POST['promotion_ids'] ?? [];
        if (!is_array($promotionIdsRaw)) {
            $promotionIdsRaw = [$promotionIdsRaw];
        }

        $promotionIds = array_values(
            array_filter(
                array_map('intval', $promotionIdsRaw),
                fn($x) => $x > 0
            )
        );

        $admin_id = $_SESSION['user_id'] ?? null;

        if ($title === '') {
            $fieldErrors['title'][] = 'Tiêu đề không được để trống.';
        }
        if ($description === '') {
            $fieldErrors['description'][] = 'Mô tả ngắn không được để trống.';
        }
        if ($content === '') {
            $fieldErrors['content_html'][] = 'Nội dung không được để trống.';
        }
        if ($news_type === '') {
            $fieldErrors['news_type'][] = 'Loại tin không hợp lệ.';
        }
        if (!$admin_id) {
            $fieldErrors['general'][] = 'Phiên đăng nhập không hợp lệ.';
        }

        $thumbnailUrl = null;

        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            try {
                $tmp  = $_FILES['thumbnail']['tmp_name'];
                $size = (int)($_FILES['thumbnail']['size'] ?? 0);

                $fi   = new finfo(FILEINFO_MIME_TYPE);
                $mime = $fi->file($tmp);

                $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
                if (!in_array($mime, $allowed, true)) {
                    $fieldErrors['thumbnail'][] = 'Thumbnail phải là JPEG/PNG/WebP/GIF.';
                }
                if ($size > 10 * 1024 * 1024) {
                    $fieldErrors['thumbnail'][] = 'Thumbnail vượt quá 10MB.';
                }

                if (empty($fieldErrors['thumbnail'])) {
                    $rootDir = dirname(__DIR__);
                    $baseDir = $rootDir . '/images/news/thumb';
                    $baseUrl = '/images/news/thumb';

                    $targetDir = $baseDir . '/' . date('Y/m');
                    if (!is_dir($targetDir) && !mkdir($targetDir, 0775, true) && !is_dir($targetDir)) {
                        throw new RuntimeException('Không thể tạo thư mục thumbnail');
                    }

                    $ext = match ($mime) {
                        'image/jpeg' => '.jpg',
                        'image/png'  => '.png',
                        'image/webp' => '.webp',
                        'image/gif'  => '.gif',
                        default      => '.bin'
                    };

                    $filename   = bin2hex(random_bytes(16)) . $ext;
                    $targetPath = $targetDir . '/' . $filename;

                    if (!move_uploaded_file($tmp, $targetPath)) {
                        throw new RuntimeException('Không thể lưu file thumbnail');
                    }

                    $thumbnailUrl = $baseUrl . '/' . date('Y/m') . '/' . $filename;
                }
            } catch (\Throwable $e) {
                $fieldErrors['thumbnail'][] = 'Upload thumbnail thất bại: ' . $e->getMessage();
            }
        }

        $old = [
            'title'         => $title,
            'description'   => $description,
            'content_html'  => $content,
            'news_type'     => $news_type,
            'promotion_ids' => $promotionIds,
        ];

        $hasValidationError = false;
        foreach ($fieldErrors as $arr) {
            if (!empty($arr)) {
                $hasValidationError = true;
                break;
            }
        }

        if ($hasValidationError) {
            require_once 'views/admin/components/header.php';
            require_once 'views/admin/pages/add-news.php';
            require_once 'views/admin/components/admin_footer.php';
            return;
        }

        $newsId = $this->newsModel->addNews(
            $title,
            $description,
            $content,
            $admin_id,
            $news_type,
            $thumbnailUrl
        );

        if (!$newsId) {
            $toastError = 'Không thể thêm bài viết. Vui lòng thử lại.';

            require_once 'views/admin/components/header.php';
            require_once 'views/admin/pages/add-news.php';
            require_once 'views/admin/components/admin_footer.php';
            return;
        }

        if (!empty($promotionIds)) {
            $this->promotionModel->syncNewsPromotions($newsId, $promotionIds);
        }

        $toastSuccess = 'Thêm bài viết thành công!';
        $old            = [];
        $fieldErrors    = [];
        $selectedPromotionIds = [];

        require_once 'views/admin/components/header.php';
        require_once 'views/admin/pages/add-news.php';
        require_once 'views/admin/components/admin_footer.php';
        return;
    }


    public function editNews()
    {
        $isAjax = $this->isAjax();

        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(401);
                echo json_encode(['ok' => false, 'error' => 'Unauthorized'], JSON_UNESCAPED_UNICODE);
                exit;
            }
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(400);
                echo json_encode(['ok' => false, 'error' => 'Thiếu hoặc sai id'], JSON_UNESCAPED_UNICODE);
                exit;
            }
            header('Location: index.php?controller=adminNews&action=manage');
            exit;
        }

        $news_id   = (int)$_GET['id'];
        $edit_news = $this->newsModel->getNewsById($news_id);
        if (!$edit_news) {
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(404);
                echo json_encode(['ok' => false, 'error' => 'Bài viết không tồn tại'], JSON_UNESCAPED_UNICODE);
                exit;
            }
            header('Location: index.php?controller=adminNews&action=manage');
            exit;
        }

        $promotions = $this->promotionModel->getAllPromotions();

        $selectedPromotionIds = [];
        try {
            $existingPromos = $this->promotionModel->getPromotionsByNewsId($news_id);
            foreach ($existingPromos as $p) {
                if (isset($p['PromotionID'])) {
                    $selectedPromotionIds[] = (int)$p['PromotionID'];
                }
            }
            $selectedPromotionIds = array_values(array_unique($selectedPromotionIds));
        } catch (\Throwable $e) {
            error_log('getPromotionsByNewsId failed for news ' . $news_id . ': ' . $e->getMessage());
        }

        $old          = [];
        $fieldErrors  = [];
        $toastError   = null;
        $toastSuccess = null;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            require_once 'views/admin/components/header.php';
            require_once 'views/admin/pages/edit-news.php';
            require_once 'views/admin/components/admin_footer.php';
            return;
        }

        ini_set('display_errors', '0');
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        $title       = trim($_POST['title']        ?? '');
        $description = trim($_POST['description']  ?? '');
        $content     = trim($_POST['content_html'] ?? '');
        $news_type   = trim($_POST['news_type']    ?? 'general');

        $promotionIdsRaw = $_POST['promotion_ids'] ?? [];
        if (!is_array($promotionIdsRaw)) {
            $promotionIdsRaw = [$promotionIdsRaw];
        }

        $promotionIds = array_values(
            array_filter(
                array_map('intval', $promotionIdsRaw),
                fn($x) => $x > 0
            )
        );

        $old = [
            'title'         => $title,
            'description'   => $description,
            'content_html'  => $content,
            'news_type'     => $news_type,
            'promotion_ids' => $promotionIds,
        ];
        $selectedPromotionIds = $promotionIds;

        $fieldErrors = [];

        if ($title === '') {
            $fieldErrors['title'][] = 'Tiêu đề không được để trống.';
        }
        if ($description === '') {
            $fieldErrors['description'][] = 'Mô tả ngắn không được để trống.';
        }
        if ($content === '') {
            $fieldErrors['content_html'][] = 'Nội dung không được để trống.';
        }
        if ($news_type === '') {
            $fieldErrors['news_type'][] = 'Loại tin không hợp lệ.';
        }

        $thumbnailUrl = $edit_news['Thumbnail'] ?? null;

        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
                try {
                    $tmp  = $_FILES['thumbnail']['tmp_name'];
                    $size = (int)($_FILES['thumbnail']['size'] ?? 0);

                    $fi   = new finfo(FILEINFO_MIME_TYPE);
                    $mime = $fi->file($tmp);

                    $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
                    if (!in_array($mime, $allowed, true)) {
                        $fieldErrors['thumbnail'][] = 'Thumbnail phải là JPEG/PNG/WebP/GIF.';
                    }
                    if ($size > 10 * 1024 * 1024) {
                        $fieldErrors['thumbnail'][] = 'Thumbnail vượt quá 10MB.';
                    }

                    if (empty($fieldErrors['thumbnail'])) {
                        $rootDir = dirname(__DIR__);
                        $baseDir = $rootDir . '/assets/images/news/thumb';
                        $baseUrl = '/assets/images/news/thumb';

                        $targetDir = $baseDir . '/' . date('Y/m');
                        if (!is_dir($targetDir) && !mkdir($targetDir, 0775, true) && !is_dir($targetDir)) {
                            throw new RuntimeException('Không thể tạo thư mục thumbnail');
                        }

                        $ext = match ($mime) {
                            'image/jpeg' => '.jpg',
                            'image/png'  => '.png',
                            'image/webp' => '.webp',
                            'image/gif'  => '.gif',
                            default      => '.bin'
                        };

                        $filename   = bin2hex(random_bytes(16)) . $ext;
                        $targetPath = $targetDir . '/' . $filename;

                        if (!move_uploaded_file($tmp, $targetPath)) {
                            throw new RuntimeException('Không thể lưu file thumbnail');
                        }

                        $thumbnailUrl = $baseUrl . '/' . date('Y/m') . '/' . $filename;
                    }
                } catch (\Throwable $e) {
                    $fieldErrors['thumbnail'][] = 'Upload thumbnail thất bại: ' . $e->getMessage();
                }
            } else {
                $fieldErrors['thumbnail'][] = 'Upload thumbnail thất bại (mã lỗi: ' . $_FILES['thumbnail']['error'] . ').';
            }
        }

        if (!empty($fieldErrors)) {
            $flat = [];
            foreach ($fieldErrors as $msgs) {
                foreach ($msgs as $m) {
                    $flat[] = $m;
                }
            }
            $toastError = implode("\n", $flat);

            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(400);
                echo json_encode([
                    'ok'          => false,
                    'error'       => $toastError,
                    'fieldErrors' => $fieldErrors,
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }

            require_once 'views/admin/components/header.php';
            require_once 'views/admin/pages/edit-news.php';
            require_once 'views/admin/components/admin_footer.php';
            return;
        }

        $ok = $this->newsModel->updateNews(
            $news_id,
            $title,
            $description,
            $content,
            $news_type,
            $thumbnailUrl
        );

        if ($ok) {
            $this->promotionModel->syncNewsPromotions($news_id, $promotionIds);
        }

        if ($isAjax) {
            header('Content-Type: application/json; charset=utf-8');
            if ($ok) {
                http_response_code(200);
                echo json_encode([
                    'ok'            => true,
                    'message'       => 'Cập nhật bài viết thành công!',
                    'thumbnail_url' => $thumbnailUrl
                ], JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(500);
                echo json_encode([
                    'ok'    => false,
                    'error' => 'Không thể cập nhật bài viết. Vui lòng thử lại.'
                ], JSON_UNESCAPED_UNICODE);
            }
            exit;
        }

        if ($ok) {
            $toastSuccess = 'Cập nhật bài viết thành công!';
            $edit_news = $this->newsModel->getNewsById($news_id);
            $selectedPromotionIds = $promotionIds;
            $old = [];
            $fieldErrors = [];
        } else {
            $toastError = 'Không thể cập nhật bài viết. Vui lòng thử lại.';
        }

        require_once 'views/admin/components/header.php';
        require_once 'views/admin/pages/edit-news.php';
        require_once 'views/admin/components/admin_footer.php';
    }



    public function deleteNews()
    {
        $this->assertAdmin();

        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            header('Location: index.php?controller=adminNews&action=manage');
            exit;
        }

        $news_id = intval($_GET['id']);
        $news = $this->newsModel->getNewsById($news_id);

        if ($news && !empty($news['Thumbnail'])) {
            $thumbUrl = $news['Thumbnail'];

            if (str_starts_with($thumbUrl, '/assets/images/')) {
                $rootDir  = dirname(__DIR__);
                $filePath = $rootDir . $thumbUrl;

                if (is_file($filePath)) {
                    @unlink($filePath);
                }
            }
        }


        if ($this->newsModel->deleteNews($news_id)) {
            $success = 'Xóa bài viết thành công!';
        } else {
            $error = 'Không thể xóa bài viết. Vui lòng thử lại.';
        }

        header('Location: index.php?controller=adminNews&action=manage');
        exit;
    }

    public function show()
    {
        $this->assertAdmin();

        $id = (int)($_GET['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(404);
            echo 'Not found';
            return;
        }

        $news = $this->newsModel->getNewsById($id);
        if (!$news) {
            http_response_code(404);
            echo 'Not found';
            return;
        }

        $promotions = $this->promotionModel->getPromotionsByNewsId($id, true);

        require_once 'views/admin/components/header.php';
        require_once 'views/admin/pages/view-news.php';
        require_once 'views/admin/components/admin_footer.php';
    }

    private function isAjax(): bool
    {
        if (strtolower($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'xmlhttprequest') return true;
        if (($_POST['ajax'] ?? '') === '1') return true;
        $accept = strtolower($_SERVER['HTTP_ACCEPT'] ?? '');
        if (strpos($accept, 'application/json') !== false) return true;
        return false;
    }
}
