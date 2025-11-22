<style>
    main .container {
        max-width: unset;
        width: 100%;
        margin: 0;
        padding: 0px;
    }

    main .container>* {
        max-width: 1000px;
        margin-inline: auto;
    }

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
        letter-spacing: -0.5px;
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
        font-size: 0.875rem;
    }

    .news-meta .separator {
        color: #e9e9e9;
    }

    .news-thumbnail {
        width: 100%;
        margin-top: -32px;
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
        object-fit: cover;
    }

    .news-description {
        font-size: 1.125rem;
        line-height: 1.6;
        color: #333;
        margin-bottom: 32px;
        padding: 20px;
        background: #f7f7f7;
        border-radius: 12px;
        border-left: 4px solid #e60023;
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

    .news-content strong,
    .news-content b {
        font-weight: 600;
        color: #211922;
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

    .news-content ul,
    .news-content ol {
        margin: 16px 0;
        padding-left: 24px;
    }

    .news-content li {
        margin-bottom: 8px;
    }

    .news-content a {
        color: #e60023;
        text-decoration: none;
        border-bottom: 1px solid transparent;
        transition: border-color 0.2s;
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

    @media (max-width: 768px) {
        .container {
            padding: 0px;
            margin: 0px;
            width: 100%;
        }

        .container>.news-detail>*:not(.thumbnail-container),
        .container>.comments {
            margin: 0 16px;
        }

        .news-detail {
            padding: 0;
        }

        .news-detail h1 {
            font-size: 1.875rem;
        }

        .news-description {
            font-size: 1rem;
            padding: 16px;
        }

        .news-thumbnail {
            width: 100%;
            height: unset;
            /* height: 400px; */
            /* margin-top: -32px; */
            margin-bottom: 32px;
            overflow: hidden;
        }

        .news-content img {
            min-height: 174px;
        }
    }


    /* comments */
    .comments-section {
        margin-top: 48px;
        padding-top: 32px;
        border-top: 2px solid #e9e9e9;
    }

    /* Header + counter */
    .comments-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
    }

    .comments-header h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #211922;
        margin: 0;
    }

    .comment-count {
        font-size: .875rem;
        color: #767676;
        background: #f5f5f5;
        padding: 4px 12px;
        border-radius: 12px;
    }

    /* Empty state */
    .empty-message {
        text-align: center;
        padding: 40px 20px;
        color: #767676;
        font-size: .95rem;
        background: #fafafa;
        border-radius: 8px;
        margin-bottom: 32px;
    }

    /* Comments List */
    .comments-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 32px;
    }

    .cmt-children {
        margin-left: 48px;
        border-left: 2px solid #f5f5f5;
        padding-left: 12px;
    }

    /* ===== Comment Form ===== */
    .comment-form-section {
        background: #fafafa;
        padding: 24px;
        border-radius: 12px;
        border: 1px solid #e9e9e9;
        margin-top: 32px;
    }

    .comment-form-section h3 {
        font-size: 1.125rem;
        font-weight: 600;
        color: #211922;
        margin: 0 0 16px 0;
    }

    .form-group {
        margin: 0;
    }

    .comment-form textarea {
        width: 100%;
        min-height: 100px;
        padding: 12px 16px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-family: inherit;
        font-size: 1rem;
        color: #211922;
        background: #fff;
        resize: vertical;
        transition: border-color .2s ease;
        line-height: 1.5;
    }

    .comment-form textarea:focus {
        outline: none;
        border-color: #e60023;
    }

    .comment-form textarea::placeholder {
        color: #aaa;
    }

    .form-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-top: 12px;
    }

    .form-hint {
        font-size: .85rem;
        color: #767676;
    }

    .btn-submit {
        background: #e60023;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 10px 20px;
        font-weight: 600;
        font-size: .95rem;
        cursor: pointer;
        transition: opacity .2s ease;
    }

    .btn-submit:hover {
        opacity: .9;
    }

    .btn-submit:active {
        transform: scale(.98);
    }

    /* Alerts */
    .alert {
        padding: 12px 16px;
        border-radius: 8px;
        margin-bottom: 16px;
        font-size: .9rem;
    }

    .alert-success {
        background: #ecfff1;
        border: 1px solid #b6ebc3;
        color: #145a1c;
    }

    .alert-error {
        background: #fff0f0;
        border: 1px solid #ffcccc;
        color: #941111;
    }

    /* ===== Responsive ===== */
    @media (max-width: 1024px) {

        main .container>.news-detail>*:not(.thumbnail-container),
        main .container>.comments-section {
            margin: 0 16px;
        }
    }

    @media (max-width:768px) {
        .comments-section {
            margin-top: 32px;
            padding-top: 24px;
        }

        /* .comments-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        } */

        .comments-header h2 {
            font-size: 1.25rem;
        }


        .cmt-item {
            padding: 16px 0;
        }

        .comment-header,
        .cmt-header {
            flex-wrap: wrap;
        }

        .comment-form-section {
            padding: 20px;
        }

        .form-footer {
            flex-direction: column;
            align-items: stretch;
        }

        .btn-submit {
            width: 100%;
        }

        .form-hint {
            text-align: center;
            order: -1;
        }
    }

    @media (max-width:480px) {

        .news-thumbnail img {
            height: 200px;
        }

        .comments-header h2 {
            font-size: 1.125rem;
        }

        .comment-form-section {
            padding: 16px;
        }

        .comment-form textarea {
            font-size: .95rem;
        }
    }


    @keyframes flashIn {
        from {
            background: #fff8d8;
        }

        to {
            background: transparent;
        }
    }
