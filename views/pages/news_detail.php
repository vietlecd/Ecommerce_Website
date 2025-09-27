<style>
    .news-detail img {
        display: block;
        max-width: 300px;
        margin: 0 auto 20px;
        border-radius: 5px;
    }

    .news-detail h1 {
        font-size: 1.75rem;
        margin-bottom: 15px;
        color: #333;
    }

    .news-detail p {
        margin-bottom: 10px;
        color: #666;
    }

    .news-detail div {
        margin-top: 20px;
        line-height: 1.6;
        color: #333;
    }
</style>

<div class="news-detail">
    <h1><?php echo htmlspecialchars($news['Title']); ?></h1>
    <?php if ($news['thumbnail'] && file_exists($news['thumbnail'])): ?>
        <img src="/<?php echo htmlspecialchars($news['thumbnail']); ?>" alt="Thumbnail">
    <?php endif; ?>
    <p><strong>Người đăng:</strong> <?php echo htmlspecialchars($news['AdminName'] ?? 'Unknown'); ?></p>
    <p><strong>Ngày đăng:</strong> <?php echo date('d/m/Y H:i', strtotime($news['DateCreated'])); ?></p>
    <p><strong>Mô tả:</strong> <?php echo htmlspecialchars($news['Description']); ?></p>
    <div><strong>Nội dung:</strong> <?php echo nl2br(htmlspecialchars($news['Content'])); ?></div>
</div>