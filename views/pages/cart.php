<?php require_once 'views/components/header.php'; ?>

<div class="section-title">
    <h2>Your Shopping Cart</h2>
</div>

<?php if (empty($cartItems)): ?>
    <div class="empty-cart">
        <p>Your cart is empty.</p>
        <a href="/index.php?controller=products&action=index" class="btn">Continue Shopping</a>
    </div>
<?php else: ?>
    <form method="post" action="/index.php?controller=cart&action=update">
        <table class="cart-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
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
                                <span style="text-decoration: line-through;">$<?php echo number_format($item['product']['price'], 2); ?></span>
                                <span>$<?php echo number_format($item['product']['final_price'], 2); ?></span>
                            <?php else: ?>
                                <span>$<?php echo number_format($item['product']['final_price'], 2); ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <input type="number" name="quantity[<?php echo $item['product']['id']; ?>]" value="<?php echo $item['quantity']; ?>" min="1" class="cart-quantity">
                        </td>
                        <td>$<?php echo number_format($item['subtotal'], 2); ?></td>
                        <td>
                            <a href="/index.php?controller=cart&action=remove&id=<?php echo $item['product']['id']; ?>" class="btn-remove" onclick="return confirm('Are you sure you want to remove this item?')">Remove</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <div class="cart-actions">
            <div>
                <button type="submit" name="update_cart" class="btn">Update Cart</button>
                <a href="/index.php?controller=products&action=index" class="btn btn-secondary">Continue Shopping</a>
            </div>
        </div>
    </form>
    
    <div class="cart-summary">
        <h3>Order Summary</h3>
        <div class="summary-item">
            <span>Subtotal</span>
            <span>$<?php echo number_format($subtotal, 2); ?></span>
        </div>
        <div class="summary-item">
            <span>Shipping</span>
            <span>$<?php echo number_format($shipping, 2); ?></span>
        </div>
        <div class="summary-item summary-total">
            <span>Total</span>
            <span>$<?php echo number_format($total, 2); ?></span>
        </div>
        <a href="/index.php?controller=checkout&action=index" class="btn">Proceed to Checkout</a>
    </div>
<?php endif; ?>

<?php require_once 'views/components/footer.php'; ?>