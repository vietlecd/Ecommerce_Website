<div class="admin-header">
    <h1>Thêm Sản Phẩm Mới</h1>
</div>

<?php if (isset($error)): ?>
    <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
<?php if (isset($success)): ?>
    <div class="alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<div class="admin-form">
    <form action="/views/admin/index.php?controller=adminProduct&action=addProduct" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Tên Sản Phẩm</label>
            <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="price">Giá</label>
            <input type="number" id="price" name="price" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="stock">Số lượng tồn kho</label>
            <input type="number" id="stock" name="stock" required>
        </div>
        <div class="form-group">
            <label for="image">Hình Ảnh</label>
            <input type="file" id="image" name="image" required>
        </div>
        <div class="form-group">
            <label for="description">Mô Tả</label>
            <textarea id="description" name="description" required></textarea>
        </div>
        <div class="form-group">
            <label for="shoes_size">Kích thước giày</label>
            <input type="text" id="shoes_size" name="shoes_size" required>
        </div>
        <div class="form-group">
            <label for="category_id">Danh Mục</label>
            <select id="category_id" name="category_id" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn">Thêm Sản Phẩm</button>
    </form>
</div>
