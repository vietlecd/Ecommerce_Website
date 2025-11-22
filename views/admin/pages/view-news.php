<?php

if (session_status() !== PHP_SESSION_ACTIVE) session_start();
$uid  = (int)($_SESSION['user_id'] ?? 0);
$role = $_SESSION['role'] ?? '';
if ($uid <= 0 || $role !== 'admin') {
    header('Location: /index.php?controller=auth&action=login', true, 302);
    exit;
}

$thumbRaw = $news['Thumbnail'] ?? '';
$thumbUrl = '';
if ($thumbRaw) {
    $thumbUrl = preg_match('#^https?://#i', $thumbRaw) ? $thumbRaw : '/' . ltrim($thumbRaw, '/\\');
}
function e($s)
{
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

$PAGE_SIZE = 20;
?>
<style>
    .news-detail {
        margin: 0 auto;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
    }

    .news-detail h1 {
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1.2;
        margin-bottom: 20px;
        color: #211922;
        letter-spacing: -.5px;
    }

    .news-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 32px;
        padding-bottom: 24px;
        border-bottom: 1px solid #e9e9e9;
    }

    .news-meta p {
        margin: 0;
        color: #767676;
        font-size: .875rem;
    }

    .news-meta .separator {
        color: #e9e9e9;
    }

    .news-thumbnail {
        width: 100%;
        margin-bottom: 32px;
        overflow: hidden;
    }

    .news-thumbnail img {
        width: 100%;
        height: 400px;
        object-fit: cover;
        object-position: center;
        display: block;
        overflow-clip-margin: content-box;
        overflow: clip;
    }

    .news-content {
        font-size: 1rem;
        line-height: 1.8;
        color: #211922;
    }

    .news-content p {
        margin-bottom: 20px;
    }

    .news-content img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        margin: 24px 0;
        display: block;
    }

    .news-content h2 {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 32px 0 16px;
        color: #211922;
    }

    .news-content h3 {
        font-size: 1.375rem;
        font-weight: 600;
        margin: 24px 0 12px;
        color: #211922;
    }

    .news-content a {
        color: #e60023;
        text-decoration: none;
        border-bottom: 1px solid transparent;
        transition: border-color .2s;
    }

    .news-content a:hover {
        border-bottom-color: #e60023;
    }

    .news-content blockquote {
        border-left: 3px solid #e60023;
        padding-left: 20px;
        margin: 24px 0;
        color: #5f5f5f;
        font-style: italic;
    }

    .page-header .h3 {
        margin-bottom: 0;
    }

    .cm-toolbar {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        align-items: center;
    }

    .cm-bulk {
        display: flex;
        gap: 6px;
        flex-wrap: wrap;
    }

    .cm-note {
        font-size: .875rem;
        color: #6c757d;
    }

    @media (max-width:768px) {
        .news-thumbnail img {
            height: 200px;
        }
    }
</style>

<div class="page-header mb-3">
    <div class="row align-items-center g-3">
        <div class="col-12 col-lg-6">
            <h1 class="h3">View Article</h1>
            <p class="text-muted small mb-0">Xem nội dung & quản lý bình luận</p>
        </div>
        <div class="col-12 col-lg-6">
            <div class="d-flex gap-2 justify-content-lg-end">
                <a href="/index.php?controller=adminNews&action=manage" class="btn btn-outline-secondary">← Back</a>
                <a href="/index.php?controller=adminNews&action=editNews&id=<?= (int)$news['NewsID'] ?>" class="btn btn-outline-primary">
                    <i class="fas fa-edit me-1"></i>Edit
                </a>
                <a href="/index.php?controller=news&action=detail&id=<?= (int)$news['NewsID'] ?>" target="_blank" class="btn btn-primary">
                    View public
                </a>
            </div>
        </div>
    </div>
</div>


<ul class="nav nav-tabs" id="newsTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="tab-article" data-bs-toggle="tab" data-bs-target="#pane-article" type="button" role="tab">Article</button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="tab-comments" data-bs-toggle="tab" data-bs-target="#pane-comments" type="button" role="tab">
            Comments <span class="badge rounded-pill bg-secondary" id="cmCount">0</span>
        </button>
    </li>
</ul>

