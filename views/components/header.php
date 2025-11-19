<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>V.AShoes - Find Your Perfect Pair</title>
    <link rel="icon" type="image/png" href="https://cdn-icons-png.flaticon.com/128/2742/2742687.png">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <?php
    $logoUrl = 'https://cdn-icons-png.flaticon.com/128/2742/2742687.png';
    $navItems = [
        ['key' => 'home', 'label' => 'Home', 'url' => '/index.php', 'icon' => 'https://cdn-icons-png.flaticon.com/128/1946/1946436.png'],
        ['key' => 'products', 'label' => 'Products', 'url' => '/index.php?controller=products&action=index', 'icon' => 'https://cdn-icons-png.flaticon.com/128/3205/3205973.png'],
        ['key' => 'news', 'label' => 'News', 'url' => '/index.php?controller=news&action=index', 'icon' => 'https://cdn-icons-png.flaticon.com/128/2965/2965879.png'],
        ['key' => 'about', 'label' => 'About', 'url' => '/index.php?controller=about&action=index', 'icon' => 'https://cdn-icons-png.flaticon.com/128/1256/1256650.png'],
        ['key' => 'qna', 'label' => 'Q&A', 'url' => '/index.php?controller=qna&action=index', 'icon' => 'https://cdn-icons-png.flaticon.com/128/854/854866.png'],
        ['key' => 'cart', 'label' => 'Cart', 'url' => '/index.php?controller=cart&action=index', 'icon' => 'https://cdn-icons-png.flaticon.com/128/891/891462.png'],
    ];

    $labelMap = [
        'home' => 'Home',
        'products' => 'Products',
        'news' => 'News',
        'about' => 'About',
        'qna' => 'Q&A',
        'cart' => 'Cart',
        'account' => 'My Account',
        'auth' => 'Account',
        'admin' => 'Admin',
        'detail' => 'Detail',
        'login' => 'Login',
        'register' => 'Register'
    ];

    $currentController = isset($_GET['controller']) ? strtolower($_GET['controller']) : 'home';
    $currentAction = isset($_GET['action']) ? strtolower($_GET['action']) : 'index';

    $buildTooltip = function ($data) {
        $eyebrow = htmlspecialchars($data['title'], ENT_QUOTES, 'UTF-8');
        $focus = htmlspecialchars($data['focus'], ENT_QUOTES, 'UTF-8');
        $highlightRows = '';
        foreach ($data['highlight_rows'] as $row) {
            $highlightRows .= '<tr>';
            foreach ($row as $cell) {
                $highlightRows .= '<td>' . $cell . '</td>';
            }
            $highlightRows .= '</tr>';
        }
        $metaRows = '';
        foreach ($data['meta_rows'] as $row) {
            $metaRows .= '<tr>';
            foreach ($row as $cell) {
                $metaRows .= '<td>' . $cell . '</td>';
            }
            $metaRows .= '</tr>';
        }
        return <<<HTML
<div class="nav-tooltip-content">
    <table class="nav-tooltip-table nav-tooltip-table-highlight">
        <thead>
            <tr>
                <th colspan="4">
                    <span class="nav-tooltip-eyebrow">{$eyebrow}</span>
                    <span class="nav-tooltip-focus"><strong>{$focus}</strong> {$data['intro']}</span>
                </th>
            </tr>
        </thead>
        <tbody>{$highlightRows}</tbody>
    </table>
    <table class="nav-tooltip-table nav-tooltip-table-meta">
        <tbody>{$metaRows}</tbody>
    </table>
    <p class="nav-tooltip-copy">{$data['footer']}</p>
</div>
HTML;
    };

    $navTooltipData = [
        'home' => [
            'title' => 'Home Command Center',
            'focus' => 'Home',
            'intro' => 'frames every visit with <em>calm velocity</em>.',
            'highlight_rows' => [
                [
                    '<strong>Rhythm</strong><br><em>Hero playlists rotate every 12 minutes.</em>',
                    '<strong><u>Concierge</u></strong><br><span class="nav-tooltip-accent">Pinned chat</span> greets every persona.',
                    '<strong>Continuity</strong><br><em>Story cards snap to the same axis across devices.</em>',
                    '<strong>Proof</strong><br><span class="nav-tooltip-muted">Live reviews scroll beside drops.</span>'
                ]
            ],
            'meta_rows' => [
                [
                    '<span>Focus</span><strong>Orientation</strong><br><em>Map every pathway in two taps.</em>',
                    '<span>Palette</span><strong><s>Flat</s> Layered</strong><br><em>Soft gradients hug bold serif copy.</em>',
                    '<span>Data</span><strong>Predictive</strong><br><em>Intent scoring tunes modules instantly.</em>',
                    '<span>Tone</span><strong><u>Warm Authority</u></strong><br><em>Concise, rooted, reassuring.</em>'
                ]
            ],
            'footer' => 'Hover to glimpse the atelier moodboard, stay for the living brief.'
        ],
        'products' => [
            'title' => 'Product Atlas',
            'focus' => 'Products',
            'intro' => 'curates assortments with <strong>merchant-grade precision</strong>.',
            'highlight_rows' => [
                [
                    '<strong>Filters</strong><br><em>Stacks by mood, size, sustainability.</em>',
                    '<strong>Comparisons</strong><br><span class="nav-tooltip-accent">Overlay</span> price, drop date, stock health.',
                    '<strong>Badging</strong><br><em>Animated chips call out <u>Limited</u> or <s>Sold Out</s>.</em>',
                    '<strong>Continuity</strong><br><span class="nav-tooltip-muted">Wishlist syncs with cart + checkout.</span>'
                ]
            ],
            'meta_rows' => [
                [
                    '<span>Action</span><strong>Shop</strong><br><em>Slide into curated rails.</em>',
                    '<span>Story</span><strong>Material</strong><br><em>Cues for leather, knit, recycled blends.</em>',
                    '<span>Assist</span><strong><u>Fit Lab</u></strong><br><em>Size logic auto-suggests.</em>',
                    '<span>Rhythm</span><strong>Live stock</strong><br><em>Inventory bar pulses when low.</em>'
                ]
            ],
            'footer' => 'Every SKU feels editorial yet actionable, keeping discovery playful.'
        ],
        'news' => [
            'title' => 'Newsroom Digest',
            'focus' => 'News',
            'intro' => 'mixes reportage with <strong>atelier gossip</strong>.',
            'highlight_rows' => [
                [
                    '<strong>Pulse</strong><br><em>Headlines animate with ticker energy.</em>',
                    '<strong>Depth</strong><br><span class="nav-tooltip-accent">Long-form</span> capsules use layered typography.',
                    '<strong>Evidence</strong><br><em>Pull-quotes float with italic + underline combos.</em>',
                    '<strong>Context</strong><br><span class="nav-tooltip-muted">Timeline chips keep continuity.</span>'
                ]
            ],
            'meta_rows' => [
                [
                    '<span>Action</span><strong>Read</strong><br><em>Tap to reveal backstage notes.</em>',
                    '<span>Media</span><strong><u>Video + still</u></strong><br><em>Autoplay muted loops.</em>',
                    '<span>Credibility</span><strong>Sources</strong><br><em>Inline badges cite labs, stylists.</em>',
                    '<span>Mood</span><strong>Editorial</strong><br><em>Warm neutrals hugging coral highlights.</em>'
                ]
            ],
            'footer' => 'The newsroom turns runway whispers into actionable insights within seconds.'
        ],
        'about' => [
            'title' => 'About Studio',
            'focus' => 'About',
            'intro' => 'celebrates origin stories with <strong>museum-level care</strong>.',
            'highlight_rows' => [
                [
                    '<strong>Heritage</strong><br><em>Chronicles in gilded serif blocks.</em>',
                    '<strong>People</strong><br><span class="nav-tooltip-accent">Underline</span> highlights maker names.',
                    '<strong>Values</strong><br><em>Icon grid pairs bold titles with italic vows.</em>',
                    '<strong>Proof</strong><br><span class="nav-tooltip-muted">Timeline pins show awards + press.</span>'
                ]
            ],
            'meta_rows' => [
                [
                    '<span>Focus</span><strong>Culture</strong><br><em>Invite visitors into rituals.</em>',
                    '<span>Texture</span><strong><u>Paper Grain</u></strong><br><em>Soft background gradients mimic zines.</em>',
                    '<span>Social</span><strong>Loopback</strong><br><em>Sustainability stats in bold color.</em>',
                    '<span>CTA</span><strong>Visit</strong><br><em>Studio map glows on hover.</em>'
                ]
            ],
            'footer' => 'This section reads like a love letter with transparent receipts.'
        ],
        'qna' => [
            'title' => 'Q&A Concierge',
            'focus' => 'Q&A',
            'intro' => 'keeps knowledge bases <strong>lightweight and lyrical</strong>.',
            'highlight_rows' => [
                [
                    '<strong>Modules</strong><br><em>Accordion cards expand with eased motion.</em>',
                    '<strong>Trust</strong><br><span class="nav-tooltip-accent">Underline</span> marks policy keywords.</em>',
                    '<strong>Routing</strong><br><em>Suggested threads appear in muted color.</em>',
                    '<strong>Continuity</strong><br><span class="nav-tooltip-muted">Context chips follow user journey.</span>'
                ]
            ],
            'meta_rows' => [
                [
                    '<span>Action</span><strong>Ask</strong><br><em>Form auto-fills order data.</em>',
                    '<span>Assist</span><strong><u>Live agent</u></strong><br><em>Escalate in one tap.</em>',
                    '<span>Voice</span><strong>Reassuring</strong><br><em>Italic callouts calm urgency.</em>',
                    '<span>Docs</span><strong>Rich</strong><br><em>Links stack with icons + colors.</em>'
                ]
            ],
            'footer' => 'Guests feel heard instantly, even before the first sentence ends.'
        ],
        'cart' => [
            'title' => 'Cart Capsule',
            'focus' => 'Cart',
            'intro' => 'guards checkout momentum with <strong>spa-level clarity</strong>.',
            'highlight_rows' => [
                [
                    '<strong>Totals</strong><br><em>Animated underline emphasizes savings.</em>',
                    '<strong>Coupons</strong><br><span class="nav-tooltip-accent">Inline table</span> lists eligible codes.</em>',
                    '<strong>Shipping</strong><br><em>Timeline row shows arrival promise.</em>',
                    '<strong>Support</strong><br><span class="nav-tooltip-muted">Chat + hotline anchored below.</span>'
                ]
            ],
            'meta_rows' => [
                [
                    '<span>Action</span><strong>Checkout</strong><br><em>Primary CTA stretches full width.</em>',
                    '<span>Trust</span><strong><u>SSL + badges</u></strong><br><em>Icons tinted sage.</em>',
                    '<span>Assist</span><strong>Split Pay</strong><br><em>Toggle surfaces financing copy.</em>',
                    '<span>Continuity</span><strong>Cart memory</strong><br><em>Persist across devices.</em>'
                ]
            ],
            'footer' => 'Aromas of calm typography and bold numbers keep cart anxiety away.'
        ]
    ];

    $navTooltips = [];
    foreach ($navTooltipData as $key => $tooltipDatum) {
        $navTooltips[$key] = $buildTooltip($tooltipDatum);
    }

    $breadcrumbs = [
        ['label' => 'Home', 'url' => '/index.php']
    ];

    if ($currentController !== 'home') {
        $controllerLabel = $labelMap[$currentController] ?? ucfirst($currentController);
        $controllerUrl = "/index.php?controller={$currentController}&action=index";
        $breadcrumbs[] = ['label' => $controllerLabel, 'url' => $controllerUrl];
    }

    if ($currentAction !== 'index') {
        $actionLabel = $labelMap[$currentAction] ?? ucfirst(str_replace('-', ' ', $currentAction));
        $breadcrumbs[] = ['label' => $actionLabel, 'url' => null];
    }
    ?>
    <header class="site-header">
        <div class="header-glow"></div>
        <div class="container header-inner">
            <div class="logo">
                <a href="/index.php" class="logo-link">
                    <img src="<?php echo $logoUrl; ?>" alt="V.AShoes logo" loading="lazy">
                    <div>
                    <h1>V.AShoes</h1>
                        <span>curated footwear drops</span>
                    </div>
                </a>
            </div>
            <div class="header-actions">
                <button class="header-toggle" aria-label="Toggle navigation" aria-expanded="false">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
            <nav class="primary-nav">
                <ul>
                    <?php foreach ($navItems as $item): ?>
                        <?php
                        $isActive = false;
                        if ($item['key'] === 'home' && $currentController === 'home') {
                            $isActive = true;
                        } elseif ($item['key'] === 'cart' && $currentController === 'cart') {
                            $isActive = true;
                        } elseif ($item['key'] !== 'home' && $item['key'] !== 'cart' && $currentController === $item['key']) {
                            $isActive = true;
                        }
                        ?>
                        <li class="<?php echo $item['key'] === 'cart' ? 'nav-cart' : ''; ?>">
                            <a href="<?php echo $item['url']; ?>" class="<?php echo $isActive ? 'active' : ''; ?>">
                                <img src="<?php echo $item['icon']; ?>" alt="<?php echo $item['label']; ?> icon" loading="lazy">
                                <span><?php echo $item['label']; ?></span>
                                <?php if ($item['key'] === 'cart' && isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
                                <span class="cart-count"><?php echo count($_SESSION['cart']); ?></span>
                            <?php endif; ?>
                                <?php if (isset($navTooltips[$item['key']])): ?>
                                    <div class="nav-tooltip">
                                        <?php echo $navTooltips[$item['key']]; ?>
                                    </div>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'member'): ?>
                            <li>
                                <a href="/index.php?controller=account&action=index">
                                    <img src="https://cdn-icons-png.flaticon.com/128/1077/1077063.png" alt="Account icon" loading="lazy">
                                    <span>My Account</span>
                                </a>
                            </li>
                        <?php elseif (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                            <li>
                                <a href="/index.php?controller=admin&action=dashboard">
                                    <img src="https://cdn-icons-png.flaticon.com/128/1828/1828884.png" alt="Dashboard icon" loading="lazy">
                                    <span>Dashboard</span>
                                </a>
                            </li>
                        <?php endif; ?>
                        <li>
                            <a href="/index.php?controller=auth&action=logout" class="nav-btn nav-btn-outline">
                                Logout
                            </a>
                        </li>
                    <?php else: ?>
                        <li>
                            <a href="/index.php?controller=auth&action=login" class="nav-btn">
                                Login
                            </a>
                        </li>
                        <li>
                            <a href="/index.php?controller=auth&action=register" class="nav-btn nav-btn-outline">
                                Register
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const header = document.querySelector('.site-header');
            const toggle = document.querySelector('.header-toggle');
            if (!header || !toggle) {
                return;
            }
            toggle.addEventListener('click', function() {
                const isOpen = header.classList.toggle('nav-open');
                toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            });
        });
    </script>
    <div class="breadcrumb-wrap">
        <div class="container">
            <nav class="breadcrumb" aria-label="Breadcrumb">
                <?php foreach ($breadcrumbs as $index => $crumb): ?>
                    <?php if ($index > 0): ?>
                        <span class="breadcrumb-divider">/</span>
                    <?php endif; ?>
                    <?php if (!empty($crumb['url'])): ?>
                        <a href="<?php echo $crumb['url']; ?>"><?php echo htmlspecialchars($crumb['label']); ?></a>
                    <?php else: ?>
                        <span class="breadcrumb-current"><?php echo htmlspecialchars($crumb['label']); ?></span>
                    <?php endif; ?>
                <?php endforeach; ?>
            </nav>
        </div>
    </div>
    <main>
        <div class="container">
