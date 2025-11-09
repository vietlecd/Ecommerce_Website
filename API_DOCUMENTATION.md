# API Documentation - Products

## Cấu hình

**Base URL:** https://unflayed-aron-overtrustful.ngrok-free.dev/

## Lấy danh sách sản phẩm

### Endpoint

```
GET {BASE_URL}/index.php?controller=products&action=api
```

**Ví dụ:**
```
GET {BASE_URL}/index.php?controller=products&action=api
```

### Method

`GET`

### Mô tả

API này cho phép lấy danh sách sản phẩm với các tính năng:
- Tìm kiếm theo tên giày
- Lọc theo category
- Lọc theo khoảng giá (min - max)
- Phân trang kết quả
- Trả về giá gốc và giá sau khuyến mãi

### Query Parameters

| Parameter | Type | Required | Default | Mô tả |
|-----------|------|----------|---------|-------|
| `keyword` | string | No | - | Từ khóa tìm kiếm trong tên và mô tả sản phẩm |
| `category` | string | No | - | Tên category để lọc (phải khớp chính xác) |
| `min_price` | float | No | - | Giá tối thiểu (áp dụng cho final_price sau khuyến mãi) |
| `max_price` | float | No | - | Giá tối đa (áp dụng cho final_price sau khuyến mãi) |
| `page` | integer | No | 1 | Số trang (bắt đầu từ 1) |
| `limit` | integer | No | 20 | Số sản phẩm trên mỗi trang |

### Danh sách Category hợp lệ

Dựa trên dữ liệu từ database table `category`, có **13 categories** với **10 category names khác nhau**:

| CategoryID | Category Name | Mô tả |
|------------|---------------|-------|
| 1 | `Sneakers` | Giày thể thao |
| 2 | `Boots` | Giày boot |
| 3 | `Sandals` | Dép sandal |
| 4 | `Running` | Giày chạy bộ |
| 5 | `Sneakers` | Giày sneaker thời trang |
| 6 | `Boots` | Giày bốt nam/nữ |
| 7 | `Sandals` | Giày dép sandal |
| 8 | `Formal` | Giày tây công sở |
| 9 | `Slippers` | Dép đi trong nhà |
| 10 | `Basketball` | Giày bóng rổ |
| 11 | `Soccer` | Giày đá bóng |
| 12 | `Skateboarding` | Giày trượt ván |
| 13 | `Casual` | Giày đi hàng ngày |

**Thống kê:**
- Tổng số categories: **13** (mỗi category có CategoryID duy nhất)
- Số category names khác nhau: **10**
- Một số category names xuất hiện nhiều lần nhưng là các category khác nhau:
  - `Sneakers`: CategoryID 1 và 5 (khác nhau về mô tả)
  - `Boots`: CategoryID 2 và 6 (khác nhau về mô tả)
  - `Sandals`: CategoryID 3 và 7 (khác nhau về mô tả)

**Lưu ý quan trọng về Category Matching:**
- Mỗi category có **CategoryID duy nhất**, không có category nào trùng lặp
- Một số category có cùng **Name** nhưng là các category khác nhau với CategoryID và Description khác nhau
- Category matching là **case-sensitive** và phải khớp chính xác với Name trong database
- Khi filter theo category name, API sử dụng query: `WHERE c.Name = ?`
- Khi filter theo `category=Sneakers`, API sẽ trả về sản phẩm từ **cả hai** CategoryID 1 và 5 (vì cả hai đều có Name = "Sneakers")
- Để lấy danh sách tất cả categories: `SELECT CategoryID, Name, Description FROM category ORDER BY CategoryID`
- Để lấy danh sách category names duy nhất: `SELECT DISTINCT Name FROM category ORDER BY Name`

### Request Examples

#### 1. Lấy tất cả sản phẩm (trang 1)

```http
GET {BASE_URL}/index.php?controller=products&action=api
```

#### 2. Lấy sản phẩm với phân trang

```http
GET {BASE_URL}/index.php?controller=products&action=api&page=2&limit=10
```

#### 3. Tìm kiếm theo category

```http
GET {BASE_URL}/index.php?controller=products&action=api&category=Sneakers
```

#### 4. Tìm kiếm theo tên giày

```http
GET {BASE_URL}/index.php?controller=products&action=api&keyword=nike
```

#### 5. Lọc theo khoảng giá

```http
GET {BASE_URL}/index.php?controller=products&action=api&min_price=50&max_price=200
```

#### 6. Kết hợp nhiều filter

```http
GET {BASE_URL}/index.php?controller=products&action=api&category=Sneakers&keyword=nike&min_price=100&max_price=300&page=1&limit=10
```

### Response Format

#### Success Response

**Status Code:** `200 OK`

**Content-Type:** `application/json`

