<div class="admin-header">
    <h1>Đơn Hàng</h1>
</div>

<table class="admin-table" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
    <thead>
        <tr style="background-color: #f4f4f4; color: #333;">
            <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Mã Đơn Hàng</th>
            <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Khách Hàng</th>
            <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Ngày</th>
            <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Số Tiền</th>
            <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Trạng Thái</th>
            <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Hành Động</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($orders)): ?>
            <tr>
                <td colspan="6" style="text-align: center; padding: 20px; border: 1px solid #ddd;">Không có đơn hàng nào.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($orders as $order): ?>
                <tr style="border-bottom: 1px solid #ddd;">
                    <td style="padding: 10px; border: 1px solid #ddd;">#<?php echo htmlspecialchars($order['OrderID']); ?></td>
                    <td style="padding: 10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($order['customer_name']); ?> (<?php echo htmlspecialchars($order['Email']); ?>)</td>
                    <td style="padding: 10px; border: 1px solid #ddd;"><?php echo date('M d, Y', strtotime($order['Date'])); ?></td>
                    <td style="padding: 10px; border: 1px solid #ddd;"><?php echo number_format($order['Total_price'], 2); ?> VND</td>
                    <td style="padding: 10px; border: 1px solid #ddd;">
                        <?php
                        // Tùy chỉnh màu sắc cho từng trạng thái
                        $statusStyles = [
                            'Pending' => 'background-color: #ffeb3b; color: #333; padding: 5px 10px; border-radius: 12px; display: inline-block; font-size: 14px; font-weight: bold; border: 1px solid #d4c107;',
                            'Processing' => 'background-color: #2196f3; color: white; padding: 5px 10px; border-radius: 12px; display: inline-block; font-size: 14px; font-weight: bold; border: 1px solid #1976d2;',
                            'Shipped' => 'background-color: #ff9800; color: white; padding: 5px 10px; border-radius: 12px; display: inline-block; font-size: 14px; font-weight: bold; border: 1px solid #f57c00;',
                            'Delivered' => 'background-color: #4caf50; color: white; padding: 5px 10px; border-radius: 12px; display: inline-block; font-size: 14px; font-weight: bold; border: 1px solid #388e3c;',
                            'Cancelled' => 'background-color: #f44336; color: white; padding: 5px 10px; border-radius: 12px; display: inline-block; font-size: 14px; font-weight: bold; border: 1px solid #d32f2f;'
                        ];
                        $statusStyle = isset($statusStyles[$order['Status']]) ? $statusStyles[$order['Status']] : '';
                        ?>
                        <span style="<?php echo $statusStyle; ?>">
                            <?php echo htmlspecialchars($order['Status']); ?>
                        </span>
                    </td>
                    <td style="padding: 10px; border: 1px solid #ddd;">
                        <a href="/views/admin/index.php?controller=adminOrder&action=orderDetail&id=<?php echo $order['OrderID']; ?>" 
                           style="display: inline-block; padding: 6px 12px; background-color: #ff6b6b; color: white; text-decoration: none; border-radius: 5px; font-size: 14px; font-weight: bold; border: 1px solid #ff6b6b; transition: background-color 0.3s;"
                           onmouseover="this.style.backgroundColor='#ff5252'"
                           onmouseout="this.style.backgroundColor='#ff6b6b'">Xem</a>
                        <select onchange="updateOrderStatus(<?php echo $order['OrderID']; ?>, this.value)"
                                style="padding: 6px 10px; border-radius: 5px; border: 1px solid #ccc; font-size: 14px; margin-left: 10px; background-color: #fff; cursor: pointer; transition: border-color 0.3s;"
                                onmouseover="this.style.borderColor='#2196f3'"
                                onmouseout="this.style.borderColor='#ccc'">
                            <option value="Pending" <?php echo $order['Status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                            <option value="Processing" <?php echo $order['Status'] == 'Processing' ? 'selected' : ''; ?>>Processing</option>
                            <option value="Shipped" <?php echo $order['Status'] == 'Shipped' ? 'selected' : ''; ?>>Shipped</option>
                            <option value="Delivered" <?php echo $order['Status'] == 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
                            <option value="Cancelled" <?php echo $order['Status'] == 'Cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<script>
function updateOrderStatus(orderId, status) {
    if (confirm('Bạn có chắc muốn cập nhật trạng thái đơn hàng này?')) {
        window.location.href = '/views/admin/index.php?controller=adminOrder&action=updateOrderStatus&id=' + orderId + '&status=' + status;
    }
}
</script>

<?php require_once __DIR__ . '/../components/admin_footer.php'; ?>