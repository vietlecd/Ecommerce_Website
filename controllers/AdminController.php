<?php
require_once 'models/ProductModelv2.php';

class AdminController {
    private $productModel;
    private $db;

    public function __construct() {
        $this->productModel = new ProductModel();
        $database = new Database();
        $this->db = $database->getConnection();
    }   

    public function dashboard() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        // Thống kê tổng quan
        $stats = [
            'totalOrders' => 0,
            'monthlyRevenue' => 0,
            'totalCustomers' => 0,
            'totalProducts' => 0,
        ];

        // Tổng số đơn hàng
        $stats['totalOrders'] = (int)$this->db->query("SELECT COUNT(*) FROM `order`")->fetchColumn();

        // Doanh thu tháng hiện tại
        $stmtRevenue = $this->db->prepare("SELECT COALESCE(SUM(Total_price), 0) FROM `order` WHERE DATE_FORMAT(`Date`, '%Y-%m') = DATE_FORMAT(CURDATE(), '%Y-%m')");
        $stmtRevenue->execute();
        $stats['monthlyRevenue'] = (float)$stmtRevenue->fetchColumn();

        // Tổng số khách hàng
        $stats['totalCustomers'] = (int)$this->db->query("SELECT COUNT(*) FROM `member`")->fetchColumn();

        // Tổng số sản phẩm (bảng shoes)
        $stats['totalProducts'] = (int)$this->db->query("SELECT COUNT(*) FROM `shoes`")->fetchColumn();

        // Đơn hàng gần đây
        $recentOrdersStmt = $this->db->query(
            "SELECT o.OrderID, m.Name AS customer_name, o.Date, o.Total_price, o.Status
             FROM `order` o
             JOIN member m ON o.MemberID = m.MemberID
             ORDER BY o.Date DESC
             LIMIT 5"
        );
        $recentOrders = $recentOrdersStmt->fetchAll(PDO::FETCH_ASSOC);

