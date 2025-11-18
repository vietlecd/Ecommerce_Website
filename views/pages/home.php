<?php
$heroImages = [
    'https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=2000&q=80',
    'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=2000&q=80',
    'https://images.unsplash.com/photo-1465453869711-7e174808ace9?auto=format&fit=crop&w=2000&q=80',
    'https://images.unsplash.com/photo-1503341455253-b2e723bb3dbb?auto=format&fit=crop&w=2000&q=80',
    'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?auto=format&fit=crop&w=2000&q=80',
    'https://images.unsplash.com/photo-1503341338985-c0477be52513?auto=format&fit=crop&w=2000&q=80',
    'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=2000&q=80'
];
?>
<section class="hero-slider" data-hero-slider>
    <?php foreach ($heroImages as $index => $image): ?>
        <div class="hero-slide <?php echo $index === 0 ? 'active' : ''; ?>" style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('<?php echo $image; ?>');">
            <div class="hero-content">
                <h1>Step into Style</h1>
                <p>Discover the perfect pair of shoes for every occasion. From casual to formal, we've got you covered.</p>
                <div class="hero-cta-group">
                    <a href="/index.php?controller=products&action=index" class="hero-btn hero-btn-primary">
                        <span>Our Product</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="/index.php?controller=about&action=index" class="hero-btn hero-btn-ghost">
                        <span>About Us</span>
                        <i class="fas fa-compass"></i>
                    </a>
                    <a href="/index.php?controller=news&action=index" class="hero-btn hero-btn-outline">
                        <span>News</span>
                        <i class="fas fa-newspaper"></i>
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <div class="hero-slider-dots">
        <?php foreach ($heroImages as $index => $image): ?>
            <button class="<?php echo $index === 0 ? 'active' : ''; ?>" data-hero-dot="<?php echo $index; ?>" aria-label="Go to slide <?php echo $index + 1; ?>"></button>
        <?php endforeach; ?>
    </div>
</section>

<!-- About/Introduction Section -->
<section class="intro-section">
    <div class="container">
        <div class="intro-content">
            <div class="intro-text">
                <h2>Welcome to ShoeStore</h2>
                <p class="intro-subtitle">Where You Find the Perfect Pair of Shoes</p>
                <p class="intro-description">
                    ShoeStore is proud to be the leading provider of high-quality sports shoes, casual shoes, and formal footwear. 
                    With over 10 years of experience in the industry, we are committed to bringing customers authentic products, 
                    diverse styles and the most competitive prices on the market.
                </p>
                <div class="intro-features">
                    <div class="feature-item">
                        <i class="fas fa-check-circle"></i>
                        <span>100% Authentic Products</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-shipping-fast"></i>
                        <span>Fast Shipping</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-undo"></i>
                        <span>Easy Returns</span>
                    </div>
                    <div class="feature-item">
                        <i class="fas fa-headset"></i>
                        <span>24/7 Support</span>
                    </div>
                </div>
                <a href="/index.php?controller=about&action=index" class="btn btn-outline">Learn More</a>
            </div>
            <div class="intro-image">
                <img src="/assets/images/shoes_store.jpg" alt="ShoeStore Introduction">
            </div>
        </div>
    </div>
</section>

<!-- Stats/Testimonials Section -->
<section class="stats-section">
    <div class="container">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-number" data-count="10000" data-animate="true" data-speed="slow" data-loop="true">0</div>
                <div class="stat-label">Satisfied Customers</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" data-count="5000" data-animate="true" data-speed="fast" data-loop="true">0</div>
                <div class="stat-label">Products Sold</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" data-count="50">0</div>
                <div class="stat-label">Partner Brands</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" data-count="10">0</div>
                <div class="stat-label">Years of Experience</div>
            </div>
        </div>
    </div>
</section>

<!-- Features/Benefits Section -->
<section class="features-section">
    <div class="container">
        <div class="section-title">
            <h2>Why Choose ShoeStore?</h2>
        </div>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-award"></i>
                </div>
                <h3>Top Quality</h3>
                <p>Products carefully selected from the world's most trusted brands</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <h3>Reasonable Prices</h3>
                <p>Commitment to the best market prices with many attractive promotional programs</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Dedicated Service</h3>
                <p>Professional staff ready to advise and support customers</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Official Warranty</h3>
                <p>Complete warranty policy, free returns within the first 30 days</p>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<?php if (!empty($categories)): ?>
