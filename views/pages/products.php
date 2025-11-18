<?php require_once 'views/components/header.php'; ?>

<?php
$activeCategoryId = isset($_GET['category']) ? trim($_GET['category']) : '';
$activeCategory = null;
if (!empty($categories)) {
    foreach ($categories as $cat) {
        if ((string)$cat['CategoryID'] === (string)$activeCategoryId) {
            $activeCategory = $cat;
            break;
        }
    }
}

$truncateText = function($text, $limit = 120) {
    $clean = trim(strip_tags($text ?? ''));
    if ($clean === '') {
        return '';
    }

    if (function_exists('mb_strlen')) {
        if (mb_strlen($clean) <= $limit) {
            return $clean;
        }
        return rtrim(mb_substr($clean, 0, $limit - 3)) . '...';
    }

    if (strlen($clean) <= $limit) {
        return $clean;
    }

    return rtrim(substr($clean, 0, $limit - 3)) . '...';
};
?>

<div class="section-title">
    <?php if ($activeCategory): ?>
        <h2><?php echo htmlspecialchars($activeCategory['CategoryName']); ?></h2>
        <?php if (!empty($activeCategory['Description'])): ?>
            <p class="section-subtitle"><?php echo htmlspecialchars($activeCategory['Description']); ?></p>
        <?php endif; ?>
    <?php else: ?>
        <h2>Our Products</h2>
        <p class="section-subtitle">Browse every drop, curated for every style.</p>
    <?php endif; ?>
</div>

<!-- Filter & Search Section -->
<div class="products-filter-section">
    <div class="filter-wrapper">
        <!-- Category Filter -->
        <div class="category-filter">
            <label for="category-select" class="filter-label">
                <i class="fas fa-filter"></i> Category:
            </label>
            <div class="category-dropdown">
                <select id="category-select" name="category" class="category-select" onchange="filterByCategory(this.value)">
                    <option value="">All Categories</option>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat['CategoryID']); ?>" 
                                <?php echo (isset($_GET['category']) && $_GET['category'] == $cat['CategoryID']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat['CategoryName']); ?>
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
        </div>

        <!-- Search Form -->
        <div class="search-form">
            <form action="/index.php?controller=products&action=index" method="get" class="search-filter-form">
                <input type="hidden" name="controller" value="products">
                <input type="hidden" name="action" value="index">
                <?php if (!empty($_GET['category'])): ?>
                    <input type="hidden" name="category" value="<?php echo htmlspecialchars($_GET['category']); ?>">
                <?php endif; ?>
                <div class="search-input-wrapper">
                    <input type="text" name="keyword" placeholder="Search products..." 
                           value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>" 
                           class="search-input-field">
                    <button type="submit" class="search-btn-submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Clear Filters -->
        <?php if (!empty($_GET['category']) || !empty($_GET['keyword'])): ?>
            <a href="/index.php?controller=products&action=index" class="clear-filters-btn">
                <i class="fas fa-times"></i> Clear Filters
            </a>
        <?php endif; ?>
    </div>
</div>

