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
                                $thumb = '/assets/images/placeholder.png';
                                if (!empty($item['Thumbnail'])) {
                                    if (filter_var($item['Thumbnail'], FILTER_VALIDATE_URL)) {
                                        $thumb = $item['Thumbnail'];
                                    } elseif (file_exists($item['Thumbnail'])) {
                                        $thumb = '/' . ltrim($item['Thumbnail'], '/');
                                    }
                                }
                                ?>
                                <li>
                                    <a href="/index.php?controller=news&action=detail&id=<?php echo $item['NewsID']; ?>">
                                        <img src="<?php echo htmlspecialchars($thumb); ?>" alt="<?php echo htmlspecialchars($item['Title']); ?>" loading="lazy">
                                        <div>
                                            <span><?php echo date('M d, Y', strtotime($item['CreatedAt'])); ?></span>
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
                                $thumb = '/assets/images/placeholder.png';
                                if (!empty($item['Thumbnail'])) {
                                    if (filter_var($item['Thumbnail'], FILTER_VALIDATE_URL)) {
                                        $thumb = $item['Thumbnail'];
                                    } elseif (file_exists($item['Thumbnail'])) {
                                        $thumb = '/' . ltrim($item['Thumbnail'], '/');
                                    }
                                }
                                ?>
                                <li>
                                    <a href="/index.php?controller=news&action=detail&id=<?php echo $item['NewsID']; ?>">
                                        <img src="<?php echo htmlspecialchars($thumb); ?>" alt="<?php echo htmlspecialchars($item['Title']); ?>" loading="lazy">
                                        <div>
                                            <span><?php echo !empty($item['clicks']) ? number_format($item['clicks']) . ' views' : 'New'; ?></span>
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
                $thumbnailPath = '/assets/images/placeholder.png';
                if (!empty($item['Thumbnail'])) {
                    if (filter_var($item['Thumbnail'], FILTER_VALIDATE_URL)) {
                        $thumbnailPath = $item['Thumbnail'];
                    } elseif (file_exists($item['Thumbnail'])) {
                        $thumbnailPath = '/' . ltrim($item['Thumbnail'], '/');
                    }
                }
                $typeLabel = !empty($item['news_type']) ? str_replace('_', ' ', $item['news_type']) : 'Update';
                $descriptionSnippet = !empty($item['Description'])
                    ? mb_strimwidth(strip_tags($item['Description']), 0, 140, '...')
                    : 'Tap to read the full story.';
                ?>
                <article class="news-card">
                    <a class="news-card-image" href="/index.php?controller=news&action=trackClick&id=<?php echo $item['NewsID']; ?>">
                        <img src="<?php echo htmlspecialchars($thumbnailPath); ?>" alt="<?php echo htmlspecialchars($item['Title']); ?>" loading="lazy">
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
                            <span><?php echo date('M d, Y', strtotime($item['CreatedAt'])); ?></span>
                            <?php if (!empty($item['PromotionName'])): ?>
                                · <span><?php echo htmlspecialchars($item['PromotionName']); ?></span>
                            <?php endif; ?>
                        </p>
                        <h3><?php echo htmlspecialchars($item['Title']); ?></h3>
                        <p><?php echo htmlspecialchars($descriptionSnippet); ?></p>
                        <div class="news-card-actions">
                            <a class="btn btn-outline" href="/index.php?controller=news&action=trackClick&id=<?php echo $item['NewsID']; ?>">
                                Read Article
                            </a>
                            <a class="news-card-detail-link" href="/index.php?controller=news&action=detail&id=<?php echo $item['NewsID']; ?>">
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
            <a class="news-pagination-link" href="/index.php?controller=news&action=index&search=<?php echo urlencode($search); ?>&page=<?php echo $page - 1; ?>">
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
            <a class="news-pagination-link" href="/index.php?controller=news&action=index&search=<?php echo urlencode($search); ?>&page=<?php echo $page + 1; ?>">
                Next <i class="fas fa-arrow-right"></i>
            </a>
        <?php endif; ?>
    </div>
<?php endif; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const countdownElements = document.querySelectorAll('.news-card-countdown');

        countdownElements.forEach(function(element) {
            const endDate = new Date(element.getAttribute('data-end-date')).getTime();
            const timerElement = element.querySelector('.timer');

            if (!timerElement) {
                return;
            }

            function updateCountdown() {
                const now = new Date().getTime();
                const distance = endDate - now;

                if (distance < 0) {
                    timerElement.innerHTML = 'Expired';
                    element.classList.add('expired');
                    return;
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));

                timerElement.innerHTML = `${days}d ${hours}h left`;
            }

            updateCountdown();
            setInterval(updateCountdown, 60000);
        });
    });
</script>

<?php require_once 'views/components/footer.php'; ?>