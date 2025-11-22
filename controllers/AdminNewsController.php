<?php
require_once 'lib/minio.php';
require_once 'lib/draft-media.php';
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


        $errors = [];
        if ($title === '')        $errors[] = 'Tiêu đề không được để trống.';
        if ($description === '')  $errors[] = 'Mô tả ngắn không được để trống.';
        if ($content === '')      $errors[] = 'Nội dung không được để trống.';
        if ($news_type === '')    $errors[] = 'Loại tin không hợp lệ.';
        if (!$admin_id)           $errors[] = 'Phiên đăng nhập không hợp lệ.';


        $thumbnailUrl = null;
        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            try {
                $tmp  = $_FILES['thumbnail']['tmp_name'];
                $size = (int)($_FILES['thumbnail']['size'] ?? 0);

                $fi   = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($fi, $tmp);
                finfo_close($fi);

                $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
                if (!in_array($mime, $allowed, true)) {
                    $errors[] = 'Thumbnail phải là JPEG/PNG/WebP/GIF.';
                }
                if ($size > 10 * 1024 * 1024) {
                    $errors[] = 'Thumbnail vượt quá 10MB.';
                }

                if (empty($errors)) {
                    $ext = match ($mime) {
                        'image/jpeg' => '.jpg',
                        'image/png'  => '.png',
                        'image/webp' => '.webp',
                        'image/gif'  => '.gif',
                        default      => '.bin'
                    };
                    $key = 'news/thumb/' . date('Y/m/') . bin2hex(random_bytes(16)) . $ext;


                    s3_put_object($tmp, $mime, $key, true);
                    $thumbnailUrl = s3_public_url($key);
                }
            } catch (\Throwable $e) {
                $errors[] = 'Upload thumbnail thất bại: ' . $e->getMessage();
            }
        }

        if (!empty($errors)) {
            $error = implode("\n", $errors);
            require_once 'views/admin/components/header.php';
            require_once 'views/admin/pages/add-news.php';
            require_once 'views/admin/components/admin_footer.php';
            return;
        }


        if ($draft_token !== '') {
            $mappings = DraftMedia::adoptDraft($draft_token, function (string $bucket, string $srcKey) {

                $ext = pathinfo($srcKey, PATHINFO_EXTENSION);
                $dstKey = rtrim(s3_final_prefix(), '/') . '/article/' . date('Y/m/') . bin2hex(random_bytes(16)) . ($ext ? ('.' . $ext) : '');

                s3_copy_object($srcKey, $dstKey, true /* public-read final */);

                s3_delete_object($srcKey);

                $dstUrl = s3_public_url($dstKey);
                return ['dstKey' => $dstKey, 'dstUrl' => $dstUrl];
            });

            if (!empty($mappings)) {
                $content = $this->replaceTempImages($content, $mappings);
            }
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
            $error = 'Không thể thêm bài viết. Vui lòng thử lại.';
            require_once 'views/admin/components/header.php';
            require_once 'views/admin/pages/add-news.php';
            require_once 'views/admin/components/admin_footer.php';
            return;
        }

        if (!empty($promotionIds)) {
            $this->promotionModel->syncNewsPromotions($newsId, $promotionIds);
        }


        header('Location: index.php?controller=adminNews&action=manage');
        exit;
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

        $selectedPromotionIds = $promotionIds;

        $errors = [];
        if ($title === '')       $errors[] = 'Tiêu đề không được để trống.';
        if ($description === '') $errors[] = 'Mô tả ngắn không được để trống.';
        if ($content === '')     $errors[] = 'Nội dung không được để trống.';
        if ($news_type === '')   $errors[] = 'Loại tin không hợp lệ.';

        $thumbnailUrl = $edit_news['Thumbnail'] ?? null;

        if (isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] === UPLOAD_ERR_OK) {
            try {
                $tmp  = $_FILES['thumbnail']['tmp_name'];
                $size = (int)($_FILES['thumbnail']['size'] ?? 0);

                $fi   = finfo_open(FILEINFO_MIME_TYPE);
                $mime = finfo_file($fi, $tmp);
                finfo_close($fi);

                $allowed = ['image/jpeg', 'image/png', 'image/webp', 'image/gif'];
                if (!in_array($mime, $allowed, true)) {
                    $errors[] = 'Thumbnail phải là JPEG/PNG/WebP/GIF.';
                }
                if ($size > 10 * 1024 * 1024) {
                    $errors[] = 'Thumbnail vượt quá 10MB.';
                }

                if (empty($errors)) {
                    $ext = match ($mime) {
                        'image/jpeg' => '.jpg',
                        'image/png'  => '.png',
                        'image/webp' => '.webp',
                        'image/gif'  => '.gif',
                        default      => '.bin'
                    };
                    $key = 'news/thumb/' . date('Y/m/') . bin2hex(random_bytes(16)) . $ext;
                    s3_put_object($tmp, $mime, $key, true);
                    $thumbnailUrl = s3_public_url($key);
                }
            } catch (\Throwable $e) {
                $errors[] = 'Upload thumbnail thất bại: ' . $e->getMessage();
            }
        }

        if (!empty($errors)) {
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(400);
                echo json_encode(['ok' => false, 'error' => implode("\n", $errors)], JSON_UNESCAPED_UNICODE);
                exit;
            }

            $error = implode("\n", $errors);
            require_once 'views/admin/components/header.php';
            require_once 'views/admin/pages/edit-news.php';
            require_once 'views/admin/components/admin_footer.php';
            return;
        }

        if ($draft_token !== '') {
            $mappings = DraftMedia::adoptDraft($draft_token, function (string $bucket, string $srcKey) {
                $ext = pathinfo($srcKey, PATHINFO_EXTENSION);
                $dstKey = rtrim(s3_final_prefix(), '/') . '/article/' . date('Y/m/') . bin2hex(random_bytes(16)) . ($ext ? ('.' . $ext) : '');
                s3_copy_object($srcKey, $dstKey, true);
                s3_delete_object($srcKey);
                return ['dstKey' => $dstKey, 'dstUrl' => s3_public_url($dstKey)];
            });

            if (!empty($mappings)) {
                $content = $this->replaceTempImages($content, $mappings);
            }
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
            $success = 'Cập nhật bài viết thành công!';

            $edit_news = $this->newsModel->getNewsById($news_id);

            $selectedPromotionIds = $promotionIds;
        } else {
            $error = 'Không thể cập nhật bài viết. Vui lòng thử lại.';
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

        if ($news && $news['Thumbnail'] && file_exists($news['Thumbnail'])) {
            unlink($news['Thumbnail']);
        }

        if ($this->newsModel->deleteNews($news_id)) {
            $success = 'Xóa bài viết thành công!';
        } else {
            $error = 'Không thể xóa bài viết. Vui lòng thử lại.';
        }

        header('Location: index.php?controller=adminNews&action=manage');
        exit;
    }

    /**
     * Thay URL ảnh tạm trong $html sang URL final dựa theo mapping adoptDraft().
     * Ưu tiên replace theo data-s3-key (ổn định nhất).
     * Fallback: nếu không có data-s3-key, cố gắng thay bằng regex src chứa {bucket}/{key}.
     *
     * @param string $html
     * @param array<int,array{srcBucket?:string,srcKey:string,dstKey:string,dstUrl:string}> $mappings
     * @return string
     */
    private function replaceTempImages(string $html, array $mappings): string
    {
        foreach ($mappings as $m) {
            $srcKey = $m['srcKey'];
            $dstUrl = $m['dstUrl'];

            $pattern = '~(<img[^>]*\bdata-s3-key\s*=\s*")' . preg_quote($srcKey, '~') . '(")([^>]*\bsrc\s*=\s*")[^"]*(")~i';
            $repl    = '$1' . addcslashes($srcKey, '\\$') . '$2$3' . addcslashes($dstUrl, '\\$') . '$4';
            $newHtml = preg_replace($pattern, $repl, $html, -1, $count1);
            if ($newHtml !== null) {
                $html = $newHtml;
                if ($count1 > 0) continue;
            }

            $bucket = s3_bucket();
            $keyEsc = preg_quote(str_replace('%2F', '/', rawurlencode($srcKey)), '~');
            $bucketEsc = preg_quote(rawurlencode($bucket), '~');

            $pattern2 = '~(<img[^>]*\bsrc\s*=\s*")[^"]*?/' . $bucketEsc . '/' . $keyEsc . '[^"]*(")~i';
            $repl2    = '$1' . addcslashes($dstUrl, '\\$') . '$2';
            $newHtml2 = preg_replace($pattern2, $repl2, $html);
            if ($newHtml2 !== null) {
                $html = $newHtml2;
            }
        }
        return $html;
    }

    public function show()
    { // /index.php?controller=adminNews&action=show&id=123
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

        $comments = $this->newsModel->getCommentsByNewsId($id);
        $totalComments = $this->newsModel->getCommentCountByNewsId($id);
        $hasMore = count($comments) < $totalComments;

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
