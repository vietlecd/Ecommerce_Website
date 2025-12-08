<?php
$modalId   = 'promotionModal';
$actionUrl = '/index.php?controller=adminPromotion&action=create';
require 'views/admin/components/promotion-modal.php';
?>

<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">Article Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="news-detail-page pv-page">
                    <section class="news-detail-hero">
                        <p class="news-detail-meta">
                            By <span id="pvAuthor">Admin</span>
                            · <span id="pvDate"></span>
                        </p>
                        <h1 id="pvTitle"></h1>

                        <p id="pvDescBlock" class="news-detail-description" style="display:none;">
                            <span id="pvDesc"></span>
                        </p>
                    </section>

                    <div class="news-detail-layout">
                        <article class="news-article" id="pvArticle">
                            <div id="pvThumbWrap" style="display:none;">
                                <img id="pvThumb" alt="Thumbnail" loading="lazy">
                            </div>

                            <div id="pvPromoCard" class="news-promo-card" style="display:none;">
                                <strong id="pvPromoName"></strong>
                                <p id="pvPromoDates"></p>
                            </div>

                            <div id="pvContent" class="news-rich-content"></div>
                        </article>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close Preview</button>
            </div>
        </div>
    </div>
</div>

<style>
    #previewModal .news-detail-page {
        padding: 24px 0 40px;
    }

    #previewModal .news-detail-hero {
        max-width: 760px;
        margin: 0 auto 24px;
        text-align: center;
    }

    #previewModal .news-detail-hero h1 {
        font-size: 32px;
        margin: 12px 0;
        color: #111;
    }

    #previewModal .news-detail-meta {
        color: #777;
        font-size: 14px;
    }

    #previewModal .news-detail-description {
        color: #555;
        line-height: 1.7;
        font-size: 16px;
        margin: 0;
    }

    #previewModal .news-detail-layout {
        max-width: 900px;
        margin: 0 auto;
    }

    #previewModal .news-article {
        background: #fff;
        border-radius: 20px;
        padding: 24px 24px 32px;
        box-shadow: 0 20px 40px rgba(17, 17, 17, 0.05);
    }

    #previewModal .news-article img {
        width: 100%;
        border-radius: 16px;
        margin-bottom: 24px;
        display: block;
    }

    #previewModal .news-promo-card {
        background: rgba(255, 107, 107, 0.08);
        border: 1px solid rgba(255, 107, 107, 0.2);
        padding: 16px;
        border-radius: 12px;
        margin-bottom: 24px;
    }

    #previewModal .news-rich-content h2,
    #previewModal .news-rich-content h3 {
        margin-top: 32px;
        color: #111;
    }

    #previewModal .news-rich-content p {
        color: #444;
        line-height: 1.8;
        margin-bottom: 1rem;
    }

    #previewModal .news-rich-content img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        margin: 24px 0;
        display: block;
    }

    #previewModal .news-rich-content ul,
    #previewModal .news-rich-content ol {
        margin: 16px 0;
        padding-left: 24px;
    }

    #previewModal .news-rich-content li {
        margin-bottom: 8px;
    }

    #previewModal .news-rich-content a {
        color: #0d6efd;
        text-decoration: none;
        border-bottom: 1px solid transparent;
        transition: border-color .2s;
    }

    #previewModal .news-rich-content a:hover {
        border-bottom-color: #0d6efd;
    }

    /* Inline promo mention trong content (nếu em còn dùng) */
    #previewModal .news-rich-content a.promo-mention {
        display: inline-flex;
        align-items: center;
        gap: .25rem;
        padding: .1rem .4rem;
        border-radius: .5rem;
        background: #eef6ff;
        border: 1px solid #cfe2ff;
        color: #084298;
        white-space: nowrap;
    }

    #previewModal .news-rich-content a.promo-mention:hover {
        background: #e7f1ff;
        color: #06357a;
    }

    @media (max-width: 768px) {
        #previewModal .news-detail-page {
            padding-inline: 0;
        }

        #previewModal .news-detail-hero h1 {
            font-size: 1.7rem;
        }

        #previewModal .news-article {
            border-radius: 0;
            box-shadow: none;
        }

        #previewModal .news-article img {
            max-height: 220px;
            object-fit: cover;
        }
    }
</style>