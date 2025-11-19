<?php
require_once __DIR__ . '/../models/OrderModel.php';

class OrderTrackingController {
    private $orderModel;

    public function __construct() {
        $this->orderModel = new OrderModel();
    }

    public function index() {
        $error = '';
        $order = null;
        $trackingStatus = null;

        if (isset($_POST['track_order'])) {
            $trackingId = isset($_POST['tracking_id']) ? trim($_POST['tracking_id']) : '';
            
            if (empty($trackingId)) {
                $error = 'Please enter a tracking ID';
            } else {
                $order = $this->orderModel->getOrderByTrackingId(strtoupper($trackingId));
                
                if ($order) {
                    $trackingStatus = $this->getRandomTrackingStatus();
                } else {
                    $error = 'Order not found. Please check your tracking ID and try again.';
                }
            }
        }

        $headerPath = dirname(__DIR__) . '/views/components/header.php';
        $viewPath = dirname(__DIR__) . '/views/pages/order-tracking.php';
        $footerPath = dirname(__DIR__) . '/views/components/footer.php';

        if (file_exists($headerPath)) {
            require_once $headerPath;
        }

        if (file_exists($viewPath)) {
            $renderView = function ($error, $order, $trackingStatus) use ($viewPath) {
                require $viewPath;
            };
            $renderView($error, $order, $trackingStatus);
        }

        if (file_exists($footerPath)) {
            require_once $footerPath;
        }
    }

    private function getRandomTrackingStatus() {
        $statuses = [
            [
                'current' => 'Order Confirmed',
                'progress' => 20,
                'steps' => [
                    ['name' => 'Order Confirmed', 'completed' => true, 'date' => 'Just now', 'description' => 'Your order has been received and confirmed'],
                    ['name' => 'Preparing', 'completed' => false, 'date' => null, 'description' => 'We are preparing your items'],
                    ['name' => 'Shipped', 'completed' => false, 'date' => null, 'description' => 'Your order is on its way'],
                    ['name' => 'In Transit', 'completed' => false, 'date' => null, 'description' => 'Package is traveling to you'],
                    ['name' => 'Delivered', 'completed' => false, 'date' => null, 'description' => 'Expected delivery in 3-5 business days']
                ],
                'location' => 'Warehouse - Processing',
                'estimated_delivery' => '5-7 business days'
            ],
            [
                'current' => 'Preparing',
                'progress' => 40,
                'steps' => [
                    ['name' => 'Order Confirmed', 'completed' => true, 'date' => '2 hours ago', 'description' => 'Your order has been received and confirmed'],
                    ['name' => 'Preparing', 'completed' => true, 'date' => 'Just now', 'description' => 'Items are being packaged with care'],
                    ['name' => 'Shipped', 'completed' => false, 'date' => null, 'description' => 'Your order is on its way'],
                    ['name' => 'In Transit', 'completed' => false, 'date' => null, 'description' => 'Package is traveling to you'],
                    ['name' => 'Delivered', 'completed' => false, 'date' => null, 'description' => 'Expected delivery in 3-5 business days']
                ],
                'location' => 'Warehouse - Packing',
                'estimated_delivery' => '4-6 business days'
            ],
            [
                'current' => 'Shipped',
                'progress' => 60,
                'steps' => [
                    ['name' => 'Order Confirmed', 'completed' => true, 'date' => '1 day ago', 'description' => 'Your order has been received and confirmed'],
                    ['name' => 'Preparing', 'completed' => true, 'date' => '23 hours ago', 'description' => 'Items have been packaged'],
                    ['name' => 'Shipped', 'completed' => true, 'date' => '2 hours ago', 'description' => 'Package left our facility'],
                    ['name' => 'In Transit', 'completed' => false, 'date' => null, 'description' => 'Package is traveling to you'],
                    ['name' => 'Delivered', 'completed' => false, 'date' => null, 'description' => 'Expected delivery in 2-4 business days']
                ],
                'location' => 'Shipping Hub - In Transit',
                'estimated_delivery' => '3-5 business days',
                'tracking_number' => 'TRK' . rand(1000000, 9999999)
            ],
            [
                'current' => 'In Transit',
                'progress' => 80,
                'steps' => [
                    ['name' => 'Order Confirmed', 'completed' => true, 'date' => '2 days ago', 'description' => 'Your order has been received and confirmed'],
                    ['name' => 'Preparing', 'completed' => true, 'date' => '1 day ago', 'description' => 'Items have been packaged'],
                    ['name' => 'Shipped', 'completed' => true, 'date' => '1 day ago', 'description' => 'Package left our facility'],
                    ['name' => 'In Transit', 'completed' => true, 'date' => '5 hours ago', 'description' => 'Package is on the way to your city'],
                    ['name' => 'Delivered', 'completed' => false, 'date' => null, 'description' => 'Expected delivery tomorrow or next day']
                ],
                'location' => 'Local Distribution Center',
                'estimated_delivery' => '1-2 business days',
                'tracking_number' => 'TRK' . rand(1000000, 9999999),
                'last_update' => 'Package scanned at distribution center'
            ],
            [
                'current' => 'Delivered',
                'progress' => 100,
                'steps' => [
                    ['name' => 'Order Confirmed', 'completed' => true, 'date' => '5 days ago', 'description' => 'Your order has been received and confirmed'],
                    ['name' => 'Preparing', 'completed' => true, 'date' => '4 days ago', 'description' => 'Items have been packaged'],
                    ['name' => 'Shipped', 'completed' => true, 'date' => '3 days ago', 'description' => 'Package left our facility'],
                    ['name' => 'In Transit', 'completed' => true, 'date' => '2 days ago', 'description' => 'Package traveled to your city'],
                    ['name' => 'Delivered', 'completed' => true, 'date' => 'Today', 'description' => 'Package delivered successfully']
                ],
                'location' => 'Delivered to Address',
                'estimated_delivery' => 'Delivered',
                'tracking_number' => 'TRK' . rand(1000000, 9999999),
                'delivery_date' => date('Y-m-d'),
                'delivery_time' => 'Morning'
            ]
        ];

        return $statuses[array_rand($statuses)];
    }
}

