<?php
$truncateText = function ($text, $limit = 120) {
    $clean = trim(strip_tags($text ?? ''));
    if ($clean === '') {
        return '';
    }

    if (function_exists('mb_strlen')) {
        if (mb_strlen($clean) <= $limit) {
            return $clean;
        }
        return rtrim(mb_substr($clean, 0, $limit - 3)) . '...';
    }

    if (strlen($clean) <= $limit) {
        return $clean;
    }

    return rtrim(substr($clean, 0, $limit - 3)) . '...';
};

$baseUrl = '/index.php?controller=promotionalProducts&action=index';
if (!empty($promotionId)) {
    $baseUrl .= '&id=' . (int)$promotionId;
}

$pagination = $pagination ?? [
    'page'       => 1,
    'limit'      => count($products),
    'totalItems' => count($products),
    'totalPages' => 1,
];

$currentPage = (int)$pagination['page'];
$totalPages  = (int)$pagination['totalPages'];
$totalItems  = (int)$pagination['totalItems'];
$limit       = (int)$pagination['limit'];

$fromItem = $totalItems > 0 ? (($currentPage - 1) * $limit + 1) : 0;
$toItem   = min($totalItems, $currentPage * $limit);

$promotion   = $promotion   ?? null;
$promoName   = $promotion['PromotionName'] ?? null;
$promoStart  = $promotion['StartDate']     ?? null;
$promoEnd    = $promotion['EndDate']       ?? null;

$promoStartText = $promoStart ? date('M d, Y', strtotime($promoStart)) : null;
$promoEndText   = $promoEnd   ? date('M d, Y', strtotime($promoEnd))   : null;

?>

<div class="section-title">
    <?php if (!empty($promotion) && !empty($promoName)): ?>
        <h2>
            <?php echo htmlspecialchars($promoName, ENT_QUOTES, 'UTF-8'); ?>
        </h2>

        <p class="section-subtitle">
            <?php if ($promoStartText && $promoEndText): ?>
                Valid from
                <strong><?php echo htmlspecialchars($promoStartText); ?></strong>
                to
                <strong><?php echo htmlspecialchars($promoEndText); ?></strong>
            <?php elseif ($promoStartText && !$promoEndText): ?>
                Starts on
                <strong><?php echo htmlspecialchars($promoStartText); ?></strong>
            <?php elseif (!$promoStartText && $promoEndText): ?>
                Ends on
                <strong><?php echo htmlspecialchars($promoEndText); ?></strong>
            <?php else: ?>
                Promotion details
            <?php endif; ?>

            <?php if ($totalItems > 0): ?>
                &nbsp;· Showing
                <strong><?php echo $fromItem; ?>–<?php echo $toItem; ?></strong>
                of
                <strong><?php echo $totalItems; ?></strong>
                products
            <?php endif; ?>
        </p>
    <?php else: ?>
        <h2>All Promotional Products</h2>
        <?php if ($totalItems > 0): ?>
            <p class="section-subtitle">
                Showing
                <strong><?php echo $fromItem; ?>–<?php echo $toItem; ?></strong>
                of
                <strong><?php echo $totalItems; ?></strong>
                promotional products
            </p>
        <?php endif; ?>
    <?php endif; ?>
</div>


