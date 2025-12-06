<?php
$formatCurrency = function ($value) {
    return '$' . number_format($value, 2);
};
?>

<section class="payment-page">
    <div class="section-title payment-title">
        <p class="section-eyebrow">Payment</p>
        <h2>Complete Your Payment</h2>
        <p class="section-subtitle">Scan the QR code or use the payment information below to complete your order.</p>
    </div>

    <div class="payment-container">
        <div class="payment-info-card">
            <div class="payment-card-header">
                <h3>Order #<?php echo htmlspecialchars($orderId); ?></h3>
                <span class="payment-status">Pending Payment</span>
            </div>

            <?php if ($paymentLink): ?>
                <div class="payment-qr-section">
                    <div class="qr-code-container">
                        <?php if (isset($paymentLink['qrCode'])): ?>
                            <img src="<?php echo htmlspecialchars($paymentLink['qrCode']); ?>" alt="QR Code" class="qr-code-image">
                        <?php else: ?>
                            <div class="qr-code-placeholder">
                                <i class="fas fa-qrcode"></i>
                                <p>QR Code will appear here</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="payment-instructions">
                        <h4>Scan QR Code to Pay</h4>
                        <ol>
                            <li>Open your banking app</li>
                            <li>Select "Scan QR Code" or "Transfer"</li>
                            <li>Scan the QR code above</li>
                            <li>Confirm the payment</li>
                        </ol>
                    </div>
                </div>

                <div class="payment-divider">
                    <span>OR</span>
                </div>

                <div class="payment-details-section">
                    <h4>Manual Transfer Information</h4>
                    <div class="payment-detail-row">
                        <span class="detail-label">Bank:</span>
                        <strong class="detail-value">PayOS Bank</strong>
                    </div>
                    <div class="payment-detail-row">
                        <span class="detail-label">Account Number:</span>
                        <strong class="detail-value"><?php echo htmlspecialchars($paymentLink['accountNumber'] ?? 'See QR Code'); ?></strong>
                    </div>
                    <div class="payment-detail-row">
                        <span class="detail-label">Account Name:</span>
                        <strong class="detail-value"><?php echo htmlspecialchars($paymentLink['accountName'] ?? 'PayOS Merchant'); ?></strong>
                    </div>
                    <div class="payment-detail-row">
                        <span class="detail-label">Amount:</span>
                        <strong class="detail-value amount-highlight"><?php echo number_format($paymentLink['amount'] ?? 0); ?> VND</strong>
                    </div>
                    <div class="payment-detail-row">
                        <span class="detail-label">Transfer Content:</span>
                        <strong class="detail-value transfer-content"><?php echo htmlspecialchars($paymentLink['description'] ?? 'Payment for order #' . $orderId); ?></strong>
                    </div>
                </div>

                <?php if (isset($paymentLink['checkoutUrl'])): ?>
                    <div class="payment-actions">
                        <a href="<?php echo htmlspecialchars($paymentLink['checkoutUrl']); ?>" class="btn btn-primary btn-full" target="_blank">
                            <i class="fas fa-external-link-alt"></i> Pay Now with PayOS
                        </a>
                    </div>
                <?php endif; ?>

                <div class="payment-notice">
                    <i class="fas fa-info-circle"></i>
                    <p>This payment link will expire in 15 minutes. Please complete your payment before expiration.</p>
                </div>

                <div class="payment-footer">
                    <p>Having trouble? <a href="/index.php?controller=checkout&action=index">Return to checkout</a> or <a href="/index.php?controller=home&action=index">Go to homepage</a></p>
                </div>
            <?php else: ?>
                <div class="payment-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <h3>Payment Link Not Available</h3>
                    <p>We couldn't load the payment information. Please try again.</p>
                    <a href="/index.php?controller=checkout&action=index" class="btn btn-primary">Return to Checkout</a>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($orderDetails): ?>
            <div class="payment-summary-card">
                <h3>Order Summary</h3>
                <div class="summary-items">
                    <?php foreach ($orderDetails['items'] as $item): ?>
                        <div class="summary-item">
                            <div class="item-info">
                                <span class="item-name"><?php echo htmlspecialchars($item['product_name']); ?></span>
                            </div>
                            <span class="item-price"><?php echo $formatCurrency($item['Price']); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="summary-total">
                    <span>Total:</span>
                    <strong><?php echo $formatCurrency($orderDetails['Total_price']); ?></strong>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