```json
{
    "success": true,
    "data": {
        "products": [
            {
                "id": 1,
                "name": "Nike Air Max",
                "price": 150.00,
                "final_price": 120.00,
                "image": "nike-air-max.jpg",
                "image_url": "/assets/images/shoes/nike-air-max.jpg",
                "product_url": "https://unflayed-aron-overtrustful.ngrok-free.dev/index.php?controller=products&action=detail&id=1",
                "description": "Giày thể thao Nike Air Max chất lượng cao",
                "category": "Sneakers",
                "category_id": 1,
                "shoes_size": "42",
                "Stock": 10,
                "promotion": {
                    "promotion_id": 1,
                    "promotion_name": "Giảm giá 20%",
                    "discount_percentage": 20,
                    "fixed_price": null,
                    "start_date": "2024-01-01 00:00:00",
                    "end_date": "2024-12-31 23:59:59"
                }
            }
        ],
        "pagination": {
            "current_page": 1,
            "total_pages": 5,
            "total_items": 100,
            "items_per_page": 20,
            "has_next": true,
            "has_prev": false
        },
        "filters": {
            "keyword": "nike",
            "category": "Sneakers",
            "min_price": 100,
            "max_price": 300
        }
    }
}
```

#### Response Fields

**Products Array:**

| Field | Type | Mô tả |
|-------|------|-------|
| `id` | integer | ID của sản phẩm |
| `name` | string | Tên sản phẩm |
| `price` | float | Giá gốc của sản phẩm |
| `final_price` | float | Giá sau khi áp dụng khuyến mãi |
| `image` | string | Tên file ảnh |
| `image_url` | string | URL đầy đủ của ảnh |
| `product_url` | string | URL chi tiết sản phẩm |
| `description` | string | Mô tả sản phẩm |
| `category` | string | Tên category |
| `category_id` | integer | ID của category |
| `shoes_size` | string | Size giày |
| `Stock` | integer | Số lượng tồn kho |
| `promotion` | object\|null | Thông tin khuyến mãi (nếu có) |

**Promotion Object:**

| Field | Type | Mô tả |
|-------|------|-------|
| `promotion_id` | integer | ID của chương trình khuyến mãi |
| `promotion_name` | string | Tên chương trình khuyến mãi |
| `discount_percentage` | float\|null | Phần trăm giảm giá |
| `fixed_price` | float\|null | Giá cố định (nếu có) |
| `start_date` | string | Ngày bắt đầu khuyến mãi |
| `end_date` | string | Ngày kết thúc khuyến mãi |

**Pagination Object:**

| Field | Type | Mô tả |
|-------|------|-------|
| `current_page` | integer | Trang hiện tại |
| `total_pages` | integer | Tổng số trang |
| `total_items` | integer | Tổng số sản phẩm |
| `items_per_page` | integer | Số sản phẩm mỗi trang |
| `has_next` | boolean | Có trang tiếp theo không |
| `has_prev` | boolean | Có trang trước đó không |

**Filters Object:**

| Field | Type | Mô tả |
|-------|------|-------|
| `keyword` | string\|null | Từ khóa tìm kiếm đã sử dụng |
| `category` | string\|null | Category đã lọc |
| `min_price` | float\|null | Giá tối thiểu đã lọc |
| `max_price` | float\|null | Giá tối đa đã lọc |

#### Error Response

**Status Code:** `500 Internal Server Error`

**Content-Type:** `application/json`

```json
{
    "success": false,
    "error": "Internal server error",
    "message": "Chi tiết lỗi cụ thể"
}
```

### Lưu ý quan trọng

1. **Filter theo giá:** 
   - Filter `min_price` và `max_price` được áp dụng cho `final_price` (giá sau khuyến mãi), không phải `price` (giá gốc)
   - Nếu sản phẩm có khuyến mãi, giá sẽ được tính lại và filter theo giá đã giảm

2. **Category matching:**
   - Mỗi category có **CategoryID duy nhất**, không có category nào trùng lặp
   - Một số category có cùng **Name** nhưng là các category khác nhau (khác CategoryID và Description)
   - Category name phải khớp chính xác (case-sensitive)
   - Khi filter theo category name, API sẽ trả về **tất cả sản phẩm** của các category có cùng Name
   - Ví dụ: `category=Sneakers` sẽ trả về sản phẩm từ cả CategoryID 1 (Giày thể thao) và CategoryID 5 (Giày sneaker thời trang)
   - **Cách lấy danh sách category:**
     - Lấy tất cả categories: `SELECT CategoryID, Name, Description FROM category ORDER BY CategoryID`
     - Lấy category names duy nhất: `SELECT DISTINCT Name FROM category ORDER BY Name`
     - Từ API response: mỗi sản phẩm có field `category` (Name) và `category_id` (CategoryID)
   - **Category names có sẵn:** `Sneakers`, `Boots`, `Sandals`, `Running`, `Formal`, `Slippers`, `Basketball`, `Soccer`, `Skateboarding`, `Casual`

3. **Keyword search:**
   - Tìm kiếm trong cả `name` và `description`
   - Không phân biệt hoa thường (LIKE query)
   - Hỗ trợ tìm kiếm một phần từ khóa

4. **Pagination:**
   - Trang bắt đầu từ 1 (không phải 0)
   - Nếu không chỉ định `limit`, mặc định là 20 sản phẩm/trang
   - Nếu `page` lớn hơn `total_pages`, sẽ trả về mảng rỗng

