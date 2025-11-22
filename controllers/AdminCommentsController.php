<?php

class AdminCommentsController
{
    private CommentModel $commentModel;

    public function __construct()
    {
        $this->commentModel = new CommentModel();
    }

    private function assertAdminJson(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (empty($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
            http_response_code(401);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['ok' => false, 'error' => 'Unauthorized'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        header('Content-Type: application/json; charset=utf-8');
    }


    public function list()
    {
        $this->assertAdminJson();

        $newsId = (int)($_GET['news_id'] ?? 0);
        if ($newsId <= 0) {
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => 'Bad news_id'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $page  = max(1, (int)($_GET['page']  ?? 1));
        $limit = max(1, min(100, (int)($_GET['limit'] ?? 20)));
        $offset = ($page - 1) * $limit;

        $sort   = $_GET['sort']   ?? 'newest';
        $q      = trim($_GET['q'] ?? '');

        try {
            $items = $this->commentModel->listForNewsPage($newsId, $q, $sort, $limit, $offset);
            $total = $this->commentModel->countForNewsPage($newsId, $q);
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode(['ok' => false, 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
            return;
        }

        $totalPages = $total > 0 ? (int)ceil($total / $limit) : 1;

        echo json_encode([
            'ok'          => true,
            'items'       => $items,
            'page'        => $page,
            'total'       => $total,
            'total_pages' => $totalPages,
        ], JSON_UNESCAPED_UNICODE);
    }

    public function delete()
    {
        $this->assertAdminJson();

        $id = (int)($_POST['id'] ?? 0);
        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => 'Invalid ID'], JSON_UNESCAPED_UNICODE);
            return;
        }

        try {
            $this->commentModel->deleteComment($id);
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode(['ok' => false, 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
            return;
        }

        echo json_encode(['ok' => true], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Bulk delete – version mới không hide nữa, chỉ xoá.
     * JS sẽ gọi: POST /index.php?controller=adminComments&action=bulkUpdate với ids[]
     */
    public function bulkUpdate()
    {
        $this->assertAdminJson();

        $ids = $_POST['ids'] ?? [];
        if (!is_array($ids) || empty($ids)) {
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => 'No IDs'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $ids = array_map('intval', $ids);
        $ids = array_filter($ids, fn($x) => $x > 0);

        if (empty($ids)) {
            http_response_code(400);
            echo json_encode(['ok' => false, 'error' => 'No valid IDs'], JSON_UNESCAPED_UNICODE);
            return;
        }

        try {
            $this->commentModel->bulkDelete($ids);
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode(['ok' => false, 'error' => $e->getMessage()], JSON_UNESCAPED_UNICODE);
            return;
        }

        echo json_encode(['ok' => true], JSON_UNESCAPED_UNICODE);
    }
}
