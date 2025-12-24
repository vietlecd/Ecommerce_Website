<?php
require_once __DIR__ . '/Database.php';

class MemberModel {
    private $pdo;

    public function __construct() {
        $database = new Database();
        $this->pdo = $database->getConnection();
    }

    public function getAllMembers() {
        try {
            $stmt = $this->pdo->query("SELECT MemberID, Name, Email, Exp_VIP FROM `member` ORDER BY MemberID ASC");
            $members = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($members as &$member) {
                $stmt = $this->pdo->prepare("SELECT COUNT(*) as OrderCount FROM `order` WHERE MemberID = ?");
                $stmt->execute([$member['MemberID']]);
                $member['OrderCount'] = $stmt->fetchColumn();
            }
            unset($member);

            return $members;
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getMemberById($memberId) {
        $stmt = $this->pdo->prepare("SELECT * FROM `member` WHERE MemberID = ?");
        $stmt->execute([$memberId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function resetPassword($memberId) {
        $stmt = $this->pdo->prepare("UPDATE `member` SET Password = ? WHERE MemberID = ?");
        return $stmt->execute(['1', $memberId]);
    }

    public function getTotalCustomers(): int
    {
        try {
            $stmt = $this->pdo->query("SELECT COUNT(*) AS total FROM `member`");
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)($row['total'] ?? 0);
        } catch (PDOException $e) {
            return 0;
        }
    }
}
