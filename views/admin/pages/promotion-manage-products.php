<?php
$promoName  = $promotion['PromotionName']      ?? $promotion['promotion_name']      ?? '';
$promoType  = $promotion['PromotionType']      ?? $promotion['promotion_type']      ?? '';
$discount   = $promotion['DiscountPercentage'] ?? $promotion['discount_percentage'] ?? null;
$fixedPrice = $promotion['FixedPrice']         ?? $promotion['fixed_price']         ?? null;
$startRaw   = $promotion['StartDate']          ?? $promotion['start_date']          ?? '';
$endRaw     = $promotion['EndDate']            ?? $promotion['end_date']            ?? '';

$startObj = $startRaw ? new DateTime($startRaw) : null;
$endObj   = $endRaw   ? new DateTime($endRaw)   : null;

$startDateShort = $startObj ? $startObj->format('d M Y') : '';
$endDateShort   = $endObj   ? $endObj->format('d M Y')   : '';

$benefitLabel = 'None';
if ($promoType === 'discount' && $discount !== null) {
    $benefitLabel = rtrim(rtrim(number_format((float)$discount, 2), '0'), '.') . '% off';
} elseif ($promoType === 'fixed' && $fixedPrice !== null) {
    $benefitLabel = 'Fixed price: ' . number_format((float)$fixedPrice, 0);
}

$statusLabel = 'Unknown';
$statusClass = 'bg-secondary';

$now = new DateTime();

if ($startObj && $endObj) {
    if ($now < $startObj) {
        $statusLabel = 'Upcoming';
        $statusClass = 'bg-warning text-dark';
    } elseif ($now >= $startObj && $now <= $endObj) {
        $statusLabel = 'Active';
        $statusClass = 'bg-success';
    } else {
        $statusLabel = 'Expired';
        $statusClass = 'bg-secondary';
    }
}
?>

<div class="page-header mb-4">
    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">
        <div>
            <h1 class="h3 mb-1">
                Manage Products for Promotion:
                <span class="text-primary">
                    <?php echo htmlspecialchars($promoName, ENT_QUOTES, 'UTF-8'); ?>
                </span>
            </h1>
            <div class="small text-muted">
                <span class="me-2">
                    Type:
                    <span class="badge bg-info text-dark">
                        <?php echo htmlspecialchars(ucfirst((string)$promoType), ENT_QUOTES, 'UTF-8'); ?>
                    </span>
                </span>

                <span class="me-2">
                    Benefit:
                    <strong><?php echo htmlspecialchars($benefitLabel, ENT_QUOTES, 'UTF-8'); ?></strong>
                </span>

                <?php if ($startDateShort || $endDateShort): ?>
                    <span class="me-2">
                        Period:
                        <span class="fw-semibold">
                            <?php echo htmlspecialchars($startDateShort); ?>
                            &nbsp;→&nbsp;
                            <?php echo htmlspecialchars($endDateShort); ?>
                        </span>
                    </span>
                <?php endif; ?>

                <?php if ($statusLabel !== 'Unknown'): ?>
                    <span>
                        Status:
                        <span class="badge <?php echo $statusClass; ?>">
                            <?php echo htmlspecialchars($statusLabel, ENT_QUOTES, 'UTF-8'); ?>
                        </span>
                    </span>
                <?php endif; ?>
            </div>
        </div>

        <div>
            <a href="/index.php?controller=adminPromotion&action=manage" class="btn btn-outline-secondary btn-sm">
                ← Back to Promotions
            </a>
        </div>
    </div>
</div>

