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
                                 class="img-fluid rounded w-75 mw-100">
                        </div>
                    <?php endif; ?>
                    
                    <div class="lh-lg text-secondary mb-3">
                        <?php echo nl2br(htmlspecialchars($aboutContent['Content'])); ?>
                    </div>
                    
                    <?php if ($aboutContent['LastUpdated']): ?>
                        <div class="text-end text-muted small fst-italic mt-4">
                            Last updated: <?php echo date('F j, Y', strtotime($aboutContent['LastUpdated'])); ?>
                            <?php if ($aboutContent['UpdatedByName']): ?>
                                by <?php echo htmlspecialchars($aboutContent['UpdatedByName']); ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
