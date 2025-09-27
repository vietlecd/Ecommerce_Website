

<div class="product-detail">
    <div class="product-detail-img">
    <?php
        $imageUrl = !empty($product['image']) && filter_var($product['image'], FILTER_VALIDATE_URL) ? htmlspecialchars($product['image']) : '/public/placeholder.jpg';

            echo '<img src="' . $imageUrl . '" alt="' . htmlspecialchars($product['name']) . '" loading="lazy" >';
    ?>
    </div>
    <div class="product-detail-info">
        <h1><?php echo htmlspecialchars($product['name']); ?></h1>
        <?php if (isset($product['final_price']) && $product['final_price'] != $product['price']) { ?>
            <div class="price"><s>$<?php echo number_format($product['price'], 2); ?></s><br>$<?php echo number_format($product['final_price'], 2); ?></div>
        <?php } else { ?>
            <div class="price">$<?php echo number_format($product['price'], 2); ?></div>
        <?php } ?>
        <div class="description"><?php echo htmlspecialchars($product['description']); ?></div>
        
        <form method="post" action="">
            <div class="quantity-selector">
                <button type="button" class="quantity-minus">-</button>
                <input type="number" name="quantity" value="1" min="1" class="quantity-input">
                <button type="button" class="quantity-plus">+</button>
            </div>
            <button type="submit" name="add_to_cart" class="btn">Add to Cart</button>
        </form>
    </div>
</div>

<script>
    document.querySelector('.quantity-minus').addEventListener('click', function() {
        let input = document.querySelector('.quantity-input');
        let value = parseInt(input.value);
        if (value > 1) {
            input.value = value - 1;
        }
    });

    document.querySelector('.quantity-plus').addEventListener('click', function() {
        let input = document.querySelector('.quantity-input');
        let value = parseInt(input.value);
        input.value = value + 1;
    });

    document.querySelector('.quantity-input').addEventListener('change', function() {
        if (this.value < 1) {
            this.value = 1;
        }
    });
</script>

<?php require_once 'views/components/footer.php'; ?>