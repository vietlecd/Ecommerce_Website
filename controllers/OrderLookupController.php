<?php
require_once __DIR__ . '/../models/OrderModel.php';

class OrderLookupController {
    private $orderModel;

    public function __construct() {
        $this->orderModel = new OrderModel();
    }

    public function index() {
        $orderSummary = null;
        $error = '';
        $emailOrders = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['lookup_id'])) {
                $orderIdInput = isset($_POST['order_id']) ? trim($_POST['order_id']) : '';
                if ($orderIdInput === '' || !ctype_digit($orderIdInput)) {
                    $error = 'Please enter a valid order ID using numbers only.';
                } else {
                    $orderId = (int) $orderIdInput;
                    $orderSummary = $this->orderModel->getOrderSummaryById($orderId);
                    if (!$orderSummary) {
                        $error = 'We could not find any order matching that ID. Please double-check and try again.';
                    }
                }
            } elseif (isset($_POST['lookup_email_submit'])) {
                $emailInput = isset($_POST['lookup_email']) ? trim($_POST['lookup_email']) : '';
                if ($emailInput === '' || !filter_var($emailInput, FILTER_VALIDATE_EMAIL)) {
                    $error = 'Please enter a valid email address.';
                } else {
                    $emailOrders = $this->orderModel->getOrdersByEmail($emailInput);
                    if (empty($emailOrders)) {
                        $error = 'We could not find any orders for that email.';
                    }
                }
            }
        }

        if (isset($_GET['order_id']) && ctype_digit($_GET['order_id']) && !$orderSummary) {
            $orderId = (int) $_GET['order_id'];
            $orderSummary = $this->orderModel->getOrderSummaryById($orderId);
        }

        $headerPath = dirname(__DIR__) . '/views/components/header.php';
        $viewPath = dirname(__DIR__) . '/views/pages/order-lookup.php';
        $footerPath = dirname(__DIR__) . '/views/components/footer.php';

        if (file_exists($headerPath)) {
            require_once $headerPath;
        } else {
            die("Header file not found: $headerPath");
        }

        if (file_exists($viewPath)) {
            $renderView = function ($orderSummary, $emailOrders, $error) use ($viewPath) {
                require $viewPath;
            };
            $renderView($orderSummary, $emailOrders, $error);
        } else {
            die("View file not found: $viewPath");
        }

        if (file_exists($footerPath)) {
            require_once $footerPath;
        } else {
            die("Footer file not found: $footerPath");
        }
    }
}


