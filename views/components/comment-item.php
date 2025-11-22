<style>
    .cmt-item {
        display: flex;
        gap: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid #f0f0f0;
    }

    .cmt-item:last-child {
        border-bottom: none;
        padding-bottom: 0px;
    }

    .cmt-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #ececec;
        color: #666;
        font-weight: 700;
        font-size: .95rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex: 0 0 36px;
        user-select: none;
    }

    .cmt-main {
        flex: 1 1 auto;
        min-width: 0;
    }

    .cmt-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 6px;
    }

    .cmt-author {
        font-weight: 700;
        color: #211922;
        font-size: .95rem;
    }

    .cmt-text {
        color: #333;
        font-size: 1rem;
        line-height: 1.6;
        white-space: pre-line;

        overflow-wrap: anywhere;
        margin: 2px 0 10px;
    }

    .cmt-actions {
        display: flex;
        align-items: center;
        gap: 14px;
        font-size: .9rem;
        color: #767676;
    }

    .cmt-action {
        cursor: pointer;
        border: none;
        background: none;
        padding: 0;
        color: #767676;
    }

    .cmt-action:hover {
        color: #e60023;
    }

    .cmt-like-wrap {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .cmt-like-badge {
        min-width: 28px;
        height: 20px;
        padding: 0 6px;
        border-radius: 10px;
        background: #f5f5f5;
        color: #555;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: .8rem;
        font-weight: 600;
    }

    .cmt-dot {
        color: #bdbdbd;
    }

    .cmt-time {
        margin-left: auto;
        color: #9a9a9a;
        font-size: .85rem;
    }
</style>

<?php
// Input: $c (array comment), $renderReplies (bool, default true)
// Yêu cầu: đã có hàm time_ago_vi() ở scope global (hoặc require helper trước)

$who     = $c['Username'] ?? 'Guest';
$initial = mb_strtoupper(mb_substr($who, 0, 1, 'UTF-8'), 'UTF-8');
$when    = function_exists('time_ago_vi')
    ? time_ago_vi($c['CreatedAt'] ?? date('c'))
    : date('d/m/Y', strtotime($c['CreatedAt'] ?? 'now'));
$text    = nl2br(htmlspecialchars($c['Content'] ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'));
$like    = (int)($c['like_count'] ?? 0);
$cid     = (int)($c['id'] ?? $c['CommentID'] ?? 0);
?>

<div class="cmt-item" data-comment-id="<?= $cid ?>" data-created-at="<?= $c['CreatedAt'] ?? date('c') ?>">
    <div class="cmt-avatar" aria-hidden="true"><?= $initial ?></div>

    <div class="cmt-main">
        <div class="cmt-header">
            <span class="cmt-author"><?= htmlspecialchars($who) ?></span>
            <span class="cmt-time"><?= htmlspecialchars($when) ?></span>
        </div>

        <div class="cmt-text"><?= $text ?></div>

        <div class="cmt-actions">
            <button class="cmt-action" type="button">Thích</button>
            <div class="cmt-like-wrap">
                <span class="cmt-like-badge"><?= $like ?></span>
            </div>
            <!-- <button class="cmt-action" type="button">Answer</button> -->
            <span class="cmt-dot">·</span>

        </div>

        <?php if (!empty($renderReplies) && !empty($c['replies']) && is_array($c['replies'])): ?>
            <div class="cmt-children">
                <?php foreach ($c['replies'] as $r):
                    // set biến local cho partial
                    $cChild = $r;
                    $renderReplies = false; // ví dụ chỉ render 1 cấp; nếu muốn vô hạn, để true
                    include __DIR__ . '/_comment_item.php';
                endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>