<div class="page-header d-print-none mb-4">
    <div class="row align-items-center">
        <div class="col">
            <div class="page-pretitle text-secondary">Overview</div>
            <h2 class="page-title">Dashboard</h2>
        </div>
        <div class="col-auto ms-auto">
            <div class="text-secondary small"><?php echo date('F d, Y'); ?></div>
        </div>
    </div>
</div>

<div class="row row-cards mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Total Orders</div>
                </div>
                <div class="h1 mb-3"><?php echo number_format($totalOrders); ?></div>
                <div class="d-flex mb-2">
                    <div class="text-secondary">All time orders</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Monthly Revenue</div>
                </div>
                <div class="h1 mb-3">$<?php echo number_format($monthlyRevenue, 2); ?></div>
                <div class="d-flex mb-2">
                    <div class="text-secondary">Current month</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Total Customers</div>
                </div>
                <div class="h1 mb-3"><?php echo number_format($totalCustomers); ?></div>
                <div class="d-flex mb-2">
                    <div class="text-secondary">Registered members</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="subheader">Total Products</div>
                </div>
                <div class="h1 mb-3"><?php echo number_format($totalProducts); ?></div>
                <div class="d-flex mb-2">
                    <div class="text-secondary">Available products</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Recent Orders</h3>
    </div>
    <div class="table-responsive">
        <table class="table table-vcenter card-table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th class="w-1"></th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($recentOrders)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-secondary py-4">No orders found</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($recentOrders as $order): ?>
                        <tr>
                            <td>#<?php echo htmlspecialchars($order['OrderID']); ?></td>
                            <td>
                                <div><?php echo htmlspecialchars($order['customer_name'] ?? 'Guest'); ?></div>
                                <?php if (!empty($order['Email'])): ?>
                                    <div class="text-secondary text-xs"><?php echo htmlspecialchars($order['Email']); ?></div>
                                <?php endif; ?>
                            </td>
                            <td><?php echo date('M d, Y', strtotime($order['Date'])); ?></td>
                            <td>$<?php echo number_format((float)$order['Total_price'], 2); ?></td>
                            <td>
                                <?php
                                $status = $order['Status'] ?? 'Pending';
                                $statusClass = 'bg-secondary';
                                if ($status === 'Delivered') $statusClass = 'bg-success';
                                elseif ($status === 'Shipped') $statusClass = 'bg-info';
                                elseif ($status === 'Processing') $statusClass = 'bg-warning';
                                elseif ($status === 'Cancelled') $statusClass = 'bg-danger';
                                ?>
                                <span class="badge <?php echo $statusClass; ?>"><?php echo htmlspecialchars($status); ?></span>
                            </td>
                            <td>
                                <a href="/index.php?controller=adminOrder&action=orderDetail&id=<?php echo $order['OrderID']; ?>" class="btn btn-sm btn-outline-primary">
                                    View
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once 'views/admin/components/admin_footer.php'; ?>