<?php
require_once __DIR__ . '/../models/ProductModelv2.php';

class AdminProductController {
    private $productModel;

    public function __construct() {
        $this->productModel = new ProductModel();
    }

    public function products() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /views/admin/index.php?controller=auth&action=login');
            exit;
        }

        $keyword = isset($_GET['keyword']) ? trim($_GET['keyword']) : '';
        $category = isset($_GET['category']) ? trim($_GET['category']) : '';
        $perPage = 8;
        $currentPage = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
        $offset = ($currentPage - 1) * $perPage;

        $products = $this->productModel->getProducts($keyword, $category, $perPage, $offset);
        $totalProducts = $this->productModel->getTotalProducts($keyword, $category);
        $totalPages = ceil($totalProducts / $perPage);
        $categories = $this->productModel->getCategories();

        $pageTitle = 'Quản lý sản phẩm';
        require_once __DIR__ . '/../views/admin/components/header.php';
        require_once __DIR__ . '/../views/admin/pages/products.php';
    }

    public function addProduct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /views/admin/index.php?controller=auth&action=login');
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
                    $error = 'Chỉ hỗ trợ các định dạng hình ảnh JPEG, PNG, GIF.';
                } elseif ($fileSize > $maxFileSize) {
                    $error = 'Kích thước hình ảnh không được vượt quá 5MB.';
                } else {
                    $imageName = uniqid() . '-' . basename($_FILES['image']['name']);
                    $uploadDir = __DIR__ . '/../assets/images/shoes/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }
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
                if (empty($name) || empty($price) || empty($description) || empty($categoryId) || empty($sizeTuples)) {
                    $error = 'Vui lòng nhập đầy đủ thông tin và size.';
                } else {
                    $productId = $this->productModel->addProduct($name, $price, $totalStock, $description, $categoryId, $primarySize, $image);
                    if ($productId) {
                        if ($this->productModel->syncProductSizes($productId, $sizeTuples)) {
                            $success = 'Thêm sản phẩm thành công!';
                        } else {
                            $error = 'Không thể lưu size cho sản phẩm.';
                        }
                    } else {
                        $error = 'Thêm sản phẩm thất bại.';
                    }
                }
            }
        }

        $categories = $this->productModel->getCategories();
        $pageTitle = 'Thêm sản phẩm';
        require_once __DIR__ . '/../views/admin/components/header.php';
        require_once __DIR__ . '/../views/admin/pages/add-product.php';
    }

    public function editProduct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /views/admin/index.php?controller=auth&action=login');
            exit;
        }

        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            header('Location: /views/admin/index.php?controller=adminProduct&action=products');
            exit;
        }

        $id = intval($_GET['id']);
        $product = $this->productModel->getProductById($id);

        if (!$product) {
            $product_id = $id;
        } else {
            $product_id = $product['id'];
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $price = trim($_POST['price'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $categoryId = trim($_POST['category_id'] ?? '');
            $sizeTuples = $this->collectSizeInputs();
            $totalStock = array_sum(array_column($sizeTuples, 'quantity'));
            $primarySize = $sizeTuples[0]['size'] ?? null;

            $image = $product['image'] ?? null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                $maxFileSize = 5 * 1024 * 1024;
                $fileType = mime_content_type($_FILES['image']['tmp_name']);
                $fileSize = $_FILES['image']['size'];

                if (!in_array($fileType, $allowedTypes)) {
                    $error = 'Chỉ hỗ trợ các định dạng hình ảnh JPEG, PNG, GIF.';
                } elseif ($fileSize > $maxFileSize) {
                    $error = 'Kích thước hình ảnh không được vượt quá 5MB.';
                } else {
                    $imageName = uniqid() . '-' . basename($_FILES['image']['name']);
                    $uploadDir = __DIR__ . '/../assets/images/shoes/';
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
                        $error = 'Không thể tải lên hình ảnh.';
                    }
                }
            }

            if (!isset($error)) {
                if (empty($name) || empty($price) || empty($description) || empty($categoryId) || empty($sizeTuples)) {
                    $error = 'Vui lòng nhập đầy đủ thông tin và size.';
                } else {
                    if ($this->productModel->updateProduct($id, $name, $price, $totalStock, $description, $categoryId, $primarySize, $image)) {
                        if ($this->productModel->syncProductSizes($id, $sizeTuples)) {
                            $success = 'Cập nhật sản phẩm thành công!';
                            $product = $this->productModel->getProductById($id);
                        } else {
                            $error = 'Không thể lưu size cho sản phẩm.';
                        }
                    } else {
                        $error = 'Cập nhật sản phẩm thất bại.';
                    }
                }
            }
        }

        $categories = $this->productModel->getCategories();
        $pageTitle = 'Chỉnh sửa sản phẩm';
        require_once __DIR__ . '/../views/admin/components/header.php';
        require_once __DIR__ . '/../views/admin/pages/edit-product.php';
    }

    public function deleteProduct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
            header('Location: /views/admin/index.php?controller=auth&action=login');
            exit;
        }

        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            header('Location: /views/admin/index.php?controller=adminProduct&action=products');
            exit;
        }

        $id = intval($_GET['id']);
        $product = $this->productModel->getProductById($id);

        if ($product) {
            $deleted = $this->productModel->deleteProduct($id);
            if ($deleted) {
                if ($product['image'] && file_exists(__DIR__ . '/../assets/images/shoes/' . $product['image'])) {
                    unlink(__DIR__ . '/../assets/images/shoes/' . $product['image']);
                }
                $_SESSION['message'] = 'Product deleted successfully.';
            } else {
                $_SESSION['error'] = 'Cannot delete product because it is used in orders or other records.';
            }
        } else {
            $_SESSION['error'] = 'Product not found.';
        }

        header('Location: /views/admin/index.php?controller=adminProduct&action=products');
        exit;
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
