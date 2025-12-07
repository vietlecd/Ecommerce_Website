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
            header('Location: /index.php?controller=auth&action=login');
            exit;
        }
    }

    public function manage()
    {
        $this->assertAdminJson();

        $flash = $_SESSION['cm_flash'] ?? null;
        unset($_SESSION['cm_flash']);

        $page   = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $limit  = isset($_GET['limit']) ? max(1, (int)$_GET['limit']) : 20;
        $offset = ($page - 1) * $limit;

        $search   = trim($_GET['search'] ?? '');
        $rating   = $_GET['rating'] ?? '';
        $shoesId  = $_GET['shoes_id'] ?? '';
        $sort     = $_GET['sort'] ?? 'newest';

        $filters = [
            'search'  => $search ?: null,
            'rating'  => $rating !== '' ? (int)$rating : null,
            'shoesId' => $shoesId !== '' ? (int)$shoesId : null,
            'sort'    => $sort,
        ];

        $totalComments = $this->commentModel->countComments($filters);
        $comments      = $this->commentModel->getComments($filters, $limit, $offset);

        $totalPages = (int)ceil($totalComments / $limit);

        $shoesList = $this->commentModel->getShoesForFilter();

        require_once 'views/admin/components/header.php';
        require_once 'views/admin/pages/manage-comments.php';
        require_once 'views/admin/components/admin_footer.php';
    }

    public function delete()
    {
        $this->assertAdminJson();

        $id = (int)($_GET['id'] ?? 0);

        if ($id <= 0) {
            $_SESSION['cm_flash'] = [
                'type'    => 'danger',
                'message' => 'Invalid comment ID.'
            ];
            header("Location: /index.php?controller=adminComments&action=manage");
            exit;
        }

        try {
            $this->commentModel->deleteComment($id);

            $_SESSION['cm_flash'] = [
                'type'    => 'success',
                'message' => 'Comment has been deleted.'
            ];
        } catch (\Throwable $e) {
            $_SESSION['cm_flash'] = [
                'type'    => 'danger',
                'message' => 'Delete failed: ' . $e->getMessage()
            ];
        }

        header("Location: /index.php?controller=adminComments&action=manage");
        exit;
    }

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
