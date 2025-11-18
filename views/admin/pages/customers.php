<div class="admin-header">
    <h1>Khách Hàng</h1>
</div>

<table class="admin-table" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
    <thead>
        <tr style="background-color: #f4f4f4; color: #333;">
            <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">ID</th>
            <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Họ Tên</th>
            <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Email</th>
            <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Ngày Đăng Ký</th>
            <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Số Đơn Hàng</th>
            <th style="padding: 10px; border: 1px solid #ddd; text-align: left;">Hành Động</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($members)): ?>
            <tr>
                <td colspan="6" style="text-align: center; padding: 20px; border: 1px solid #ddd;">Không có khách hàng nào.</td>
            </tr>
        <?php else: ?>
            <?php foreach ($members as $member): ?>
                <tr style="border-bottom: 1px solid #ddd;">
                    <td style="padding: 10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($member['MemberID']); ?></td>
                    <td style="padding: 10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($member['Name']); ?></td>
                    <td style="padding: 10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($member['Email']); ?></td>
                    <td style="padding: 10px; border: 1px solid #ddd;"><?php echo date('M d, Y', strtotime($member['Exp_VIP'])); ?></td>
                    <td style="padding: 10px; border: 1px solid #ddd;"><?php echo htmlspecialchars($member['OrderCount']); ?></td>
                    <td style="padding: 10px; border: 1px solid #ddd;">
                        <a href="/index.php?controller=adminCustomer&action=customerDetail&id=<?php echo $member['MemberID']; ?>" 
                           style="display: inline-block; padding: 6px 12px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px; font-size: 14px; font-weight: bold; border: 1px solid #388E3C; transition: background-color 0.3s; margin-right: 5px;"
                           onmouseover="this.style.backgroundColor='#388E3C'" onmouseout="this.style.backgroundColor='#4CAF50'">Xem</a>
                        <a href="/index.php?controller=adminCustomer&action=resetPassword&id=<?php echo $member['MemberID']; ?>" 
                           style="display: inline-block; padding: 6px 12px; background-color: #ff6b6b; color: white; text-decoration: none; border-radius: 5px; font-size: 14px; font-weight: bold; border: 1px solid #ff5252; transition: background-color 0.3s;"
                           onmouseover="this.style.backgroundColor='#ff5252'" onmouseout="this.style.backgroundColor='#ff6b6b'" 
                           onclick="return confirm('Bạn có chắc muốn reset mật khẩu?')">Reset MK</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
    </tbody>
</table>

<?php 
$footerPath = dirname(__DIR__) . '/components/admin_footer.php';
if (file_exists($footerPath)) {
    require_once $footerPath;
} else {
    die("Footer file not found: $footerPath");
}
?>