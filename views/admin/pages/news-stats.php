<style>
    .news-stats table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }

    .news-stats th, .news-stats td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    .news-stats th {
        background-color: #f4f4f4;
    }

    .pagination {
        margin-top: 20px;
        text-align: center;
    }

    .pagination a {
        display: inline-block;
        padding: 8px 16px;
        margin: 0 5px;
        border: 1px solid #ddd;
        text-decoration: none;
        color: #333;
        border-radius: 5px;
    }

    .pagination a.active {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
    }

    .pagination a:hover:not(.active) {
        background-color: #f1f1f1;
    }
</style>

<div class="section-title">
    <h2>News Click Statistics</h2>
</div>

<form method="get" action="" class="form-container">
    <div class="form-group">
        <label for="search">Search News</label>
        <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search); ?>" placeholder="Enter keyword...">
        <input type="hidden" name="controller" value="adminNews">
        <input type="hidden" name="action" value="stats">
        <button type="submit" class="form-btn">Search</button>
    </div>
</form>

<div class="news-stats">
    <table>
        <thead>
            <tr>
                <th>News ID</th>
                <th>Title</th>
                <th>Click Count</th>
                <th>Last Clicked At</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($clickStats as $stat): ?>
                <tr>
                    <td><?php echo $stat['NewsID']; ?></td>
                    <td><?php echo htmlspecialchars($stat['Title']); ?></td>
                    <td><?php echo $stat['click_count'] ?? 0; ?></td>
                    <td><?php echo $stat['last_clicked_at'] ? date('d/m/Y H:i', strtotime($stat['last_clicked_at'])) : '-'; ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="pagination">
    <?php if ($totalPages > 1): ?>
        <?php if ($page > 1): ?>
            <a href="/index.php?controller=adminNews&action=stats&search=<?php echo urlencode($search); ?>&page=<?php echo $page - 1; ?>">Previous</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="/index.php?controller=adminNews&action=stats&search=<?php echo urlencode($search); ?>&page=<?php echo $i; ?>" <?php echo $i === $page ? 'class="active"' : ''; ?>><?php echo $i; ?></a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="/index.php?controller=adminNews&action=stats&search=<?php echo urlencode($search); ?>&page=<?php echo $page + 1; ?>">Next</a>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php require_once 'views/admin/components/admin_footer.php'; ?>