<div class="products">


    <?php if (empty($products)): ?>
        <p class="no-products">No products found.</p>
    <?php else: ?>
        <?php foreach ($products as $product): ?>
            <?php
            $productFinalPrice = isset($product['final_price']) ? (float)$product['final_price'] : (float)$product['price'];
            $productBasePrice = isset($product['price']) ? (float)$product['price'] : $productFinalPrice;
            $productHasSale = $productFinalPrice < $productBasePrice;
            $productSale = $product['sale'] ?? null;
            $productSaleExpiryIso = !empty($productSale['ExpiresAt']) ? date('c', strtotime($productSale['ExpiresAt'])) : null;
            ?>
            <div class="product-card">
                <?php if ($productHasSale): ?>
                    <span class="sale-badge">Sale</span>
                <?php endif; ?>
                <div class="product-img">
                    <?php
                    $imageUrl = '/public/placeholder.jpg';
                    if (!empty($product['image'])) {
                        $rawImage = trim($product['image']);
                        if (filter_var($rawImage, FILTER_VALIDATE_URL)) {
                            $imageUrl = $rawImage;
                        } else {
                            $resolvedPath = ltrim($rawImage, '/');
                            if (file_exists($resolvedPath)) {
                                $imageUrl = '/' . $resolvedPath;
                            } else {
                                $imageUrl = $rawImage;
                            }
                        }
                    }
                    $productSnippet = $truncateText($product['description'] ?? '', 120);
                    $productCategory = !empty($product['category']) ? $product['category'] : 'Uncategorized';
                    $productSize = !empty($product['size_summary']) ? $product['size_summary'] : null;
                    $productStock = isset($product['Stock']) ? (int)$product['Stock'] : 0;
                    $productStockLabel = $productStock > 0 ? $productStock . ' in stock' : 'Out of stock';
                    $productStockClass = $productStock > 0 ? 'stock-available' : 'stock-out';
                    ?>
                    <img src="<?php echo htmlspecialchars($imageUrl); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" loading="lazy">
                </div>
                <div class="product-info">
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <?php if (!empty($productSnippet)): ?>
                        <p class="product-snippet"><?php echo htmlspecialchars($productSnippet); ?></p>
                    <?php endif; ?>
                    <div class="product-meta">
                        <span><i class="fas fa-layer-group"></i><?php echo htmlspecialchars($productCategory); ?></span>
                        <?php if (!empty($productSize)): ?>
                            <span><i class="fas fa-ruler-combined"></i><?php echo htmlspecialchars($productSize); ?></span>
                        <?php endif; ?>
                        <span class="<?php echo $productStockClass; ?>"><i class="fas fa-box"></i><?php echo htmlspecialchars($productStockLabel); ?></span>
                    </div>
                    <div class="price">
                        <?php if ($productHasSale): ?>
                            <span class="price-regular">$<?php echo number_format($productFinalPrice, 2); ?></span>
                            <span class="price-sale-strike">$<?php echo number_format($productBasePrice, 2); ?></span>
                        <?php else: ?>
                            <span class="price-regular">$<?php echo number_format($productBasePrice, 2); ?></span>
                        <?php endif; ?>
                    </div>
                    <?php if ($productHasSale && $productSaleExpiryIso): ?>
                        <div class="sale-countdown" data-sale-expiry="<?php echo htmlspecialchars($productSaleExpiryIso); ?>"></div>
                    <?php endif; ?>
                    <a href="/index.php?controller=products&action=detail&id=<?php echo $product['id']; ?>" class="btn">View Details</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>



</div>
<?php if (!empty($products) && $totalPages > 1): ?>
    <div class="pagination modern">
        <?php
        $baseUrl = '/index.php?controller=promotionalProducts&action=index';

        $queryParams = [];

        if (!empty($promotionId)) {
            $queryParams['id'] = (int)$promotionId;
        }

        if (!empty($limit) && $limit > 0) {
            $queryParams['limit'] = $limit;
        }

        $queryString = !empty($queryParams)
            ? '&' . http_build_query($queryParams)
            : '';

        $prevPage = $currentPage > 1 ? $currentPage - 1 : 1;
        $nextPage = $currentPage < $totalPages ? $currentPage + 1 : $totalPages;
        ?>

        <a href="<?php echo $baseUrl . $queryString . '&page=' . $prevPage; ?>"
            class="pagination-btn prev <?php echo ($currentPage === 1) ? 'disabled' : ''; ?>">
            <i class="fas fa-chevron-left"></i>
            <span>Previous</span>
        </a>

        <!-- Các số trang -->
        <div class="pagination-numbers">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <?php if ($i == 1 || $i == $totalPages || ($i >= $currentPage - 1 && $i <= $currentPage + 1)): ?>
                    <a href="<?php echo $baseUrl . $queryString . '&page=' . $i; ?>"
                        class="pagination-number <?php echo ($currentPage === $i) ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php elseif ($i == $currentPage - 2 || $i == $currentPage + 2): ?>
                    <span class="pagination-ellipsis">...</span>
                <?php endif; ?>
            <?php endfor; ?>
        </div>

        <!-- Nút Next -->
        <a href="<?php echo $baseUrl . $queryString . '&page=' . $nextPage; ?>"
            class="pagination-btn next <?php echo ($currentPage === $totalPages) ? 'disabled' : ''; ?>">
            <span>Next</span>
            <i class="fas fa-chevron-right"></i>
        </a>
    </div>
<?php endif; ?>