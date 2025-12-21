<?php
class UserModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getUserById($userId) {
        $query = "SELECT MemberID, Username, Name, Email, Phone, Exp_VIP, AdminID 
                  FROM member WHERE MemberID = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', (int)$userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserByUsername($username) {
        $query = "SELECT MemberID, Username, Password, Name, Email, Phone, Exp_VIP, AdminID 
                  FROM member WHERE Username = :username LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateUser($userId, $name, $email, $phone) {
        $query = "UPDATE member 
                  SET Name = :name, Email = :email, Phone = :phone 
                  WHERE MemberID = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', (int)$userId, PDO::PARAM_INT);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':phone', $phone, PDO::PARAM_STR);
        return $stmt->execute();
    }

    public function isEmailTaken($email, $userId) {
        $query = "SELECT COUNT(*) FROM member WHERE Email = :email AND MemberID != :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':id', (int)$userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    public function checkPassword($userId, $password) {
        $query = "SELECT Password FROM member WHERE MemberID = :id LIMIT 1";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', (int)$userId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result && $result['Password'] === $password;
    }

    public function updatePassword($userId, $newPassword) {
        $query = "UPDATE member SET Password = :password WHERE MemberID = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':password', $newPassword, PDO::PARAM_STR);
        $stmt->bindValue(':id', (int)$userId, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getDb() {
        return $this->db;
    }
}
