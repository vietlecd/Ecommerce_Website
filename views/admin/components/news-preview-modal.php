<div class="modal fade" id="promotionModal" tabindex="-1" aria-labelledby="promotionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="promotionCreateForm" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="promotionModalLabel">New Promotion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input name="name" type="text" class="form-control" required placeholder="Promotion name">
                </div>
                <div class="row g-2">
                    <div class="col">
                        <label class="form-label">Start date</label>
                        <input name="start_date" type="date" class="form-control">
                    </div>
                    <div class="col">
                        <label class="form-label">End date</label>
                        <input name="end_date" type="date" class="form-control">
                    </div>
                </div>
                <div class="mt-3">
                    <label class="form-label">Description (optional)</label>
                    <textarea name="description" class="form-control" rows="2" placeholder="Short note"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary">Create Promotion</button>
            </div>
        </form>
    </div>
</div>

<!-- Preview Modal -->
<div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="previewModalLabel">Article Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-0">
                <article class="pv-scope">
                    <div class="news-detail">
                        <div class="thumbnail-container" id="pvThumbWrap" style="display:none;">
                            <div class="news-thumbnail">
                                <img id="pvThumb" alt="Thumbnail">
                            </div>
                        </div>

                        <h1 id="pvTitle" class="pv-title"></h1>

                        <div class="news-meta">
                            <p><strong id="pvAuthor">Admin</strong></p>
                            <span class="separator">•</span>
                            <p id="pvDate"></p>
                        </div>

                        <div id="pvDescBlock" class="news-description" style="display:none;">
                            <span id="pvDesc"></span>
                        </div>

                        <div id="pvContent" class="news-content"></div>
                    </div>
                </article>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close Preview</button>
            </div>
        </div>
    </div>
</div>

<style>
    /* ---- Preview styles ---- */
    #previewModal .pv-scope .news-detail {
        margin: 0 auto;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
    }

    #previewModal .pv-scope .news-detail h1 {
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1.2;
        margin: 0 16px 20px;
        color: #211922;
        letter-spacing: -.5px;
    }

    #previewModal .pv-scope .news-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 0 16px 32px;
        padding-bottom: 24px;
        border-bottom: 1px solid #e9e9e9;
    }

    #previewModal .pv-scope .news-meta p {
        margin: 0;
        color: #767676;
        font-size: .875rem;
    }

    #previewModal .pv-scope .news-meta .separator {
        color: #e9e9e9;
    }

    #previewModal .pv-scope .news-thumbnail {
        width: 100%;
        margin-top: -32px;
        margin-bottom: 32px;
        overflow: hidden;
    }

    #previewModal .pv-scope .news-thumbnail img {
        width: 100%;
        height: 400px;
        object-fit: cover;
        object-position: center;
        display: block;
        overflow-clip-margin: content-box;
        overflow: clip;
    }

    #previewModal .pv-scope .news-description {
        font-size: 1.125rem;
        line-height: 1.6;
        color: #333;
        margin: 0 16px 32px;
        padding: 20px;
        background: #f7f7f7;
        border-radius: 12px;
        border-left: 4px solid #e60023;
    }

    #previewModal .pv-scope .news-content {
        font-size: 1rem;
        line-height: 1.8;
        color: #211922;
        margin: 0 16px 32px;
    }

    #previewModal .pv-scope .news-content p {
        margin-bottom: 20px;
    }

    #previewModal .pv-scope .news-content img {
        max-width: 100%;
        height: auto;
        border-radius: 12px;
        margin: 24px 0;
        display: block;
    }

    #previewModal .pv-scope .news-content strong,
    #previewModal .pv-scope .news-content b {
        font-weight: 600;
        color: #211922;
    }

    #previewModal .pv-scope .news-content h2 {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 32px 0 16px;
        color: #211922;
    }

    #previewModal .pv-scope .news-content h3 {
        font-size: 1.375rem;
        font-weight: 600;
        margin: 24px 0 12px;
        color: #211922;
    }

    #previewModal .pv-scope .news-content ul,
    #previewModal .pv-scope .news-content ol {
        margin: 16px 0;
        padding-left: 24px;
    }

    #previewModal .pv-scope .news-content li {
        margin-bottom: 8px;
    }

    #previewModal .pv-scope .news-content a {
        color: #e60023;
        text-decoration: none;
        border-bottom: 1px solid transparent;
        transition: border-color .2s;
    }

    #previewModal .pv-scope .news-content a:hover {
        border-bottom-color: #e60023;
    }

    #previewModal .pv-scope .news-content blockquote {
        border-left: 3px solid #e60023;
        padding-left: 20px;
        margin: 24px 0;
        color: #5f5f5f;
        font-style: italic;
    }

    #previewModal .pv-scope .news-content a.promo-mention {
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

    #previewModal .pv-scope .news-content a.promo-mention:hover {
        background: #e7f1ff;
        color: #06357a;
    }

    @media (max-width:480px) {
        #previewModal .pv-scope .news-thumbnail img {
            height: 200px;
        }

        #previewModal .pv-scope .news-detail h1 {
            font-size: 1.875rem;
        }
    }
</style>