<?php require_once 'views/components/header.php'; ?>

<?php
$keyword = isset($keyword) ? $keyword : '';
$selectedCategory = isset($selectedCategory) ? $selectedCategory : '';
$categories = isset($categories) ? $categories : [];
?>

<div class="section-title">
    <h2>All Products</h2>
</div>

<div class="products-toolbar">
    <form action="/index.php" method="get" class="products-filter-form">
        <input type="hidden" name="controller" value="products">
        <input type="hidden" name="action" value="index">

        <div class="products-filter-field">
            <label for="keyword">Keyword</label>
            <input type="text" id="keyword" name="keyword" placeholder="Search products..."
                   value="<?php echo htmlspecialchars($keyword); ?>">
        </div>

        <div class="products-filter-field">
            <label for="category">Category</label>
            <select id="category" name="category">
                <option value="">All categories</option>
                <?php foreach ($categories as $category): ?>
                    <?php
                    $value = isset($category['id']) ? (string)$category['id'] : (string)$category['name'];
                    $isSelected = $selectedCategory !== '' && $selectedCategory == $value;
                    ?>
                    <option value="<?php echo htmlspecialchars($value); ?>" <?php echo $isSelected ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn">Search</button>
    </form>
</div>

<?php if (empty($products)): ?>
    <p class="products-empty">No products found.</p>
<?php else: ?>
    <div class="products-meta">
        <span>Showing <?php echo count($products); ?> of <?php echo (int)$totalProducts; ?> products</span>
    </div>
    <div class="products">
        <?php foreach ($products as $product): ?>
            <?php
            $imageUrl = !empty($product['image']) && filter_var($product['image'], FILTER_VALIDATE_URL)
                ? htmlspecialchars($product['image'])
                : '/public/placeholder.jpg';
            ?>
            <div class="product-card">
                <div class="product-img">
                    <img src="<?php echo $imageUrl; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" loading="lazy">
                </div>
                <div class="product-info">
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <?php if (isset($product['final_price']) && $product['final_price'] != $product['price']): ?>
                        <div class="product-price">
                            <span class="product-price-original">$<?php echo number_format($product['price'], 2); ?></span>
                            <span class="product-price-final">$<?php echo number_format($product['final_price'], 2); ?></span>
                        </div>
                    <?php else: ?>
                        <div class="product-price">
                            <span class="product-price-final">$<?php echo number_format($product['price'], 2); ?></span>
                        </div>
                    <?php endif; ?>
                    <a href="/index.php?controller=products&amp;action=detail&amp;id=<?php echo $product['id']; ?>" class="btn">
                        View details
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if ($totalPages > 1): ?>
        <div class="pagination">
            <?php
            $baseUrl = '/index.php?controller=products&action=index';
            if (!empty($keyword)) {
                $baseUrl .= '&keyword=' . urlencode($keyword);
            }
            if ($selectedCategory !== '') {
                $baseUrl .= '&category=' . urlencode($selectedCategory);
            }

            $prevPage = $currentPage > 1 ? $currentPage - 1 : 1;
            $nextPage = $currentPage < $totalPages ? $currentPage + 1 : $totalPages;
            ?>
            <a href="<?php echo $baseUrl . '&page=' . $prevPage; ?>" class="pagination-link<?php echo $currentPage === 1 ? ' disabled' : ''; ?>">
                Previous
            </a>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="<?php echo $baseUrl . '&page=' . $i; ?>"
                   class="pagination-link<?php echo $currentPage === $i ? ' active' : ''; ?>">
                    <?php echo $i; ?>
                </a>
            <?php endfor; ?>
            <a href="<?php echo $baseUrl . '&page=' . $nextPage; ?>" class="pagination-link<?php echo $currentPage === $totalPages ? ' disabled' : ''; ?>">
                Next
            </a>
        </div>
    <?php endif; ?>
<?php endif; ?>

<?php require_once 'views/components/footer.php'; ?>
