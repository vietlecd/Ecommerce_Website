<section class="payment-result">
    <div class="result-container">
        <div class="result-icon failed">
            <i class="fas fa-times-circle"></i>
        </div>
        <h2>Payment Failed</h2>
        <p class="result-message"><?php echo htmlspecialchars($message ?? 'We couldn\'t process your payment. Please try again.'); ?></p>
        
        <?php if ($orderId): ?>
            <div class="order-info">
                <p>Order Number: <strong>#<?php echo htmlspecialchars($orderId); ?></strong></p>
                <p>This order is currently on hold. You can retry the payment.</p>
            </div>
        <?php endif; ?>
        
        <div class="result-actions">
            <a href="/index.php?controller=checkout&action=index" class="btn btn-primary">Try Again</a>
            <a href="/index.php?controller=home&action=index" class="btn btn-outline">Go to Homepage</a>
        </div>
    </div>
</section>

<style>
.payment-result {
    min-height: 60vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 2rem;
}

.result-container {
    text-align: center;
    max-width: 600px;
    background: white;
    padding: 3rem;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.result-icon {
    font-size: 5rem;
    margin-bottom: 1.5rem;
}

.result-icon.failed i {
    color: #ef4444;
}

.result-container h2 {
    color: #1f2937;
    margin-bottom: 1rem;
}

.result-message {
    color: #6b7280;
    font-size: 1.125rem;
    margin-bottom: 2rem;
}

.order-info {
    background: #fef2f2;
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 2rem;
    border-left: 4px solid #ef4444;
}

.order-info p {
    margin: 0.5rem 0;
    color: #991b1b;
}

.order-info strong {
    color: #7f1d1d;
    font-size: 1.25rem;
}

.result-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s;
}

.btn-primary {
    background: #6366f1;
    color: white;
}

.btn-primary:hover {
    background: #4f46e5;
}

.btn-outline {
    background: white;
    color: #6366f1;
    border: 2px solid #6366f1;
}

.btn-outline:hover {
    background: #eff6ff;
}
</style>

<?php require_once 'views/components/footer.php'; ?>
