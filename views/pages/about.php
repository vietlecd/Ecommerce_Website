<style>
    .about-page {
        max-width: 900px;
        margin: 40px auto;
        padding: 20px;
    }

    .about-page h1 {
        color: #333;
        font-size: 2.5em;
        margin-bottom: 20px;
        text-align: center;
    }

    .about-content {
        background: #fff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .about-image {
        width: 100%;
        max-width: 600px;
        height: auto;
        margin: 20px auto;
        display: block;
        border-radius: 8px;
    }

    .about-content p {
        line-height: 1.8;
        color: #555;
        margin-bottom: 15px;
        white-space: pre-line;
    }

    .about-updated {
        text-align: right;
        color: #999;
        font-size: 0.9em;
        margin-top: 20px;
        font-style: italic;
    }
</style>

<div class="about-page">
    <h1><?php echo htmlspecialchars($aboutContent['Title']); ?></h1>
    
    <div class="about-content">
        <?php if ($aboutContent['Image']): ?>
            <img src="/<?php echo htmlspecialchars($aboutContent['Image']); ?>" alt="About Us" class="about-image">
        <?php endif; ?>
        
        <p><?php echo nl2br(htmlspecialchars($aboutContent['Content'])); ?></p>
        
        <?php if ($aboutContent['LastUpdated']): ?>
            <div class="about-updated">
                Last updated: <?php echo date('F j, Y', strtotime($aboutContent['LastUpdated'])); ?>
                <?php if ($aboutContent['UpdatedByName']): ?>
                    by <?php echo htmlspecialchars($aboutContent['UpdatedByName']); ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
