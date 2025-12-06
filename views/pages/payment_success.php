<section class="payment-result">
    <div class="result-container">
        <div class="result-icon success">
            <i class="fas fa-check-circle"></i>
        </div>
        <h2>Payment Successful!</h2>
        <p class="result-message"><?php echo htmlspecialchars($message ?? 'Your payment has been processed successfully.'); ?></p>
        
        <?php if ($orderId): ?>
            <div class="order-info">
                <p>Order Number: <strong>#<?php echo htmlspecialchars($orderId); ?></strong></p>
                <p>You will receive a confirmation email shortly.</p>
            </div>
        <?php endif; ?>
        
        <div class="result-actions">
            <a href="/index.php?controller=home&action=index" class="btn btn-primary">Continue Shopping</a>
            <?php if ($orderId): ?>
                <a href="/index.php?controller=orderLookup&action=index" class="btn btn-outline">View Order</a>
            <?php endif; ?>
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

.result-icon.success i {
    color: #10b981;
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
    background: #f9fafb;
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 2rem;
}

.order-info p {
    margin: 0.5rem 0;
    color: #4b5563;
}

.order-info strong {
    color: #1f2937;
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
