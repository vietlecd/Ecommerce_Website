<?php
class OrderModel
{
    private $pdo;

    public function __construct()
    {
        $host     = $_ENV['DB_HOST'] ?? 'mysql';
        $dbname   = $_ENV['DB_NAME'] ?? 'shoe';
        $username = $_ENV['DB_USER'] ?? 'shoes_user';
        $password = $_ENV['DB_PASS'] ?? 'shoes_pass';

        try {
            $this->pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    /**
     * Get orders with optional search / status filter / sort.
     *
     * @param int    $limit
     * @param int    $offset
     * @param string $keyword       search by order id / name / email
     * @param string $statusFilter  exact status
     * @param string $sort          date_desc|date_asc|id_desc|id_asc
     */
    public function getOrders(
        int $limit  = 0,
        int $offset = 0,
        string $keyword = '',
        string $statusFilter = '',
        string $sort = 'date_desc'
    ) {
        $sql = "
            SELECT
                o.OrderID,
                o.Total_price,
                o.Quantity,
                o.Date,
                o.Status,
                m.Name AS customer_name,
                m.Email
            FROM `order` o
            LEFT JOIN member m ON o.MemberID = m.MemberID
        ";

        $where  = [];
        $params = [];

        if ($keyword !== '') {
            $where[]       = '(o.OrderID LIKE :kw OR m.Name LIKE :kw OR m.Email LIKE :kw)';
            $params[':kw'] = '%' . $keyword . '%';
        }

        if ($statusFilter !== '') {
            $where[]             = 'o.Status = :status';
            $params[':status']   = $statusFilter;
        }

        if ($where) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        // Sort mapping
        switch ($sort) {
            case 'id_asc':
                $orderBy = ' ORDER BY o.OrderID ASC';
                break;
            case 'id_desc':
                $orderBy = ' ORDER BY o.OrderID DESC';
                break;
            case 'date_asc':
                $orderBy = ' ORDER BY o.Date ASC, o.OrderID ASC';
                break;
            case 'date_desc':
            default:
                $orderBy = ' ORDER BY o.Date DESC, o.OrderID DESC';
                break;
        }

        $sql .= $orderBy;

        if ($limit > 0) {
            $sql .= ' LIMIT :limit OFFSET :offset';
        }

        $stmt = $this->pdo->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        if ($limit > 0) {
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', max(0, $offset), PDO::PARAM_INT);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Count orders with same filter conditions.
     */
    public function getTotalOrders(string $keyword = '', string $statusFilter = ''): int
    {
        $sql = "
            SELECT COUNT(*) AS total
            FROM `order` o
            LEFT JOIN member m ON o.MemberID = m.MemberID
        ";

        $where  = [];
        $params = [];

        if ($keyword !== '') {
            $where[]       = '(o.OrderID LIKE :kw OR m.Name LIKE :kw OR m.Email LIKE :kw)';
            $params[':kw'] = '%' . $keyword . '%';
        }

        if ($statusFilter !== '') {
            $where[]             = 'o.Status = :status';
            $params[':status']   = $statusFilter;
        }

        if ($where) {
            $sql .= ' WHERE ' . implode(' AND ', $where);
        }

        $stmt = $this->pdo->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($row['total'] ?? 0);
    }

    // Order detail
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
                SELECT
                    os.ShoesID,
                    s.Name  AS product_name,
                    s.Price,
                    s.Image AS product_image,
                    os.OrderID
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

        $items        = $order['items'] ?? [];
        $groupedItems = [];

        foreach ($items as $item) {
            $key = $item['ShoesID'];
            if (!isset($groupedItems[$key])) {
                $groupedItems[$key] = [
                    'id'       => $item['ShoesID'],
                    'name'     => $item['product_name'],
                    'price'    => (float)$item['Price'],
                    'image'    => $item['product_image'],
                    'quantity' => 0
                ];
            }
            $groupedItems[$key]['quantity']++;
        }

        $summaryItems = [];
        $subtotal     = 0;

        foreach ($groupedItems as $group) {
            $lineTotal    = $group['price'] * $group['quantity'];
            $summaryItems[] = [
                'id'       => $group['id'],
                'name'     => $group['name'],
                'image'    => $group['image'],
                'price'    => $group['price'],
                'quantity' => $group['quantity'],
                'subtotal' => $lineTotal
            ];
            $subtotal += $lineTotal;
        }

        $shipping = 10.00;
        $total    = (float)$order['Total_price'];

        return [
            'meta'      => $order,
            'items'     => $summaryItems,
            'subtotal'  => $subtotal,
            'shipping'  => $shipping,
            'total'     => $total
        ];
    }

    public function getOrdersByEmail($email)
    {
        $stmt = $this->pdo->prepare("
            SELECT
                o.OrderID,
                o.Total_price,
                o.Quantity,
                o.Date,
                o.Status,
                COALESCE(m.Name,  o.ShippingName)  AS customer_name,
                COALESCE(m.Email, o.ShippingEmail) AS email
            FROM `order` o
            LEFT JOIN member m ON o.MemberID = m.MemberID
            WHERE (m.Email = ? OR o.ShippingEmail = ?)
            ORDER BY o.Date DESC
        ");
        $stmt->execute([$email, $email]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateOrderStatus($orderId, $status)
    {
        $validStatuses = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];
        if (!in_array($status, $validStatuses)) {
            return false;
        }

        $stmt = $this->pdo->prepare("UPDATE `order` SET Status = ? WHERE OrderID = ?");
        return $stmt->execute([$status, $orderId]);
    }

    public function addOrder($memberId, $totalPrice, $quantity, $shippingData)
    {
        $shippingName    = $shippingData['name'] ?? null;
        $shippingEmail   = $shippingData['email'] ?? null;
        $shippingAddress = $shippingData['address'] ?? null;
        $shippingCity    = $shippingData['city'] ?? null;
        $shippingZip     = $shippingData['zip'] ?? null;
        $paymentMethod   = $shippingData['payment_method'] ?? null;

        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO `order`
                    (MemberID, Total_price, Quantity, Date, Earned_VIP, Status,
                     ShippingName, ShippingEmail, ShippingAddress, ShippingCity, ShippingZip, PaymentMethod)
                VALUES
                    (?, ?, ?, NOW(), 0, 'Pending',
                     ?, ?, ?, ?, ?, ?)
            ");
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
            exit;
        }
    }

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
