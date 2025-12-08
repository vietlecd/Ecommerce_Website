<style>
    .filter-bar .btn-search {
        min-width: 110px;
    }
</style>


<div class="row align-items-center g-3">
    <div class="col-12 col-lg-6">
        <h1 class="mb-1">Manage Promotions</h1>
    </div>
    <div class="col-12 col-lg-6">
        <div class="d-flex gap-2 justify-content-lg-end">
            <button type="button" class="btn btn-primary"
                data-bs-toggle="modal"
                data-action="create"
                data-bs-target="#promotionModal">
                <i class="ti ti-plus me-1"></i>
                <span class="d-none d-sm-inline">New Promotion</span>
            </button>
        </div>
    </div>
</div>


<div class="actions">
    <form class="card shadow-sm mb-4 mt-4" method="get" action="index.php">
        <input type="hidden" name="controller" value="adminPromotion">
        <input type="hidden" name="action" value="manage">
        <input type="hidden" name="page" value="<?= (int)($page ?? 1); ?>">

        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-lg-4">
                    <label class="form-label mb-1 fw-semibold small" for="promo_keyword">Search</label>
                    <input
                        type="text"
                        class="form-control"
                        id="promo_keyword"
                        name="keyword"
                        placeholder="Search by name..."
                        value="<?= isset($keyword) ? htmlspecialchars($keyword, ENT_QUOTES, 'UTF-8') : ''; ?>">
                </div>

                <div class="col-6 col-md-3 col-lg-2">
                    <label class="form-label mb-1 fw-semibold small" for="promo_from">From</label>
                    <input
                        type="date"
                        name="from"
                        id="promo_from"
                        class="form-control field-narrow"
                        value="<?= isset($_GET['from']) ? htmlspecialchars($_GET['from'], ENT_QUOTES, 'UTF-8') : ''; ?>">
                </div>

                <div class="col-6 col-md-3 col-lg-2">
                    <label class="form-label mb-1 fw-semibold small" for="promo_to">To</label>
                    <input
                        type="date"
                        name="to"
                        id="promo_to"
                        class="form-control field-narrow"
                        value="<?= isset($_GET['to']) ? htmlspecialchars($_GET['to'], ENT_QUOTES, 'UTF-8') : ''; ?>">
                </div>

                <div class="col-6 col-md-3 col-lg-2">
                    <label class="form-label mb-1 fw-semibold small" for="promo_sort">Sort by</label>
                    <?php $sortDir = $sort ?? 'ASC'; ?>
                    <select
                        name="sort"
                        id="promo_sort"
                        class="form-select field-narrow">
                        <option value="ASC" <?= $sortDir === 'ASC'  ? 'selected' : ''; ?>>ID Ascending</option>
                        <option value="DESC" <?= $sortDir === 'DESC' ? 'selected' : ''; ?>>ID Descending</option>
                    </select>
                </div>

                <div class="col-6 col-md-auto">
                    <label class="form-label mb-1 fw-semibold small" for="promo_limit">Items per page</label>
                    <?php $promoLimit = (int)($limit ?? 10); ?>
                    <select
                        name="limit"
                        id="promo_limit"
                        class="form-select field-narrow">
                        <?php foreach ([10, 20, 50, 100] as $opt): ?>
                            <option value="<?= $opt ?>" <?= $promoLimit === $opt ? 'selected' : ''; ?>>
                                <?= $opt ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>
            <div class="mt-3 d-flex justify-content-end gap-2">
                <div class="d-flex gap-2">
                    <a
                        href="/index.php?controller=adminPromotion&action=manage"
                        class="btn btn-outline-secondary">
                        Reset
                    </a>
                    <button type="submit" class="btn btn-primary btn-search">
                        Apply filters
                    </button>
                </div>
            </div>
        </div>
    </form>



    <div class="card shadow-sm">
        <div class="table-wrapper table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="border-bottom">
                    <tr>
                        <th>ID</th>
                        <th>Name / Type</th>
                        <th>Discount / Fixed</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($promotions)): ?>
                        <tr>
                            <td colspan="8">No promotions found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($promotions as $promotion): ?>
                            <?php
                            $id   = (int)($promotion['PromotionID']        ?? $promotion['promotion_id']        ?? 0);
                            $name =        $promotion['PromotionName']     ?? $promotion['promotion_name']      ?? '';
                            $type =        $promotion['PromotionType']     ?? $promotion['promotion_type']      ?? '';
                            $start =       $promotion['StartDate']         ?? $promotion['start_date']          ?? null;
                            $end   =       $promotion['EndDate']           ?? $promotion['end_date']            ?? null;
                            $discount =    $promotion['DiscountPercentage'] ?? $promotion['discount_percentage'] ?? null;
                            $fixed    =    $promotion['FixedPrice']        ?? $promotion['fixed_price']         ?? null;


                            $now = new DateTime();
                            $startDate = $start ? new DateTime($start) : null;
                            $endDate   = $end   ? new DateTime($end)   : null;

                            if (!$startDate || !$endDate) {
                                $statusHtml = '<span class="status-pending">Pending</span>';
                            } elseif ($now < $startDate) {
                                $statusHtml = '<span class="status-pending">Pending</span>';
                            } elseif ($now >= $startDate && $now <= $endDate) {
                                $statusHtml = '<span class="status-active">Active</span>';
                            } else {
                                $statusHtml = '<span class="status-expired">Expired</span>';
                            }

                            $benefitText = '';
                            if ($type === 'discount' && $discount !== null) {
                                $benefitText = rtrim(rtrim(number_format((float)$discount, 2), '0'), '.') . '% off';
                            } elseif ($type === 'fixed' && $fixed !== null) {
                                $benefitText = 'Fixed: ' . number_format((float)$fixed, 0);
                            }
                            ?>
                            <tr>
                                <td><?php echo $id; ?></td>
                                <td>
                                    <?php echo htmlspecialchars((string)$name); ?><br>
                                    <small><?php echo htmlspecialchars(ucfirst((string)$type)); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars((string)$benefitText); ?></td>
                                <td><?php echo htmlspecialchars((string)$start); ?></td>
                                <td><?php echo htmlspecialchars((string)$end); ?></td>
                                <td><?php echo $statusHtml; ?></td>
                                <td>
                                    <div class="d-flex gap-1" role="group" aria-label="actions">
                                        <a href="/index.php?controller=adminPromotion&action=manageProducts&promotion_id=<?php echo $id; ?>"
                                            class="btn btn-sm btn-outline-primary"> <i class="ti ti-eye"></i></a>
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#promotionModal"
                                            data-action="edit"
                                            data-id="<?php echo $id; ?>"
                                            data-name="<?php echo htmlspecialchars((string)$name, ENT_QUOTES); ?>"
                                            data-type="<?php echo htmlspecialchars((string)$type, ENT_QUOTES); ?>"
                                            data-discount="<?php echo $discount !== null ? (float)$discount : ''; ?>"
                                            data-fixed="<?php echo $fixed !== null ? (float)$fixed : ''; ?>"
                                            data-start="<?php echo $start ? substr($start, 0, 10) : ''; ?>"
                                            data-end="<?php echo $end ? substr($end, 0, 10) : ''; ?>">
                                            <i class="ti ti-edit"></i>
                                        </button>
                                        <a href="/index.php?controller=adminPromotion&action=delete&promotion_id=<?php echo $id; ?>"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('Are you sure you want to delete this promotion?')"><i class="ti ti-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    <?php endif; ?>
                </tbody>
            </table>

        </div>
        <?php if (isset($totalPages)): ?>
            <div class="card-footer bg-white d-flex justify-content-between align-items-center">
                <p class="mb-0 text-muted small">
                    Page <?= (int)$page ?> of <?= (int)$totalPages ?> —
                    Total <?= (int)$totalPromotions ?> promotions
                </p>

                <nav aria-label="Pagination">
                    <ul class="pagination pagination-sm mb-0">
                        <?php
                        $baseUrl = '/index.php?controller=adminPromotion&action=manage'
                            . '&keyword=' . urlencode($keyword ?? '')
                            . '&sort=' . urlencode($sort)
                            . '&from=' . urlencode($_GET['from'] ?? '')
                            . '&to=' . urlencode($_GET['to'] ?? '')
                            . '&limit=' . (int)$limit;

                        $window     = 3;
                        $totalPages = max(1, (int)$totalPages);
                        $page       = max(1, min($page, $totalPages));

                        $half  = intdiv($window, 2);

                        $start = max(1, $page - $half);
                        $end   = min($totalPages, $start + $window - 1);
                        if ($end - $start + 1 < $window) {
                            $start = max(1, $end - $window + 1);
                        }
                        ?>

                        <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link"
                                href="<?= $page <= 1 ? '#' : $baseUrl . '&page=' . ($page - 1) ?>">
                                «
                            </a>
                        </li>

                        <?php if ($start > 1): ?>
                            <li class="page-item <?= $page === 1 ? 'active' : '' ?>">
                                <a class="page-link" href="<?= $baseUrl . '&page=1' ?>">1</a>
                            </li>

                            <?php if ($start > 2): ?>
                                <li class="page-item disabled">
                                    <span class="page-link">…</span>
                                </li>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php for ($p = $start; $p <= $end; $p++): ?>
                            <li class="page-item <?= $p === $page ? 'active' : '' ?>">
                                <a class="page-link" href="<?= $baseUrl . '&page=' . $p ?>">
                                    <?= $p ?>
                                </a>
                            </li>
                        <?php endfor; ?>

                        <?php if ($end < $totalPages): ?>
                            <?php if ($end < $totalPages - 1): ?>
                                <li class="page-item disabled">
                                    <span class="page-link">…</span>
                                </li>
                            <?php endif; ?>

                            <li class="page-item <?= $page === $totalPages ? 'active' : '' ?>">
                                <a class="page-link" href="<?= $baseUrl . '&page=' . $totalPages ?>">
                                    <?= $totalPages ?>
                                </a>
                            </li>
                        <?php endif; ?>

                        <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                            <a class="page-link"
                                href="<?= $page >= $totalPages ? '#' : $baseUrl . '&page=' . ($page + 1) ?>">
                                »
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        <?php endif; ?>
    </div>
