<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Add New Q&A</h3>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/index.php?controller=adminDashboard&action=dashboard">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="/index.php?controller=adminQna&action=manage">Q&A</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Add New</li>
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
                <h4 class="card-title">Add Q&A Item</h4>
            </div>
            <div class="card-body">
                <form method="post" class="needs-validation" novalidate>
                    <div class="form-group mb-3">
                        <label for="question" class="form-label">Question <span class="text-danger">*</span></label>
                        <input type="text" 
                               class="form-control" 
                               id="question" 
                               name="question" 
                               placeholder="Enter the question" 
                               required
                               maxlength="500">
                        <div class="invalid-feedback">
                            Please enter a question.
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="answer" class="form-label">Answer <span class="text-danger">*</span></label>
                        <textarea class="form-control" 
                                  id="answer" 
                                  name="answer" 
                                  rows="6" 
                                  placeholder="Enter the answer" 
                                  required></textarea>
                        <div class="invalid-feedback">
                            Please enter an answer.
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="display_order" class="form-label">Display Order</label>
                        <input type="number" 
                               class="form-control" 
                               id="display_order" 
                               name="display_order" 
                               value="0" 
                               min="0">
                        <div class="form-text">Lower numbers appear first</div>
                    </div>

                    <div class="form-group mb-3">
                        <div class="form-check">
                            <input class="form-check-input" 
                                   type="checkbox" 
                                   name="is_active" 
                                   value="1" 
                                   id="is_active" 
                                   checked>
                            <label class="form-check-label" for="is_active">
                                Active (Display on Q&A page)
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Q&A
                        </button>
                        <a href="/index.php?controller=adminQna&action=manage" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
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
