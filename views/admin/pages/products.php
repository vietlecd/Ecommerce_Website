<div class="admin-header">
    <h1>Sản Phẩm</h1>
    <a href="/views/admin/index.php?controller=adminProduct&action=addProduct" class="btn">Thêm Sản Phẩm Mới</a>
</div>

<!-- Form tìm kiếm -->
<div class="search-form" style="margin: 20px 0; text-align: center;">
    <form action="/views/admin/index.php?controller=adminProduct&action=products" method="get" style="display: inline-block;">
        <input type="hidden" name="controller" value="adminProduct">
        <input type="hidden" name="action" value="products">
        <input type="text" name="keyword" placeholder="Search products..." value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>" style="padding: 10px; width: 300px; border: 1px solid #ccc; border-radius: 4px;">
        <button type="submit" style="padding: 10px 20px; background-color: #ff6b6b#; color: white; border: none; border-radius: 4px; cursor: pointer;"
                onmouseover="this.style.backgroundColor='#ff5252'"
                onmouseout="this.style.backgroundColor='#ff6b6b'">Tìm kiếm</button>
    </form>
</div>

<table class="admin-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Hình Ảnh</th>
            <th>Tên</th>
            <th>Giá</th>
            <th>Danh Mục</th>
            <th>Hành Động</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($products)): ?>
            <tr>
                <td colspan="6" style="text-align: center;">Không tìm thấy sản phẩm.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['id']); ?></td>
                    <td>
                    <?php $imageUrl = !empty($product['image']) && filter_var($product['image'], FILTER_VALIDATE_URL) ? htmlspecialchars($product['image']) : '/public/placeholder.jpg';
                    echo '<img src="' . $imageUrl . '" alt="' . htmlspecialchars($product['name']) . '" loading="lazy" width="80px">' ;?>
                    </td>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td>$<?php echo number_format($product['price'], 2); ?></td>
                    <td><?php echo htmlspecialchars($product['category']); ?></td>
                    <td>
                        <a href="/views/admin/index.php?controller=adminProduct&action=editProduct&id=<?php echo $product['id']; ?>" class="btn-edit">Sửa</a>
                        <a href="/views/admin/index.php?controller=adminProduct&action=deleteProduct&id=<?php echo $product['id']; ?>" class="btn-delete" onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này?')">Xóa</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?php if (!empty($products) && $totalPages > 1): ?>
        <div class="pagination" style="margin: 20px 0; text-align: center;">
            <?php
            $baseUrl = '/views/admin/index.php?controller=adminProduct&action=products';
            if (!empty($keyword)) $baseUrl .= '&keyword=' . urlencode($keyword);
            if (!empty($category)) $baseUrl .= '&category=' . urlencode($category);

            // Nút Previous
            $prevPage = $currentPage > 1 ? $currentPage - 1 : 1;
            echo '<a href="' . $baseUrl . '&page=' . $prevPage . '" 
                     style="display: inline-block; padding: 8px 12px; margin: 0 5px; text-decoration: none; color: #ff6b6b; border: 1px solid #ddd; border-radius: 4px; ' . ($currentPage === 1 ? 'color: #ccc; pointer-events: none; border-color: #ccc;' : '') . '"
                     onmouseover="' . ($currentPage === 1 ? '' : 'this.style.backgroundColor=\'#f5f5f5\'') . '"
                     onmouseout="' . ($currentPage === 1 ? '' : 'this.style.backgroundColor=\'\'') . '">Previous</a>';

            // Các số trang
            for ($i = 1; $i <= $totalPages; $i++) {
                echo '<a href="' . $baseUrl . '&page=' . $i . '" 
                         style="display: inline-block; padding: 8px 12px; margin: 0 5px; text-decoration: none; color: #ff6b6b; border: 1px solid #ddd; border-radius: 4px; ' . ($currentPage === $i ? 'background-color: #ff6b6b; color: white; border-color: #ff6b6b;' : '') . '"
                         onmouseover="' . ($currentPage === $i ? '' : 'this.style.backgroundColor=\'#f5f5f5\'') . '"
                         onmouseout="' . ($currentPage === $i ? '' : 'this.style.backgroundColor=\'\'') . '">' . $i . '</a>';
            }

            // Nút Next
            $nextPage = $currentPage < $totalPages ? $currentPage + 1 : $totalPages;
            echo '<a href="' . $baseUrl . '&page=' . $nextPage . '" 
                     style="display: inline-block; padding: 8px 12px; margin: 0 5px; text-decoration: none; color: #ff6b6b; border: 1px solid #ddd; border-radius: 4px; ' . ($currentPage === $totalPages ? 'color: #ccc; pointer-events: none; border-color: #ccc;' : '') . '"
                     onmouseover="' . ($currentPage === $totalPages ? '' : 'this.style.backgroundColor=\'#f5f5f5\'') . '"
                     onmouseout="' . ($currentPage === $totalPages ? '' : 'this.style.backgroundColor=\'\'') . '">Next</a>';
            ?>
        </div>
    <?php endif; ?>
</div>

