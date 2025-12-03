<?php
$statusOptions = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled'];
$currentStatus = $order['Status'] ?? 'Pending';
$subtotal = 0;
if (!empty($order['items'])) {
    foreach ($order['items'] as $item) {
        $lineTotal = $item['Price'] ?? 0;
        $subtotal += $lineTotal;
    }
}
?>

<div class="row g-3 align-items-center mb-4">
  <div class="col">
    <div class="page-pretitle text-secondary text-uppercase">Đơn hàng</div>
    <h2 class="page-title">#<?php echo htmlspecialchars($order['OrderID']); ?></h2>
  </div>
  <div class="col-auto">
    <a href="/views/admin/index.php?controller=adminOrder&action=orders" class="btn btn-outline-secondary">
      <i class="ti ti-arrow-left me-1"></i> Danh sách đơn hàng
    </a>
  </div>
</div>

<div class="row g-4">
  <div class="col-lg-4">
    <div class="card">
      <div class="card-header">
        <div class="card-title">Thông tin khách hàng</div>
      </div>
      <div class="card-body">
        <dl class="row">
          <dt class="col-4 text-secondary">Khách hàng</dt>
          <dd class="col-8 fw-semibold"><?php echo htmlspecialchars($order['customer_name']); ?></dd>
          <dt class="col-4 text-secondary">Email</dt>
          <dd class="col-8"><?php echo htmlspecialchars($order['Email']); ?></dd>
          <dt class="col-4 text-secondary">Điện thoại</dt>
          <dd class="col-8"><?php echo htmlspecialchars($order['Phone'] ?? ''); ?></dd>
          <dt class="col-4 text-secondary">Ngày đặt</dt>
          <dd class="col-8"><?php echo date('d/m/Y H:i', strtotime($order['Date'])); ?></dd>
          <dt class="col-4 text-secondary">Điểm VIP</dt>
          <dd class="col-8"><?php echo number_format($order['Earned_VIP'] ?? 0, 2); ?> VND</dd>
        </dl>
        <label class="form-label mt-3">Cập nhật trạng thái</label>
        <select class="form-select" onchange="updateOrderStatus(<?php echo $order['OrderID']; ?>, this.value)">
          <?php foreach ($statusOptions as $status): ?>
            <option value="<?php echo $status; ?>" <?php echo $status === $currentStatus ? 'selected' : ''; ?>><?php echo $status; ?></option>
          <?php endforeach; ?>
        </select>
      </div>
    </div>
    <div class="card mt-4">
      <div class="card-header">
        <div class="card-title">Tổng quan đơn hàng</div>
      </div>
      <div class="card-body">
        <div class="d-flex justify-content-between mb-2">
          <span class="text-secondary">Tạm tính</span>
          <span><?php echo number_format($subtotal, 2); ?> VND</span>
        </div>
        <div class="d-flex justify-content-between mb-2">
          <span class="text-secondary">Phí vận chuyển</span>
          <span>0.00 VND</span>
        </div>
        <div class="d-flex justify-content-between mb-2">
          <span class="text-secondary">Số lượng</span>
          <span><?php echo (int)($order['Quantity'] ?? 0); ?> đôi</span>
        </div>
        <div class="d-flex justify-content-between fw-bold fs-5 mt-3">
          <span>Tổng cộng</span>
          <span><?php echo number_format($order['Total_price'], 2); ?> VND</span>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="card card-stacked">
      <div class="card-header">
        <div class="card-title">Chi tiết sản phẩm</div>
      </div>
      <div class="table-responsive">
        <table class="table table-vcenter">
          <thead>
            <tr>
              <th>Sản phẩm</th>
              <th>Giá</th>
              <th>Tổng</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($order['items'])): ?>
              <tr>
                <td colspan="3" class="text-center text-secondary">Chưa có sản phẩm nào được ghi nhận trong đơn hàng.</td>
              </tr>
            <?php else: ?>
              <?php foreach ($order['items'] as $item): ?>
                <?php $lineTotal = $item['Price'] ?? 0; ?>
                <tr>
                  <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                  <td><?php echo number_format($item['Price'], 2); ?> VND</td>
                  <td><?php echo number_format($lineTotal, 2); ?> VND</td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
          <tfoot>
            <tr>
              <td colspan="2" class="text-end fw-semibold">Tạm tính</td>
              <td><?php echo number_format($subtotal, 2); ?> VND</td>
            </tr>
            <tr>
              <td colspan="2" class="text-end fw-semibold">Tổng cộng</td>
              <td><?php echo number_format($order['Total_price'], 2); ?> VND</td>
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
  const confirmed = confirm('Xác nhận cập nhật trạng thái đơn hàng này?');
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
