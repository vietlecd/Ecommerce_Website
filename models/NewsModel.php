<?php

class NewsModel
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getAllNews($search = '', $limit = 10, $offset = 0)
    {
        $query = "SELECT n.*
        -- , p.promotion_type, p.promotion_name, p.end_date, p.start_date 
                    -- CASE
                    --     WHEN p.start_date > NOW() THEN 'upcoming'
                    --     WHEN p.end_date < NOW() THEN 'expired'
                    --     ELSE 'active'
                    -- END AS promotion_status
                  FROM news n 
                --   LEFT JOIN promotions p ON n.promotion_id = p.promotion_id 
                --   ORDER BY n.DateCreated DESC
                  ";
        $params = [];

        if (!empty($search)) {
            $query .= " AND (n.Title LIKE :search_title OR n.Description LIKE :search_desc)";
            $params[':search_title'] = "%$search%";
            $params[':search_desc'] = "%$search%";
        }

        $query .= " LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($query);

        if (!empty($search)) {
            $stmt->bindValue(':search_title', "%$search%", PDO::PARAM_STR);
            $stmt->bindValue(':search_desc', "%$search%", PDO::PARAM_STR);
        }

        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNewsById($id)
    {
        $stmt = $this->db->prepare("
            SELECT 
                n.*,
                CONCAT(a.Fname, ' ', a.Lname) AS AdminName,
                p.*
            FROM news n
            JOIN admin a ON a.AdminID = n.CreatedBy
            LEFT JOIN news_promotion np ON np.NewsID = n.NewsID
            LEFT JOIN promotion p ON p.PromotionID = np.PromotionID
            WHERE n.NewsID = :id
            ORDER BY p.StartDate DESC;
        ");
        $stmt->execute([$id]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!$rows) return null;

        // Lấy thông tin news từ dòng đầu
        $news = $rows[0];
        $news['promotions'] = [];

        foreach ($rows as $r) {
            if ($r['PromotionID'] !== null) {
                $news['promotions'][] = [
                    'PromotionID'     => (int)$r['PromotionID'],
                    'PromotionType'   => $r['PromotionType'],
                    'PromotionName'   => $r['PromotionName'],
                    'DiscountPercentage' => $r['DiscountPercentage'],
                    'FixedPrice'      => $r['FixedPrice'],
                    'StartDate'       => $r['StartDate'],
                    'EndDate'         => $r['EndDate'],
                    'Active'           => ($r['StartDate'] <= date('Y-m-d H:i:s')
                        && date('Y-m-d H:i:s') <= $r['EndDate']),
                ];
            }
        }

        return $news;
    }

    public function getRecentNews($limit = 4)
    {
        $stmt = $this->db->prepare("SELECT NewsID, Title, Description, Thumbnail, CreatedAt 
                                    FROM news 
                                    ORDER BY CreatedAt DESC 
                                    LIMIT :limit");
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPopularNews($limit = 4)
    {
        $stmt = $this->db->prepare("SELECT n.NewsID, n.Title, n.Description, n.Thumbnail, n.CreatedAt, COALESCE(nc.click_count, 0) AS clicks
                                    FROM news n
                                    LEFT JOIN news_clicks nc ON n.NewsID = nc.news_id
                                    ORDER BY clicks DESC, n.DateCreated DESC
                                    LIMIT :limit");
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function incrementClickCount($newsId)
    {
        $query = "SELECT click_count FROM news_clicks WHERE news_id = :news_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':news_id', (int)$newsId, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $query = "UPDATE news_clicks SET click_count = click_count + 1, last_clicked_at = NOW() 
                      WHERE news_id = :news_id";
        } else {
            $query = "INSERT INTO news_clicks (news_id, click_count) VALUES (:news_id, 1)";
        }

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':news_id', (int)$newsId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getClickStats($search = '', $limit = 10, $offset = 0)
    {
        $query = "SELECT n.NewsID, n.Title, nc.click_count, nc.last_clicked_at 
                  FROM news n 
                  LEFT JOIN news_clicks nc ON n.NewsID = nc.news_id";
        $params = [];

        if (!empty($search)) {
            $query .= " WHERE n.Title LIKE :search_title OR n.Description LIKE :search_desc";
            $params = [':search_title' => "%$search%", ':search_desc' => "%$search%"];
        }

        $query .= " LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($query);

        if (!empty($search)) {
            $stmt->bindValue(':search_title', "%$search%", PDO::PARAM_STR);
            $stmt->bindValue(':search_desc', "%$search%", PDO::PARAM_STR);
        }

        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getClickStatsCount($search = '')
    {
        $query = "SELECT COUNT(*) FROM news n";
        $params = [];

        if (!empty($search)) {
            $query .= " WHERE n.Title LIKE :search_title OR n.Description LIKE :search_desc";
            $params = [':search_title' => "%$search%", ':search_desc' => "%$search%"];
        }

        $stmt = $this->db->prepare($query);
        if (!empty($search)) {
            $stmt->bindValue(':search_title', "%$search%", PDO::PARAM_STR);
            $stmt->bindValue(':search_desc', "%$search%", PDO::PARAM_STR);
        }
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function addNews($title, $description, $content, $admin_id, $news_type, $thumbnail = null)
    {
        $query = "INSERT INTO news (Title, Description, Content, CreatedBy, NewsType, Thumbnail) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);

        $ok = $stmt->execute([$title, $description, $content, $admin_id, $news_type, $thumbnail]);

        if (!$ok) {
            return 0;
        }

        return (int)$this->db->lastInsertId();
    }


    public function updateNews($id, $title, $description, $content, $news_type, $thumbnail = null)
    {
        $query = "UPDATE news SET Title = ?, Description = ?, Content = ?, NewsType = ?, Thumbnail = ? 
                  WHERE NewsID = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$title, $description, $content, $news_type, $thumbnail, $id]);
    }

    public function deleteNews($id)
    {
        $id = (int)$id;

        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("DELETE FROM news_promotion WHERE NewsID = :id");
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $stmt2 = $this->db->prepare("DELETE FROM news WHERE NewsID = :id");
            $stmt2->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt2->execute();

            $this->db->commit();

            return $stmt2->rowCount() > 0;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log('deleteNews error: ' . $e->getMessage());
            return false;
        }
    }


    public function getNewsWithAdmin($search = '', $limit = 10, $offset = 0, $status = 'all')
    {
        $query = "
        SELECT 
            n.*,
            CONCAT(admin.Fname, ' ', admin.Lname) AS AdminName
        FROM news n
        JOIN admin ON n.CreatedBy = admin.AdminID
    ";

        $conditions = [];
        $params = [];

        if (!empty($search)) {
            $conditions[] = "(n.Title LIKE :search_title OR n.Description LIKE :search_desc OR n.Content LIKE :search_content) OR CONCAT(admin.Fname, ' ', admin.Lname) LIKE :search_author";
            $params[':search_title'] = "%{$search}%";
            $params[':search_desc'] = "%{$search}%";
            $params[':search_content'] = "%{$search}%";
            $params[':search_author'] = "%{$search}%";
        }

        if ($status === 'pending') {
            $conditions[] = "
            EXISTS (
                SELECT 1
                FROM news_promotion np
                JOIN promotion p ON p.PromotionID = np.PromotionID
                WHERE np.NewsID = n.NewsID
                  AND p.StartDate > NOW()
            )
        ";
        } elseif ($status === 'active') {
            $conditions[] = "
            EXISTS (
                SELECT 1
                FROM news_promotion np
                JOIN promotion p ON p.PromotionID = np.PromotionID
                WHERE np.NewsID = n.NewsID
                  AND p.StartDate <= NOW()
                  AND p.EndDate   >= NOW()
            )
        ";
        } elseif ($status === 'expired') {
            $conditions[] = "
            EXISTS (
                SELECT 1
                FROM news_promotion np
                JOIN promotion p ON p.PromotionID = np.PromotionID
                WHERE np.NewsID = n.NewsID
                  AND p.EndDate < NOW()
            )
        ";
        }

        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        $query .= " ORDER BY n.CreatedAt DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($query);

        if (!empty($search)) {
            $stmt->bindValue(':search_title', "%{$search}%", PDO::PARAM_STR);
            $stmt->bindValue(':search_desc', "%{$search}%", PDO::PARAM_STR);
            $stmt->bindValue(':search_content', "%{$search}%", PDO::PARAM_STR);
            $stmt->bindValue(':search_author', "%{$search}%", PDO::PARAM_STR);
        }

        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getNewsCount($search = '', $status = 'all')
    {
        $query = "
        SELECT COUNT(*) 
        FROM news n
        JOIN admin ON n.CreatedBy = admin.AdminID
    ";

        $conditions = [];
        $params = [];

        if (!empty($search)) {
            $conditions[] = "(n.Title LIKE :search_title OR n.Description LIKE :search_desc OR n.Content LIKE :search_content) OR CONCAT(admin.Fname, ' ', admin.Lname) LIKE :search_author";
            $params[':search_title'] = "%{$search}%";
            $params[':search_desc'] = "%{$search}%";
            $params[':search_content'] = "%{$search}%";
            $params[':search_author'] = "%{$search}%";
        }

        if ($status === 'pending') {
            $conditions[] = "
            EXISTS (
                SELECT 1
                FROM news_promotion np
                JOIN promotion p ON p.PromotionID = np.PromotionID
                WHERE np.NewsID = n.NewsID
                  AND p.StartDate > NOW()
            )
        ";
        } elseif ($status === 'active') {
            $conditions[] = "
            EXISTS (
                SELECT 1
                FROM news_promotion np
                JOIN promotion p ON p.PromotionID = np.PromotionID
                WHERE np.NewsID = n.NewsID
                  AND p.StartDate <= NOW()
                  AND p.EndDate   >= NOW()
            )
        ";
        } elseif ($status === 'expired') {
            $conditions[] = "
            EXISTS (
                SELECT 1
                FROM news_promotion np
                JOIN promotion p ON p.PromotionID = np.PromotionID
                WHERE np.NewsID = n.NewsID
                  AND p.EndDate < NOW()
            )
        ";
        }

        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        $stmt = $this->db->prepare($query);

        if (!empty($search)) {
            $stmt->bindValue(':search_title', "%{$search}%", PDO::PARAM_STR);
            $stmt->bindValue(':search_desc', "%{$search}%", PDO::PARAM_STR);
            $stmt->bindValue(':search_content', "%{$search}%", PDO::PARAM_STR);
            $stmt->bindValue(':search_author', "%{$search}%", PDO::PARAM_STR);
        }

        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }


    public function getPublicNewsCount($search = '')
    {
        $query = "SELECT COUNT(*) FROM news n 
                --   LEFT JOIN promotions p ON n.promotion_id = p.promotion_id 
                --   WHERE (n.promotion_id IS NULL OR (p.start_date <= NOW() AND p.end_date >= NOW()))
                  ";
        $params = [];

        if (!empty($search)) {
            $query .= " AND (n.Title LIKE :search_title OR n.Description LIKE :search_desc)";
            $params = [':search_title' => "%$search%", ':search_desc' => "%$search%"];
        }

        $stmt = $this->db->prepare($query);
        if (!empty($search)) {
            $stmt->bindValue(':search_title', "%$search%", PDO::PARAM_STR);
            $stmt->bindValue(':search_desc', "%$search%", PDO::PARAM_STR);
        }
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getNewsList($keyword = '')
    {
        $query = "SELECT * FROM news WHERE title LIKE ? OR content LIKE ?";
        $stmt = $this->db->prepare($query);
        $search = "%$keyword%";
        $stmt->bindParam(1, $search);
        $stmt->bindParam(2, $search);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCommentsByNewsId($newsId, int $limit = 5,  ?string $lastCreatedAt = null)
    {
        $lastCreatedAt = $lastCreatedAt ?? date('Y-m-d H:i:s');
        $query = "
            SELECT nc.*, m.Username
            FROM comment AS nc 
            LEFT JOIN member AS m ON nc.CreatedBy = m.MemberID
            WHERE NewsID = :nid AND nc.CreatedAt < :lastCreatedAt
            ORDER BY nc.CreatedAt DESC, nc.CommentID DESC
            LIMIT :lim 
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nid', $newsId, PDO::PARAM_INT);
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':lastCreatedAt', $lastCreatedAt, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCommentCountByNewsId($newsId)
    {
        $query = "SELECT COUNT(*) FROM comment WHERE NewsID = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $newsId);
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    public function getCommentById($commentId)
    {
        $query = "
            SELECT nc.*, m.Username
            FROM comment AS nc 
            LEFT JOIN member AS m ON nc.CreatedBy = m.MemberID
            WHERE CommentID = :comment_id
        ";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':comment_id', $commentId);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addComment($newsId, $comment)
    {
        $query = "INSERT INTO comment (NewsID, Content) VALUES (?, ?)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(1, $newsId);
        $stmt->bindParam(2, $comment);
        $stmt->execute();

        return (int)$this->db->lastInsertId();
    }
}
