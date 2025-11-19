
<?php
$formatCurrency = function ($value) {
    return '$' . number_format($value, 2);
};
$selectedPaymentMethod = isset($selectedPaymentMethod) ? $selectedPaymentMethod : (isset($_POST['payment_method']) ? $_POST['payment_method'] : 'Card');
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
                        <h3>Payment method</h3>
                    </div>
                    <div class="checkout-field">
                        <label>Payment Method</label>
                        <div class="payment-method-selector" role="radiogroup" aria-label="Select payment method">
                            <?php
                            $paymentMethods = [
                                ['value' => 'Card', 'title' => 'Card', 'description' => 'Visa, MasterCard, AMEX', 'icon' => 'fas fa-credit-card'],
                                ['value' => 'Cash', 'title' => 'Cash on Delivery', 'description' => 'Pay when the courier arrives', 'icon' => 'fas fa-money-bill-wave'],
                                ['value' => 'Bank Transfer', 'title' => 'Bank Transfer', 'description' => 'Wire from your bank account', 'icon' => 'fas fa-university'],
                                ['value' => 'E-wallet', 'title' => 'E-wallet', 'description' => 'PayPal, Apple Pay, Google Pay', 'icon' => 'fas fa-mobile-screen-button'],
                            ];
                            foreach ($paymentMethods as $method):
                                $isActive = $selectedPaymentMethod === $method['value'];
                                $inputId = 'payment_method_' . strtolower(str_replace(' ', '_', $method['value']));
                            ?>
                            <label class="payment-method-option <?php echo $isActive ? 'is-active' : ''; ?>" for="<?php echo $inputId; ?>">
                                <input type="radio" id="<?php echo $inputId; ?>" name="payment_method" value="<?php echo $method['value']; ?>" <?php echo $isActive ? 'checked' : ''; ?> required>
                                <span class="payment-method-option-icon">
                                    <i class="<?php echo $method['icon']; ?>"></i>
                                </span>
                                <span class="payment-method-option-copy">
                                    <strong><?php echo $method['title']; ?></strong>
                                    <span><?php echo $method['description']; ?></span>
                                </span>
                            </label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div id="payment-card-fields" class="payment-method-fields" style="display: <?php echo ($selectedPaymentMethod === 'Card') ? 'block' : 'none'; ?>;">
                        <div class="checkout-field">
                            <label for="card_number">Card Number</label>
                            <input type="text" id="card_number" name="card_number" placeholder="Mock payment (optional)" value="<?php echo isset($_POST['card_number']) ? htmlspecialchars($_POST['card_number']) : ''; ?>">
                        </div>
                        <div class="checkout-row">
                            <div class="checkout-field">
                                <label for="expiry">Expiry</label>
                                <input type="text" id="expiry" name="expiry" placeholder="MM/YY (optional)" value="<?php echo isset($_POST['expiry']) ? htmlspecialchars($_POST['expiry']) : ''; ?>">
                            </div>
                            <div class="checkout-field">
                                <label for="cvv">CVV</label>
                                <input type="text" id="cvv" name="cvv" placeholder="Optional" value="<?php echo isset($_POST['cvv']) ? htmlspecialchars($_POST['cvv']) : ''; ?>">
                            </div>
                        </div>
                    </div>

                    <div id="payment-cash-fields" class="payment-method-fields" style="display: <?php echo ($selectedPaymentMethod === 'Cash') ? 'block' : 'none'; ?>;">
                        <p class="payment-method-note">Payment will be collected upon delivery. No additional information required.</p>
                    </div>

                    <div id="payment-bank-transfer-fields" class="payment-method-fields" style="display: <?php echo ($selectedPaymentMethod === 'Bank Transfer') ? 'block' : 'none'; ?>;">
                        <div class="checkout-field">
                            <label for="bank_account">Account Number</label>
                            <input type="text" id="bank_account" name="bank_account" placeholder="Enter account number (optional)" value="<?php echo isset($_POST['bank_account']) ? htmlspecialchars($_POST['bank_account']) : ''; ?>">
                        </div>
                        <div class="checkout-field">
                            <label for="bank_name">Bank Name</label>
                            <input type="text" id="bank_name" name="bank_name" placeholder="Enter bank name (optional)" value="<?php echo isset($_POST['bank_name']) ? htmlspecialchars($_POST['bank_name']) : ''; ?>">
                        </div>
                    </div>

                    <div id="payment-ewallet-fields" class="payment-method-fields" style="display: <?php echo ($selectedPaymentMethod === 'E-wallet') ? 'block' : 'none'; ?>;">
                        <div class="checkout-field">
                            <label for="wallet_number">Wallet Number</label>
                            <input type="text" id="wallet_number" name="wallet_number" placeholder="Enter wallet number (optional)" value="<?php echo isset($_POST['wallet_number']) ? htmlspecialchars($_POST['wallet_number']) : ''; ?>">
                        </div>
                        <div class="checkout-field">
                            <label for="wallet_provider">Wallet Provider</label>
                            <input type="text" id="wallet_provider" name="wallet_provider" placeholder="e.g., PayPal, Apple Pay, Google Pay (optional)" value="<?php echo isset($_POST['wallet_provider']) ? htmlspecialchars($_POST['wallet_provider']) : ''; ?>">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
    const paymentCards = document.querySelectorAll('.payment-method-option');
    const cardFields = document.getElementById('payment-card-fields');
    const cashFields = document.getElementById('payment-cash-fields');
    const bankTransferFields = document.getElementById('payment-bank-transfer-fields');
    const eWalletFields = document.getElementById('payment-ewallet-fields');

    function getSelectedMethod() {
        const checked = document.querySelector('input[name="payment_method"]:checked');
        return checked ? checked.value : '';
    }

    function syncPaymentCards() {
        paymentCards.forEach(card => {
            const radio = card.querySelector('input[name="payment_method"]');
            if (!radio) {
                return;
            }
            card.classList.toggle('is-active', radio.checked);
        });
    }

    function togglePaymentFields() {
        const selectedMethod = getSelectedMethod();
        cardFields.style.display = selectedMethod === 'Card' ? 'block' : 'none';
        cashFields.style.display = selectedMethod === 'Cash' ? 'block' : 'none';
        bankTransferFields.style.display = selectedMethod === 'Bank Transfer' ? 'block' : 'none';
        eWalletFields.style.display = selectedMethod === 'E-wallet' ? 'block' : 'none';
    }

    paymentRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            syncPaymentCards();
            togglePaymentFields();
        });
    });

    syncPaymentCards();
    togglePaymentFields();
});
</script>

<?php require_once 'views/components/footer.php'; ?>