<div class="tab-content pt-3">
    <div class="tab-pane fade show active" id="pane-article" role="tabpanel" aria-labelledby="tab-article">
        <div class="news-detail">
            <?php if ($thumbUrl): ?>
                <div class="news-thumbnail"><img src="<?= e($thumbUrl) ?>" alt="<?= e($news['Title']) ?>"></div>
            <?php endif; ?>
            <h1><?= e($news['Title']) ?></h1>
            <div class="news-meta">
                <p><strong><?= e($news['AdminName'] ?? 'Unknown') ?></strong></p>
                <span class="separator">•</span>
                <p><?= date('d/m/Y', strtotime($news['CreatedAt'])) ?></p>
                <span class="separator">•</span>
                <p class="text-muted small mb-0">Type: <?= e($news['NewsType'] ?? 'general') ?></p>
            </div>
            <?php if (!empty($news['Description'])): ?>
                <div class="news-description"><?= e($news['Description']) ?></div>
            <?php endif; ?>
            <div class="news-content"><?= $news['Content'] ?></div>
        </div>
    </div>

    <div class="tab-pane fade" id="pane-comments" role="tabpanel" aria-labelledby="tab-comments">
        <div class="card shadow-sm">
            <div class="card-body">
                <style>
                    .cm-item {
                        border-radius: .5rem;
                        border: 1px solid var(--bs-border-color);
                        padding: .75rem .75rem;
                    }

                    .cm-item+.cm-item {
                        margin-top: .75rem;
                    }

                    .cm-content {
                        display: -webkit-box;
                        -webkit-line-clamp: 2;
                        line-clamp: 2;
                        -webkit-box-orient: vertical;
                        overflow: hidden;
                    }

                    .cm-meta small,
                    .cm-meta a {
                        color: var(--bs-secondary-color) !important;
                    }

                    .cm-right {
                        text-align: right;
                        min-width: 180px;
                    }

                    @media (max-width: 576px) {
                        .cm-right {
                            text-align: left;
                        }
                    }
                </style>

                <div>
                    <div class="cm-toolbar">
                        <div class="input-group" style="max-width:420px;">
                            <span class="input-group-text bg-white"><i class="fas fa-search text-muted"></i></span>
                            <input type="search" class="form-control" id="cmSearch" placeholder="Search by text, author, email, IP">
                        </div>

                        <select id="cmSort" class="form-select form-select-sm" style="max-width:160px;">
                            <option value="newest">Newest first</option>
                            <option value="oldest">Oldest first</option>
                        </select>

                        <button class="btn btn-sm btn-outline-secondary" id="cmRefresh" title="Refresh">
                            <i class="fas fa-rotate"></i>
                        </button>

                        <!-- Master checkbox -->
                        <div class="form-check ms-2">
                            <input class="form-check-input" type="checkbox" id="cmCheckAll">
                            <label class="form-check-label small" for="cmCheckAll">Select all</label>
                        </div>

                        <div class="ms-auto cm-bulk">
                            <button class="btn btn-sm btn-danger" data-bulk="delete">
                                <i class="fas fa-trash"></i> Delete selected
                            </button>
                        </div>
                    </div>

                    <div class="cm-note mt-2">
                        <span id="cmSelCount">0</span> selected
                    </div>
                </div>

                <div class="mt-3">
                    <ul id="cmTbody" class="list-unstyled mb-0">
                        <li class="text-center text-muted py-3" id="cmLoading">Loading comments...</li>
                    </ul>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="cm-note">
                        <span id="cmPageInfo">Loading...</span>
                    </div>
                    <nav>
                        <ul class="pagination pagination-sm mb-0" id="cmPagination"></ul>
                    </nav>
                </div>

            </div>
        </div>
    </div>

</div>

<div id="toastContainer" class="toast-container position-fixed top-0 end-0 p-3" style="z-index:1080;"></div>