.payment-page {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

.payment-title {
    text-align: center;
    margin-bottom: 3rem;
}

.section-eyebrow {
    color: #6366f1;
    font-weight: 600;
    text-transform: uppercase;
    font-size: 0.875rem;
    letter-spacing: 0.05em;
    margin-bottom: 0.5rem;
}

.payment-container {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
}

@media (max-width: 768px) {
    .payment-container {
        grid-template-columns: 1fr;
    }
}

.payment-info-card,
.payment-summary-card {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.payment-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e5e7eb;
}

.payment-status {
    background: #fef3c7;
    color: #92400e;
    padding: 0.5rem 1rem;
    border-radius: 9999px;
    font-size: 0.875rem;
    font-weight: 600;
}

.payment-qr-section {
    display: flex;
    gap: 2rem;
    margin-bottom: 2rem;
}

@media (max-width: 768px) {
    .payment-qr-section {
        flex-direction: column;
    }
}

.qr-code-container {
    flex: 0 0 250px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.qr-code-image {
    width: 100%;
    max-width: 250px;
    height: auto;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    padding: 1rem;
    background: white;
}

.qr-code-placeholder {
    width: 250px;
    height: 250px;
    border: 2px dashed #d1d5db;
    border-radius: 8px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    color: #9ca3af;
}

.qr-code-placeholder i {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.payment-instructions {
    flex: 1;
}

.payment-instructions h4 {
    margin-bottom: 1rem;
    color: #1f2937;
}

.payment-instructions ol {
    padding-left: 1.5rem;
    line-height: 2;
}

.payment-divider {
    text-align: center;
    margin: 2rem 0;
    position: relative;
}

.payment-divider::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: #e5e7eb;
}

.payment-divider span {
    background: white;
    padding: 0 1rem;
    position: relative;
    color: #9ca3af;
    font-weight: 600;
}

.payment-details-section {
    background: #f9fafb;
    padding: 1.5rem;
    border-radius: 8px;
    margin-bottom: 2rem;
}

.payment-details-section h4 {
    margin-bottom: 1rem;
    color: #1f2937;
}

.payment-detail-row {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid #e5e7eb;
}

.payment-detail-row:last-child {
    border-bottom: none;
}

.detail-label {
    color: #6b7280;
}

.detail-value {
    color: #1f2937;
    text-align: right;
}

.amount-highlight {
    color: #dc2626;
    font-size: 1.25rem;
}

.transfer-content {
    font-family: monospace;
    background: #fff;
    padding: 0.5rem;
    border-radius: 4px;
    max-width: 60%;
    word-break: break-all;
}

.payment-actions {
    margin-bottom: 1.5rem;
}

.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
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

.btn-full {
    width: 100%;
}

.payment-notice {
    display: flex;
    gap: 1rem;
    padding: 1rem;
    background: #eff6ff;
    border-left: 4px solid #3b82f6;
    border-radius: 4px;
    margin-bottom: 1.5rem;
}

.payment-notice i {
    color: #3b82f6;
    font-size: 1.25rem;
}

.payment-notice p {
    margin: 0;
    color: #1e40af;
}

.payment-footer {
    text-align: center;
    padding-top: 1.5rem;
    border-top: 1px solid #e5e7eb;
}

.payment-footer a {
    color: #6366f1;
    text-decoration: none;
    font-weight: 600;
}

.payment-footer a:hover {
    text-decoration: underline;
}

.payment-error {
    text-align: center;
    padding: 3rem;
}

.payment-error i {
    font-size: 4rem;
    color: #ef4444;
    margin-bottom: 1rem;
}

.payment-error h3 {
    margin-bottom: 1rem;
    color: #1f2937;
}

.payment-summary-card h3 {
    margin-bottom: 1.5rem;
    color: #1f2937;
}

.summary-items {
    margin-bottom: 1.5rem;
}

.summary-item {
    display: flex;
    justify-content: space-between;
    padding: 0.75rem 0;
    border-bottom: 1px solid #e5e7eb;
}

.summary-item:last-child {
    border-bottom: none;
}

.item-name {
    color: #4b5563;
}

.item-price {
    font-weight: 600;
    color: #1f2937;
}

.summary-total {
    display: flex;
    justify-content: space-between;
    padding-top: 1rem;
    border-top: 2px solid #e5e7eb;
    font-size: 1.25rem;
}

.summary-total strong {
    color: #dc2626;
}
</style>

<script>
// Check payment status periodically
(function() {
    var orderId = <?php echo json_encode($orderId); ?>;
    var checkCount = 0;
    var maxChecks = 90; // Check for 15 minutes (every 10 seconds)
    
    function checkPaymentStatus() {
        checkCount++;
        if (checkCount > maxChecks) {
            console.log('Payment check timeout');
            return;
        }
        
        fetch('/index.php?controller=checkout&action=checkPaymentStatus&order_id=' + orderId)
            .then(response => response.json())
            .then(data => {
                if (data.status === 'paid') {
                    window.location.href = '/index.php?controller=checkout&action=success&order_id=' + orderId;
                } else if (data.status === 'cancelled' || data.status === 'expired') {
                    window.location.href = '/index.php?controller=checkout&action=failed&order_id=' + orderId;
                }
            })
            .catch(error => {
                console.error('Error checking payment status:', error);
            });
    }
    
    // Check every 10 seconds
    setInterval(checkPaymentStatus, 10000);
})();
</script>

<?php require_once 'views/components/footer.php'; ?>
