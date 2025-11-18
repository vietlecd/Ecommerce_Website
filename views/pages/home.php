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
                <div class="stat-number" data-count="10000">0</div>
                <div class="stat-label">Satisfied Customers</div>
            </div>
            <div class="stat-item">
                <div class="stat-number" data-count="5000">0</div>
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

<div id="chat-widget">
    <div id="chat-button" class="chat-button">
        <i class="fas fa-comments"></i>
        <span>Consultation</span>
    </div>
    <div id="chat-window" class="chat-window">
        <div class="chat-header">
            <h3>Product Consultation</h3>
            <button id="chat-close" class="chat-close">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="chat-body">
            <div id="chat-messages" class="chat-messages"></div>
            <div class="chat-input-container">
                <input type="text" id="chat-input" class="chat-input" placeholder="Enter your question..." />
                <button id="chat-send" class="chat-send">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
#chat-widget {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 1000;
}

.chat-button {
    background-color: #ff6b6b;
    color: white;
    padding: 15px 20px;
    border-radius: 50px;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    display: flex;
    align-items: center;
    gap: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.chat-button:hover {
    background-color: #ff5252;
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
}

.chat-button i {
    font-size: 20px;
}

.chat-window {
    position: absolute;
    bottom: 80px;
    right: 0;
    width: 380px;
    height: 600px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
    display: none;
    flex-direction: column;
    overflow: hidden;
    animation: slideUp 0.3s ease;
}

.chat-window.active {
    display: flex;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.chat-header {
    background-color: #ff6b6b;
    color: white;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.chat-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 600;
}

.chat-close {
    background: none;
    border: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
    padding: 5px;
    transition: transform 0.2s ease;
}

.chat-close:hover {
    transform: rotate(90deg);
}

.chat-body {
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.chat-messages {
    flex: 1;
    overflow-y: auto;
    padding: 15px;
    background: #f5f5f5;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.chat-messages::-webkit-scrollbar {
    width: 6px;
}

.chat-messages::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.chat-messages::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 3px;
}

.chat-messages::-webkit-scrollbar-thumb:hover {
    background: #999;
}

.message {
    max-width: 80%;
    padding: 10px 12px;
    border-radius: 12px;
    word-wrap: break-word;
}

.message.user {
    align-self: flex-end;
    background: #ff6b6b;
    color: white;
}

.message.bot {
    align-self: flex-start;
    background: white;
    color: #333;
    border: 1px solid #e0e0e0;
}

.chat-input-container {
    display: flex;
    padding: 10px;
    background: white;
    border-top: 1px solid #e0e0e0;
    gap: 8px;
}

.chat-input {
    flex: 1;
    padding: 10px 15px;
    border: 1px solid #e0e0e0;
    border-radius: 20px;
    outline: none;
    font-size: 14px;
}

.chat-input:focus {
    border-color: #ff6b6b;
}

.chat-send {
    padding: 10px 20px;
    background: #ff6b6b;
    color: white;
    border: none;
    border-radius: 20px;
    cursor: pointer;
    transition: background 0.3s;
}

.chat-send:hover {
    background: #ff5252;
}

.chat-send:disabled {
    background: #ccc;
    cursor: not-allowed;
}

.loading {
    display: inline-block;
    width: 12px;
    height: 12px;
    border: 2px solid #ccc;
    border-top-color: #ff6b6b;
    border-radius: 50%;
    animation: spin 0.6s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

@media (max-width: 768px) {
    .chat-window {
        width: calc(100vw - 40px);
        height: calc(100vh - 120px);
        bottom: 80px;
        right: 20px;
        left: 20px;
    }
    
    #chat-widget {
        bottom: 15px;
        right: 15px;
    }
    
    .chat-button {
        padding: 12px 16px;
        font-size: 14px;
    }
}
</style>

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

    const chatButton = document.getElementById('chat-button');
    const chatWindow = document.getElementById('chat-window');
    const chatClose = document.getElementById('chat-close');
    const chatInput = document.getElementById('chat-input');
    const chatSend = document.getElementById('chat-send');
    const chatMessages = document.getElementById('chat-messages');
    const webhookBaseUrl = 'index.php';
    
    if (!chatButton || !chatWindow || !chatClose || !chatInput || !chatSend || !chatMessages) return;
    
    chatButton.addEventListener('click', function(e) {
        e.stopPropagation();
        chatWindow.classList.toggle('active');
    });
    
    chatClose.addEventListener('click', function(e) {
        e.stopPropagation();
        chatWindow.classList.remove('active');
    });
    
    chatWindow.addEventListener('click', function(e) {
        e.stopPropagation();
    });
    
    document.addEventListener('click', function(event) {
        if (chatWindow.classList.contains('active')) {
            if (!event.target.closest('#chat-widget')) {
                chatWindow.classList.remove('active');
            }
            if (nextBtn) {
                nextBtn.addEventListener('click', () => scrollTrack('next'));
            }
        });
    }
    
    function addLoadingMessage() {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'message bot';
        messageDiv.id = 'loading-message';
        messageDiv.innerHTML = '<span class="loading"></span> Searching...';
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function removeLoadingMessage() {
        const loadingMsg = document.getElementById('loading-message');
        if (loadingMsg) {
            loadingMsg.remove();
        }
    }

    function renderProductResponse(data) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'message bot';
        
        let html = '<div style="font-size: 13px; line-height: 1.4; color: #333;">';
        
        if (data.p) {
            html += '<div style="margin-bottom: 8px; color: #555;">' + escapeHtml(data.p) + '</div>';
        }
        
        if (data.h2) {
            html += '<h2 style="margin: 8px 0; font-size: 16px; font-weight: 600; color: #ff6b6b;">' + escapeHtml(data.h2) + '</h2>';
        }
        
        if (data.items && Array.isArray(data.items)) {
            html += '<div style="display: flex; flex-direction: column; gap: 8px; margin-top: 10px;">';
            
            data.items.forEach(function(item) {
                html += '<div style="border: 1px solid #e0e0e0; border-radius: 6px; padding: 8px; background: #f9f9f9; display: flex; gap: 8px;">';
                
                if (item.img) {
                    html += '<div style="flex-shrink: 0; width: 60px; height: 60px; overflow: hidden; border-radius: 4px;">';
                    html += '<img src="' + escapeHtml(item.img) + '" alt="' + escapeHtml(item.h3 || '') + '" style="width: 100%; height: 100%; object-fit: cover;">';
                    html += '</div>';
                }
                
                html += '<div style="flex: 1; min-width: 0;">';
                
                if (item.h3) {
                    html += '<div style="font-weight: 600; font-size: 14px; margin-bottom: 4px; color: #333;">' + escapeHtml(item.h3) + '</div>';
                }
                
                if (item.desc) {
                    html += '<div style="font-size: 12px; color: #666; margin-bottom: 4px;">' + escapeHtml(item.desc) + '</div>';
                }
                
                html += '<div style="display: flex; gap: 8px; flex-wrap: wrap; font-size: 11px; color: #888; margin-bottom: 4px;">';
                if (item.price) {
                    html += '<span><strong style="color: #ff6b6b;">' + escapeHtml(item.price) + '</strong></span>';
                }
                if (item.size) {
                    html += '<span>Size: ' + escapeHtml(item.size) + '</span>';
                }
                if (item.stock) {
                    html += '<span>In Stock: ' + escapeHtml(item.stock) + '</span>';
                }
                html += '</div>';
                
                if (item.link) {
                    html += '<a href="' + escapeHtml(item.link) + '" target="_blank" style="display: inline-block; padding: 4px 8px; background: #ff6b6b; color: white; text-decoration: none; border-radius: 4px; font-size: 11px; margin-top: 4px;">View Details</a>';
                }
                
                html += '</div></div>';
            });
            
            html += '</div>';
        }
        
        if (data.note) {
            html += '<div style="margin-top: 10px; padding: 8px; background: #e8f4f8; border-radius: 4px; font-size: 12px; color: #555;">' + escapeHtml(data.note) + '</div>';
        }
        
        html += '</div>';
        
        messageDiv.innerHTML = html;
        chatMessages.appendChild(messageDiv);
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    function sendMessage() {
        const message = chatInput.value.trim();
        if (!message) return;
        
        addMessage(message, true);
        chatInput.value = '';
        chatSend.disabled = true;
        addLoadingMessage();
        
        const url = new URL(webhookBaseUrl, window.location.origin);
        url.searchParams.set('controller', 'chat');
        url.searchParams.set('action', 'api');
        url.searchParams.set('chatInput', message);
        
        console.log('Fetching URL:', url.toString());
        
        // Set timeout for fetch request (200 seconds)
        const controller = new AbortController();
        const timeoutId = setTimeout(() => controller.abort(), 200000); // 200 seconds
        
        fetch(url.toString(), { signal: controller.signal })
            .then(response => {
                clearTimeout(timeoutId);
                if (!response.ok) {
                    return response.text().then(text => {
                        throw new Error('HTTP error: ' + response.status);
                    });
                }
                return response.json();
            })
            .then(result => {
                removeLoadingMessage();
                chatSend.disabled = false;
                
                if (result.success && result.data) {
                    const data = result.data;
                    console.log('Full result:', result);
                    console.log('Data:', data);
                    console.log('Has p:', !!data.p);
                    console.log('Has items:', !!data.items);
                    console.log('Items is array:', Array.isArray(data.items));
                    
                    if (data.p && Array.isArray(data.items)) {
                        console.log('Rendering product response...');
                        renderProductResponse(data);
                    } else if (data.message) {
                        if (data.message === 'Workflow was started') {
                            addMessage('Processing your request, please wait...', false);
                        } else {
                            addMessage(data.message, false);
                        }
                    } else if (data.items && Array.isArray(data.items) && data.items.length > 0) {
                        console.log('Rendering product response (items only)...');
                        renderProductResponse(data);
                    } else if (data.p) {
                        addMessage(data.p, false);
                    } else {
                        console.log('No valid data to render');
                        addMessage('Sorry, I cannot find the information.', false);
                    }
                } else if (result.error) {
                    let errorMsg = 'Sorry, an error occurred.';
                    
                    if (result.message) {
                        errorMsg = result.message;
                        if (result.message.includes('not registered') || result.message.includes('webhook')) {
                            errorMsg = 'Webhook is not activated in n8n. Please check the configuration.';
                        }
                    } else if (result.error === 'API error' && result.response) {
                        try {
                            const apiError = JSON.parse(result.response);
                            if (apiError.message) {
                                errorMsg = apiError.message;
                                if (apiError.hint) {
                                    errorMsg += ' (' + apiError.hint + ')';
                                }
                            }
                        } catch (e) {
                            errorMsg = 'Error connecting to n8n API';
                        }
                    }
                    
                    addMessage(errorMsg, false);
                } else {
                    addMessage('Sorry, I cannot find the information.', false);
                }
            })
            .catch(error => {
                clearTimeout(timeoutId);
                removeLoadingMessage();
                chatSend.disabled = false;
                console.error('Error:', error);
                
                let errorMsg = 'Sorry, an error occurred.';
                if (error.name === 'AbortError') {
                    errorMsg = 'Request has timed out (200s timeout). Please try again.';
                } else if (error.message) {
                    if (error.message.includes('404')) {
                        errorMsg = 'Webhook is not activated. Please check n8n configuration.';
                    } else if (error.message.includes('NetworkError') || error.message.includes('Failed to fetch')) {
                        errorMsg = 'Cannot connect to server. Please check your network connection.';
                    } else {
                        errorMsg = 'Error: ' + error.message;
                    }
                }
                addMessage(errorMsg, false);
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

    const salePopupOverlay = document.getElementById('salePopupOverlay');
    const salePopupClose = document.getElementById('salePopupClose');
    const salePopupDismiss = document.getElementById('salePopupDismiss');

    function showSalePopup() {
        if (salePopupOverlay) {
            setTimeout(() => {
                salePopupOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            }, 1000);
        }
    }

    function hideSalePopup() {
        if (salePopupOverlay) {
            salePopupOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    if (salePopupOverlay) {
        if (salePopupClose) {
            salePopupClose.addEventListener('click', hideSalePopup);
        }
        if (salePopupDismiss) {
            salePopupDismiss.addEventListener('click', hideSalePopup);
        }
        salePopupOverlay.addEventListener('click', function(e) {
            if (e.target === salePopupOverlay) {
                hideSalePopup();
            }
        });
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && salePopupOverlay.classList.contains('active')) {
                hideSalePopup();
            }
        });
        showSalePopup();
    }

    // Stats counter animation
    function animateCounter(element) {
        const target = parseInt(element.getAttribute('data-count'));
        const duration = 2000; // 2 seconds
        const increment = target / (duration / 16); // 60fps
        let current = 0;

        const updateCounter = () => {
            current += increment;
            if (current < target) {
                element.textContent = Math.floor(current).toLocaleString('vi-VN');
                requestAnimationFrame(updateCounter);
            } else {
                element.textContent = target.toLocaleString('vi-VN');
            }
        };

        updateCounter();
    }

    // Intersection Observer for stats animation
    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const statNumbers = entry.target.querySelectorAll('.stat-number');
                statNumbers.forEach(stat => {
                    if (stat.textContent === '0') {
                        animateCounter(stat);
                    }
                });
                statsObserver.unobserve(entry.target);
            }
        });
    }, { threshold: 0.5 });

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

