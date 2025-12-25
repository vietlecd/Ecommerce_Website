<?php

class NewsModel
{
    private $db;
    private $newsPromotionColumns = null;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getAllNews($search = '', $limit = 10, $offset = 0)
    {
        $query = "SELECT n.* FROM news n";
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
        $npCols = $this->getNewsPromotionColumns();
        $stmt = $this->db->prepare("
            SELECT 
                n.*,
                CONCAT(a.Fname, ' ', a.Lname) AS AdminName,
                p.*
            FROM news n
            JOIN admin a ON a.AdminID = n.CreatedBy
            LEFT JOIN news_promotion np ON np.{$npCols['news_id']} = n.NewsID
            LEFT JOIN promotions p ON p.promotion_id = np.{$npCols['promotion_id']}
            WHERE n.NewsID = :id
            ORDER BY p.start_date DESC;
        ");
        $stmt->execute([$id]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (!$rows) return null;

        $news = $rows[0];
        $news['promotions'] = [];

        foreach ($rows as $r) {
            $promotionId = $r['promotion_id'] ?? $r['PromotionID'] ?? null;
            if ($promotionId !== null) {
                $startDate = $r['start_date'] ?? $r['StartDate'] ?? null;
                $endDate = $r['end_date'] ?? $r['EndDate'] ?? null;
                $active = false;
                if ($startDate && $endDate) {
                    $now = date('Y-m-d H:i:s');
                    $active = ($startDate <= $now && $now <= $endDate);
                }

                $promo = [
                    'promotion_id' => (int)$promotionId,
                    'promotion_type' => $r['promotion_type'] ?? $r['PromotionType'] ?? null,
                    'promotion_name' => $r['promotion_name'] ?? $r['PromotionName'] ?? null,
                    'discount_percentage' => $r['discount_percentage'] ?? $r['DiscountPercentage'] ?? null,
                    'fixed_price' => $r['fixed_price'] ?? $r['FixedPrice'] ?? null,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'Active' => $active,
                ];
                $promo['PromotionID'] = $promo['promotion_id'];
                $promo['PromotionType'] = $promo['promotion_type'];
                $promo['PromotionName'] = $promo['promotion_name'];
                $promo['DiscountPercentage'] = $promo['discount_percentage'];
                $promo['FixedPrice'] = $promo['fixed_price'];
                $promo['StartDate'] = $promo['start_date'];
                $promo['EndDate'] = $promo['end_date'];

                $news['promotions'][] = $promo;
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
                                    ORDER BY clicks DESC, n.CreatedAt DESC
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

    private function getNewsPromotionColumns(): array
    {
        if ($this->newsPromotionColumns !== null) {
            return $this->newsPromotionColumns;
        }

        $columns = [
            'news_id' => 'NewsID',
            'promotion_id' => 'PromotionID',
        ];

        try {
            $stmt = $this->db->query("SHOW COLUMNS FROM news_promotion");
            $fields = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if (in_array('news_id', $fields, true)) {
                $columns['news_id'] = 'news_id';
            } elseif (in_array('NewsID', $fields, true)) {
                $columns['news_id'] = 'NewsID';
            }

            if (in_array('promotion_id', $fields, true)) {
                $columns['promotion_id'] = 'promotion_id';
            } elseif (in_array('PromotionID', $fields, true)) {
                $columns['promotion_id'] = 'PromotionID';
            }
        } catch (PDOException $e) {
            // Keep defaults if schema inspection fails.
        }

        $this->newsPromotionColumns = $columns;
        return $columns;
    }

    public function getNewsTypes(): array
    {
        $stmt = $this->db->prepare("
            SELECT DISTINCT NewsType
            FROM news
            WHERE NewsType IS NOT NULL AND NewsType <> ''
            ORDER BY NewsType ASC
        ");
        $stmt->execute();

        $types = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $types = array_map('trim', $types ?: []);
        $types = array_filter($types, fn($type) => $type !== '');
        return array_values($types);
    }


    public function getNewsWithAdmin(
        $search = '',
        $limit = 10,
        $offset = 0,
        $type = 'all',
        $sort = 'newest'
    ) {
        $query = "
            SELECT 
                n.*,
                CONCAT(a.Fname, ' ', a.Lname) AS AdminName,
                COALESCE(nc.ClickCount, 0)    AS ClickCount,
                nc.LastClickedAt
            FROM news n
            JOIN admin a ON n.CreatedBy = a.AdminID
            LEFT JOIN (
                SELECT 
                    news_id,
                    SUM(click_count)      AS ClickCount,
                    MAX(last_clicked_at)  AS LastClickedAt
                FROM news_clicks
                GROUP BY news_id
            ) nc ON nc.news_id = n.NewsID
        ";

        $conditions = [];
        $params     = [];

        if ($search !== '') {
            $isNumericSearch = ctype_digit($search);
            $like            = "%{$search}%";

            $searchParts   = [];
            $searchParts[] = 'n.Title LIKE :search_title';
            $params[':search_title'] = $like;

            $searchParts[] = 'n.Description LIKE :search_desc';
            $params[':search_desc'] = $like;

            $searchParts[] = 'n.Content LIKE :search_content';
            $params[':search_content'] = $like;

            $searchParts[] = 'CONCAT(a.Fname, " ", a.Lname) LIKE :search_author';
            $params[':search_author'] = $like;

            if ($isNumericSearch) {
                $searchParts[]        = 'n.NewsID = :search_id';
                $params[':search_id'] = (int)$search;
            }

            $conditions[] = '(' . implode(' OR ', $searchParts) . ')';
        }

        if ($type !== '' && $type !== 'all') {
            $conditions[] = 'n.NewsType = :news_type';
            $params[':news_type'] = $type;
        }

        if (!empty($conditions)) {
            $query .= " WHERE " . implode(" AND ", $conditions);
        }

        $orderBy = $this->getNewsWithAdminSort($sort);
        $query  .= $orderBy . " LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($query);

        foreach ($params as $key => $value) {
            $stmt->bindValue(
                $key,
                $value,
                is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR
            );
        }

        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    private function getNewsWithAdminSort($sort)
    {
        switch ($sort) {
            case 'oldest':
                return " ORDER BY n.CreatedAt ASC";

            case 'views_desc':
                return " ORDER BY ClickCount DESC, n.CreatedAt DESC";
            case 'views_asc':
                return " ORDER BY ClickCount ASC, n.CreatedAt DESC";

            case 'author_asc':
                return " ORDER BY AdminName ASC, n.CreatedAt DESC";
            case 'author_desc':
                return " ORDER BY AdminName DESC, n.CreatedAt DESC";

            case 'title_asc':
                return " ORDER BY n.Title ASC, n.CreatedAt DESC";
            case 'title_desc':
                return " ORDER BY n.Title DESC, n.CreatedAt DESC";

            case 'id_desc':
                return " ORDER BY n.NewsID DESC";
            case 'id_asc':
                return " ORDER BY n.NewsID ASC";

            case 'newest':
            default:
                return " ORDER BY n.CreatedAt DESC";
        }
    }


    public function getNewsCount($search = '', $type = 'all')
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

        if ($type !== '' && $type !== 'all') {
            $conditions[] = 'n.NewsType = :news_type';
            $params[':news_type'] = $type;
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
        if ($type !== '' && $type !== 'all') {
            $stmt->bindValue(':news_type', $type, PDO::PARAM_STR);
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
}
