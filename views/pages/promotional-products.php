<div class="section-title">
    <h2>All Promotional Products</h2>
</div>

<div class="products">
    <?php
    // Lọc theo danh mục nếu có
    $category = isset($_GET['category']) ? $_GET['category'] : '';

    if (!empty($category)) {
        $filtered_products = array_filter($products, function($product) use ($category) {
            return $product['category'] === $category;
        });
    } else {
        $filtered_products = $products;
    }

    if (empty($filtered_products)) {
        echo '<p>No products found in this category.</p>';
    } else {
        foreach ($filtered_products as $product) {
            echo '<div class="product-card">';
            echo '<div class="product-img">';
            $imageUrl = !empty($product['image']) && filter_var($product['image'], FILTER_VALIDATE_URL) ? htmlspecialchars($product['image']) : '/public/placeholder.jpg';
            echo '<img src="' . $imageUrl . '" alt="' . htmlspecialchars($product['name']) . '" loading="lazy">';
            echo '</div>';
            echo '<div class="product-info">';
            echo '<h3>' . htmlspecialchars($product['name']) . '</h3>';
            if (!empty($product['promotion'])) {
                echo '<div class="price" style="text-decoration: line-through;">$' . number_format($product['price'], 2) . '</div>';
                if ($product['promotion']['discount_percentage']) {
                    echo '<div class="price discount-price">$' . number_format($product['final_price'], 2) . '</div>';
                    echo '<div class="promotion-info">' . htmlspecialchars($product['promotion']['promotion_name']) . '</div>';
                } elseif ($product['promotion']['fixed_price']) {
                    echo '<div class="price discount-price">$' . number_format($product['final_price'], 2) . '</div>';
                    echo '<div class="promotion-info">' . htmlspecialchars($product['promotion']['promotion_name']) . '</div>';
                } elseif ($product['promotion']['buy_quantity'] && $product['promotion']['get_quantity']) {
                    echo '<div class="price">$' . number_format($product['price'], 2) . '</div>';
                    echo '<div class="promotion-info">Buy ' . $product['promotion']['buy_quantity'] . ' Get ' . $product['promotion']['get_quantity'] . ' Free</div>';
                }
            } else {
                echo '<div class="price">$' . number_format($product['price'], 2) . '</div>';
            }
            echo '<a href="/index.php?controller=promotionalProducts&action=detail&id=' . $product['id'] . '" class="btn">View Details</a>';
            echo '</div>';
            echo '</div>';
        }
    }
    ?>
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
<?php require_once 'views/components/footer.php'; ?>