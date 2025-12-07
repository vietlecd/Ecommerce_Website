<?php

if (session_status() !== PHP_SESSION_ACTIVE) session_start();
$promotions           = $promotions           ?? [];
$selectedPromotionIds = $selectedPromotionIds ?? [];
$currentAdminName     = $currentAdminName     ?? 'Admin';

$old          = $old          ?? [];
$fieldErrors  = $fieldErrors  ?? [];
$toastError   = $toastError   ?? null;
$toastSuccess = $toastSuccess ?? null;

if (!empty($old['promotion_ids']) && is_array($old['promotion_ids'])) {
    $selectedPromotionIds = $old['promotion_ids'];
}

?>
<style>
    #content {
        min-height: 300px;
    }
</style>

<div class="container my-0!">
    <!-- Toast container -->
    <div id="toastContainer" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1080;"></div>

    <div class="card shadow-sm">
        <div class="card-body">
            <!-- Header -->
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                <div>
                    <h1 class="mb-1">Add New Article</h1>
                    <p class="text-muted small mb-0">Fill in the article details and upload a thumbnail image.</p>
                </div>
                <div class="d-flex gap-2">
                    <button id="backBtn" type="button" class="btn btn-outline-secondary">← Back</button>
                    <button id="previewBtn" type="button" class="btn btn-outline-primary"
                        data-author-default="<?= htmlspecialchars($currentAdminName) ?>">
                        Preview
                    </button>
                </div>
            </div>

            <!-- Form -->
            <form id="newsForm" method="POST" action="/index.php?controller=adminNews&action=addNews" enctype="multipart/form-data">
                <div class="row g-4">
                    <!-- LEFT: content -->
                    <div class="col-lg-8">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input
                                id="title"
                                name="title"
                                type="text"
                                class="form-control"
                                placeholder="Article title"
                                value="<?= htmlspecialchars($old['title'] ?? '', ENT_QUOTES, 'UTF-8') ?>">
                            <?php if (!empty($fieldErrors['title'])): ?>
                                <div class="text-danger small mt-1">
                                    <?php foreach ($fieldErrors['title'] as $msg): ?>
                                        <div><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Short description</label>
                            <textarea
                                id="description"
                                name="description"
                                rows="2"
                                class="form-control"
                                placeholder="Short description (excerpt)"><?= htmlspecialchars($old['description'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                            <?php if (!empty($fieldErrors['description'])): ?>
                                <div class="text-danger small mt-1">
                                    <?php foreach ($fieldErrors['description'] as $msg): ?>
                                        <div><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="mb-2">
                            <label class="form-label" for="content">Content</label>
                            <textarea
                                id="content"
                                name="content_html"
                                class="form-control"
                                rows="20"
                                aria-label="Article content"><?= htmlspecialchars($old['content_html'] ?? '', ENT_QUOTES, 'UTF-8') ?></textarea>
                            <?php if (!empty($fieldErrors['content_html'])): ?>
                                <div class="text-danger small mt-1">
                                    <?php foreach ($fieldErrors['content_html'] as $msg): ?>
                                        <div><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- RIGHT: meta + thumbnail -->
                    <div class="col-lg-4">
                        <div class="card border-0 bg-body-tertiary">
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="news_type" class="form-label">News type</label>

                                    <input
                                        id="news_type"
                                        name="news_type"
                                        type="text"
                                        class="form-control"
                                        value="<?= htmlspecialchars($old['news_type'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                                        placeholder="VD: general, flash_sale, fixed_price..."
                                        list="news_type_suggestions">

                                    <datalist id="news_type_suggestions">
                                        <option value="general">
                                        <option value="flash_sale">
                                        <option value="fixed_price">
                                    </datalist>

                                    <?php if (!empty($fieldErrors['news_type'])): ?>
                                        <div class="text-danger small mt-1">
                                            <?php foreach ($fieldErrors['news_type'] as $msg): ?>
                                                <div><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Promotions -->
                                <div class="mb-3">
                                    <label class="form-label" for="promotion_ids">Promotions</label>
                                    <div class="d-flex gap-2">
                                        <select
                                            id="promotion_ids"
                                            name="promotion_ids[]"
                                            class="form-select"
                                            multiple
                                            size="6"
                                            aria-describedby="promoHelp">
                                            <?php foreach ($promotions as $p): ?>
                                                <?php
                                                $pid   = (int)($p['promotion_id'] ?? $p['PromotionID'] ?? 0);
                                                $pname = $p['promotion_name'] ?? $p['PromotionName'] ?? ('Promotion #' . $pid);
                                                $isSelected = in_array($pid, $selectedPromotionIds, true);
                                                ?>
                                                <option
                                                    value="<?= $pid ?>"
                                                    <?= $isSelected ? 'selected' : '' ?>
                                                    data-name="<?= htmlspecialchars($pname, ENT_QUOTES, 'UTF-8') ?>">
                                                    <?= htmlspecialchars($pname, ENT_QUOTES, 'UTF-8') ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#promotionModal">
                                            + New
                                        </button>
                                    </div>
                                    <div id="promoHelp" class="form-text">
                                        Hold <kbd>Ctrl</kbd>/<kbd>⌘</kbd> to select multiple promotions.
                                    </div>
                                    <div id="promotionCreateError" class="text-danger small mt-1 d-none"></div>
                                </div>

                                <div class="mb-2">
                                    <label for="thumbnail" class="form-label">Thumbnail (16:9)</label>
                                    <input
                                        id="thumbnail"
                                        name="thumbnail"
                                        type="file"
                                        class="form-control"
                                        accept="image/*">
                                    <?php if (!empty($fieldErrors['thumbnail'])): ?>
                                        <div class="text-danger small mt-1">
                                            <?php foreach ($fieldErrors['thumbnail'] as $msg): ?>
                                                <div><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>

                                <!-- 16:9 Preview -->
                                <div id="thumbPreviewWrap" class="ratio ratio-16x9 bg-light rounded overflow-hidden d-none mb-3">
                                    <img id="thumbPreview" class="w-100 h-100 object-fit-cover" alt="Preview thumbnail">
                                </div>

                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">Save Article</button>
                                    <button type="reset" class="btn btn-outline-secondary">Clear Content</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- /row -->
            </form>
        </div>
    </div>
</div>

<?php include 'views/admin/components/news-preview-modal.php'; ?>

<script>
    window.addEventListener('error', (e) => console.error('JS error:', e.message));

    const form = document.getElementById('newsForm');
    const titleInput = document.getElementById('title');
    const descInput = document.getElementById('description');

    const thumbInput = document.getElementById('thumbnail');
    const thumbWrap = document.getElementById('thumbPreviewWrap');
    const thumbPreview = document.getElementById('thumbPreview');

    const backBtn = document.getElementById('backBtn');
    const previewBtn = document.getElementById('previewBtn');

    const toastContainer = document.getElementById('toastContainer');

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

    document.addEventListener("DOMContentLoaded", function() {
        tinymce.init({
            selector: '#content',
            height: 500,
            menubar: false,
            plugins: [
                'anchor', 'autolink', 'charmap', 'codesample', 'emoticons',
                'link', 'lists', 'media', 'searchreplace', 'table',
                'visualblocks', 'wordcount'
            ],
            toolbar: 'undo redo | blocks | ' +
                'bold italic underline strikethrough | ' +
                'alignleft aligncenter alignright alignjustify | ' +
                'bullist numlist outdent indent | ' +
                'link table | ' +
                'removeformat',
            content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
            branding: false,
            promotion: false,
            setup: function(editor) {
                editor.on('change keyup', function() {
                    editor.save();
                });
            }
        });

        <?php if (!empty($toastError)): ?>
            showToast('danger', <?= json_encode($toastError, JSON_UNESCAPED_UNICODE) ?>, 'Error');
        <?php endif; ?>

        <?php if (!empty($toastSuccess)): ?>
            showToast('success', <?= json_encode($toastSuccess, JSON_UNESCAPED_UNICODE) ?>, 'Success');
        <?php endif; ?>
    });

    function getEditorHtml() {
        if (window.tinymce && typeof tinymce.get === 'function') {
            const ed = tinymce.get('content');
            if (ed) return ed.getContent();
        }
        const ta = document.getElementById('content');
        return ta ? ta.value : '';
    }

    backBtn.addEventListener('click', () => {
        if (window.history.length > 1) window.history.back();
        else window.location.href = '/index.php?controller=adminNews&action=manage';
    });

    // ========== THUMBNAIL PREVIEW ==========
    thumbInput.addEventListener('change', (e) => {
        const file = e.target.files && e.target.files[0];
        if (file) {
            const url = URL.createObjectURL(file);
            thumbPreview.src = url;
            thumbWrap.classList.remove('d-none');
        } else {
            thumbPreview.src = '';
            thumbWrap.classList.add('d-none');
        }
    });

    // ========== SUBMIT ==========
    form.addEventListener('submit', () => {
        if (window.tinymce && typeof tinymce.triggerSave === 'function') {
            tinymce.triggerSave();
        }
    });

    // ========== PREVIEW ==========
    const pvThumbWrap = document.getElementById('pvThumbWrap');
    const pvThumb = document.getElementById('pvThumb');
    const pvTitle = document.getElementById('pvTitle');
    const pvAuthor = document.getElementById('pvAuthor');
    const pvDate = document.getElementById('pvDate');
    const pvDescBlock = document.getElementById('pvDescBlock');
    const pvDesc = document.getElementById('pvDesc');
    const pvContent = document.getElementById('pvContent');

    function normalizeLinksInEl(root) {
        root.querySelectorAll('a[href]').forEach(a => {
            const href = (a.getAttribute('href') || '').trim().toLowerCase();
            if (!href.startsWith('javascript:')) {
                a.target = '_blank';
                a.rel = 'noopener noreferrer';
            }
        });
    }

    previewBtn.addEventListener('click', () => {
        const title = (titleInput.value || '').trim();
        pvTitle.textContent = title || 'Untitled';

        const defaultAuthor = previewBtn.getAttribute('data-author-default') || 'Admin';
        pvAuthor.textContent = defaultAuthor;

        const d = new Date();
        const dd = String(d.getDate()).padStart(2, '0');
        const mm = String(d.getMonth() + 1).padStart(2, '0');
        const yyyy = d.getFullYear();
        pvDate.textContent = `${dd}/${mm}/${yyyy}`;

        if (thumbPreview && thumbPreview.src) {
            pvThumb.src = thumbPreview.src;
            pvThumbWrap.style.display = '';
        } else {
            pvThumb.src = '/assets/images/placeholder.png';
            pvThumbWrap.style.display = '';
        }

        const desc = (descInput.value || '').trim();
        if (desc) {
            pvDesc.textContent = desc;
            pvDescBlock.style.display = '';
        } else {
            pvDescBlock.style.display = 'none';
            pvDesc.textContent = '';
        }

        const html = getEditorHtml();
        pvContent.innerHTML = html;
        normalizeLinksInEl(pvContent);

        const modal = new tabler.Modal(document.getElementById('previewModal'));
        modal.show();
    });
</script>