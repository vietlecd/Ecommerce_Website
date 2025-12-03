<?php
$sizeInputs = [];
if (!empty($_POST['sizes']) && is_array($_POST['sizes'])) {
    foreach ($_POST['sizes'] as $index => $sizeValue) {
        $quantityValue = $_POST['size_quantities'][$index] ?? '';
        $sizeInputs[] = [
            'size' => $sizeValue,
            'quantity' => $quantityValue,
        ];
    }
}
if (empty($sizeInputs)) {
    $sizeInputs[] = ['size' => '', 'quantity' => ''];
}

$nameValue = $_POST['name'] ?? '';
$priceValue = $_POST['price'] ?? '';
$descriptionValue = $_POST['description'] ?? '';
$categorySelected = $_POST['category_id'] ?? '';
?>

<div class="row g-4">
  <div class="col-12">
    <?php if (isset($error)): ?>
      <div class="alert alert-danger alert-dismissible mb-3">
        <div class="d-flex">
          <div><i class="ti ti-alert-triangle"></i></div>
          <div class="ms-3">
            <h4 class="alert-title">Không thể lưu sản phẩm</h4>
            <div class="text-secondary"><?php echo htmlspecialchars($error); ?></div>
          </div>
        </div>
        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
      </div>
    <?php endif; ?>
    <?php if (isset($success)): ?>
      <div class="alert alert-success alert-dismissible mb-3">
        <div class="d-flex">
          <div><i class="ti ti-check"></i></div>
          <div class="ms-3">
            <h4 class="alert-title">Thành công</h4>
            <div class="text-secondary"><?php echo htmlspecialchars($success); ?></div>
          </div>
        </div>
        <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
      </div>
    <?php endif; ?>
    <div class="card card-stacked">
      <div class="card-header">
        <div>
          <div class="card-title">Thêm sản phẩm mới</div>
          <div class="text-secondary">Điền đầy đủ thông tin để sản phẩm có thể xuất hiện ở trang bán hàng.</div>
        </div>
        <div class="ms-auto">
          <a href="/views/admin/index.php?controller=adminProduct&action=products" class="btn btn-outline-secondary">
            <i class="ti ti-arrow-left me-1"></i> Danh sách sản phẩm
          </a>
        </div>
      </div>
      <form action="/views/admin/index.php?controller=adminProduct&action=addProduct" method="post" enctype="multipart/form-data">
        <div class="card-body">
          <div class="row g-3">
            <div class="col-12 col-md-6">
              <label class="form-label">Tên sản phẩm</label>
              <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($nameValue); ?>" required>
            </div>
            <div class="col-12 col-md-3">
              <label class="form-label">Giá niêm yết</label>
              <input type="number" name="price" min="0" step="0.01" class="form-control" value="<?php echo htmlspecialchars($priceValue); ?>" required>
            </div>
            <div class="col-12 col-md-3">
              <label class="form-label">Danh mục</label>
              <select name="category_id" class="form-select" required>
                <option value="">-- Chọn danh mục --</option>
                <?php foreach ($categories as $category): ?>
                  <option value="<?php echo $category['id']; ?>" <?php echo (string)$category['id'] === (string)$categorySelected ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($category['name']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-12 col-lg-6">
              <label class="form-label">Ảnh đại diện</label>
              <input type="file" name="image" class="form-control" accept="image/png,image/jpeg,image/gif" required>
              <div class="form-hint">Dung lượng tối đa 5MB. Hỗ trợ JPG, PNG, GIF.</div>
            </div>
            <div class="col-12">
              <label class="form-label">Mô tả chi tiết</label>
              <textarea class="form-control" name="description" rows="6" required><?php echo htmlspecialchars($descriptionValue); ?></textarea>
            </div>
          </div>
          <hr class="my-4">
          <div class="row g-3 align-items-center">
            <div class="col">
              <h3 class="mb-1">Kích thước & tồn kho</h3>
              <p class="text-secondary mb-3">Khai báo từng size và số lượng để hệ thống tự động tính tồn kho.</p>
            </div>
            <div class="col-auto">
              <button type="button" class="btn btn-outline-primary" data-add-size>
                <i class="ti ti-plus me-1"></i> Thêm dòng size
              </button>
            </div>
          </div>
          <div class="list list-row" data-size-rows>
            <?php foreach ($sizeInputs as $row): ?>
              <div class="card mb-2 shadow-none">
                <div class="card-body py-2">
                  <div class="row g-2 align-items-center size-row">
                    <div class="col-5 col-md-3">
                      <label class="form-label">Size</label>
                      <input type="number" step="0.5" min="0" name="sizes[]" class="form-control" value="<?php echo htmlspecialchars($row['size']); ?>" required>
                    </div>
                    <div class="col-5 col-md-3">
                      <label class="form-label">Số lượng</label>
                      <input type="number" min="0" name="size_quantities[]" class="form-control" value="<?php echo htmlspecialchars($row['quantity']); ?>" required>
                    </div>
                    <div class="col-2 col-md-auto d-flex align-items-end">
                      <button type="button" class="btn btn-icon btn-outline-danger" data-remove-size aria-label="Xóa đường size">
                        <i class="ti ti-x"></i>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
          <div class="mt-2 text-secondary">
            <strong>Tổng tồn kho:</strong> <span data-total-stock>0</span> đôi
          </div>
        </div>
        <div class="card-footer text-end">
          <button type="submit" class="btn btn-primary">
            <i class="ti ti-device-floppy me-1"></i> Lưu sản phẩm
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const container = document.querySelector('[data-size-rows]');
  const addBtn = document.querySelector('[data-add-size]');
  const totalLabel = document.querySelector('[data-total-stock]');
  if (!container || !addBtn || !totalLabel) {
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
    totalLabel.textContent = total;
  };

  const attachRowEvents = (row) => {
    row.querySelectorAll('input').forEach(input => input.addEventListener('input', updateTotal));
    const removeBtn = row.querySelector('[data-remove-size]');
    if (removeBtn) {
      removeBtn.addEventListener('click', () => {
        if (container.children.length > 1) {
          row.remove();
          updateTotal();
        }
      });
    }
  };

  const createRow = (size = '', quantity = '') => {
    const outer = document.createElement('div');
    outer.className = 'card mb-2 shadow-none';
    outer.innerHTML = `
      <div class="card-body py-2">
        <div class="row g-2 align-items-center size-row">
          <div class="col-5 col-md-3">
            <label class="form-label">Size</label>
            <input type="number" step="0.5" min="0" name="sizes[]" class="form-control" value="${size}" required>
          </div>
          <div class="col-5 col-md-3">
            <label class="form-label">Số lượng</label>
            <input type="number" min="0" name="size_quantities[]" class="form-control" value="${quantity}" required>
          </div>
          <div class="col-2 col-md-auto d-flex align-items-end">
            <button type="button" class="btn btn-icon btn-outline-danger" data-remove-size aria-label="Xóa đường size">
              <i class="ti ti-x"></i>
            </button>
          </div>
        </div>
      </div>
    `;
    container.appendChild(outer);
    attachRowEvents(outer);
  };

  Array.from(container.children).forEach(attachRowEvents);
  addBtn.addEventListener('click', () => createRow());
  updateTotal();
});
</script>

<?php require_once __DIR__ . '/../components/admin_footer.php'; ?>
