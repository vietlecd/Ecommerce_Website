<?php
$keyword = $keyword ?? '';
$category = $category ?? '';
$currentPage = $currentPage ?? 1;
$perPage = $perPage ?? 8;
$offset = max(0, ($currentPage - 1) * $perPage);
$fromRecord = $totalProducts > 0 ? $offset + 1 : 0;
$toRecord = $totalProducts > 0 ? min($offset + count($products), $totalProducts) : 0;

$baseQuery = [
    'controller' => 'adminProduct',
    'action'     => 'products',
];
if ($keyword !== '') {
    $baseQuery['keyword'] = $keyword;
}
if ($category !== '') {
    $baseQuery['category'] = $category;
}

$buildPageUrl = function (int $page) use ($baseQuery): string {
    if ($page < 1) {
        $page = 1;
    }
    return '/views/admin/index.php?' . http_build_query(array_merge($baseQuery, ['page' => $page]));
};

$resolveProductImage = function (?string $imageValue): string {
    $placeholder = '/public/placeholder.jpg';
    if (empty($imageValue)) {
        return $placeholder;
    }
    if (filter_var($imageValue, FILTER_VALIDATE_URL)) {
        return $imageValue;
    }
    $normalized = ltrim($imageValue, '/');
    if (strpos($normalized, 'assets/') === 0) {
        return '/' . $normalized;
    }
    if (strpos($normalized, 'public/') === 0) {
        return '/' . $normalized;
    }
    return '/assets/images/shoes/' . $normalized;
};
?>

