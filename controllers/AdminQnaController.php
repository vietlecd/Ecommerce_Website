<?php
require_once 'models/QnaModel.php';

class AdminQnaController {
    private $qnaModel;

    public function __construct() {
        $this->qnaModel = new QnaModel();
    }

    public function manage() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $limit = 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $offset = ($page - 1) * $limit;

        $qnaList = $this->qnaModel->getAllQna($search, $limit, $offset);
        $totalQna = $this->qnaModel->getQnaCount($search);
        $totalPages = ceil($totalQna / $limit);

        require_once 'views/admin/components/header.php';
        require_once 'views/admin/pages/manage-qna.php';
        require_once 'views/admin/components/admin_footer.php';
    }

    public function add() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $question = isset($_POST['question']) ? trim($_POST['question']) : '';
            $answer = isset($_POST['answer']) ? trim($_POST['answer']) : '';
            $displayOrder = isset($_POST['display_order']) ? (int)$_POST['display_order'] : 0;
            $isActive = isset($_POST['is_active']) ? 1 : 0;
            $adminId = $_SESSION['user_id'];

            if (empty($question) || empty($answer)) {
                $error = 'Question and answer are required.';
            } else {
                if ($this->qnaModel->addQna($question, $answer, $displayOrder, $isActive, $adminId)) {
                    $success = 'Q&A added successfully!';
                } else {
                    $error = 'Failed to add Q&A. Please try again.';
                }
            }
        }

        require_once 'views/admin/components/header.php';
        require_once 'views/admin/pages/add-qna.php';
        require_once 'views/admin/components/admin_footer.php';
    }

    public function edit() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            header('Location: index.php?controller=adminQna&action=manage');
            exit;
        }

        $qnaId = (int)$_GET['id'];
        $qna = $this->qnaModel->getQnaById($qnaId);

        if (!$qna) {
            header('Location: index.php?controller=adminQna&action=manage');
            exit;
        }

        $error = '';
        $success = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $question = isset($_POST['question']) ? trim($_POST['question']) : '';
            $answer = isset($_POST['answer']) ? trim($_POST['answer']) : '';
            $displayOrder = isset($_POST['display_order']) ? (int)$_POST['display_order'] : 0;
            $isActive = isset($_POST['is_active']) ? 1 : 0;

            if (empty($question) || empty($answer)) {
                $error = 'Question and answer are required.';
            } else {
                if ($this->qnaModel->updateQna($qnaId, $question, $answer, $displayOrder, $isActive)) {
                    $success = 'Q&A updated successfully!';
                    $qna = $this->qnaModel->getQnaById($qnaId);
                } else {
                    $error = 'Failed to update Q&A. Please try again.';
                }
            }
        }

        require_once 'views/admin/components/header.php';
        require_once 'views/admin/pages/edit-qna.php';
        require_once 'views/admin/components/admin_footer.php';
    }

    public function delete() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            header('Location: index.php?controller=adminQna&action=manage');
            exit;
        }

        $qnaId = (int)$_GET['id'];

        if ($this->qnaModel->deleteQna($qnaId)) {
            $_SESSION['success'] = 'Q&A deleted successfully!';
        } else {
            $_SESSION['error'] = 'Failed to delete Q&A. Please try again.';
        }

        header('Location: index.php?controller=adminQna&action=manage');
        exit;
    }
}
