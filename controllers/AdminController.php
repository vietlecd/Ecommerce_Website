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

        require_once 'models/OrderModel.php';
        require_once 'models/MemberModel.php';
        require_once 'models/ProductModelv2.php';

        $orderModel = new OrderModel();
        $memberModel = new MemberModel();
        $productModel = new ProductModel();

        $totalOrders = $orderModel->getTotalOrders();
        $monthlyRevenue = $orderModel->getMonthlyRevenue();
        $totalCustomers = $memberModel->getTotalCustomers();
        $totalProducts = $productModel->getTotalProductsCount();
        $recentOrders = $orderModel->getOrders(5, 0);

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
            $description = trim($_POST['description'] ?? '');
            $categoryId = trim($_POST['category_id'] ?? '');
            $sizeTuples = $this->collectSizeInputs();
            $totalStock = array_sum(array_column($sizeTuples, 'quantity'));
            $primarySize = $sizeTuples[0]['size'] ?? null;

            $image = null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                $maxFileSize = 5 * 1024 * 1024;
                $fileType = mime_content_type($_FILES['image']['tmp_name']);
                $fileSize = $_FILES['image']['size'];

                if (!in_array($fileType, $allowedTypes)) {
                    $error = 'Unsupported image format (JPEG, PNG, GIF only).';
                } elseif ($fileSize > $maxFileSize) {
                    $error = 'Image must be smaller than 5MB.';
                } else {
                    $imageName = uniqid() . '-' . basename($_FILES['image']['name']);
                    $uploadDir = 'assets/images/';
                    $uploadPath = $uploadDir . $imageName;

                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                        $image = $imageName;
                    } else {
                        $error = 'Unable to upload image.';
                    }
                }
            } else {
                $error = 'Please choose an image.';
            }

            if (!isset($error)) {
                if (empty($name) || empty($price) || empty($description) || empty($categoryId) || empty($sizeTuples)) {
                    $error = 'Please fill in all required fields and at least one size.';
                } else {
                    $stmt = $this->db->prepare("INSERT INTO shoes (Name, Price, Stock, Description, DateCreate, DateUpdate, CategoryID, shoes_size, Image) VALUES (?, ?, ?, ?, CURDATE(), CURDATE(), ?, ?, ?)");
                    if ($stmt->execute([$name, $price, $totalStock, $description, $categoryId, $primarySize, $image])) {
                        $shoeId = (int)$this->db->lastInsertId();
                        if ($this->productModel->syncProductSizes($shoeId, $sizeTuples)) {
                            $success = 'Product created successfully!';
                        } else {
                            $error = 'Could not save size information.';
                        }
                    } else {
                        $error = 'Failed to create product.';
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
            $description = trim($_POST['description'] ?? '');
            $categoryId = trim($_POST['category_id'] ?? '');
            $sizeTuples = $this->collectSizeInputs();
            $totalStock = array_sum(array_column($sizeTuples, 'quantity'));
            $primarySize = $sizeTuples[0]['size'] ?? null;

            $image = $product['Image'] ?? null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                $maxFileSize = 5 * 1024 * 1024;
                $fileType = mime_content_type($_FILES['image']['tmp_name']);
                $fileSize = $_FILES['image']['size'];

                if (!in_array($fileType, $allowedTypes)) {
                    $error = 'Unsupported image format (JPEG, PNG, GIF only).';
                } elseif ($fileSize > $maxFileSize) {
                    $error = 'Image must be smaller than 5MB.';
                } else {
                    $imageName = uniqid() . '-' . basename($_FILES['image']['name']);
                    $uploadDir = 'assets/images/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
                    $uploadPath = $uploadDir . $imageName;

                    if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath)) {
                        if ($image && file_exists($uploadDir . $image)) {
                            unlink($uploadDir . $image);
                        }
                        $image = $imageName;
                    } else {
                        $error = 'Unable to upload image.';
                    }
                }
            }

            if (!isset($error)) {
                if (empty($name) || empty($price) || empty($description) || empty($categoryId) || empty($sizeTuples)) {
                    $error = 'Please fill in all required fields and at least one size.';
                } else {
                    $stmt = $this->db->prepare("UPDATE shoes SET Name = ?, Price = ?, Stock = ?, Description = ?, DateUpdate = CURDATE(), CategoryID = ?, shoes_size = ?, Image = ? WHERE ShoesID = ?");
                    if ($stmt->execute([$name, $price, $totalStock, $description, $categoryId, $primarySize, $image, $id])) {
                        if ($this->productModel->syncProductSizes($id, $sizeTuples)) {
                            $success = 'Product updated successfully!';
                            $product = $this->productModel->getProductById($id);
                        } else {
                            $error = 'Could not save size information.';
                        }
                    } else {
                        $error = 'Failed to update product.';
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

    private function collectSizeInputs(): array {
        $sizes = $_POST['sizes'] ?? [];
        $quantities = $_POST['size_quantities'] ?? [];
        $tuples = [];

        foreach ($sizes as $index => $sizeValue) {
            $sizeValue = trim((string)$sizeValue);
            if ($sizeValue === '' || !is_numeric($sizeValue)) {
                continue;
            }

            $quantity = isset($quantities[$index]) ? (int)$quantities[$index] : 0;
            if ($quantity < 0) {
                $quantity = 0;
            }

            $tuples[] = [
                'size' => (float)$sizeValue,
                'quantity' => $quantity
            ];
        }

        return $tuples;
    }
}
