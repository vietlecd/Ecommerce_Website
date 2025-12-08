<?php
$statusStyles = [
    'Pending'    => ['bg-yellow-lt text-yellow-darker', 'Pending'],
    'Processing' => ['bg-blue-lt text-blue-darker', 'Processing'],
    'Shipped'    => ['bg-orange-lt text-orange-darker', 'Shipped'],
    'Delivered'  => ['bg-green-lt text-green-darker', 'Delivered'],
    'Cancelled'  => ['bg-red-lt text-red-darker', 'Cancelled'],
];

$keyword      = $keyword      ?? '';
$statusFilter = $statusFilter ?? '';
$sort         = $sort         ?? 'date_desc';

$totalOrders  = $totalOrders ?? count($orders);
$perPage      = $perPage     ?? max(1, count($orders));
$currentPage  = $currentPage ?? 1;
$totalPages   = $totalPages  ?? 1;
$offset       = max(0, ($currentPage - 1) * $perPage);
$fromRecord   = $totalOrders > 0 ? $offset + 1 : 0;
$toRecord     = $totalOrders > 0 ? min($offset + count($orders), $totalOrders) : 0;

$baseQuery = [
    'controller' => 'adminOrder',
    'action'     => 'orders',
];

if ($keyword !== '') {
    $baseQuery['keyword'] = $keyword;
}
if ($statusFilter !== '') {
    $baseQuery['status'] = $statusFilter;
}
if ($sort !== 'date_desc') {
    $baseQuery['sort'] = $sort;
}

$buildPageUrl = function (int $page) use ($baseQuery): string {
    if ($page < 1) {
        $page = 1;
    }
    return '/views/admin/index.php?' . http_build_query(array_merge($baseQuery, ['page' => $page]));
};
?>

