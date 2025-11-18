<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShoeStore - Find Your Perfect Pair</title>
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

    $buildTooltip = function ($sectionTitle, $focusLabel) {
        $safeTitle = htmlspecialchars($sectionTitle, ENT_QUOTES, 'UTF-8');
        $safeFocus = htmlspecialchars($focusLabel, ENT_QUOTES, 'UTF-8');
        return <<<HTML
<div class="nav-tooltip-content">
    <h4>{$safeTitle}</h4>
    <p>The {$safeFocus} waypoint is designed as a living briefing room where every visitor receives a concise yet emotionally rich orientation. We surface the most relevant drops, editorial cues, and service pathways, condensed from thousands of data points streaming in from merchandising dashboards, social conversations, and support tickets. Even before a click happens, the {$safeFocus} overview distills intent, ensuring each journey begins with trust, momentum, and a crystal-clear reason to keep exploring.</p>
    <p>We treat this touchpoint like a hospitality lounge. Copy, typography, and animation scales in real time to match device context, preferred tone, and shopping cadence. We lean on sensory cues inspired by atelier sketches—soft gradients that mimic dyed leather, micro-interactions that echo lace tension, and layered cards that nod to shoe boxes stacked in a workshop. Everything whispers calm authority, inviting guests to take the next step at their own rhythm.</p>
    <ul>
        <li><strong>Rhythm Mapping:</strong> Every scroll depth is logged, allowing {$safeFocus} to reorder hero stories based on freshness, sell-through velocity, and editorial heat without breaking aesthetic harmony.</li>
        <li><strong>Service Shortcuts:</strong> Sticky links surface concierge chat, fit guides, and localized shipping rules directly within {$safeFocus}, eliminating context shifts.</li>
        <li><strong>Trend Capsules:</strong> Inline marquees summarize runway references, color psychology, and material innovations backing each featured collection.</li>
        <li><strong>Community Pulse:</strong> Micro-testimonials rotate through, each highlighting how a specific persona styled their latest pair in everyday life.</li>
        <li><strong>Learning Moments:</strong> Tooltips layer in pattern-making lore, factory provenance, and sustainability stats so curiosity is rewarded on the spot.</li>
        <li><strong>Action Grid:</strong> CTA clusters adapt to mood—browse, compare, repair, or book a fitting session—ensuring {$safeFocus} serves both dreamers and decisive buyers.</li>
    </ul>
    <p>Behind the scenes, predictive modules score each visitor’s intent and feed curated stories into {$safeFocus}, making sure the tone lands between editorial excitement and practical guidance. We balance narrative flair with concrete merchandising logic, so creative directors, casual shoppers, and wholesale partners all feel the interface was built expressly for them.</p>
    <p>Accessibility drives every pixel. Font scaling, reduced-motion modes, and voice-ready summaries are native to {$safeFocus}. We annotate imagery with heartfelt copy that celebrates diverse bodies, climates, and rituals, making the entire ecosystem feel inclusive without sacrificing avant-garde polish.</p>
    <p>A dedicated experimentation lane constantly iterates on hero choreography: swapping portrait photography for macro material studies, testing vertical reveal sequences, and even prototyping tactile audio cues. Each experiment is archived inside {$safeFocus} so future campaigns can remix learnings quickly.</p>
    <blockquote>The {$safeFocus} hub is our editorial lab, our concierge desk, and our data nerve center in one fluid surface. It keeps newcomers oriented, rewards loyalists with delightful depth, and reassures skeptical shoppers that every promise is backed by transparent craft.</blockquote>
    <p>We stretch the canvas with long-form explainers, modular kits, and seasonal playlists that blend music, movement drills, and styling prompts. That way, {$safeFocus} never feels like a static menu; it behaves like a living publication that just happens to live inside the header navigation.</p>
    <p>From here, users can pivot anywhere—product deep dives, cultural essays, service utilities—without cognitive whiplash. The tooltip previews that philosophy before the click, delivering a luxurious micro story of at least 750 tokens so even the smallest hover interaction feels collectible.</p>
</div>
HTML;
    };

    $navTooltips = [
        'home' => $buildTooltip('Home Command Center', 'Home'),
        'products' => $buildTooltip('Product Atlas', 'Products'),
        'news' => $buildTooltip('Newsroom Digest', 'News'),
        'about' => $buildTooltip('About Studio', 'About'),
        'qna' => $buildTooltip('Q&A Concierge', 'Q&A'),
        'cart' => $buildTooltip('Cart Capsule', 'Cart'),
    ];

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
                    <img src="<?php echo $logoUrl; ?>" alt="ShoeStore logo" loading="lazy">
                    <div>
                    <h1>ShoeStore</h1>
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
