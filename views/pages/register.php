<div class="section-title">
    <h2>Đăng Ký</h2>
</div>

<?php if (isset($error)): ?>
    <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
<?php if (isset($success)): ?>
    <div class="alert-success"><?php echo htmlspecialchars($success); ?></div>
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
    <div class="form-group">
        <label for="name">Họ Tên</label>
        <input type="text" id="name" name="name" required>
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required>
    </div>
    <div class="form-group">
        <label for="phone">Số Điện Thoại</label>
        <input type="text" id="phone" name="phone" required>
    </div>
    <button type="submit" class="form-btn">Đăng Ký</button>
</form>

<p>Đã có tài khoản? <a href="/index.php?controller=auth&action=login">Đăng nhập ngay</a></p>

<?php require_once 'views/components/footer.php'; ?>