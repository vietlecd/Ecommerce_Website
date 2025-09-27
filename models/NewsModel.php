<?php

class NewsModel {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getAllNews($search = '', $limit = 10, $offset = 0) {
        $query = "SELECT n.*, p.promotion_type, p.promotion_name, p.end_date, p.start_date 
                  FROM news n 
                  LEFT JOIN promotions p ON n.promotion_id = p.promotion_id 
                  WHERE (n.promotion_id IS NULL OR (p.start_date <= NOW() AND p.end_date >= NOW()))";
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

    public function getNewsById($id) {
        $stmt = $this->db->prepare("SELECT news.*, admin.Fname, admin.Lname, CONCAT(admin.Fname, ' ', admin.Lname) AS AdminName, p.end_date, p.start_date 
                                   FROM news 
                                   JOIN admin ON news.AdminID = admin.AdminID 
                                   LEFT JOIN promotions p ON news.promotion_id = p.promotion_id 
                                   WHERE NewsID = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function incrementClickCount($newsId) {
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

    public function getClickStats($search = '', $limit = 10, $offset = 0) {
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

    public function getClickStatsCount($search = '') {
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

    public function addNews($title, $description, $content, $admin_id, $news_type, $promotion_id = null, $thumbnail = null) {
        $query = "INSERT INTO news (Title, Description, Content, AdminID, news_type, promotion_id, thumbnail) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$title, $description, $content, $admin_id, $news_type, $promotion_id, $thumbnail]);
    }

    public function updateNews($id, $title, $description, $content, $news_type, $promotion_id = null, $thumbnail = null) {
        $query = "UPDATE news SET Title = ?, Description = ?, Content = ?, news_type = ?, promotion_id = ?, thumbnail = ? 
                  WHERE NewsID = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$title, $description, $content, $news_type, $promotion_id, $thumbnail, $id]);
    }

    public function deleteNews($id) {
        $stmt = $this->db->prepare("DELETE FROM news WHERE NewsID = ?");
        return $stmt->execute([$id]);
    }

    public function getNewsWithAdmin($search = '', $limit = 10, $offset = 0, $status = 'all') {
        $query = "SELECT n.*, CONCAT(admin.Fname, ' ', admin.Lname) AS AdminName, p.promotion_name, p.start_date, p.end_date 
                  FROM news n 
                  JOIN admin ON n.AdminID = admin.AdminID 
                  LEFT JOIN promotions p ON n.promotion_id = p.promotion_id";
        $params = [];

        $conditions = [];

        // Bộ lọc trạng thái
        if ($status !== 'all') {
            if ($status === 'pending') {
                $conditions[] = "n.promotion_id IS NOT NULL AND p.start_date > NOW()";
            } elseif ($status === 'active') {
                $conditions[] = "n.promotion_id IS NOT NULL AND p.start_date <= NOW() AND p.end_date >= NOW()";
            } elseif ($status === 'expired') {
                $conditions[] = "n.promotion_id IS NOT NULL AND p.end_date < NOW()";
            }
        }

        // Bộ lọc tìm kiếm
        if (!empty($search)) {
            $conditions[] = "(n.Title LIKE :search_title OR n.Description LIKE :search_desc OR n.Content LIKE :search_content)";
            $params[':search_title'] = "%$search%";
            $params[':search_desc'] = "%$search%";
            $params[':search_content'] = "%$search%";
        }

        // Thêm điều kiện vào truy vấn
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        $query .= " LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($query);

        if (!empty($search)) {
            $stmt->bindValue(':search_title', "%$search%", PDO::PARAM_STR);
            $stmt->bindValue(':search_desc', "%$search%", PDO::PARAM_STR);
            $stmt->bindValue(':search_content', "%$search%", PDO::PARAM_STR);
        }

        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNewsCount($search = '', $status = 'all') {
        $query = "SELECT COUNT(*) 
                  FROM news 
                  JOIN admin ON news.AdminID = admin.AdminID 
                  LEFT JOIN promotions p ON news.promotion_id = p.promotion_id";
        $params = [];

        $conditions = [];

        // Bộ lọc trạng thái
        if ($status !== 'all') {
            if ($status === 'pending') {
                $conditions[] = "news.promotion_id IS NOT NULL AND p.start_date > NOW()";
            } elseif ($status === 'active') {
                $conditions[] = "news.promotion_id IS NOT NULL AND p.start_date <= NOW() AND p.end_date >= NOW()";
            } elseif ($status === 'expired') {
                $conditions[] = "news.promotion_id IS NOT NULL AND p.end_date < NOW()";
            }
        }

        // Bộ lọc tìm kiếm
        if (!empty($search)) {
            $conditions[] = "(news.Title LIKE :search_title OR news.Description LIKE :search_desc OR news.Content LIKE :search_content)";
            $params[':search_title'] = "%$search%";
            $params[':search_desc'] = "%$search%";
            $params[':search_content'] = "%$search%";
        }

        // Thêm điều kiện vào truy vấn
        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        $stmt = $this->db->prepare($query);
        if (!empty($search)) {
            $stmt->bindValue(':search_title', "%$search%", PDO::PARAM_STR);
            $stmt->bindValue(':search_desc', "%$search%", PDO::PARAM_STR);
            $stmt->bindValue(':search_content', "%$search%", PDO::PARAM_STR);
        }
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getPublicNewsCount($search = '') {
        $query = "SELECT COUNT(*) FROM news n 
                  LEFT JOIN promotions p ON n.promotion_id = p.promotion_id 
                  WHERE (n.promotion_id IS NULL OR (p.start_date <= NOW() AND p.end_date >= NOW()))";
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
}