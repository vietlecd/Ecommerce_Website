<?php
class ProductModel
{
    private $pdo;

    public function __construct()
    {
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
            $stmt = $this->pdo->prepare("SELECT s.ShoesID AS id, s.Name AS name, s.Price AS price, s.Image AS image, s.Stock, s.Description AS description, c.Name AS category, s.CategoryID AS category_id
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

        $needsSizeJoin = ($minSize !== null && is_numeric($minSize)) || ($maxSize !== null && is_numeric($maxSize));
        if ($needsSizeJoin) {
            $sql .= " JOIN shoe_sizes sz ON sz.ShoeID = s.ShoesID";
        }

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

        if ($needsSizeJoin) {
            if ($minSize !== null && is_numeric($minSize)) {
                $sql .= " AND sz.Size >= ?";
                $params[] = (float)$minSize;
            }

            if ($maxSize !== null && is_numeric($maxSize)) {
                $sql .= " AND sz.Size <= ?";
                $params[] = (float)$maxSize;
            }
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
            $products = array_filter($products, function ($product) use ($minPrice, $maxPrice) {
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
                       sh.Description AS description, sh.Stock, sh.CategoryID AS category_id,
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
                       sh.Description AS description, sh.Stock, sh.CategoryID AS category_id,
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

    public function getTotalProductsCount(): int
    {
        try {
            $stmt = $this->pdo->query("SELECT COUNT(*) AS total FROM shoes");
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)($row['total'] ?? 0);
        } catch (PDOException $e) {
            return 0;
        }
    }

    public function getAvailableSizes()
    {
        $stmt = $this->pdo->prepare("SELECT DISTINCT CAST(Size AS DECIMAL(10,2)) AS size FROM shoe_sizes ORDER BY size ASC");
        $stmt->execute();
        $sizes = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return array_map('floatval', $sizes);
    }

    public function getProductById($id)
    {
        $stmt = $this->pdo->prepare("SELECT s.ShoesID AS id, s.Name AS name, s.Price AS price, s.Image AS image, s.Description AS description, 
                                           c.Name AS category, s.CategoryID AS category_id, s.Stock, 
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

        $query = "
        SELECT
            p.promotion_id        AS promotion_id,
            p.promotion_type      AS promotion_type,
            p.promotion_name      AS promotion_name,
            p.discount_percentage AS discount_percentage,
            p.fixed_price         AS fixed_price,
            p.start_date          AS start_date,
            p.end_date            AS end_date
        FROM promotions p
        JOIN promotion_shoes ps 
            ON p.promotion_id = ps.promotion_id 
        WHERE ps.shoe_id   = :shoe_id 
          AND p.start_date <= :start_date 
          AND p.end_date   >= :end_date 
        ORDER BY 
            COALESCE(p.discount_percentage, 0) DESC,
            COALESCE(p.fixed_price, 999999) ASC 
        LIMIT 1
    ";

        $stmt = $this->pdo->prepare($query);
        $stmt->bindValue(':shoe_id', (int)$shoe_id, PDO::PARAM_INT);
        $stmt->bindValue(':start_date', $current_date, PDO::PARAM_STR);
        $stmt->bindValue(':end_date', $current_date, PDO::PARAM_STR);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
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
                           c.Name AS category, s.Stock, s.CategoryID AS category_id
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
                       s.Stock, s.DateCreate
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
                       s.Stock, MAX(cm.Date) AS latest_comment_date
                FROM shoes s
                JOIN comment cm ON cm.ShoesID = s.ShoesID
                JOIN category c ON s.CategoryID = c.CategoryID
                GROUP BY s.ShoesID, s.Name, s.Price, s.Image, s.Description, c.Name, s.CategoryID, s.Stock
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
                       s.Stock
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
        if (empty($products)) {
            return [];
        }

        $productIds = array_column($products, 'id');
        $sizeMap = $this->fetchSizesForProductIds($productIds);

        foreach ($products as &$product) {
            $productSizes = $sizeMap[$product['id']] ?? [];
            $product['sizes'] = $productSizes;
            $product['size_summary'] = $this->formatSizeSummary($productSizes);
            $product['shoes_size'] = $product['size_summary'];
            $product['promotion'] = $this->getPromotionForProduct($product['id']);
            $product['sale'] = $this->getSaleForProduct($product['id']);
            $product['final_price'] = $this->calculateDiscountedPrice($product, $product['promotion'], $product['sale']);
        }
        unset($product);
        return $products;
    }

    private function fetchSizesForProductIds(array $productIds): array
    {
        if (empty($productIds)) {
            return [];
        }

        $placeholders = implode(',', array_fill(0, count($productIds), '?'));
        $stmt = $this->pdo->prepare("SELECT SizeID, ShoeID, Size, Quantity FROM shoe_sizes WHERE ShoeID IN ($placeholders) ORDER BY Size ASC");
        $stmt->execute($productIds);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $result = [];
        foreach ($rows as $row) {
            $result[$row['ShoeID']][] = [
                'id' => (int)$row['SizeID'],
                'size' => (float)$row['Size'],
                'label' => $this->formatSizeValue($row['Size']),
                'quantity' => (int)$row['Quantity'],
            ];
        }

        return $result;
    }

    private function formatSizeSummary(array $sizes): string
    {
        if (empty($sizes)) {
            return '';
        }

        $labels = array_map(function ($sizeRow) {
            return $this->formatSizeValue($sizeRow['size']);
        }, $sizes);

        return implode(', ', $labels);
    }

    private function formatSizeValue($value): string
    {
        return rtrim(rtrim(number_format((float)$value, 2, '.', ''), '0'), '.');
    }

    public function syncProductSizes(int $shoeId, array $sizes): bool
    {
        $prepared = [];
        foreach ($sizes as $row) {
            if (!isset($row['size'])) {
                continue;
            }

            $sizeValue = is_numeric($row['size']) ? (float)$row['size'] : null;
            if ($sizeValue === null) {
                continue;
            }

            $quantityValue = isset($row['quantity']) ? (int)$row['quantity'] : 0;
            if ($quantityValue < 0) {
                $quantityValue = 0;
            }

            $normalizedSize = (float)number_format($sizeValue, 2, '.', '');
            if (!isset($prepared[$normalizedSize])) {
                $prepared[$normalizedSize] = ['size' => $normalizedSize, 'quantity' => 0];
            }
            $prepared[$normalizedSize]['quantity'] += $quantityValue;
        }

        $normalized = array_values($prepared);

        try {
            $this->pdo->beginTransaction();
            $deleteStmt = $this->pdo->prepare("DELETE FROM shoe_sizes WHERE ShoeID = ?");
            $deleteStmt->execute([$shoeId]);

            if (!empty($normalized)) {
                $insertStmt = $this->pdo->prepare("INSERT INTO shoe_sizes (ShoeID, Size, Quantity) VALUES (?, ?, ?)");
                foreach ($normalized as $row) {
                    $insertStmt->execute([$shoeId, $row['size'], $row['quantity']]);
                }
            }

            $totalStock = array_sum(array_column($normalized, 'quantity'));
            $baseSize = $normalized[0]['size'] ?? null;
            $updateStmt = $this->pdo->prepare("UPDATE shoes SET Stock = ?, shoes_size = ? WHERE ShoesID = ?");
            $updateStmt->execute([$totalStock, $baseSize, $shoeId]);

            $this->pdo->commit();
            return true;
        } catch (PDOException $e) {
            $this->pdo->rollBack();
            error_log('Failed to sync product sizes: ' . $e->getMessage());
            return false;
        }
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

    /**
     * Trừ tồn kho khi place order dựa trên shoe_sizes.
     *
     * $cartItems: mỗi phần tử [
     *   'product_id' => ShoesID,
     *   'size'       => size đã chọn (float/string),
     *   'quantity'   => số lượng đặt
     * ]
     *
     * Trả về true nếu trừ tồn thành công, false nếu thiếu hàng hoặc lỗi.
     */
    public function decrementStockForCartItems(array $cartItems): bool
    {
        if (empty($cartItems)) {
            return true;
        }

        // Gom nhóm theo (shoeId, size) để tránh UPDATE nhiều lần
        $grouped = [];
        foreach ($cartItems as $item) {
            if (!isset($item['product_id'], $item['size'], $item['quantity'])) {
                continue;
            }
            $shoeId = (int)$item['product_id'];
            $size   = (float)number_format((float)$item['size'], 2, '.', '');
            $qty    = max(1, (int)$item['quantity']);

            $key = $shoeId . '|' . $size;
            if (!isset($grouped[$key])) {
                $grouped[$key] = [
                    'shoe_id' => $shoeId,
                    'size'    => $size,
                    'qty'     => 0,
                ];
            }
            $grouped[$key]['qty'] += $qty;
        }

        if (empty($grouped)) {
            return true;
        }

        try {
            $this->pdo->beginTransaction();

            $affectedShoeIds = [];

            // Kiểm tra và trừ tồn trên shoe_sizes
            foreach ($grouped as $row) {
                $shoeId = $row['shoe_id'];
                $size   = $row['size'];
                $qty    = $row['qty'];

                // Khóa dòng size tương ứng
                $selectStmt = $this->pdo->prepare("
                    SELECT Quantity 
                    FROM shoe_sizes 
                    WHERE ShoeID = ? AND Size = ? 
                    FOR UPDATE
                ");
                $selectStmt->execute([$shoeId, $size]);
                $current = $selectStmt->fetch(PDO::FETCH_ASSOC);

                if (!$current) {
                    throw new RuntimeException("Size $size for shoe $shoeId not found");
                }

                $currentQty = (int)$current['Quantity'];
                if ($currentQty < $qty) {
                    throw new RuntimeException("Not enough stock for shoe $shoeId size $size");
                }

                $newQty = $currentQty - $qty;
                $updateSizeStmt = $this->pdo->prepare("
                    UPDATE shoe_sizes 
                    SET Quantity = ? 
                    WHERE ShoeID = ? AND Size = ?
                ");
                $updateSizeStmt->execute([$newQty, $shoeId, $size]);

                $affectedShoeIds[$shoeId] = true;
            }

            // Cập nhật lại tổng Stock cho mỗi ShoesID
            $sumStmt = $this->pdo->prepare("
                SELECT COALESCE(SUM(Quantity),0) AS total 
                FROM shoe_sizes 
                WHERE ShoeID = ?
            ");
            $updateShoeStmt = $this->pdo->prepare("
                UPDATE shoes 
                SET Stock = ? 
                WHERE ShoesID = ?
            ");

            foreach (array_keys($affectedShoeIds) as $shoeId) {
                $sumStmt->execute([$shoeId]);
                $row = $sumStmt->fetch(PDO::FETCH_ASSOC);
                $totalStock = (int)($row['total'] ?? 0);
                $updateShoeStmt->execute([$totalStock, $shoeId]);
            }

            $this->pdo->commit();
            return true;
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            error_log('Failed to decrement stock for cart: ' . $e->getMessage());
            return false;
        }
    }
}
