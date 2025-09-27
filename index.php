<?php
session_start();

// Autoload classes
spl_autoload_register(function ($class_name) {
    $controllerPath = 'controllers/' . $class_name . '.php';
    $modelPath = 'models/' . $class_name . '.php';

    if (file_exists($controllerPath)) {
        require_once $controllerPath;
    } elseif (file_exists($modelPath)) {
        require_once $modelPath;
    }
});

$path = isset($_SERVER['REQUEST_URI']) ? parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) : '/';
$isAdmin = (strpos($path, '/admin') === 0);

$controller = isset($_GET['controller']) ? preg_replace('/[^a-zA-Z0-9]/', '', $_GET['controller']) : ($isAdmin ? 'adminDashboard' : 'home');
$action = isset($_GET['action']) ? preg_replace('/[^a-zA-Z0-9]/', '', $_GET['action']) : 'index';

$adminControllers = ['adminDashboard', 'adminProduct', 'adminOrder', 'adminCustomer', 'adminNews', 'adminPromotion', 'adminMember'];
if (in_array($controller, $adminControllers) && (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin')) {
    header('Location: /index.php?controller=auth&action=login');
    exit;
}

$controllerClass = ucfirst($controller) . 'Controller';

if (!class_exists($controllerClass)) {
    error_log("Controller not found: $controllerClass", 3, 'logs/errors.log');
    header('HTTP/1.0 404 Not Found');
    require_once 'views/errors/404.php';
    exit;
}

$controllerInstance = new $controllerClass();

if (!method_exists($controllerInstance, $action)) {
    error_log("Action not found: $action in $controllerClass", 3, 'logs/errors.log');
    header('HTTP/1.0 404 Not Found');
    require_once 'views/errors/404.php';
    exit;
}

// Gọi action
$controllerInstance->$action();
