<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (!defined('ADMIN_LAYOUT_SHUTDOWN_REGISTERED')) {
  define('ADMIN_LAYOUT_SHUTDOWN_REGISTERED', true);
  register_shutdown_function(function () {
    $footerPath = __DIR__ . '/admin_footer.php';
    if (!defined('ADMIN_LAYOUT_FOOTER_RENDERED') && file_exists($footerPath)) {
      require $footerPath;
    }
  });
}

$currentController = $_GET['controller'] ?? 'adminDashboard';
$currentAction = $_GET['action'] ?? 'dashboard';
$contentKey = $_GET['key'] ?? null;
$pageTitle = $pageTitle ?? 'V.AShoes Admin';
$userDisplayName = $_SESSION['name'] ?? ($_SESSION['email'] ?? 'Administrator');

$navItems = [
  [
    'label' => 'Dashboard',
    'icon' => 'ti ti-layout-dashboard',
    'url' => '/index.php?controller=adminDashboard&action=dashboard',
    'is_active' => function () use ($currentController) {
      return $currentController === 'adminDashboard';
    },
  ],
  [
    'label' => 'Products',
    'icon' => 'ti ti-shopping-bag',
    'url' => '/index.php?controller=adminProduct&action=products',
    'is_active' => function () use ($currentController) {
      return $currentController === 'adminProduct';
    },
  ],
  [
    'label' => 'Orders',
    'icon' => 'ti ti-receipt-2',
    'url' => '/index.php?controller=adminOrder&action=orders',
    'is_active' => function () use ($currentController) {
      return $currentController === 'adminOrder';
    },
  ],
  [
    'label' => 'Customers',
    'icon' => 'ti ti-users',
    'url' => '/index.php?controller=adminCustomer&action=customers',
    'is_active' => function () use ($currentController) {
      return $currentController === 'adminCustomer';
    },
  ],
  [
    'label' => 'News',
    'icon' => 'ti ti-news',
    'url' => '/index.php?controller=adminNews&action=manage',
    'is_active' => function () use ($currentController) {
      return $currentController === 'adminNews';
    },
  ],
  [
    'label' => 'Comments',
    'icon' => 'ti ti-message',
    'url' => '/index.php?controller=adminComments&action=manage',
    'is_active' => function () use ($currentController) {
      return $currentController === 'adminComments';
    },
  ],
  [
    'label' => 'Promotions',
    'icon' => 'ti ti-discount',
    'url' => '/index.php?controller=adminPromotion&action=manage',
    'is_active' => function () use ($currentController) {
      return $currentController === 'adminPromotion';
    },
  ],
  [
    'label' => 'About',
    'icon' => 'ti ti-info-circle',
    'url' => '/index.php?controller=adminContent&action=edit&key=about',
    'is_active' => function () use ($currentController, $contentKey) {
      return $currentController === 'adminContent' && $contentKey === 'about';
    },
  ],
  [
    'label' => 'Q&A',
    'icon' => 'ti ti-help-hexagon',
    'url' => '/index.php?controller=adminContent&action=edit&key=qna',
    'is_active' => function () use ($currentController, $contentKey) {
      return $currentController === 'adminContent' && $contentKey === 'qna';
    },
  ],
];
?>
<!doctype html>
<html lang="en" data-bs-theme="light">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title><?php echo htmlspecialchars($pageTitle); ?> · V.AShoes Admin</title>
  <link rel="icon" type="image/png" href="https://cdn-icons-png.flaticon.com/128/2742/2742687.png">
  <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/css/tabler.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/css/tabler-flags.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/css/tabler-payments.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/@tabler/core@1.4.0/dist/css/tabler-vendors.min.css" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" rel="stylesheet" />
  <style>
    @import url('https://rsms.me/inter/inter.css');

    :root {
      font-feature-settings: "cv03", "cv04", "cv11";
    }
  </style>
  <script src="https://cdn.tiny.cloud/1/4x5hb9ffv86aidqa4em2gen7jpm7d2i5gm8xettlzmg1xlai/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>
</head>

<body class="layout-fluid">
  <div class="page">
    <aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark">
      <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu" aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <h1 class="navbar-brand navbar-brand-autodark">
          <a href="/index.php?controller=adminDashboard&action=dashboard" class="text-decoration-none text-white fw-bold">V.AShoes Admin</a>
        </h1>
        <div class="collapse navbar-collapse" id="sidebar-menu">
          <ul class="navbar-nav pt-lg-3">
            <?php foreach ($navItems as $item): ?>
              <?php
              $isActive = false;
              if (isset($item['is_active']) && is_callable($item['is_active'])) {
                $isActive = (bool) $item['is_active']();
              }
              ?>
              <li class="nav-item">
                <a class="nav-link <?php echo $isActive ? 'active' : ''; ?>" href="<?php echo htmlspecialchars($item['url']); ?>">
                  <span class="nav-link-icon d-md-none d-lg-inline-block">
                    <i class="ti <?php echo htmlspecialchars($item['icon']); ?>"></i>
                  </span>
                  <span class="nav-link-title"><?php echo htmlspecialchars($item['label']); ?></span>
                </a>
              </li>
            <?php endforeach; ?>
            <li class="nav-item mt-3">
              <span class="nav-link text-uppercase text-secondary fw-bold small">Shortcuts</span>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/index.php?controller=home&action=index">
                <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-world"></i></span>
                <span class="nav-link-title">Back to site</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/index.php?controller=auth&action=logout">
                <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-logout"></i></span>
                <span class="nav-link-title">Logout</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </aside>
    <div class="page-wrapper">
      <header class="navbar navbar-expand-md d-print-none">
        <div class="container-xl">
          <div class="d-flex align-items-center w-100">
            <button class="navbar-toggler d-lg-none me-3" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu">
              <span class="navbar-toggler-icon"></span>
            </button>
            <div class="flex-fill">
              <div class="page-pretitle text-secondary text-uppercase">Admin workspace</div>
              <h2 class="page-title mb-0"><?php echo htmlspecialchars($pageTitle); ?></h2>
            </div>
            <div class="navbar-nav flex-row order-md-last ms-auto">
              <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex align-items-center" data-bs-toggle="dropdown" aria-label="Open user menu">
                  <span class="avatar avatar-sm" style="background-image: url('https://avatars.dicebear.com/api/initials/<?php echo urlencode($userDisplayName); ?>.svg');"></span>
                  <div class="d-none d-xl-block ps-2 lh-sm">
                    <div><?php echo htmlspecialchars($userDisplayName); ?></div>
                    <div class="mt-1 small text-secondary">Administrator</div>
                  </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                  <a href="/index.php?controller=home&action=index" class="dropdown-item">
                    <i class="ti ti-world me-2"></i> View website
                  </a>
                  <a href="/index.php?controller=auth&action=logout" class="dropdown-item text-danger">
                    <i class="ti ti-logout me-2"></i> Logout
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </header>
      <div class="page-body">
        <div class="container-xl">
