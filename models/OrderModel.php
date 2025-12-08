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
    public function getOrders(int $limit = 0, int $offset = 0)
    {
        $sql = "
            SELECT o.OrderID, o.Total_price, o.Quantity, o.Date, o.Status, m.Name AS customer_name, m.Email
            FROM `order` o
            LEFT JOIN member m ON o.MemberID = m.MemberID
            ORDER BY o.Date DESC
        ";

        if ($limit > 0) {
            $sql .= " LIMIT :limit OFFSET :offset";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', max(0, $offset), PDO::PARAM_INT);
            $stmt->execute();
        } else {
            $stmt = $this->pdo->query($sql);
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTotalOrders(): int
    {
        $stmt = $this->pdo->query("SELECT COUNT(*) AS total FROM `order`");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($row['total'] ?? 0);
    }

    // Lấy chi tiết đơn hàng theo ID
    public function getOrderById($orderId)
    {
        $stmt = $this->pdo->prepare("
            SELECT o.*, m.Name AS customer_name, m.Email, m.Phone
            FROM `order` o
            LEFT JOIN member m ON o.MemberID = m.MemberID
            WHERE o.OrderID = ?
        ");
        $stmt->execute([$orderId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($order) {
            $stmt = $this->pdo->prepare("
                SELECT os.ShoesID, s.Name AS product_name, s.Price, s.Image AS product_image, os.OrderID
                FROM order_shoes os
                JOIN shoes s ON os.ShoesID = s.ShoesID
                WHERE os.OrderID = ?
            ");
            $stmt->execute([$orderId]);
            $order['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return $order;
    }

    public function getOrderSummaryById($orderId)
    {
        $order = $this->getOrderById($orderId);
        if (!$order) {
            return null;
        }

        $items = $order['items'] ?? [];
        $groupedItems = [];

        foreach ($items as $item) {
            $key = $item['ShoesID'];
            if (!isset($groupedItems[$key])) {
                $groupedItems[$key] = [
                    'id' => $item['ShoesID'],
                    'name' => $item['product_name'],
                    'price' => (float)$item['Price'],
                    'image' => $item['product_image'],
                    'quantity' => 0
                ];
            }
            $groupedItems[$key]['quantity']++;
        }

        $summaryItems = [];
        $subtotal = 0;

        foreach ($groupedItems as $group) {
            $lineTotal = $group['price'] * $group['quantity'];
            $summaryItems[] = [
                'id' => $group['id'],
                'name' => $group['name'],
                'image' => $group['image'],
                'price' => $group['price'],
                'quantity' => $group['quantity'],
                'subtotal' => $lineTotal
            ];
            $subtotal += $lineTotal;
        }

        $shipping = 10.00;
        $total = (float)$order['Total_price'];

        return [
            'meta' => $order,
            'items' => $summaryItems,
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $total
        ];
    }

    public function getOrdersByEmail($email)
    {
        $stmt = $this->pdo->prepare("
            SELECT o.OrderID, o.Total_price, o.Quantity, o.Date, o.Status,
                   COALESCE(m.Name, o.ShippingName) AS customer_name,
                   COALESCE(m.Email, o.ShippingEmail) AS email
            FROM `order` o
            LEFT JOIN member m ON o.MemberID = m.MemberID
            WHERE (m.Email = ? OR o.ShippingEmail = ?)
            ORDER BY o.Date DESC
        ");
        $stmt->execute([$email, $email]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    // Thêm đơn hàng mới
    public function addOrder($memberId, $totalPrice, $quantity, $shippingData)
    {
        $shippingName = $shippingData['name'] ?? null;
        $shippingEmail = $shippingData['email'] ?? null;
        $shippingAddress = $shippingData['address'] ?? null;
        $shippingCity = $shippingData['city'] ?? null;
        $shippingZip = $shippingData['zip'] ?? null;
        $paymentMethod = $shippingData['payment_method'] ?? null;

        try {
            $stmt = $this->pdo->prepare("INSERT INTO `order` (MemberID, Total_price, Quantity, Date, Earned_VIP, Status, ShippingName, ShippingEmail, ShippingAddress, ShippingCity, ShippingZip, PaymentMethod) 
                                         VALUES (?, ?, ?, NOW(), 0, 'Pending', ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $memberId,
                $totalPrice,
                $quantity,
                $shippingName,
                $shippingEmail,
                $shippingAddress,
                $shippingCity,
                $shippingZip,
                $paymentMethod
            ]);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            echo 'Order insert error: ' . $e->getMessage();
            exit; // hoặc die();
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
