<?php
require_once 'models/QnaModel.php';

class QnaController {
    private $qnaModel;

    public function __construct() {
        $this->qnaModel = new QnaModel();
    }

    public function index() {
        $qnaList = $this->qnaModel->getAllActiveQna();
        
        require_once 'views/components/header.php';
        require_once 'views/pages/qna.php';
        require_once 'views/components/footer.php';
    }
}