<?php if (!empty($topSellers) || !empty($topPriced)): ?>
<section class="products-highlight-widgets">
    <div class="products-widget-grid">
        <?php if (!empty($topSellers)): ?>
            <div class="products-widget-card" style="background-image: url('https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=1200&q=80');">
                <div class="products-widget-card-inner">
                    <p class="products-widget-label">Top Seller</p>
                    <h3>Pairs flying off the shelves</h3>
                    <ul class="products-widget-items">
                        <?php foreach ($topSellers as $item): ?>
                            <?php
                            $thumb = '/public/placeholder.jpg';
                            if (!empty($item['image'])) {
                                if (filter_var($item['image'], FILTER_VALIDATE_URL)) {
                                    $thumb = $item['image'];
                                } elseif (file_exists($item['image'])) {
                                    $thumb = '/' . ltrim($item['image'], '/');
                                } else {
                                    $thumb = $item['image'];
                                }
                            }
                            $priceDisplay = isset($item['final_price']) ? $item['final_price'] : $item['price'];
                            ?>
                            <li>
                                <a href="/index.php?controller=products&action=detail&id=<?php echo $item['id']; ?>">
                                    <img src="<?php echo htmlspecialchars($thumb); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" loading="lazy">
                                    <div>
                                        <span><?php echo number_format($priceDisplay, 2); ?> $</span>
                                        <p><?php echo htmlspecialchars($item['name']); ?></p>
                                    </div>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>

        <?php if (!empty($topPriced)): ?>
            <div class="products-widget-card" style="background-image: url('https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=1200&q=80');">
                <div class="products-widget-card-inner">
                    <p class="products-widget-label">Top Price</p>
                    <h3>Craftsmanship at its peak</h3>
                    <ul class="products-widget-items">
                        <?php foreach ($topPriced as $item): ?>
                            <?php
                            $thumb = '/public/placeholder.jpg';
                            if (!empty($item['image'])) {
                                if (filter_var($item['image'], FILTER_VALIDATE_URL)) {
                                    $thumb = $item['image'];
                                } elseif (file_exists($item['image'])) {
                                    $thumb = '/' . ltrim($item['image'], '/');
                                } else {
                                    $thumb = $item['image'];
                                }
                            }
                            $priceDisplay = isset($item['final_price']) ? $item['final_price'] : $item['price'];
                            ?>
                            <li>
                                <a href="/index.php?controller=products&action=detail&id=<?php echo $item['id']; ?>">
                                    <img src="<?php echo htmlspecialchars($thumb); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" loading="lazy">
                                    <div>
                                        <span><?php echo number_format($priceDisplay, 2); ?> $</span>
                                        <p><?php echo htmlspecialchars($item['name']); ?></p>
                                    </div>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($categories)): ?>
<section class="products-category-widgets">
    <div class="products-category-card" style="background-image: url('https://images.unsplash.com/photo-1512436991641-6745cdb1723f?auto=format&fit=crop&w=1400&q=80');">
        <div class="products-category-card-inner">
            <p class="products-widget-label">Categories</p>
            <h3>Explore every product ecosystem</h3>
            <p>Choose the categories you care about; each comes with its own story and curated collection.</p>
            <div class="products-category-chip-list">
                <?php foreach ($categories as $cat): ?>
                    <a class="products-category-chip" href="/index.php?controller=products&action=index&category=<?php echo urlencode($cat['CategoryID']); ?>">
                        <span class="chip-title"><?php echo htmlspecialchars($cat['CategoryName']); ?></span>
                        <span class="chip-desc"><?php echo htmlspecialchars($cat['Description'] ?? ''); ?></span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<?php if (!empty($productSlides)): ?>
