<?php
require_once __DIR__ . '/Database.php';

class CategoryModel {
    private $pdo;

    public function __construct() {
        $database = new Database();
        $this->pdo = $database->getConnection();
    }

    public function getAllCategories() {
        try {
            $stmt = $this->pdo->query("SELECT CategoryID, Name AS CategoryName, Description, ImageUrl FROM `category`");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("CategoryModel Error: " . $e->getMessage());
            return [];
        }
    }

    public function getRandomCategories($limit = 2) {
        try {
            $stmt = $this->pdo->prepare("SELECT CategoryID, Name AS CategoryName, Description, ImageUrl FROM `category` ORDER BY RAND() LIMIT :limit");
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("CategoryModel Error: " . $e->getMessage());
            return [];
        }
    }
}