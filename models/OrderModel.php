<?php
class OrderModel
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

    // Lấy danh sách đơn hàng
    public function getOrders()
    {
        $stmt = $this->pdo->query("
            SELECT o.OrderID, o.Total_price, o.Quantity, o.Date, o.Status, m.Name AS customer_name, m.Email
            FROM `order` o
            JOIN member m ON o.MemberID = m.MemberID
            ORDER BY o.Date DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Lấy chi tiết đơn hàng theo ID
    public function getOrderById($orderId)
    {
        $stmt = $this->pdo->prepare("
            SELECT o.*, 
                   COALESCE(m.Name, o.guest_name) AS customer_name,
                   COALESCE(m.Email, o.guest_email) AS email,
                   m.Phone
            FROM `order` o
            LEFT JOIN member m ON o.MemberID = m.MemberID
            WHERE o.OrderID = ?
        ");
        $stmt->execute([$orderId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($order) {
            $stmt = $this->pdo->prepare("
                SELECT os.ShoesID, s.Name AS product_name, s.Price, os.OrderID
                FROM order_shoes os
                JOIN shoes s ON os.ShoesID = s.ShoesID
                WHERE os.OrderID = ?
            ");
            $stmt->execute([$orderId]);
            $order['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return $order;
    }

    // Lấy đơn hàng theo tracking_id
    public function getOrderByTrackingId($trackingId)
    {
        $stmt = $this->pdo->prepare("
            SELECT o.*, 
                   COALESCE(m.Name, o.guest_name) AS customer_name,
                   COALESCE(m.Email, o.guest_email) AS email,
                   m.Phone
            FROM `order` o
            LEFT JOIN member m ON o.MemberID = m.MemberID
            WHERE o.tracking_id = ?
        ");
        $stmt->execute([$trackingId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($order) {
            $stmt = $this->pdo->prepare("
                SELECT os.ShoesID, s.Name AS product_name, s.Price, os.OrderID
                FROM order_shoes os
                JOIN shoes s ON os.ShoesID = s.ShoesID
                WHERE os.OrderID = ?
            ");
            $stmt->execute([$order['OrderID']]);
            $order['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return $order;
    }

    // Tạo tracking_id ngẫu nhiên
    private function generateTrackingId()
    {
        do {
            $trackingId = strtoupper(substr(md5(uniqid(rand(), true)), 0, 12));
            $stmt = $this->pdo->prepare("SELECT OrderID FROM `order` WHERE tracking_id = ?");
            $stmt->execute([$trackingId]);
        } while ($stmt->fetch());
        return $trackingId;
    }

    // Thêm đơn hàng guest
    public function addGuestOrder($guestInfo, $totalPrice, $quantity, $paymentMethod = null)
    {
        try {
            $trackingId = $this->generateTrackingId();
            $stmt = $this->pdo->prepare("
                INSERT INTO `order` 
                (tracking_id, MemberID, guest_name, guest_email, guest_address, guest_city, guest_zip, Total_price, Quantity, payment_method, Date, Status) 
                VALUES (?, NULL, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), 'Pending')
            ");
            $stmt->execute([
                $trackingId,
                $guestInfo['name'],
                $guestInfo['email'],
                $guestInfo['address'],
                $guestInfo['city'],
                $guestInfo['zip'],
                $totalPrice,
                $quantity,
                $paymentMethod
            ]);
            $orderId = $this->pdo->lastInsertId();
            return ['order_id' => $orderId, 'tracking_id' => $trackingId];
        } catch (PDOException $e) {
            $errorMessage = $e->getMessage();
            error_log("OrderModel::addGuestOrder error: " . $errorMessage);
            if (strpos($errorMessage, 'Unknown column') !== false && strpos($errorMessage, 'payment_method') !== false) {
                error_log("Payment method column missing. Please run migration: 004_add_payment_method.sql");
            }
            return false;
        }
    }

    // Cập nhật trạng thái đơn hàng
    public function updateOrderStatus($orderId, $status)
    {
        $validStatuses = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];
        if (!in_array($status, $validStatuses)) {
            return false;
        }

        $stmt = $this->pdo->prepare("UPDATE `order` SET Status = ? WHERE OrderID = ?");
        return $stmt->execute([$status, $orderId]);
    }

    // Thêm đơn hàng mới (cho member)
    public function addOrder($memberId, $totalPrice, $quantity, $paymentMethod = null)
    {
        try {
            $trackingId = $this->generateTrackingId();
            $stmt = $this->pdo->prepare("
                INSERT INTO `order` (tracking_id, MemberID, Total_price, Quantity, payment_method, Date, Status) 
                VALUES (?, ?, ?, ?, ?, NOW(), 'Pending')
            ");
            $stmt->execute([$trackingId, $memberId, $totalPrice, $quantity, $paymentMethod]);
            $orderId = $this->pdo->lastInsertId();
            return ['order_id' => $orderId, 'tracking_id' => $trackingId];
        } catch (PDOException $e) {
            $errorMessage = $e->getMessage();
            error_log("OrderModel::addOrder error: " . $errorMessage);
            if (strpos($errorMessage, 'Unknown column') !== false && strpos($errorMessage, 'payment_method') !== false) {
                error_log("Payment method column missing. Please run migration: 004_add_payment_method.sql");
            }
            return false;
        }
    }

    // Thêm chi tiết đơn hàng vào bảng order_shoes
    public function addOrderShoes($orderId, $shoesId)
    {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO order_shoes (OrderID, ShoesID) VALUES (?, ?)");
            return $stmt->execute([$orderId, $shoesId]);
        } catch (PDOException $e) {
            return false;
        }
    }
}
