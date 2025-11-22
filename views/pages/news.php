<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Avenir Next', 'Avenir', 'Segoe UI', 'Helvetica Neue', 'Helvetica', 'Ubuntu', 'Roboto', 'Noto', Arial, sans-serif;
        background-color: #f9fafb;
        color: #2e4369;
        line-height: 1.6;
    }

    main .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px 24px;
    }

    /* Header Section */
    .news-header {
        margin-bottom: 48px;
    }

    .news-header h1 {
        font-size: 48px;
        font-weight: 600;
        color: #2e4369;
        margin-bottom: 16px;
        letter-spacing: -0.02em;
    }

    .news-header p {
        font-size: 18px;
        color: #6b7c93;
        max-width: 600px;
    }

    /* Search Section */
    .search-container {
        margin-bottom: 48px;
        max-width: 500px;
    }

    .search-wrapper {
        position: relative;
        display: flex;
        gap: 12px;
    }

    .search-input {
        flex: 1;
        padding: 14px 20px;
        border: 2px solid #e3e8ee;
        border-radius: 8px;
        font-size: 16px;
        transition: all .2s ease;
        background: white;
    }

    .search-input:focus {
        outline: none;
        border-color: #37517e;
        box-shadow: 0 0 0 3px rgba(55, 81, 126, 0.1);
    }

    .search-btn {
        padding: 14px 28px;
        background: #37517e;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 500;
        cursor: pointer;
        transition: all .2s ease;
    }

    .search-btn:hover {
        background: #2e4369;
        transform: translateY(-1px);
    }

    /* News Grid */
    .news-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(340px, 1fr));
        gap: 32px;
        margin-bottom: 64px;
    }

    .news-card {
        background: #fff;
        border-radius: 12px;
        overflow: hidden;
        transition: all .3s ease;
        border: 1px solid #e3e8ee;
        display: flex;
        flex-direction: column;
    }

    .news-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(46, 67, 105, .08);
        border-color: #d4dce6;
    }

    /* ==== KHUNG ẢNH: Ép tỉ lệ hiển thị bằng CSS, không phụ thuộc tỉ lệ gốc ==== */
    .news-image-wrapper {
        --card-ar: 16/9;
        /* đổi 16/9 thành tỉ lệ bạn muốn (4/3, 1/1, 5/3, ...) */
        aspect-ratio: var(--card-ar);
        /* ép tỉ lệ hiển thị */
        overflow: hidden;
        background: #f3f5f7;
    }

    .news-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .4s ease;
        display: block;
    }

    .news-card:hover .news-image {
        transform: scale(1.05);
    }


    @supports not (aspect-ratio: 1 / 1) {
        .news-image-wrapper {
            position: relative;
            padding-top: 56.25%;
        }

        /* 16:9 */
        .news-image {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    }

    .news-content {
        padding: 24px;
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin: 0;
    }

    /* Meta Info */
    .news-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .news-category {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        background: #e8f4f8;
        color: #37517e;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .5px;
    }

    .news-date {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #6b7c93;
        font-size: 14px;
    }

    .date-icon {
        width: 14px;
        height: 14px;
    }

    .news-title {
        font-size: 24px;
        font-weight: 600;
        color: #2e4369;
        line-height: 1.4;
        margin: 4px 0;
    }

    .news-description {
        font-size: 16px;
        color: #6b7c93;
        line-height: 1.5;
        display: -webkit-box;
        line-clamp: 3;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .news-link {
        color: inherit;
        text-decoration: none;
    }

    .news-link:hover .news-title {
        color: #37517e;
    }

    /* Footer section of card */
    .news-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: auto;
        padding-top: 16px;
        border-top: 1px solid #e3e8ee;
    }

    .read-more {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #37517e;
        font-size: 14px;
        font-weight: 500;
        transition: gap .2s ease;
    }

    .news-card:hover .read-more {
        gap: 10px;
    }

    .arrow-icon {
        width: 16px;
        height: 16px;
        transition: transform .2s ease;
    }

    .news-card:hover .arrow-icon {
        transform: translateX(2px);
    }

    /* Countdown Badge */
    .countdown-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 12px;
        background: #fff3cd;
        border: 1px solid #ffc107;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        color: #856404;
    }

    .countdown-icon {
        width: 14px;
        height: 14px;
    }

    /* Author Info */
    .news-author {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 14px;
        color: #6b7c93;
    }

    .author-avatar {
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 11px;
        font-weight: 600;
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 80px 24px;
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e3e8ee;
    }

    .empty-state-icon {
        font-size: 64px;
        margin-bottom: 24px;
        opacity: .3;
    }

    .empty-state h3 {
        font-size: 24px;
        color: #2e4369;
        margin-bottom: 12px;
    }

    .empty-state p {
        color: #6b7c93;
        font-size: 16px;
    }

    .pagination .page-item .page-link {
        color: #2e4369;
    }

    .pagination .page-item.active .page-link {
        color: #ff6b6b !important;
    }

    .pagination .page-item.disabled .page-link {
        color: #c5ccd6 !important;
    }

    @media (max-width: 768px) {
        .news-header h1 {
            font-size: 36px;
        }

        .news-grid {
            grid-template-columns: 1fr;
            gap: 24px;
        }

        .search-wrapper {
            flex-direction: column;
        }

        .search-btn {
            width: 100%;
        }

        .news-image-wrapper {
            --card-ar: 1/1;
        }
    }

    @media (max-width:480px) {
        main .container {
            padding: 0;
        }
    }
