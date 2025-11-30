<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit <?php echo htmlspecialchars(ucfirst($key)); ?> Content</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/index.php?controller=adminDashboard&action=dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars(ucfirst($key)); ?> Content</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <?php if (isset($_SESSION['error']) && !empty($_SESSION['error'])): ?>
        <div class="alert alert-danger alert-dismissible show fade">
            <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($_SESSION['error']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success']) && !empty($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible show fade">
            <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($_SESSION['success']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title"><?php echo htmlspecialchars(ucfirst($key)); ?> Page Content</h4>
                    <?php if ($key === 'about'): ?>
                        <a href="/index.php?controller=about&action=index" class="btn btn-sm btn-outline-primary" target="_blank">
                            <i class="fas fa-eye"></i> View Page
                        </a>
                    <?php elseif ($key === 'qna'): ?>
                        <a href="/index.php?controller=qna&action=index" class="btn btn-sm btn-outline-primary" target="_blank">
                            <i class="fas fa-eye"></i> View Page
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <form method="post" action="/index.php?controller=adminContent&action=update" class="needs-validation" novalidate>
                    <input type="hidden" name="key" value="<?php echo htmlspecialchars($key); ?>">
                    
                    <div class="form-group mb-3">
                        <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
                        <textarea class="form-control" 
                                  id="content" 
                                  name="content" 
                                  rows="20" 
                                  required><?php echo htmlspecialchars($htmlContent); ?></textarea>
                        <div class="invalid-feedback">
                            Please enter content for the <?php echo htmlspecialchars(ucfirst($key)); ?> page.
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Content
                        </button>
                        <a href="/index.php?controller=adminDashboard&action=dashboard" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    tinymce.init({
        selector: '#content',
        height: 500,
        menubar: false,
        plugins: [
            'anchor', 'autolink', 'charmap', 'codesample', 'emoticons', 'link', 'lists', 'media', 'searchreplace', 'table', 'visualblocks', 'wordcount',
            'checklist', 'mediaembed', 'casechange', 'formatpainter', 'pageembed', 'a11ychecker', 'tinymcespellchecker', 'permanentpen', 'powerpaste', 'advtable', 'advcode', 'advtemplate', 'ai', 'uploadcare', 'mentions', 'tinycomments', 'tableofcontents', 'footnotes', 'mergetags', 'autocorrect', 'typography', 'inlinecss', 'markdown', 'importword', 'exportword', 'exportpdf'
        ],
        toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link media table mergetags | addcomment showcomments | spellcheckdialog a11ycheck typography uploadcare | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
        branding: false,
        promotion: false,
        tinycomments_mode: 'embedded',
        tinycomments_author: 'Admin',
        mergetags_list: [
            { value: 'First.Name', title: 'First Name' },
            { value: 'Email', title: 'Email' },
        ],
        ai_request: (request, respondWith) => respondWith.string(() => Promise.reject('See docs to implement AI Assistant')),
        uploadcare_public_key: 'c15f8e35b0f68c8a7258',
        setup: function(editor) {
            editor.on('change', function() {
                editor.save();
            });
        }
    });
});

(function () {
    'use strict'
    var forms = document.querySelectorAll('.needs-validation')
    Array.prototype.slice.call(forms).forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
                event.preventDefault()
                event.stopPropagation()
            }
            form.classList.add('was-validated')
        }, false)
    })
})()
</script>

