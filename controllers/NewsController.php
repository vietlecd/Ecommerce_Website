<?php
require_once 'models/NewsModel.php';
require_once 'models/PromotionModel.php';

class NewsController
{
    private $newsModel;
    private $promotionModel;

    public function __construct()
    {
        $this->newsModel = new NewsModel();
        $this->promotionModel = new PromotionModel();
    }

    public function index()
    {
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $limit = 8;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page);
        $offset = ($page - 1) * $limit;

        $news = $this->newsModel->getAllNews($search, $limit, $offset);
        $totalNews = $this->newsModel->getPublicNewsCount($search);
        $totalPages = ceil($totalNews / $limit);
        $recentNews = $this->newsModel->getRecentNews(4);
        $popularNews = $this->newsModel->getPopularNews(4);

        require_once 'views/components/header.php';
        require_once 'views/pages/news.php';
    }

    public function detail()
    {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            header('Location: index.php?controller=news&action=index');
            exit;
        }

        $news_id = intval($_GET['id']);
        $news = $this->newsModel->getNewsById($news_id);

        if (!$news) {
            header('Location: index.php?controller=news&action=index');
            exit;
        }

        // Ghi lại lượt nhấp
        // $this->newsModel->incrementClickCount($news_id);

        $commentsPerPage = 5;
        $comments        = $this->newsModel->getCommentsByNewsId($news_id, $commentsPerPage);
        $totalComments   = $this->newsModel->getCommentCountByNewsId($news_id);
        $hasMore         = $totalComments > count($comments);

        require_once 'views/components/header.php';
        require_once 'views/pages/news_detail.php';
    }

    public function trackClick()
    {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            header('Location: index.php?controller=news&action=index');
            exit;
        }

        $news_id = intval($_GET['id']);
        $news = $this->newsModel->getNewsById($news_id);

        if (!$news) {
            header('Location: index.php?controller=news&action=index');
            exit;
        }

        // Ghi lại lượt nhấp
        $this->newsModel->incrementClickCount($news_id);

        // Chuyển hướng
        // $redirectUrl = $news['promotion_id']
        //     ? "/index.php?controller=promotionalProducts&action=index&promotion_id={$news['promotion_id']}"
        //     : "/index.php?controller=news&action=detail&id={$news['NewsID']}";
        $redirectUrl = "/index.php?controller=news&action=detail&id={$news['NewsID']}";
        header("Location: $redirectUrl");
        exit;
    }

    public function list()
    {
        $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
        $newsList = $this->newsModel->getNewsList($keyword);
        include 'views/pages/news-list.php';
    }

    public function addComment()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /index.php?controller=news&action=index');
            exit;
        }

        $newsId  = (int)($_POST['news_id'] ?? 0);
        $comment = trim($_POST['comment_content'] ?? '');

        $isAjax = (
            (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')
            || (isset($_SERVER['HTTP_ACCEPT']) && stripos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)
        );


        if ($newsId <= 0 || $comment === '') {
            if ($isAjax) {
                header('Content-Type: application/json; charset=utf-8');
                http_response_code(422);
                echo json_encode(['ok' => false, 'message' => 'Thiếu dữ liệu hoặc nội dung trống.']);
                exit;
            }
            header('Location: /index.php?controller=news&action=detail&id=' . $newsId . '#comments');
            exit;
        }

        $insertId = $this->newsModel->addComment($newsId, $comment);

        if ($isAjax) {
            $c = $this->newsModel->getCommentById($insertId);
            $count = $this->newsModel->getCommentCountByNewsId($newsId);

            $partial = __DIR__ . '/../views/components/comment-item.php';
            ob_start();
            if (file_exists($partial)) {
                include $partial;
            } else {
                $who  = htmlspecialchars($c['Username'] ?? 'Guest');
                $text = nl2br(htmlspecialchars($c['Content'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
                echo '<div class="cmt-item"><div class="cmt-main"><div class="cmt-header"><span class="cmt-author">'
                    . $who . '</span></div><div class="cmt-text">' . $text . '</div></div></div>';
            }
            $html = ob_get_clean();

            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'ok'    => true,
                'html'  => $html,
                'count' => (int)$count,
            ]);
            exit;
        }

        header('Location: /index.php?controller=news&action=detail&id=' . $newsId . '#comments');
        exit;
    }

    public function loadComments()
    {
        $isAjax = (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest');

        $newsId = (int)($_GET['news_id'] ?? 0);
        $lastCreatedAt = $_GET['last-created-at'] ?? 0;
        $limit  = min(50, max(1, (int)($_GET['limit'] ?? 5)));

        if (!$isAjax || $newsId <= 0) {
            http_response_code(400);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['ok' => false, 'message' => 'Bad request']);
            return;
        }

        $rows  = $this->newsModel->getCommentsByNewsId($newsId, $limit + 1, $lastCreatedAt);
        $total = $this->newsModel->getCommentCountByNewsId($newsId);
        $hasMore = count($rows) > $limit;
        if ($hasMore) array_pop($rows);

        if (!function_exists('time_ago_vi')) {
            function time_ago_vi($dateStr)
            {
                $ts = is_numeric($dateStr) ? (int)$dateStr : strtotime($dateStr);
                if (!$ts) return htmlspecialchars($dateStr);
                $diff = time() - $ts;
                if ($diff < 60) return $diff . 's trước';
                if ($diff < 3600) return floor($diff / 60) . 'm trước';
                if ($diff < 86400) return floor($diff / 3600) . 'h trước';
                if ($diff < 30 * 86400) return floor($diff / 86400) . ' ngày trước';
                return date('d/m/Y', $ts);
            }
        }

        ob_start();
        foreach ($rows as $c) {
            $renderReplies = true;
            include __DIR__ . '/../views/components/comment-item.php';
        }
        $html = ob_get_clean();

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'ok' => true,
            'html' => $html,
            'hasMore' => $hasMore,
        ]);
    }
}
