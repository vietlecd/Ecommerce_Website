<?php
require_once 'models/Database.php';

class CouponModel
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getActiveCoupons()
    {
        $query = "SELECT * FROM discount_codes WHERE IsActive = 1 AND (ValidUntil IS NULL OR ValidUntil >= NOW()) ORDER BY CodePercent DESC, CodeTitle ASC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCouponById($id)
    {
        $query = "SELECT * FROM discount_codes WHERE CodeID = :id AND IsActive = 1 AND (ValidUntil IS NULL OR ValidUntil >= NOW())";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

