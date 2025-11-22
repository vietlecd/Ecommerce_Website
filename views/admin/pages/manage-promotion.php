<?php require 'views/admin/components/header.php'; ?>

<style>
    .filter-bar .field-narrow {
        max-width: 180px;
    }

    .filter-bar .btn-search {
        min-width: 110px;
    }
</style>


<div class="page-header">
    <div class="row align-items-center g-3">
        <div class="col-12 col-lg-6">
            <h1 class="h3 mb-1">Manage Promotions</h1>
        </div>
        <div class="col-12 col-lg-6">
            <div class="d-flex gap-2 justify-content-lg-end">
                <button type="button" class="btn btn-primary"
                    data-bs-toggle="modal"
                    data-action="create"
                    data-bs-target="#promotionModal">
                    <i class="fas fa-plus me-1"></i>
                    <span class="d-none d-sm-inline">New Promotion</span>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- <?php if (isset($_SESSION['message']) || isset($_SESSION['error'])): ?>
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1100;">
        <?php if (isset($_SESSION['message'])): ?>
            <div class="toast align-items-center text-bg-success border-0 mb-2"
                role="alert"
                aria-live="assertive"
                aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <?php echo htmlspecialchars($_SESSION['message'], ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                    <button type="button"
                        class="btn-close btn-close-white me-2 m-auto"
                        data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="toast align-items-center text-bg-danger border-0"
                role="alert"
                aria-live="assertive"
                aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        <?php echo htmlspecialchars($_SESSION['error'], ENT_QUOTES, 'UTF-8'); ?>
                    </div>
                    <button type="button"
                        class="btn-close btn-close-white me-2 m-auto"
                        data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toastElList = [].slice.call(document.querySelectorAll('.toast'));
            toastElList.forEach(function(toastEl) {
                var toast = new bootstrap.Toast(toastEl, {
                    delay: 4000
                });
                toast.show();
            });
        });
    </script>
<?php endif; ?> -->


