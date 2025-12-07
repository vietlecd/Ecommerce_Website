<?php if (isset($_SESSION['error']) && !empty($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
        <div class="d-flex">
            <div>
                <i class="ti ti-alert-circle me-2"></i>
            </div>
            <div>
                <?php echo htmlspecialchars($_SESSION['error']); ?>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['success']) && !empty($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        <div class="d-flex">
            <div>
                <i class="ti ti-check me-2"></i>
            </div>
            <div>
                <?php echo htmlspecialchars($_SESSION['success']); ?>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <?php if ($key === 'about'): ?>
                <a href="/index.php?controller=about&action=index" class="btn btn-outline-primary" target="_blank">
                    <i class="ti ti-eye me-1"></i>
                    View Page
                </a>
            <?php elseif ($key === 'qna'): ?>
                <a href="/index.php?controller=qna&action=index" class="btn btn-outline-primary" target="_blank">
                    <i class="ti ti-eye me-1"></i>
                    View Page
                </a>
            <?php endif; ?>
        </div>
    </div>
    <div class="card-body">
        <form method="post" action="/index.php?controller=adminContent&action=update">
            <input type="hidden" name="key" value="<?php echo htmlspecialchars($key); ?>">
            
            <div class="mb-3">
                <label for="content" class="form-label">
                    Page Content
                    <span class="text-danger">*</span>
                </label>
                <textarea class="form-control" 
                          id="content" 
                          name="content" 
                          rows="20" 
                          required><?php echo htmlspecialchars($htmlContent); ?></textarea>
                <div class="form-hint mt-2">
                    <i class="ti ti-info-circle me-1"></i>
                    Use the rich text editor below to format your content. HTML tags are supported.
                </div>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="ti ti-device-floppy me-1"></i>
                    Save Content
                </button>
                <a href="/index.php?controller=adminDashboard&action=dashboard" class="btn">
                    <i class="ti ti-x me-1"></i>
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    tinymce.init({
        selector: '#content',
        height: 500,
        menubar: true,
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
            'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
            'insertdatetime', 'media', 'table', 'help', 'wordcount'
        ],
        toolbar: 'undo redo | blocks | bold italic forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | code | help',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px; padding: 10px; }',
        branding: false,
        promotion: false,
        setup: function(editor) {
            editor.on('change', function() {
                editor.save();
            });
        }
    });
});
</script>
