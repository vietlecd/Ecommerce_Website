<style>
    .news-list {
        margin-top: 20px;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }

    .news-item {
        text-align: center;
    }

    .news-thumbnail {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 8px;
        transition: transform 0.3s ease;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .news-thumbnail:hover {
        transform: scale(1.05);
    }

    .pagination {
        margin-top: 30px;
        text-align: center;
    }

    .pagination a {
        display: inline-block;
        padding: 10px 18px;
        margin: 0 5px;
        border: 1px solid #ddd;
        text-decoration: none;
        color: #333;
        border-radius: 5px;
        transition: background-color 0.3s ease;
    }

    .pagination a.active {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
    }

    .pagination a:hover:not(.active) {
        background-color: #f1f1f1;
    }

    .countdown {
        margin-top: 10px;
        font-weight: bold;
        color: #d9534f;
    }
</style>

<div class="section-title">
    <h2>News & Updates</h2>
</div>

<form method="get" action="" class="form-container">
    <div class="form-group">
        <label for="search">Search News</label>
        <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Enter keyword...">
        <input type="hidden" name="controller" value="news">
        <input type="hidden" name="action" value="index">
        <button type="submit" class="form-btn">Search</button>
    </div>
</form>

<div class="news-list">
    <?php if (empty($news)): ?>
        <p>No news found.</p>
    <?php else: ?>
        <?php foreach ($news as $item): ?>
            <div class="news-item">
                <?php if ($item['thumbnail'] && file_exists($item['thumbnail'])): ?>
                    <a href="/index.php?controller=news&action=trackClick&id=<?php echo $item['NewsID']; ?>">
                        <img src="/<?php echo htmlspecialchars($item['thumbnail']); ?>" alt="Thumbnail" class="news-thumbnail" loading="lazy" title="<?php echo htmlspecialchars($item['Title']); ?>">
                    </a>
                <?php else: ?>
                    <a href="/index.php?controller=news&action=trackClick&id=<?php echo $item['NewsID']; ?>">
                        <img src="/assets/images/placeholder.png" alt="No Thumbnail" class="news-thumbnail" loading="lazy" title="<?php echo htmlspecialchars($item['Title']); ?>">
                    </a>
                <?php endif; ?>
                <?php if (!empty($item['end_date'])): ?>
                    <div class="countdown" data-end-date="<?php echo $item['end_date']; ?>">
                        Time remaining: <span class="timer"></span>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="pagination">
    <?php if ($totalPages > 1): ?>
        <?php if ($page > 1): ?>
            <a href="/index.php?controller=news&action=index&search=<?php echo urlencode($search); ?>&page=<?php echo $page - 1; ?>">Trang trước</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="/index.php?controller=news&action=index&search=<?php echo urlencode($search); ?>&page=<?php echo $i; ?>" <?php echo $i === $page ? 'class="active"' : ''; ?>><?php echo $i; ?></a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="/index.php?controller=news&action=index&search=<?php echo urlencode($search); ?>&page=<?php echo $page + 1; ?>">Trang sau</a>
        <?php endif; ?>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const countdownElements = document.querySelectorAll('.countdown');

    countdownElements.forEach(function(element) {
        const endDate = new Date(element.getAttribute('data-end-date')).getTime();
        const timerElement = element.querySelector('.timer');

        function updateCountdown() {
            const now = new Date().getTime();
            const distance = endDate - now;

            if (distance < 0) {
                timerElement.innerHTML = "Expired";
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            timerElement.innerHTML = `${days}d ${hours}h ${minutes}m ${seconds}s`;
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);
    });
});
</script>

<?php require_once 'views/components/footer.php'; ?>