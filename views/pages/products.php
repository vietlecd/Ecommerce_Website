<?php require_once 'views/components/header.php'; ?>

<div class="section-title" style="text-align: center; margin: 40px 0;">
    <h2 style="font-size: 32px; color: #333; position: relative; display: inline-block; padding-bottom: 10px;">All Products</h2>
   
</div>

<div class="search-form" style="margin: 20px 0; text-align: left;">
    <form action="/index.php?controller=products&action=index" method="get" style="display: inline-block;">
        <input type="hidden" name="controller" value="products">
        <input type="hidden" name="action" value="index">
        <input type="text" name="keyword" placeholder="Search products..." 
               value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>" 
               style="padding: 10px; width: 300px; border: 1px solid #ccc; border-radius: 4px;">
        <button type="submit" 
                style="padding: 10px 20px; background-color: #ff6b6b; color: white; border: none; border-radius: 4px; cursor: pointer;"
                onmouseover="this.style.backgroundColor='#ff5252'"
                onmouseout="this.style.backgroundColor='#ff6b6b'">Search</button>
    </form>
</div>

<div class="products" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; padding: 20px; justify-items: center;">
    <?php
    $category = isset($_GET['category']) ? $_GET['category'] : '';
    $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
    if (empty($products)) {
        echo '<p style="text-align: center; color: #777;">No products found.</p>';
    } else {
        foreach ($products as $product) {
            echo '<div class="product-card" style="background-color: white; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); width: 100%; max-width: 250px; height: 400px; display: flex; flex-direction: column; justify-content: space-between; align-items: center; padding: 15px;">';
            echo '<div class="product-img" style="height: 200px; width: 100%; overflow: hidden; display: flex; justify-content: center; align-items: center;">';
            $imageUrl = !empty($product['image']) && filter_var($product['image'], FILTER_VALIDATE_URL) ? htmlspecialchars($product['image']) : '/public/placeholder.jpg';
            echo '<img src="' . $imageUrl . '" alt="' . htmlspecialchars($product['name']) . '" loading="lazy" style="max-height: 100%; max-width: 100%; object-fit: contain;">';
            echo '</div>';
            echo '<div class="product-info" style="text-align: center; width: 100%; padding: 10px 0;">';
            echo '<h3 style="font-size: 18px; margin-bottom: 10px; color: #333;">' . htmlspecialchars($product['name']) . '</h3>';
            if (isset($product['final_price']) && $product['final_price'] != $product['price']) {
                echo '<div class="price" style="color: #ff6b6b; font-weight: bold; margin-bottom: 10px;"><s>$' . number_format($product['price'], 2) . '</s><br>$' . number_format($product['final_price'], 2) . '</div>';
            } else {
                echo '<div class="price" style="color: #ff6b6b; font-weight: bold; margin-bottom: 10px;">$' . number_format($product['price'], 2) . '</div>';
            }
            echo '<a href="/index.php?controller=products&action=detail&id=' . $product['id'] . '" class="btn" style="display: inline-block; background-color: #ff6b6b; color: white; padding: 10px 20px; border: none; border-radius: 4px; text-decoration: none; font-size: 16px; transition: all 0.3s ease;" onmouseover="this.style.backgroundColor=\'#ff5252\'" onmouseout="this.style.backgroundColor=\'#ff6b6b\'">View Details</a>';
            echo '</div>';
            echo '</div>';
        }
    }
    ?>

    <?php if (!empty($products) && $totalPages > 1): ?>
        <div class="pagination" style="margin: 20px 0; text-align: center;">
            <?php
            $baseUrl = '/index.php?controller=products&action=index';
            if (!empty($keyword)) $baseUrl .= '&keyword=' . urlencode($keyword);
            if (!empty($category)) $baseUrl .= '&category=' . urlencode($category);

            // Nút Previous
            $prevPage = $currentPage > 1 ? $currentPage - 1 : 1;
            echo '<a href="' . $baseUrl . '&page=' . $prevPage . '" 
                     style="display: inline-block; padding: 8px 12px; margin: 0 5px; text-decoration: none; color: #ff6b6b; border: 1px solid #ddd; border-radius: 4px; ' . ($currentPage === 1 ? 'color: #ccc; pointer-events: none; border-color: #ccc;' : '') . '"
                     onmouseover="' . ($currentPage === 1 ? '' : 'this.style.backgroundColor=\'#f5f5f5\'') . '"
                     onmouseout="' . ($currentPage === 1 ? '' : 'this.style.backgroundColor=\'\'') . '">Previous</a>';

            // Các số trang
            for ($i = 1; $i <= $totalPages; $i++) {
                echo '<a href="' . $baseUrl . '&page=' . $i . '" 
                         style="display: inline-block; padding: 8px 12px; margin: 0 5px; text-decoration: none; color: #ff6b6b; border: 1px solid #ddd; border-radius: 4px; ' . ($currentPage === $i ? 'background-color: #ff6b6b; color: white; border-color: #ff6b6b;' : '') . '"
                         onmouseover="' . ($currentPage === $i ? '' : 'this.style.backgroundColor=\'#f5f5f5\'') . '"
                         onmouseout="' . ($currentPage === $i ? '' : 'this.style.backgroundColor=\'\'') . '">' . $i . '</a>';
            }

            // Nút Next
            $nextPage = $currentPage < $totalPages ? $currentPage + 1 : $totalPages;
            echo '<a href="' . $baseUrl . '&page=' . $nextPage . '" 
                     style="display: inline-block; padding: 8px 12px; margin: 0 5px; text-decoration: none; color: #ff6b6b; border: 1px solid #ddd; border-radius: 4px; ' . ($currentPage === $totalPages ? 'color: #ccc; pointer-events: none; border-color: #ccc;' : '') . '"
                     onmouseover="' . ($currentPage === $totalPages ? '' : 'this.style.backgroundColor=\'#f5f5f5\'') . '"
                     onmouseout="' . ($currentPage === $totalPages ? '' : 'this.style.backgroundColor=\'\'') . '">Next</a>';
            ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once 'views/components/footer.php'; ?>