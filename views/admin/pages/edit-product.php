<div class="admin-header">
    <h1>Sửa Sản Phẩm</h1>
</div>

<?php
$sizeInputs = [];
if (!empty($_POST['sizes']) && is_array($_POST['sizes'])) {
    foreach ($_POST['sizes'] as $index => $sizeValue) {
        $quantityValue = $_POST['size_quantities'][$index] ?? '';
        $sizeInputs[] = [
            'size' => $sizeValue,
            'quantity' => $quantityValue
        ];
    }
} elseif (!empty($product['sizes'])) {
    foreach ($product['sizes'] as $row) {
        $sizeInputs[] = [
            'size' => $row['size'],
            'quantity' => $row['quantity']
        ];
    }
}
if (empty($sizeInputs)) {
    $sizeInputs[] = ['size' => '', 'quantity' => ''];
}
?>

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
            <label>Kích thước & số lượng</label>
            <div class="size-rows" data-size-rows>
                <?php foreach ($sizeInputs as $row): ?>
                    <div class="size-row">
                        <input type="number" step="0.5" name="sizes[]" placeholder="Size" value="<?php echo htmlspecialchars($row['size']); ?>" required>
                        <input type="number" min="0" name="size_quantities[]" placeholder="Số lượng" value="<?php echo htmlspecialchars($row['quantity']); ?>" required>
                        <button type="button" class="btn-remove-size" aria-label="Xóa size">&times;</button>
                    </div>
                <?php endforeach; ?>
            </div>
            <button type="button" class="btn btn-secondary" data-add-size>Thêm size</button>
            <div class="form-hint">Tổng tồn kho sẽ được tính từ bảng size.</div>
            <div class="total-stock" data-total-stock>Tổng tồn kho: 0</div>
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

<style>
.size-rows {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}
.size-row {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}
.size-row input {
    flex: 1;
}
.btn-remove-size {
    border: none;
    background: #f3f3f3;
    color: #333;
    padding: 0.35rem 0.65rem;
    font-size: 1rem;
    cursor: pointer;
    border-radius: 4px;
}
.btn-remove-size:hover {
    background: #e0e0e0;
}
.total-stock {
    margin-top: 0.5rem;
    font-weight: 600;
}
.btn.btn-secondary {
    margin-top: 0.5rem;
    background: #f5f5f5;
    color: #333;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.querySelector('[data-size-rows]');
    const addBtn = document.querySelector('[data-add-size]');
    const totalLabel = document.querySelector('[data-total-stock]');
    if (!container) {
        return;
    }

    const updateTotal = () => {
        const quantities = container.querySelectorAll('input[name="size_quantities[]"]');
        let total = 0;
        quantities.forEach(input => {
            const value = parseInt(input.value, 10);
            if (!isNaN(value)) {
                total += value;
            }
        });
        if (totalLabel) {
            totalLabel.textContent = 'Tổng tồn kho: ' + total;
        }
    };

    const attachRowEvents = (row) => {
        row.querySelectorAll('input').forEach(input => input.addEventListener('input', updateTotal));
        const removeBtn = row.querySelector('.btn-remove-size');
        if (removeBtn) {
            removeBtn.addEventListener('click', () => {
                if (container.children.length > 1) {
                    row.remove();
                    updateTotal();
                }
            });
        }
    };

    const addRow = (size = '', quantity = '') => {
        const row = document.createElement('div');
        row.className = 'size-row';
        row.innerHTML = `
            <input type="number" step="0.5" name="sizes[]" placeholder="Size" value="${size}" required>
            <input type="number" min="0" name="size_quantities[]" placeholder="Số lượng" value="${quantity}" required>
            <button type="button" class="btn-remove-size" aria-label="Xóa size">&times;</button>
        `;
        container.appendChild(row);
        attachRowEvents(row);
        updateTotal();
    };

    container.querySelectorAll('.size-row').forEach(attachRowEvents);
    addBtn?.addEventListener('click', () => addRow());
    updateTotal();
});
</script>
