<?php
$categories = $categories ?? [];
$selectedCategory = $_GET['category'] ?? '';
$search = $search ?? '';
$totalNews = $totalNews ?? count($news ?? []);
$page = $page ?? 1;
$totalPages = $totalPages ?? 1;

/**
 * Resolve thumbnail path:
 * - Ưu tiên field 'Thumbnail', nếu không có thì dùng 'thumbnail'
 * - Nếu là URL => dùng luôn
 * - Nếu là path local => normalize và prefix "/"
 * - Nếu không có => dùng placeholder
 */
function resolve_thumbnail($item)
{
    $raw = null;

    if (!empty($item['Thumbnail'])) {
        $raw = $item['Thumbnail'];
    } elseif (!empty($item['thumbnail'])) {
        $raw = $item['thumbnail'];
    }

    $fallback = '/assets/images/placeholder.png';
    if (empty($raw)) {
        return $fallback;
    }

    // Trường hợp là URL tuyệt đối
    if (filter_var($raw, FILTER_VALIDATE_URL)) {
        return $raw;
    }

    // Trường hợp là đường dẫn tương đối trong project
    $normalized = ltrim($raw, '/\\');

    // Nếu file tồn tại trên server, trả về path có prefix "/"
    if (file_exists($normalized)) {
        return '/' . $normalized;
    }

    // Nếu không chắc, vẫn cứ trả về path đã normalize (để browser thử load)
    return '/' . $normalized;
}

/**
 * Helper định dạng ngày – giữ lại từ HEAD vì cũng hữu ích
 */
function get_news_date(array $item)
{
    $candidates = [
        'CreatedAt',
        'DateCreated',
        'created_at',
        'created',
        'start_date',
        'startDate',
        'Date',
        'PublishedAt',
        'published_at'
    ];
    $raw = null;
    foreach ($candidates as $key) {
        if (!empty($item[$key])) {
            $raw = $item[$key];
            break;
        }
    }
    if (empty($raw) && !empty($item['end_date'])) $raw = $item['end_date'];
    if (empty($raw)) $raw = date('c');

    $ts = strtotime($raw);
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

<section class="news-hero">
    <div class="news-hero-content">
        <p class="news-hero-label">News & Updates</p>
        <h1>Stories, insights, and launch alerts</h1>
        <p>Curated editorials, promotion breakdowns, and behind-the-scenes articles for sneakerheads and shoppers alike.</p>
        <div class="news-hero-stats">
            <span><?php echo number_format($totalNews); ?> articles published</span>
            <span>Page <?php echo $page; ?> of <?php echo max(1, $totalPages); ?></span>
        </div>
    </div>
</section>

<?php if (!empty($recentNews) || !empty($popularNews)): ?>
    <section class="news-highlight-widgets">
        <div class="news-widget-grid">
            <?php if (!empty($recentNews)): ?>
                <div class="news-widget-card" style="background-image: url('https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?auto=format&fit=crop&w=1000&q=80');">
                    <div class="news-widget-card-inner">
                        <p class="news-widget-label">Recent</p>
                        <h3>Fresh off the press</h3>
                        <ul class="news-widget-items">
                            <?php foreach ($recentNews as $item): ?>
                                <?php
                                $thumb = resolve_thumbnail($item);
                                ?>
                                <li>
                                    <a href="/index.php?controller=news&action=detail&id=<?php echo $item['NewsID']; ?>">
                                        <img src="<?php echo htmlspecialchars($thumb); ?>"
                                            alt="<?php echo htmlspecialchars($item['Title']); ?>"
                                            loading="lazy"
                                            onerror="this.onerror=null;this.src='/assets/images/placeholder.png'">
                                        <div>
                                            <?php $d = get_news_date($item); ?>
                                            <span><?php echo htmlspecialchars($d['fmt']); ?></span>
                                            <p><?php echo htmlspecialchars($item['Title']); ?></p>
                                        </div>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($popularNews)): ?>
                <div class="news-widget-card" style="background-image: url('https://images.unsplash.com/photo-1520970014086-2208d157c9e2?auto=format&fit=crop&w=1000&q=80');">
                    <div class="news-widget-card-inner">
                        <p class="news-widget-label">Popular</p>
                        <h3>Most viewed stories</h3>
                        <ul class="news-widget-items">
                            <?php foreach ($popularNews as $item): ?>
                                <?php
                                $thumb = resolve_thumbnail($item);
                                ?>
                                <li>
                                    <a href="/index.php?controller=news&action=detail&id=<?php echo $item['NewsID']; ?>">
                                        <img src="<?php echo htmlspecialchars($thumb); ?>"
                                            alt="<?php echo htmlspecialchars($item['Title']); ?>"
                                            loading="lazy"
                                            onerror="this.onerror=null;this.src='/assets/images/placeholder.png'">
                                        <div>
                                            <span>
                                                <?php
                                                if (!empty($item['clicks'])) {
                                                    echo number_format($item['clicks']) . ' views';
                                                } else {
                                                    echo 'New';
                                                }
                                                ?>
                                            </span>
                                            <p><?php echo htmlspecialchars($item['Title']); ?></p>
                                        </div>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>

