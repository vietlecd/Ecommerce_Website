<?php
require_once 'models/AboutModel.php';

class AboutController {
    private $aboutModel;

    public function __construct() {
        $this->aboutModel = new AboutModel();
    }

    public function index() {
        $aboutContent = $this->aboutModel->getAboutContent();
        
        require_once 'views/components/header.php';
        require_once 'views/pages/about.php';
        require_once 'views/components/footer.php';
    }
}
