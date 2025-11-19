<?php
$formatCurrency = function ($value) {
    return '$' . number_format($value, 2);
};
?>

<section class="order-tracking-page">
    <div class="section-title tracking-title">
        <p class="section-eyebrow">Order tracking</p>
        <h2>Track your order</h2>
        <p class="section-subtitle">Enter your tracking ID to see real-time order status and delivery updates.</p>
    </div>

    <div class="tracking-container">
        <div class="tracking-form-card">
            <form method="post" action="" class="tracking-form">
                <div class="tracking-form-field">
                    <label for="tracking_id">Tracking ID</label>
                    <input 
                        type="text" 
                        id="tracking_id" 
                        name="tracking_id" 
                        placeholder="Enter your tracking ID (e.g., A1B2C3D4E5F6)"
                        value="<?php echo isset($_POST['tracking_id']) ? htmlspecialchars($_POST['tracking_id']) : ''; ?>"
                        required
                        autofocus
                    >
                </div>
                <button type="submit" name="track_order" class="btn btn-full">Track Order</button>
            </form>

            <?php if (!empty($error)): ?>
                <div class="tracking-alert tracking-alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?php echo htmlspecialchars($error); ?></span>
                </div>
            <?php endif; ?>
        </div>

        <?php if ($order && $trackingStatus): ?>
            <div class="tracking-result">
                <div class="tracking-header-card">
                    <div class="tracking-header-info">
                        <div>
                            <p class="tracking-label">Tracking ID</p>
                            <h3 class="tracking-id-display"><?php echo htmlspecialchars($order['tracking_id']); ?></h3>
                        </div>
                        <div>
                            <p class="tracking-label">Order Date</p>
                            <p class="tracking-value"><?php echo date('M d, Y', strtotime($order['Date'])); ?></p>
                        </div>
                        <div>
                            <p class="tracking-label">Status</p>
                            <p class="tracking-status-badge tracking-status-<?php echo strtolower(str_replace(' ', '-', $trackingStatus['current'])); ?>">
                                <?php echo htmlspecialchars($trackingStatus['current']); ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="tracking-progress-card">
                    <div class="tracking-progress-header">
                        <h3>Order Progress</h3>
                        <div class="tracking-progress-bar-wrapper">
                            <div class="tracking-progress-bar" style="width: <?php echo $trackingStatus['progress']; ?>%"></div>
                        </div>
                        <p class="tracking-progress-text"><?php echo $trackingStatus['progress']; ?>% Complete</p>
                    </div>

                    <div class="tracking-steps">
                        <?php foreach ($trackingStatus['steps'] as $index => $step): ?>
                            <div class="tracking-step <?php echo $step['completed'] ? 'completed' : ''; ?>">
                                <div class="tracking-step-icon">
                                    <?php if ($step['completed']): ?>
                                        <i class="fas fa-check-circle"></i>
                                    <?php else: ?>
                                        <i class="far fa-circle"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="tracking-step-content">
                                    <h4 class="tracking-step-name"><?php echo htmlspecialchars($step['name']); ?></h4>
                                    <p class="tracking-step-description"><?php echo htmlspecialchars($step['description']); ?></p>
                                    <?php if ($step['date']): ?>
                                        <p class="tracking-step-date"><?php echo htmlspecialchars($step['date']); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="tracking-details-grid">
                    <div class="tracking-detail-card">
                        <div class="tracking-detail-header">
                            <i class="fas fa-map-marker-alt"></i>
                            <h4>Current Location</h4>
                        </div>
                        <p class="tracking-detail-value"><?php echo htmlspecialchars($trackingStatus['location']); ?></p>
                        <?php if (isset($trackingStatus['last_update'])): ?>
                            <p class="tracking-detail-note"><?php echo htmlspecialchars($trackingStatus['last_update']); ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="tracking-detail-card">
                        <div class="tracking-detail-header">
                            <i class="fas fa-truck"></i>
                            <h4>Estimated Delivery</h4>
                        </div>
                        <p class="tracking-detail-value"><?php echo htmlspecialchars($trackingStatus['estimated_delivery']); ?></p>
                        <?php if (isset($trackingStatus['delivery_date'])): ?>
                            <p class="tracking-detail-note">
                                Delivered on <?php echo date('M d, Y', strtotime($trackingStatus['delivery_date'])); ?>
                                <?php if (isset($trackingStatus['delivery_time'])): ?>
                                    (<?php echo htmlspecialchars($trackingStatus['delivery_time']); ?>)
                                <?php endif; ?>
                            </p>
                        <?php endif; ?>
                    </div>

                    <?php if (isset($trackingStatus['tracking_number'])): ?>
                        <div class="tracking-detail-card">
                            <div class="tracking-detail-header">
                                <i class="fas fa-barcode"></i>
                                <h4>Shipping Number</h4>
                            </div>
                            <p class="tracking-detail-value tracking-number"><?php echo htmlspecialchars($trackingStatus['tracking_number']); ?></p>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="tracking-order-details-card">
                    <div class="tracking-order-details-header">
                        <h3>Order Details</h3>
                    </div>
                    <div class="tracking-order-info">
                        <div class="tracking-order-row">
                            <span>Customer</span>
                            <strong><?php echo htmlspecialchars($order['customer_name']); ?></strong>
                        </div>
                        <div class="tracking-order-row">
                            <span>Email</span>
                            <strong><?php echo htmlspecialchars($order['email']); ?></strong>
                        </div>
                        <?php if (!empty($order['payment_method'])): ?>
                            <div class="tracking-order-row">
                                <span>Payment Method</span>
                                <strong class="tracking-payment-method"><?php echo htmlspecialchars($order['payment_method']); ?></strong>
                            </div>
                        <?php endif; ?>
                        <?php if ($order['guest_address']): ?>
                            <div class="tracking-order-row">
                                <span>Shipping Address</span>
                                <strong>
                                    <?php 
                                    echo htmlspecialchars($order['guest_address']) . ', ' . 
                                         htmlspecialchars($order['guest_city']) . ', ' . 
                                         htmlspecialchars($order['guest_zip']); 
                                    ?>
                                </strong>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($order['items'])): ?>
                        <div class="tracking-order-items">
                            <h4>Order Items</h4>
                            <?php foreach ($order['items'] as $item): ?>
                                <div class="tracking-order-item">
                                    <div class="tracking-item-info">
                                        <p class="tracking-item-sku">SKU #<?php echo htmlspecialchars($item['ShoesID']); ?></p>
                                        <h5><?php echo htmlspecialchars($item['product_name']); ?></h5>
                                    </div>
                                    <div class="tracking-item-price">
                                        <?php echo $formatCurrency($item['Price']); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <div class="tracking-order-total">
                        <div class="tracking-order-row">
                            <span>Quantity</span>
                            <strong><?php echo htmlspecialchars($order['Quantity']); ?> items</strong>
                        </div>
                        <div class="tracking-order-row tracking-total-row">
                            <span>Total Amount</span>
                            <strong class="tracking-total-amount"><?php echo $formatCurrency($order['Total_price']); ?></strong>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<style>
