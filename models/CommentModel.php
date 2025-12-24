<?php
require_once __DIR__ . '/Database.php';

class CommentModel
{
    private $pdo;

    public function __construct()
    {
        $database = new Database();
        $this->pdo = $database->getConnection();
    }

    public function getCommentsByProductId($shoesId)
    {
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

    public function addComment($shoesId, $memId, $rating, $content, $guestName = null)
    {
        if ($content !== null && strlen($content) > 65535) {
            return ['success' => false, 'error' => 'Content exceeds maximum length of 65535 bytes.'];
        }

        if ($guestName !== null && mb_strlen($guestName) > 100) {
            return ['success' => false, 'error' => 'Guest name exceeds maximum length of 100 characters.'];
        }

        if ($rating < 1 || $rating > 5) {
            return ['success' => false, 'error' => 'Rating must be between 1 and 5.'];
        }

        try {
            $sql = "INSERT INTO comment (ShoesID, Mem_ID, Rating, Content, GuestName, Date) 
                    VALUES (?, ?, ?, ?, ?, CURDATE())";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute([$shoesId, $memId, $rating, $content, $guestName]);

            if (!$result) {
                $errorInfo = $stmt->errorInfo();
                error_log("CommentModel::addComment() - Execute Error: " . print_r($errorInfo, true));
                return ['success' => false, 'error' => 'Failed to save comment. Please try again.'];
            }

            return ['success' => true];
        } catch (PDOException $e) {
            error_log("CommentModel::addComment() - PDOException: " . $e->getMessage());

            if (strpos($e->getMessage(), 'Unknown column') !== false) {
                return ['success' => false, 'error' => 'Database schema error. Please contact administrator.'];
            }

            return ['success' => false, 'error' => 'Failed to save comment. Please try again.'];
        }
    }

    public function getAverageRating($shoesId)
    {
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

    public function deleteComment($commentId)
    {
        $query = "DELETE FROM comment WHERE CommentID = ?";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(1, $commentId);
        return $stmt->execute();
    }


    public function bulkDelete(array $ids): void
    {
        if (!$ids) return;
        $in = implode(',', array_fill(0, count($ids), '?'));
        $sql = "DELETE FROM comment WHERE CommentID IN ($in)";
        $stmt = $this->pdo->prepare($sql);
        $i = 1;
        foreach ($ids as $id) {
            $stmt->bindValue($i++, $id, PDO::PARAM_INT);
        }
        $stmt->execute();
    }


    public function countComments(array $filters = []): int
    {
        $sql = "SELECT COUNT(*) 
                FROM comment c
                LEFT JOIN shoes s ON c.ShoesID = s.ShoesID
                WHERE 1=1";
        $params = [];

        if (!empty($filters['rating'])) {
            $sql .= " AND c.Rating = :rating";
            $params[':rating'] = $filters['rating'];
        }

        if (!empty($filters['shoesId'])) {
            $sql .= " AND c.ShoesID = :sid";
            $params[':sid'] = $filters['shoesId'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (
                        c.Content   LIKE :q_content
                        OR c.GuestName LIKE :q_guest
                        OR s.Name LIKE :q_shoe
                     )";
            $params[':q_content'] = '%' . $filters['search'] . '%';
            $params[':q_guest'] = '%' . $filters['search'] . '%';
            $params[':q_shoe'] = '%' . $filters['search'] . '%';
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    public function getComments(array $filters = [], int $limit = 20, int $offset = 0): array
    {
        $sql = "SELECT 
                    c.*,
                    s.Name,
                    m.Name AS MemberName
                FROM comment c
                LEFT JOIN shoes s ON c.ShoesID = s.ShoesID
                LEFT JOIN member m ON c.Mem_ID = m.MemberID
                WHERE 1=1";
        $params = [];

        if (!empty($filters['rating'])) {
            $sql .= " AND c.Rating = :rating";
            $params[':rating'] = $filters['rating'];
        }

        if (!empty($filters['shoesId'])) {
            $sql .= " AND c.ShoesID = :sid";
            $params[':sid'] = $filters['shoesId'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (
                        c.Content    LIKE :q_content
                        OR c.GuestName LIKE :q_guest
                        OR s.Name LIKE :q_shoe
                     )";
            $params[':q_content'] = '%' . $filters['search'] . '%';
            $params[':q_guest'] = '%' . $filters['search'] . '%';
            $params[':q_shoe'] = '%' . $filters['search'] . '%';
        }

        $sort = $filters['sort'] ?? 'newest';
        switch ($sort) {
            case 'oldest':
                $sql .= " ORDER BY c.Date ASC";
                break;
            case 'rating_desc':
                $sql .= " ORDER BY c.Rating DESC, c.Date DESC";
                break;
            case 'rating_asc':
                $sql .= " ORDER BY c.Rating ASC, c.Date DESC";
                break;
            case 'newest':
            default:
                $sql .= " ORDER BY c.Date DESC";
                break;
        }

        $sql .= " LIMIT :limit OFFSET :offset";

        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getShoesForFilter(): array
    {
        $sql = "SELECT DISTINCT s.ShoesID, s.Name
                FROM shoes s
                INNER JOIN comment c ON c.ShoesID = s.ShoesID
                ORDER BY s.Name ASC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
