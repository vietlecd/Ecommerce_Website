<div class="admin-header">
    <h1>Chi Tiết Khách Hàng</h1>
</div>

<?php if (empty($member)): ?>
    <p style="color: red; text-align: center;">Không tìm thấy thông tin khách hàng.</p>
<?php else: ?>
    <div style="max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; background-color: #f9f9f9;">
        <h2 style="font-size: 20px; margin-bottom: 20px; color: #333;">Thông Tin Khách Hàng</h2>
        <table style="width: 100%; border-collapse: collapse;">
            <tr style="border-bottom: 1px solid #ddd;">
                <td style="padding: 10px; font-weight: bold; width: 30%;">ID:</td>
                <td style="padding: 10px;"><?php echo htmlspecialchars($member['MemberID']); ?></td>
            </tr>
            <tr style="border-bottom: 1px solid #ddd;">
                <td style="padding: 10px; font-weight: bold;">Họ Tên:</td>
                <td style="padding: 10px;"><?php echo htmlspecialchars($member['Name']); ?></td>
            </tr>
            <tr style="border-bottom: 1px solid #ddd;">
                <td style="padding: 10px; font-weight: bold;">Email:</td>
                <td style="padding: 10px;"><?php echo htmlspecialchars($member['Email']); ?></td>
            </tr>
            <tr style="border-bottom: 1px solid #ddd;">
                <td style="padding: 10px; font-weight: bold;">Ngày Đăng Ký:</td>
                <td style="padding: 10px;"><?php echo date('M d, Y', strtotime($member['Exp_VIP'])); ?></td>
            </tr>
        </table>

        <div style="margin-top: 20px; text-align: center;">
            <a href="/index.php?controller=adminCustomer&action=customers" 
               style="display: inline-block; padding: 8px 16px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px; font-size: 14px; font-weight: bold; border: 1px solid #388E3C; transition: background-color 0.3s;"
               onmouseover="this.style.backgroundColor='#388E3C'" onmouseout="this.style.backgroundColor='#4CAF50'">Quay Lại</a>
            <a href="/index.php?controller=adminCustomer&action=resetPassword&id=<?php echo $member['MemberID']; ?>" 
               style="display: inline-block; padding: 8px 16px; background-color: #ff6b6b; color: white; text-decoration: none; border-radius: 5px; font-size: 14px; font-weight: bold; border: 1px solid #ff5252; transition: background-color 0.3s; margin-left: 10px;"
               onmouseover="this.style.backgroundColor='#ff5252'" onmouseout="this.style.backgroundColor='#ff6b6b'" 
               onclick="return confirm('Bạn có chắc muốn reset mật khẩu?')">Reset Mật Khẩu</a>
        </div>
    </div>
<?php endif; ?>

<?php 
$footerPath = dirname(__DIR__) . '/components/admin_footer.php';
if (file_exists($footerPath)) {
    require_once $footerPath;
} else {
    die("Footer file not found: $footerPath");
}
?>