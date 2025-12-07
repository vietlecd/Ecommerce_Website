<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();

$promotions           = $promotions           ?? [];
$selectedPromotionIds = $selectedPromotionIds ?? [];

$old          = $old          ?? [];
$fieldErrors  = $fieldErrors  ?? [];
$toastError   = $toastError   ?? null;
$toastSuccess = $toastSuccess ?? null;

$thumbUrlRaw = $edit_news['Thumbnail'] ?? '';
$thumbUrl = '';
if ($thumbUrlRaw) {
    $thumbUrl = preg_match('#^https?://#i', $thumbUrlRaw)
        ? $thumbUrlRaw
        : '/' . ltrim($thumbUrlRaw, '/\\');
}

$currentNewsType   = $edit_news['news_type'] ?? $edit_news['NewsType'] ?? 'general';
$currentPromotionId = $edit_news['promotion_id'] ?? $edit_news['PromotionID'] ?? '';

$selectedPromotionIdsFromDb = $selectedPromotionIds;

if (!empty($old['promotion_ids']) && is_array($old['promotion_ids'])) {
    $selectedPromotionIds = $old['promotion_ids'];
} elseif (!$selectedPromotionIds && $currentPromotionId !== '') {
    $selectedPromotionIds = [(int)$currentPromotionId];
    if (!$selectedPromotionIdsFromDb) {
        $selectedPromotionIdsFromDb = $selectedPromotionIds;
    }
}

$initialState = [
    'title'         => $edit_news['Title']        ?? '',
    'desc'          => $edit_news['Description']  ?? '',
    'content'       => $edit_news['Content']      ?? '',
    'newsType'      => $currentNewsType,
    'promotionIds'  => array_values(array_map('strval', $selectedPromotionIdsFromDb ?: [])),
    'thumbUrl'      => $thumbUrl,
];

