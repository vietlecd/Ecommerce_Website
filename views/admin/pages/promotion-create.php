<?php require 'views/admin/components/header.php'; ?>

<div class="section-title">
    <h2>Create New Promotion</h2>
</div>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-error">
        <?php echo htmlspecialchars($_SESSION['error']); ?>
        <?php unset($_SESSION['error']); ?>
    </div>
<?php endif; ?>

<div class="promotion-create">
    <form method="post" action="">
        <div class="form-group">
            <label for="promotion_name">Promotion Name</label>
            <input type="text" id="promotion_name" name="promotion_name" required>
        </div>

        <div class="form-group">
            <label for="start_date">Start Date</label>
            <input type="datetime-local" id="start_date" name="start_date" required>
        </div>

        <div class="form-group">
            <label for="end_date">End Date</label>
            <input type="datetime-local" id="end_date" name="end_date" required>
        </div>

        <div class="form-group">
            <label for="promotion_type">Promotion Type</label>
            <select id="promotion_type" name="promotion_type" onchange="togglePromotionFields()">
                <option value="discount">Discount Percentage</option>
                <option value="fixed">Fixed Price</option>
            </select>
        </div>

        <div id="discount_fields" class="promotion-fields">
            <div class="form-group">
                <label for="discount_percentage">Discount Percentage (%)</label>
                <input type="number" id="discount_percentage" name="discount_percentage" step="0.01" min="0" max="100">
            </div>
        </div>

        <div id="fixed_fields" class="promotion-fields" style="display: none;">
            <div class="form-group">
                <label for="fixed_price">Fixed Price ($)</label>
                <input type="number" id="fixed_price" name="fixed_price" step="0.01" min="0">
            </div>
        </div>

        <button type="submit" class="btn">Create Promotion</button>
    </form>
</div>

<script>
function togglePromotionFields() {
    const promotionType = document.getElementById('promotion_type').value;
    document.getElementById('discount_fields').style.display = promotionType === 'discount' ? 'block' : 'none';
    document.getElementById('fixed_fields').style.display = promotionType === 'fixed' ? 'block' : 'none';
}
</script>

<?php require 'views/admin/components/admin_footer.php'; ?>