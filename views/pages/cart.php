<?php require_once 'views/components/header.php'; ?>

<div class="section-title">
    <h2>Your Shopping Cart</h2>
</div>

<?php if (!empty($messages)): ?>
    <div class="cart-messages">
        <?php foreach ($messages as $message): ?>
            <?php
            $type = isset($message['type']) ? $message['type'] : 'info';
            $class = 'alert-info';
            if ($type === 'warning') {
                $class = 'alert-warning';
            } elseif ($type === 'error') {
                $class = 'alert-error';
            } elseif ($type === 'success') {
                $class = 'alert-success';
            }
            ?>
            <div class="alert <?php echo $class; ?>">
                <?php echo htmlspecialchars($message['text']); ?>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (empty($cartItems)): ?>
    <div class="empty-cart">
        <p>Your cart is empty.</p>
        <a href="/index.php?controller=products&action=index" class="btn">Continue shopping</a>
    </div>
<?php else: ?>
    <form method="post" action="/index.php?controller=cart&action=update">
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Unit price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartItems as $item): ?>
                    <tr>
                        <td>
                            <div class="cart-product">
                                <div>
                                    <h4><?php echo htmlspecialchars($item['product']['name']); ?></h4>
                                </div>
                            </div>
                        </td>
                        <td>
                            <?php if ($item['product']['final_price'] < $item['product']['price']): ?>
                                <span class="cart-price-original">$<?php echo number_format($item['product']['price'], 2); ?></span>
                                <span class="cart-price-final">$<?php echo number_format($item['product']['final_price'], 2); ?></span>
                            <?php else: ?>
                                <span class="cart-price-final">$<?php echo number_format($item['product']['final_price'], 2); ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="cart-quantity-group">
                                <input type="number"
                                       name="quantity[<?php echo $item['product']['id']; ?>]"
                                       value="<?php echo $item['quantity']; ?>"
                                       min="1"
                                       max="<?php echo $item['product']['stock']; ?>"
                                       class="cart-quantity">
                                <small class="stock-hint">Stock: <?php echo $item['product']['stock']; ?></small>
                            </div>
                        </td>
                        <td>$<?php echo number_format($item['subtotal'], 2); ?></td>
                        <td>
                            <a href="/index.php?controller=cart&action=remove&id=<?php echo $item['product']['id']; ?>"
                               class="btn-remove"
                               onclick="return confirm('Remove this item from the cart?')">Remove</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="cart-actions">
            <div>
                <button type="submit" name="update_cart" class="btn">Update cart</button>
                <a href="/index.php?controller=products&action=index" class="btn btn-secondary">Continue shopping</a>
            </div>
        </div>
    </form>

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
        <a href="/index.php?controller=checkout&action=index" class="btn">Proceed to checkout</a>
    </div>
<?php endif; ?>

<?php require_once 'views/components/footer.php'; ?>
