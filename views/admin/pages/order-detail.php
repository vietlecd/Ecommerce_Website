<?php
$statusOptions  = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];
$currentStatus  = $order['Status'] ?? 'Pending';
$subtotal       = 0;

if (!empty($order['items'])) {
    foreach ($order['items'] as $item) {
        $lineTotal = $item['Price'] ?? 0;
        $subtotal += $lineTotal;
    }
}
?>

<div class="row g-3 align-items-center mb-4">
  <div class="col">
    <div class="page-pretitle text-secondary text-uppercase">Order</div>
    <h2 class="page-title">#<?php echo htmlspecialchars($order['OrderID']); ?></h2>
  </div>
  <div class="col-auto">
    <a href="/views/admin/index.php?controller=adminOrder&action=orders" class="btn btn-outline-secondary">
      <i class="ti ti-arrow-left me-1"></i> Back to orders
    </a>
  </div>
</div>

<div class="row g-4">
  <div class="col-lg-4">
    <div class="card">
      <div class="card-header">
        <div class="card-title">Customer information</div>
      </div>
      <div class="card-body">
        <dl class="row">
          <dt class="col-4 text-secondary">Customer</dt>
          <dd class="col-8 fw-semibold"><?php echo htmlspecialchars($order['customer_name']); ?></dd>

          <dt class="col-4 text-secondary">Email</dt>
          <dd class="col-8"><?php echo htmlspecialchars($order['Email']); ?></dd>

          <dt class="col-4 text-secondary">Phone</dt>
          <dd class="col-8"><?php echo htmlspecialchars($order['Phone'] ?? ''); ?></dd>

          <dt class="col-4 text-secondary">Order date</dt>
          <dd class="col-8"><?php echo date('d/m/Y', strtotime($order['Date'])); ?></dd>

          <dt class="col-4 text-secondary">VIP points</dt>
          <dd class="col-8"><?php echo '$' . number_format($order['Earned_VIP'] ?? 0, 2); ?></dd>
        </dl>

        <label class="form-label mt-3">Update status</label>
        <select class="form-select" onchange="updateOrderStatus(<?php echo $order['OrderID']; ?>, this.value)">
          <?php foreach ($statusOptions as $status): ?>
            <option value="<?php echo $status; ?>" <?php echo $status === $currentStatus ? 'selected' : ''; ?>>
              <?php echo $status; ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>

    <div class="card mt-4">
      <div class="card-header">
        <div class="card-title">Order summary</div>
      </div>
      <div class="card-body">
        <div class="d-flex justify-content-between mb-2">
          <span class="text-secondary">Subtotal</span>
          <span><?php echo '$' . number_format($subtotal, 2); ?></span>
        </div>
        <div class="d-flex justify-content-between mb-2">
          <span class="text-secondary">Shipping fee</span>
          <span>$0.00</span>
        </div>
        <div class="d-flex justify-content-between mb-2">
          <span class="text-secondary">Quantity</span>
          <span><?php echo (int)($order['Quantity'] ?? 0); ?></span>
        </div>
        <div class="d-flex justify-content-between fw-bold fs-5 mt-3">
          <span>Total</span>
          <span><?php echo '$' . number_format($order['Total_price'], 2); ?></span>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="card card-stacked">
      <div class="card-header">
        <div class="card-title">Order items</div>
      </div>
      <div class="table-responsive">
        <table class="table table-vcenter">
          <thead>
            <tr>
              <th>Product</th>
              <th>Price</th>
              <th>Total</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($order['items'])): ?>
              <tr>
                <td colspan="3" class="text-center text-secondary">
                  No products recorded for this order.
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($order['items'] as $item): ?>
                <?php $lineTotal = $item['Price'] ?? 0; ?>
                <tr>
                  <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                  <td><?php echo '$' . number_format($item['Price'], 2); ?></td>
                  <td><?php echo '$' . number_format($lineTotal, 2); ?></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="2" class="text-end fw-semibold">Subtotal</td>
              <td><?php echo '$' . number_format($subtotal, 2); ?></td>
            </tr>
            <tr>
              <td colspan="2" class="text-end fw-semibold">Total</td>
              <td><?php echo '$' . number_format($order['Total_price'], 2); ?></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>
</div>

<script>
function updateOrderStatus(orderId, status) {
  if (!orderId || !status) {
    return;
  }
  const confirmed = confirm('Confirm updating the status of this order?');
  if (!confirmed) {
    return;
  }
  const params = new URLSearchParams({
    controller: 'adminOrder',
    action: 'updateOrderStatus',
    id: orderId,
    status: status
  });
  window.location.href = '/views/admin/index.php?' + params.toString();
}
</script>

<?php require_once __DIR__ . '/../components/admin_footer.php'; ?>
