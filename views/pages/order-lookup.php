<?php
$formatCurrency = function ($value) {
    return '$' . number_format($value, 2);
};
$meta = $orderSummary['meta'] ?? null;
$items = $orderSummary['items'] ?? [];
$emailOrders = $emailOrders ?? [];
?>

<section class="order-lookup-page">
    <div class="order-lookup-header">
        <p class="section-eyebrow">Order status</p>
        <h2>Find your order by ID</h2>
        <p class="section-subtitle">Drop in the order ID from your confirmation screen to see a full breakdown of the journey.</p>
    </div>

    <form method="post" action="" class="order-lookup-form">
        <div class="order-lookup-input-group">
            <label for="order_id">Order ID</label>
            <div class="order-lookup-input-row">
                <input
                    type="text"
                    id="order_id"
                    name="order_id"
                    inputmode="numeric"
                    pattern="[0-9]+"
                    placeholder="For example: 1024"
                    value="<?php echo isset($_POST['order_id']) ? htmlspecialchars($_POST['order_id']) : ''; ?>"
                    required
                >
                <button type="submit" name="lookup_id" class="btn btn-full order-lookup-submit">View order</button>
            </div>
            <p class="order-lookup-hint">You can find this on the success message right after checkout (Order ID #1234).</p>
        </div>
    </form>

    <div class="order-lookup-forgot">
        <div class="order-lookup-header order-lookup-header-secondary">
            <p class="section-eyebrow">Forgot your order?</p>
            <h3>Look up by email instead</h3>
            <p class="order-lookup-hint">Type the email you used at checkout and we will surface every order attached to it.</p>
        </div>
        <form method="post" action="" class="order-lookup-inline-form">
            <div class="order-lookup-input-group">
                <label for="lookup_email">Email</label>
                <div class="order-lookup-input-row">
                    <input
                        type="email"
                        id="lookup_email"
                        name="lookup_email"
                        placeholder="you@example.com"
                        value="<?php echo isset($_POST['lookup_email']) ? htmlspecialchars($_POST['lookup_email']) : ''; ?>"
                        required
                    >
                    <button type="submit" name="lookup_email_submit" class="btn btn-full order-lookup-submit">View all orders</button>
                </div>
            </div>
        </form>
    </div>

    <?php if (!empty($error)): ?>
        <div class="order-lookup-alert-error">
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <?php if ($meta): ?>
        <section class="order-lookup-layout">
            <article class="order-lookup-card order-lookup-summary-card">
                <header class="order-lookup-card-header">
                    <div>
                        <p class="section-eyebrow">Order overview</p>
                        <h3>Order #<?php echo htmlspecialchars($meta['OrderID']); ?></h3>
                    </div>
                    <span class="order-status-pill order-status-<?php echo strtolower($meta['Status']); ?>">
                        <?php echo htmlspecialchars($meta['Status']); ?>
                    </span>
                </header>
                <dl class="order-lookup-meta-grid">
                    <div class="order-lookup-meta-item">
                        <dt>Placed on</dt>
                        <dd><?php echo date('M d, Y', strtotime($meta['Date'])); ?></dd>
                    </div>
                    <div class="order-lookup-meta-item">
                        <dt>Total pairs</dt>
                        <dd><?php echo htmlspecialchars($meta['Quantity']); ?></dd>
                    </div>
                    <div class="order-lookup-meta-item">
                        <dt>Total paid</dt>
                        <dd><?php echo $formatCurrency($orderSummary['total']); ?></dd>
                    </div>
                    <?php if (!empty($meta['Earned_VIP'])): ?>
                        <div class="order-lookup-meta-item">
                            <dt>VIP credit earned</dt>
                            <dd><?php echo $formatCurrency($meta['Earned_VIP']); ?></dd>
                        </div>
                    <?php endif; ?>
                    <div class="order-lookup-meta-item">
                        <dt>Customer</dt>
                        <dd>
                            <?php if (!empty($meta['customer_name'])): ?>
                                <?php echo htmlspecialchars($meta['customer_name']); ?>
                                <?php if (!empty($meta['Email'])): ?>
                                    <span class="order-lookup-meta-sub"><?php echo htmlspecialchars($meta['Email']); ?></span>
                                <?php endif; ?>
                            <?php else: ?>
                                Guest checkout
                                <?php if (!empty($meta['Email'])): ?>
                                    <span class="order-lookup-meta-sub"><?php echo htmlspecialchars($meta['Email']); ?></span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </dd>
                    </div>
                </dl>
            </article>

            <article class="order-lookup-card">
                <header class="order-lookup-card-header">
                    <div>
                        <p class="section-eyebrow">Line items</p>
                        <h3>Everything in this order</h3>
                    </div>
                </header>
                <div class="order-lookup-items">
                    <?php foreach ($items as $item): ?>
                        <?php
                        $imageSrc = null;
                        if (!empty($item['image'])) {
                            if (filter_var($item['image'], FILTER_VALIDATE_URL)) {
                                $imageSrc = $item['image'];
                            } elseif (file_exists($item['image'])) {
                                $imageSrc = '/' . ltrim($item['image'], '/');
                            }
                        }
                        ?>
                        <div class="order-lookup-item-row">
                            <div class="order-lookup-item-main">
                                <?php if ($imageSrc): ?>
                                    <div class="order-lookup-item-thumb">
                                        <img src="<?php echo htmlspecialchars($imageSrc); ?>" alt="<?php echo htmlspecialchars($item['name']); ?>" loading="lazy">
                                    </div>
                                <?php endif; ?>
                                <div class="order-lookup-item-copy">
                                    <p class="order-lookup-item-label">SKU #<?php echo htmlspecialchars($item['id']); ?></p>
                                    <h4><?php echo htmlspecialchars($item['name']); ?></h4>
                                    <p class="order-lookup-item-sub">
                                        <?php echo $item['quantity']; ?> × <?php echo $formatCurrency($item['price']); ?>
                                    </p>
                                </div>
                            </div>
                            <div class="order-lookup-item-total">
                                <span><?php echo $formatCurrency($item['subtotal']); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </article>

            <aside class="order-lookup-card order-lookup-pricing">
                <header class="order-lookup-card-header">
                    <div>
                        <p class="section-eyebrow">Payment summary</p>
                        <h3>Final numbers</h3>
                    </div>
                </header>
                <ul class="order-lookup-pricing-list">
                    <li>
                        <span>Subtotal</span>
                        <strong><?php echo $formatCurrency($orderSummary['subtotal']); ?></strong>
                    </li>
                    <li>
                        <span>Shipping</span>
                        <strong><?php echo $formatCurrency($orderSummary['shipping']); ?></strong>
                    </li>
                    <li class="order-lookup-pricing-total">
                        <span>Total charged</span>
                        <strong><?php echo $formatCurrency($orderSummary['total']); ?></strong>
                    </li>
                </ul>
                <p class="order-lookup-note">
                    Status updates such as Processing, Shipped, or Delivered will appear here as your order moves through our studio.
                </p>
            </aside>
        </section>
    <?php endif; ?>

    <?php if (!empty($emailOrders)): ?>
        <section class="order-lookup-email-results">
            <article class="order-lookup-card order-lookup-summary-card">
                <header class="order-lookup-card-header">
                    <div>
                        <p class="section-eyebrow">Email history</p>
                        <h3>All orders for <?php echo htmlspecialchars($emailOrders[0]['email']); ?></h3>
                    </div>
                </header>
                <div class="order-lookup-email-list">
                    <?php foreach ($emailOrders as $order): ?>
                        <div class="order-lookup-email-row">
                            <div class="order-lookup-email-main">
                                <p class="order-lookup-item-label">Order #<?php echo htmlspecialchars($order['OrderID']); ?></p>
                                <h4><?php echo htmlspecialchars($order['customer_name'] ?: 'Guest checkout'); ?></h4>
                                <p class="order-lookup-item-sub">
                                    <?php echo htmlspecialchars(date('M d, Y', strtotime($order['Date']))); ?> ·
                                    <?php echo htmlspecialchars($order['Quantity']); ?> pairs ·
                                    <?php echo htmlspecialchars($order['Status']); ?>
                                </p>
                            </div>
                            <div class="order-lookup-email-total">
                                <span><?php echo $formatCurrency($order['Total_price']); ?></span>
                                <a href="/index.php?controller=orderLookup&action=index&order_id=<?php echo urlencode($order['OrderID']); ?>" class="order-lookup-email-link">View details</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </article>
        </section>
    <?php endif; ?>
</section>