        require_once 'views/admin/components/header.php';
        require_once 'views/admin/pages/dashboard.php';
    }

    public function products() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        require_once 'views/admin/components/header.php';
        require_once 'views/admin/pages/products.php';
    }

    public function addProduct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $price = trim($_POST['price'] ?? '');
            $stock = trim($_POST['stock'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $categoryId = trim($_POST['category_id'] ?? '');
            $shoesSize = trim($_POST['shoes_size'] ?? '');

            // Xử lý tải lên hình ảnh
            $image = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                $maxFileSize = 5 * 1024 * 1024; // 5MB
                $fileType = mime_content_type($_FILES['image']['tmp_name']);
                $fileSize = $_FILES['image']['size'];

                if (!in_array($fileType, $allowedTypes)) {
                    $error = 'Chỉ hỗ trợ các định dạng hình ảnh JPEG, PNG, GIF.';
                } elseif ($fileSize > $maxFileSize) {
                    $error = 'Kích thước hình ảnh không được vượt quá 5MB.';
                } else {
                    $imageName = uniqid() . '-' . basename($_FILES['image']['name']);
                    $uploadDir = 'assets/images/';
                    $uploadPath = $uploadDir . $imageName;

                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                        $image = $imageName;
                    } else {
                        $error = 'Không thể tải lên hình ảnh.';
                    }
                }
            } else {
                $error = 'Vui lòng chọn một hình ảnh.';
            }

            if (!isset($error)) {
                if (empty($name) || empty($price) || empty($stock) || empty($description) || empty($categoryId) || empty($shoesSize)) {
                    $error = 'Vui lòng nhập đầy đủ thông tin.';
                } else {
                    $stmt = $this->db->prepare("INSERT INTO shoes (Name, Price, Stock, Description, DateCreate, DateUpdate, CategoryID, shoes_size, Image) VALUES (?, ?, ?, ?, CURDATE(), CURDATE(), ?, ?, ?)");
                    if ($stmt->execute([$name, $price, $stock, $description, $categoryId, $shoesSize, $image])) {
                        $success = 'Thêm sản phẩm thành công!';
                    } else {
                        $error = 'Thêm sản phẩm thất bại.';
                    }
                }
            }
        }

        $categories = $this->db->query("SELECT * FROM category")->fetchAll(PDO::FETCH_ASSOC);

        require_once 'views/admin/components/header.php';
        require_once 'views/admin/pages/add-product.php';
    }

    public function editProduct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            header('Location: index.php?controller=admin&action=products');
            exit;
        }

        $id = intval($_GET['id']);
        $product = $this->productModel->getProductById($id);

        if (!$product) {
            $product_id = $id;
            // Dữ liệu hardcode sẽ được xử lý trong edit-product.php
        } else {
            $product_id = $product['ShoesID'];
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $price = trim($_POST['price'] ?? '');
            $stock = trim($_POST['stock'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $categoryId = trim($_POST['category_id'] ?? '');
            $shoesSize = trim($_POST['shoes_size'] ?? '');

            // Xử lý hình ảnh
            $image = $product['Image'] ?? null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                $maxFileSize = 5 * 1024 * 1024; // 5MB
                $fileType = mime_content_type($_FILES['image']['tmp_name']);
                $fileSize = $_FILES['image']['size'];

                if (!in_array($fileType, $allowedTypes)) {
                    $error = 'Chỉ hỗ trợ các định dạng hình ảnh JPEG, PNG, GIF.';
                } elseif ($fileSize > $maxFileSize) {
                    $error = 'Kích thước hình ảnh không được vượt quá 5MB.';
                } else {
                    $imageName = uniqid() . '-' . basename($_FILES['image']['name']);
                    $uploadDir = 'assets/images/';
                    $uploadPath = $uploadDir . $imageName;

                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                        // Xóa hình ảnh cũ nếu có
                        if ($image && file_exists($uploadDir . $image)) {
                            unlink($uploadDir . $image);
                        }
                        $image = $imageName;
                    } else {
                        $error = 'Không thể tải lên hình ảnh.';
                    }
                }
            }

            if (!isset($error)) {
                if (empty($name) || empty($price) || empty($stock) || empty($description) || empty($categoryId) || empty($shoesSize)) {
                    $error = 'Vui lòng nhập đầy đủ thông tin.';
                } else {
                    $stmt = $this->db->prepare("UPDATE shoes SET Name = ?, Price = ?, Stock = ?, Description = ?, DateUpdate = CURDATE(), CategoryID = ?, shoes_size = ?, Image = ? WHERE ShoesID = ?");
                    if ($stmt->execute([$name, $price, $stock, $description, $categoryId, $shoesSize, $image, $id])) {
                        $success = 'Cập nhật sản phẩm thành công!';
                        $product = $this->productModel->getProductById($id);
                    } else {
                        $error = 'Cập nhật sản phẩm thất bại.';
                    }
                }
            }
        }

        $categories = $this->db->query("SELECT * FROM category")->fetchAll(PDO::FETCH_ASSOC);

        require_once 'views/admin/components/header.php';
        require_once 'views/admin/pages/edit-product.php';
    }

    public function deleteProduct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            header('Location: index.php?controller=admin&action=products');
            exit;
        }

        $id = intval($_GET['id']);
        $product = $this->productModel->getProductById($id);

        if ($product) {
            // Xóa hình ảnh nếu có
            if ($product['Image'] && file_exists('assets/images/' . $product['Image'])) {
                unlink('assets/images/' . $product['Image']);
            }
            $stmt = $this->db->prepare("DELETE FROM shoes WHERE ShoesID = ?");
            $stmt->execute([$id]);
        }

        header('Location: index.php?controller=admin&action=products');
        exit;
    }

    public function orders() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        require_once 'views/admin/components/header.php';
        require_once 'views/admin/pages/orders.php';
    }

    public function orderDetail() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            header('Location: index.php?controller=admin&action=orders');
            exit;
        }

        $order_id = intval($_GET['id']);
        // Dữ liệu hardcode sẽ được xử lý trong order-detail.php

        require_once 'views/admin/components/header.php';
        require_once 'views/admin/pages/order-detail.php';
    }

    public function customers() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        require_once 'views/admin/components/header.php';
        require_once 'views/admin/pages/customers.php';
    }
}