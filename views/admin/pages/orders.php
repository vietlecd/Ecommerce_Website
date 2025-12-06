<?php
$statusStyles = [
    'Pending' => ['bg-yellow-lt text-yellow-darker', 'Đang chờ'],
    'Processing' => ['bg-blue-lt text-blue-darker', 'Đang xử lý'],
    'Shipped' => ['bg-orange-lt text-orange-darker', 'Đang giao'],
    'Delivered' => ['bg-green-lt text-green-darker', 'Hoàn tất'],
    'Cancelled' => ['bg-red-lt text-red-darker', 'Đã hủy'],
];

$totalOrders = $totalOrders ?? count($orders);
$perPage = $perPage ?? max(1, count($orders));
$currentPage = $currentPage ?? 1;
$totalPages = $totalPages ?? 1;
$offset = max(0, ($currentPage - 1) * $perPage);
$fromRecord = $totalOrders > 0 ? $offset + 1 : 0;
$toRecord = $totalOrders > 0 ? min($offset + count($orders), $totalOrders) : 0;

$baseQuery = [
    'controller' => 'adminOrder',
    'action' => 'orders',
];
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
          <div class="card-title">Quản lý đơn hàng</div>
          <div class="text-secondary">Theo dõi tình trạng và cập nhật nhanh trạng thái giao hàng.</div>
        </div>
      </div>
      <?php if (empty($orders)): ?>
        <div class="card-body">
          <div class="empty">
            <div class="empty-header">Chưa có đơn hàng</div>
            <p class="empty-subtitle text-secondary">
              Hệ thống chưa ghi nhận đơn hàng nào. Khi khách mua tại cửa hàng, yêu cầu sẽ xuất hiện ở đây để bạn xác nhận.
            </p>
            <div class="empty-action">
              <a href="/index.php?controller=home&action=index" class="btn btn-primary">
                <i class="ti ti-arrow-left me-1"></i> Quay lại trang bán hàng
              </a>
            </div>
          </div>
        </div>
      <?php else: ?>
        <div class="table-responsive">
          <table class="table table-vcenter">
            <thead>
              <tr>
                <th>Mã đơn</th>
                <th>Khách hàng</th>
                <th>Ngày đặt</th>
                <th class="text-center">Số lượng</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                <th class="w-1"></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($orders as $order): ?>
                <?php
                $status = $order['Status'] ?? 'Pending';
                $style = $statusStyles[$status][0] ?? 'bg-secondary-lt text-secondary';
                $statusLabel = $statusStyles[$status][1] ?? $status;
                ?>
                <tr>
                  <td class="fw-semibold">#<?php echo htmlspecialchars($order['OrderID']); ?></td>
                  <td>
                    <div class="fw-semibold"><?php echo htmlspecialchars($order['customer_name']); ?></div>
                    <div class="text-secondary small"><?php echo htmlspecialchars($order['Email']); ?></div>
                  </td>
                  <td><?php echo date('d/m/Y H:i', strtotime($order['Date'])); ?></td>
                  <td class="text-center"><?php echo (int)($order['Quantity'] ?? 0); ?></td>
                  <td><?php echo number_format($order['Total_price'], 2); ?> VND</td>
                  <td>
                    <span class="badge <?php echo $style; ?>"><?php echo htmlspecialchars($statusLabel); ?></span>
                  </td>
                  <td class="text-end">
                    <div class="btn-list flex-nowrap mb-0">
                      <a href="/views/admin/index.php?controller=adminOrder&action=orderDetail&id=<?php echo $order['OrderID']; ?>" class="btn btn-sm btn-outline-secondary">
                        <i class="ti ti-eye me-1"></i> Xem
                      </a>
                      <select class="form-select form-select-sm" onchange="updateOrderStatus(<?php echo $order['OrderID']; ?>, this.value)">
                        <option value="Pending" <?php echo $status === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="Processing" <?php echo $status === 'Processing' ? 'selected' : ''; ?>>Processing</option>
                        <option value="Shipped" <?php echo $status === 'Shipped' ? 'selected' : ''; ?>>Shipped</option>
                        <option value="Delivered" <?php echo $status === 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
                        <option value="Cancelled" <?php echo $status === 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
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
            Hiển thị <?php echo $fromRecord; ?>–<?php echo $toRecord; ?> / <?php echo $totalOrders; ?> đơn hàng
          </p>
          <?php if ($totalPages > 1): ?>
            <ul class="pagination m-0 ms-auto">
              <li class="page-item <?php echo $currentPage <= 1 ? 'disabled' : ''; ?>">
                <a class="page-link" href="<?php echo $buildPageUrl(max(1, $currentPage - 1)); ?>" tabindex="-1">
                  <i class="ti ti-chevron-left"></i> Trước
                </a>
              </li>
              <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php echo $currentPage === $i ? 'active' : ''; ?>">
                  <a class="page-link" href="<?php echo $buildPageUrl($i); ?>"><?php echo $i; ?></a>
                </li>
              <?php endfor; ?>
              <li class="page-item <?php echo $currentPage >= $totalPages ? 'disabled' : ''; ?>">
                <a class="page-link" href="<?php echo $buildPageUrl(min($totalPages, $currentPage + 1)); ?>">
                  Sau <i class="ti ti-chevron-right"></i>
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
  const confirmed = confirm('Bạn có chắc chắn muốn cập nhật trạng thái đơn hàng này?');
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
