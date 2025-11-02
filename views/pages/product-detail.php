<?php
$imageUrl = !empty($product['image']) && filter_var($product['image'], FILTER_VALIDATE_URL)
    ? htmlspecialchars($product['image'])
    : '/public/placeholder.jpg';
$stock = isset($product['Stock']) ? (int)$product['Stock'] : 0;
$canPurchase = $stock > 0;
$quantityValue = isset($selectedQuantity) ? (int)$selectedQuantity : 1;
$errorMessage = isset($errorMessage) ? $errorMessage : '';
?>

<?php if (!empty($errorMessage)): ?>
    <div class="alert-error"><?php echo htmlspecialchars($errorMessage); ?></div>
<?php endif; ?>

<div class="product-detail">
    <div class="product-detail-img">
        <img src="<?php echo $imageUrl; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" loading="lazy">
    </div>
    <div class="product-detail-info">
        <h1><?php echo htmlspecialchars($product['name']); ?></h1>
        <div class="product-detail-price">
            <?php if (isset($product['final_price']) && $product['final_price'] != $product['price']): ?>
                <span class="product-price-original">$<?php echo number_format($product['price'], 2); ?></span>
                <span class="product-price-final">$<?php echo number_format($product['final_price'], 2); ?></span>
            <?php else: ?>
                <span class="product-price-final">$<?php echo number_format($product['price'], 2); ?></span>
            <?php endif; ?>
        </div>
        <p class="product-detail-stock">
            <?php if ($canPurchase): ?>
                <span class="stock-available"><?php echo $stock; ?> item(s) in stock</span>
            <?php else: ?>
                <span class="stock-unavailable">Out of stock</span>
            <?php endif; ?>
        </p>
        <div class="product-detail-description">
            <?php echo nl2br(htmlspecialchars($product['description'])); ?>
        </div>

        <form method="post" action="" class="product-detail-form">
            <div class="quantity-selector" data-max="<?php echo $stock; ?>">
                <button type="button" class="quantity-minus" aria-label="Decrease quantity">-</button>
                <input type="number" name="quantity" value="<?php echo max(1, $quantityValue); ?>" min="1" max="<?php echo max(1, $stock); ?>" class="quantity-input">
                <button type="button" class="quantity-plus" aria-label="Increase quantity">+</button>
            </div>
            <button type="submit" name="add_to_cart" class="btn" <?php echo $canPurchase ? '' : 'disabled'; ?>>
                <?php echo $canPurchase ? 'Add to cart' : 'Out of stock'; ?>
            </button>
        </form>
    </div>
</div>

<script>
    (function () {
        const selector = document.querySelector('.quantity-selector');
        if (!selector) {
            return;
        }
        const input = selector.querySelector('.quantity-input');
        const minus = selector.querySelector('.quantity-minus');
        const plus = selector.querySelector('.quantity-plus');
        const maxStock = parseInt(selector.getAttribute('data-max'), 10) || 0;

        const clampValue = (value) => {
            let result = isNaN(value) ? 1 : value;
            if (result < 1) {
                result = 1;
            }
            if (maxStock > 0 && result > maxStock) {
                result = maxStock;
            }
            return result;
        };

        minus?.addEventListener('click', () => {
            const current = clampValue(parseInt(input.value, 10) - 1);
            input.value = current;
        });

        plus?.addEventListener('click', () => {
            const current = clampValue(parseInt(input.value, 10) + 1);
            input.value = current;
        });

        input?.addEventListener('change', () => {
            input.value = clampValue(parseInt(input.value, 10));
        });
    })();
</script>

<?php require_once 'views/components/footer.php'; ?>
