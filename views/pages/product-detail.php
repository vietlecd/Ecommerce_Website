<?php require_once 'views/components/header.php'; ?>
<?php
// Helper chuẩn hóa đường dẫn ảnh giống trang products
$resolveProductImage = function (?string $imageValue): string {
    $placeholder = '/public/placeholder.jpg';

    if (empty($imageValue)) {
        return $placeholder;
    }

    if (filter_var($imageValue, FILTER_VALIDATE_URL)) {
        return $imageValue;
    }

    $normalized = ltrim($imageValue, '/');

    if (strpos($normalized, 'assets/') === 0 || strpos($normalized, 'public/') === 0) {
        return '/' . $normalized;
    }

    return '/assets/images/shoes/' . $normalized;
};

$formatCurrency = function ($value) {
    return '$' . number_format($value, 2);
};
$descriptionSnippet = !empty($product['description'])
    ? mb_strimwidth(strip_tags($product['description']), 0, 180, '...')
    : 'Heritage craftsmanship meets modern cushioning for round-the-clock wear.';
$categoryEyebrow = !empty($product['category']) ? $product['category'] . ' capsule' : 'Signature release';
$stockUnits = isset($product['Stock']) ? max(0, (int)$product['Stock']) : null;
$stockValue = $stockUnits !== null ? ($stockUnits > 0 ? $stockUnits . ' units ready' : 'Backorder window') : 'Ready to ship';
$stockContext = $stockUnits !== null ? ($stockUnits > 0 ? 'Ships within 24h' : 'Dispatch in 7 days') : 'Ships within 24h';
$sizeValue = !empty($product['sizes']) ? 'Size ' . ($product['size_summary'] ?? '') : 'Multi-size run';
$dateValue = !empty($product['DateCreate']) ? date('M d, Y', strtotime($product['DateCreate'])) : date('M d, Y');
$productMetrics = [
    [
        'label' => 'Inventory',
        'value' => $stockValue,
        'context' => $stockContext
    ],
    [
        'label' => 'Fit profile',
        'value' => $sizeValue,
        'context' => 'Concierge fitting available'
    ],
    [
        'label' => 'Drop date',
        'value' => $dateValue,
        'context' => 'Catalogued in atelier log'
    ]
];
$productServices = [
    [
        'icon' => 'fas fa-shipping-fast',
        'title' => 'Express shipping',
        'copy' => 'Nationwide delivery within 48 hours.'
    ],
    [
        'icon' => 'fas fa-undo',
        'title' => '30-day returns',
        'copy' => 'Complimentary pick-up for size swaps.'
    ],
    [
        'icon' => 'fas fa-shield-alt',
        'title' => 'Craft warranty',
        'copy' => '12-month coverage on stitching and hardware.'
    ]
];
$productFacts = [
    [
        'label' => 'Release ID',
        'value' => 'SS-' . str_pad((string)$product['id'], 5, '0', STR_PAD_LEFT)
    ],
    [
        'label' => 'Category',
        'value' => !empty($product['category']) ? $product['category'] : 'Lifestyle'
    ],
    [
        'label' => 'Available stock',
        'value' => $stockUnits !== null ? ($stockUnits > 0 ? $stockUnits . ' pairs' : 'Backorder open') : 'Limited run'
    ],
    [
        'label' => 'Care window',
        'value' => 'Complimentary cleaning within 90 days'
    ]
];
?>