<section class="products-slider" data-slider>
    <div class="slider-header">
        <div>
            <p class="slider-eyebrow">Curated streams</p>
            <h3>Swipe through the latest product stories</h3>
            <p>Each slide brings a different mood. Ride the flow.</p>
        </div>
        <div class="slider-actions">
            <button class="slider-nav" type="button" data-slider-prev aria-label="Previous slide">
                <i class="fas fa-arrow-left"></i>
            </button>
            <button class="slider-nav" type="button" data-slider-next aria-label="Next slide">
                <i class="fas fa-arrow-right"></i>
            </button>
        </div>
    </div>
    <div class="slider-shell">
        <div class="slider-track" data-slider-track>
            <?php foreach ($productSlides as $panelIndex => $slide): ?>
                <?php
                $panelCardCount = isset($slide['products']) ? count($slide['products']) : 0;
                $panelClass = 'slider-panel';
                if ($panelCardCount <= 2) {
                    $panelClass .= ' slider-panel-compact';
                }
                ?>
                <article class="<?php echo $panelClass; ?>" data-card-count="<?php echo $panelCardCount; ?>">
                    <header class="slider-panel-header">
                        <p class="slider-panel-eyebrow"><?php echo htmlspecialchars($slide['eyebrow']); ?></p>
                        <div>
                            <h3><?php echo htmlspecialchars($slide['title']); ?></h3>
                            <?php if (!empty($slide['description'])): ?>
                                <p><?php echo htmlspecialchars($slide['description']); ?></p>
                            <?php endif; ?>
                        </div>
                    </header>
                    <div class="slider-panel-grid">
                        <?php foreach ($slide['products'] as $item): ?>
                            <?php
                            $thumb = '/public/placeholder.jpg';
                            if (!empty($item['image'])) {
                                if (filter_var($item['image'], FILTER_VALIDATE_URL)) {
                                    $thumb = $item['image'];
                                } elseif (file_exists($item['image'])) {
                                    $thumb = '/' . ltrim($item['image'], '/');
                                } else {
                                    $thumb = $item['image'];
                                }
                            }
                            $finalPrice = isset($item['final_price']) ? (float)$item['final_price'] : (float)$item['price'];
                            $basePrice = isset($item['price']) ? (float)$item['price'] : $finalPrice;
                            $hasSale = $finalPrice < $basePrice;
                            $saleInfo = $item['sale'] ?? null;
                            $saleExpiryIso = !empty($saleInfo['ExpiresAt']) ? date('c', strtotime($saleInfo['ExpiresAt'])) : null;
                            $itemSnippet = $truncateText($item['description'] ?? '', 90);
                            $itemSize = !empty($item['shoes_size']) ? $item['shoes_size'] : null;
                            $itemCategory = !empty($item['category']) ? $item['category'] : ($slide['eyebrow'] ?? 'Collection');
                            $itemStock = isset($item['Stock']) ? (int)$item['Stock'] : 0;
                            $stockLabel = $itemStock > 0 ? $itemStock . ' in stock' : 'Out of stock';
                            $stockBadge = $itemStock > 0 ? 'stock-available' : 'stock-out';
                            ?>
                            <div class="slider-card">
                                <?php if ($hasSale): ?>
                                    <span class="sale-badge">Sale</span>
                                <?php endif; ?>
                                <a class="slider-card-media" href="/index.php?controller=products&action=detail&id=<?php echo $item['id']; ?>">
                                    <img src="<?php echo htmlspecialchars($thumb); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" loading="lazy">
                                </a>
                                <div class="slider-card-body">
                                    <div class="slider-card-top">
                                        <span class="slider-card-chip"><?php echo htmlspecialchars($itemCategory); ?></span>
                                        <?php if ($hasSale): ?>
                                            <span class="slider-card-chip sale">Sale</span>
                                        <?php endif; ?>
                                    </div>
                                    <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                                    <?php if (!empty($itemSnippet)): ?>
                                        <p class="slider-card-desc"><?php echo htmlspecialchars($itemSnippet); ?></p>
                                    <?php endif; ?>
                                    <div class="slider-card-meta">
                                        <span><i class="fas fa-layer-group"></i><?php echo htmlspecialchars($itemCategory); ?></span>
                                        <?php if (!empty($itemSize)): ?>
                                            <span><i class="fas fa-ruler-combined"></i><?php echo htmlspecialchars($itemSize); ?></span>
                                        <?php endif; ?>
                                        <span class="<?php echo $stockBadge; ?>"><i class="fas fa-box"></i><?php echo htmlspecialchars($stockLabel); ?></span>
                                    </div>
                                    <div class="slider-card-price">
                                        <?php if ($hasSale): ?>
                                            <span class="price-current">$<?php echo number_format($finalPrice, 2); ?></span>
                                            <span class="price-compare">$<?php echo number_format($basePrice, 2); ?></span>
                                        <?php else: ?>
                                            <span class="price-current">$<?php echo number_format($finalPrice, 2); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($hasSale && $saleExpiryIso): ?>
                                        <div class="sale-countdown" data-sale-expiry="<?php echo htmlspecialchars($saleExpiryIso); ?>"></div>
                                    <?php endif; ?>
                                    <a class="slider-card-link" href="/index.php?controller=products&action=detail&id=<?php echo $item['id']; ?>">
                                        Shop Now <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    </div>
    <?php if (count($productSlides) > 1): ?>
        <div class="slider-dots">
            <?php foreach ($productSlides as $dotIndex => $slide): ?>
                <button type="button" class="slider-dot <?php echo $dotIndex === 0 ? 'active' : ''; ?>" data-slider-dot="<?php echo $dotIndex; ?>"></button>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
