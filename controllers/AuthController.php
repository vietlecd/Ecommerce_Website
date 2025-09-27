<?php
require_once 'models/Database.php';

class AuthController {
    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function login() {
        // Kiểm tra nếu đã đăng nhập, chuyển hướng đến trang chính
        if (isset($_SESSION['user_id'])) {
            if ($_SESSION['role'] === 'admin') {
                header('Location: index.php?controller=admin&action=dashboard');
            } else {
                header('Location: index.php?controller=home&action=index');
            }
            exit;
        }

        // Xử lý đăng nhập
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');

            if (empty($username) || empty($password)) {
                $error = 'Vui lòng nhập đầy đủ tên người dùng và mật khẩu.';
            } else {
                // Kiểm tra trong bảng `admin`
                $stmt = $this->db->prepare("SELECT * FROM admin WHERE Adname = ? AND Password = ?");
                $stmt->execute([$username, $password]);
                $admin = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($admin) {
                    $_SESSION['user_id'] = $admin['AdminID'];
                    $_SESSION['role'] = 'admin';
                    header('Location: index.php?controller=admin&action=dashboard');
                    exit;
                }

                // Kiểm tra trong bảng `member`
                $stmt = $this->db->prepare("SELECT * FROM member WHERE Username = ? AND Password = ?");
                $stmt->execute([$username, $password]);
                $member = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($member) {
                    $_SESSION['user_id'] = $member['MemberID'];
                    $_SESSION['role'] = 'member';
                    header('Location: index.php?controller=home&action=index');
                    exit;
                }

                $error = 'Tên người dùng hoặc mật khẩu không đúng.';
            }
        }

        require_once 'views/components/header.php';
        require_once 'views/pages/login.php';
    }

    public function register() {
        // Kiểm tra nếu đã đăng nhập, chuyển hướng đến trang chính
        if (isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=home&action=index');
            exit;
        }

        // Xử lý đăng ký
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');

            if (empty($username) || empty($password) || empty($name) || empty($email) || empty($phone)) {
                $error = 'Vui lòng nhập đầy đủ thông tin.';
            } else {
                // Kiểm tra xem username đã tồn tại chưa
                $stmt = $this->db->prepare("SELECT COUNT(*) FROM member WHERE Username = ?");
                $stmt->execute([$username]);
                if ($stmt->fetchColumn() > 0) {
                    $error = 'Tên người dùng đã tồn tại.';
                } else {
                    // Thêm người dùng mới vào bảng `member`
                    $stmt = $this->db->prepare("INSERT INTO member (Username, Password, Name, Email, Phone, Exp_VIP, AdminID) VALUES (?, ?, ?, ?, ?, NULL, NULL)");
                    if ($stmt->execute([$username, $password, $name, $email, $phone])) {
                        $success = 'Đăng ký thành công! Vui lòng đăng nhập.';
                    } else {
                        $error = 'Đăng ký thất bại. Vui lòng thử lại.';
                    }
                }
            }
        }

        require_once 'views/components/header.php';
        require_once 'views/pages/register.php';
    }

    public function logout() {
        session_unset();
        session_destroy();
        header('Location: index.php?controller=home&action=index');
        exit;
    }
}