<div class="row g-4">
  <div class="col-12">
    <div class="card card-stacked">
      <div class="card-header">
        <div>
          <div class="card-title">Product management</div>
          <div class="text-secondary">Monitor stock, search, and quickly update each item.</div>
        </div>
        <div class="ms-auto">
          <a href="/views/admin/index.php?controller=adminProduct&action=addProduct" class="btn btn-primary">
            <i class="ti ti-plus me-1"></i> Add product
          </a>
        </div>
      </div>
      <div class="card-body border-bottom py-3">
        <form class="row g-2 align-items-end" method="get" action="/views/admin/index.php">
          <input type="hidden" name="controller" value="adminProduct">
          <input type="hidden" name="action" value="products">
          <div class="col-12 col-md-5 col-lg-4">
            <label class="form-label">Keyword</label>
            <input
              type="text"
              name="keyword"
              class="form-control"
              placeholder="Product name or description"
              value="<?php echo htmlspecialchars($keyword); ?>"
            >
          </div>
          <div class="col-12 col-md-4 col-lg-3">
            <label class="form-label">Category</label>
            <select name="category" class="form-select">
              <option value="">All categories</option>
              <?php foreach ($categories as $categoryRow): ?>
                <option
                  value="<?php echo htmlspecialchars($categoryRow['id']); ?>"
                  <?php echo (string)$categoryRow['id'] === (string)$category ? 'selected' : ''; ?>
                >
                  <?php echo htmlspecialchars($categoryRow['name']); ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-12 col-md-3 col-lg-2">
            <label class="form-label d-none d-md-block">&nbsp;</label>
            <button type="submit" class="btn btn-outline-primary w-100">
              <i class="ti ti-search me-1"></i> Search
            </button>
          </div>
        </form>
      </div>

      <?php if (empty($products)): ?>
        <div class="card-body">
          <div class="empty">
            <div class="empty-header">No data</div>
            <p class="empty-subtitle text-secondary">
              No products match your filters. Try another keyword or add a new product.
            </p>
            <div class="empty-action">
              <a class="btn btn-primary" href="/views/admin/index.php?controller=adminProduct&action=addProduct">
                <i class="ti ti-plus me-1"></i> Add first product
              </a>
            </div>
          </div>
        </div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-vcenter">
            <thead>
              <tr>
                <th>Product</th>
                <th>Category</th>
                <th class="text-center">Stock</th>
                <th>Price</th>
                <th class="w-1">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($products as $product): ?>
                <?php
                $imagePath  = $resolveProductImage($product['image'] ?? null);
                $stockValue = isset($product['Stock']) ? (int)$product['Stock'] : (int)($product['stock'] ?? 0);
                $sizeSummary = $product['size_summary'] ?? '';
                $finalPrice  = isset($product['final_price']) ? (float)$product['final_price'] : null;
                $basePrice   = isset($product['price']) ? (float)$product['price'] : 0;
                $displayFinalPrice = $finalPrice !== null && $finalPrice > 0 && $finalPrice < $basePrice;
                ?>
                <tr>
                  <td>
                    <div class="d-flex align-items-center">
                      <span class="avatar me-2" style="background-image: url('<?php echo htmlspecialchars($imagePath); ?>')"></span>
                      <div>
                        <div class="fw-semibold"><?php echo htmlspecialchars($product['name'] ?? ''); ?></div>
                        <div class="text-secondary small text-truncate">
                          <?php if ($sizeSummary): ?>
                            Size: <?php echo htmlspecialchars($sizeSummary); ?>
                          <?php else: ?>
                            Sizes not configured
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                  </td>
                  <td class="text-secondary">
                    <?php echo htmlspecialchars($product['category'] ?? 'Unknown'); ?>
                  </td>
                  <td class="text-center">
                    <?php if ($stockValue <= 0): ?>
                      <span class="badge bg-red-lt">Out of stock</span>
                    <?php elseif ($stockValue < 10): ?>
                      <span class="badge bg-orange-lt">Only <?php echo $stockValue; ?> left</span>
                    <?php else: ?>
                      <span class="badge bg-green-lt"><?php echo $stockValue; ?> in stock</span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <?php if ($displayFinalPrice): ?>
                      <div class="fw-semibold text-danger">
                        <?php echo '$' . number_format($finalPrice, 2); ?>
                      </div>
                      <div class="text-secondary text-decoration-line-through small">
                        <?php echo '$' . number_format($basePrice, 2); ?>
                      </div>
                    <?php else: ?>
                      <div class="fw-semibold">
                        <?php echo '$' . number_format($basePrice, 2); ?>
                      </div>
                    <?php endif; ?>
                  </td>
                  <td class="text-nowrap">
                    <div class="btn-list flex-nowrap mb-0">
                      <a
                        href="/views/admin/index.php?controller=adminProduct&action=editProduct&id=<?php echo $product['id']; ?>"
                        class="btn btn-sm btn-outline-primary"
                      >
                        <i class="ti ti-pencil me-1"></i> Edit
                      </a>
                      <a
                        href="/views/admin/index.php?controller=adminProduct&action=deleteProduct&id=<?php echo $product['id']; ?>"
                        class="btn btn-sm btn-outline-danger"
                        onclick="return confirm('Are you sure you want to delete this product?');"
                      >
                        <i class="ti ti-trash me-1"></i> Delete
                      </a>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <div class="card-footer d-flex align-items-center">
          <p class="m-0 text-secondary">
            Showing <?php echo $fromRecord; ?>–<?php echo $toRecord; ?> of <?php echo $totalProducts; ?> products
          </p>
          <?php if ($totalPages > 1): ?>
            <ul class="pagination m-0 ms-auto">
              <li class="page-item <?php echo $currentPage <= 1 ? 'disabled' : ''; ?>">
                <a
                  class="page-link"
                  href="<?php echo $buildPageUrl(max(1, $currentPage - 1)); ?>"
                  tabindex="-1"
                  aria-disabled="<?php echo $currentPage <= 1 ? 'true' : 'false'; ?>"
                >
                  <i class="ti ti-chevron-left"></i> Previous
                </a>
              </li>
              <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php echo $currentPage === $i ? 'active' : ''; ?>">
                  <a class="page-link" href="<?php echo $buildPageUrl($i); ?>"><?php echo $i; ?></a>
                </li>
              <?php endfor; ?>
              <li class="page-item <?php echo $currentPage >= $totalPages ? 'disabled' : ''; ?>">
                <a class="page-link" href="<?php echo $buildPageUrl(min($totalPages, $currentPage + 1)); ?>">
                  Next <i class="ti ti-chevron-right"></i>
                </a>
              </li>
            </ul>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/../components/admin_footer.php'; ?>