<section class="categories-section">
    <div class="container">
        <div class="section-title">
            <h2>Product Categories</h2>
            <p>Quick shortcuts into every curated universe we host.</p>
        </div>
        <div class="categories-widget-grid">
            <?php foreach ($categories as $category): ?>
                <a href="/index.php?controller=products&action=index&category=<?php echo urlencode($category['CategoryID']); ?>" class="category-widget-card">
                    <div class="category-widget-header">
                        <div class="category-widget-icon">
                            <?php if (!empty($category['ImageUrl'])): ?>
                                <img src="<?php echo htmlspecialchars($category['ImageUrl']); ?>" alt="<?php echo htmlspecialchars($category['CategoryName']); ?> icon" loading="lazy">
                            <?php else: ?>
                                <i class="fas fa-shoe-prints"></i>
                            <?php endif; ?>
                        </div>
                        <span class="category-widget-link">Shop now <i class="fas fa-arrow-right"></i></span>
                    </div>
                    <div class="category-widget-body">
                        <h3><?php echo htmlspecialchars($category['CategoryName']); ?></h3>
                        <p><?php echo !empty($category['Description']) ? htmlspecialchars($category['Description']) : 'Discover the newest arrivals in this category.'; ?></p>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($highDiscountSales)): ?>
<section class="mega-sale-section">
    <div class="container">
        <div class="section-title">
            <h2>Flash Sales Above <span class="sale-highlight"><i class="fas fa-fire"></i>50%</span></h2>
            <p>Tap through the hero discounts before they disappear.</p>
        </div>
        <div class="mega-sale-slider" data-sale-slider>
            <button class="sale-slider-nav prev" type="button" data-sale-prev aria-label="Previous sale">
                <i class="fas fa-chevron-left"></i>
            </button>
            <div class="mega-sale-track" data-sale-track>
                <?php foreach ($highDiscountSales as $saleProduct): ?>
                    <?php
                    $saleInfo = $saleProduct['sale'] ?? null;
                    $saleExpiryIso = !empty($saleInfo['ExpiresAt']) ? date('c', strtotime($saleInfo['ExpiresAt'])) : null;
                    $discountLabel = !empty($saleInfo['DiscountPercent']) ? number_format($saleInfo['DiscountPercent'], 0) . '% off' : 'On sale';
                    ?>
                    <article class="sale-slide-card">
                        <div class="sale-slide-media">
                            <img src="<?php echo htmlspecialchars($saleProduct['image'] ?? '/public/placeholder.jpg'); ?>" alt="<?php echo htmlspecialchars($saleProduct['name']); ?>" loading="lazy">
                            <span class="sale-chip"><?php echo htmlspecialchars($discountLabel); ?></span>
                        </div>
                        <div class="sale-slide-body">
                            <h3><?php echo htmlspecialchars($saleProduct['name']); ?></h3>
                            <p><?php echo htmlspecialchars(mb_strimwidth($saleProduct['description'] ?? '', 0, 90, '...')); ?></p>
                            <div class="sale-slide-pricing">
                                <span class="sale-price">$<?php echo number_format($saleProduct['final_price'], 2); ?></span>
                                <span class="sale-base">$<?php echo number_format($saleProduct['price'], 2); ?></span>
                            </div>
                            <?php if ($saleExpiryIso): ?>
                                <div class="sale-countdown" data-sale-expiry="<?php echo htmlspecialchars($saleExpiryIso); ?>"></div>
                            <?php endif; ?>
                            <a class="btn btn-outline" href="/index.php?controller=products&action=detail&id=<?php echo $saleProduct['id']; ?>">View Offer</a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
            <button class="sale-slider-nav next" type="button" data-sale-next aria-label="Next sale">
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($weeklySales)): ?>
<section class="weekly-sale-section">
    <div class="container">
        <div class="section-title">
            <h2>Expiring This Week</h2>
            <p>Countdown-ready offers closing in the next few days.</p>
        </div>
        <div class="weekly-sale-grid">
            <?php foreach ($weeklySales as $weeklyProduct): ?>
                <?php
                $weeklySale = $weeklyProduct['sale'] ?? null;
                $weeklyExpiryIso = !empty($weeklySale['ExpiresAt']) ? date('c', strtotime($weeklySale['ExpiresAt'])) : null;
                ?>
                <article class="weekly-sale-card">
                    <div class="weekly-sale-info">
                        <span class="weekly-sale-tag"><?php echo !empty($weeklySale['DiscountPercent']) ? number_format($weeklySale['DiscountPercent'], 0) . '% off' : 'Sale'; ?></span>
                        <h4><?php echo htmlspecialchars($weeklyProduct['name']); ?></h4>
                        <p>$<?php echo number_format($weeklyProduct['final_price'], 2); ?> <span>$<?php echo number_format($weeklyProduct['price'], 2); ?></span></p>
                        <?php if ($weeklyExpiryIso): ?>
                            <div class="sale-countdown compact" data-sale-expiry="<?php echo htmlspecialchars($weeklyExpiryIso); ?>"></div>
                        <?php endif; ?>
                    </div>
                    <a href="/index.php?controller=products&action=detail&id=<?php echo $weeklyProduct['id']; ?>" class="weekly-sale-link">
                        <span>Shop</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Featured Products Section -->
