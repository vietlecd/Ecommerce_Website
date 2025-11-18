<?php
require_once __DIR__ . '/Database.php';

class CommentModel {
    private $pdo;

    public function __construct() {
        $database = new Database();
        $this->pdo = $database->getConnection();
    }

    public function getCommentsByProductId($shoesId) {
        try {
            $sql = "SELECT c.CommentID, c.Mem_ID, c.Rating, c.Date, c.Content, c.GuestName,
                           m.Username, m.Name AS MemberName
                    FROM comment c
                    LEFT JOIN member m ON c.Mem_ID = m.MemberID
                    WHERE c.ShoesID = ?
                    ORDER BY c.Date DESC, c.CommentID DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$shoesId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("CommentModel Error: " . $e->getMessage());
            return [];
        }
    }

    public function addComment($shoesId, $memId, $rating, $content, $guestName = null) {
        try {
            $sql = "INSERT INTO comment (ShoesID, Mem_ID, Rating, Content, GuestName, Date) 
                    VALUES (?, ?, ?, ?, ?, CURDATE())";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$shoesId, $memId, $rating, $content, $guestName]);
            
            if (!$result) {
                $errorInfo = $stmt->errorInfo();
                error_log("CommentModel Execute Error: " . print_r($errorInfo, true));
                return ['success' => false, 'error' => $errorInfo[2] ?? 'Unknown error'];
            }
            
            return ['success' => true];
        } catch (PDOException $e) {
            $errorMessage = $e->getMessage();
            error_log("CommentModel PDOException: " . $errorMessage);
            
            if (strpos($errorMessage, 'Unknown column') !== false) {
                return ['success' => false, 'error' => 'Database columns missing. Please run migration: 002_add_comment_content.sql'];
            }
            
            return ['success' => false, 'error' => $errorMessage];
        }
    }

    public function getAverageRating($shoesId) {
        try {
            $stmt = $this->pdo->prepare("SELECT AVG(Rating) AS avgRating, COUNT(*) AS totalComments 
                                         FROM comment WHERE ShoesID = ?");
            $stmt->execute([$shoesId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("CommentModel Error: " . $e->getMessage());
            return ['avgRating' => 0, 'totalComments' => 0];
        }
    }
}
