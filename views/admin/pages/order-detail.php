<div class="admin-header">
    <h1>Đơn Hàng #<?php echo htmlspecialchars($order['OrderID']); ?></h1>
    <a href="/views/admin/index.php?controller=adminOrder&action=orders" class="btn btn-secondary">Quay Lại Danh Sách Đơn Hàng</a>
</div>

<div class="order-detail">
    <div class="order-info">
        <h2>Thông Tin Đơn Hàng</h2>
        <div class="info-group">
            <div class="info-label">Khách Hàng:</div>
            <div class="info-value"><?php echo htmlspecialchars($order['customer_name']); ?> (<?php echo htmlspecialchars($order['Email']); ?>, <?php echo htmlspecialchars($order['Phone']); ?>)</div>
        </div>
        <div class="info-group">
            <div class="info-label">Ngày:</div>
            <div class="info-value"><?php echo date('M d, Y', strtotime($order['Date'])); ?></div>
        </div>
        <div class="info-group">
            <div class="info-label">Trạng Thái:</div>
            <div class="info-value">
                <select onchange="updateOrderStatus(<?php echo $order['OrderID']; ?>, this.value)">
                    <option value="Pending" <?php echo $order['Status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                    <option value="Processing" <?php echo $order['Status'] == 'Processing' ? 'selected' : ''; ?>>Processing</option>
                    <option value="Shipped" <?php echo $order['Status'] == 'Shipped' ? 'selected' : ''; ?>>Shipped</option>
                    <option value="Delivered" <?php echo $order['Status'] == 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
                    <option value="Cancelled" <?php echo $order['Status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                </select>
            </div>
        </div>
        <div class="info-group">
            <div class="info-label">Số Lượng:</div>
            <div class="info-value"><?php echo htmlspecialchars($order['Quantity']); ?></div>
        </div>
        <div class="info-group">
            <div class="info-label">Điểm VIP Được Tích Lũy:</div>
            <div class="info-value"><?php echo number_format($order['Earned_VIP'], 2); ?> VND</div>
        </div>
    </div>
    
    <h2>Chi Tiết Đơn Hàng</h2>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Sản Phẩm</th>
                <th>Giá</th>
                <th>Tổng</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($order['items'])): ?>
                <tr>
                    <td colspan="3" style="text-align: center;">Không có sản phẩm nào.</td>
                </tr>
            <?php else: ?>
                <?php
                $subtotal = 0;
                foreach ($order['items'] as $item):
                    $itemTotal = $order['Quantity'] * $item['Price'];
                    $subtotal += $itemTotal;
                ?>
                    <tr>
                        <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                        <td><?php echo number_format($item['Price'], 2); ?> VND</td>
                        <td><?php echo number_format($itemTotal, 2); ?> VND</td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="text-right">Tạm Tính</td>
                <td><?php echo number_format($subtotal, 2); ?> VND</td>
            </tr>
            <tr>
                <td colspan="2" class="text-right">Phí Vận Chuyển</td>
                <td>0.00 VND</td>
            </tr>
            <tr>
                <td colspan="2" class="text-right"><strong>Tổng Cộng</strong></td>
                <td><strong><?php echo number_format($order['Total_price'], 2); ?> VND</strong></td>
            </tr>
        </tfoot>
    </table>
</div>

<script>
function updateOrderStatus(orderId, status) {
    if (confirm('Bạn có chắc muốn cập nhật trạng thái đơn hàng này?')) {
        window.location.href = '/views/admin/index.php?controller=adminOrder&action=updateOrderStatus&id=' + orderId + '&status=' + status;
    }
}
</script>

<?php require_once __DIR__ . '/../components/admin_footer.php'; ?>