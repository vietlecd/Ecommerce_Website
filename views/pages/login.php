<div class="section-title">
    <h2>Đăng Nhập</h2>
</div>

<?php if (isset($error)): ?>
    <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>

<form method="post" class="form-container">
    <div class="form-group">
        <label for="username">Tên Người Dùng</label>
        <input type="text" id="username" name="username" required>
    </div>
    <div class="form-group">
        <label for="password">Mật Khẩu</label>
        <input type="password" id="password" name="password" required>
    </div>
    <button type="submit" class="form-btn">Đăng Nhập</button>
</form>

<p>Chưa có tài khoản? <a href="/index.php?controller=auth&action=register">Đăng ký ngay</a></p>

<?php require_once 'views/components/footer.php'; ?>