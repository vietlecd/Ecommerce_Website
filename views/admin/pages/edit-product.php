<div class="admin-header">
    <h1>Sửa Sản Phẩm</h1>
</div>

<?php if (isset($error)): ?>
    <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
<?php if (isset($success)): ?>
    <div class="alert-success"><?php echo htmlspecialchars($success); ?></div>
<?php endif; ?>

<div class="admin-form">
    <form action="/views/admin/index.php?controller=adminProduct&action=editProduct&id=<?php echo $product_id; ?>" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Tên Sản Phẩm</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="price">Giá</label>
            <input type="number" id="price" name="price" step="0.01" value="<?php echo $product['price'] ?? ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="stock">Số lượng tồn kho</label>
            <input type="number" id="stock" name="stock" value="<?php echo $product['Stock'] ?? ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="image">Hình Ảnh (để trống nếu không thay đổi)</label>
            <input type="file" id="image" name="image">
            <?php if (isset($product['image'])): ?>
                <p>Hình ảnh hiện tại: <img src="/assets/images/<?php echo htmlspecialchars($product['image']); ?>" alt="Current Image" width="50"></p>
            <?php endif; ?>
        </div>
        <div class="form-group">
            <label for="description">Mô Tả</label>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($product['description'] ?? ''); ?></textarea>
        </div>
        <div class="form-group">
            <label for="shoes_size">Kích thước giày</label>
            <input type="text" id="shoes_size" name="shoes_size" value="<?php echo htmlspecialchars($product['shoes_size'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="category_id">Danh Mục</label>
            <select id="category_id" name="category_id" required>
                <?php foreach ($categories as $category): ?>
                    <option value="<?php echo $category['id']; ?>" <?php echo (isset($product['category_id']) && $category['id'] == $product['category_id']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($category['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn">Cập Nhật Sản Phẩm</button>
    </form>
</div>