<div class="row g-4">
  <div class="col-12">
    <div class="card card-stacked">
      <div class="card-header">
        <div>
          <div class="card-title">Order management</div>
          <div class="text-secondary">Track order status and update fulfillment quickly.</div>
        </div>
      </div>

      <!-- FILTER BAR -->
      <div class="card-body border-bottom py-3">
        <form class="row g-2 align-items-end" method="get" action="/views/admin/index.php">
          <input type="hidden" name="controller" value="adminOrder">
          <input type="hidden" name="action" value="orders">

          <div class="col-12 col-md-4 col-lg-4">
            <label class="form-label">Keyword</label>
            <input
              type="text"
              name="keyword"
              class="form-control"
              placeholder="Order ID, customer name, or email"
              value="<?php echo htmlspecialchars($keyword); ?>"
            >
          </div>

          <div class="col-6 col-md-3 col-lg-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
              <option value="">All statuses</option>
              <?php foreach (array_keys($statusStyles) as $st): ?>
                <option value="<?php echo $st; ?>" <?php echo $statusFilter === $st ? 'selected' : ''; ?>>
                  <?php echo $st; ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <div class="col-6 col-md-3 col-lg-3">
            <label class="form-label">Sort by</label>
            <select name="sort" class="form-select">
              <option value="date_desc" <?php echo $sort === 'date_desc' ? 'selected' : ''; ?>>
                Newest first
              </option>
              <option value="date_asc" <?php echo $sort === 'date_asc' ? 'selected' : ''; ?>>
                Oldest first
              </option>
              <option value="id_desc" <?php echo $sort === 'id_desc' ? 'selected' : ''; ?>>
                Order ID (high → low)
              </option>
              <option value="id_asc" <?php echo $sort === 'id_asc' ? 'selected' : ''; ?>>
                Order ID (low → high)
              </option>
            </select>
          </div>

          <div class="col-12 col-md-2 col-lg-2">
            <label class="form-label d-none d-md-block">&nbsp;</label>
            <button type="submit" class="btn btn-outline-primary w-100">
              <i class="ti ti-search me-1"></i> Filter
            </button>
          </div>
        </form>
      </div>

      <?php if (empty($orders)): ?>
        <div class="card-body">
          <div class="empty">
            <div class="empty-header">No orders found</div>
            <p class="empty-subtitle text-secondary">
              There are no orders matching the current filters. Try adjusting your filters or wait for new orders.
            </p>
            <div class="empty-action">
              <a href="/index.php?controller=home&action=index" class="btn btn-primary">
                <i class="ti ti-arrow-left me-1"></i> Back to storefront
              </a>
            </div>
          </div>
        </div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-vcenter">
            <thead>
              <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Order date</th>
                <th class="text-center">Quantity</th>
                <th>Total</th>
                <th>Status</th>
                <th class="w-1"></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($orders as $order): ?>
                <?php
                $status      = $order['Status'] ?? 'Pending';
                $style       = $statusStyles[$status][0] ?? 'bg-secondary-lt text-secondary';
                $statusLabel = $statusStyles[$status][1] ?? $status;
                ?>
                <tr>
                  <td class="fw-semibold">#<?php echo htmlspecialchars($order['OrderID']); ?></td>
                  <td>
                    <div class="fw-semibold"><?php echo htmlspecialchars($order['customer_name'] ?? 'Guest'); ?></div>
                    <div class="text-secondary small"><?php echo htmlspecialchars($order['Email'] ?? 'N/A'); ?></div>
                  </td>
                  <td><?php echo date('d/m/Y', strtotime($order['Date'])); ?></td>
                  <td class="text-center"><?php echo (int)($order['Quantity'] ?? 0); ?></td>
                  <td><?php echo '$' . number_format($order['Total_price'], 2); ?></td>
                  <td>
                    <span class="badge <?php echo $style; ?>"><?php echo htmlspecialchars($statusLabel); ?></span>
                  </td>
                  <td class="text-end">
                    <div class="btn-list flex-nowrap mb-0">
                      <a
                        href="/views/admin/index.php?controller=adminOrder&action=orderDetail&id=<?php echo $order['OrderID']; ?>"
                        class="btn btn-sm btn-outline-secondary"
                      >
                        <i class="ti ti-eye me-1"></i> View
                      </a>
                      <select
                        class="form-select form-select-sm"
                        onchange="updateOrderStatus(<?php echo $order['OrderID']; ?>, this.value)"
                      >
                        <option value="Pending"    <?php echo $status === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="Processing" <?php echo $status === 'Processing' ? 'selected' : ''; ?>>Processing</option>
                        <option value="Shipped"    <?php echo $status === 'Shipped' ? 'selected' : ''; ?>>Shipped</option>
                        <option value="Delivered"  <?php echo $status === 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
                        <option value="Cancelled"  <?php echo $status === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                      </select>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>

        <div class="card-footer d-flex align-items-center">
          <p class="m-0 text-secondary">
            Showing <?php echo $fromRecord; ?>–<?php echo $toRecord; ?> of <?php echo $totalOrders; ?> orders
          </p>
          <?php if ($totalPages > 1): ?>
            <ul class="pagination m-0 ms-auto">
              <li class="page-item <?php echo $currentPage <= 1 ? 'disabled' : ''; ?>">
                <a class="page-link" href="<?php echo $buildPageUrl(max(1, $currentPage - 1)); ?>" tabindex="-1">
                  <i class="ti ti-chevron-left"></i> Prev
                </a>
              </li>
              <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php echo $currentPage === $i ? 'active' : ''; ?>">
                  <a class="page-link" href="<?php echo $buildPageUrl($i); ?>"><?php echo $i; ?></a>
                </li>
              <?php endfor; ?>
              <li class="page-item <?php echo $currentPage >= $totalPages ? 'disabled' : ''; ?>">
                <a class="page-link" href="<?php echo $buildPageUrl(min($totalPages, $currentPage + 1)); ?>">
                  Next <i class="ti ti-chevron-right"></i>
                </a>
              </li>
            </ul>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<script>
function updateOrderStatus(orderId, status) {
  if (!orderId || !status) {
    return;
  }
  const confirmed = confirm('Are you sure you want to update this order status?');
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
