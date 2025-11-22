<?php

if (session_status() !== PHP_SESSION_ACTIVE) session_start();
$promotions           = $promotions           ?? [];
$selectedPromotionIds = $selectedPromotionIds ?? [];
$currentAdminName     = $currentAdminName     ?? 'Admin';

$draftToken = bin2hex(random_bytes(16));
$_SESSION['draft_tokens'][$draftToken] = time();
?>
<style>
    #editor.form-control {
        height: 640px;
        max-height: 640px;
        overflow-y: auto;
        overflow-x: hidden;
        position: relative;
    }

    #editor.form-control::before {
        content: attr(data-placeholder);
        position: absolute;
        inset: 1rem 1rem auto 1rem;
        color: #6c757d;
        pointer-events: none;
        opacity: 0;
        transition: opacity .15s ease-in-out;
        font-weight: 400;
    }

    #editor.form-control[data-placeholder-visible="true"]::before {
        opacity: 1;
    }


    #editor p {
        margin-bottom: .75rem;
    }

    #editor h2 {
        font-size: 1.125rem;
        margin-bottom: .5rem;
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

    a.promo-mention {
        display: inline-flex;
        align-items: center;
        gap: .25rem;
        padding: .1rem .4rem;
        border-radius: .5rem;
        background: #eef6ff;
        border: 1px solid #cfe2ff;
        color: #084298;
        text-decoration: none;
        white-space: nowrap;
    }

    a.promo-mention:hover {
        background: #e7f1ff;
        color: #06357a;
    }
</style>

