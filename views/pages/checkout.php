<div class="section-title">
    <h2>Checkout</h2>
</div>

<?php if (!empty($error)): ?>
    <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <div class="alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php else: ?>
    <div class="checkout-container">
        <div class="checkout-form">
            <h3>Shipping information</h3>
            <form method="post" action="">
                <div class="form-group">
                    <label for="name">Full name</label>
                    <input type="text" id="name" name="name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="city">City</label>
                        <input type="text" id="city" name="city" value="<?php echo isset($_POST['city']) ? htmlspecialchars($_POST['city']) : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="zip">ZIP / Postal code</label>
                        <input type="text" id="zip" name="zip" value="<?php echo isset($_POST['zip']) ? htmlspecialchars($_POST['zip']) : ''; ?>" required>
                    </div>
                </div>

                <h3>Payment information</h3>
                <div class="form-group">
                    <label for="card_number">Card number</label>
                    <input type="text" id="card_number" name="card_number" value="<?php echo isset($_POST['card_number']) ? htmlspecialchars($_POST['card_number']) : ''; ?>" required>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label for="expiry">Expiry date</label>
                        <input type="text" id="expiry" name="expiry" placeholder="MM/YY" value="<?php echo isset($_POST['expiry']) ? htmlspecialchars($_POST['expiry']) : ''; ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="cvv">CVV</label>
                        <input type="text" id="cvv" name="cvv" value="<?php echo isset($_POST['cvv']) ? htmlspecialchars($_POST['cvv']) : ''; ?>" required>
                    </div>
                </div>

                <div class="cart-summary">
                    <h3>Order summary</h3>
                    <div class="summary-item">
                        <span>Subtotal</span>
                        <span>$<?php echo number_format($subtotal, 2); ?></span>
                    </div>
                    <div class="summary-item">
                        <span>Shipping</span>
                        <span><?php echo $shipping > 0 ? '$' . number_format($shipping, 2) : 'Free'; ?></span>
                    </div>
                    <div class="summary-item summary-total">
                        <span>Total</span>
                        <span>$<?php echo number_format($total, 2); ?></span>
                    </div>
                </div>

                <button type="submit" name="place_order" class="form-btn">Place order</button>
            </form>
        </div>
    </div>
<?php endif; ?>
