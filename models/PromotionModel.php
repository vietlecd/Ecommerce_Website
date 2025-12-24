<?php

class PromotionModel
{
    private $db;

    public function __construct()
    {
        $database = new Database();
        $this->db = $database->getConnection();
    }


    public function getPromotionsCount(
        string $keyword = '',
        ?string $fromDate = null,
        ?string $toDate = null
    ): int {
        $sql = "SELECT COUNT(*) FROM promotions WHERE 1=1";
        $params = [];

        if ($keyword !== '') {
            $sql .= " AND promotion_name LIKE :kw";
            $params[':kw'] = "%{$keyword}%";
        }

        if (!empty($fromDate)) {
            $sql .= " AND start_date >= :fromDate";
            $params[':fromDate'] = $fromDate . ' 00:00:00';
        }

        if (!empty($toDate)) {
            $sql .= " AND end_date <= :toDate";
            $params[':toDate'] = $toDate . ' 23:59:59';
        }

        $stmt = $this->db->prepare($sql);
        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v, PDO::PARAM_STR);
        }
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    public function getPromotionById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM promotions WHERE promotion_id = :id");
        $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getShoesByPromotion($promotion_id, $limit = 10, $offset = 0)
    {
        try {
            $query = "
            SELECT s.*
            FROM shoes s
            INNER JOIN promotion_shoes ps
                ON s.ShoesID = ps.ShoesID
            WHERE ps.promotion_id = :promotion_id
            LIMIT :limit OFFSET :offset
        ";

            $stmt = $this->db->prepare($query);
            $stmt->bindValue(':promotion_id', (int)$promotion_id, PDO::PARAM_INT);
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error in getShoesByPromotion: " . $e->getMessage());
            return [];
        }
    }

    public function getShoesCountByPromotion($promotion_id)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*)
            FROM shoes s
            INNER JOIN promotion_shoes ps
                ON s.ShoesID = ps.ShoesID
            WHERE ps.promotion_id = :promotion_id
        ");
        $stmt->bindValue(':promotion_id', (int)$promotion_id, PDO::PARAM_INT);
        $stmt->execute();

        return (int)$stmt->fetchColumn();
    }

    public function getAllPromotions(
        ?int $limit = null,
        int $offset = 0,
        string $keyword = '',
        string $sort = 'ASC',
        ?string $fromDate = null,
        ?string $toDate = null
    ): array {
        $sql = "SELECT * FROM promotions WHERE 1=1";
        $params = [];

        if ($keyword !== '') {
            $sql .= " AND promotion_name LIKE :kw";
            $params[':kw'] = "%{$keyword}%";
        }

        if (!empty($fromDate)) {
            $sql .= " AND start_date >= :fromDate";
            $params[':fromDate'] = $fromDate . ' 00:00:00';
        }

        if (!empty($toDate)) {
            $sql .= " AND end_date <= :toDate";
            $params[':toDate'] = $toDate . ' 23:59:59';
        }

        $sort = strtoupper($sort) === 'DESC' ? 'DESC' : 'ASC';
        $sql .= " ORDER BY promotion_id {$sort}";

        $useLimit = $limit !== null;
        if ($useLimit) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }

        $stmt = $this->db->prepare($sql);

        foreach ($params as $k => $v) {
            $stmt->bindValue($k, $v, PDO::PARAM_STR);
        }

        if ($useLimit) {
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getPromotionsByNewsId(int $newsId, ?bool $onlyActive = null): array
    {
        $sql = "
        SELECT p.*
        FROM news_promotion np
        INNER JOIN promotions p ON p.promotion_id = np.promotion_id
        WHERE np.NewsID = :news_id
    ";
        if ($onlyActive === true) {
            $sql .= "
            AND (
                (p.start_date IS NULL OR p.start_date <= CURDATE())
                AND (p.end_date IS NULL OR p.end_date >= CURDATE())
            )
        ";
        }

        $sql .= " ORDER BY p.start_date ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':news_id', $newsId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function syncNewsPromotions(int $newsId, array $promotionIds): bool
    {
        $promotionIds = array_unique(array_map('intval', $promotionIds));
        $promotionIds = array_values(array_filter($promotionIds, fn($x) => $x > 0));

        try {
            $this->db->beginTransaction();

            $del = $this->db->prepare("DELETE FROM news_promotion WHERE NewsID = :nid");
            $del->bindValue(':nid', $newsId, PDO::PARAM_INT);
            $del->execute();

            if (!empty($promotionIds)) {
                $ins = $this->db->prepare("
                    INSERT INTO news_promotion (NewsID, PromotionID)
                    VALUES (:nid, :pid)
                ");

                foreach ($promotionIds as $pid) {
                    $ins->bindValue(':nid', $newsId, PDO::PARAM_INT);
                    $ins->bindValue(':pid', $pid, PDO::PARAM_INT);
                    $ins->execute();
                }
            }

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log('syncNewsPromotions error: ' . $e->getMessage());
            return false;
        }
    }



    public function createPromotion($name, $startDate, $endDate, $discountPercentage, $fixedPrice, $promotionType)
    {
        $query = "INSERT INTO promotions (promotion_name, start_date, end_date, discount_percentage, fixed_price, promotion_type) 
                  VALUES (:promotion_name, :start_date, :end_date, :discount_percentage, :fixed_price, :promotion_type)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':promotion_name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':start_date', $startDate, PDO::PARAM_STR);
        $stmt->bindValue(':end_date', $endDate, PDO::PARAM_STR);
        $stmt->bindValue(':discount_percentage', $discountPercentage, PDO::PARAM_STR);
        $stmt->bindValue(':fixed_price', $fixedPrice, PDO::PARAM_STR);
        $stmt->bindValue(':promotion_type', $promotionType, PDO::PARAM_STR);
        $stmt->execute();
    }

    public function updatePromotion($promotionId, $name, $startDate, $endDate, $discountPercentage, $fixedPrice, $promotionType)
    {
        $query = "UPDATE promotions 
                  SET promotion_name = :promotion_name, 
                      start_date = :start_date, 
                      end_date = :end_date, 
                      discount_percentage = :discount_percentage, 
                      fixed_price = :fixed_price, 
                      promotion_type = :promotion_type 
                  WHERE promotion_id = :promotion_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':promotion_id', (int)$promotionId, PDO::PARAM_INT);
        $stmt->bindValue(':promotion_name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':start_date', $startDate, PDO::PARAM_STR);
        $stmt->bindValue(':end_date', $endDate, PDO::PARAM_STR);
        $stmt->bindValue(':discount_percentage', $discountPercentage, PDO::PARAM_STR);
        $stmt->bindValue(':fixed_price', $fixedPrice, PDO::PARAM_STR);
        $stmt->bindValue(':promotion_type', $promotionType, PDO::PARAM_STR);
        $stmt->execute();
    }

    public function deletePromotion($promotionId)
    {
        $promotionId = (int)$promotionId;

        try {
            $this->db->beginTransaction();

            $sql = "DELETE FROM promotion_shoes WHERE promotion_id = :promotion_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':promotion_id', $promotionId, PDO::PARAM_INT);
            $stmt->execute();

            $sql = "DELETE FROM promotions WHERE promotion_id = :promotion_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':promotion_id', $promotionId, PDO::PARAM_INT);
            $stmt->execute();

            $this->db->commit();
        } catch (PDOException $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    private function getPromotionForProduct($product_id)
    {
        $current_date = date('Y-m-d H:i:s');

        $query = "
            SELECT p.*
            FROM promotions p
            INNER JOIN promotion_shoes ps
                ON p.promotion_id = ps.promotion_id
            WHERE ps.ShoesID   = :shoe_id
              AND p.start_date <= :start_date
              AND p.end_date   >= :end_date
            LIMIT 1
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':shoe_id', (int)$product_id, PDO::PARAM_INT);
        $stmt->bindValue(':start_date', $current_date, PDO::PARAM_STR);
        $stmt->bindValue(':end_date', $current_date, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function calculateDiscountedPrice($product, $promotion)
    {
        if (!empty($promotion)) {
            if (isset($promotion['DiscountPercentage']) && $promotion['DiscountPercentage'] !== null) {
                $discount = (float)$promotion['DiscountPercentage'];
                return $product['price'] * (1 - $discount / 100);
            }

            if (isset($promotion['FixedPrice']) && $promotion['FixedPrice'] !== null) {
                return (float)$promotion['FixedPrice'];
            }
        }

        return $product['price'];
    }

    public function getProductsByPromotionId($promotionId): array
    {
        $query = "
        SELECT ShoesID
        FROM promotion_shoes
        WHERE promotion_id = :promotion_id
    ";
        $stmt  = $this->db->prepare($query);
        $stmt->bindValue(':promotion_id', (int)$promotionId, PDO::PARAM_INT);
        $stmt->execute();

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map('intval', array_column($rows, 'ShoesID'));
    }


    public function removeAllProductsFromPromotion($promotionId)
    {
        $query = "DELETE FROM promotion_shoes WHERE promotion_id = :promotion_id";
        $stmt  = $this->db->prepare($query);
        $stmt->bindValue(':promotion_id', (int)$promotionId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function assignProductToPromotion($promotionId, $productId): void
    {
        $query = "
            INSERT IGNORE INTO promotion_shoes (promotion_id, ShoesID)
            VALUES (:promotion_id, :shoes_id)
        ";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':promotion_id', (int)$promotionId, PDO::PARAM_INT);
        $stmt->bindValue(':shoes_id',     (int)$productId,   PDO::PARAM_INT);
        $stmt->execute();
    }


    public function removeProductFromAllPromotions($productId): void
    {
        $query = "
            DELETE FROM promotion_shoes
            WHERE ShoesID = :ShoesID
        ";
        $stmt  = $this->db->prepare($query);
        $stmt->bindValue(':ShoesID', (int)$productId, PDO::PARAM_INT);
        $stmt->execute();
    }
}