</div>
<div id="toastContainer" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1080;"></div>
<?php require 'views/admin/components/promotion-modal.php'; ?>


<script>
    window.addEventListener('hide.bs.modal', event => {
        document.activeElement.blur()
    })

    document.addEventListener('DOMContentLoaded', function() {
        window.showToast = function(type, message, title) {
            const config = {
                success: {
                    dotClass: 'bg-success',
                    badgeClass: 'badge bg-success-lt text-success text-uppercase',
                    defaultTitle: 'Success'
                },
                danger: {
                    dotClass: 'bg-danger',
                    badgeClass: 'badge bg-danger-lt text-danger text-uppercase',
                    defaultTitle: 'Error'
                },
                warning: {
                    dotClass: 'bg-warning',
                    badgeClass: 'badge bg-warning-lt text-warning text-uppercase',
                    defaultTitle: 'Warning'
                },
                info: {
                    dotClass: 'bg-info',
                    badgeClass: 'badge bg-info-lt text-info text-uppercase',
                    defaultTitle: 'Info'
                }
            };

            const cfg = config[type] || config.info;

            const html = `
                <div class="toast border-0 shadow-sm mb-2" role="status" aria-live="assertive" aria-atomic="true">
                    <div class="toast-body d-flex align-items-start gap-2 p-3">
                        <span class="status-dot status-dot-animated ${cfg.dotClass} mt-1"></span>
                        <div class="flex-fill">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="fw-semibold">${title || cfg.defaultTitle}</span>
                                <span class="${cfg.badgeClass}">${(type || 'info')}</span>
                            </div>
                            <div class="text-muted small">
                                ${message}
                            </div>
                        </div>
                        <button type="button" class="btn-close ms-2" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            `;

            const wrap = document.createElement('div');
            wrap.innerHTML = html.trim();
            const el = wrap.firstElementChild;

            toastContainer.appendChild(el);

            const t = new tabler.Toast(el, {
                delay: 4000,
                autohide: true
            });
            t.show();

            el.addEventListener('hidden.bs.toast', () => el.remove());

        };

        <?php if (isset($_SESSION['message'])): ?>
            if (window.showToast) {
                window.showToast('success', <?php echo json_encode($_SESSION['message']); ?>, 'Success');
            }
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            if (window.showToast) {
                window.showToast('danger', <?php echo json_encode($_SESSION['error']); ?>, 'Error');
            }
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    });
</script>