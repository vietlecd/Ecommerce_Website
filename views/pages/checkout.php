
<?php
$formatCurrency = function ($value) {
    return '$' . number_format($value, 2);
};
?>

<section class="checkout-page">
    <div class="section-title checkout-title">
        <p class="section-eyebrow">Checkout flow</p>
        <h2>Secure your order</h2>
        <p class="section-subtitle">Shipping, payment, and review live side by side so you never lose context.</p>
    </div>

    <?php if (!empty($error)): ?>
        <div class="checkout-alert checkout-alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="checkout-success-card">
            <i class="fas fa-check-circle"></i>
            <h3><?php echo htmlspecialchars($success); ?></h3>
            <p>We’re guiding you back to the storefront so you can keep exploring fresh drops.</p>
        </div>
    <?php else: ?>
        <form method="post" action="" class="checkout-grid">
            <div class="checkout-main">
                <article class="checkout-card">
                    <div class="checkout-card-header">
                        <p class="section-eyebrow">Shipping</p>
                        <h3>Where should we send your pair?</h3>
                    </div>
                    <div class="checkout-field">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required>
                    </div>
                    <div class="checkout-field">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                    </div>
                    <div class="checkout-field">
                        <label for="address">Address</label>
                        <input type="text" id="address" name="address" value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>" required>
                    </div>
                    <div class="checkout-row">
                        <div class="checkout-field">
                            <label for="city">City</label>
                            <input type="text" id="city" name="city" value="<?php echo isset($_POST['city']) ? htmlspecialchars($_POST['city']) : ''; ?>" required>
                        </div>
                        <div class="checkout-field">
                            <label for="zip">ZIP</label>
                            <input type="text" id="zip" name="zip" value="<?php echo isset($_POST['zip']) ? htmlspecialchars($_POST['zip']) : ''; ?>" required>
                        </div>
                    </div>
                </article>

                <article class="checkout-card">
                    <div class="checkout-card-header">
                        <p class="section-eyebrow">Payment</p>
                        <h3>Card details</h3>
                    </div>
                    <div class="checkout-field">
                        <label for="card_number">Card Number</label>
                        <input type="text" id="card_number" name="card_number" value="<?php echo isset($_POST['card_number']) ? htmlspecialchars($_POST['card_number']) : ''; ?>" required>
                    </div>
                    <div class="checkout-row">
                        <div class="checkout-field">
                            <label for="expiry">Expiry</label>
                            <input type="text" id="expiry" name="expiry" placeholder="MM/YY" value="<?php echo isset($_POST['expiry']) ? htmlspecialchars($_POST['expiry']) : ''; ?>" required>
                        </div>
                        <div class="checkout-field">
                            <label for="cvv">CVV</label>
                            <input type="text" id="cvv" name="cvv" value="<?php echo isset($_POST['cvv']) ? htmlspecialchars($_POST['cvv']) : ''; ?>" required>
                        </div>
                    </div>
                </article>

                <article class="checkout-card checkout-items">
                    <div class="checkout-card-header">
                        <p class="section-eyebrow">Items</p>
                        <h3>Review before you confirm</h3>
                    </div>
                    <?php foreach ($cartItems as $item): ?>
                        <div class="checkout-item">
                            <div>
                                <p class="checkout-item-label">SKU #<?php echo htmlspecialchars($item['id']); ?></p>
                                <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                            </div>
                            <div class="checkout-item-meta">
                                <span><?php echo $item['quantity']; ?> × <?php echo $formatCurrency($item['price']); ?></span>
                                <strong><?php echo $formatCurrency($item['subtotal']); ?></strong>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </article>
            </div>

            <aside class="checkout-summary">
                <div class="checkout-summary-header">
                    <p class="section-eyebrow">Summary</p>
                    <h3>Final numbers</h3>
                </div>
                <div class="checkout-coupon-panel">
                    <label for="checkout-coupon-select" class="coupon-label">Coupon</label>
                    <div class="checkout-coupon-actions">
                        <select id="checkout-coupon-select" name="selected_coupon" class="checkout-coupon-select">
                            <option value="">No coupon</option>
                            <?php foreach ($availableCoupons as $coupon): ?>
                                <option value="<?php echo $coupon['CodeID']; ?>" <?php echo ($appliedCoupon && (int)$appliedCoupon['CodeID'] === (int)$coupon['CodeID']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($coupon['CodeTitle']); ?> (<?php echo $coupon['CodePercent']; ?>% off)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" name="apply_coupon" class="btn btn-outline coupon-apply-btn">Apply</button>
                    </div>
                    <?php if ($appliedCoupon): ?>
                        <div class="checkout-coupon-active">
                            <strong><?php echo htmlspecialchars($appliedCoupon['CodeTitle']); ?></strong>
                            <span><?php echo $appliedCoupon['CodePercent']; ?>% off · <?php echo htmlspecialchars($appliedCoupon['CodeDescription']); ?></span>
                        </div>
                    <?php elseif (empty($availableCoupons)): ?>
                        <p class="coupon-note">No active coupons right now. Check back soon.</p>
                    <?php endif; ?>
                </div>
                <ul class="checkout-summary-list">
                    <li>
                        <span>Subtotal</span>
                        <strong><?php echo $formatCurrency($subtotal); ?></strong>
                    </li>
                    <?php if ($discountAmount > 0 && $appliedCoupon): ?>
                        <li class="checkout-summary-discount">
                            <span><?php echo htmlspecialchars($appliedCoupon['CodeTitle']); ?></span>
                            <strong>-<?php echo $formatCurrency($discountAmount); ?></strong>
                        </li>
                    <?php endif; ?>
                    <li>
                        <span>Shipping</span>
                        <strong><?php echo $formatCurrency($shipping); ?></strong>
                    </li>
                    <li class="checkout-summary-total">
                        <span>Total</span>
                        <strong><?php echo $formatCurrency($total); ?></strong>
                    </li>
                </ul>
                <p class="checkout-summary-note">We pre-authorize your card but only capture funds when the order ships.</p>
                <button type="submit" name="place_order" class="btn btn-full">Place Order</button>
            </aside>
        </form>
    <?php endif; ?>
</section>

<?php require_once 'views/components/footer.php'; ?>