.order-tracking-page {
    padding: 2rem 0 4rem;
    min-height: 60vh;
}

.tracking-title {
    text-align: center;
    margin-bottom: 3rem;
}

.tracking-container {
    max-width: 900px;
    margin: 0 auto;
}

.tracking-form-card {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    margin-bottom: 2rem;
}

.tracking-form {
    display: flex;
    gap: 1rem;
    align-items: flex-end;
    flex-wrap: nowrap;
}

.tracking-form-field {
    flex: 1;
    min-width: 300px;
}

.tracking-form-field label {
    display: block;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #1a1a1a;
}

.tracking-form-field input {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 2px solid #e5e5e5;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.2s;
    min-width: 0;
}

.tracking-form .btn {
    flex-shrink: 0;
    min-width: 160px;
    width: auto;
    padding: 0.875rem 1.5rem;
    line-height: 1.5;
}

.tracking-form-field input:focus {
    outline: none;
    border-color: #2563eb;
}

.tracking-alert {
    margin-top: 1rem;
    padding: 1rem;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.tracking-alert-error {
    background: #fef2f2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

.tracking-result {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.tracking-header-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 12px;
    padding: 2rem;
    color: white;
}

.tracking-header-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 2rem;
}

.tracking-label {
    font-size: 0.875rem;
    opacity: 0.9;
    margin-bottom: 0.5rem;
}

.tracking-id-display {
    font-size: 1.5rem;
    font-weight: 700;
    letter-spacing: 0.05em;
    margin: 0;
}

.tracking-value {
    font-size: 1.125rem;
    font-weight: 600;
    margin: 0;
}

.tracking-status-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.875rem;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
}

