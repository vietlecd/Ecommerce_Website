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
</style>

<div class="page-header mb-3">
    <div class="row align-items-center g-3">
        <div class="col-12 col-lg-6">
            <h1 class="h3">View Article</h1>
        </div>
        <div class="col-12 col-lg-6">
            <div class="d-flex gap-2 justify-content-lg-end">
                <a href="/index.php?controller=adminNews&action=manage" class="btn btn-outline-secondary">← Back</a>
                <a href="/index.php?controller=adminNews&action=editNews&id=<?= (int)$news['NewsID'] ?>" class="btn btn-outline-primary">
                    <i class="fas fa-edit me-1"></i>Edit
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

            <?php if ($promoName || $startDate || $endDate): ?>
                <div class="news-promo-card">
                    <strong><?= e($promoName ?? 'Promotion') ?></strong>
                    <p>
                        <?php if ($startDate): ?>
                            Starts: <?= date('M d, Y', strtotime($startDate)) ?>
                        <?php endif; ?>
                        <?php if ($endDate): ?>
                            <?php if ($startDate): ?> · <?php endif; ?>
                            Ends: <?= date('M d, Y', strtotime($endDate)) ?>
                        <?php endif; ?>
                    </p>
                </div>
            <?php endif; ?>

            <div class="news-rich-content">
                <?= $contentHtml ?>
            </div>
        </article>
    </div>
</div>