<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
$draftToken = bin2hex(random_bytes(16));
$_SESSION['draft_tokens'][$draftToken] = time();

$thumbUrlRaw = $edit_news['Thumbnail'] ?? '';
$thumbUrl = '';
if ($thumbUrlRaw) {
    $thumbUrl = preg_match('#^https?://#i', $thumbUrlRaw)
        ? $thumbUrlRaw
        : '/' . ltrim($thumbUrlRaw, '/\\');
}

$currentNewsType = $edit_news['news_type'] ?? $edit_news['NewsType'] ?? 'general';
$currentPromotionId = $edit_news['promotion_id'] ?? $edit_news['PromotionID'] ?? '';

$selectedPromotionIds = $selectedPromotionIds ?? [];
if (!$selectedPromotionIds && $currentPromotionId !== '') {
    $selectedPromotionIds = [(int)$currentPromotionId];
}
?>
<style>
    #editor.form-control {
        height: 640px;
        max-height: 640px;
        overflow-y: auto;
        overflow-x: hidden;
    }

    #editor p {
        margin-bottom: .75rem;
    }

    #editor a {
        text-decoration: underline;
    }

    #editor img {
        max-width: 100%;
        height: auto;
        border-radius: .5rem;
        display: block;
        margin: .5rem auto;
    }

    .btn-light.border.active {
        color: #fff;
        background-color: #0d6efd;
        border-color: #0d6efd;
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

<div class="container">

    <!-- Toast container (Bootstrap 5) -->
    <div id="toastContainer" class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1080;"></div>

    <div class="card shadow-sm">
        <div class="card-body">
            <!-- Header -->
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                <div>
                    <h1 class="h4 mb-1">Edit Article</h1>
                    <p class="text-muted small mb-0">Chỉnh sửa nội dung bài viết. Save chỉ bật khi có thay đổi.</p>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span id="dirtyTag" class="dirty-indicator"><span class="dirty-dot"></span> Unsaved changes</span>
                    <button id="backBtn" type="button" class="btn btn-outline-secondary">← Back</button>
                    <button id="previewBtn" type="button" class="btn btn-outline-primary">Preview</button>
                </div>
            </div>

            <form
                id="editForm"
                method="POST"
                action="/index.php?controller=adminNews&action=editNews&id=<?= (int)$edit_news['NewsID'] ?>"
                enctype="multipart/form-data">
                <input type="hidden" name="news_id" value="<?= (int)$edit_news['NewsID'] ?>">
                <input type="hidden" name="draft_token" id="draft_token" value="<?= htmlspecialchars($draftToken) ?>">
                <input type="hidden" name="content_html" id="content_html">

                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input
                                id="title"
                                name="title"
                                type="text"
                                class="form-control"
                                required
                                value="<?= htmlspecialchars($edit_news['Title']) ?>">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Short description</label>
                            <textarea
                                id="description"
                                name="description"
                                rows="2"
                                class="form-control"
                                required><?= htmlspecialchars($edit_news['Description']) ?></textarea>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Content (rich)</label>

                            <div class="btn-toolbar gap-2 mb-2" role="toolbar" aria-label="Editor toolbar">
                                <div class="btn-group btn-group-sm me-2" role="group">
                                    <button type="button" class="btn btn-light border" data-cmd="bold"><strong>B</strong></button>
                                    <button type="button" class="btn btn-light border" data-cmd="italic"><em>I</em></button>
                                    <button type="button" class="btn btn-light border" data-cmd="underline"><u>U</u></button>
                                    <button type="button" class="btn btn-light border" data-cmd="ul">• List</button>
                                </div>

                                <div class="btn-group btn-group-sm me-2" role="group">
                                    <button type="button" class="btn btn-light border" data-cmd="justifyLeft" title="Align left"><i class="bi bi-text-left"></i></button>
                                    <button type="button" class="btn btn-light border" data-cmd="justifyCenter" title="Center"><i class="bi bi-text-center"></i></button>
                                    <button type="button" class="btn btn-light border" data-cmd="justifyRight" title="Align right"><i class="bi bi-text-right"></i></button>
                                    <button type="button" class="btn btn-light border" data-cmd="justifyFull" title="Justify"><i class="bi bi-justify"></i></button>
                                </div>


                                <div class="btn-group btn-group-sm me-2" role="group">
                                    <select id="blockFormat" class="form-select form-select-sm">
                                        <option value="p">Paragraph</option>
                                        <option value="h2">Heading 2</option>
                                        <option value="h3">Heading 3</option>
                                        <option value="h4">Heading 4</option>
                                    </select>
                                </div>


                                <div class="btn-group btn-group-sm me-2" role="group">
                                    <button type="button" class="btn btn-light border" id="aLink">Link</button>
                                    <button type="button" class="btn btn-light border" id="clean">Clear</button>
                                </div>

                                <div class="btn-group btn-group-sm me-2" role="group">
                                    <button type="button" class="btn btn-light border" id="insertImageBtn">Image</button>
                                </div>

                                <!-- Insert Promotion dropdown (nếu edit muốn chèn tag khuyến mãi vào nội dung sau này) -->
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-light border dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="insertPromoToggle">
                                        Insert promo
                                    </button>
                                    <ul class="dropdown-menu" id="promoMenu"></ul>
                                </div>
                            </div>

                            <div
                                id="editor"
                                class="form-control p-3"
                                contenteditable="true"
                                spellcheck="true"
                                aria-label="Article content">
                                <?= $edit_news['Content'] ?>
                            </div>
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
                                        required
                                        value="<?= htmlspecialchars($currentNewsType) ?>"
                                        placeholder="VD: general, flash_sale, fixed_price..."
                                        list="news_type_suggestions">

                                    <datalist id="news_type_suggestions">
                                        <option value="general">
                                        <option value="flash_sale">
                                        <option value="fixed_price">
                                    </datalist>
                                </div>


                                <div class="mb-3">
                                    <label for="promotion_ids" class="form-label">Promotions</label>

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

                                        <!-- Nếu có luồng tạo mới promotion ở edit thì bật nút này -->
                                        <!--
                                        <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#promotionModal">
                                            + New
                                        </button>
                                        -->
                                    </div>

                                    <div id="promoHelp" class="form-text">
                                        Hold <kbd>Ctrl</kbd>/<kbd>⌘</kbd> để chọn nhiều promotions.
                                    </div>
                                </div>

                                <div class="mb-2">
                                    <label for="thumbnail" class="form-label">Thumbnail (16:9)</label>
                                    <input id="thumbnail" name="thumbnail" type="file" class="form-control" accept="image/*">
                                </div>

                                <div
                                    id="thumbPreviewWrap"
                                    class="ratio ratio-16x9 rounded overflow-hidden mb-3 <?= $thumbUrl ? '' : 'd-none' ?>"
                                    data-initial-url="<?= htmlspecialchars($thumbUrl) ?>">
                                    <?php if ($thumbUrl): ?>
                                        <img
                                            id="thumbPreview"
                                            class="w-100 h-100 object-fit-cover"
                                            alt="Preview thumbnail"
                                            src="<?= htmlspecialchars($thumbUrl) ?>">
                                    <?php else: ?>
                                        <img
                                            id="thumbPreview"
                                            class="w-100 h-100 object-fit-cover"
                                            alt="Preview thumbnail">
                                    <?php endif; ?>
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
                </div>
            </form>
        </div>
    </div>
</div>

<?php include 'views/admin/components/news-preview-modal.php'; ?>

<script>
    (function() {
        const toastContainer = document.getElementById('toastContainer');

        function showToast(type, message, title = '') {
            const color = {
                success: 'bg-success text-white',
                danger: 'bg-danger text-white',
                warning: 'bg-warning',
                info: 'bg-info'
            } [type] || 'bg-secondary text-white';

            const html = `
          <div class="toast align-items-center border-0 shadow" role="status" aria-live="assertive" aria-atomic="true">
            <div class="toast-header ${color}">
              <strong class="me-auto">${title || (type === 'success' ? 'Thành công' : 'Thông báo')}</strong>
              <small class="text-body-secondary"></small>
              <button type="button" class="btn-close btn-close-white ms-2 mb-1" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body">${message}</div>
          </div>`;
            const wrap = document.createElement('div');
            wrap.innerHTML = html.trim();
            const el = wrap.firstElementChild;
            toastContainer.appendChild(el);
            const t = new bootstrap.Toast(el, {
                delay: 4000,
                autohide: true
            });
            t.show();
            el.addEventListener('hidden.bs.toast', () => el.remove());
        }

        window.addEventListener('error', (e) => console.error('JS error:', e.message));

        const form = document.getElementById('editForm');
        const editor = document.getElementById('editor');
        const contentHidden = document.getElementById('content_html');

        const titleInput = document.getElementById('title');
        const descInput = document.getElementById('description');

        const newsTypeSel = document.getElementById('news_type');
        const promotionSelect = document.getElementById('promotion_ids');

        const thumbInput = document.getElementById('thumbnail');
        const thumbWrap = document.getElementById('thumbPreviewWrap');
        const thumbPreview = document.getElementById('thumbPreview');
        const initialThumbUrl = thumbWrap?.dataset?.initialUrl || '';

        const backBtn = document.getElementById('backBtn');
        const previewBtn = document.getElementById('previewBtn');
        const saveBtn = document.getElementById('saveBtn');
        const cancelBtn = document.getElementById('cancelBtn');
        const dirtyTag = document.getElementById('dirtyTag');

        const draftTokenEl = document.getElementById('draft_token');
        const draftToken = draftTokenEl ? draftTokenEl.value : '';

        const cmdButtons = {
            bold: document.querySelector('[data-cmd="bold"]'),
            italic: document.querySelector('[data-cmd="italic"]'),
            underline: document.querySelector('[data-cmd="underline"]'),
            ul: document.querySelector('[data-cmd="ul"]'),
            link: document.getElementById('aLink'),
            clean: document.getElementById('clean')
        };

        const blockFormatSel = document.getElementById('blockFormat');

        const alignButtons = {
            left: document.querySelector('[data-cmd="justifyLeft"]'),
            center: document.querySelector('[data-cmd="justifyCenter"]'),
            right: document.querySelector('[data-cmd="justifyRight"]'),
            full: document.querySelector('[data-cmd="justifyFull"]')
        };



        document.querySelectorAll('[data-cmd]').forEach(btn => {
            btn.addEventListener('click', () => {
                const cmd = btn.getAttribute('data-cmd');
                if (cmd === 'ul') {
                    document.execCommand('insertUnorderedList');
                } else {
                    document.execCommand(cmd);
                }
                refreshToolbarState();
                markDirtyIfChanged();
            });
        });

        if (blockFormatSel) {
            blockFormatSel.addEventListener('change', () => {
                const value = blockFormatSel.value || 'p';
                document.execCommand('formatBlock', false, value);
                refreshToolbarState();
                markDirtyIfChanged();
            });
        }



        cmdButtons.link.addEventListener('click', () => {
            const sel = window.getSelection();
            if (!sel || !sel.rangeCount) return;
            const a = closestInside(sel.getRangeAt(0).startContainer, 'a[href]');
            if (a) {
                document.execCommand('unlink');
            } else {
                const url = prompt('URL (http/https):', 'https://');
                if (url) document.execCommand('createLink', false, url);
            }
            normalizeLinksInEditor();
            refreshToolbarState();
            markDirtyIfChanged();
        });

        cmdButtons.clean.addEventListener('click', () => {
            document.execCommand('removeFormat');
            refreshToolbarState();
            markDirtyIfChanged();
        });

        function normalizeLinksInEditor() {
            editor.querySelectorAll('a[href]').forEach(a => {
                const href = (a.getAttribute('href') || '').trim().toLowerCase();
                if (!href.startsWith('javascript:')) {
                    a.target = '_blank';
                    a.rel = 'noopener noreferrer';
                }
            });
        }

        function closestInside(node, selector) {
            let cur = node && (node.nodeType === Node.TEXT_NODE ? node.parentNode : node);
            while (cur && cur !== editor) {
                if (cur.matches?.(selector)) return cur;
                cur = cur.parentNode;
            }
            return null;
        }

        function getBlockAncestor(node) {
            const sel = node ? null : window.getSelection();
            const n = node || (sel && sel.rangeCount ? sel.getRangeAt(0).startContainer : null);
            let cur = n && (n.nodeType === Node.TEXT_NODE ? n.parentNode : n);
            while (cur && cur !== editor) {
                if (/^(P|H1|H2|H3|H4|H5|H6|LI|DIV)$/.test(cur.nodeName)) return cur;
                cur = cur.parentNode;
            }
            return null;
        }

        function refreshToolbarState() {
            try {
                toggleActive(cmdButtons.bold, document.queryCommandState('bold'));
                toggleActive(cmdButtons.italic, document.queryCommandState('italic'));
                toggleActive(cmdButtons.underline, document.queryCommandState('underline'));
                toggleActive(cmdButtons.ul, document.queryCommandState('insertUnorderedList'));

                if (alignButtons.left)
                    toggleActive(alignButtons.left, document.queryCommandState('justifyLeft'));
                if (alignButtons.center)
                    toggleActive(alignButtons.center, document.queryCommandState('justifyCenter'));
                if (alignButtons.right)
                    toggleActive(alignButtons.right, document.queryCommandState('justifyRight'));
                if (alignButtons.full)
                    toggleActive(alignButtons.full, document.queryCommandState('justifyFull'));
            } catch (e) {}

            const block = getBlockAncestor();
            if (blockFormatSel) {
                let tag = block ? block.nodeName.toLowerCase() : 'p';
                if (!['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6'].includes(tag)) {
                    tag = 'p';
                }
                if ([...blockFormatSel.options].some(opt => opt.value === tag)) {
                    blockFormatSel.value = tag;
                } else {
                    blockFormatSel.value = 'p';
                }
            }
        }



        function toggleActive(el, on) {
            if (el) el.classList.toggle('active', !!on);
        }

        document.addEventListener('selectionchange', () => setTimeout(refreshToolbarState, 0));
        editor.addEventListener('keyup', () => {
            refreshToolbarState();
            markDirtyIfChanged();
        });
        editor.addEventListener('input', markDirtyIfChanged);
        normalizeLinksInEditor();

        const insertImageBtn = document.getElementById('insertImageBtn');
        const hiddenFile = document.createElement('input');
        hiddenFile.type = 'file';
        hiddenFile.accept = 'image/*';
        hiddenFile.style.position = 'fixed';
        hiddenFile.style.left = '-10000px';
        hiddenFile.style.top = '-10000px';
        hiddenFile.style.opacity = '0';
        document.body.appendChild(hiddenFile);

        insertImageBtn.addEventListener('click', () => {
            try {
                if (typeof hiddenFile.showPicker === 'function') hiddenFile.showPicker();
                else hiddenFile.click();
            } catch {
                hiddenFile.click();
            }
        });

        hiddenFile.addEventListener('change', async (ev) => {
            const f = ev.target.files && ev.target.files[0];
            if (!f) return;
            try {
                const data = await uploadImageFile(f);
                insertImageTag(data);
                markDirtyIfChanged();
            } catch (e) {
                showToast('danger', e.message || 'Upload image failed', 'Lỗi');
            } finally {
                hiddenFile.value = '';
            }
        });

        editor.addEventListener('paste', async (e) => {
            const cd = e.clipboardData;
            if (!cd) return;
            const items = [...cd.items].filter(i => i.type && i.type.startsWith('image/'));
            if (!items.length) return;
            e.preventDefault();
            for (const item of items) {
                const file = item.getAsFile();
                if (!file) continue;
                try {
                    const data = await uploadImageFile(file);
                    insertImageTag(data);
                    markDirtyIfChanged();
                } catch (err) {
                    showToast('danger', err.message || 'Upload image failed', 'Lỗi');
                }
            }
        });

        editor.addEventListener('dragover', (e) => e.preventDefault());
        editor.addEventListener('drop', async (e) => {
            e.preventDefault();
            const files = [...(e.dataTransfer?.files || [])].filter(f => f.type.startsWith('image/'));
            for (const f of files) {
                try {
                    const data = await uploadImageFile(f);
                    insertImageTag(data);
                    markDirtyIfChanged();
                } catch (err) {
                    showToast('danger', err.message || 'Upload image failed', 'Lỗi');
                }
            }
        });

        async function uploadImageFile(file) {
            if (!file.type.startsWith('image/')) throw new Error('Only image files are allowed');
            const fd = new FormData();
            fd.append('image', file);
            if (draftToken) fd.append('draft', draftToken);
            const resp = await fetch('/index.php?controller=media&action=uploadImage', {
                method: 'POST',
                body: fd,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                credentials: 'include'
            });
            const ct = (resp.headers.get('content-type') || '').toLowerCase();
            if (!ct.includes('application/json')) {
                const text = await resp.text();
                throw new Error('Server không trả JSON hợp lệ (upload). Snippet: ' + text.slice(0, 200));
            }
            const data = await resp.json();
            if (!resp.ok || !data || !data.ok) throw new Error((data && data.error) || 'Upload failed');
            return data;
        }

        function insertImageTag(payload) {
            const img = document.createElement('img');
            img.src = payload.url;
            img.alt = payload.alt || '';
            img.loading = 'lazy';
            if (payload.width) img.width = payload.width;
            if (payload.height) img.height = payload.height;
            if (payload.id) img.dataset.mediaId = String(payload.id);
            if (payload.s3Key) img.setAttribute('data-s3-key', payload.s3Key);
            img.classList.add('img-fluid', 'rounded');

            const sel = window.getSelection();
            if (sel && sel.rangeCount) {
                const r = sel.getRangeAt(0);
                r.deleteContents();
                r.insertNode(img);
            } else {
                editor.appendChild(img);
            }
            const range = document.createRange();
            range.setStartAfter(img);
            range.setEndAfter(img);
            sel.removeAllRanges();
            sel.addRange(range);
        }


        function getSelectedPromoIds() {
            if (!promotionSelect) return [];
            return Array.from(promotionSelect.options)
                .filter(opt => opt.selected)
                .map(opt => String(opt.value));
        }

        function setSelectedPromoIds(ids) {
            if (!promotionSelect) return;
            const set = new Set((ids || []).map(String));
            Array.from(promotionSelect.options).forEach(opt => {
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


        function normHTML(s) {
            return String(s).replace(/\s+/g, ' ').trim();
        }

        function canonUrl(u) {
            if (!u) return '';
            try {
                const url = new URL(u, location.origin);
                return url.origin === location.origin ? (url.pathname + url.search + url.hash) : url.href;
            } catch {
                return String(u);
            }
        }

        function getSnapshot() {
            return {
                title: (titleInput.value || '').trim(),
                desc: (descInput.value || '').trim(),
                content: normHTML(editor.innerHTML || ''),
                newsType: newsTypeSel.value || '',
                promotionIds: getSelectedPromoIds(),
                thumbUrl: (thumbPreview && thumbPreview.src) ? thumbPreview.src : '',
                thumbFileChosen: !!(thumbInput.files && thumbInput.files.length)
            };
        }

        let baseline = null;

        function setBaselineFromDOM() {
            baseline = getSnapshot();
        }

        const initialState = {
            title: <?= json_encode($edit_news['Title'] ?? '') ?>,
            desc: <?= json_encode($edit_news['Description'] ?? '') ?>,
            content: <?= json_encode(preg_replace('/\s+/', ' ', $edit_news['Content'] ?? '')) ?>,
            newsType: <?= json_encode($currentNewsType) ?>,
            promotionIds: <?= json_encode(array_values(array_map('strval', $selectedPromotionIds))) ?>,
            thumbUrl: <?= json_encode($thumbUrl) ?>,
        };

        function isDirty(now) {
            if (!baseline) return false;
            if (now.title !== baseline.title) return true;
            if (now.desc !== baseline.desc) return true;
            if (now.newsType !== baseline.newsType) return true;
            if (!arraysEqual(now.promotionIds || [], baseline.promotionIds || [])) return true;
            if (now.content !== baseline.content) return true;
            if (now.thumbFileChosen) return true;
            if (canonUrl(now.thumbUrl) !== canonUrl(baseline.thumbUrl)) return true;
            return false;
        }

        function markDirtyIfChanged() {
            const now = getSnapshot();
            const dirty = isDirty(now);
            saveBtn.disabled = !dirty;
            dirtyTag.classList.toggle('on', dirty);
            return dirty;
        }

        [titleInput, descInput, newsTypeSel, promotionSelect].forEach(el => {
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
            titleInput.value = initialState.title;
            descInput.value = initialState.desc;
            newsTypeSel.value = initialState.newsType;
            setSelectedPromoIds(initialState.promotionIds || []);
            editor.innerHTML = initialState.content || '';
            thumbInput.value = '';
            if (initialState.thumbUrl) {
                thumbPreview.src = initialState.thumbUrl;
                thumbWrap.classList.remove('d-none');
            } else {
                thumbPreview.src = '';
                thumbWrap.classList.add('d-none');
            }
            normalizeLinksInEditor();

            if (draftToken) {
                try {
                    const body = new URLSearchParams({
                        draft: draftToken
                    });
                    navigator.sendBeacon('/index.php?controller=media&action=discardDraft', body);
                } catch {}
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


        const pvThumbWrap = document.getElementById('pvThumbWrap');
        const pvThumb = document.getElementById('pvThumb');
        const pvTitle = document.getElementById('pvTitle');
        const pvAuthor = document.getElementById('pvAuthor');
        const pvDate = document.getElementById('pvDate');
        const pvDescBlock = document.getElementById('pvDescBlock');
        const pvDesc = document.getElementById('pvDesc');
        const pvContent = document.getElementById('pvContent');

        previewBtn.addEventListener('click', () => {
            pvTitle.textContent = (titleInput.value || '').trim() || 'Untitled';
            pvAuthor.textContent = 'Admin';
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

            pvContent.innerHTML = editor.innerHTML;
            pvContent.querySelectorAll('a[href]').forEach(a => {
                const href = (a.getAttribute('href') || '').trim().toLowerCase();
                if (!href.startsWith('javascript:')) {
                    a.target = '_blank';
                    a.rel = 'noopener noreferrer';
                }
            });

            const modal = new bootstrap.Modal(document.getElementById('previewModal'));
            modal.show();
        });

        let isSubmitting = false;

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            if (saveBtn.disabled) return;

            isSubmitting = true;
            const oldText = saveBtn.textContent;
            saveBtn.disabled = true;
            saveBtn.textContent = 'Saving...';
            contentHidden.value = editor.innerHTML;

            const fd = new FormData(form);
            fd.set('ajax', '1');
            const headers = {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            };

            async function parseJsonOrThrow(resp) {
                const ct = (resp.headers.get('content-type') || '').toLowerCase();
                if (ct.includes('application/json')) return await resp.json();
                const text = await resp.text();
                throw new Error('Server không trả JSON hợp lệ.\nSnippet:\n' + text.slice(0, 400));
            }

            try {
                const resp = await fetch(form.action, {
                    method: 'POST',
                    body: fd,
                    headers,
                    credentials: 'include',
                    redirect: 'follow'
                });

                let data;
                try {
                    data = await parseJsonOrThrow(resp);
                } catch (jsonErr) {
                    showToast('danger', (jsonErr.message || '').replace(/\n/g, '<br>'), 'Lỗi');
                    return;
                }

                if (resp.status === 401) {
                    showToast('danger', 'Phiên đăng nhập hết hạn. Chuyển đến trang đăng nhập...', 'Unauthorized');
                    setTimeout(() => {
                        window.location.href = '/index.php?controller=auth&action=login';
                    }, 1200);
                    return;
                }

                if (!resp.ok) {
                    showToast('danger', `Lưu thất bại (HTTP ${resp.status}).`, 'Lỗi');
                    return;
                }

                if (data.ok) {
                    if (data.thumbnail_url) {
                        thumbPreview.src = data.thumbnail_url;
                        thumbWrap.classList.remove('d-none');
                        thumbWrap.dataset.initialUrl = data.thumbnail_url;
                    }
                    thumbInput.value = '';
                    normalizeLinksInEditor();
                    setBaselineFromDOM();
                    markDirtyIfChanged();
                    showToast('success', data.message || 'Đã lưu thay đổi.', 'Thành công');
                } else {
                    showToast('danger', (data.error || data.message || 'Lưu thất bại.'), 'Lỗi');
                }
            } catch (err) {
                showToast('danger', (err && err.message) ? err.message : 'Có lỗi mạng khi lưu.', 'Lỗi mạng');
            } finally {
                isSubmitting = false;
                saveBtn.textContent = oldText;
                markDirtyIfChanged();
            }
        });

        window.addEventListener('beforeunload', (e) => {
            if (isSubmitting) return;
            if (markDirtyIfChanged()) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        function markInitialToolbar() {
            try {
                refreshToolbarState();
            } catch {}
        }
        markInitialToolbar();

        queueMicrotask(() => {
            if (initialThumbUrl && !thumbPreview.src) {
                thumbPreview.src = initialThumbUrl;
            }
            setSelectedPromoIds(initialState.promotionIds || []);
            setBaselineFromDOM();
            markDirtyIfChanged();
        });

    })();
</script>