</style>

<div class="news-detail">
    <!-- Thumbnail -->
    <?php if ($news['Thumbnail'] && file_exists($news['Thumbnail'])): ?>
        <div class="thumbnail-container">
            <div class="news-thumbnail">
                <img src="/<?php echo htmlspecialchars($news['Thumbnail']); ?>" alt="<?php echo htmlspecialchars($news['Title']); ?>">
            </div>
        </div>
    <?php endif; ?>

    <!-- Header -->
    <h1><?php echo htmlspecialchars($news['Title']); ?></h1>

    <!-- Meta Information -->
    <div class="news-meta">
        <p><strong><?php echo htmlspecialchars($news['AdminName'] ?? 'Unknown'); ?></strong></p>
        <span class="separator">•</span>
        <p><?php echo date('d/m/Y', strtotime($news['CreatedAt'])); ?></p>
    </div>


    <!-- Description -->
    <!-- <?php if (!empty($news['Description'])): ?>
        <div class="news-description">
            <?php echo htmlspecialchars($news['Description']); ?>
        </div>
    <?php endif; ?> -->

    <!-- Main Content (HTML support for user styling) -->
    <div class="news-content">
        <?php
        // Allow HTML content while still sanitizing dangerous tags
        // If you trust your admin users, you can output HTML directly:
        echo $news['Content'];

        // OR if you want to sanitize but keep formatting:
        // echo strip_tags($news['Content'], '<p><br><strong><b><em><i><u><h2><h3><h4><ul><ol><li><a><img><blockquote>');
        ?>
    </div>
</div>

