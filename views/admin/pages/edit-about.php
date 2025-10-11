<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit About Page Content</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/index.php?controller=adminDashboard&action=dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">About Page</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger alert-dismissible show fade">
            <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if (!empty($success)): ?>
        <div class="alert alert-success alert-dismissible show fade">
            <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="card-title">About Page Content</h4>
                    <a href="/index.php?controller=about&action=index" class="btn btn-sm btn-outline-primary" target="_blank">
                        <i class="fas fa-eye"></i> View Page
                    </a>
                </div>
            </div>
            <div class="card-body">
                <form method="post" enctype="multipart/form-data" class="needs-validation" novalidate>
                    <div class="form-group mb-3">
                        <label for="title" class="form-label">Page Title <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control" 
                               id="title" 
                               name="title" 
                               value="<?php echo htmlspecialchars($aboutContent['Title']); ?>" 
                               required
                               maxlength="200">
                        <div class="invalid-feedback">
                            Please enter a page title.
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="content" class="form-label">Content <span class="text-danger">*</span></label>
                        <textarea class="form-control" 
                                  id="content" 
                                  name="content" 
                                  rows="15" 
                                  required><?php echo htmlspecialchars($aboutContent['Content']); ?></textarea>
                        <div class="invalid-feedback">
                            Please enter content for the About page.
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="image" class="form-label">Image (optional)</label>
                        <?php if ($aboutContent['Image']): ?>
                            <div class="mb-3">
                                <img src="/<?php echo htmlspecialchars($aboutContent['Image']); ?>" 
                                     alt="Current Image" 
                                     class="img-thumbnail w-50 mw-100">
                                <p class="text-muted small mt-2">Current image - Upload a new image to replace it</p>
                            </div>
                        <?php endif; ?>
                        <input type="file" 
                               class="form-control" 
                               id="image" 
                               name="image" 
                               accept="image/jpeg,image/png,image/jpg,image/gif">
                        <div class="form-text">Accepted formats: JPG, JPEG, PNG, GIF. Max size: 2MB</div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Content
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<script>
// Bootstrap 5 form validation
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