<section class="featured-products">
    <div class="container">
        <div class="section-title">
            <h2>Featured Products</h2>
        </div>
        <?php if (empty($featuredProducts)): ?>
            <p style="text-align: center; color: #777;">No featured products available.</p>
        <?php else: ?>
            <div class="featured-products-slider-wrapper">
                <button class="slider-nav-btn featured-slider-prev" aria-label="Previous products">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <div class="featured-products-slider">
                    <?php foreach ($featuredProducts as $product): ?>
                        <?php
                        $descriptionSnippet = '';
                        if (!empty($product['description'])) {
                            $descriptionSnippet = mb_strimwidth(strip_tags($product['description']), 0, 110, '...');
                        }
                        $productImage = !empty($product['image']) ? $product['image'] : '/placeholder.svg?height=200&width=300';
                        $featuredFinalPrice = isset($product['final_price']) ? (float)$product['final_price'] : (float)$product['price'];
                        $featuredBasePrice = isset($product['price']) ? (float)$product['price'] : $featuredFinalPrice;
                        $featuredHasSale = $featuredFinalPrice < $featuredBasePrice;
                        $featuredSale = $product['sale'] ?? null;
                        $featuredExpiryIso = !empty($featuredSale['ExpiresAt']) ? date('c', strtotime($featuredSale['ExpiresAt'])) : null;
                        ?>
                        <div class="product-card">
                            <?php if ($featuredHasSale): ?>
                                <span class="sale-badge">Sale</span>
                            <?php endif; ?>
                            <div class="product-img">
                                <img src="<?php echo htmlspecialchars($productImage); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            </div>
                            <div class="product-info">
                                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                                <?php if (!empty($descriptionSnippet)): ?>
                                    <p class="product-snippet"><?php echo htmlspecialchars($descriptionSnippet); ?></p>
                                <?php endif; ?>
                                <?php if (!empty($product['shoes_size']) || isset($product['Stock'])): ?>
                                    <div class="product-meta">
                                        <?php if (!empty($product['shoes_size'])): ?>
                                            <span><i class="fas fa-ruler"></i> Size: <?php echo htmlspecialchars($product['shoes_size']); ?></span>
                                        <?php endif; ?>
                                        <?php if (isset($product['Stock'])): ?>
                                            <span><i class="fas fa-box"></i> Stock: <?php echo $product['Stock'] > 0 ? $product['Stock'] : 'Out'; ?></span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                <div class="price">
                                    <?php if ($featuredHasSale): ?>
                                        <span class="price-regular">$<?php echo number_format($featuredFinalPrice, 2); ?></span>
                                        <span class="price-sale-strike">$<?php echo number_format($featuredBasePrice, 2); ?></span>
                                    <?php else: ?>
                                        <span class="price-regular">$<?php echo number_format($featuredBasePrice, 2); ?></span>
                                    <?php endif; ?>
                                </div>
                                <?php if ($featuredHasSale && $featuredExpiryIso): ?>
                                    <div class="sale-countdown" data-sale-expiry="<?php echo htmlspecialchars($featuredExpiryIso); ?>"></div>
                                <?php endif; ?>
                                <a href="/index.php?controller=products&action=detail&id=<?php echo $product['id']; ?>" class="btn">View Details</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button class="slider-nav-btn featured-slider-next" aria-label="Next products">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Widgets Section -->