<div class="actions">
    <div class="card shadow-sm mb-4 mt-4">
        <div class="card-body">
            <form class="row g-3 align-items-end filter-bar" method="GET" action="index.php">
                <input type="hidden" name="controller" value="adminPromotion">
                <input type="hidden" name="action" value="manage">
                <input type="hidden" name="page" value="<?php echo (int)$page; ?>">

                <div class="col-12 col-lg-4">
                    <label class="form-label mb-1 small text-muted">Keyword</label>
                    <input
                        type="text"
                        class="form-control"
                        name="keyword"
                        placeholder="Search by name..."
                        value="<?php echo isset($keyword) ? htmlspecialchars($keyword) : ''; ?>">
                </div>

                <!-- From -->
                <div class="col-6 col-md-auto">
                    <label class="form-label mb-1 small text-muted">From</label>
                    <input
                        type="date"
                        name="from"
                        class="form-control field-narrow"
                        value="<?php echo isset($_GET['from']) ? htmlspecialchars($_GET['from']) : ''; ?>">
                </div>

                <!-- To -->
                <div class="col-6 col-md-auto">
                    <label class="form-label mb-1 small text-muted">To</label>
                    <input
                        type="date"
                        name="to"
                        class="form-control field-narrow"
                        value="<?php echo isset($_GET['to']) ? htmlspecialchars($_GET['to']) : ''; ?>">
                </div>

                <div class="col-12 col-md-auto d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-search">
                        Search
                    </button>
                </div>

                <div class="col-12 col-md-auto ms-lg-auto">
                    <label class="form-label mb-1 small text-muted d-flex justify-content-lg-end">
                        Sort by ID
                    </label>
                    <select
                        name="sort"
                        class="form-select field-narrow"
                        onchange="this.form.submit()">
                        <option value="ASC" <?php echo (isset($sort) && $sort === 'ASC')  ? 'selected' : ''; ?>>
                            ID Ascending
                        </option>
                        <option value="DESC" <?php echo (isset($sort) && $sort === 'DESC') ? 'selected' : ''; ?>>
                            ID Descending
                        </option>
                    </select>
                </div>
            </form>

        </div>
    </div>


    <div class="card shadow-sm">
        <div class="table-wrapper mt-3">
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
                                            class="btn btn-sm btn-outline-primary btn-action"> <i class="fas fa-eye"></i></a>
                                        <button
                                            type="button"
                                            class="btn btn-sm btn-outline-primary btn-action"
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
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="/index.php?controller=adminPromotion&action=delete&promotion_id=<?php echo $id; ?>"
                                            class="btn btn-sm btn-outline-danger btn-action"
                                            onclick="return confirm('Are you sure you want to delete this article?')"><i class="fas fa-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    <?php endif; ?>
                </tbody>
            </table>

            <?php if (!empty($totalPages) && $totalPages > 1): ?>
                <div class="card-footer">
                    <div class="pagination pagination-sm justify-content-center mb-0">
                        <?php if ($totalPages > 1): ?>
                            <?php if ($page > 1): ?>
                                <a href="index.php?controller=adminPromotion&action=manage&page=<?php echo $page - 1; ?>&keyword=<?php echo urlencode($keyword ?? ''); ?>&sort=<?php echo $sort; ?>&from=<?php echo urlencode($_GET['from'] ?? ''); ?>&to=<?php echo urlencode($_GET['to'] ?? ''); ?>">Previous</a>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <a href="index.php?controller=adminPromotion&action=manage&page=<?php echo $i; ?>&keyword=<?php echo urlencode($keyword ?? ''); ?>&sort=<?php echo $sort; ?>&from=<?php echo urlencode($_GET['from'] ?? ''); ?>&to=<?php echo urlencode($_GET['to'] ?? ''); ?>"
                                    <?php echo $i === $page ? 'class="active"' : ''; ?>>
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>

                            <?php if ($page < $totalPages): ?>
                                <a href="index.php?controller=adminPromotion&action=manage&page=<?php echo $page + 1; ?>&keyword=<?php echo urlencode($keyword ?? ''); ?>&sort=<?php echo $sort; ?>&from=<?php echo urlencode($_GET['from'] ?? ''); ?>&to=<?php echo urlencode($_GET['to'] ?? ''); ?>">Next</a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <div class="card-footer bg-white">
                        <nav aria-label="Pagination">
                            <ul class="pagination pagination-sm justify-content-center mb-0">
                                <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                                    <a class="page-link"
                                        href="/index.php?controller=adminPromotion&action=manage&keyword=<?php echo urlencode($keyword ?? ''); ?>&sort=<?php echo $sort; ?>&from=<?php echo urlencode($_GET['from'] ?? ''); ?>&to=<?php echo urlencode($_GET['to'] ?? ''); ?>&page=<?php echo max(1, $page - 1); ?>">«</a>
                                </li>
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?php echo $i === $page ? 'active' : ''; ?>">
                                        <a class="page-link"
                                            href="/index.php?controller=adminPromotion&action=manage&keyword=<?php echo urlencode($keyword ?? ''); ?>&sort=<?php echo $sort; ?>&from=<?php echo urlencode($_GET['from'] ?? ''); ?>&to=<?php echo urlencode($_GET['to'] ?? ''); ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endfor; ?>
                                <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                                    <a class="page-link"
                                        href="/index.php?controller=adminPromotion&action=manage&keyword=<?php echo urlencode($keyword ?? ''); ?>&sort=<?php echo $sort; ?>&from=<?php echo urlencode($_GET['from'] ?? ''); ?>&to=<?php echo urlencode($_GET['to'] ?? ''); ?>&page=<?php echo min($totalPages, $page + 1); ?>">»</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php require 'views/admin/components/promotion-modal.php'; ?>


<?php require 'views/admin/components/admin_footer.php'; ?>


<script>
    window.showToast = function(message, type) {
        type = type || 'success';

        var container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'position-fixed top-0 end-0 p-3';
            container.style.zIndex = '1100';
            document.body.appendChild(container);
        }

        var toastEl = document.createElement('div');
        toastEl.className =
            'toast align-items-center text-bg-' +
            ((type === 'danger' || type === 'error') ? 'danger' : 'success') +
            ' border-0 mb-2';

        toastEl.setAttribute('role', 'alert');
        toastEl.setAttribute('aria-live', 'assertive');
        toastEl.setAttribute('aria-atomic', 'true');

        toastEl.innerHTML = `
            <div class="d-flex">
                <div class="toast-body"></div>
                <button type="button"
                    class="btn-close btn-close-white me-2 m-auto"
                    data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        `;

        toastEl.querySelector('.toast-body').textContent = message;

        container.appendChild(toastEl);

        var bsToast = new bootstrap.Toast(toastEl, {
            delay: 4000
        });
        bsToast.show();
    };


    document.addEventListener('DOMContentLoaded', function() {
        <?php if (isset($_SESSION['message'])): ?>
            if (window.showToast) {
                window.showToast(<?php echo json_encode($_SESSION['message']); ?>, 'success');
            }
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            if (window.showToast) {
                window.showToast(<?php echo json_encode($_SESSION['error']); ?>, 'danger');
            }
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    });
</script>