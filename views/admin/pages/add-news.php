<style>
    .news-form .form-group {
        margin-bottom: 15px;
    }

    .news-form .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .news-form .form-group input,
    .news-form .form-group textarea,
    .news-form .form-group select {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }

    .news-form .form-group textarea {
        resize: vertical;
    }

    .news-form .form-group small {
        display: block;
        margin-top: 5px;
        color: #666;
    }

    .news-form button {
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .news-form button:hover {
        background-color: #0056b3;
    }
</style>

<div class="admin-header">
    <h1>Thêm Bài Viết Mới</h1>
    <a href="/index.php?controller=adminNews&action=manage" class="btn btn-secondary">Quay Lại</a>
</div>

<?php if (isset($error)): ?>
    <div class="alert alert-danger"><?php echo $error; ?></div>
<?php endif; ?>
<?php if (isset($success)): ?>
    <div class="alert alert-success"><?php echo $success; ?></div>
<?php endif; ?>

<form method="post" class="news-form" enctype="multipart/form-data">
    <div class="form-group">
        <label for="title">Tiêu Đề</label>
        <input type="text" id="title" name="title" required>
    </div>
    <div class="form-group">
        <label for="description">Mô Tả</label>
        <textarea id="description" name="description" required></textarea>
    </div>
    <div class="form-group">
        <label for="content">Nội Dung</label>
        <textarea id="content" name="content" rows="10" required></textarea>
    </div>
    <div class="form-group">
        <label for="news_type">Loại Tin Tức</label>
        <select id="news_type" name="news_type" required>
            <option value="general">Tin Tức Thông Thường</option>
            <option value="flash_sale">Sale sập sàn</option>
            <option value="fixed_price">Rẻ Vô Địch</option>
        </select>
    </div>
    <div class="form-group">
        <label for="promotion_id">Chương Trình Khuyến Mãi (nếu có)</label>
        <select id="promotion_id" name="promotion_id">
            <option value="">Không có</option>
            <?php foreach ($promotions as $promotion): ?>
                <option value="<?php echo $promotion['promotion_id']; ?>">
                    <?php echo htmlspecialchars($promotion['promotion_name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-group">
        <label for="thumbnail">Ảnh Thumbnail</label>
        <input type="file" id="thumbnail" name="thumbnail" accept="image/*">
        <small>Chỉ chấp nhận file ảnh (JPEG, PNG, GIF), tối đa 5MB.</small>
    </div>
    <button type="submit" name="add_news">Thêm Bài Viết</button>
</form>