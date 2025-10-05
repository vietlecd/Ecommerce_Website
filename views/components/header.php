<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShoeStore - Find Your Perfect Pair</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <header>
        <div class="container">
            <div class="logo">
                <a href="/index.php">
                    <h1>ShoeStore</h1>
                </a>
            </div>
            <nav>
                <ul>
                    <li><a href="/index.php">Home</a></li>
                    <li><a href="/index.php?controller=products&action=index">Products</a></li>
                    <li><a href="/index.php?controller=news&action=index">News</a></li>
                    <li><a href="/index.php?controller=about&action=index">About</a></li>
                    <li><a href="/index.php?controller=qna&action=index">Q&A</a></li>
                    <li><a href="/index.php?controller=cart&action=index"><i class="fas fa-shopping-cart"></i> Cart
                            <?php if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                                <span class="cart-count"><?php echo count($_SESSION['cart']); ?></span>
                            <?php endif; ?>
                        </a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'member'): ?>
                            <li><a href="/index.php?controller=account&action=index">My Account</a></li>
                        <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <li><a href="/index.php?controller=admin&action=dashboard">Dashboard</a></li>
                        <?php endif; ?>
                        <li><a href="/index.php?controller=auth&action=logout">Logout</a></li>
                    <?php else: ?>
                        <li><a href="/index.php?controller=auth&action=login">Login</a></li>
                        <li><a href="/index.php?controller=auth&action=register">Register</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <main>
        <div class="container">
