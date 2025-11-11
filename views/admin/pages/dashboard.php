<div class="admin-header">
    <h1>Dashboard</h1>
    <div class="date"><?php echo date('F d, Y'); ?></div>
</div>

<div class="admin-stats">
    <div class="stat-card">
        <h3><?php echo isset($stats['totalOrders']) ? (int)$stats['totalOrders'] : 0; ?></h3>
        <p>Tổng Số Đơn Hàng</p>
    </div>
    <div class="stat-card">
        <h3>$<?php echo isset($stats['monthlyRevenue']) ? number_format((float)$stats['monthlyRevenue'], 2) : '0.00'; ?></h3>
        <p>Doanh Thu Tháng</p>
    </div>
    <div class="stat-card">
        <h3><?php echo isset($stats['totalCustomers']) ? (int)$stats['totalCustomers'] : 0; ?></h3>
        <p>Tổng Số Khách Hàng</p>
    </div>
    <div class="stat-card">
        <h3><?php echo isset($stats['totalProducts']) ? (int)$stats['totalProducts'] : 0; ?></h3>
        <p>Tổng Số Sản Phẩm</p>
    </div>
</div>

<div class="recent-orders">
    <h2>Đơn Hàng Gần Đây</h2>
    <table class="admin-table">
        <thead>
            <tr>
                <th>Mã Đơn Hàng</th>
                <th>Khách Hàng</th>
                <th>Ngày</th>
                <th>Số Tiền</th>
                <th>Trạng Thái</th>
                <th>Hành Động</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($recentOrders)) { ?>
            <?php foreach ($recentOrders as $order) { 
                $status = $order['Status'] ?? '';
                $statusClass = 'status-processing';
                if ($status === 'Delivered') $statusClass = 'status-delivered';
                elseif ($status === 'Shipped') $statusClass = 'status-shipped';
                elseif ($status === 'Cancelled') $statusClass = 'status-cancelled';
            ?>
            <tr>
                <td>#<?php echo htmlspecialchars($order['OrderID']); ?></td>
                <td><?php echo htmlspecialchars($order['customer_name']); ?></td>
                <td><?php echo date('M d, Y', strtotime($order['Date'])); ?></td>
                <td>$<?php echo number_format((float)$order['Total_price'], 2); ?></td>
                <td><span class="<?php echo $statusClass; ?>"><?php echo htmlspecialchars($status); ?></span></td>
                <td><a href="/admin/index.php?controller=admin&action=orderDetail&id=<?php echo urlencode($order['OrderID']); ?>" class="btn-view">Xem</a></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
                <td colspan="6" style="text-align:center;">Không có đơn hàng gần đây</td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php require_once 'views/admin/components/admin_footer.php'; ?>