</style>

<div>
    <!-- Header -->
    <div class="news-header">
        <h1>News & Updates</h1>
        <p>Stay up to date with our latest news, product updates, and company announcements.</p>
    </div>

    <!-- Search -->
    <div class="search-container">
        <form method="get" action="">
            <div class="search-wrapper">
                <input
                    type="text"
                    name="search"
                    class="search-input"
                    placeholder="Search for news..."
                    value="<?php echo htmlspecialchars($search); ?>">
                <input type="hidden" name="controller" value="news">
                <input type="hidden" name="action" value="index">
                <button type="submit" class="search-btn">Search</button>
            </div>
        </form>
    </div>

    <?php if (empty($news)): ?>
        <div class="empty-state">
            <div class="empty-state-icon">📰</div>
            <h3>No news found</h3>
            <p>Try adjusting your search or check back later for new updates.</p>
        </div>
    <?php else: ?>
        <div class="news-grid">
            <?php foreach ($news as $item): ?>
                <?php
                $thumb = !empty($item['Thumbnail']) ? '/' . ltrim($item['Thumbnail'], '/\\') : '/assets/images/placeholder.png';
                $title = htmlspecialchars($item['Title']);
                ?>
                <article class="news-card">
                    <a href="/index.php?controller=news&action=detail&id=<?php echo $item['NewsID']; ?>" class="news-link">
                        <div class="news-image-wrapper">
                            <img
                                src="<?php echo htmlspecialchars($thumb); ?>"
                                alt="<?php echo $title; ?>"
                                class="news-image"
                                loading="lazy"
                                onerror="this.onerror=null;this.src='/assets/images/placeholder.png'">
                        </div>

                        <div class="news-content">
                            <!-- Meta Information -->
                            <div class="news-meta">
                                <?php if (!empty($item['Category'])): ?>
                                    <span class="news-category"><?php echo htmlspecialchars($item['Category']); ?></span>
                                <?php endif; ?>

                                <?php $d = get_news_date($item); ?>
                                <span class="news-date">
                                    <svg class="date-icon" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                    </svg>
                                    <time datetime="<?php echo htmlspecialchars($d['iso']); ?>"><?php echo htmlspecialchars($d['fmt']); ?></time>
                                </span>
                            </div>

                            <!-- Title -->
                            <h2 class="news-title"><?php echo $title; ?></h2>

                            <!-- Description -->
                            <?php if (!empty($item['Description']) || !empty($item['Content'])): ?>
                                <p class="news-description">
                                    <?php
                                    $description = !empty($item['Description']) ? $item['Description'] : $item['Content'];
                                    echo htmlspecialchars(substr(strip_tags($description), 0, 150)) . '...';
                                    ?>
                                </p>
                            <?php endif; ?>

                            <!-- Footer -->
                            <div class="news-footer">
                                <div class="news-author">
                                    <?php if (!empty($item['Author'])): ?>
                                        <div class="author-avatar">
                                            <?php echo strtoupper(substr($item['Author'], 0, 1)); ?>
                                        </div>
                                        <span><?php echo htmlspecialchars($item['Author']); ?></span>
                                    <?php endif; ?>
                                </div>

                                <?php if (!empty($item['end_date'])): ?>
                                    <div class="countdown-badge" data-end-date="<?php echo $item['end_date']; ?>">
                                        <svg class="countdown-icon" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="timer">Loading...</span>
                                    </div>
                                <?php else: ?>
                                    <span class="read-more">
                                        Read more
                                        <svg class="arrow-icon" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                </article>
            <?php endforeach; ?>
        </div>

        <!-- Pagination -->
        <?php if ($totalPages > 1): ?>
            <nav>
                <ul class="pagination">

                    <!-- Previous -->
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="/index.php?controller=news&action=index&search=<?= urlencode($search) ?>&page=<?= $page - 1 ?>">
                                <i class="fa-solid fa-chevron-left"></i>
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="page-item disabled">
                            <span class="page-link"><i class="fa-solid fa-chevron-left"></i></span>
                        </li>
                    <?php endif; ?>


                    <?php
                    $startPage = max(1, $page - 2);
                    $endPage   = min($totalPages, $page + 2);

                    if ($startPage > 1):
                    ?>
                        <li class="page-item">
                            <a class="page-link" href="/index.php?controller=news&action=index&search=<?= urlencode($search) ?>&page=1">1</a>
                        </li>

                        <?php if ($startPage > 2): ?>
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="/index.php?controller=news&action=index&search=<?= urlencode($search) ?>&page=<?= $i ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($endPage < $totalPages): ?>
                        <?php if ($endPage < $totalPages - 1): ?>
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        <?php endif; ?>

                        <li class="page-item">
                            <a class="page-link"
                                href="/index.php?controller=news&action=index&search=<?= urlencode($search) ?>&page=<?= $totalPages ?>">
                                <?= $totalPages ?>
                            </a>
                        </li>
                    <?php endif; ?>


                    <!-- Next -->
                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="/index.php?controller=news&action=index&search=<?= urlencode($search) ?>&page=<?= $page + 1 ?>">
                                <i class="fa-solid fa-chevron-right"></i>

                            </a>
                        </li>
                    <?php else: ?>
                        <li class="page-item disabled">
                            <span class="page-link"><i class="fa-solid fa-chevron-right"></i></span>
                        </li>
                    <?php endif; ?>

                </ul>
            </nav>
        <?php endif; ?>

    <?php endif; ?>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const countdownElements = document.querySelectorAll('.countdown-badge');

        countdownElements.forEach(function(element) {
            const endDate = new Date(element.getAttribute('data-end-date')).getTime();
            const timerElement = element.querySelector('.timer');

            function updateCountdown() {
                const now = new Date().getTime();
                const distance = endDate - now;

                if (distance < 0) {
                    element.style.background = '#f8d7da';
                    element.style.borderColor = '#dc3545';
                    element.style.color = '#721c24';
                    timerElement.textContent = "Expired";
                    return;
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                timerElement.textContent = `${days}d ${hours}h ${minutes}m ${seconds}s`;
            }

            updateCountdown();
            setInterval(updateCountdown, 1000);
        });
    });
</script>

<?php
$categories = $categories ?? [];
$selectedCategory = $_GET['category'] ?? '';

function get_news_date(array $item)
{
    $candidates = ['CreatedAt', 'DateCreated', 'created_at', 'created', 'start_date', 'startDate', 'Date', 'PublishedAt', 'published_at'];
    $raw = null;
    foreach ($candidates as $key) {
        if (!empty($item[$key])) {
            $raw = $item[$key];
            break;
        }
    }
    if (empty($raw) && !empty($item['end_date'])) $raw = $item['end_date'];
    if (empty($raw)) $raw = date('c');

    try {
        $ts = strtotime($raw);
    } catch (Throwable $e) {
        $ts = false;
    }
    if ($ts === false || $ts === -1) {
        $iso = date('c');
        $fmt = date('d M Y');
    } else {
        $iso = date('c', $ts);
        $fmt = date('d M Y', $ts);
    }

    return ['raw' => $raw, 'iso' => $iso, 'fmt' => $fmt];
}
?>

<?php require_once 'views/components/footer.php'; ?>