<div class="container my-0!">
    <div class="card shadow-sm">
        <div class="card-body">
            <!-- Header -->
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
                <div>
                    <h1 class="h4 mb-1">Add New Article</h1>
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
                <input type="hidden" name="draft_token" id="draft_token" value="<?= htmlspecialchars($draftToken) ?>">
                <input type="hidden" name="content_html" id="content_html">

                <div class="row g-4">
                    <!-- LEFT: content -->
                    <div class="col-lg-8">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input id="title" name="title" type="text" class="form-control" required placeholder="Article title">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Short description</label>
                            <textarea id="description" name="description" rows="2" class="form-control" placeholder="Short description (excerpt)"></textarea>
                        </div>

                        <div class="mb-2">
                            <label class="form-label">Content (rich)</label>

                            <!-- Toolbar -->
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

                                <!-- Insert Promotion dropdown -->
                                <div class="btn-group btn-group-sm" role="group">
                                    <button type="button" class="btn btn-light border dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="insertPromoToggle">
                                        Insert promo
                                    </button>
                                    <ul class="dropdown-menu" id="promoMenu"></ul>
                                </div>
                            </div>

                            <!-- Editor -->
                            <div
                                id="editor"
                                class="form-control p-3"
                                contenteditable="true"
                                spellcheck="true"
                                aria-label="Article content"
                                data-placeholder="Write your content here..."
                                data-placeholder-visible="true"></div>

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
                                        required
                                        value=""
                                        placeholder="VD: general, flash_sale, fixed_price..."
                                        list="news_type_suggestions">

                                    <datalist id="news_type_suggestions">
                                        <option value="general">
                                        <option value="flash_sale">
                                        <option value="fixed_price">
                                    </datalist>
                                </div>

                                <!-- MANY-TO-MANY Promotions -->
                                <div class="mb-3">
                                    <label class="form-label">Promotions</label>
                                    <div class="d-flex gap-2">
                                        <select id="promotion_ids" name="promotion_ids[]" class="form-select" multiple size="6" aria-describedby="promoHelp">
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
                                    <div id="promoHelp" class="form-text">Hold <kbd>Ctrl</kbd>/<kbd>⌘</kbd> to select multiple promotions.</div>
                                    <div id="promotionCreateError" class="text-danger small mt-1 d-none"></div>
                                </div>

                                <div class="mb-2">
                                    <label for="thumbnail" class="form-label">Thumbnail (16:9)</label>
                                    <input id="thumbnail" name="thumbnail" type="file" class="form-control" accept="image/*">
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
    const editor = document.getElementById('editor');
    const contentHidden = document.getElementById('content_html');

    const titleInput = document.getElementById('title');
    const descInput = document.getElementById('description');

    const thumbInput = document.getElementById('thumbnail');
    const thumbWrap = document.getElementById('thumbPreviewWrap');
    const thumbPreview = document.getElementById('thumbPreview');

    const backBtn = document.getElementById('backBtn');
    const previewBtn = document.getElementById('previewBtn');

    const promotionCreateForm = document.getElementById('promotionCreateForm');
    const promotionSelect = document.getElementById('promotion_ids');
    const promoMenu = document.getElementById('promoMenu');

    const draftTokenEl = document.getElementById('draft_token');
    const draftToken = draftTokenEl ? draftTokenEl.value : '';

    let isSubmitting = false;

    form.addEventListener('submit', () => {
        isSubmitting = true;
        contentHidden.value = editor.innerHTML;
    });

    function updateEditorPlaceholder() {
        const isEmpty = !editor.textContent.trim();
        editor.dataset.placeholderVisible = isEmpty ? 'true' : 'false';
    }

    editor.addEventListener('input', updateEditorPlaceholder);
    editor.addEventListener('keyup', updateEditorPlaceholder);
    editor.addEventListener('paste', () => {
        setTimeout(updateEditorPlaceholder, 0);
    });
    editor.addEventListener('drop', () => {
        setTimeout(updateEditorPlaceholder, 0);
    });
    updateEditorPlaceholder();


    backBtn.addEventListener('click', () => {
        if (window.history.length > 1) window.history.back();
        else window.location.href = '/index.php?controller=adminNews&action=manage';
    });

    // Thumb preview
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

    // ============ IMAGE HANDLING ============
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
        } catch (e) {
            alert(e.message || 'Upload image failed');
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
            } catch (err) {
                alert(err.message || 'Upload image failed');
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
            } catch (err) {
                alert(err.message || 'Upload image failed');
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
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'include'
        });
        let data;
        try {
            data = await resp.json();
        } catch {
            throw new Error('Invalid JSON from server');
        }
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
        img.classList.add('img-fluid', 'rounded');
        insertNodeAtCursor(img);
        placeCaretAfter(img);
    }

    function insertNodeAtCursor(node) {
        const sel = window.getSelection();
        if (sel && sel.rangeCount) {
            const range = sel.getRangeAt(0);
            range.deleteContents();
            range.insertNode(node);
        } else {
            editor.appendChild(node);
        }
    }

    function placeCaretAfter(node) {
        const sel = window.getSelection();
        if (!sel) return;
        const range = document.createRange();
        range.setStartAfter(node);
        range.setEndAfter(node);
        sel.removeAllRanges();
        sel.addRange(range);
    }

    // ============ Toolbar ============
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
            if (cmd === 'h2') {
                const isH2 = getBlockAncestor()?.nodeName === 'H2';
                document.execCommand('formatBlock', false, isH2 ? 'p' : 'h2');
            } else if (cmd === 'ul') {
                document.execCommand('insertUnorderedList');
            } else {
                document.execCommand(cmd);
            }
            refreshToolbarState();
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
    });

    cmdButtons.clean.addEventListener('click', () => {
        document.execCommand('removeFormat');
        refreshToolbarState();
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
    editor.addEventListener('keyup', refreshToolbarState);
    normalizeLinksInEditor();

    // ============ Promo menu  ============
    function getSelectedPromotions() {
        const options = [...promotionSelect.options];
        return options.filter(op => op.selected).map(op => ({
            id: op.value,
            name: (op.dataset.name || op.textContent).trim()
        }));
    }

    function getAllPromotions() {
        const options = [...promotionSelect.options];
        return options.map(op => ({
            id: op.value,
            name: (op.dataset.name || op.textContent).trim()
        }));
    }

    function escapeHtml(str) {
        return String(str).replace(/&/g, '&amp;').replace(/"/g, '&quot;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    }

    function buildPromoMenu() {
        const selected = getSelectedPromotions();
        promoMenu.innerHTML = '';
        if (!selected.length) {
            promoMenu.insertAdjacentHTML('beforeend', `<li><span class="dropdown-item-text text-muted">No selected promotions</span></li>`);
            promoMenu.insertAdjacentHTML('beforeend', `<li><span class="dropdown-item-text small text-muted">Select promotions on the right first</span></li>`);
            promoMenu.insertAdjacentHTML('beforeend', `<li><hr class="dropdown-divider"></li>`);
            const all = getAllPromotions();
            if (all.length) {
                promoMenu.insertAdjacentHTML('beforeend', `<li><span class="dropdown-item-text fw-semibold">All promotions</span></li>`);
                all.forEach(p => promoMenu.insertAdjacentHTML('beforeend',
                    `<li><button type="button" class="dropdown-item" data-pid="${p.id}" data-pname="${escapeHtml(p.name)}">Insert: ${escapeHtml(p.name)}</button></li>`));
            }
            return;
        }
        promoMenu.insertAdjacentHTML('beforeend', `<li><span class="dropdown-item-text fw-semibold">Selected promotions (${selected.length})</span></li>`);
        selected.forEach(p => promoMenu.insertAdjacentHTML('beforeend',
            `<li><button type="button" class="dropdown-item" data-pid="${p.id}" data-pname="${escapeHtml(p.name)}">Insert: ${escapeHtml(p.name)}</button></li>`));
        promoMenu.insertAdjacentHTML('beforeend', `<li><hr class="dropdown-divider"></li>`);
        promoMenu.insertAdjacentHTML('beforeend', `<li><span class="dropdown-item-text fw-semibold">All promotions</span></li>`);
        getAllPromotions().forEach(p => promoMenu.insertAdjacentHTML('beforeend',
            `<li><button type="button" class="dropdown-item" data-pid="${p.id}" data-pname="${escapeHtml(p.name)}">${escapeHtml(p.name)}</button></li>`));
    }

    document.addEventListener('click', (e) => {
        const btn = e.target.closest('#promoMenu .dropdown-item');
        if (!btn) return;
        const pid = btn.getAttribute('data-pid');
        const pname = btn.getAttribute('data-pname') || '';
        if (!pid) return;
        const href = `/index.php?controller=promotion&action=view&id=${encodeURIComponent(pid)}`;
        const sel = window.getSelection();
        if (sel && sel.rangeCount && !sel.isCollapsed) {
            const range = sel.getRangeAt(0);
            const frag = range.extractContents();
            const a = document.createElement('a');
            a.href = href;
            a.className = 'promo-mention';
            a.dataset.promotionId = String(pid);
            a.appendChild(frag);
            range.insertNode(a);
            placeCaretAfter(a);
            normalizeLinksInEditor();
            return;
        }
        const display = prompt('Display text (optional):', `#${pname}`) || `#${pname}`;
        const html = `<a href="${href}" class="promo-mention" data-promotion-id="${pid}">${escapeHtml(display)}</a>&nbsp;`;
        try {
            document.execCommand('insertHTML', false, html);
        } catch {
            editor.insertAdjacentHTML('beforeend', html);
        }
        normalizeLinksInEditor();
    });

    // ============ Preview ============
    const pvThumbWrap = document.getElementById('pvThumbWrap');
    const pvThumb = document.getElementById('pvThumb');
    const pvTitle = document.getElementById('pvTitle');
    const pvAuthor = document.getElementById('pvAuthor');
    const pvDate = document.getElementById('pvDate');
    const pvDescBlock = document.getElementById('pvDescBlock');
    const pvDesc = document.getElementById('pvDesc');
    const pvContent = document.getElementById('pvContent');

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

        pvContent.innerHTML = editor.innerHTML;
        normalizeLinksInEl(pvContent);

        const modal = new bootstrap.Modal(document.getElementById('previewModal'));
        modal.show();
    });

    function normalizeLinksInEl(root) {
        root.querySelectorAll('a[href]').forEach(a => {
            const href = (a.getAttribute('href') || '').trim().toLowerCase();
            if (!href.startsWith('javascript:')) {
                a.target = '_blank';
                a.rel = 'noopener noreferrer';
            }
        });
    }

    window.addEventListener('beforeunload', () => {
        if (!draftToken || isSubmitting) return;
        const body = new URLSearchParams({
            draft: draftToken
        });
        navigator.sendBeacon('/index.php?controller=media&action=discardDraft', body);
    });
</script>