<section class="widgets-section">
    <div class="container">
        <div class="widgets-grid">
            <!-- Latest News Widget -->
            <div class="widget-card">
                <div class="widget-header">
                    <h3><i class="fas fa-newspaper"></i> Latest News</h3>
                    <a href="/index.php?controller=news&action=index" class="widget-more">View All <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="widget-content">
                    <?php if (!empty($latestNews)): ?>
                        <?php foreach ($latestNews as $news): ?>
                            <div class="news-widget-item">
                                <div class="news-widget-image">
                                    <?php
                                    $newsThumb = '/assets/images/placeholder.png';
                                    if (!empty($news['thumbnail'])) {
                                        if (filter_var($news['thumbnail'], FILTER_VALIDATE_URL)) {
                                            $newsThumb = $news['thumbnail'];
                                        } elseif (file_exists($news['thumbnail'])) {
                                            $newsThumb = '/' . ltrim($news['thumbnail'], '/');
                                        }
                                    }
                                    ?>
                                    <?php if (!empty($newsThumb)): ?>
                                        <img src="<?php echo htmlspecialchars($newsThumb); ?>" alt="<?php echo htmlspecialchars($news['Title'] ?? ''); ?>" loading="lazy">
                                    <?php else: ?>
                                        <div class="news-placeholder"><i class="fas fa-newspaper"></i></div>
                                    <?php endif; ?>
                                </div>
                                <div class="news-widget-info">
                                    <h4><a href="/index.php?controller=news&action=detail&id=<?php echo $news['NewsID'] ?? 0; ?>"><?php echo htmlspecialchars($news['Title'] ?? 'Untitled'); ?></a></h4>
                                    <p class="news-date">
                                        <i class="far fa-calendar"></i> 
                                        <?php echo !empty($news['DateCreated']) ? date('d/m/Y', strtotime($news['DateCreated'])) : date('d/m/Y'); ?>
                                    </p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="no-news">No news available</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Categories Widget -->
            <div class="widget-card">
                <div class="widget-header">
                    <h3><i class="fas fa-th-large"></i> Product Categories</h3>
                    <a href="/index.php?controller=products&action=index" class="widget-more">View All <i class="fas fa-arrow-right"></i></a>
                </div>
                <div class="widget-content">
                    <?php if (!empty($categories)): ?>
                        <div class="categories-list">
                            <?php foreach (array_slice($categories, 0, 6) as $category): ?>
                                <a href="/index.php?controller=products&action=index&category=<?php echo urlencode($category['CategoryID']); ?>" class="category-link">
                                    <i class="fas fa-chevron-right"></i>
                                    <?php echo htmlspecialchars($category['CategoryName']); ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="no-categories">No categories available</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Search Widget -->
            <div class="widget-card">
                <div class="widget-header">
                    <h3><i class="fas fa-search"></i> Search Products</h3>
                </div>
                <div class="widget-content">
                    <form action="/index.php?controller=products&action=index" method="get" class="search-widget-form">
                        <input type="hidden" name="controller" value="products">
                        <input type="hidden" name="action" value="index">
                        <div class="search-input-group">
                            <input type="text" name="keyword" placeholder="Enter product name..." class="search-input">
                            <button type="submit" class="search-btn">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                    <div class="search-tags">
                        <span class="tag-label">Popular searches:</span>
                        <a href="/index.php?controller=products&action=index&keyword=Nike" class="search-tag">Nike</a>
                        <a href="/index.php?controller=products&action=index&keyword=Adidas" class="search-tag">Adidas</a>
                        <a href="/index.php?controller=products&action=index&keyword=Running" class="search-tag">Running</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- <section class="categories">
    <div class="section-title">
        <h2>Shop by Category</h2>
    </div>
    <div class="products">
        <//?php if (empty($categories)): ?>
            <p style="text-align: center; color: #777;">No categories available.</p>
        <//?php else: ?>
            <//?php foreach ($categories as $category): ?>
                <div class="product-card">
                    <div class="product-img">
                        <img src="/placeholder.svg?height=200&width=300" alt="<//?php echo htmlspecialchars($category['CategoryName']); ?>">
                    </div>
                    <div class="product-info">
                        <h3><//?php echo htmlspecialchars($category['CategoryName']); ?></h3>
                        <a href="/index.php?controller=products&action=index&category=<//?php echo urlencode($category['CategoryID']); ?>" class="btn">Shop Now</a>
                    </div>
                </div>
            <//?php endforeach; ?>
        <//?php endif; ?>
    </div>