<?php endif; ?>

<!-- Products Grid -->
<section class="all-products-section">
    <div class="all-products-header">
        <div>
            <p class="section-subtitle">All Products</p>
            <h3>Every pair currently available</h3>
        </div>
    </div>
    <div class="products">
        <?php if (empty($products)): ?>
            <p class="no-products">No products found.</p>
        <?php else: ?>
            <?php foreach ($products as $product): ?>
                <?php
                $productFinalPrice = isset($product['final_price']) ? (float)$product['final_price'] : (float)$product['price'];
                $productBasePrice = isset($product['price']) ? (float)$product['price'] : $productFinalPrice;
                $productHasSale = $productFinalPrice < $productBasePrice;
                $productSale = $product['sale'] ?? null;
                $productSaleExpiryIso = !empty($productSale['ExpiresAt']) ? date('c', strtotime($productSale['ExpiresAt'])) : null;
                ?>
                <div class="product-card">
                    <?php if ($productHasSale): ?>
                        <span class="sale-badge">Sale</span>
                    <?php endif; ?>
                    <div class="product-img">
                        <?php 
                        $imageUrl = '/public/placeholder.jpg';
                        if (!empty($product['image'])) {
                            $rawImage = trim($product['image']);
                            if (filter_var($rawImage, FILTER_VALIDATE_URL)) {
                                $imageUrl = $rawImage;
                            } else {
                                $resolvedPath = ltrim($rawImage, '/');
                                if (file_exists($resolvedPath)) {
                                    $imageUrl = '/' . $resolvedPath;
                                } else {
                                    $imageUrl = $rawImage;
                                }
                            }
                        }
                        $productSnippet = $truncateText($product['description'] ?? '', 120);
                        $productCategory = !empty($product['category']) ? $product['category'] : 'Uncategorized';
                        $productSize = !empty($product['shoes_size']) ? $product['shoes_size'] : null;
                        $productStock = isset($product['Stock']) ? (int)$product['Stock'] : 0;
                        $productStockLabel = $productStock > 0 ? $productStock . ' in stock' : 'Out of stock';
                        $productStockClass = $productStock > 0 ? 'stock-available' : 'stock-out';
                        ?>
                        <img src="<?php echo htmlspecialchars($imageUrl); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" loading="lazy">
                    </div>
                    <div class="product-info">
                        <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                        <?php if (!empty($productSnippet)): ?>
                            <p class="product-snippet"><?php echo htmlspecialchars($productSnippet); ?></p>
                        <?php endif; ?>
                        <div class="product-meta">
                            <span><i class="fas fa-layer-group"></i><?php echo htmlspecialchars($productCategory); ?></span>
                            <?php if (!empty($productSize)): ?>
                                <span><i class="fas fa-ruler-combined"></i><?php echo htmlspecialchars($productSize); ?></span>
                            <?php endif; ?>
                            <span class="<?php echo $productStockClass; ?>"><i class="fas fa-box"></i><?php echo htmlspecialchars($productStockLabel); ?></span>
                        </div>
                        <div class="price">
                            <?php if ($productHasSale): ?>
                                <span class="price-regular">$<?php echo number_format($productFinalPrice, 2); ?></span>
                                <span class="price-sale-strike">$<?php echo number_format($productBasePrice, 2); ?></span>
                            <?php else: ?>
                                <span class="price-regular">$<?php echo number_format($productBasePrice, 2); ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if ($productHasSale && $productSaleExpiryIso): ?>
                            <div class="sale-countdown" data-sale-expiry="<?php echo htmlspecialchars($productSaleExpiryIso); ?>"></div>
                        <?php endif; ?>
                        <a href="/index.php?controller=products&action=detail&id=<?php echo $product['id']; ?>" class="btn">View Details</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php if (!empty($products) && $totalPages > 1): ?>
        <div class="pagination modern">
            <?php
            $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
            $category = isset($_GET['category']) ? $_GET['category'] : '';
            $baseUrl = '/index.php?controller=products&action=index';
            if (!empty($keyword)) $baseUrl .= '&keyword=' . urlencode($keyword);
            if (!empty($category)) $baseUrl .= '&category=' . urlencode($category);
            $prevPage = $currentPage > 1 ? $currentPage - 1 : 1;
            $nextPage = $currentPage < $totalPages ? $currentPage + 1 : $totalPages;
            ?>
            <a href="<?php echo $baseUrl . '&page=' . $prevPage; ?>" 
               class="pagination-btn prev <?php echo ($currentPage === 1) ? 'disabled' : ''; ?>">
                <i class="fas fa-chevron-left"></i>
                <span>Previous</span>
            </a>

            <div class="pagination-numbers">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php if ($i == 1 || $i == $totalPages || ($i >= $currentPage - 1 && $i <= $currentPage + 1)): ?>
                        <a href="<?php echo $baseUrl . '&page=' . $i; ?>" 
                           class="pagination-number <?php echo ($currentPage === $i) ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php elseif ($i == $currentPage - 2 || $i == $currentPage + 2): ?>
                        <span class="pagination-ellipsis">...</span>
                    <?php endif; ?>
                <?php endfor; ?>
            </div>

            <a href="<?php echo $baseUrl . '&page=' . $nextPage; ?>" 
               class="pagination-btn next <?php echo ($currentPage === $totalPages) ? 'disabled' : ''; ?>">
                <span>Next</span>
                <i class="fas fa-chevron-right"></i>
            </a>
        </div>
    <?php endif; ?>
