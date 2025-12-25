<?php
require_once 'models/ProductModelv2.php';

class PromotionalProductModel
{
    private $productModel;
    private $db;
    private $promotionShoesColumns;
    private $newsPromotionColumns;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function getAllProducts()
    {
        $products = $this->productModel->getAllProducts();
        foreach ($products as &$product) {
            $product['promotion'] = $this->getActivePromotionForProduct($product['id']);
            $product['final_price'] = $this->calculateDiscountedPrice($product, $product['promotion']);
        }
        return $products;
    }

    public function getProductById($id)
    {
        $product = $this->productModel->getProductById($id);
        if ($product) {
            $product['promotion'] = $this->getActivePromotionForProduct($id);
            $product['final_price'] = $this->calculateDiscountedPrice($product, $product['promotion']);
        }
        return $product;
    }

    public function getTopSaleProducts($limit = 4)
    {
        $products = $this->getAllProducts();
        $discounted = array_filter($products, function ($product) {
            return isset($product['price'], $product['final_price']) && $product['price'] > 0 && $product['final_price'] < $product['price'];
        });

        usort($discounted, function ($a, $b) {
            $discountA = ($a['price'] - $a['final_price']) / $a['price'];
            $discountB = ($b['price'] - $b['final_price']) / $b['price'];
            if ($discountA === $discountB) {
                return $a['final_price'] <=> $b['final_price'];
            }
            return $discountB <=> $discountA;
        });

        return array_slice($discounted, 0, $limit);
    }

