<?php
require_once 'models/NewsModel.php';
require_once 'models/PromotionModel.php';

class NewsController {
    private $newsModel;
    private $promotionModel;

    public function __construct() {
        $this->newsModel = new NewsModel();
        $this->promotionModel = new PromotionModel();
    }

    public function index() {
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $limit = 8;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page);
        $offset = ($page - 1) * $limit;

        $news = $this->newsModel->getAllNews($search, $limit, $offset);
        $totalNews = $this->newsModel->getPublicNewsCount($search);
        $totalPages = ceil($totalNews / $limit);

        require_once 'views/components/header.php';
        require_once 'views/pages/news.php';
    }

    public function detail() {
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

        require_once 'views/components/header.php';
        require_once 'views/pages/news_detail.php';
    }

    public function trackClick() {
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
        $redirectUrl = $news['promotion_id']
            ? "/index.php?controller=promotionalProducts&action=index&promotion_id={$news['promotion_id']}"
            : "/index.php?controller=news&action=detail&id={$news['NewsID']}";
        header("Location: $redirectUrl");
        exit;
    }
}