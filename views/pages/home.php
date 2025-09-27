<div class="hero" style="background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('/assets/images/shoes_store.jpg'); background-size: cover; background-position: center; background-color: transparent; height: 500px; display: flex; align-items: center; justify-content: center; text-align: center; color: white; margin-bottom: 40px; position: relative;">
    <div class="hero-content">
        <h1>Step into Style</h1>
        <p>Discover the perfect pair of shoes for every occasion. From casual to formal, we've got you covered.</p>
        <a href="/index.php?controller=products&action=index" class="btn">Shop Now</a>
    </div>
</div>

<section class="featured-products">
    <div class="section-title">
        <h2>Featured Products</h2>
    </div>
    <div class="products">
        <?php if (empty($featuredProducts)): ?>
            <p style="text-align: center; color: #777;">No featured products available.</p>
        <?php else: ?>
            <?php foreach ($featuredProducts as $product): ?>
                <div class="product-card">
                    <div class="product-img">
                        <img src="<?php echo htmlspecialchars($product['Image'] ?: '/placeholder.svg?height=200&width=300'); ?>" alt="<?php echo htmlspecialchars($product['ProductName']); ?>">
                    </div>
                    <div class="product-info">
                        <h3><?php echo htmlspecialchars($product['ProductName']); ?></h3>
                        <div class="price">$<?php echo number_format($product['Price'], 2); ?></div>
                        <a href="/index.php?controller=products&action=detail&id=<?php echo $product['ProductID']; ?>" class="btn">View Details</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>

<!-- <section class="categories">
    <div class="section-title">
        <h2>Shop by Category</h2>
    </div>
    <div class="products">
        <//?php if (empty($categories)): ?>
            <p style="text-align: center; color: #777;">No categories available.</p>
        <//?php else: ?>
            <//?php foreach ($categories as $category): ?>
                <div class="product-card">
                    <div class="product-img">
                        <img src="/placeholder.svg?height=200&width=300" alt="<//?php echo htmlspecialchars($category['CategoryName']); ?>">
                    </div>
                    <div class="product-info">
                        <h3><//?php echo htmlspecialchars($category['CategoryName']); ?></h3>
                        <a href="/index.php?controller=products&action=index&category=<//?php echo urlencode($category['CategoryID']); ?>" class="btn">Shop Now</a>
                    </div>
                </div>
            <//?php endforeach; ?>
        <//?php endif; ?>
    </div>
</section> -->

<?php require_once __DIR__ . '/../components/footer.php'; ?>
