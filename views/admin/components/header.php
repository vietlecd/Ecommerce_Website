<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - ShoeStore</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Mazer CSS
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/app.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/app-dark.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/iconly.css"> -->
    <!-- DataTables CSS -->
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/2.3.0/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.0/css/dataTables.dataTables.rtl.css"> -->
    

</head>

<body>
    <div class="admin-container">
        <div class="admin-sidebar">
            <h2>ShoeStore Admin</h2>
            <?php
            // Lấy controller và action hiện tại từ URL
            $currentController = isset($_GET['controller']) ? $_GET['controller'] : 'adminDashboard';
            $currentAction = isset($_GET['action']) ? $_GET['action'] : 'dashboard';
            ?>
            <ul>
                <li>
                    <a href="/index.php?controller=adminDashboard&action=dashboard" 
                       class="<?php echo $currentController === 'adminDashboard' ? 'active' : ''; ?>">
                        <i class="fas fa-tachometer-alt"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="/index.php?controller=adminProduct&action=products" 
                       class="<?php echo $currentController === 'adminProduct' ? 'active' : ''; ?>">
                        <i class="fas fa-shoe-prints"></i> Products
                    </a>
                </li>
                <li>
                    <a href="/index.php?controller=adminOrder&action=orders" 
                       class="<?php echo $currentController === 'adminOrder' ? 'active' : ''; ?>">
                        <i class="fas fa-shopping-cart"></i> Orders
                    </a>
                </li>
                <li>
                    <a href="/index.php?controller=adminCustomer&action=customers" 
                       class="<?php echo $currentController === 'adminCustomer' ? 'active' : ''; ?>">
                        <i class="fas fa-users"></i> Customers
                    </a>
                </li>
                <li>
                <li>
                    <a href="/index.php?controller=adminNews&action=manage" 
                    class="<?php echo $currentController === 'adminNews' ? 'active' : ''; ?>">
                        <i class="fas fa-newspaper"></i> News
                    </a>
                </li>
                </li>
                <li>
                    <a href="/index.php?controller=home&action=index">
                        <i class="fas fa-home"></i> Back to Site
                    </a>
                </li>
                <li>
                    <a href="/index.php?controller=auth&action=logout">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
        <div class="admin-content">
        
        
</body>
