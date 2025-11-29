<?php
require_once 'models/ContentModel.php';

class AboutController {
    private $contentModel;

    public function __construct() {
        $this->contentModel = new ContentModel();
    }

    public function index() {
        $content = $this->contentModel->getContentByKey('about');
        $htmlContent = $content ? $content['html_content'] : '';
        
        require_once 'views/components/header.php';
        require_once 'views/pages/about.php';
        require_once 'views/components/footer.php';
    }
}