.tracking-progress-card {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.tracking-progress-header {
    margin-bottom: 2rem;
}

.tracking-progress-header h3 {
    margin: 0 0 1rem 0;
    font-size: 1.5rem;
    color: #1a1a1a;
}

.tracking-progress-bar-wrapper {
    height: 8px;
    background: #e5e5e5;
    border-radius: 4px;
    overflow: hidden;
    margin-bottom: 0.5rem;
}

.tracking-progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #667eea 0%, #764ba2 100%);
    border-radius: 4px;
    transition: width 0.5s ease;
}

.tracking-progress-text {
    font-size: 0.875rem;
    color: #666;
    margin: 0;
}

.tracking-steps {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.tracking-step {
    display: flex;
    gap: 1.5rem;
    position: relative;
}

.tracking-step:not(:last-child)::after {
    content: '';
    position: absolute;
    left: 12px;
    top: 32px;
    width: 2px;
    height: calc(100% + 0.5rem);
    background: #e5e5e5;
}

.tracking-step.completed:not(:last-child)::after {
    background: #667eea;
}

.tracking-step-icon {
    flex-shrink: 0;
    width: 28px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1;
}

.tracking-step-icon i {
    font-size: 1.25rem;
}

.tracking-step.completed .tracking-step-icon i {
    color: #10b981;
}

.tracking-step:not(.completed) .tracking-step-icon i {
    color: #d1d5db;
}

.tracking-step-content {
    flex: 1;
}

.tracking-step-name {
    font-size: 1.125rem;
    font-weight: 600;
    margin: 0 0 0.25rem 0;
    color: #1a1a1a;
}

.tracking-step.completed .tracking-step-name {
    color: #10b981;
}

.tracking-step-description {
    color: #666;
    margin: 0 0 0.25rem 0;
    font-size: 0.875rem;
}

.tracking-step-date {
    color: #999;
    font-size: 0.75rem;
    margin: 0;
}

.tracking-details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
}

.tracking-detail-card {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.tracking-detail-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.tracking-detail-header i {
    color: #667eea;
    font-size: 1.25rem;
}

.tracking-detail-header h4 {
    margin: 0;
    font-size: 1rem;
    color: #1a1a1a;
}

.tracking-detail-value {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1a1a1a;
    margin: 0 0 0.5rem 0;
}

.tracking-detail-note {
    font-size: 0.875rem;
    color: #666;
    margin: 0;
}

.tracking-number {
    font-family: 'Courier New', monospace;
    letter-spacing: 0.1em;
}

.tracking-order-details-card {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.tracking-order-details-header h3 {
    margin: 0 0 1.5rem 0;
    font-size: 1.5rem;
    color: #1a1a1a;
}

.tracking-order-info {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #e5e5e5;
}

.tracking-order-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.tracking-order-row span {
    color: #666;
    font-size: 0.875rem;
}

.tracking-order-row strong {
    color: #1a1a1a;
    font-weight: 600;
}

.tracking-order-items {
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #e5e5e5;
}

.tracking-order-items h4 {
    margin: 0 0 1rem 0;
    font-size: 1.125rem;
    color: #1a1a1a;
}

.tracking-order-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 0;
    border-bottom: 1px solid #f5f5f5;
}

.tracking-order-item:last-child {
    border-bottom: none;
}

.tracking-item-sku {
    font-size: 0.75rem;
    color: #999;
    margin: 0 0 0.25rem 0;
}

.tracking-item-info h5 {
    margin: 0;
    font-size: 1rem;
    color: #1a1a1a;
    font-weight: 600;
}

.tracking-item-price {
    font-weight: 600;
    color: #1a1a1a;
}

.tracking-order-total {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.tracking-total-row {
    padding-top: 1rem;
    border-top: 2px solid #e5e5e5;
    font-size: 1.125rem;
}

.tracking-total-amount {
    font-size: 1.5rem;
    color: #667eea;
}

.tracking-payment-method {
    color: #10b981;
    font-weight: 600;
    text-transform: capitalize;
}

@media (max-width: 768px) {
    .tracking-form {
        flex-direction: column;
        align-items: stretch;
    }

    .tracking-form-field {
        min-width: unset;
    }

    .tracking-form .btn {
        min-width: unset;
        width: 100%;
    }

    .tracking-header-info {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }

    .tracking-details-grid {
        grid-template-columns: 1fr;
    }
}
</style>

