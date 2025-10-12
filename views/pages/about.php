<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9">
            <h1 class="display-4 text-center text-dark mb-4"><?php echo htmlspecialchars($aboutContent['Title']); ?></h1>
            
            <div class="card shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <?php if ($aboutContent['Image']): ?>
                        <div class="text-center mb-4">
                            <img src="/<?php echo htmlspecialchars($aboutContent['Image']); ?>" 
                                 alt="About Us" 
                                 class="rounded img-fluid mx-auto d-block" 
                                 style="width: 100%; max-width: 400px; height: auto;">
                        </div>
                    <?php endif; ?>
                    
                    <div class="lh-lg text-secondary mb-3">
                        <?php echo nl2br(htmlspecialchars($aboutContent['Content'])); ?>
                    </div>
                    
                    <?php if (!empty($aboutContent['UpdatedAt'])): ?>
                        <div class="text-end text-muted small fst-italic mt-4">
                            Last updated: <?php echo date('F j, Y', strtotime($aboutContent['UpdatedAt'])); ?>
                            <?php if (!empty($aboutContent['UpdatedByName'])): ?>
                                by <?php echo htmlspecialchars($aboutContent['UpdatedByName']); ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
