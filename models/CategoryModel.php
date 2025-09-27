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
            $stmt = $this->pdo->query("SELECT CategoryID, CategoryName FROM `category`");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }
}