<div class="card shadow-sm">
    <form method="post" action="">
        <div class="card-header d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">
            <div>
                <h5 class="card-title mb-0">Products</h5>
                <small class="text-muted">
                    Check the products you want to link to this promotion.
                    Existing links with other promotions will not be changed.
                </small>
            </div>
            <div class="text-md-end">
                <button type="submit" class="btn btn-primary btn-sm">
                    Save Changes
                </button>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th style="width:40px;">
                                <input
                                    type="checkbox"
                                    class="form-check-input"
                                    id="select-all-products">
                            </th>
                            <th style="width:80px;">ID</th>
                            <th>Product Name</th>
                            <th style="width:260px;">Promotion Links</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($products)): ?>
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    No products found.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($products as $product): ?>
                                <?php
                                $productId   = (int)$product['id'];
                                $productName = $product['name'] ?? $product['Name'] ?? '';
                                $isInCurrent = in_array($productId, $assignedProducts, true);


                                $otherPromo  = $allAssignedProducts[$productId] ?? null;
                                $otherNames  = [];

                                if (is_array($otherPromo)) {
                                    $otherNames = $otherPromo;
                                } elseif (is_string($otherPromo) && $otherPromo !== '') {
                                    $otherNames = [$otherPromo];
                                }

                                $maxBadges = 2;
                                ?>
                                <tr>
                                    <td>
                                        <div class="form-check mb-0">
                                            <input
                                                type="checkbox"
                                                class="form-check-input js-product-checkbox"
                                                name="products[]"
                                                value="<?php echo $productId; ?>"
                                                <?php echo $isInCurrent ? 'checked' : ''; ?>>
                                        </div>
                                    </td>
                                    <td>#<?php echo $productId; ?></td>
                                    <td><?php echo htmlspecialchars((string)$productName, ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td class="small" style="max-width: 260px;">
                                        <?php if ($isInCurrent || !empty($otherNames)): ?>
                                            <div class="d-flex flex-wrap gap-1">
                                                <?php if ($isInCurrent): ?>
                                                    <span class="badge bg-success">
                                                        This promotion
                                                    </span>
                                                <?php endif; ?>

                                                <?php if (!empty($otherNames)): ?>
                                                    <?php
                                                    $shown = array_slice($otherNames, 0, $maxBadges);
                                                    $remaining = max(0, count($otherNames) - $maxBadges);
                                                    foreach ($shown as $name):
                                                    ?>
                                                        <span class="badge bg-warning text-dark">
                                                            <?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>
                                                        </span>
                                                    <?php endforeach; ?>

                                                    <?php if ($remaining > 0): ?>
                                                        <span
                                                            class="badge bg-light text-muted"
                                                            title="<?php echo htmlspecialchars(implode(', ', $otherNames), ENT_QUOTES, 'UTF-8'); ?>">
                                                            +<?php echo $remaining; ?> more
                                                        </span>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <span class="text-muted">None</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php if (!empty($totalPages) && $totalPages > 1): ?>
            <div class="card-body border-top">
                <nav aria-label="Product pagination">
                    <ul class="pagination pagination-sm mb-0 justify-content-center">
                        <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                            <a class="page-link"
                                href="/index.php?controller=adminPromotion&action=manageProducts&promotion_id=<?php echo (int)$promotionId; ?>&page=<?php echo max(1, $page - 1); ?>">
                                «
                            </a>
                        </li>

                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                <a class="page-link"
                                    href="/index.php?controller=adminPromotion&action=manageProducts&promotion_id=<?php echo (int)$promotionId; ?>&page=<?php echo $i; ?>">
                                    <?php echo $i; ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                            <a class="page-link"
                                href="/index.php?controller=adminPromotion&action=manageProducts&promotion_id=<?php echo (int)$promotionId; ?>&page=<?php echo min($totalPages, $page + 1); ?>">
                                »
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>

        <div class="card-footer d-flex justify-content-between align-items-center">
            <small class="text-muted">
                Tip: use the checkbox in the header to select / unselect all products.
            </small>
            <button type="submit" class="btn btn-primary btn-sm">
                Save Changes
            </button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAll = document.getElementById('select-all-products');
        const checkboxes = document.querySelectorAll('.js-product-checkbox');

        if (!selectAll) return;

        selectAll.addEventListener('change', function() {
            checkboxes.forEach(cb => {
                cb.checked = selectAll.checked;
            });
        });


        const allChecked = Array.from(checkboxes).length > 0 &&
            Array.from(checkboxes).every(cb => cb.checked);

        if (allChecked) {
            selectAll.checked = true;
        }
    });
</script>

<?php
require 'views/admin/components/admin_footer.php';
?>