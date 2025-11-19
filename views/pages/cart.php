<?php require_once 'views/components/header.php'; ?>

<?php
$formatCurrency = function ($value) {
    return '$' . number_format($value, 2);
};

$resolveImagePath = function ($imagePath) {
    $fallback = '/public/placeholder.jpg';
    if (empty($imagePath)) {
        return $fallback;
    }
    if (filter_var($imagePath, FILTER_VALIDATE_URL)) {
        return $imagePath;
    }
    if (file_exists($imagePath)) {
        return '/' . ltrim($imagePath, '/');
    }
    return $imagePath;
};

$cartCount = isset($cartItems) ? count($cartItems) : 0;
$availableCoupons = $availableCoupons ?? [];
$discountAmount = $discountAmount ?? 0;
$appliedCoupon = $appliedCoupon ?? null;
$selectedCouponId = $selectedCouponId ?? null;
?>

<section class="cart-page">
    <div class="section-title cart-section-title">
        <p class="section-eyebrow">Cart overview</p>
        <h2>Your Shopping Cart</h2>
        <p class="section-subtitle">Items stay grouped, pricing stays transparent, and the summary keeps up in real time.</p>
    </div>

    <?php if (empty($cartItems)): ?>
        <div class="cart-empty-card">
            <div class="cart-empty-icon">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <h3>Cart is feeling light</h3>
            <p>Add a pair or two to see your tailored summary light up.</p>
            <a href="/index.php?controller=products&action=index" class="cart-browse-btn">
                <span>Browse Collections</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    <?php else: ?>
        <form method="post" action="/index.php?controller=cart&action=update" class="cart-form">
            <div class="cart-grid">
                <div>
                    <div class="cart-pill">
                        <span><?php echo $cartCount; ?> items</span>
                        <span>Subtotal <?php echo $formatCurrency($subtotal); ?></span>
                    </div>
                    <div class="cart-items">
                        <?php foreach ($cartItems as $item): ?>
                            <?php
                            $product = $item['product'];
                            $image = $resolveImagePath($product['image'] ?? null);
                            $hasDiscount = $product['price'] > $product['final_price'];
                            $discountPercent = $hasDiscount && $product['price'] > 0
                                ? round((1 - ($product['final_price'] / $product['price'])) * 100)
                                : 0;
                            ?>
                            <article class="cart-card">
                                <div class="cart-card-media">
                                    <img src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" loading="lazy">
                                </div>
                                <div class="cart-card-body">
                                    <div class="cart-card-header">
                                        <div>
                                            <p class="cart-card-label">Product ID #<?php echo htmlspecialchars($product['id']); ?></p>
                                            <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                                        </div>
                                        <?php if ($hasDiscount): ?>
                                            <span class="cart-badge">-<?php echo $discountPercent; ?>%</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="cart-card-stats">
                                        <div class="cart-price-stack">
                                            <?php if ($hasDiscount): ?>
                                                <span class="price-original"><?php echo $formatCurrency($product['price']); ?></span>
                                                <span class="price-current"><?php echo $formatCurrency($product['final_price']); ?></span>
                                            <?php else: ?>
                                                <span class="price-current"><?php echo $formatCurrency($product['final_price']); ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <label class="cart-quantity-label" for="quantity_<?php echo $product['id']; ?>">Quantity</label>
                                        <input
                                            id="quantity_<?php echo $product['id']; ?>"
                                            type="number"
                                            name="quantity[<?php echo $product['id']; ?>]"
                                            value="<?php echo $item['quantity']; ?>"
                                            min="1"
                                            class="cart-quantity-input">
                                        <div class="cart-line-total">
                                            <span>Line total</span>
                                            <strong><?php echo $formatCurrency($item['subtotal']); ?></strong>
                                        </div>
                                    </div>
                                    <div class="cart-card-footer">
                                        <a href="/index.php?controller=cart&action=remove&id=<?php echo $product['id']; ?>" class="cart-remove-link" onclick="return confirm('Remove this item from cart?');">
                                            <i class="fas fa-times"></i> Remove
                                        </a>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>

                        <div class="cart-form-actions">
                            <button type="submit" name="update_cart" class="btn">Update Cart</button>
                            <a href="/index.php?controller=products&action=index" class="btn btn-outline">Continue Shopping</a>
                        </div>
                    </div>
                </div>

                <aside class="cart-summary-card">
                    <div class="cart-summary-header">
                        <p class="summary-eyebrow">Order summary</p>
                        <h3>Everything at a glance</h3>
                    </div>
                    <?php if (!empty($availableCoupons)): ?>
                        <div class="cart-coupon-control">
                            <label for="coupon-select">Coupon code</label>
                            <select id="coupon-select" name="selected_coupon" class="cart-coupon-select">
                                <option value="">No code applied</option>
                                <?php foreach ($availableCoupons as $coupon): ?>
                                    <option value="<?php echo $coupon['CodeID']; ?>" <?php echo ($selectedCouponId === (int) $coupon['CodeID']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($coupon['CodeTitle']); ?> (<?php echo $coupon['CodePercent']; ?>% off)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <p class="cart-coupon-hint">Pick a ShoeStore code to update totals instantly.</p>
                        </div>
                    <?php endif; ?>
                    <ul class="cart-summary-list">
                        <li>
                            <span>Subtotal</span>
                            <strong><?php echo $formatCurrency($subtotal); ?></strong>
                        </li>
                        <?php if ($discountAmount > 0 && $appliedCoupon): ?>
                            <li class="cart-summary-discount">
                                <span><?php echo htmlspecialchars($appliedCoupon['CodeTitle']); ?></span>
                                <strong>-<?php echo $formatCurrency($discountAmount); ?></strong>
                            </li>
                        <?php endif; ?>
                        <li>
                            <span>Shipping</span>
                            <strong><?php echo $formatCurrency($shipping); ?></strong>
                        </li>
                        <li class="cart-summary-total">
                            <span>Total</span>
                            <strong><?php echo $formatCurrency($total); ?></strong>
                        </li>
                    </ul>
                    <p class="cart-summary-note">Shipping adjusts automatically if new items join the cart.</p>
                    <a href="/index.php?controller=checkout&action=index" class="btn btn-full">Proceed to Checkout</a>
                </aside>
            </div>
        </form>
    <?php endif; ?>

    <?php if (!empty($recommendedProducts)): ?>
        <section class="cart-recommend">
            <div class="cart-recommend-header">
                <p class="section-eyebrow">Popular markdowns</p>
                <h3>Finish your look with these high-impact deals</h3>
                <p>These pairs carry the steepest active promotions right now.</p>
            </div>
            <div class="cart-recommend-grid">
                <?php foreach ($recommendedProducts as $product): ?>
                    <?php
                    $image = $resolveImagePath($product['image'] ?? null);
                    $savingPercent = $product['price'] > 0
                        ? round((1 - ($product['final_price'] / $product['price'])) * 100)
                        : 0;
                    ?>
                    <article class="cart-recommend-card">
                        <div class="cart-recommend-media">
                            <img src="<?php echo htmlspecialchars($image); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" loading="lazy">
                            <span class="cart-recommend-badge"><?php echo $savingPercent; ?>% off</span>
                        </div>
                        <div class="cart-recommend-body">
                            <h4><?php echo htmlspecialchars($product['name']); ?></h4>
                            <div class="cart-recommend-price">
                                <span class="price-current"><?php echo $formatCurrency($product['final_price']); ?></span>
                                <span class="price-original"><?php echo $formatCurrency($product['price']); ?></span>
                            </div>
                            <a href="/index.php?controller=products&action=detail&id=<?php echo $product['id']; ?>" class="link-arrow">
                                View details
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>
        </section>
    <?php endif; ?>
</section>

<?php require_once 'views/components/footer.php'; ?>