<div id="comments" class="comments-section">
    <div class="comments-header">
        <h2>Comments</h2>
        <span id="comment-count" class="comment-count"><?php echo $totalComments; ?></span>
    </div>

    <?php if (empty($comments)): ?>
        <p id="empty-message" class="empty-message">No comments yet. Be the first to comment!</p>
        <div id="comments-list" class="comments-list" style="display:none;"></div>
    <?php else: ?>
        <div id="comments-list" class="comments-list">
            <?php
            function time_ago_vi($dateStr)
            {
                $ts = is_numeric($dateStr) ? (int)$dateStr : strtotime($dateStr);
                if (!$ts) return htmlspecialchars($dateStr);
                $diff = time() - $ts;
                if ($diff < 60) return $diff . 's trước';
                if ($diff < 3600) return floor($diff / 60) . 'm trước';
                if ($diff < 86400) return floor($diff / 3600) . 'h trước';
                if ($diff < 30 * 86400) return floor($diff / 86400) . ' ngày trước';
                return date('d/m/Y', $ts);
            }
            ?>

            <?php foreach ($comments as $c):
                // mỗi lần render, truyền $c và chọn có render replies hay không
                $renderReplies = true; // hoặc false nếu chỉ muốn cấp 1
                include __DIR__ . '/../components/comment-item.php';
            endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($comments) && $hasMore): ?>
        <div style="text-align:center;margin:16px 0;">
            <a id="load-more"
                role="button"
                href="/index.php?controller=news&action=loadComments&news_id=<?= $news_id ?>"
                data-ajax="true">Load more</a>
        </div>
    <?php endif; ?>


    <div id="comment-alert" class="alert" style="display:none;"></div>

    <div class="comment-form-section">
        <h3>Leave a Comment</h3>
        <form id="comment-form" class="comment-form" method="post" action="/index.php?controller=news&action=addComment" autocomplete="off">
            <input type="hidden" name="news_id" value="<?php echo (int)$news['NewsID']; ?>">
            <div class="form-group">
                <textarea
                    id="comment_content"
                    name="comment_content"
                    maxlength="1000"
                    required
                    placeholder="Write your comment here..."
                    rows="4"></textarea>
                <div class="form-footer">
                    <span class="form-hint">Guests can also comment • Max 1000 characters</span>
                    <button id="btn-submit-comment" class="btn-submit" type="submit">Post Comment</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    (function() {
        const form = document.getElementById('comment-form');
        if (!form) return;

        const list = document.getElementById('comments-list');
        const countEl = document.getElementById('comment-count');
        const emptyMsg = document.getElementById('empty-message');
        const alertEl = document.getElementById('comment-alert');
        const btn = document.getElementById('btn-submit-comment');
        const textarea = document.getElementById('comment_content');

        function showAlert(kind, msg) {
            if (!alertEl) return;
            alertEl.className = 'alert ' + (kind === 'error' ? 'alert-error' : 'alert-success');
            alertEl.textContent = msg;
            alertEl.style.display = 'block';
            setTimeout(() => {
                alertEl.style.display = 'none';
            }, 2500);
        }

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const content = textarea.value.trim();
            if (!content) {
                showAlert('error', 'Nội dung bình luận trống.');
                return;
            }

            const fd = new FormData(form);
            const xhr = new XMLHttpRequest();

            xhr.open('POST', form.action, true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.setRequestHeader('Accept', 'application/json');
            xhr.withCredentials = true;

            btn.disabled = true;

            xhr.onload = function() {
                btn.disabled = false;

                if (xhr.status >= 200 && xhr.status < 300) {
                    let data;
                    try {
                        data = JSON.parse(xhr.responseText);
                    } catch {
                        showAlert('error', 'Phản hồi không phải JSON.');
                        return;
                    }

                    if (!data || !data.ok) {
                        showAlert('error', (data && data.message) || 'Gửi bình luận thất bại.');
                        return;
                    }

                    // bỏ empty state, hiện list (nếu list đang hidden)
                    if (emptyMsg) {
                        emptyMsg.remove();
                        if (list && list.style.display === 'none') list.style.display = '';
                    }

                    // chèn HTML comment mới (server render)
                    if (list && data.html) {
                        list.insertAdjacentHTML('afterbegin', data.html);
                        const first = list.firstElementChild;
                        if (first) {
                            first.style.animation = 'flashIn .8s ease';
                            setTimeout(() => {
                                first.style.animation = '';
                            }, 900);
                        }
                    }

                    // cập nhật counter
                    if (typeof data.count === 'number' && countEl) {
                        countEl.textContent = data.count;
                    } else if (countEl) {
                        countEl.textContent = String(parseInt(countEl.textContent || '0', 10) + 1);
                    }

                    // reset form
                    textarea.value = '';
                    showAlert('success', 'Đã đăng bình luận.');
                } else {
                    showAlert('error', 'Lỗi ' + xhr.status + ' khi gửi bình luận.');
                }
            };

            xhr.onerror = function() {
                btn.disabled = false;
                showAlert('error', 'Lỗi mạng. Thử lại sau.');
            };

            // nếu muốn theo dõi tiến trình upload (ít ý nghĩa với text nhỏ, nhưng cứ để đây):
            // xhr.upload.onprogress = function(e) {
            //   if (e.lengthComputable) {
            //     const pct = (e.loaded / e.total * 100) | 0;
            //     // cập nhật thanh tiến trình nếu bạn có
            //   }
            // };

            xhr.send(fd); // KHÔNG set 'Content-Type' khi gửi FormData
        });
    })();
</script>


<script>
    (function() {
        const list = document.getElementById('comments-list');
        const link = document.getElementById('load-more');
        if (!list || !link) return;

        link.addEventListener('click', function(e) {
            if (link.dataset.ajax === 'true') e.preventDefault();
            if (link.getAttribute('aria-disabled') === 'true') return;
            const lastItem = list.querySelector('.cmt-item:last-of-type');
            const lastAt = lastItem?.dataset.createdAt || '';

            const url = new URL(link.href, window.location.origin);
            if (lastAt) url.searchParams.set('last-created-at', lastAt);

            link.setAttribute('aria-disabled', 'true');
            link.setAttribute('aria-busy', 'true');

            const xhr = new XMLHttpRequest();
            xhr.open('GET', url.toString(), true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.setRequestHeader('Accept', 'application/json');


            xhr.onload = function() {
                link.removeAttribute('aria-disabled');
                link.removeAttribute('aria-busy');

                if (xhr.status >= 200 && xhr.status < 300) {
                    let data;
                    try {
                        data = JSON.parse(xhr.responseText);
                    } catch {
                        return;
                    }
                    if (!data || !data.ok) return;

                    if (data.html) {
                        list.insertAdjacentHTML('beforeend', data.html);
                    }

                    if (data.hasMore) {
                        const newLast = list.querySelector('.cmt-item:last-of-type');
                        const nextAt = newLast?.dataset.createdAt || lastAt;

                        const nextUrl = new URL(link.href, window.location.origin);
                        if (nextAt) nextUrl.searchParams.set('last-created-at', nextAt);
                        link.href = nextUrl.toString();
                    } else {
                        const wrap = link.closest('.load-more-wrap');
                        if (wrap) wrap.remove();
                        else link.remove();
                    }
                }
            };

            xhr.onerror = function() {
                link.removeAttribute('aria-disabled');
                link.removeAttribute('aria-busy');
            };

            xhr.send();
        });
    })();
</script>