</section>

<script>
function filterByCategory(categoryId) {
    const url = new URL(window.location.href);
    if (categoryId) {
        url.searchParams.set('category', categoryId);
    } else {
        url.searchParams.delete('category');
    }
    url.searchParams.delete('page'); // Reset to page 1 when filtering
    window.location.href = url.toString();
}

document.addEventListener('DOMContentLoaded', function() {
    const slider = document.querySelector('[data-slider]');
    if (slider) {
        const track = slider.querySelector('[data-slider-track]');
        const panels = Array.from(track.children);
        const prevButton = slider.querySelector('[data-slider-prev]');
        const nextButton = slider.querySelector('[data-slider-next]');
        const dots = Array.from(slider.querySelectorAll('[data-slider-dot]'));
        let currentIndex = 0;
        let autoplayTimer = null;

        const updateSlider = () => {
            track.style.transform = `translateX(-${currentIndex * 100}%)`;
            dots.forEach((dot, index) => {
                dot.classList.toggle('active', index === currentIndex);
            });
        };

        const goToSlide = (index) => {
            if (!panels.length) {
                return;
            }
            if (index < 0) {
                currentIndex = panels.length - 1;
            } else if (index >= panels.length) {
                currentIndex = 0;
            } else {
                currentIndex = index;
            }
            updateSlider();
        };

        const handlePrev = () => goToSlide(currentIndex - 1);
        const handleNext = () => goToSlide(currentIndex + 1);

        prevButton?.addEventListener('click', handlePrev);
        nextButton?.addEventListener('click', handleNext);
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => goToSlide(index));
        });

        const stopAutoplay = () => {
            if (autoplayTimer) {
                clearInterval(autoplayTimer);
                autoplayTimer = null;
            }
        };

        const startAutoplay = () => {
            if (panels.length <= 1) {
                return;
            }
            stopAutoplay();
            autoplayTimer = setInterval(() => {
                goToSlide(currentIndex + 1);
            }, 8000);
        };

        slider.addEventListener('mouseenter', stopAutoplay);
        slider.addEventListener('mouseleave', startAutoplay);

        updateSlider();
        startAutoplay();
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

    initSaleCountdowns();
});
</script>

<?php require_once 'views/components/footer.php'; ?>