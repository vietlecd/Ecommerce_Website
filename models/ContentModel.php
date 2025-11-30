<?php

class ContentModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getContentByKey($key) {
        $stmt = $this->db->prepare("SELECT * FROM site_contents WHERE page_key = ? LIMIT 1");
        $stmt->execute([$key]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function saveContent($key, $content) {
        $existing = $this->getContentByKey($key);
        
        if ($existing) {
            $stmt = $this->db->prepare("UPDATE site_contents SET html_content = ?, updated_at = CURRENT_TIMESTAMP WHERE page_key = ?");
            return $stmt->execute([$content, $key]);
        } else {
            $stmt = $this->db->prepare("INSERT INTO site_contents (page_key, html_content) VALUES (?, ?)");
            return $stmt->execute([$key, $content]);
        }
    }
}

