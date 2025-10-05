<style>
    .admin-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .btn {
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        display: inline-block;
    }

    .btn:hover {
        background-color: #0056b3;
    }

    .search-form {
        margin-bottom: 20px;
    }

    .search-form input[type="text"] {
        padding: 10px;
        width: 300px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .search-form button {
        padding: 10px 20px;
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .qna-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .qna-table th,
    .qna-table td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .qna-table th {
        background-color: #f8f9fa;
        font-weight: bold;
    }

    .qna-table tr:hover {
        background-color: #f5f5f5;
    }

    .status-badge {
        padding: 4px 8px;
        border-radius: 3px;
        font-size: 12px;
        font-weight: bold;
    }

    .status-active {
        background-color: #d4edda;
        color: #155724;
    }

    .status-inactive {
        background-color: #f8d7da;
        color: #721c24;
    }

    .action-buttons a {
        margin-right: 10px;
        text-decoration: none;
        color: #007bff;
    }

    .action-buttons a:hover {
        text-decoration: underline;
    }

    .action-buttons .delete {
        color: #dc3545;
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

    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 4px;
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
    <h1>Manage Q&A</h1>
    <a href="/index.php?controller=adminQna&action=add" class="btn">Add New Q&A</a>
</div>

<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success">
        <?php echo htmlspecialchars($_SESSION['success']); ?>
        <?php unset($_SESSION['success']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger">
        <?php echo htmlspecialchars($_SESSION['error']); ?>
        <?php unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<form method="get" class="search-form">
    <input type="hidden" name="controller" value="adminQna">
    <input type="hidden" name="action" value="manage">
    <input type="text" name="search" placeholder="Search questions or answers..." value="<?php echo htmlspecialchars($search); ?>">
    <button type="submit">Search</button>
</form>

<table class="qna-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Question</th>
            <th>Answer Preview</th>
            <th>Order</th>
            <th>Status</th>
            <th>Created By</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($qnaList)): ?>
            <?php foreach ($qnaList as $qna): ?>
                <tr>
                    <td><?php echo htmlspecialchars($qna['QnaID']); ?></td>
                    <td><?php echo htmlspecialchars(substr($qna['Question'], 0, 80)); ?><?php echo strlen($qna['Question']) > 80 ? '...' : ''; ?></td>
                    <td><?php echo htmlspecialchars(substr($qna['Answer'], 0, 100)); ?><?php echo strlen($qna['Answer']) > 100 ? '...' : ''; ?></td>
                    <td><?php echo htmlspecialchars($qna['DisplayOrder']); ?></td>
                    <td>
                        <span class="status-badge <?php echo $qna['IsActive'] ? 'status-active' : 'status-inactive'; ?>">
                            <?php echo $qna['IsActive'] ? 'Active' : 'Inactive'; ?>
                        </span>
                    </td>
                    <td><?php echo htmlspecialchars($qna['CreatedByName'] ?? 'N/A'); ?></td>
                    <td class="action-buttons">
                        <a href="/index.php?controller=adminQna&action=edit&id=<?php echo $qna['QnaID']; ?>">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="/index.php?controller=adminQna&action=delete&id=<?php echo $qna['QnaID']; ?>" 
                           class="delete" 
                           onclick="return confirm('Are you sure you want to delete this Q&A?');">
                            <i class="fas fa-trash"></i> Delete
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="7" style="text-align: center; padding: 40px; color: #999;">
                    No Q&A items found.
                </td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php if ($totalPages > 1): ?>
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="/index.php?controller=adminQna&action=manage&search=<?php echo urlencode($search); ?>&page=<?php echo $page - 1; ?>">Previous</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="/index.php?controller=adminQna&action=manage&search=<?php echo urlencode($search); ?>&page=<?php echo $i; ?>" 
               <?php echo $i === $page ? 'class="active"' : ''; ?>>
                <?php echo $i; ?>
            </a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="/index.php?controller=adminQna&action=manage&search=<?php echo urlencode($search); ?>&page=<?php echo $page + 1; ?>">Next</a>
        <?php endif; ?>
    </div>
<?php endif; ?>
