<?php
class ProductModel
{
    private $pdo;

    public function __construct()
    {
        // Use environment variables for Docker or fallback to defaults
        $host = $_ENV['DB_HOST'] ?? 'mysql';
        $dbname = $_ENV['DB_NAME'] ?? 'shoe';
        $username = $_ENV['DB_USER'] ?? 'shoes_user';
        $password = $_ENV['DB_PASS'] ?? 'shoes_pass';

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    // Lấy danh sách sản phẩm ngẫu nhiên
    public function getRandomProducts($limit)
    {
        try {
            $stmt = $this->pdo->prepare("SELECT s.ShoesID AS id, s.Name AS name, s.Price AS price, s.Image AS image, s.Stock, s.Description AS description, s.shoes_size, c.Name AS category, s.CategoryID AS category_id
                                         FROM shoes s
                                         JOIN category c ON s.CategoryID = c.CategoryID
                                         ORDER BY RAND()
                                         LIMIT :limit");
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $this->enrichProducts($products);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getAllProducts()
    {
        $stmt = $this->pdo->prepare("SELECT s.ShoesID AS id, s.Name AS name, s.Price AS price, s.Image AS image, s.Description AS description, 
                                           c.Name AS category, s.Stock
                                     FROM shoes s
                                     JOIN category c ON s.CategoryID = c.CategoryID");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProducts($keyword = '', $category = '', $limit = 8, $offset = 0, $minPrice = null, $maxPrice = null, $minSize = null, $maxSize = null, $saleOnly = false)
    {
        $sql = "SELECT DISTINCT s.ShoesID AS id, s.Name AS name, s.Price AS price, s.Image AS image, s.Description AS description, 
                       c.Name AS category, s.Stock, s.CategoryID AS category_id
                FROM shoes s
                JOIN category c ON s.CategoryID = c.CategoryID";
        
        if ($saleOnly) {
            $sql .= " LEFT JOIN sales sl ON sl.ShoesID = s.ShoesID AND (sl.ExpiresAt IS NULL OR sl.ExpiresAt >= NOW())";
        }
        
        $sql .= " WHERE 1=1";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND (s.Name LIKE ? OR s.Description LIKE ?)";
            $params[] = '%' . $keyword . '%';
            $params[] = '%' . $keyword . '%';
        }

        if (!empty($category)) {
            if (is_numeric($category)) {
                $sql .= " AND s.CategoryID = ?";
                $params[] = (int)$category;
            } else {
                $sql .= " AND c.Name = ?";
                $params[] = $category;
            }
        }

        if ($minSize !== null && is_numeric($minSize)) {
            $sql .= " AND CAST(s.shoes_size AS DECIMAL(10,2)) >= ?";
            $params[] = (float)$minSize;
        }

        if ($maxSize !== null && is_numeric($maxSize)) {
            $sql .= " AND CAST(s.shoes_size AS DECIMAL(10,2)) <= ?";
            $params[] = (float)$maxSize;
        }

        if ($saleOnly) {
            $sql .= " AND sl.ShoesID IS NOT NULL";
        }

        if ($limit > 0) {
            $sql .= " LIMIT " . (int)$limit . " OFFSET " . (int)$offset;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $products = $this->enrichProducts($products);

        if ($minPrice !== null || $maxPrice !== null) {
            $products = array_filter($products, function($product) use ($minPrice, $maxPrice) {
                $finalPrice = $product['final_price'];
                if ($minPrice !== null && $finalPrice < $minPrice) {
                    return false;
                }
                if ($maxPrice !== null && $finalPrice > $maxPrice) {
                    return false;
                }
                return true;
            });
            $products = array_values($products);
        }
        
        return $products;
    }

    public function getHighDiscountSales($minDiscount = 50, $limit = 10)
    {
        $sql = "SELECT sh.ShoesID AS id, sh.Name AS name, sh.Price AS price, sh.Image AS image,
                       sh.Description AS description, sh.shoes_size, sh.Stock, sh.CategoryID AS category_id,
                       c.Name AS category
                FROM sales s
                JOIN shoes sh ON s.ShoesID = sh.ShoesID
                JOIN category c ON sh.CategoryID = c.CategoryID
                WHERE s.DiscountPercent >= :minDiscount
                  AND (s.ExpiresAt IS NULL OR s.ExpiresAt >= NOW())
                ORDER BY s.DiscountPercent DESC, s.ExpiresAt ASC
                LIMIT :limit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':minDiscount', (float)$minDiscount, PDO::PARAM_STR);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $this->enrichProducts($products);
    }

    public function getSalesEndingSoon($daysAhead = 7, $limit = 10)
    {
        $endDate = (new DateTime())->modify('+' . (int)$daysAhead . ' days')->format('Y-m-d H:i:s');
        $sql = "SELECT sh.ShoesID AS id, sh.Name AS name, sh.Price AS price, sh.Image AS image,
                       sh.Description AS description, sh.shoes_size, sh.Stock, sh.CategoryID AS category_id,
                       c.Name AS category
                FROM sales s
                JOIN shoes sh ON s.ShoesID = sh.ShoesID
                JOIN category c ON sh.CategoryID = c.CategoryID
                WHERE s.ExpiresAt IS NOT NULL
                  AND s.ExpiresAt BETWEEN NOW() AND :endDate
                ORDER BY s.ExpiresAt ASC, s.DiscountPercent DESC
                LIMIT :limit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':endDate', $endDate);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $this->enrichProducts($products);
    }

    public function getTotalProducts($keyword = '', $category = '', $minPrice = null, $maxPrice = null, $minSize = null, $maxSize = null, $saleOnly = false)
    {
        $products = $this->getProducts($keyword, $category, 0, 0, $minPrice, $maxPrice, $minSize, $maxSize, $saleOnly);
        return count($products);
    }

    public function getAvailableSizes()
    {
        $stmt = $this->pdo->prepare("SELECT DISTINCT CAST(shoes_size AS DECIMAL(10,2)) AS size FROM shoes WHERE shoes_size IS NOT NULL AND shoes_size != '' ORDER BY size ASC");
        $stmt->execute();
        $sizes = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return array_map('floatval', $sizes);
    }

    public function getProductById($id)
    {
        $stmt = $this->pdo->prepare("SELECT s.ShoesID AS id, s.Name AS name, s.Price AS price, s.Image AS image, s.Description AS description, 
                                           c.Name AS category, s.CategoryID AS category_id, s.shoes_size, s.Stock, 
                                           s.DateCreate, s.DateUpdate
                                     FROM shoes s
                                     JOIN category c ON s.CategoryID = c.CategoryID
                                     WHERE s.ShoesID = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($product) {
            $sizes = $this->fetchSizesForProductIds([$id]);
            $product['sizes'] = $sizes[$id] ?? [];
            $product['size_summary'] = $this->formatSizeSummary($product['sizes']);
            $product['shoes_size'] = $product['size_summary'];
            $product['promotion'] = $this->getPromotionForProduct($id);
            $product['sale'] = $this->getSaleForProduct($id);
            $product['final_price'] = $this->calculateDiscountedPrice($product, $product['promotion'], $product['sale']);
        }
        return $product;
    }

    private function getPromotionForProduct($shoe_id)
    {
        $current_date = date('Y-m-d H:i:s');
        $query = "SELECT p.* 
                  FROM promotions p 
                  JOIN promotion_shoes ps ON p.promotion_id = ps.promotion_id 
                  WHERE ps.shoe_id = :shoe_id 
                  AND p.start_date <= :current_date 
                  AND p.end_date >= :current_date 
                  ORDER BY COALESCE(p.discount_percentage, 0) DESC, COALESCE(p.fixed_price, 999999) ASC 
                  LIMIT 1";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':shoe_id', (int)$shoe_id, PDO::PARAM_INT);
        $stmt->bindValue(':current_date', $current_date, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function getSaleForProduct($shoe_id)
    {
        $currentDate = date('Y-m-d H:i:s');
        $query = "SELECT *
                  FROM sales
                  WHERE ShoesID = :shoe_id
                  AND (ExpiresAt IS NULL OR ExpiresAt >= :current_date)
                  ORDER BY DiscountPercent DESC
                  LIMIT 1";
        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':shoe_id', (int)$shoe_id, PDO::PARAM_INT);
        $stmt->bindValue(':current_date', $currentDate, PDO::PARAM_STR);
        $stmt->execute();
        $sale = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$sale) {
            $fallbackQuery = "SELECT *
                              FROM sales
                              WHERE ShoesID = :shoe_id
                              ORDER BY ExpiresAt DESC, DiscountPercent DESC
                              LIMIT 1";
            $fallbackStmt = $this->pdo->prepare($fallbackQuery);
            $fallbackStmt->bindValue(':shoe_id', (int)$shoe_id, PDO::PARAM_INT);
            $fallbackStmt->execute();
            $sale = $fallbackStmt->fetch(PDO::FETCH_ASSOC);
        }

        return $sale ?: null;
    }

    private function calculateDiscountedPrice($product, $promotion, $sale = null)
    {
        $basePrice = (float)$product['price'];
        $finalPrice = $basePrice;

        if (!empty($promotion)) {
            if ($promotion['discount_percentage']) {
                $finalPrice = min($finalPrice, $basePrice * (1 - $promotion['discount_percentage'] / 100));
            } elseif ($promotion['fixed_price']) {
                $finalPrice = min($finalPrice, (float)$promotion['fixed_price']);
            }
        }

        if (!empty($sale) && isset($sale['DiscountPercent'])) {
            $salePrice = $basePrice * (1 - ((float)$sale['DiscountPercent'] / 100));
            $finalPrice = min($finalPrice, $salePrice);
        }

        return $finalPrice;
    }

    // Thêm sản phẩm mới
    public function addProduct($name, $price, $stock, $description, $categoryId, $shoesSize, $image)
    {
        $stmt = $this->pdo->prepare("INSERT INTO shoes (Name, Price, Stock, Description, DateCreate, DateUpdate, CategoryID, shoes_size, Image) 
                                     VALUES (?, ?, ?, ?, CURDATE(), CURDATE(), ?, ?, ?)");
        if ($stmt->execute([$name, $price, $stock, $description, $categoryId, $shoesSize, $image])) {
            return (int)$this->pdo->lastInsertId();
        }
        return false;
    }

    // Cập nhật sản phẩm
    public function updateProduct($id, $name, $price, $stock, $description, $categoryId, $shoesSize, $image)
    {
        $stmt = $this->pdo->prepare("UPDATE shoes 
                                     SET Name = ?, Price = ?, Stock = ?, Description = ?, DateUpdate = CURDATE(), CategoryID = ?, shoes_size = ?, Image = ? 
                                     WHERE ShoesID = ?");
        return $stmt->execute([$name, $price, $stock, $description, $categoryId, $shoesSize, $image, $id]);
    }

    // Xóa sản phẩm
    public function deleteProduct($id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM shoes WHERE ShoesID = ?");
        return $stmt->execute([$id]);
    }

    // Lấy danh sách danh mục
    public function getCategories()
    {
        $stmt = $this->pdo->prepare("SELECT CategoryID AS id, Name AS name FROM category");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy sản phẩm cùng category (loại trừ sản phẩm hiện tại)
    public function getRelatedProducts($categoryId, $excludeProductId, $limit = 4)
    {
        try {
            $sql = "SELECT s.ShoesID AS id, s.Name AS name, s.Price AS price, s.Image AS image, s.Description AS description, 
                           c.Name AS category, s.shoes_size, s.Stock, s.CategoryID AS category_id
                    FROM shoes s
                    JOIN category c ON s.CategoryID = c.CategoryID
                    WHERE s.CategoryID = ? AND s.ShoesID != ?
                    ORDER BY RAND()
                    LIMIT ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(1, (int)$categoryId, PDO::PARAM_INT);
            $stmt->bindValue(2, (int)$excludeProductId, PDO::PARAM_INT);
            $stmt->bindValue(3, (int)$limit, PDO::PARAM_INT);
            $stmt->execute();
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $this->enrichProducts($products);
        } catch (PDOException $e) {
            error_log("Error getting related products: " . $e->getMessage());
            return [];
        }
    }

    public function getTopSellers($limit = 4)
    {
        $sql = "SELECT s.ShoesID AS id, s.Name AS name, s.Price AS price, s.Image AS image,
                       s.Description AS description, s.Stock
                FROM shoes s
                ORDER BY s.ShoesID ASC
                LIMIT :limit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $this->enrichProducts($products);
    }

    public function getTopPricedProducts($limit = 4)
    {
        $sql = "SELECT s.ShoesID AS id, s.Name AS name, s.Price AS price, s.Image AS image,
                       s.Description AS description, s.Stock
                FROM shoes s
                ORDER BY s.Price DESC
                LIMIT :limit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $this->enrichProducts($products);
    }

    public function getLatestProducts($limit = 10)
    {
        $sql = "SELECT s.ShoesID AS id, s.Name AS name, s.Price AS price, s.Image AS image,
                       s.Description AS description, c.Name AS category, s.CategoryID AS category_id,
                       s.Stock, s.shoes_size, s.DateCreate
                FROM shoes s
                JOIN category c ON s.CategoryID = c.CategoryID
                ORDER BY s.DateCreate DESC, s.ShoesID DESC
                LIMIT :limit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $this->enrichProducts($products);
    }

    public function getRecentlyReviewedProducts($limit = 10)
    {
        $sql = "SELECT s.ShoesID AS id, s.Name AS name, s.Price AS price, s.Image AS image,
                       s.Description AS description, c.Name AS category, s.CategoryID AS category_id,
                       s.Stock, s.shoes_size, MAX(cm.Date) AS latest_comment_date
                FROM shoes s
                JOIN comment cm ON cm.ShoesID = s.ShoesID
                JOIN category c ON s.CategoryID = c.CategoryID
                GROUP BY s.ShoesID, s.Name, s.Price, s.Image, s.Description, c.Name, s.CategoryID, s.Stock, s.shoes_size
                ORDER BY latest_comment_date DESC
                LIMIT :limit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $this->enrichProducts($products);
    }

    public function getLowestPriceProducts($limit = 10)
    {
        $sql = "SELECT s.ShoesID AS id, s.Name AS name, s.Price AS price, s.Image AS image,
                       s.Description AS description, c.Name AS category, s.CategoryID AS category_id,
                       s.Stock, s.shoes_size
                FROM shoes s
                JOIN category c ON s.CategoryID = c.CategoryID
                ORDER BY s.Price ASC, s.ShoesID ASC
                LIMIT :limit";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $this->enrichProducts($products);
    }

    private function enrichProducts(array $products)
    {
        foreach ($products as &$product) {
            $product['promotion'] = $this->getPromotionForProduct($product['id']);
            $product['sale'] = $this->getSaleForProduct($product['id']);
            $product['final_price'] = $this->calculateDiscountedPrice($product, $product['promotion'], $product['sale']);
        }
        unset($product);
        return $products;
    }

    public function getCategoryStats(array $categoryIds)
    {
        if (empty($categoryIds)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($categoryIds), '?'));
        $sql = "
            SELECT s.CategoryID AS category_id,
                   COUNT(*) AS total_products,
                   SUM(CASE WHEN sl.ShoesID IS NOT NULL THEN 1 ELSE 0 END) AS sale_products,
                   SUM(CASE WHEN s.Stock BETWEEN 1 AND 9 THEN 1 ELSE 0 END) AS low_stock_products,
                   SUM(CASE WHEN s.Stock <= 0 THEN 1 ELSE 0 END) AS out_of_stock_products,
                   SUM(CASE WHEN s.DateCreate >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) THEN 1 ELSE 0 END) AS new_products_week,
                   SUM(CASE WHEN od.ShoesID IS NOT NULL THEN 1 ELSE 0 END) AS purchased_today
            FROM shoes s
            LEFT JOIN (
                SELECT ShoesID
                FROM sales
                WHERE (ExpiresAt IS NULL OR ExpiresAt >= NOW())
                GROUP BY ShoesID
            ) sl ON sl.ShoesID = s.ShoesID
            LEFT JOIN (
                SELECT os.ShoesID
                FROM order_shoes os
                JOIN `order` o ON o.OrderID = os.OrderID
                WHERE DATE(o.Date) = CURDATE()
                GROUP BY os.ShoesID
            ) od ON od.ShoesID = s.ShoesID
            WHERE s.CategoryID IN ($placeholders)
            GROUP BY s.CategoryID
        ";

        $stmt = $this->pdo->prepare($sql);
        foreach ($categoryIds as $index => $categoryId) {
            $stmt->bindValue($index + 1, (int)$categoryId, PDO::PARAM_INT);
        }
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $stats = [];
        foreach ($rows as $row) {
            $stats[$row['category_id']] = [
                'total_products' => (int)$row['total_products'],
                'sale_products' => (int)$row['sale_products'],
                'low_stock_products' => (int)$row['low_stock_products'],
                'out_of_stock_products' => (int)$row['out_of_stock_products'],
                'new_products_week' => (int)$row['new_products_week'],
                'purchased_today' => (int)$row['purchased_today'],
            ];
        }

        return $stats;
    }
}

