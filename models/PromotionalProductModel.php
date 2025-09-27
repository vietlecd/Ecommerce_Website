<?php
require_once 'models/ProductModelv2.php';

class PromotionalProductModel
{
    private $productModel;
    private $db;

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
            $product['promotion'] = $this->getPromotionForProduct($product['id']);
            $product['final_price'] = $this->calculateDiscountedPrice($product, $product['promotion']);
        }
        return $products;
    }

    public function getProductById($id)
    {
        $product = $this->productModel->getProductById($id);
        if ($product) {
            $product['promotion'] = $this->getPromotionForProduct($id);
            $product['final_price'] = $this->calculateDiscountedPrice($product, $product['promotion']);
        }
        return $product;
    }

    private function getPromotionForProduct($product_id)
    {
        $current_date = date('Y-m-d H:i:s');
        $query = "SELECT p.* 
                  FROM promotions p 
                  JOIN promotion_shoes ps ON p.promotion_id = ps.promotion_id 
                  WHERE ps.shoe_id = :shoe_id 
                  AND p.start_date <= :start_date 
                  AND p.end_date >= :end_date";

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
            if ($promotion['discount_percentage']) {
                return $product['price'] * (1 - $promotion['discount_percentage'] / 100);
            } elseif ($promotion['fixed_price']) {
                return $promotion['fixed_price'];
            }
        }
        return $product['price'];
    }

    public function getAllPromotions($limit = 10, $offset = 0, $keyword = '', $sort = 'ASC')
    {
        $query = "SELECT * FROM promotions WHERE 1=1";
        $params = [];

        if (!empty($keyword)) {
            $query .= " AND promotion_name LIKE :keyword";
            $params[':keyword'] = '%' . $keyword . '%';
        }

        $query .= " ORDER BY promotion_id " . ($sort === 'DESC' ? 'DESC' : 'ASC');
        $query .= " LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($query);
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
        $query = "SELECT COUNT(*) FROM promotions WHERE 1=1";
        $params = [];

        if (!empty($keyword)) {
            $query .= " AND promotion_name LIKE :keyword";
            $params[':keyword'] = '%' . $keyword . '%';
        }

        $stmt = $this->db->prepare($query);
        if (!empty($keyword)) {
            $stmt->bindValue(':keyword', $params[':keyword'], PDO::PARAM_STR);
        }
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getPromotionById($promotionId)
    {
        $query = "SELECT * FROM promotions WHERE promotion_id = :promotion_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':promotion_id', (int)$promotionId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getProductsByPromotionId($promotionId)
    {
        $query = "SELECT shoe_id FROM promotion_shoes WHERE promotion_id = :promotion_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':promotion_id', (int)$promotionId, PDO::PARAM_INT);
        $stmt->execute();
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'shoe_id');
    }

    public function removeAllProductsFromPromotion($promotionId)
    {
        $query = "DELETE FROM promotion_shoes WHERE promotion_id = :promotion_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':promotion_id', (int)$promotionId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function assignProductToPromotion($promotionId, $productId)
    {
        $this->removeProductFromAllPromotions($productId);

        $query = "INSERT INTO promotion_shoes (promotion_id, shoe_id) VALUES (:promotion_id, :shoe_id)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':promotion_id', (int)$promotionId, PDO::PARAM_INT);
        $stmt->bindValue(':shoe_id', (int)$productId, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function removeProductFromAllPromotions($productId)
    {
        $query = "DELETE FROM promotion_shoes WHERE shoe_id = :shoe_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':shoe_id', (int)$productId, PDO::PARAM_INT);
        $stmt->execute();
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
        $query = "UPDATE news SET promotion_id = NULL WHERE promotion_id = :promotion_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':promotion_id', (int)$promotionId, PDO::PARAM_INT);
        $stmt->execute();

        $query = "DELETE FROM promotion_shoes WHERE promotion_id = :promotion_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':promotion_id', (int)$promotionId, PDO::PARAM_INT);
        $stmt->execute();

        $query = "DELETE FROM promotions WHERE promotion_id = :promotion_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':promotion_id', (int)$promotionId, PDO::PARAM_INT);
        $stmt->execute();
    }
}
