# PayOS Integration Guide

## Tích hợp thanh toán PayOS vào dự án

### 1. Đăng ký tài khoản PayOS

1. Truy cập: https://my.payos.vn/
2. Đăng ký tài khoản merchant
3. Lấy thông tin xác thực:
   - **Client ID**
   - **API Key**
   - **Checksum Key**

### 2. Cấu hình

#### Option 1: Sử dụng file .env (Khuyến nghị)

Tạo file `.env` trong thư mục root và thêm:

```env
PAYOS_CLIENT_ID=your_client_id_here
PAYOS_API_KEY=your_api_key_here
PAYOS_CHECKSUM_KEY=your_checksum_key_here
PAYOS_ENV=sandbox
APP_URL=http://localhost:8080
```

#### Option 2: Sửa trực tiếp file config

Mở file `config/payos.php` và thay thế các giá trị:

```php
return [
    'client_id' => 'your_client_id_here',
    'api_key' => 'your_api_key_here',
    'checksum_key' => 'your_checksum_key_here',
    'environment' => 'sandbox', // hoặc 'production'
];
```

### 3. Cấu hình Webhook trên PayOS Dashboard

1. Đăng nhập vào PayOS Dashboard
2. Vào phần **Settings** → **Webhook**
3. Thêm Webhook URL:
   ```
   http://your-domain.com/index.php?controller=checkout&action=payos_webhook
   ```
4. Lưu lại

**Lưu ý**: Để test local, bạn cần expose localhost ra internet bằng:
- ngrok: `ngrok http 8080`
- Sử dụng ngrok container đã có trong docker-compose.yml

### 4. Test thanh toán

1. Truy cập trang checkout: http://localhost:8080/index.php?controller=checkout&action=index
2. Chọn phương thức thanh toán **"PayOS - Thanh toán QR"**
3. Điền thông tin giao hàng
4. Click **"Place Order"**
5. Bạn sẽ được chuyển đến trang PayOS để quét QR code thanh toán

### 5. Flow thanh toán

```
User → Checkout → PayOS → QR Code → User scan & pay → Webhook → Order confirmed
```

### 6. Testing với Sandbox

PayOS cung cấp môi trường sandbox để test:
- Không cần thanh toán thực
- Có thể test các trạng thái: SUCCESS, FAILED, PENDING

### 7. Files được tạo

- `models/PayOSService.php` - Service class xử lý PayOS API
- `config/payos.php` - File cấu hình
- `controllers/CheckoutController.php` - Đã được update với PayOS logic
- `views/pages/checkout.php` - Đã thêm option PayOS

### 8. API Endpoints

#### Return URL (sau khi thanh toán)
```
GET /index.php?controller=checkout&action=payos_return
```

#### Cancel URL (khi user hủy)
```
GET /index.php?controller=checkout&action=payos_cancel
```

#### Webhook URL (nhận thông báo từ PayOS)
```
POST /index.php?controller=checkout&action=payos_webhook
```

### 9. Troubleshooting

**Lỗi: "CURL Error"**
- Kiểm tra PHP có bật extension curl: `php -m | grep curl`
- Enable trong php.ini: `extension=curl`

**Lỗi: "Invalid signature"**
- Kiểm tra lại CHECKSUM_KEY
- Đảm bảo không có khoảng trắng thừa trong config

**Webhook không nhận được**
- Kiểm tra URL webhook đã config đúng trên PayOS
- Đảm bảo server có thể nhận request từ internet
- Check logs trong container: `docker logs shoes_web`

### 10. Chuyển sang Production

1. Đổi `PAYOS_ENV` từ `sandbox` sang `production`
2. Cập nhật credentials production từ PayOS dashboard
3. Cập nhật `APP_URL` thành domain thật
4. Cấu hình webhook URL production

### 11. Bảo mật

- ✅ Signature verification cho webhook
- ✅ HTTPS cho production (khuyến nghị)
- ✅ Không commit credentials vào git
- ✅ Sử dụng environment variables

---

## Support

- PayOS Documentation: https://payos.vn/docs/
- PayOS Support: support@payos.vn