5. **Image URL:**
   - `image_url` được tự động tạo từ `image`
   - Nếu `image` là URL đầy đủ, `image_url` sẽ giữ nguyên
   - Nếu `image` chỉ là tên file, `image_url` sẽ là `/assets/images/shoes/{image}`

6. **Product URL:**
   - `product_url` là URL đầy đủ để xem chi tiết sản phẩm
   - URL được tự động tạo từ host của request (hỗ trợ cả ngrok và localhost)
   - Format: `{BASE_URL}/index.php?controller=products&action=detail&id={product_id}`

7. **Promotion:**
   - Chỉ hiển thị promotion đang active (trong khoảng thời gian start_date và end_date)
   - Nếu có nhiều promotion, sẽ chọn promotion có discount cao nhất hoặc giá cố định thấp nhất
   - Nếu không có promotion, `promotion` sẽ là `null`

### Ví dụ sử dụng với cURL

**Lưu ý:** Thay `{BASE_URL}` bằng host của bạn (ví dụ: `http://localhost:8080`)

```bash
# Định nghĩa BASE_URL
BASE_URL="http://localhost:8080"

# Lấy tất cả sản phẩm
curl "${BASE_URL}/index.php?controller=products&action=api"

# Tìm kiếm theo category
curl "${BASE_URL}/index.php?controller=products&action=api&category=Sneakers"

# Tìm kiếm theo keyword
curl "${BASE_URL}/index.php?controller=products&action=api&keyword=nike"

# Lọc theo giá
curl "${BASE_URL}/index.php?controller=products&action=api&min_price=50&max_price=200"

# Kết hợp nhiều filter
curl "${BASE_URL}/index.php?controller=products&action=api&category=Sneakers&keyword=nike&min_price=100&max_price=300&page=1&limit=10"
```

### Ví dụ sử dụng với JavaScript (Fetch API)

```javascript
// Định nghĩa BASE_URL
const BASE_URL = 'http://localhost:8080';

// Lấy tất cả sản phẩm
fetch(`${BASE_URL}/index.php?controller=products&action=api`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Products:', data.data.products);
            console.log('Pagination:', data.data.pagination);
        }
    })
    .catch(error => console.error('Error:', error));

// Tìm kiếm với filter
const params = new URLSearchParams({
    category: 'Sneakers',
    keyword: 'nike',
    min_price: 100,
    max_price: 300,
    page: 1,
    limit: 10
});

fetch(`${BASE_URL}/index.php?controller=products&action=api&${params.toString()}`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            data.data.products.forEach(product => {
                console.log(`${product.name} - $${product.final_price}`);
            });
        }
    })
    .catch(error => console.error('Error:', error));
```

### Ví dụ sử dụng với PHP

```php
// Định nghĩa BASE_URL
$baseUrl = 'http://localhost:8080';

// Lấy tất cả sản phẩm
$url = $baseUrl . '/index.php?controller=products&action=api';
$response = file_get_contents($url);
$data = json_decode($response, true);

if ($data['success']) {
    foreach ($data['data']['products'] as $product) {
        echo $product['name'] . ' - $' . $product['final_price'] . "\n";
    }
}

// Tìm kiếm với filter
$params = http_build_query([
    'category' => 'Sneakers',
    'keyword' => 'nike',
    'min_price' => 100,
    'max_price' => 300,
    'page' => 1,
    'limit' => 10
]);

$url = $baseUrl . '/index.php?controller=products&action=api&' . $params;
$response = file_get_contents($url);
$data = json_decode($response, true);

if ($data['success']) {
    echo "Total items: " . $data['data']['pagination']['total_items'] . "\n";
    echo "Current page: " . $data['data']['pagination']['current_page'] . "\n";
    echo "Total pages: " . $data['data']['pagination']['total_pages'] . "\n";
}
```

### Testing với Postman

1. **Method:** GET
2. **URL:** `{BASE_URL}/index.php?controller=products&action=api`
   - Thay `{BASE_URL}` bằng host của bạn (ví dụ: `http://localhost:8080`)
3. **Params:**
   - Key: `category`, Value: `Sneakers`
   - Key: `keyword`, Value: `nike`
   - Key: `min_price`, Value: `100`
   - Key: `max_price`, Value: `300`
   - Key: `page`, Value: `1`
   - Key: `limit`, Value: `10`

### Troubleshooting

1. **Không trả về kết quả:**
   - Kiểm tra category name có đúng không (case-sensitive)
   - Kiểm tra giá min_price và max_price có hợp lý không
   - Kiểm tra database có dữ liệu không

2. **Lỗi 500:**
   - Kiểm tra kết nối database
   - Kiểm tra log file trong `logs/errors.log`
   - Kiểm tra các tham số có đúng định dạng không

3. **Image không hiển thị:**
   - Kiểm tra file ảnh có tồn tại trong `assets/images/shoes/` không
   - Kiểm tra quyền truy cập thư mục
   - Kiểm tra đường dẫn `image_url` có đúng không