$authorName = $edit_news['AdminName'] ?? 'Admin';
?>
<style>
    #content {
        min-height: 300px;
    }

    .dirty-indicator {
        font-size: .875rem;
        color: #dc3545;
        display: none;
    }

    .dirty-indicator.on {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .dirty-dot {
        width: 8px;
        height: 8px;
        background: #dc3545;
        border-radius: 50%;
        display: inline-block;
    }
</style>

<div class="container my-0!">
    <div id="toastContainer" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1080;"></div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                <div>
                    <h1 class="h4 mb-1">Edit Article</h1>
                    <p class="text-muted small mb-0">Chỉnh sửa nội dung bài viết. Save chỉ bật khi có thay đổi.</p>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span id="dirtyTag" class="dirty-indicator">
                        <span class="dirty-dot"></span> Unsaved changes
                    </span>
                    <button id="backBtn" type="button" class="btn btn-outline-secondary">← Back</button>
                    <button id="previewBtn" type="button" class="btn btn-outline-primary">
                        Preview
                    </button>
                </div>
            </div>

            <form
                id="editForm"
                method="POST"
                action="/index.php?controller=adminNews&action=editNews&id=<?= (int)$edit_news['NewsID'] ?>"
                enctype="multipart/form-data">

                <input type="hidden" name="news_id" value="<?= (int)$edit_news['NewsID'] ?>">

                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input
                                id="title"
                                name="title"
                                type="text"
                                class="form-control"
                                placeholder="Article title"
                                value="<?= htmlspecialchars($old['title'] ?? ($edit_news['Title'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
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
                                placeholder="Short description (excerpt)"><?= htmlspecialchars($old['description'] ?? ($edit_news['Description'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
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
                                aria-label="Article content"><?= htmlspecialchars($old['content_html'] ?? ($edit_news['Content'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                            <?php if (!empty($fieldErrors['content_html'])): ?>
                                <div class="text-danger small mt-1">
                                    <?php foreach ($fieldErrors['content_html'] as $msg): ?>
                                        <div><?= htmlspecialchars($msg, ENT_QUOTES, 'UTF-8') ?></div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>


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
                                        value="<?= htmlspecialchars($old['news_type'] ?? $currentNewsType, ENT_QUOTES, 'UTF-8') ?>"
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
                                    </div>
                                    <div id="promoHelp" class="form-text">
                                        Hold <kbd>Ctrl</kbd>/<kbd>⌘</kbd> để chọn nhiều promotions.
                                    </div>
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

                                <div
                                    id="thumbPreviewWrap"
                                    class="ratio ratio-16x9 bg-light rounded overflow-hidden mb-3 <?= $thumbUrl ? '' : 'd-none' ?>"
                                    data-initial-url="<?= htmlspecialchars($thumbUrl, ENT_QUOTES, 'UTF-8') ?>">
                                    <img
                                        id="thumbPreview"
                                        class="w-100 h-100 object-fit-cover"
                                        alt="Preview thumbnail"
                                        src="<?= htmlspecialchars($thumbUrl ?: '', ENT_QUOTES, 'UTF-8') ?>">
                                </div>

                                <div class="d-flex gap-2">
                                    <button id="saveBtn" type="submit" class="btn btn-primary" disabled>Save Changes</button>
                                    <button id="cancelBtn" type="button" class="btn btn-outline-secondary">Cancel</button>
                                </div>
                                <div class="form-text mt-2">
                                    Cancel sẽ khôi phục lại toàn bộ giá trị ban đầu (và hủy ảnh tạm của phiên edit).
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
    (function() {
        const AUTHOR_NAME = <?= json_encode($authorName, JSON_UNESCAPED_UNICODE) ?>;

        window.addEventListener('error', (e) => console.error('JS error:', e.message));

        const form = document.getElementById('editForm');
        const titleInput = document.getElementById('title');
        const descInput = document.getElementById('description');
        const newsTypeSel = document.getElementById('news_type');
        const promoSelect = document.getElementById('promotion_ids');

        const thumbInput = document.getElementById('thumbnail');
        const thumbWrap = document.getElementById('thumbPreviewWrap');
        const thumbPreview = document.getElementById('thumbPreview');
        const initialThumbUrl = (thumbWrap && thumbWrap.dataset.initialUrl) || '';

        const backBtn = document.getElementById('backBtn');
        const previewBtn = document.getElementById('previewBtn');
        const saveBtn = document.getElementById('saveBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const dirtyTag = document.getElementById('dirtyTag');

        const contentTextarea = document.getElementById('content');

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
                        markDirtyIfChanged();
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
            return contentTextarea ? contentTextarea.value || '' : '';
        }

        function getSelectedPromoIds() {
            if (!promoSelect) return [];
            return Array.from(promoSelect.options)
                .filter(opt => opt.selected)
                .map(opt => String(opt.value));
        }

        function setSelectedPromoIds(ids) {
            if (!promoSelect) return;
            const set = new Set((ids || []).map(String));
            Array.from(promoSelect.options).forEach(opt => {
                opt.selected = set.has(String(opt.value));
            });
        }

        function arraysEqual(a, b) {
            if (!Array.isArray(a) || !Array.isArray(b)) return false;
            if (a.length !== b.length) return false;
            const aa = [...a].map(String).sort();
            const bb = [...b].map(String).sort();
            for (let i = 0; i < aa.length; i++) {
                if (aa[i] !== bb[i]) return false;
            }
            return true;
        }

        function canonUrl(u) {
            if (!u) return '';
            try {
                const url = new URL(u, location.origin);
                return url.origin === location.origin ?
                    (url.pathname + url.search + url.hash) :
                    url.href;
            } catch {
                return String(u);
            }
        }

        function getSnapshot() {
            return {
                title: (titleInput.value || '').trim(),
                desc: (descInput.value || '').trim(),
                newsType: (newsTypeSel.value || '').trim(),
                content: getEditorHtml().replace(/\s+/g, ' ').trim(),
                promotionIds: getSelectedPromoIds(),
                thumbUrl: (thumbPreview && thumbPreview.src) ? thumbPreview.src : '',
                thumbFileChosen: !!(thumbInput.files && thumbInput.files.length)
            };
        }

        let baseline = null;

        function setBaselineFromDOM() {
            baseline = getSnapshot();
        }

        const initialState = <?= json_encode($initialState, JSON_UNESCAPED_UNICODE) ?>;

        function isDirty(now) {
            if (!baseline) return false;
            if (now.title !== baseline.title) return true;
            if (now.desc !== baseline.desc) return true;
            if (now.newsType !== baseline.newsType) return true;
            if (now.content !== baseline.content) return true;
            if (!arraysEqual(now.promotionIds || [], baseline.promotionIds || [])) return true;
            if (now.thumbFileChosen) return true;
            if (canonUrl(now.thumbUrl) !== canonUrl(baseline.thumbUrl)) return true;
            return false;
        }

        function markDirtyIfChanged() {
            const now = getSnapshot();
            const dirty = isDirty(now);
            if (saveBtn) saveBtn.disabled = !dirty;
            if (dirtyTag) dirtyTag.classList.toggle('on', dirty);
            return dirty;
        }

        [titleInput, descInput, newsTypeSel, promoSelect].forEach(el => {
            if (!el) return;
            el.addEventListener('input', markDirtyIfChanged);
            el.addEventListener('change', markDirtyIfChanged);
        });

        thumbInput.addEventListener('change', (e) => {
            const file = e.target.files && e.target.files[0];
            if (file) {
                const url = URL.createObjectURL(file);
                thumbPreview.src = url;
                thumbWrap.classList.remove('d-none');
            } else {
                if (initialThumbUrl) {
                    thumbPreview.src = initialThumbUrl;
                    thumbWrap.classList.remove('d-none');
                } else {
                    thumbPreview.src = '';
                    thumbWrap.classList.add('d-none');
                }
            }
            markDirtyIfChanged();
        });

        cancelBtn.addEventListener('click', () => {
            if (!confirm('Hủy các thay đổi và khôi phục lại trạng thái ban đầu?')) return;

            titleInput.value = initialState.title || '';
            descInput.value = initialState.desc || '';
            newsTypeSel.value = initialState.newsType || '';

            const html = initialState.content || '';
            if (window.tinymce && typeof tinymce.get === 'function') {
                const ed = tinymce.get('content');
                if (ed) {
                    ed.setContent(html);
                    ed.save();
                } else if (contentTextarea) {
                    contentTextarea.value = html;
                }
            } else if (contentTextarea) {
                contentTextarea.value = html;
            }

            setSelectedPromoIds(initialState.promotionIds || []);

            thumbInput.value = '';
            if (initialState.thumbUrl) {
                thumbPreview.src = initialState.thumbUrl;
                thumbWrap.classList.remove('d-none');
            } else {
                thumbPreview.src = '';
                thumbWrap.classList.add('d-none');
            }

            setBaselineFromDOM();
            markDirtyIfChanged();

            showToast('info', 'Đã khôi phục nội dung ban đầu.', 'Khôi phục');
        });

        backBtn.addEventListener('click', () => {
            if (markDirtyIfChanged() && !confirm('Bạn có thay đổi chưa lưu. Rời trang?')) return;
            if (window.history.length > 1) window.history.back();
            else window.location.href = '/index.php?controller=adminNews&action=manage';
        });

        let isSubmitting = false;

        form.addEventListener('submit', () => {
            isSubmitting = true;
            if (window.tinymce && typeof tinymce.triggerSave === 'function') {
                tinymce.triggerSave();
            }
        });

        window.addEventListener('beforeunload', (e) => {
            if (isSubmitting) return;
            if (markDirtyIfChanged()) {
                e.preventDefault();
                e.returnValue = '';
            }
        });



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

            pvAuthor.textContent = AUTHOR_NAME || 'Admin';

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

        queueMicrotask(() => {
            setBaselineFromDOM();
            markDirtyIfChanged();
        });
    })();
</script>