<?php
$contentHtml = $news['Content'] ?? '';
$tocItems = [];

if (!empty(trim($contentHtml))) {
    libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    $dom->loadHTML('<?xml encoding="utf-8" ?>' . $contentHtml);
    libxml_clear_errors();

    $xpath    = new DOMXPath($dom);
    $headings = $xpath->query('//h1 | //h2 | //h3');

    $generatedIds = [];

    foreach ($headings as $heading) {
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

$thumbnailPath = null;
if (!empty($news['Thumbnail'])) {
    if (filter_var($news['Thumbnail'], FILTER_VALIDATE_URL)) {
        $thumbnailPath = $news['Thumbnail'];
    } elseif (file_exists($news['Thumbnail'])) {
        $thumbnailPath = '/' . ltrim($news['Thumbnail'], '/');
    } else {
        $thumbnailPath = '/' . ltrim($news['Thumbnail'], '/');
    }
}
?>

<div class="news-detail-page">
    <section class="news-detail-hero">
        <p class="news-detail-meta">
            By <?php echo htmlspecialchars($news['AdminName'] ?? 'Editorial Team'); ?>
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

            <?php if (!empty($promotions)): ?>
                <?php
                $validPromos = [];
                foreach ($promotions as $p) {
                    $pid = (int)($p['promotion_id'] ?? $p['PromotionID'] ?? 0);
                    if ($pid > 0) {
                        $validPromos[] = $p;
                    }
                }

                $totalPromos = count($validPromos);
                $maxDisplay  = 3;
                ?>

                <?php if ($totalPromos > 0): ?>
                    <section class="news-promos-inline">
                        <span class="news-promos-inline-label">
                            Active promotions:
                        </span>

                        <div class="news-promos-inline-list">
                            <?php for ($i = 0; $i < min($maxDisplay, $totalPromos); $i++): ?>
                                <?php
                                $p     = $validPromos[$i];
                                $pid   = (int)($p['promotion_id'] ?? $p['PromotionID'] ?? 0);
                                $pname = $p['promotion_name'] ?? $p['PromotionName'] ?? ('Promotion #' . $pid);
                                ?>
                                <a
                                    class="news-promo-pill"
                                    href="/index.php?controller=promotionalProducts&action=index&id=<?= $pid ?>">
                                    <span class="news-promo-pill-dot"></span>
                                    <span class="news-promo-pill-text">
                                        <?= htmlspecialchars($pname, ENT_QUOTES, 'UTF-8') ?>
                                    </span>
                                </a>
                            <?php endfor; ?>

                            <?php if ($totalPromos > $maxDisplay): ?>
                                <?php for ($i = $maxDisplay; $i < $totalPromos; $i++): ?>
                                    <?php
                                    $p     = $validPromos[$i];
                                    $pid   = (int)($p['promotion_id'] ?? $p['PromotionID'] ?? 0);
                                    $pname = $p['promotion_name'] ?? $p['PromotionName'] ?? ('Promotion #' . $pid);
                                    ?>
                                    <a
                                        class="news-promo-pill news-promo-pill-hidden"
                                        href="/index.php?controller=promotionalProducts&action=index&id=<?= $pid ?>">
                                        <span class="news-promo-pill-dot"></span>
                                        <span class="news-promo-pill-text">
                                            <?= htmlspecialchars($pname, ENT_QUOTES, 'UTF-8') ?>
                                        </span>
                                    </a>
                                <?php endfor; ?>

                                <button
                                    type="button"
                                    class="news-promo-pill news-promo-pill-toggle"
                                    data-state="collapsed"
                                    data-hidden-count="<?= $totalPromos - $maxDisplay ?>">
                                    +<?= $totalPromos - $maxDisplay ?> more
                                </button>
                            <?php endif; ?>
                        </div>
                    </section>
                <?php endif; ?>
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

<style>
    .news-promos-inline {
        margin-top: 12px;
        margin-bottom: 8px;
        padding: 8px 12px;
        border-radius: 999px;
        background: #f5f5f5;
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .news-promos-inline-label {
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #6b7280;
        white-space: nowrap;
    }

    .news-promos-inline-list {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .news-promo-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 999px;
        background: #ffffff;
        border: 1px solid #e5e5e5;
        font-size: 0.8rem;
        text-decoration: none;
        color: #111827;
        transition: background-color 0.15s ease, border-color 0.15s ease, box-shadow 0.15s ease, transform 0.12s ease;
        cursor: pointer;
    }

    .news-promo-pill-dot {
        width: 6px;
        height: 6px;
        border-radius: 999px;
        background: #2563eb;
    }

    .news-promo-pill-text {
        max-width: 180px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .news-promo-pill-hidden {
        display: none;
    }

    .news-promo-pill-toggle {
        background: #111827;
        color: #f9fafb;
        border-color: transparent;
        font-weight: 500;
    }

    .news-promo-pill:hover {
        background-color: #f9fafb;
        border-color: #d4d4d8;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.08);
        transform: translateY(-1px);
    }

    .news-promo-pill-toggle:hover {
        background-color: #020617;
    }

    @media (max-width: 768px) {
        .news-promos-inline {
            padding: 8px 10px;
            border-radius: 10px;
        }

        .news-promo-pill-text {
            max-width: 140px;
        }
    }
</style>

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


        const promoToggle = document.querySelector('.news-promo-pill-toggle');
        if (promoToggle) {
            const hiddenPills = document.querySelectorAll('.news-promo-pill-hidden');
            const hiddenCount = parseInt(promoToggle.getAttribute('data-hidden-count'), 10) || hiddenPills.length;

            const updateToggleText = () => {
                const state = promoToggle.getAttribute('data-state');
                if (state === 'expanded') {
                    promoToggle.textContent = 'Show less';
                } else {
                    promoToggle.textContent = `+${hiddenCount} more`;
                }
            };

            updateToggleText();

            promoToggle.addEventListener('click', function() {
                const currentState = this.getAttribute('data-state');
                const willExpand = currentState !== 'expanded';

                hiddenPills.forEach(function(pill) {
                    pill.style.display = willExpand ? 'inline-flex' : 'none';
                });

                this.setAttribute('data-state', willExpand ? 'expanded' : 'collapsed');
                updateToggleText();
            });
        }
    });
</script>