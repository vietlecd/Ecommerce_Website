<?php
require_once 'models/UserModel.php';

class AccountController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel();
    }

    public function index() {
        // Kiểm tra đăng nhập
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Please log in to access your account.";
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        // Chặn admin truy cập trang này
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
            $_SESSION['error'] = "Admins cannot access this page.";
            header('Location: index.php?controller=admin&action=dashboard');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $user = $this->userModel->getUserById($userId);

        if (!$user) {
            $_SESSION['error'] = "User not found.";
            header('Location: index.php?controller=auth&action=logout');
            exit;
        }

        // Xử lý cập nhật thông tin
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_info'])) {
            $name = isset($_POST['name']) ? trim($_POST['name']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';

            // Kiểm tra dữ liệu đầu vào
            if (empty($name) || empty($email) || empty($phone)) {
                $_SESSION['error'] = "All fields are required.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = "Invalid email format.";
            } elseif ($this->userModel->isEmailTaken($email, $userId)) {
                $_SESSION['error'] = "This email is already taken.";
            } else {
                // Cập nhật thông tin người dùng
                try {
                    $updated = $this->userModel->updateUser($userId, $name, $email, $phone);
                    if ($updated) {
                        $_SESSION['message'] = "Your information has been updated successfully.";
                        // Cập nhật lại thông tin người dùng để hiển thị
                        $user = $this->userModel->getUserById($userId);
                    } else {
                        $_SESSION['error'] = "Failed to update your information.";
                    }
                } catch (Exception $e) {
                    $_SESSION['error'] = "Error updating information: " . $e->getMessage();
                }
            }
        }

        // Xử lý đổi mật khẩu
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
            $currentPassword = isset($_POST['current_password']) ? trim($_POST['current_password']) : '';
            $newPassword = isset($_POST['new_password']) ? trim($_POST['new_password']) : '';
            $confirmPassword = isset($_POST['confirm_password']) ? trim($_POST['confirm_password']) : '';

            // Kiểm tra dữ liệu đầu vào
            if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
                $_SESSION['error'] = "All password fields are required.";
            } elseif ($this->userModel->checkPassword($userId, $currentPassword) === false) {
                $_SESSION['error'] = "Current password is incorrect.";
            } elseif ($newPassword !== $confirmPassword) {
                $_SESSION['error'] = "New password and confirmation do not match.";
            } elseif (strlen($newPassword) < 6) {
                $_SESSION['error'] = "New password must be at least 6 characters long.";
            } else {
                // Cập nhật mật khẩu mới
                try {
                    $updated = $this->userModel->updatePassword($userId, $newPassword);
                    if ($updated) {
                        $_SESSION['message'] = "Your password has been updated successfully.";
                    } else {
                        $_SESSION['error'] = "Failed to update your password.";
                    }
                } catch (Exception $e) {
                    $_SESSION['error'] = "Error updating password: " . $e->getMessage();
                }
            }
        }

        require_once 'views/pages/account.php';
    }
}