<script>
    (function() {
        const newsId = <?= (int)$news['NewsID'] ?>;
        const cmCount = document.getElementById('cmCount');
        const tbody = document.getElementById('cmTbody');
        const checkAll = document.getElementById('cmCheckAll');
        const selCount = document.getElementById('cmSelCount');
        const inpSearch = document.getElementById('cmSearch');
        const selSort = document.getElementById('cmSort');
        const btnRefresh = document.getElementById('cmRefresh');
        const toastContainer = document.getElementById('toastContainer');
        const csrf = <?= json_encode($csrfToken ?? '') ?>;

        const pagination = document.getElementById('cmPagination');
        const pageInfo = document.getElementById('cmPageInfo');
        const PAGE_SIZE = <?= (int)$PAGE_SIZE ?>;

        let page = 1;
        let total = 0;
        let totalPages = 1;

        function showToast(type, msg) {
            const color = {
                success: 'bg-success text-white',
                danger: 'bg-danger text-white',
                warning: 'bg-warning',
                info: 'bg-info'
            } [type] || 'bg-secondary text-white';

            const html = `
                <div class="toast align-items-center border-0 shadow">
                    <div class="toast-header ${color}">
                        <strong class="me-auto">${type === 'success' ? 'Success' : 'Notice'}</strong>
                        <button type="button" class="btn-close btn-close-white ms-2 mb-1" data-bs-dismiss="toast"></button>
                    </div>
                    <div class="toast-body">${msg}</div>
                </div>`;
            const wrap = document.createElement('div');
            wrap.innerHTML = html.trim();
            const el = wrap.firstElementChild;
            toastContainer.appendChild(el);
            new bootstrap.Toast(el, {
                delay: 2500,
                autohide: true
            }).show();
            el.addEventListener('hidden.bs.toast', () => el.remove());
        }

        function getSelectedIds() {
            return Array.from(tbody.querySelectorAll('.cmCheck:checked'))
                .map(cb => cb.closest('[data-id]')?.dataset.id)
                .filter(Boolean);
        }

        function updateSelCount() {
            if (selCount) selCount.textContent = String(getSelectedIds().length);
        }

        function updatePageInfo() {
            if (!pageInfo) return;
            if (!total) {
                pageInfo.textContent = 'No comments';
                return;
            }
            const from = (page - 1) * PAGE_SIZE + 1;
            const to = Math.min(total, page * PAGE_SIZE);
            pageInfo.textContent = `Showing ${from}–${to} of ${total}`;
        }

        checkAll?.addEventListener('change', () => {
            const checked = checkAll.checked;
            tbody.querySelectorAll('.cmCheck').forEach(cb => cb.checked = checked);
            updateSelCount();
        });

        tbody.addEventListener('change', (e) => {
            if (e.target.classList.contains('cmCheck')) updateSelCount();
        });

        async function apiPost(url, formObj) {
            const fd = new FormData();
            Object.entries(formObj || {}).forEach(([k, v]) => {
                if (Array.isArray(v)) v.forEach(x => fd.append(k + '[]', x));
                else fd.append(k, v);
            });
            if (csrf) fd.append('csrf', csrf);
            const resp = await fetch(url, {
                method: 'POST',
                body: fd,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                credentials: 'include'
            });
            const ct = (resp.headers.get('content-type') || '').toLowerCase();
            if (!ct.includes('application/json')) throw new Error('Invalid JSON response');
            const data = await resp.json();
            if (!resp.ok || !data.ok) throw new Error(data.error || data.message || `HTTP ${resp.status}`);
            return data;
        }

        function buildQuery(pageNum = 1) {
            const url = new URL('/index.php', window.location.origin);
            url.searchParams.set('controller', 'adminComments');
            url.searchParams.set('action', 'list');
            url.searchParams.set('news_id', String(newsId));
            url.searchParams.set('page', String(pageNum));
            url.searchParams.set('limit', String(PAGE_SIZE));

            const q = (inpSearch.value || '').trim();
            if (q) url.searchParams.set('q', q);
            const sort = selSort.value;
            if (sort) url.searchParams.set('sort', sort);
            return url.toString();
        }

        function escapeHtml(s) {
            return String(s).replace(/[&<>"']/g, m => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            } [m]));
        }

        function renderRow(item) {
            const li = document.createElement('li');
            li.className = 'cm-item';
            li.dataset.id = item.id;

            const author = item.author_name || 'Guest';
            const email = item.author_email || '';
            const ip = item.ip || '';
            const contentTxt = (item.content_text || '').replace(/\s+/g, ' ').trim();
            const createdHuman = item.created_human || item.created_at || '';

            li.innerHTML = `
            <div class="d-flex align-items-start gap-2 flex-wrap">
                <div class="form-check mt-1">
                    <input class="form-check-input cmCheck" type="checkbox">
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                        <div>
                            <div class="fw-semibold">${escapeHtml(author)}</div>
                            <div class="cm-meta small">
                                ${email ? `<a href="mailto:${escapeHtml(email)}" class="text-decoration-none">${escapeHtml(email)}</a>` : ''}
                                ${email && ip ? ' • ' : ''}${ip ? escapeHtml(ip) : ''}
                            </div>

                             <div class="cm-content mt-2" title="${escapeHtml(contentTxt)}">${escapeHtml(contentTxt)}</div>
                        </div>
                        <div class="cm-right">
                            <div class="small text-muted">${escapeHtml(createdHuman)}</div>
                            <div class="btn-group btn-group-sm mt-1" role="group">
                                <button class="btn btn-outline-danger" data-act="delete" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                   
                </div>
            </div>
        `;
            return li;
        }

        function renderPagination() {
            if (!pagination) return;

            pagination.innerHTML = '';
            if (totalPages <= 1) return;

            const makeItem = (label, targetPage, disabled = false, active = false) => {
                const li = document.createElement('li');
                li.className = 'page-item';
                if (disabled) li.classList.add('disabled');
                if (active) li.classList.add('active');
                li.dataset.page = String(targetPage);

                const a = document.createElement('button');
                a.type = 'button';
                a.className = 'page-link';
                a.textContent = label;

                li.appendChild(a);
                return li;
            };

            pagination.appendChild(
                makeItem('«', Math.max(1, page - 1), page === 1, false)
            );

            const windowSize = 5;
            let start = Math.max(1, page - 2);
            let end = Math.min(totalPages, start + windowSize - 1);
            if (end - start < windowSize - 1) {
                start = Math.max(1, end - windowSize + 1);
            }

            for (let p = start; p <= end; p++) {
                pagination.appendChild(
                    makeItem(String(p), p, false, p === page)
                );
            }

            pagination.appendChild(
                makeItem('»', Math.min(totalPages, page + 1), page === totalPages, false)
            );
        }

        async function loadPage(pageNum = 1) {
            const url = buildQuery(pageNum);
            const resp = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                credentials: 'include'
            });

            const ct = (resp.headers.get('content-type') || '').toLowerCase();
            if (!ct.includes('application/json')) throw new Error('Invalid JSON');
            const data = await resp.json();
            if (!resp.ok || !data.ok) throw new Error(data.error || data.message || `HTTP ${resp.status}`);

            const items = data.items || [];

            tbody.innerHTML = '';
            if (!items.length) {
                tbody.innerHTML = '<li class="text-center text-muted py-3">No comments to show.</li>';
            } else {
                items.forEach(item => tbody.appendChild(renderRow(item)));
            }

            page = data.page || pageNum;
            total = typeof data.total === 'number' ? data.total : total;
            totalPages = data.total_pages || Math.max(1, Math.ceil(total / PAGE_SIZE));

            if (typeof data.total === 'number' && cmCount) {
                cmCount.textContent = String(data.total);
            }

            updateSelCount();
            updatePageInfo();
            renderPagination();
        }

        [inpSearch, selSort].forEach(el => {
            el?.addEventListener('change', async () => {
                try {
                    await loadPage(1);
                } catch (e) {
                    showToast('danger', e.message || 'Load failed');
                }
            });
        });

        btnRefresh?.addEventListener('click', async () => {
            try {
                await loadPage(page);
                showToast('info', 'Refreshed');
            } catch (e) {
                showToast('danger', e.message || 'Refresh failed');
            }
        });

        tbody.addEventListener('click', async (e) => {
            const btn = e.target.closest('button[data-act="delete"]');
            if (!btn) return;
            const row = btn.closest('[data-id]');
            if (!row) return;
            const id = row.dataset.id;

            try {
                if (!confirm('Delete this comment?')) return;
                await apiPost('/index.php?controller=adminComments&action=delete', {
                    id
                });
                row.remove();
                cmCount.textContent = String(Math.max(0, parseInt(cmCount.textContent || '0', 10) - 1));
                total = Math.max(0, total - 1);
                totalPages = Math.max(1, Math.ceil(total / PAGE_SIZE));
                updateSelCount();
                updatePageInfo();
                renderPagination();
                showToast('success', 'Deleted');
            } catch (err) {
                showToast('danger', err.message || 'Action failed');
            }
        });

        pagination?.addEventListener('click', (e) => {
            const li = e.target.closest('.page-item');
            if (!li || li.classList.contains('disabled') || li.classList.contains('active')) return;

            const target = parseInt(li.dataset.page, 10);
            if (!Number.isNaN(target) && target >= 1 && target <= totalPages) {
                loadPage(target).catch(err => {
                    showToast('danger', err.message || 'Load failed');
                });
            }
        });

        document.querySelectorAll('[data-bulk="delete"]').forEach(btn => {
            btn.addEventListener('click', async () => {
                const ids = getSelectedIds();
                if (!ids.length) {
                    showToast('warning', 'No selection');
                    return;
                }
                if (!confirm(`Delete ${ids.length} comment(s)?`)) return;

                try {
                    await apiPost('/index.php?controller=adminComments&action=bulkUpdate', {
                        ids
                    });
                    ids.forEach(id => {
                        const row = tbody.querySelector(`[data-id="${CSS.escape(id)}"]`);
                        if (row) row.remove();
                    });
                    const current = parseInt(cmCount.textContent || '0', 10);
                    const newTotal = Math.max(0, current - ids.length);
                    cmCount.textContent = String(newTotal);
                    total = newTotal;
                    totalPages = Math.max(1, Math.ceil(total / PAGE_SIZE));
                    updateSelCount();
                    updatePageInfo();
                    renderPagination();
                    showToast('success', 'Bulk deleted');
                } catch (err) {
                    showToast('danger', err.message || 'Bulk failed');
                }
            });
        });

        updateSelCount();
        updatePageInfo();
        renderPagination();

        loadPage(1).catch(err => {
            tbody.innerHTML = '<li class="text-center text-danger py-3">Failed to load comments.</li>';
            pageInfo.textContent = 'Load error';
            showToast('danger', err.message || 'Initial load failed');
        });
    })();
</script>