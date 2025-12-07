<style>
    .filter-bar .field-narrow {
        max-width: 180px;
    }

    .filter-bar .btn-search {
        min-width: 110px;
    }

    .btn {
        padding: 8px 8px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
    }
</style>

<?php $sortNews = $sort ?? 'newest'; ?>
<div class="row align-items-center g-3">
    <div class="col-12 col-lg-6">
        <h1 class=" mb-1">Manage News</h1>
        <p class="text-muted mb-0 small">Manage articles and visibility status</p>
    </div>
    <div class="col-12 col-lg-6">
        <div class="d-flex gap-2 justify-content-lg-end">
            <a href="/index.php?controller=adminNews&action=addNews" class="btn btn-primary">
                <i class="ti ti-plus me-1"></i>
                <span class="d-none d-sm-inline">Add Article</span>
            </a>
        </div>
    </div>
</div>


<div class="alert alert-success alert-dismissible fade show d-none" role="alert" id="successAlert">
    <i class="fas fa-check-circle me-2"></i>
    <span id="successText">Action successful!</span>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>

<div class="alert alert-danger alert-dismissible fade show d-none" role="alert" id="errorAlert">
    <i class="fas fa-exclamation-circle me-2"></i>
    <span id="errorText">An error occurred!</span>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>


<div class="card shadow-sm mb-4 mt-4">
    <div class="card-body">
        <form method="get" class="row g-3 align-items-end">
            <input type="hidden" name="controller" value="adminNews">
            <input type="hidden" name="action" value="manage">

            <div class="col-12 col-lg-4">
                <label class="form-label fw-semibold small mb-1" for="news_search">Search</label>
                <div class="input-group search-input-group">
                    <span class="input-group-text">
                        <i class="ti ti-search text-muted"></i>
                    </span>
                    <input
                        type="text"
                        name="search"
                        id="news_search"
                        class="form-control"
                        placeholder="Enter title, description or author..."
                        value="<?= htmlspecialchars($search ?? '', ENT_QUOTES, 'UTF-8'); ?>">
                </div>
            </div>

            <div class="col-6 col-md-3 col-lg-2">
                <label class="form-label fw-semibold small mb-1" for="news_status">Status</label>
                <select name="status" id="news_status" class="form-select field-narrow">
                    <?php $stVal = $status ?? 'all'; ?>
                    <option value="all" <?= $stVal === 'all'     ? 'selected' : ''; ?>>All</option>
                    <option value="pending" <?= $stVal === 'pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="active" <?= $stVal === 'active'  ? 'selected' : ''; ?>>Active</option>
                    <option value="expired" <?= $stVal === 'expired' ? 'selected' : ''; ?>>Expired</option>
                </select>
            </div>

            <div class="col-6 col-md-3 col-lg-2">
                <label class="form-label fw-semibold small mb-1" for="news_sort">Sort by</label>
                <select name="sort" id="news_sort" class="form-select field-narrow">

                    <option value="newest" <?= $sortNews === 'newest'      ? 'selected' : ''; ?>>Newest first</option>
                    <option value="oldest" <?= $sortNews === 'oldest'      ? 'selected' : ''; ?>>Oldest first</option>
                    <option value="views_desc" <?= $sortNews === 'views_desc'  ? 'selected' : ''; ?>>Most viewed</option>
                    <option value="views_asc" <?= $sortNews === 'views_asc'   ? 'selected' : ''; ?>>Least viewed</option>
                    <option value="author_asc" <?= $sortNews === 'author_asc'  ? 'selected' : ''; ?>>Author A–Z</option>
                    <option value="author_desc" <?= $sortNews === 'author_desc' ? 'selected' : ''; ?>>Author Z–A</option>
                    <option value="title_asc" <?= $sortNews === 'title_asc'   ? 'selected' : ''; ?>>Title A–Z</option>
                    <option value="title_desc" <?= $sortNews === 'title_desc'  ? 'selected' : ''; ?>>Title Z–A</option>
                    <option value="id_asc" <?= $sortNews === 'id_asc'      ? 'selected' : ''; ?>>ID Ascending</option>
                    <option value="id_desc" <?= $sortNews === 'id_desc'     ? 'selected' : ''; ?>>ID Descending</option>
                </select>
            </div>

            <div class="col-6 col-md-3 col-lg-2">
                <label class="form-label fw-semibold small mb-1" for="news_limit">Items per page</label>
                <select name="limit" id="news_limit" class="form-select field-narrow">
                    <?php
                    $currentLimit = (int)($limit ?? 10);
                    foreach ([10, 20, 50, 100] as $opt):
                    ?>
                        <option value="<?= $opt ?>" <?= $currentLimit === $opt ? 'selected' : ''; ?>>
                            <?= $opt ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-12 col-md-3 col-lg-2 d-flex align-items-end">
                <div class="d-flex gap-2 w-100 justify-content-md-end">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="ti ti-filter me-1"></i>Filter
                    </button>
                    <a
                        href="/index.php?controller=adminNews&action=manage"
                        class="btn btn-outline-secondary"
                        title="Reset">
                        <i class="ti ti-reload"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card shadow-sm">
    <div class="table-responsive table-wrapper">
        <table class="table table-hover align-middle mb-0">
            <colgroup>
                <col>
                <col style="width: 120px;">
                <col style="min-width: 240px;">
                <col style="min-width: 280px;">
                <col style="width: 130px;">
                <col style="width: 140px;">
                <col style="width: 130px;">
                <col>
                <!-- <col style="width: 110px;"> -->
                <col style="width: 140px;">
            </colgroup>
            <thead class="border-bottom">
                <tr>
                    <th>ID</th>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Type</th>
                    <th>Author</th>
                    <th>Published Date</th>
                    <th>Clicks</th>
                    <!-- <th style="width: 110px;">Status</th> -->
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($news)): ?>
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">No articles found.</td>
                    </tr>
                <?php else: ?>
                    <?php
                    $news_types = [
                        'general'     => 'Regular',
                        'flash_sale'  => 'Flash Sale',
                        'fixed_price' => 'Best Price',
                    ];
                    ?>
                    <?php foreach ($news as $item): ?>
                        <?php
                        $thumb = !empty($item['Thumbnail']) ?  ltrim($item['Thumbnail'], '/\\') : null;
                        ?>
                        <tr>
                            <td><?php echo (int)$item['NewsID']; ?></td>
                            <td>
                                <?php if ($thumb): ?>
                                    <div class="ratio ratio-16x9">
                                        <img src="<?php echo htmlspecialchars($thumb); ?>" alt="Thumb" class="news-thumb rounded border" loading="lazy"
                                            onerror="this.parentNode.classList.add('thumb-placeholder'); this.remove();">
                                    </div>
                                <?php else: ?>
                                    <div class="thumb-placeholder"></div>
                                <?php endif; ?>

                            </td>
                            <td title="<?php echo htmlspecialchars($item['Title']); ?>">
                                <div class="text-truncate-2 fw-semibold">
                                    <?php echo htmlspecialchars($item['Title']); ?>
                                </div>
                            </td>
                            <td title="<?php echo htmlspecialchars($item['Description']); ?>">
                                <div class="text-truncate-2 text-muted small">
                                    <?php echo htmlspecialchars($item['Description']); ?>
                                </div>
                            </td>
                            <td><span class="badge bg-danger-subtle text-danger badge-type"><?php echo htmlspecialchars($item['NewsType'] ?? 'Unknown'); ?></span></td>
                            <td>
                                <div class="small text-muted"><?php echo htmlspecialchars($item['AdminName'] ?? 'Unknown'); ?></div>
                            </td>
                            <td><?php echo date('d/m/Y H:i', strtotime($item['CreatedAt'])); ?></td>
                            <td><?php echo (int)$item['ClickCount']; ?></td>
                            <td>
                                <div class="d-flex gap-1 justify-content-center" role="group" aria-label="actions">
                                    <a href="/index.php?controller=adminNews&action=show&id=<?php echo (int)$item['NewsID']; ?>"
                                        aria-label="Button"
                                        class="btn btn-sm btn-outline-primary btn-icon"> <i class="ti ti-eye"></i></a>
                                    <a href="/index.php?controller=adminNews&action=editNews&id=<?php echo (int)$item['NewsID']; ?>"
                                        aria-label="Button"
                                        class="btn btn-sm btn-outline-primary btn-icon"> <i class="ti ti-edit"></i></a>
                                    <a href="/index.php?controller=adminNews&action=deleteNews&id=<?php echo (int)$item['NewsID']; ?>"
                                        class="btn btn-sm btn-outline-danger btn-icon"
                                        aria-label="Button"
                                        onclick="return confirm('Are you sure you want to delete this article?')"><i class="ti ti-trash"></i></a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="text-center py-5 d-none" id="emptyState">
        <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
        <p class="text-muted">No articles found</p>
    </div>

    <?php if (isset($totalPages)): ?>
        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
            <p class="mb-0 text-muted small">
                Page <?= (int)$page ?> of <?= (int)$totalPages ?> —
                Total <?= (int)$totalNews ?> news
            </p>

            <nav aria-label="Pagination">
                <ul class="pagination pagination-sm mb-0">
                    <?php
                    $baseUrl = '/index.php?controller=adminNews&action=manage'
                        . '&search=' . urlencode($search ?? '')
                        . '&sort=' . urlencode($sortNews)
                        . '&status=' . urlencode($status ?? 'all')
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

<?php if (isset($error)): ?>
    <div class="alert alert-danger mb-3"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
<?php if (isset($success)): ?>
    <div class="alert alert-success mb-3"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>