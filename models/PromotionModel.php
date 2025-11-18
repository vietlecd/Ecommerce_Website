<?php

class PromotionModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getAllPromotions() {
        $stmt = $this->db->prepare("SELECT * FROM promotions");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPromotionById($id) {
        $stmt = $this->db->prepare("SELECT * FROM promotions WHERE promotion_id = :id");
        $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getShoesByPromotion($promotion_id, $limit = 10, $offset = 0) {
        try {
            $query = "SELECT s.* 
                      FROM shoes s 
                      JOIN promotion_shoes ps ON s.ShoesID = ps.shoe_id 
                      WHERE ps.promotion_id = :promotion_id 
                      LIMIT :limit OFFSET :offset";
            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':promotion_id', (int)$promotion_id, PDO::PARAM_INT);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getShoesByPromotion: " . $e->getMessage());
            return [];
        }
    }

    public function getShoesCountByPromotion($promotion_id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) 
                                    FROM shoes s 
                                    JOIN promotion_shoes ps ON s.ShoesID = ps.shoe_id 
                                    WHERE ps.promotion_id = :promotion_id");
        $stmt->bindValue(':promotion_id', (int)$promotion_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }
}