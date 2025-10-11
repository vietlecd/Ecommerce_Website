<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">
            <h1 class="display-4 text-center text-dark mb-5">Frequently Asked Questions</h1>
            
            <div class="card shadow-sm">
                <?php if (!empty($qnaList)): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($qnaList as $qna): ?>
                            <div class="list-group-item p-4">
                                <div class="d-flex align-items-start mb-3">
                                    <i class="fas fa-question-circle text-primary me-3 mt-1 fs-5"></i>
                                    <h5 class="fw-bold text-dark mb-0"><?php echo htmlspecialchars($qna['Question']); ?></h5>
                                </div>
                                <div class="text-secondary lh-lg ps-5">
                                    <?php echo nl2br(htmlspecialchars($qna['Answer'])); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="card-body text-center py-5">
                        <i class="fas fa-inbox display-1 text-muted mb-4"></i>
                        <p class="text-muted">No questions available at the moment.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