</section> -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    const heroSlider = document.querySelector('[data-hero-slider]');
    if (heroSlider) {
        const slides = heroSlider.querySelectorAll('.hero-slide');
        const dots = heroSlider.querySelectorAll('[data-hero-dot]');
        let heroIndex = 0;
        let heroTimer;

        const setHeroSlide = (index) => {
            slides[heroIndex].classList.remove('active');
            dots[heroIndex].classList.remove('active');
            heroIndex = index;
            slides[heroIndex].classList.add('active');
            dots[heroIndex].classList.add('active');
        };

        const nextHeroSlide = () => {
            const nextIndex = (heroIndex + 1) % slides.length;
            setHeroSlide(nextIndex);
        };

        const startHeroAuto = () => {
            heroTimer = setInterval(nextHeroSlide, 5000);
        };

        const stopHeroAuto = () => {
            clearInterval(heroTimer);
        };

        dots.forEach((dot) => {
            dot.addEventListener('click', () => {
                const index = parseInt(dot.dataset.heroDot, 10);
                if (!isNaN(index) && index !== heroIndex) {
                    stopHeroAuto();
                    setHeroSlide(index);
                    startHeroAuto();
                }
            });
        });

        heroSlider.addEventListener('mouseenter', stopHeroAuto);
        heroSlider.addEventListener('mouseleave', startHeroAuto);
        startHeroAuto();
    }

    function initSaleCountdowns() {
        const countdowns = document.querySelectorAll('[data-sale-expiry]');
        if (!countdowns.length) return;

        const formatTime = (value) => value.toString().padStart(2, '0');

        const updateCountdowns = () => {
            countdowns.forEach((countdown) => {
                const expiry = countdown.getAttribute('data-sale-expiry');
                if (!expiry) return;
                const endTime = new Date(expiry).getTime();
                if (Number.isNaN(endTime)) {
                    countdown.textContent = 'Ending soon';
                    return;
                }
                const diff = endTime - Date.now();
                if (diff <= 0) {
                    countdown.textContent = 'Expired';
                    return;
                }
                const totalSeconds = Math.floor(diff / 1000);
                const days = Math.floor(totalSeconds / 86400);
                const hours = Math.floor((totalSeconds % 86400) / 3600);
                const minutes = Math.floor((totalSeconds % 3600) / 60);
                const seconds = totalSeconds % 60;
                countdown.textContent = `${formatTime(days)}:${formatTime(hours)}:${formatTime(minutes)}:${formatTime(seconds)}`;
            });
        };

        updateCountdowns();
        setInterval(updateCountdowns, 1000);
    }

    function initSaleSliders() {
        document.querySelectorAll('[data-sale-slider]').forEach((wrapper) => {
            const track = wrapper.querySelector('[data-sale-track]');
            if (!track) return;
            const prevBtn = wrapper.querySelector('[data-sale-prev]');
            const nextBtn = wrapper.querySelector('[data-sale-next]');

            const scrollTrack = (direction) => {
                const distance = track.clientWidth * 0.8 || 300;
                track.scrollBy({
                    left: direction === 'next' ? distance : -distance,
                    behavior: 'smooth'
                });
            };

            if (prevBtn) {
                prevBtn.addEventListener('click', () => scrollTrack('prev'));
            }
            if (nextBtn) {
                nextBtn.addEventListener('click', () => scrollTrack('next'));
            }
        });
    }

    initSaleCountdowns();
    initSaleSliders();

    // Stats counter animation
    function animateCounter(element) {
        const target = parseInt(element.getAttribute('data-count'), 10);
        if (Number.isNaN(target)) return;
        const speed = element.getAttribute('data-speed') || 'fast';
        const interval = speed === 'slow' ? 3000 : 15;
        const loop = element.hasAttribute('data-loop');
        const initialValue = target * 0.8;
        let current = parseInt(element.textContent.replace(/\D/g, ''), 10) || 0;

        const preload = () => {
            const bump = Math.round(initialValue / 8);
            current = Math.min(target, current + bump);
            element.textContent = current.toLocaleString('vi-VN');
            if (current < initialValue) {
                requestAnimationFrame(preload);
            } else {
                setTimeout(step, interval);
            }
        };

        const step = () => {
            if (!loop && current >= target) {
                element.textContent = target.toLocaleString('vi-VN');
                return;
            }
            current = loop && current >= target ? current + 1 : current + 1;
            element.textContent = current.toLocaleString('vi-VN');
            setTimeout(step, interval);
        };

        preload();
    }

    (function hydrateStaticStats() {
        document.querySelectorAll('.stat-number').forEach((stat) => {
            const target = parseInt(stat.getAttribute('data-count'), 10);
            if (Number.isNaN(target)) return;
            if (stat.hasAttribute('data-animate')) {
                stat.textContent = '0';
            } else {
                stat.textContent = target.toLocaleString('vi-VN');
            }
        });
    })();

    // Intersection Observer for stats animation
    const statsObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.querySelectorAll('.stat-number[data-animate]').forEach((stat) => {
                    if (!stat.dataset.animated) {
                        stat.dataset.animated = 'true';
                        animateCounter(stat);
                    }
                });
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.4 });

    const statsSection = document.querySelector('.stats-section');
    if (statsSection) {
        statsObserver.observe(statsSection);
    }

    const featuredSlider = document.querySelector('.featured-products-slider');
    const featuredPrevBtn = document.querySelector('.featured-slider-prev');
    const featuredNextBtn = document.querySelector('.featured-slider-next');

    if (featuredSlider && featuredPrevBtn && featuredNextBtn) {
        let isDown = false;
        let startX;
        let scrollLeft;

        const cardWidth = featuredSlider.querySelector('.product-card')?.offsetWidth || 280;
        const gap = 24;

        featuredPrevBtn.addEventListener('click', function() {
            featuredSlider.scrollBy({
                left: -(cardWidth + gap),
                behavior: 'smooth'
            });
        });

        featuredNextBtn.addEventListener('click', function() {
            featuredSlider.scrollBy({
                left: cardWidth + gap,
                behavior: 'smooth'
            });
        });

        featuredSlider.addEventListener('mousedown', function(e) {
            isDown = true;
            featuredSlider.style.cursor = 'grabbing';
            startX = e.pageX - featuredSlider.offsetLeft;
            scrollLeft = featuredSlider.scrollLeft;
        });

        featuredSlider.addEventListener('mouseleave', function() {
            isDown = false;
            featuredSlider.style.cursor = 'grab';
        });

        featuredSlider.addEventListener('mouseup', function() {
            isDown = false;
            featuredSlider.style.cursor = 'grab';
        });

        featuredSlider.addEventListener('mousemove', function(e) {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - featuredSlider.offsetLeft;
            const walk = (x - startX) * 2;
            featuredSlider.scrollLeft = scrollLeft - walk;
        });

        featuredSlider.addEventListener('touchstart', function(e) {
            isDown = true;
            startX = e.touches[0].pageX - featuredSlider.offsetLeft;
            scrollLeft = featuredSlider.scrollLeft;
        });

        featuredSlider.addEventListener('touchend', function() {
            isDown = false;
        });

        featuredSlider.addEventListener('touchmove', function(e) {
            if (!isDown) return;
            e.preventDefault();
            const x = e.touches[0].pageX - featuredSlider.offsetLeft;
            const walk = (x - startX) * 2;
            featuredSlider.scrollLeft = scrollLeft - walk;
        });

        function updateNavButtons() {
            const maxScroll = featuredSlider.scrollWidth - featuredSlider.clientWidth;
            featuredPrevBtn.style.opacity = featuredSlider.scrollLeft <= 0 ? '0.3' : '1';
            featuredPrevBtn.style.pointerEvents = featuredSlider.scrollLeft <= 0 ? 'none' : 'auto';
            featuredNextBtn.style.opacity = featuredSlider.scrollLeft >= maxScroll ? '0.3' : '1';
            featuredNextBtn.style.pointerEvents = featuredSlider.scrollLeft >= maxScroll ? 'none' : 'auto';
        }

        featuredSlider.addEventListener('scroll', updateNavButtons);
        updateNavButtons();

        window.addEventListener('resize', updateNavButtons);
    }
});
</script>

<?php require_once __DIR__ . '/../components/footer.php'; ?>

