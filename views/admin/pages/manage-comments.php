<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
function e($s)
{
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

$search    = $_GET['search']   ?? '';
$ratingVal = $_GET['rating']   ?? '';
$shoesVal  = $_GET['shoes_id'] ?? '';
$sortVal   = $_GET['sort']     ?? 'newest';
?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h1 class="mb-1">Comments</h1>
        <p class="text-muted small mb-0">
            Manage product reviews from customers and guests.
        </p>
    </div>
</div>

<form method="get" class="card mb-4 mt-4 shadow-sm">
    <input type="hidden" name="controller" value="adminComments">
    <input type="hidden" name="action" value="manage">
    <div class="card-body">
        <div class="row g-3 align-items-end">

            <div class="col-12 col-lg-4">
                <label for="cm_search" class="form-label fw-semibold small mb-1">Search</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="ti ti-search text-muted"></i></span>
                    <input
                        id="cm_search"
                        type="search"
                        class="form-control"
                        name="search"
                        placeholder="Search by content or guest name..."
                        value="<?= e($search ?? '') ?>">
                </div>
            </div>

            <div class="col-12 col-md-5 col-lg-3">
                <label for="cm_shoes_id" class="form-label fw-semibold small mb-1">Product</label>
                <select id="cm_shoes_id" name="shoes_id" class="form-select">
                    <option value="">All products</option>
                    <?php foreach ($shoesList as $shoe): ?>
                        <option
                            value="<?= (int)$shoe['ShoesID'] ?>"
                            <?= (($shoesVal ?? '') === (string)$shoe['ShoesID'] ? 'selected' : '') ?>>
                            <?= e($shoe['Name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-6 col-md-auto">
                <label for="cm_rating" class="form-label fw-semibold small mb-1">Rating</label>
                <select id="cm_rating" name="rating" class="form-select field-narrow">
                    <option value="">All</option>
                    <?php for ($r = 5; $r >= 1; $r--): ?>
                        <option value="<?= $r ?>" <?= (($ratingVal ?? '') === (string)$r ? 'selected' : '') ?>>
                            <?= $r ?> stars
                        </option>
                    <?php endfor; ?>
                </select>
            </div>


            <div class="col-6 col-md-3 col-lg-2">
                <label for="cm_sort" class="form-label fw-semibold small mb-1">Sort by</label>
                <select id="cm_sort" name="sort" class="form-select field-narrow">
                    <option value="newest" <?= ($sortVal ?? 'newest') === 'newest'      ? 'selected' : '' ?>>Newest first</option>
                    <option value="oldest" <?= ($sortVal ?? '') === 'oldest'           ? 'selected' : '' ?>>Oldest first</option>
                    <option value="rating_desc" <?= ($sortVal ?? '') === 'rating_desc'      ? 'selected' : '' ?>>Rating: high to low</option>
                    <option value="rating_asc" <?= ($sortVal ?? '') === 'rating_asc'       ? 'selected' : '' ?>>Rating: low to high</option>
                </select>
            </div>

            <div class="col-6 col-md-auto">
                <label for="cm_limit" class="form-label fw-semibold small mb-1">Items per page</label>
                <select id="cm_limit" name="limit" class="form-select field-narrow">
                    <?php
                    $cmCurrentLimit = (int)($limit ?? 20);
                    foreach ([10, 20, 50, 100] as $opt):
                    ?>
                        <option value="<?= $opt ?>" <?= $cmCurrentLimit === $opt ? 'selected' : '' ?>>
                            <?= $opt ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

        </div>

        <div class="mt-3 d-flex justify-content-end gap-2">
            <button type="button" id="cm_bulk_apply" class="btn btn-sm btn-outline-danger">
                Delete selected
            </button>
            <a href="/index.php?controller=adminComments&action=manage" class="btn btn-outline-secondary">
                Reset
            </a>
            <button type="submit" class="btn btn-primary">
                Apply filters
            </button>
        </div>
    </div>
</form>

<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive table-wrapper">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light border-bottom">
                    <tr>
                        <th style="width: 40px;">
                            <input type="checkbox" id="cm_select_all" class="form-check-input">
                        </th>
                        <th style="width: 60px;">ID</th>
                        <th style="width: 120px;">Product</th>
                        <th style="width: 90px;">Rating</th>
                        <th>Content</th>
                        <th style="width: 180px;">Author</th>
                        <th style="width: 120px;">Date</th>
                        <th style="width: 70px;" class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($comments)): ?>
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                No comments found for the current filters.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($comments as $c): ?>
                            <?php
                            $author = $c['GuestName'] ?: ($c['MemberName'] ?: ('Member #' . $c['Mem_ID']));
                            $content = $c['Content'] ?: '(No text, rating only)';
                            $short   = mb_strimwidth($content, 0, 80, '...');
                            ?>
                            <tr>
                                <td>
                                    <input
                                        type="checkbox"
                                        class="form-check-input cm-select"
                                        value="<?= (int)$c['CommentID'] ?>">
                                </td>
                                <td><?= (int)$c['CommentID'] ?></td>
                                <td>
                                    <?php if (!empty($c['Name'])): ?>
                                        <div class="fw-semibold small"><?= e($c['Name']) ?></div>
                                        <div class="text-muted small">Shoes ID: <?= (int)$c['ShoesID'] ?></div>
                                    <?php else: ?>
                                        <span class="text-muted small">Shoes ID: <?= (int)$c['ShoesID'] ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-warning text-dark">
                                        <?= (int)$c['Rating'] ?> ★
                                    </span>
                                </td>
                                <td>
                                    <span title="<?= e($content) ?>"><?= e($short) ?></span>
                                </td>
                                <td>
                                    <div class="fw-semibold small"><?= e($author) ?></div>
                                    <?php if (!empty($c['Mem_ID'])): ?>
                                        <div class="text-muted small">Member ID: <?= (int)$c['Mem_ID'] ?></div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= e(date('Y-m-d', strtotime($c['Date']))) ?>
                                </td>
                                <td class="text-end">
                                    <a href="/index.php?controller=adminComments&action=delete&id=<?= (int)$c['CommentID'] ?>"
                                        class="btn btn-sm btn-outline-danger"
                                        onclick="return confirm('Delete this comment?');">
                                        <i class="ti ti-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php if (isset($totalPages)): ?>
        <div class="card-footer d-flex justify-content-between align-items-center">
            <p class="mb-0 text-muted small">
                Page <?= (int)$page ?> of <?= (int)$totalPages ?> —
                Total <?= (int)$totalComments ?> comments
            </p>

            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <?php
                    $baseUrl = '/index.php?controller=adminComments&action=manage'
                        . '&search=' . urlencode($search)
                        . '&rating=' . urlencode($ratingVal)
                        . '&shoes_id=' . urlencode($shoesVal)
                        . '&sort=' . urlencode($sortVal)
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


<div id="toastContainer" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1080;"></div>

<?php if (!empty($flash)): ?>
    <script>
        window.__cmFlash = <?= json_encode($flash, JSON_UNESCAPED_UNICODE) ?>;
    </script>
<?php endif; ?>

<div class="modal modal-blur fade" id="cmConfirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
        <div class="modal-content">
            <button type="button" class="btn-close ms-auto mt-2 me-2" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-danger"></div>
            <div class="modal-body text-center py-4">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="icon mb-2 text-danger icon-lg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    stroke-width="2"
                    stroke="currentColor"
                    fill="none"
                    stroke-linecap="round"
                    stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M12 9v2m0 4v.01" />
                    <path
                        d="M5 19h14a2 2 0 0 0 1.84 -2.75l-7.1 -12.25a2 2 0 0 0 -3.5 0l-7.1 12.25a2 2 0 0 0 1.75 2.75" />
                </svg>
                <h3>Delete selected comments?</h3>
                <div class="text-secondary">
                    You are about to delete
                    <span id="cm_confirm_count" class="fw-semibold">0</span>
                    comment(s). This action cannot be undone.
                </div>
            </div>
            <div class="modal-footer">
                <div class="w-100">
                    <div class="row">
                        <div class="col">
                            <button type="button" class="btn w-100" data-bs-dismiss="modal">
                                Cancel
                            </button>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-danger w-100" id="cm_confirm_delete_btn">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    (function() {
        window.addEventListener('hide.bs.modal', event => {
            document.activeElement.blur()
        })

        window.addEventListener('load', function() {
            const toastContainer = document.getElementById('toastContainer');
            if (!toastContainer) return;

            function showToast(type, message, title = '') {
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
            }

            window.adminCommentsShowToast = showToast;


            const selectAll = document.getElementById('cm_select_all');
            const bulkBtn = document.getElementById('cm_bulk_apply');

            const modalEl = document.getElementById('cmConfirmModal');
            const countEl = document.getElementById('cm_confirm_count');
            const confirmBtn = document.getElementById('cm_confirm_delete_btn');
            const confirmModal = (typeof tabler !== 'undefined' && modalEl) ?
                new tabler.Modal(modalEl) :
                null;

            let pendingIds = [];


            function updateSelectAllState() {
                if (!selectAll) return;
                const boxes = document.querySelectorAll('.cm-select');
                const checked = document.querySelectorAll('.cm-select:checked');
                if (!boxes.length) {
                    selectAll.checked = false;
                    selectAll.indeterminate = false;
                    return;
                }
                selectAll.checked = checked.length === boxes.length;
                selectAll.indeterminate = checked.length > 0 && checked.length < boxes.length;
            }

            if (selectAll) {
                selectAll.addEventListener('change', function(e) {
                    const checked = e.target.checked;
                    document
                        .querySelectorAll('.cm-select')
                        .forEach(cb => cb.checked = checked);
                    updateSelectAllState();
                });
            }

            document.addEventListener('change', function(e) {
                if (e.target.classList && e.target.classList.contains('cm-select')) {
                    updateSelectAllState();
                }
            });

            if (bulkBtn) {
                bulkBtn.addEventListener('click', function() {
                    const ids = Array.from(
                        document.querySelectorAll('.cm-select:checked')
                    ).map(cb => cb.value);

                    if (!ids.length) {
                        showToast('warning', 'No comments selected.');
                        return;
                    }

                    pendingIds = ids;

                    if (confirmModal && countEl) {
                        countEl.textContent = String(pendingIds.length);
                        confirmModal.show();
                    } else {
                        if (!window.confirm('Delete selected comments?')) return;
                        doBulkDelete();
                    }
                });
            }

            function doBulkDelete() {
                if (!pendingIds.length) return;

                const params = new URLSearchParams();
                pendingIds.forEach(id => params.append('ids[]', id));

                fetch('/index.php?controller=adminComments&action=bulkUpdate', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: params.toString()
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.ok) {
                            showToast('success', 'Selected comments have been deleted.', 'Success');
                            setTimeout(() => window.location.reload(), 1500);
                        } else {
                            showToast('danger', data.error || 'Bulk delete failed.', 'Error');
                        }
                    })
                    .catch(err => {
                        showToast('danger', 'Bulk delete failed: ' + err, 'Error');
                    })
                    .finally(() => {
                        pendingIds = [];
                    });
            }

            if (confirmBtn) {
                confirmBtn.addEventListener('click', function() {
                    if (confirmModal) {
                        confirmModal.hide();
                    }
                    doBulkDelete();
                });
            }


            if (window.__cmFlash) {
                const f = window.__cmFlash;
                showToast(
                    f.type || 'info',
                    f.message || '',
                    f.type === 'success' ? 'Success' : 'Error'
                );
            }
        });
    })();
</script>