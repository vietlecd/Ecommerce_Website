<div class="product-detail">
    <div class="product-detail-img">
        <?php
            $imageUrl = !empty($product['image']) && filter_var($product['image'], FILTER_VALIDATE_URL) ? htmlspecialchars($product['image']) : '/public/placeholder.jpg';

                echo '<img src="' . $imageUrl . '" alt="' . htmlspecialchars($product['name']) . '" loading="lazy" >';
        ?>
        </div>
    <div class="product-detail-info">
        <h1><?php echo htmlspecialchars($product['name']); ?></h1>
        <?php if (!empty($product['promotion'])): ?>
            <div class="price" style="text-decoration: line-through;">
                $<?php echo number_format($product['price'], 2); ?>
            </div>
            <?php if ($product['promotion']['discount_percentage']): ?>
                <div class="price discount-price">
                    $<?php echo number_format($product['final_price'], 2); ?>
                </div>
                <div class="promotion-info">
                    <?php echo htmlspecialchars($product['promotion']['promotion_name']); ?>
                </div>
            <?php elseif ($product['promotion']['fixed_price']): ?>
                <div class="price discount-price">
                    $<?php echo number_format($product['final_price'], 2); ?>
                </div>
                <div class="promotion-info">
                    <?php echo htmlspecialchars($product['promotion']['promotion_name']); ?>
                </div>
            <?php elseif ($product['promotion']['buy_quantity'] && $product['promotion']['get_quantity']): ?>
                <div class="price">
                    $<?php echo number_format($product['price'], 2); ?>
                </div>
                <div class="promotion-info">
                    Buy <?php echo $product['promotion']['buy_quantity']; ?> Get <?php echo $product['promotion']['get_quantity']; ?> Free
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="price">
                $<?php echo number_format($product['price'], 2); ?>
            </div>
        <?php endif; ?>
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

<style>
    .discount-price {
        color: #e74c3c;
        font-weight: bold;
    }
    .promotion-info {
        color: #007bff;
        font-style: italic;
        margin-top: 5px;
    }
</style>

<script>
    // JavaScript cho nút tăng/giảm số lượng
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

    // Đảm bảo giá trị không nhỏ hơn 1
    document.querySelector('.quantity-input').addEventListener('change', function() {
        if (this.value < 1) {
            this.value = 1;
        }
    });
</script>


<?php require_once 'views/components/footer.php'; ?>