<div class="product-detail-wrapper">

    <div class="product-detail">
        <div class="product-media-stack">
            <div class="product-detail-img">
                <?php
                $imageUrl = $resolveProductImage($product['image'] ?? null);
                ?>
                <img src="<?php echo htmlspecialchars($imageUrl); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" loading="lazy">
                <?php if (!empty($product['promotion'])): ?>
                    <div class="promotion-badge">
                        <?php if (!empty($product['promotion']['discount_percentage'])): ?>
                            -<?php echo number_format($product['promotion']['discount_percentage']); ?>%
                        <?php elseif (!empty($product['promotion']['fixed_price'])): ?>
                            Offer
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php if (!empty($product['sizes'])): ?>
                <div class="product-size-note">
                    <i class="fas fa-ruler"></i>
                    <span><?php echo htmlspecialchars($product['size_summary']); ?></span>
                </div>
            <?php endif; ?>
            <div class="product-highlight-metrics">
                <?php foreach ($productMetrics as $metric): ?>
                    <div class="product-metric-card">
                        <span class="product-metric-label"><?php echo htmlspecialchars($metric['label']); ?></span>
                        <strong><?php echo htmlspecialchars($metric['value']); ?></strong>
                        <p><?php echo htmlspecialchars($metric['context']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <div class="product-detail-info">
            <p class="product-eyebrow"><?php echo htmlspecialchars($categoryEyebrow); ?></p>
            <h1><?php echo htmlspecialchars($product['name']); ?></h1>
            <p class="product-subcopy"><?php echo htmlspecialchars($descriptionSnippet); ?></p>

            <div class="product-price-section">
                <?php if (isset($product['final_price']) && $product['final_price'] != $product['price']): ?>
                    <div class="price">
                        <span class="current-price"><?php echo $formatCurrency($product['final_price']); ?></span>
                        <span class="original-price"><?php echo $formatCurrency($product['price']); ?></span>
                        <?php 
                        $discount = (($product['price'] - $product['final_price']) / $product['price']) * 100;
                        ?>
                        <span class="discount-badge">-<?php echo number_format($discount, 0); ?>%</span>
                    </div>
                <?php else: ?>
                    <div class="price">
                        <span class="current-price"><?php echo $formatCurrency($product['price']); ?></span>
                    </div>
                <?php endif; ?>
                <p class="price-note">Tax included. Complimentary cleaning kit ships with every pair.</p>
            </div>

            <div class="product-meta-chips">
                <?php if (!empty($product['sizes'])): ?>
                    <span class="product-meta-chip">
                        <i class="fas fa-ruler"></i>
                        Size <?php echo htmlspecialchars($product['size_summary']); ?>
                    </span>
                <?php endif; ?>
                <span class="product-meta-chip <?php echo ($stockUnits !== null && $stockUnits <= 0) ? 'chip-low' : ''; ?>">
                    <i class="fas fa-box"></i>
                    <?php echo $stockUnits !== null ? ($stockUnits > 0 ? $stockUnits . ' in stock' : 'Backorder in queue') : 'Available to ship'; ?>
                </span>
                <span class="product-meta-chip">
                    <i class="far fa-calendar"></i>
                    <?php echo $dateValue; ?>
                </span>
            </div>

            <?php if (!empty($product['promotion'])): ?>
                <div class="promotion-info">
                    <i class="fas fa-gift"></i>
                    <div class="promotion-details">
                        <strong>Limited-time promotion:</strong>
                        <?php if (!empty($product['promotion']['discount_percentage'])): ?>
                            Save <?php echo number_format($product['promotion']['discount_percentage']); ?>%
                        <?php elseif (!empty($product['promotion']['fixed_price'])): ?>
                            Promo price: <?php echo $formatCurrency($product['promotion']['fixed_price']); ?>
                        <?php endif; ?>
                        <?php if (!empty($product['promotion']['end_date'])): ?>
                            <span class="promotion-end">(Ends <?php echo date('m/d/Y', strtotime($product['promotion']['end_date'])); ?>)</span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="product-service-grid">
                <?php foreach ($productServices as $service): ?>
                    <div class="product-service-card">
                        <div class="service-icon">
                            <i class="<?php echo $service['icon']; ?>"></i>
                        </div>
                        <div>
                            <h4><?php echo htmlspecialchars($service['title']); ?></h4>
                            <p><?php echo htmlspecialchars($service['copy']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <form method="post" action="" class="product-add-to-cart">
                <?php if (!empty($product['sizes'])): ?>
                    <div class="size-picker">
                        <label class="size-picker-label"><i class="fas fa-ruler"></i> Choose your size:</label>
                        <?php if (!empty($productAddError)): ?>
                            <div class="size-picker-error"><?php echo htmlspecialchars($productAddError); ?></div>
                        <?php endif; ?>
                        <div class="size-select-grid" data-size-picker>
                            <?php foreach ($product['sizes'] as $sizeOption): ?>
                                <?php
                                    $sizeLabel = $sizeOption['label'] ?? rtrim(rtrim(number_format((float)$sizeOption['size'], 2, '.', ''), '0'), '.');
                                    $sizeKey = rtrim(rtrim(number_format((float)$sizeOption['size'], 2, '.', ''), '0'), '.');
                                    $inStock = (int)$sizeOption['quantity'] > 0;
                                ?>
                                <button type="button"
                                        class="size-select-btn <?php echo $inStock ? '' : 'is-disabled'; ?>"
                                        data-size-value="<?php echo htmlspecialchars($sizeKey); ?>"
                                        data-size-label="<?php echo htmlspecialchars($sizeLabel); ?>"
                                        data-size-max="<?php echo (int)$sizeOption['quantity']; ?>"
                                        <?php echo $inStock ? '' : 'disabled'; ?>>
                                    <span>Size <?php echo htmlspecialchars($sizeLabel); ?></span>
                                    <small><?php echo $inStock ? $sizeOption['quantity'] . ' Pairs' : 'Out of stock'; ?></small>
                                </button>
                            <?php endforeach; ?>
                        </div>
                        <input type="hidden" name="selected_size" id="selected-size-input">
                    </div>
                <?php endif; ?>
                <div class="quantity-section">
                    <label for="quantity-input"><i class="fas fa-shopping-cart"></i> Quantity:</label>
                    <div class="quantity-selector">
                        <button type="button" class="quantity-btn quantity-minus" aria-label="Decrease quantity">
                            <i class="fas fa-minus"></i>
                        </button>
                        <input type="number" id="quantity-input" name="quantity" value="1" min="1" 
                               max="<?php echo isset($product['Stock']) && $product['Stock'] > 0 ? $product['Stock'] : 999; ?>" 
                               class="quantity-input" required>
                        <button type="button" class="quantity-btn quantity-plus" aria-label="Increase quantity">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                
                <?php
                    $isOutOfStock = isset($product['Stock']) && $product['Stock'] <= 0;
                    $requiresSize = !empty($product['sizes']) && !$isOutOfStock;
                ?>
                <button type="submit"
                        name="add_to_cart"
                        class="btn btn-add-cart"
                        data-add-cart
                        data-requires-size="<?php echo $requiresSize ? '1' : '0'; ?>"
                        data-default-disabled="<?php echo $isOutOfStock ? '1' : '0'; ?>"
                        <?php echo $isOutOfStock ? 'disabled' : ''; ?>>
                    <i class="fas fa-cart-plus"></i>
                    <?php echo $isOutOfStock ? 'Out of Stock' : 'Add to Cart'; ?>
                </button>
            </form>
        </div>
    </div>

    <section class="product-story-grid">
        <div class="product-description">
            <h3><i class="fas fa-info-circle"></i> Product Description</h3>
            <p><?php echo nl2br(htmlspecialchars($product['description'] ?? 'Detailed spec coming soon.')); ?></p>
        </div>
        <aside class="product-facts-panel">
            <h3>Drop essentials</h3>
            <ul class="product-fact-list">
                <?php foreach ($productFacts as $fact): ?>
                    <li>
                        <span><?php echo htmlspecialchars($fact['label']); ?></span>
                        <strong><?php echo htmlspecialchars($fact['value']); ?></strong>
                    </li>
                <?php endforeach; ?>
            </ul>
        </aside>
    </section>

    <?php if (!empty($relatedProducts)): ?>
        <section class="related-products-section">
            <div class="container">
                <div class="section-title">
                    <h2><i class="fas fa-th-large"></i> Related Products</h2>
                    <p>Discover more items from this category</p>
                </div>
                <div class="related-products-slider-wrapper">
                    <button class="slider-nav-btn slider-prev" aria-label="Previous product">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <div class="related-products-slider">
                        <?php foreach ($relatedProducts as $related): ?>
                            <?php
                            $relatedSnippet = '';
                            if (!empty($related['description'])) {
                                $relatedSnippet = mb_strimwidth(strip_tags($related['description']), 0, 110, '...');
                            }
                            $relatedFinalPrice = isset($related['final_price']) ? (float)$related['final_price'] : (float)$related['price'];
                            $relatedBasePrice = isset($related['price']) ? (float)$related['price'] : $relatedFinalPrice;
                            $relatedHasSale = $relatedFinalPrice < $relatedBasePrice;
                            $relatedSale = $related['sale'] ?? null;
                            $relatedSaleExpiryIso = !empty($relatedSale['ExpiresAt']) ? date('c', strtotime($relatedSale['ExpiresAt'])) : null;
                            ?>
                            <div class="product-card">
                                <?php if ($relatedHasSale): ?>
                                    <span class="sale-badge">Sale</span>
                                <?php endif; ?>
                                <div class="product-img">
                                    <?php 
                                    $relatedImageUrl = $resolveProductImage($related['image'] ?? null);
                                    ?>
                                    <img src="<?php echo htmlspecialchars($relatedImageUrl); ?>" alt="<?php echo htmlspecialchars($related['name']); ?>" loading="lazy">
                                    <?php if (!empty($related['promotion'])): ?>
                                        <div class="product-badge">
                                            <?php if (!empty($related['promotion']['discount_percentage'])): ?>
                                                -<?php echo number_format($related['promotion']['discount_percentage']); ?>%
                                            <?php else: ?>
                                                Offer
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="product-info">
                                    <h3><?php echo htmlspecialchars($related['name']); ?></h3>
                                    <?php if (!empty($relatedSnippet)): ?>
                                        <p class="product-snippet"><?php echo htmlspecialchars($relatedSnippet); ?></p>
                                    <?php endif; ?>
                                    <div class="product-meta">
                                        <?php if (!empty($related['category'])): ?>
                                            <span><i class="fas fa-tags"></i> <?php echo htmlspecialchars($related['category']); ?></span>
                                        <?php endif; ?>
                                        <?php if (!empty($related['sizes'])): ?>
                                            <span><i class="fas fa-ruler"></i> Size: <?php echo htmlspecialchars($related['size_summary']); ?></span>
                                        <?php endif; ?>
                                        <?php if (isset($related['Stock'])): ?>
                                            <span><i class="fas fa-box"></i> <?php echo $related['Stock'] > 0 ? $related['Stock'] . ' in stock' : 'Out of stock'; ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="price">
                                        <?php if ($relatedHasSale): ?>
                                            <span class="price-regular"><?php echo $formatCurrency($relatedFinalPrice); ?></span>
                                            <span class="price-sale-strike"><?php echo $formatCurrency($relatedBasePrice); ?></span>
                                        <?php else: ?>
                                            <span class="price-regular"><?php echo $formatCurrency($relatedBasePrice); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($relatedHasSale && $relatedSaleExpiryIso): ?>
                                        <div class="sale-countdown" data-sale-expiry="<?php echo htmlspecialchars($relatedSaleExpiryIso); ?>"></div>
                                    <?php endif; ?>
                                    <a href="/index.php?controller=products&action=detail&id=<?php echo $related['id']; ?>" class="btn">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <button class="slider-nav-btn slider-next" aria-label="Next product">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <section class="comments-section">
        <div class="container">
            <div class="section-title">
                <h2><i class="fas fa-comments"></i> Reviews & Ratings</h2>
                <?php if (!empty($ratingStats) && $ratingStats['totalComments'] > 0): ?>
                    <div class="rating-summary">
                        <div class="average-rating">
                            <span class="rating-value"><?php echo number_format($ratingStats['avgRating'], 1); ?></span>
                            <div class="stars">
                                <?php 
                                $avgRating = round($ratingStats['avgRating']);
                                for ($i = 1; $i <= 5; $i++): 
                                ?>
                                    <i class="fas fa-star <?php echo $i <= $avgRating ? 'active' : ''; ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <span class="total-comments">(<?php echo $ratingStats['totalComments']; ?> reviews)</span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="comment-form-wrapper">
                <h3><i class="fas fa-edit"></i> Write a Review</h3>
                
                <?php if (isset($_SESSION['comment_success'])): ?>
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_SESSION['comment_success']); ?>
                        <?php unset($_SESSION['comment_success']); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['comment_error'])): ?>
                    <div class="alert alert-error">
                        <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($_SESSION['comment_error']); ?>
                        <?php unset($_SESSION['comment_error']); ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="" class="comment-form">
                    <div class="form-group">
                        <label for="rating">Your Rating:</label>
                        <div class="rating-input">
                            <input type="radio" id="rating-5" name="rating" value="5" required>
                            <label for="rating-5" class="star-label"><i class="fas fa-star"></i></label>
                            <input type="radio" id="rating-4" name="rating" value="4" required>
                            <label for="rating-4" class="star-label"><i class="fas fa-star"></i></label>
                            <input type="radio" id="rating-3" name="rating" value="3" required>
                            <label for="rating-3" class="star-label"><i class="fas fa-star"></i></label>
                            <input type="radio" id="rating-2" name="rating" value="2" required>
                            <label for="rating-2" class="star-label"><i class="fas fa-star"></i></label>
                            <input type="radio" id="rating-1" name="rating" value="1" required>
                            <label for="rating-1" class="star-label"><i class="fas fa-star"></i></label>
                        </div>
                    </div>

                    <?php 
                    $isLoggedIn = isset($_SESSION['user_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'member';
                    ?>

                    <?php if ($isLoggedIn && $member): ?>
                        <div class="form-group">
                            <label>Name:</label>
                            <div class="user-name-display">
                                <i class="fas fa-user"></i>
                                <span><?php echo htmlspecialchars($member['Username'] ?? $member['Name'] ?? 'User'); ?></span>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="form-group">
                            <label for="guest_name">Your Name:</label>
                            <input type="text" id="guest_name" name="guest_name" class="form-control" 
                                   placeholder="Enter your name" required>
                        </div>
                    <?php endif; ?>

                    <div class="form-group">
                        <label for="content">Your Review:</label>
                        <textarea id="content" name="content" class="form-control" rows="5" 
                                  placeholder="Share your experience with this product..." required></textarea>
                    </div>

                    <button type="submit" name="submit_comment" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Submit Review
                    </button>
                </form>
            </div>

            <div class="comments-list">
                <h3><i class="fas fa-list"></i> Customer Reviews</h3>
                
                <?php if (empty($comments)): ?>
                    <div class="no-comments">
                        <i class="fas fa-comment-slash"></i>
                        <p>No reviews yet. Be the first to share your thoughts!</p>
                    </div>
                <?php else: ?>
                    <div class="comments-grid">
                        <?php foreach ($comments as $comment): ?>
                            <div class="comment-item">
                                <div class="comment-header">
                                    <div class="comment-author">
                                        <i class="fas fa-user-circle"></i>
                                        <div class="author-info">
                                            <strong>
                                                <?php 
                                                if (!empty($comment['Username'])) {
                                                    echo htmlspecialchars($comment['Username']);
                                                } elseif (!empty($comment['MemberName'])) {
                                                    echo htmlspecialchars($comment['MemberName']);
                                                } elseif (!empty($comment['GuestName'])) {
                                                    echo htmlspecialchars($comment['GuestName']);
                                                } else {
                                                    echo 'Customer';
                                                }
                                                ?>
                                            </strong>
                                            <?php if (!empty($comment['Mem_ID'])): ?>
                                                <span class="badge-member"><i class="fas fa-check-circle"></i> Verified buyer</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="comment-rating">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fas fa-star <?php echo $i <= $comment['Rating'] ? 'active' : ''; ?>"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                
                                <?php if (!empty($comment['Date'])): ?>
                                    <div class="comment-date">
                                        <i class="far fa-calendar"></i>
                                        <?php echo date('m/d/Y', strtotime($comment['Date'])); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($comment['Content'])): ?>
                                    <div class="comment-content">
                                        <?php echo nl2br(htmlspecialchars($comment['Content'])); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const quantityInput = document.querySelector('.quantity-input');
    const quantityMinus = document.querySelector('.quantity-minus');
    const quantityPlus = document.querySelector('.quantity-plus');
    let maxQuantity = quantityInput ? parseInt(quantityInput.getAttribute('max')) || 999 : 999;

    if (quantityMinus && !quantityMinus.dataset.bound) {
        quantityMinus.dataset.bound = '1';
        quantityMinus.addEventListener('click', function() {
            let value = parseInt(quantityInput.value);
            if (value > 1) {
                quantityInput.value = value - 1;
            }
        });
    }

    if (quantityPlus && !quantityPlus.dataset.bound) {
        quantityPlus.dataset.bound = '1';
        quantityPlus.addEventListener('click', function() {
            let value = parseInt(quantityInput.value);
            if (value < maxQuantity) {
                quantityInput.value = value + 1;
            }
        });
    }

    if (quantityInput && !quantityInput.dataset.bound) {
        quantityInput.dataset.bound = '1';
        quantityInput.addEventListener('change', function() {
            let value = parseInt(this.value);
            if (value < 1) {
                this.value = 1;
            } else if (value > maxQuantity) {
                this.value = maxQuantity;
            }
        });
    }

    const sizeButtons = document.querySelectorAll('[data-size-picker] .size-select-btn');
    const sizeInput = document.getElementById('selected-size-input');
    const addToCartButton = document.querySelector('[data-add-cart]');
    const requiresSize = addToCartButton && addToCartButton.getAttribute('data-requires-size') === '1';
    const defaultDisabled = addToCartButton && addToCartButton.getAttribute('data-default-disabled') === '1';

    if (addToCartButton && requiresSize && !defaultDisabled) {
        addToCartButton.disabled = true;
        addToCartButton.classList.add('btn-disabled');
    }

    if (sizeButtons.length && sizeInput) {
        sizeButtons.forEach(button => {
            button.addEventListener('click', function() {
                if (this.disabled) {
                    return;
                }
                sizeButtons.forEach(btn => btn.classList.remove('is-active'));
                this.classList.add('is-active');
                const value = this.getAttribute('data-size-value') || '';
                const sizeMax = parseInt(this.getAttribute('data-size-max')) || 1;
                sizeInput.value = value;
                maxQuantity = sizeMax;
                if (quantityInput) {
                    quantityInput.setAttribute('max', sizeMax);
                    let currentValue = parseInt(quantityInput.value) || 1;
                    if (currentValue > sizeMax) {
                        quantityInput.value = sizeMax;
                    }
                }
                if (addToCartButton && requiresSize && !defaultDisabled) {
                    addToCartButton.disabled = false;
                    addToCartButton.classList.remove('btn-disabled');
                }
            });
        });
    }

    const slider = document.querySelector('.related-products-slider');
    const prevBtn = document.querySelector('.slider-prev');
    const nextBtn = document.querySelector('.slider-next');

    if (slider && prevBtn && nextBtn) {
        let isDown = false;
        let startX;
        let scrollLeft;

        const cardWidth = slider.querySelector('.product-card')?.offsetWidth || 280;
        const gap = 24;

        prevBtn.addEventListener('click', function() {
            slider.scrollBy({
                left: -(cardWidth + gap),
                behavior: 'smooth'
            });
        });

        nextBtn.addEventListener('click', function() {
            slider.scrollBy({
                left: cardWidth + gap,
                behavior: 'smooth'
            });
        });

        slider.addEventListener('mousedown', function(e) {
            isDown = true;
            slider.style.cursor = 'grabbing';
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
        });

        slider.addEventListener('mouseleave', function() {
            isDown = false;
            slider.style.cursor = 'grab';
        });

        slider.addEventListener('mouseup', function() {
            isDown = false;
            slider.style.cursor = 'grab';
        });

        slider.addEventListener('mousemove', function(e) {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 2;
            slider.scrollLeft = scrollLeft - walk;
        });

        slider.addEventListener('touchstart', function(e) {
            isDown = true;
            startX = e.touches[0].pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
        });

        slider.addEventListener('touchend', function() {
            isDown = false;
        });

        slider.addEventListener('touchmove', function(e) {
            if (!isDown) return;
            e.preventDefault();
            const x = e.touches[0].pageX - slider.offsetLeft;
            const walk = (x - startX) * 2;
            slider.scrollLeft = scrollLeft - walk;
        });

        function updateNavButtons() {
            const maxScroll = slider.scrollWidth - slider.clientWidth;
            prevBtn.style.opacity = slider.scrollLeft <= 0 ? '0.3' : '1';
            prevBtn.style.pointerEvents = slider.scrollLeft <= 0 ? 'none' : 'auto';
            nextBtn.style.opacity = slider.scrollLeft >= maxScroll ? '0.3' : '1';
            nextBtn.style.pointerEvents = slider.scrollLeft >= maxScroll ? 'none' : 'auto';
        }

        slider.addEventListener('scroll', updateNavButtons);
        updateNavButtons();

        window.addEventListener('resize', updateNavButtons);
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

<style>
.product-size-note {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.5rem 1rem;
    border: 1px dashed #e0e0e0;
    border-radius: 999px;
    font-size: 0.9rem;
    color: #ff5a5f;
    margin-bottom: 1rem;
}

.size-picker {
    margin: 1.5rem 0;
}

.size-picker-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.size-picker-error {
    background: #ffe0e0;
    border: 1px solid #ff9b9b;
    color: #b00020;
    padding: 0.5rem 0.75rem;
    border-radius: 6px;
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

.size-select-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
    gap: 0.5rem;
    margin: 1rem 0;
}
.size-select-btn {
    border: 1px solid #ddd;
    padding: 0.5rem 0.75rem;
    border-radius: 999px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.9rem;
    background: #fff;
    cursor: pointer;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.size-select-btn.is-active {
    border-color: #ff5a5f;
    box-shadow: 0 0 0 2px rgba(255, 90, 95, 0.15);
}
.size-select-btn.is-disabled {
    opacity: 0.45;
    cursor: not-allowed;
    border-style: dashed;
}
.size-select-btn small {
    font-size: 0.75rem;
    color: #666;
}
.btn-add-cart.btn-disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
</style>
