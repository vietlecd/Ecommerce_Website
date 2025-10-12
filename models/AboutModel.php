<?php

class AboutModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getAboutContent() {
        $stmt = $this->db->prepare("SELECT a.*, CONCAT(ad.Fname, ' ', ad.Lname) AS UpdatedByName 
                                   FROM about a 
                                   LEFT JOIN admin ad ON a.UpdatedBy = ad.AdminID 
                                   WHERE AboutID = 1");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result && isset($result['Image']) && strpos($result['Image'], '/var/www/html/') === 0) {
            $result['Image'] = substr($result['Image'], strlen('/var/www/html/'));
        }
        return $result;
    }

    public function updateAboutContent($title, $content, $image, $adminId) {
        $query = "UPDATE about SET Title = ?, Content = ?, UpdatedBy = ?";
        $params = [$title, $content, $adminId];
        
        if ($image !== null) {
            $query .= ", Image = ?";
            $params[] = $image;
        }
        
        $query .= " WHERE AboutID = 1";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute($params);
    }
}
