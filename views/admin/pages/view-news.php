<?php

if (session_status() !== PHP_SESSION_ACTIVE) session_start();
$uid  = (int)($_SESSION['user_id'] ?? 0);
$role = $_SESSION['role'] ?? '';
if ($uid <= 0 || $role !== 'admin') {
    header('Location: /index.php?controller=auth&action=login', true, 302);
    exit;
}

function e($s)
{
    return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
}

$thumbnailPath = null;
$thumbRaw = $news['Thumbnail'] ?? '';
if (!empty($thumbRaw)) {
    if (filter_var($thumbRaw, FILTER_VALIDATE_URL)) {
        $thumbnailPath = $thumbRaw;
    } elseif (file_exists($thumbRaw)) {
        $thumbnailPath = '/' . ltrim($thumbRaw, '/\\');
    } else {
        $thumbnailPath = '/' . ltrim($thumbRaw, '/\\');
    }
}

$contentHtml = $news['Content'] ?? '';
if (trim((string)$contentHtml) === '') {
    $contentHtml = '<p>Content will be updated soon.</p>';
}

$promoName = $news['PromotionName'] ?? ($news['promotion_name'] ?? null);
$startDate = $news['StartDate'] ?? ($news['start_date'] ?? null);
$endDate   = $news['EndDate'] ?? ($news['end_date'] ?? null);

?>
<style>
    .news-detail-page {
        padding: 40px 0 80px;
    }

    .news-detail-hero {
        max-width: 760px;
        margin: 0 auto 40px;
        text-align: center;
    }

    .news-detail-hero h1 {
        font-size: 36px;
        margin: 16px 0;
        color: #111;
    }

    .news-detail-meta {
        color: #777;
        font-size: 15px;
    }

    .news-detail-description {
        color: #555;
        line-height: 1.7;
        font-size: 18px;
    }

    .news-article {
        background: #fff;
        border-radius: 20px;
        padding: 32px;
        box-shadow: 0 20px 40px rgba(17, 17, 17, 0.05);
    }

    .news-article img {
        width: 100%;
        border-radius: 16px;
        margin-bottom: 24px;
    }

    .news-promo-card {
        background: rgba(255, 107, 107, 0.08);
        border: 1px solid rgba(255, 107, 107, 0.2);
        padding: 16px;
        border-radius: 12px;
        margin-bottom: 24px;
    }

    .news-rich-content h2,
    .news-rich-content h3 {
        margin-top: 32px;
        color: #111;
    }

    .news-rich-content p {
        color: #444;
        line-height: 1.8;
    }

    @media (max-width: 768px) {
        .news-detail-page {
            max-width: 100%;
            padding-inline: 0;
        }

        .news-article img {
            max-height: 220px;
        }

        .news-detail-hero h1 {
            font-size: 1.7rem;
        }
    }

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

<div class="mb-3">
    <div class="row align-items-center g-3">
        <div class="col-12 col-lg-6">
            <h1>View Article</h1>
        </div>
        <div class="col-12 col-lg-6">
            <div class="d-flex gap-2 justify-content-lg-end">
                <a href="/index.php?controller=adminNews&action=manage" class="btn btn-outline-secondary btn-animate-icon"><i class="ti ti-chevron-left"></i> Back</a>
                <a href="/index.php?controller=adminNews&action=editNews&id=<?= (int)$news['NewsID'] ?>" class="btn btn-outline-primary">
                    <i class="ti ti-edit me-1"></i>Edit
                </a>
                <a href="/index.php?controller=news&action=detail&id=<?= (int)$news['NewsID'] ?>" target="_blank" class="btn btn-primary">
                    View public
                </a>
            </div>
        </div>
    </div>
</div>

<div class="news-detail-page">
    <section class="news-detail-hero">
        <p class="news-detail-meta">
            By <?= e($news['AdminName'] ?? 'Editorial Team') ?>
            · <?= date('F d, Y', strtotime($news['CreatedAt'])) ?>
            <?php if (!empty($news['NewsType'])): ?>
                · Type: <?= e($news['NewsType']) ?>
            <?php endif; ?>
        </p>
        <h1><?= e($news['Title']) ?></h1>

        <?php if (!empty($news['Description'])): ?>
            <p class="news-detail-description">
                <?= e($news['Description']) ?>
            </p>
        <?php endif; ?>
    </section>

    <div class="news-detail-layout">
        <article class="news-article" id="news-article">
            <?php if ($thumbnailPath): ?>
                <img
                    src="<?= e($thumbnailPath) ?>"
                    alt="<?= e($news['Title']) ?>"
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
                <?= $contentHtml ?>
            </div>
        </article>
    </div>
</div>

<script>
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
</script>