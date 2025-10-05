<style>
    .qna-page {
        max-width: 900px;
        margin: 40px auto;
        padding: 20px;
    }

    .qna-page h1 {
        color: #333;
        font-size: 2.5em;
        margin-bottom: 30px;
        text-align: center;
    }

    .qna-list {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .qna-item {
        padding: 25px;
        border-bottom: 1px solid #eee;
    }

    .qna-item:last-child {
        border-bottom: none;
    }

    .qna-question {
        font-size: 1.2em;
        font-weight: bold;
        color: #2c3e50;
        margin-bottom: 15px;
        display: flex;
        align-items: flex-start;
    }

    .qna-question i {
        color: #3498db;
        margin-right: 10px;
        margin-top: 3px;
    }

    .qna-answer {
        color: #555;
        line-height: 1.8;
        padding-left: 30px;
    }

    .qna-empty {
        text-align: center;
        padding: 40px;
        color: #999;
    }
</style>

<div class="qna-page">
    <h1>Frequently Asked Questions</h1>
    
    <div class="qna-list">
        <?php if (!empty($qnaList)): ?>
            <?php foreach ($qnaList as $qna): ?>
                <div class="qna-item">
                    <div class="qna-question">
                        <i class="fas fa-question-circle"></i>
                        <span><?php echo htmlspecialchars($qna['Question']); ?></span>
                    </div>
                    <div class="qna-answer">
                        <?php echo nl2br(htmlspecialchars($qna['Answer'])); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="qna-empty">
                <i class="fas fa-inbox" style="font-size: 3em; color: #ddd; margin-bottom: 20px;"></i>
                <p>No questions available at the moment.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
