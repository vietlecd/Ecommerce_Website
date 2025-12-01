<?php require_once 'views/components/header.php'; ?>

<div class="section-title">
    <h2>My Account</h2>
</div>

<style>
    .account-form {
        max-width: 600px;
        margin: 40px auto;
        padding: 30px;
        background-color: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .account-form .form-group {
        margin-bottom: 20px;
    }

    .account-form label {
        display: block;
        font-weight: 500;
        color: #333;
        margin-bottom: 8px;
    }

    .account-form input {
        width: 100%;
        padding: 12px;
        border: 2px solid #e0e0e0;
        border-radius: 6px;
        box-sizing: border-box;
        font-size: 16px;
        transition: border-color 0.3s ease;
    }

    .account-form input:focus {
        border-color: #007bff;
        outline: none;
        box-shadow: 0 0 5px rgba(0, 123, 255, 0.3);
    }

    .account-form button {
        width: 100%;
        padding: 12px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-size: 16px;
        font-weight: 500;
        transition: background-color 0.3s ease;
    }

    .account-form button:hover {
        background-color: #0056b3;
    }

    .alert {
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 6px;
        text-align: center;
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

    .password-section {
        margin-top: 40px;
        border-top: 1px solid #e0e0e0;
        padding-top: 20px;
    }

    .password-section h3 {
        font-size: 18px;
        font-weight: 600;
        color: #333;
        margin-bottom: 20px;
    }
</style>

<div class="account-form">
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success">
            <?php echo htmlspecialchars($_SESSION['message']); ?>
            <?php unset($_SESSION['message']); ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php echo htmlspecialchars($_SESSION['error']); ?>
            <?php unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <!-- Form cập nhật thông tin -->
    <form method="post" action="">
        <div class="form-group">
            <label for="username">Username (cannot be changed)</label>
            <input type="text" id="username" value="<?php echo htmlspecialchars($user['Username']); ?>" disabled>
        </div>

        <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['Name'] ?? ''); ?>" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['Email'] ?? ''); ?>" required>
        </div>

        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($user['Phone'] ?? ''); ?>" required>
        </div>

        <button type="submit" name="update_info">Update Information</button>
    </form>

    <!-- Form đổi mật khẩu -->
    <div class="password-section">
        <h3>Change Password</h3>
        <form method="post" action="">
            <div class="form-group">
                <label for="current_password">Current Password</label>
                <input type="password" id="current_password" name="current_password" required>
            </div>

            <div class="form-group">
                <label for="new_password">New Password</label>
                <input type="password" id="new_password" name="new_password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm New Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>

            <button type="submit" name="change_password">Change Password</button>
        </form>
    </div>
</div>

<?php require_once 'views/components/footer.php'; ?>