    private function getActivePromotionForProduct($productId)
    {
        $now = date('Y-m-d H:i:s');
        $columns = $this->getPromotionShoesColumns();
        $promotionIdColumn = $columns['promotion_id'];
        $shoeIdColumn = $columns['shoe_id'];

        $sql = "
            SELECT
                p.promotion_id        AS promotion_id,
                p.promotion_name      AS promotion_name,
                p.promotion_type      AS promotion_type,
                p.discount_percentage AS discount_percentage,
                p.fixed_price         AS fixed_price,
                p.start_date          AS start_date,
                p.end_date            AS end_date
            FROM promotions p
            INNER JOIN promotion_shoes ps
                ON p.promotion_id = ps.{$promotionIdColumn}
            WHERE ps.{$shoeIdColumn} = :shoes_id
              AND p.start_date <= :start_date
              AND p.end_date   >= :end_date
            ORDER BY
                -- ưu tiên có phần trăm giảm
                (p.discount_percentage IS NULL) ASC,
                p.discount_percentage DESC,
                p.promotion_id ASC
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':shoes_id', (int)$productId, PDO::PARAM_INT);
        $stmt->bindValue(':start_date', $now, PDO::PARAM_STR);
        $stmt->bindValue(':end_date', $now, PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    private function calculateDiscountedPrice(array $product, ?array $promotion)
    {
        $price = (float)$product['price'];

        if ($promotion) {
            $discountPct = $promotion['discount_percentage'] ?? null;
            $fixedPrice  = $promotion['fixed_price'] ?? null;

            if ($discountPct !== null && $discountPct !== '') {
                return $price * (1 - (float)$discountPct / 100);
            }

            if ($fixedPrice !== null && $fixedPrice !== '') {
                return (float)$fixedPrice;
            }
        }

        return $price;
    }

    public function getAllPromotions($limit = 10, $offset = 0, $keyword = '', $sort = 'ASC')
    {
        $sql = "
            SELECT
                p.promotion_id,
                p.promotion_name,
                p.promotion_type,
                p.discount_percentage,
                p.fixed_price,
                p.start_date,
                p.end_date
            FROM promotions p
            WHERE 1 = 1
        ";

        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND p.promotion_name LIKE :keyword";
            $params[':keyword'] = '%' . $keyword . '%';
        }

        $sql .= " ORDER BY p.promotion_id " . ($sort === 'DESC' ? 'DESC' : 'ASC');
        $sql .= " LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        if (!empty($keyword)) {
            $stmt->bindValue(':keyword', $params[':keyword'], PDO::PARAM_STR);
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPromotionsCount($keyword = '')
    {
        $sql = "SELECT COUNT(*) FROM promotions p WHERE 1 = 1";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND p.promotion_name LIKE :keyword";
            $params[':keyword'] = '%' . $keyword . '%';
        }

        $stmt = $this->db->prepare($sql);
        if (!empty($keyword)) {
            $stmt->bindValue(':keyword', $params[':keyword'], PDO::PARAM_STR);
        }
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    public function getPromotionById($promotionId)
    {
        $sql = "
            SELECT
                p.promotion_id,
                p.promotion_name,
                p.promotion_type,
                p.discount_percentage,
                p.fixed_price,
                p.start_date,
                p.end_date
            FROM promotions p
            WHERE p.promotion_id = :promotion_id
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':promotion_id', (int)$promotionId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getProductsByPromotionId($promotionId)
    {
        $columns = $this->getPromotionShoesColumns();
        $promotionIdColumn = $columns['promotion_id'];
        $shoeIdColumn = $columns['shoe_id'];
        $sql = "SELECT {$shoeIdColumn} AS shoe_id FROM promotion_shoes WHERE {$promotionIdColumn} = :promotion_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':promotion_id', (int)$promotionId, PDO::PARAM_INT);
        $stmt->execute();

        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'shoe_id');
    }

    public function removeAllProductsFromPromotion($promotionId)
    {
        $columns = $this->getPromotionShoesColumns();
        $promotionIdColumn = $columns['promotion_id'];
        $sql = "DELETE FROM promotion_shoes WHERE {$promotionIdColumn} = :promotion_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':promotion_id', (int)$promotionId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function assignProductToPromotion($promotionId, $productId)
    {
        $columns = $this->getPromotionShoesColumns();
        $promotionIdColumn = $columns['promotion_id'];
        $shoeIdColumn = $columns['shoe_id'];
        $sql = "
            INSERT IGNORE INTO promotion_shoes ({$promotionIdColumn}, {$shoeIdColumn})
            VALUES (:promotion_id, :shoes_id)
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':promotion_id', (int)$promotionId, PDO::PARAM_INT);
        $stmt->bindValue(':shoes_id', (int)$productId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function createPromotion($name, $startDate, $endDate, $discountPercentage, $fixedPrice, $promotionType)
    {
        $sql = "
            INSERT INTO promotion (
                PromotionName,
                PromotionType,
                DiscountPercentage,
                FixedPrice,
                StartDate,
                EndDate
            ) VALUES (
                :promotion_name,
                :promotion_type,
                :discount_percentage,
                :fixed_price,
                :start_date,
                :end_date
            )
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':promotion_name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':promotion_type', $promotionType, PDO::PARAM_STR);
        $stmt->bindValue(':discount_percentage', $discountPercentage !== '' ? $discountPercentage : null, PDO::PARAM_STR);
        $stmt->bindValue(':fixed_price', $fixedPrice !== '' ? $fixedPrice : null, PDO::PARAM_STR);
        $stmt->bindValue(':start_date', $startDate, PDO::PARAM_STR);
        $stmt->bindValue(':end_date', $endDate, PDO::PARAM_STR);
        $stmt->execute();
    }

    public function updatePromotion($promotionId, $name, $startDate, $endDate, $discountPercentage, $fixedPrice, $promotionType)
    {
        $sql = "
            UPDATE promotion
            SET
                PromotionName      = :promotion_name,
                PromotionType      = :promotion_type,
                DiscountPercentage = :discount_percentage,
                FixedPrice         = :fixed_price,
                StartDate          = :start_date,
                EndDate            = :end_date
            WHERE promotion_id = :promotion_id
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':promotion_id', (int)$promotionId, PDO::PARAM_INT);
        $stmt->bindValue(':promotion_name', $name, PDO::PARAM_STR);
        $stmt->bindValue(':promotion_type', $promotionType, PDO::PARAM_STR);
        $stmt->bindValue(':discount_percentage', $discountPercentage !== '' ? $discountPercentage : null, PDO::PARAM_STR);
        $stmt->bindValue(':fixed_price', $fixedPrice !== '' ? $fixedPrice : null, PDO::PARAM_STR);
        $stmt->bindValue(':start_date', $startDate, PDO::PARAM_STR);
        $stmt->bindValue(':end_date', $endDate, PDO::PARAM_STR);
        $stmt->execute();
    }

    public function deletePromotion($promotionId)
    {
        $newsCols = $this->getNewsPromotionColumns();
        $sql = "DELETE FROM news_promotion WHERE {$newsCols['promotion_id']} = :promotion_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':promotion_id', (int)$promotionId, PDO::PARAM_INT);
        $stmt->execute();

        $columns = $this->getPromotionShoesColumns();
        $promotionIdColumn = $columns['promotion_id'];
        $sql = "DELETE FROM promotion_shoes WHERE {$promotionIdColumn} = :promotion_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':promotion_id', (int)$promotionId, PDO::PARAM_INT);
        $stmt->execute();

        $sql = "DELETE FROM promotions WHERE promotion_id = :promotion_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':promotion_id', (int)$promotionId, PDO::PARAM_INT);
        $stmt->execute();
    }

    private function getNewsPromotionColumns(): array
    {
        if ($this->newsPromotionColumns) {
            return $this->newsPromotionColumns;
        }

        $columns = [
            'news_id' => 'NewsID',
            'promotion_id' => 'PromotionID',
        ];

        try {
            $stmt = $this->db->query("SHOW COLUMNS FROM news_promotion");
            $fields = array_map(static function ($row) {
                return $row['Field'] ?? '';
            }, $stmt->fetchAll(PDO::FETCH_ASSOC));

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

    private function getPromotionShoesColumns(): array
    {
        if ($this->promotionShoesColumns) {
            return $this->promotionShoesColumns;
        }

        $columns = [
            'promotion_id' => 'promotion_id',
            'shoe_id' => 'shoe_id',
        ];

        try {
            $stmt = $this->db->query("SHOW COLUMNS FROM promotion_shoes");
            $fields = array_map(static function ($row) {
                return $row['Field'] ?? '';
            }, $stmt->fetchAll(PDO::FETCH_ASSOC));

            if (in_array('promotion_id', $fields, true)) {
                $columns['promotion_id'] = 'promotion_id';
            } elseif (in_array('PromotionID', $fields, true)) {
                $columns['promotion_id'] = 'PromotionID';
            }

            if (in_array('shoe_id', $fields, true)) {
                $columns['shoe_id'] = 'shoe_id';
            } elseif (in_array('ShoesID', $fields, true)) {
                $columns['shoe_id'] = 'ShoesID';
            }
        } catch (PDOException $e) {
        }

        $this->promotionShoesColumns = $columns;
        return $columns;
    }
}