<section class="news-search-panel">
    <form method="get" action="" class="news-search-form">
        <input type="hidden" name="controller" value="news">
        <input type="hidden" name="action" value="index">
        <label for="search" class="sr-only">Search news</label>
        <input type="text"
            id="search"
            name="search"
            value="<?php echo htmlspecialchars($search); ?>"
            placeholder="Search articles, promos, topics..."
            class="news-search-input">
        <button type="submit" class="news-search-btn">
            <i class="fas fa-search"></i>
            <span>Search</span>
        </button>
    </form>
</section>

<section class="news-grid-section">
    <?php if (empty($news)): ?>
        <div class="news-empty">
            <i class="fas fa-newspaper"></i>
            <p>No news found. Try another keyword.</p>
        </div>
    <?php else: ?>
        <div class="news-grid">
            <?php foreach ($news as $item): ?>
                <?php
                $thumbnailPath = resolve_thumbnail($item);
                $typeLabel = !empty($item['news_type']) ? str_replace('_', ' ', $item['news_type']) : 'Update';
                $descriptionSnippet = !empty($item['Description'])
                    ? mb_strimwidth(strip_tags($item['Description']), 0, 140, '...')
                    : 'Tap to read the full story.';
                $d = get_news_date($item);
                ?>
                <article class="news-card">
                    <a class="news-card-image"
                        href="/index.php?controller=news&action=trackClick&id=<?php echo $item['NewsID']; ?>">
                        <img src="<?php echo htmlspecialchars($thumbnailPath); ?>"
                            alt="<?php echo htmlspecialchars($item['Title']); ?>"
                            loading="lazy"
                            onerror="this.onerror=null;this.src='/assets/images/placeholder.png'">
                        <span class="news-card-tag"><?php echo htmlspecialchars(ucwords($typeLabel)); ?></span>
                        <?php if (!empty($item['end_date'])): ?>
                            <span class="news-card-countdown" data-end-date="<?php echo $item['end_date']; ?>">
                                <i class="far fa-clock"></i>
                                <span class="timer">--</span>
                            </span>
                        <?php endif; ?>
                    </a>
                    <div class="news-card-content">
                        <p class="news-card-meta">
                            <span><?php echo htmlspecialchars($d['fmt']); ?></span>
                            <?php if (!empty($item['promotion_name'])): ?>
                                · <span><?php echo htmlspecialchars($item['promotion_name']); ?></span>
                            <?php endif; ?>
                        </p>
                        <h3><?php echo htmlspecialchars($item['Title']); ?></h3>
                        <p><?php echo htmlspecialchars($descriptionSnippet); ?></p>
                        <div class="news-card-actions">
                            <a class="btn btn-outline"
                                href="/index.php?controller=news&action=trackClick&id=<?php echo $item['NewsID']; ?>">
                                Read Article
                            </a>
                            <a class="news-card-detail-link"
                                href="/index.php?controller=news&action=detail&id=<?php echo $item['NewsID']; ?>">
                                View blog view
                            </a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<?php if ($totalPages > 1): ?>
    <div class="news-pagination">
        <?php if ($page > 1): ?>
            <a class="news-pagination-link"
                href="/index.php?controller=news&action=index&search=<?php echo urlencode($search); ?>&page=<?php echo $page - 1; ?>">
                <i class="fas fa-arrow-left"></i> Previous
            </a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a class="news-pagination-link <?php echo $i === $page ? 'active' : ''; ?>"
                href="/index.php?controller=news&action=index&search=<?php echo urlencode($search); ?>&page=<?php echo $i; ?>">
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a class="news-pagination-link"
                href="/index.php?controller=news&action=index&search=<?php echo urlencode($search); ?>&page=<?php echo $page + 1; ?>">
                Next <i class="fas fa-arrow-right"></i>
            </a>
        <?php endif; ?>
    </div>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const countdownElements = document.querySelectorAll('.news-card-countdown');

        countdownElements.forEach(function(element) {
            const endDateAttr = element.getAttribute('data-end-date');
            if (!endDateAttr) return;

            const endDate = new Date(endDateAttr).getTime();
            const timerElement = element.querySelector('.timer');
            if (!timerElement) return;

            function updateCountdown() {
                const now = new Date().getTime();
                const distance = endDate - now;

                if (distance <= 0) {
                    timerElement.textContent = 'Expired';
                    element.classList.add('expired');
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

<?php require_once 'views/components/footer.php'; ?>