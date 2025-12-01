<?php
// Chuẩn bị content + TOC từ Content HTML
$contentHtml = $news['Content'] ?? '';
$tocItems = [];

if (!empty(trim($contentHtml))) {
    libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    $dom->loadHTML('<?xml encoding="utf-8" ?>' . $contentHtml);
    libxml_clear_errors();

    $xpath    = new DOMXPath($dom);
    $headings = $xpath->query('//h2 | //h3');

    $generatedIds = [];

    foreach ($headings as $heading) {
        // Fix cho Intelephense + safety
        if (!$heading instanceof DOMElement) {
            continue;
        }

        $text = trim($heading->textContent);
        if ($text === '') {
            continue;
        }

        $slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $text));
        $slug = trim($slug, '-');
        if ($slug === '') {
            $slug = 'section';
        }

        $baseSlug = $slug;
        $counter  = 1;
        while (in_array($slug, $generatedIds, true)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        $generatedIds[] = $slug;

        $heading->setAttribute('id', $slug);
        $tocItems[] = [
            'id'    => $slug,
            'text'  => $text,
            'level' => $heading->nodeName,
        ];
    }

    $body = $dom->getElementsByTagName('body')->item(0);
    $contentHtml = '';
    if ($body) {
        foreach ($body->childNodes as $child) {
            $contentHtml .= $dom->saveHTML($child);
        }
    }

    if (trim($contentHtml) === '') {
        $contentHtml = nl2br(htmlspecialchars($news['Content'] ?? ''));
    }
} else {
    $contentHtml = nl2br(htmlspecialchars($news['Content'] ?? ''));
}

// Thumbnail (schema mới dùng 'thumbnail')
$thumbnailPath = null;
if (!empty($news['Thumbnail'])) {
    if (filter_var($news['Thumbnail'], FILTER_VALIDATE_URL)) {
        $thumbnailPath = $news['Thumbnail'];
    } elseif (file_exists($news['Thumbnail'])) {
        $thumbnailPath = '/' . ltrim($news['Thumbnail'], '/');
    } else {
        // fallback: cứ prepand slash cho path
        $thumbnailPath = '/' . ltrim($news['Thumbnail'], '/');
    }
}
?>

<div class="news-detail-page">
    <section class="news-detail-hero">
        <p class="news-detail-meta">
            By <?php echo htmlspecialchars($news['CreatedBy'] ?? 'Editorial Team'); ?>
            · <?php echo date('F d, Y', strtotime($news['CreatedAt'])); ?>
        </p>
        <h1><?php echo htmlspecialchars($news['Title']); ?></h1>

        <?php if (!empty($news['Description'])): ?>
            <p class="news-detail-description">
                <?php echo htmlspecialchars($news['Description']); ?>
            </p>
        <?php endif; ?>
    </section>

    <div class="news-detail-layout">
        <article class="news-article" id="news-article">
            <?php if ($thumbnailPath): ?>
                <img
                    src="<?php echo htmlspecialchars($thumbnailPath); ?>"
                    alt="<?php echo htmlspecialchars($news['Title']); ?>"
                    loading="lazy">
            <?php endif; ?>

            <?php if (!empty($news['promotion_name']) || !empty($news['start_date']) || !empty($news['end_date'])): ?>
                <div class="news-promo-card">
                    <strong><?php echo htmlspecialchars($news['PromotionName'] ?? 'Promotion'); ?></strong>
                    <p>
                        <?php if (!empty($news['StartDate'])): ?>
                            Starts: <?php echo date('M d, Y', strtotime($news['StartDate'])); ?>
                        <?php endif; ?>
                        <?php if (!empty($news['EndDate'])): ?>
                            <?php if (!empty($news['StartDate'])): ?> · <?php endif; ?>
                            Ends: <?php echo date('M d, Y', strtotime($news['EndDate'])); ?>
                        <?php endif; ?>
                    </p>
                </div>
            <?php endif; ?>

            <div class="news-rich-content">
                <?php echo !empty($contentHtml) ? $contentHtml : '<p>Content will be updated soon.</p>'; ?>
            </div>
        </article>

        <aside class="news-toc">
            <div class="news-toc-card">
                <h4>On this page</h4>
                <?php if (!empty($tocItems)): ?>
                    <ol class="news-toc-list">
                        <?php foreach ($tocItems as $entry): ?>
                            <li data-level="<?php echo htmlspecialchars($entry['level']); ?>">
                                <a href="#<?php echo htmlspecialchars($entry['id']); ?>">
                                    <?php echo htmlspecialchars($entry['text']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                <?php else: ?>
                    <p>No section headings detected.</p>
                <?php endif; ?>
            </div>
        </aside>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tocLinks = document.querySelectorAll('.news-toc-list a');

        tocLinks.forEach(function(link) {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                const target = document.getElementById(targetId);
                if (target) {
                    window.scrollTo({
                        top: target.getBoundingClientRect().top + window.pageYOffset - 100,
                        behavior: 'smooth'
                    });
                }
            });
        });

        const observerTargets = Array.from(tocLinks).map(function(link) {
            const target = document.getElementById(link.getAttribute('href').substring(1));
            return target;
        }).filter(Boolean);

        if (observerTargets.length) {
            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    const id = entry.target.getAttribute('id');
                    const activeLink = document.querySelector('.news-toc-list a[href="#' + id + '"]');
                    if (activeLink) {
                        activeLink.classList.toggle('active', entry.isIntersecting);
                    }
                });
            }, {
                rootMargin: '-60% 0px -35% 0px',
                threshold: 0
            });

            observerTargets.forEach(function(target) {
                observer.observe(target);
            });
        }
    });
</script>