<div class="admin-header">
    <h1>Dashboard</h1>
    <div class="date"><?php echo date('F d, Y'); ?></div>
</div>

<div class="admin-stats">
    <div class="stat-card">
        <h3>42</h3>
        <p>Tổng Số Đơn Hàng</p>
    </div>
    <div class="stat-card">
        <h3>$3,240</h3>
        <p>Doanh Thu Tháng</p>
    </div>
    <div class="stat-card">
        <h3>128</h3>
        <p>Tổng Số Khách Hàng</p>
    </div>
    <div class="stat-card">
        <h3>24</h3>
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
            <tr>
                <td>#1001</td>
                <td>John Doe</td>
                <td>Jun 12, 2023</td>
                <td>$129.99</td>
                <td><span class="status-delivered">Delivered</span></td>
                <td><a href="/admin/index.php?controller=admin&action=orderDetail&id=1001" class="btn-view">Xem</a></td>
            </tr>
            <tr>
                <td>#1002</td>
                <td>Jane Smith</td>
                <td>Jun 11, 2023</td>
                <td>$89.99</td>
                <td><span class="status-shipped">Shipped</span></td>
                <td><a href="/admin/index.php?controller=admin&action=orderDetail&id=1002" class="btn-view">Xem</a></td>
            </tr>
            <tr>
                <td>#1003</td>
                <td>Robert Johnson</td>
                <td>Jun 10, 2023</td>
                <td>$199.99</td>
                <td><span class="status-processing">Processing</span></td>
                <td><a href="/admin/index.php?controller=admin&action=orderDetail&id=1003" class="btn-view">Xem</a></td>
            </tr>
            <tr>
                <td>#1004</td>
                <td>Emily Davis</td>
                <td>Jun 9, 2023</td>
                <td>$149.99</td>
                <td><span class="status-delivered">Delivered</span></td>
                <td><a href="/admin/index.php?controller=admin&action=orderDetail&id=1004" class="btn-view">Xem</a></td>
            </tr>
            <tr>
                <td>#1005</td>
                <td>Michael Wilson</td>
                <td>Jun 8, 2023</td>
                <td>$79.99</td>
                <td><span class="status-cancelled">Cancelled</span></td>
                <td><a href="/admin/index.php?controller=admin&action=orderDetail&id=1005" class="btn-view">Xem</a></td>
            </tr>
        </tbody>
    </table>
</div>

<?php require_once 'views/admin/components/admin_footer.php'; ?>