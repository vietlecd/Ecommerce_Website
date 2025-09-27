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
            $stmt = $this->pdo->prepare("SELECT ShoesID AS ProductID, Name AS ProductName, Price, Image FROM shoes ORDER BY RAND() LIMIT :limit");
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getAllProducts()
    {
        $stmt = $this->pdo->prepare("SELECT s.ShoesID AS id, s.Name AS name, s.Price AS price, s.Image AS image, s.Description AS description, 
                                           c.Name AS category, s.shoes_size, s.Stock
                                     FROM shoes s
                                     JOIN category c ON s.CategoryID = c.CategoryID");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProducts($keyword = '', $category = '', $limit = 8, $offset = 0)
    {
        $sql = "SELECT s.ShoesID AS id, s.Name AS name, s.Price AS price, s.Image AS image, s.Description AS description, 
                       c.Name AS category, s.shoes_size, s.Stock
                FROM shoes s
                JOIN category c ON s.CategoryID = c.CategoryID
                WHERE 1=1";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND (s.Name LIKE ? OR s.Description LIKE ?)";
            $params[] = '%' . $keyword . '%';
            $params[] = '%' . $keyword . '%';
        }

        if (!empty($category)) {
            $sql .= " AND c.Name = ?";
            $params[] = $category;
        }

        $sql .= " LIMIT " . (int)$limit . " OFFSET " . (int)$offset;

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($products as &$product) {
            $product['promotion'] = $this->getPromotionForProduct($product['id']);
            $product['final_price'] = $this->calculateDiscountedPrice($product, $product['promotion']);
        }
        return $products;
    }

    public function getTotalProducts($keyword = '', $category = '')
    {
        $sql = "SELECT COUNT(*) as total
                FROM shoes s
                JOIN category c ON s.CategoryID = c.CategoryID
                WHERE 1=1";
        $params = [];

        if (!empty($keyword)) {
            $sql .= " AND (s.Name LIKE ? OR s.Description LIKE ?)";
            $params[] = '%' . $keyword . '%';
            $params[] = '%' . $keyword . '%';
        }

        if (!empty($category)) {
            $sql .= " AND c.Name = ?";
            $params[] = $category;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getProductById($id)
    {
        $stmt = $this->pdo->prepare("SELECT s.ShoesID AS id, s.Name AS name, s.Price AS price, s.Image AS image, s.Description AS description, 
                                           c.Name AS category, s.CategoryID AS category_id, s.shoes_size, s.Stock
                                     FROM shoes s
                                     JOIN category c ON s.CategoryID = c.CategoryID
                                     WHERE s.ShoesID = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($product) {
            $product['promotion'] = $this->getPromotionForProduct($id);
            $product['final_price'] = $this->calculateDiscountedPrice($product, $product['promotion']);
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

    // Thêm sản phẩm mới
    public function addProduct($name, $price, $stock, $description, $categoryId, $shoesSize, $image)
    {
        $stmt = $this->pdo->prepare("INSERT INTO shoes (Name, Price, Stock, Description, DateCreate, DateUpdate, CategoryID, shoes_size, Image) 
                                     VALUES (?, ?, ?, ?, CURDATE(), CURDATE(), ?, ?, ?)");
        return $stmt->execute([$name, $price, $stock, $description, $categoryId, $shoesSize, $image]);
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
}
