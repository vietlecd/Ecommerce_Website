<style>
    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
    }

    .admin-header h1 {
        font-size: 24px;
        font-weight: 600;
        color: #333;
    }

    .admin-actions {
        display: flex;
        gap: 10px;
        margin-bottom: 20px;
    }

    .btn {
        padding: 10px 20px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        transition: background-color 0.3s ease;
    }

    .btn-primary {
        background-color: #007bff;
        color: white;
        border: none;
    }

    .btn-primary:hover {
        background-color: #0056b3;
    }

    .btn-secondary {
        background-color: #6c757d;
        color: white;
        border: none;
    }

    .btn-secondary:hover {
        background-color: #5a6268;
    }

    .btn-danger {
        background-color: #dc3545;
        color: white;
        border: none;
    }

    .btn-danger:hover {
        background-color: #c82333;
    }

    .search-form {
        display: flex;
        gap: 15px;
        margin-bottom: 30px;
        align-items: center;
    }

    .search-form input[type="text"],
    .search-form select {
        padding: 10px;
        border: 2px solid #e0e0e0;
        border-radius: 6px;
        font-size: 14px;
        width: 200px;
        transition: border-color 0.3s ease;
    }

    .search-form input[type="text"]:focus,
    .search-form select:focus {
        border-color: #007bff;
        outline: none;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
    }

    .search-form button {
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        font-weight: 500;
        transition: background-color 0.3s ease;
    }

    .search-form button:hover {
        background-color: #0056b3;
    }

    .admin-table {
        width: 100%;
        border-collapse: collapse;
        background-color: #ffffff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        overflow: hidden;
    }

    .admin-table th,
    .admin-table td {
        padding: 15px;
        text-align: left;
        border-bottom: 1px solid #e0e0e0;
    }

    .admin-table th {
        background-color: #f8f9fa;
        font-weight: 600;
        color: #333;
    }

    .admin-table tbody tr:hover {
        background-color: #f1f3f5;
    }

    .admin-table img {
        max-width: 50px;
        border-radius: 5px;
        object-fit: cover;
    }

    .admin-table .action-buttons {
        display: flex;
        gap: 10px;
    }

    .admin-table .action-buttons a {
        padding: 8px 15px;
        font-size: 13px;
        border-radius: 5px;
        text-align: center;
        min-width: 70px;
    }

    .pagination {
        margin-top: 30px;
        text-align: center;
        display: flex;
        justify-content: center;
        gap: 8px;
    }

    .pagination a {
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: 5px;
        text-decoration: none;
        color: #333;
        font-size: 14px;
        transition: background-color 0.3s ease;
    }

    .pagination a.active {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
    }

    .pagination a:hover:not(.active) {
        background-color: #e9ecef;
    }

    .status-pending {
        color: #ff8c00;
        font-weight: 500;
    }

    .status-active {
        color: #28a745;
        font-weight: 500;
    }

    .status-expired {
        color: #dc3545;
        font-weight: 500;
    }

    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 6px;
        text-align: center;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }
</style>

<div class="admin-header">
    <h1>Quản Lý Tin Tức</h1>
    <a href="/index.php?controller=adminNews&action=stats" class="btn btn-secondary">Thống Kê Truy Cập</a>
</div>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
<?php if (isset($success)): ?>
    <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<div class="admin-actions">
    <a href="/index.php?controller=adminNews&action=addNews" class="btn btn-primary">Thêm Bài Viết Mới</a>
    <a href="/index.php?controller=adminPromotion&action=index" class="btn btn-primary">Chỉnh Sửa Khuyến Mãi</a>
</div>

<form method="get" class="search-form">
    <input type="hidden" name="controller" value="adminNews">
    <input type="hidden" name="action" value="manage">
    <input type="text" name="search" placeholder="Tìm kiếm bài viết..." value="<?php echo htmlspecialchars($search); ?>">
    <select name="status">
        <option value="all" <?php echo $status === 'all' ? 'selected' : ''; ?>>Tất Cả</option>
        <option value="pending" <?php echo $status === 'pending' ? 'selected' : ''; ?>>Pending</option>
        <option value="active" <?php echo $status === 'active' ? 'selected' : ''; ?>>Active</option>
        <option value="expired" <?php echo $status === 'expired' ? 'selected' : ''; ?>>Expired</option>
    </select>
    <button type="submit">Tìm Kiếm</button>
</form>

<table class="table admin-table">
    <thead>
        <tr>
            <th>Thumbnail</th>
            <th>Tiêu Đề</th>
            <th>Mô Tả</th>
            <th>Loại Tin Tức</th>
            <th>Khuyến Mãi</th>
            <th>Người Đăng</th>
            <th>Ngày Đăng</th>
            <th>Status</th>
            <th>Hành Động</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($news)): ?>
            <tr>
                <td colspan="9">Không tìm thấy bài viết nào.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($news as $item): ?>
                <tr>
                    <td>
                        <?php if ($item['thumbnail'] && file_exists($item['thumbnail'])): ?>
                            <img src="/<?php echo htmlspecialchars($item['thumbnail']); ?>" alt="Thumbnail">
                        <?php else: ?>
                            Không có ảnh
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($item['Title']); ?></td>
                    <td><?php echo htmlspecialchars($item['Description']); ?></td>
                    <td>
                        <?php
                        $news_types = [
                            'general' => 'Tin Tức Thông Thường',
                            'flash_sale' => 'Sale Sập Sàn',
                            'fixed_price' => 'Rẻ Vô Địch',
                        ];
                        echo $news_types[$item['news_type']] ?? 'Không xác định';
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($item['promotion_name'] ?? 'Không có'); ?></td>
                    <td><?php echo htmlspecialchars($item['AdminName'] ?? 'Unknown'); ?></td>
                    <td><?php echo date('d/m/Y H:i', strtotime($item['DateCreated'])); ?></td>
                    <td>
                        <?php
                        if ($item['promotion_id']) {
                            $now = new DateTime();
                            $startDate = new DateTime($item['start_date']);
                            $endDate = new DateTime($item['end_date']);
                            if ($now < $startDate) {
                                echo '<span class="status-pending">Pending</span>';
                            } elseif ($now >= $startDate && $now <= $endDate) {
                                echo '<span class="status-active">Active</span>';
                            } else {
                                echo '<span class="status-expired">Expired</span>';
                            }
                        } else {
                            echo '-';
                        }
                        ?>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="/index.php?controller=adminNews&action=editNews&id=<?php echo $item['NewsID']; ?>" class="btn btn-primary">Sửa</a>
                            <a href="/index.php?controller=adminNews&action=deleteNews&id=<?php echo $item['NewsID']; ?>" class="btn btn-danger" onclick="return confirm('Bạn có chắc muốn xóa bài viết này?')">Xóa</a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<div class="pagination">
    <?php if ($totalPages > 1): ?>
        <?php if ($page > 1): ?>
            <a href="/index.php?controller=adminNews&action=manage&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>&page=<?php echo $page - 1; ?>">Trang Trước</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="/index.php?controller=adminNews&action=manage&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>&page=<?php echo $i; ?>" class="<?php echo $i === $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="/index.php?controller=adminNews&action=manage&search=<?php echo urlencode($search); ?>&status=<?php echo urlencode($status); ?>&page=<?php echo $page + 1; ?>">Trang Sau</a>
        <?php endif; ?>
    <?php endif; ?>
</div>