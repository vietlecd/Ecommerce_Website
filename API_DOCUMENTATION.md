# API Documentation - Products

## Cáº¥u hÃ¬nh

**Base URL:** https://unflayed-aron-overtrustful.ngrok-free.dev/

## Láº¥y danh sÃ¡ch sáº£n pháº©m

### Endpoint

```
GET {BASE_URL}/index.php?controller=products&action=api
```

**VÃ­ dá»¥:**
```
GET {BASE_URL}/index.php?controller=products&action=api
```

### Method

`GET`

### MÃ´ táº£

API nÃ y cho phÃ©p láº¥y danh sÃ¡ch sáº£n pháº©m vá»›i cÃ¡c tÃ­nh nÄƒng:
- TÃ¬m kiáº¿m theo tÃªn giÃ y
- Lá»c theo category
- Lá»c theo khoáº£ng giÃ¡ (min - max)
- PhÃ¢n trang káº¿t quáº£
- Tráº£ vá» giÃ¡ gá»‘c vÃ  giÃ¡ sau khuyáº¿n mÃ£i

### Query Parameters

| Parameter | Type | Required | Default | MÃ´ táº£ |
|-----------|------|----------|---------|-------|
| `keyword` | string | No | - | Tá»« khÃ³a tÃ¬m kiáº¿m trong tÃªn vÃ  mÃ´ táº£ sáº£n pháº©m |
| `category` | string | No | - | TÃªn category Ä‘á»ƒ lá»c (pháº£i khá»›p chÃ­nh xÃ¡c) |
| `min_price` | float | No | - | GiÃ¡ tá»‘i thiá»ƒu (Ã¡p dá»¥ng cho final_price sau khuyáº¿n mÃ£i) |
| `max_price` | float | No | - | GiÃ¡ tá»‘i Ä‘a (Ã¡p dá»¥ng cho final_price sau khuyáº¿n mÃ£i) |
| `page` | integer | No | 1 | Sá»‘ trang (báº¯t Ä‘áº§u tá»« 1) |
| `limit` | integer | No | 20 | Sá»‘ sáº£n pháº©m trÃªn má»—i trang |

### Danh sÃ¡ch Category há»£p lá»‡

Dá»±a trÃªn dá»¯ liá»‡u tá»« database table `category`, cÃ³ **13 categories** vá»›i **10 category names khÃ¡c nhau**:

| CategoryID | Category Name | MÃ´ táº£ |
|------------|---------------|-------|
| 1 | `Sneakers` | GiÃ y thá»ƒ thao |
| 2 | `Boots` | GiÃ y boot |
| 3 | `Sandals` | DÃ©p sandal |
| 4 | `Running` | GiÃ y cháº¡y bá»™ |
| 5 | `Sneakers` | GiÃ y sneaker thá»i trang |
| 6 | `Boots` | GiÃ y bá»‘t nam/ná»¯ |
| 7 | `Sandals` | GiÃ y dÃ©p sandal |
| 8 | `Formal` | GiÃ y tÃ¢y cÃ´ng sá»Ÿ |
| 9 | `Slippers` | DÃ©p Ä‘i trong nhÃ  |
| 10 | `Basketball` | GiÃ y bÃ³ng rá»• |
| 11 | `Soccer` | GiÃ y Ä‘Ã¡ bÃ³ng |
| 12 | `Skateboarding` | GiÃ y trÆ°á»£t vÃ¡n |
| 13 | `Casual` | GiÃ y Ä‘i hÃ ng ngÃ y |

**Thá»‘ng kÃª:**
- Tá»•ng sá»‘ categories: **13** (má»—i category cÃ³ CategoryID duy nháº¥t)
- Sá»‘ category names khÃ¡c nhau: **10**
- Má»™t sá»‘ category names xuáº¥t hiá»‡n nhiá»u láº§n nhÆ°ng lÃ  cÃ¡c category khÃ¡c nhau:
  - `Sneakers`: CategoryID 1 vÃ  5 (khÃ¡c nhau vá» mÃ´ táº£)
  - `Boots`: CategoryID 2 vÃ  6 (khÃ¡c nhau vá» mÃ´ táº£)
  - `Sandals`: CategoryID 3 vÃ  7 (khÃ¡c nhau vá» mÃ´ táº£)

**LÆ°u Ã½ quan trá»ng vá» Category Matching:**
- Má»—i category cÃ³ **CategoryID duy nháº¥t**, khÃ´ng cÃ³ category nÃ o trÃ¹ng láº·p
- Má»™t sá»‘ category cÃ³ cÃ¹ng **Name** nhÆ°ng lÃ  cÃ¡c category khÃ¡c nhau vá»›i CategoryID vÃ  Description khÃ¡c nhau
- Category matching lÃ  **case-sensitive** vÃ  pháº£i khá»›p chÃ­nh xÃ¡c vá»›i Name trong database
- Khi filter theo category name, API sá»­ dá»¥ng query: `WHERE c.Name = ?`
- Khi filter theo `category=Sneakers`, API sáº½ tráº£ vá» sáº£n pháº©m tá»« **cáº£ hai** CategoryID 1 vÃ  5 (vÃ¬ cáº£ hai Ä‘á»u cÃ³ Name = "Sneakers")
- Äá»ƒ láº¥y danh sÃ¡ch táº¥t cáº£ categories: `SELECT CategoryID, Name, Description FROM category ORDER BY CategoryID`
- Äá»ƒ láº¥y danh sÃ¡ch category names duy nháº¥t: `SELECT DISTINCT Name FROM category ORDER BY Name`

### Request Examples

#### 1. Láº¥y táº¥t cáº£ sáº£n pháº©m (trang 1)

```http
GET {BASE_URL}/index.php?controller=products&action=api
```

#### 2. Láº¥y sáº£n pháº©m vá»›i phÃ¢n trang

```http
GET {BASE_URL}/index.php?controller=products&action=api&page=2&limit=10
```

#### 3. TÃ¬m kiáº¿m theo category

```http
GET {BASE_URL}/index.php?controller=products&action=api&category=Sneakers
```

#### 4. TÃ¬m kiáº¿m theo tÃªn giÃ y

```http
GET {BASE_URL}/index.php?controller=products&action=api&keyword=nike
```

#### 5. Lá»c theo khoáº£ng giÃ¡

```http
GET {BASE_URL}/index.php?controller=products&action=api&min_price=50&max_price=200
```

#### 6. Káº¿t há»£p nhiá»u filter

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
                "description": "GiÃ y thá»ƒ thao Nike Air Max cháº¥t lÆ°á»£ng cao",
                "category": "Sneakers",
                "category_id": 1,
                "size_summary": "38, 39, 40",
                "shoes_size": "38, 39, 40",
                "Stock": 10,
                "sizes": [
                    {"size": 38, "quantity": 4},
                    {"size": 39, "quantity": 3},
                    {"size": 40, "quantity": 3}
                ],
                "promotion": {
                    "promotion_id": 1,
                    "promotion_name": "Giáº£m giÃ¡ 20%",
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

| Field | Type | MÃ´ táº£ |
|-------|------|-------|
| `id` | integer | ID cá»§a sáº£n pháº©m |
| `name` | string | TÃªn sáº£n pháº©m |
| `price` | float | GiÃ¡ gá»‘c cá»§a sáº£n pháº©m |
| `final_price` | float | GiÃ¡ sau khi Ã¡p dá»¥ng khuyáº¿n mÃ£i |
| `image` | string | TÃªn file áº£nh |
| `image_url` | string | URL Ä‘áº§y Ä‘á»§ cá»§a áº£nh |
| `product_url` | string | URL chi tiáº¿t sáº£n pháº©m |
| `description` | string | MÃ´ táº£ sáº£n pháº©m |
| `category` | string | TÃªn category |
| `category_id` | integer | ID cá»§a category |
| `size_summary` | string | Chuá»—i kÃ­ch thÆ°á»›c hiá»ƒn thá»‹ (vd: `38, 39, 40`) |
| `sizes` | array | Danh sÃ¡ch tá»«ng size vÃ  sá»‘ lÆ°á»£ng tá»“n |
| `shoes_size` | string | Trường tương thích cũ, cùng giá trị với `size_summary` |
| `Stock` | integer | Sá»‘ lÆ°á»£ng tá»“n kho |
| `promotion` | object\|null | ThÃ´ng tin khuyáº¿n mÃ£i (náº¿u cÃ³) |

**Promotion Object:**

| Field | Type | MÃ´ táº£ |
|-------|------|-------|
| `promotion_id` | integer | ID cá»§a chÆ°Æ¡ng trÃ¬nh khuyáº¿n mÃ£i |
| `promotion_name` | string | TÃªn chÆ°Æ¡ng trÃ¬nh khuyáº¿n mÃ£i |
| `discount_percentage` | float\|null | Pháº§n trÄƒm giáº£m giÃ¡ |
| `fixed_price` | float\|null | GiÃ¡ cá»‘ Ä‘á»‹nh (náº¿u cÃ³) |
| `start_date` | string | NgÃ y báº¯t Ä‘áº§u khuyáº¿n mÃ£i |
| `end_date` | string | NgÃ y káº¿t thÃºc khuyáº¿n mÃ£i |

**Pagination Object:**

| Field | Type | MÃ´ táº£ |
|-------|------|-------|
| `current_page` | integer | Trang hiá»‡n táº¡i |
| `total_pages` | integer | Tá»•ng sá»‘ trang |
| `total_items` | integer | Tá»•ng sá»‘ sáº£n pháº©m |
| `items_per_page` | integer | Sá»‘ sáº£n pháº©m má»—i trang |
| `has_next` | boolean | CÃ³ trang tiáº¿p theo khÃ´ng |
| `has_prev` | boolean | CÃ³ trang trÆ°á»›c Ä‘Ã³ khÃ´ng |

**Filters Object:**

| Field | Type | MÃ´ táº£ |
|-------|------|-------|
| `keyword` | string\|null | Tá»« khÃ³a tÃ¬m kiáº¿m Ä‘Ã£ sá»­ dá»¥ng |
| `category` | string\|null | Category Ä‘Ã£ lá»c |
| `min_price` | float\|null | GiÃ¡ tá»‘i thiá»ƒu Ä‘Ã£ lá»c |
| `max_price` | float\|null | GiÃ¡ tá»‘i Ä‘a Ä‘Ã£ lá»c |

#### Error Response

**Status Code:** `500 Internal Server Error`

**Content-Type:** `application/json`

```json
{
    "success": false,
    "error": "Internal server error",
    "message": "Chi tiáº¿t lá»—i cá»¥ thá»ƒ"
}
```

### LÆ°u Ã½ quan trá»ng

1. **Filter theo giÃ¡:** 
   - Filter `min_price` vÃ  `max_price` Ä‘Æ°á»£c Ã¡p dá»¥ng cho `final_price` (giÃ¡ sau khuyáº¿n mÃ£i), khÃ´ng pháº£i `price` (giÃ¡ gá»‘c)
   - Náº¿u sáº£n pháº©m cÃ³ khuyáº¿n mÃ£i, giÃ¡ sáº½ Ä‘Æ°á»£c tÃ­nh láº¡i vÃ  filter theo giÃ¡ Ä‘Ã£ giáº£m

2. **Category matching:**
   - Má»—i category cÃ³ **CategoryID duy nháº¥t**, khÃ´ng cÃ³ category nÃ o trÃ¹ng láº·p
   - Má»™t sá»‘ category cÃ³ cÃ¹ng **Name** nhÆ°ng lÃ  cÃ¡c category khÃ¡c nhau (khÃ¡c CategoryID vÃ  Description)
   - Category name pháº£i khá»›p chÃ­nh xÃ¡c (case-sensitive)
   - Khi filter theo category name, API sáº½ tráº£ vá» **táº¥t cáº£ sáº£n pháº©m** cá»§a cÃ¡c category cÃ³ cÃ¹ng Name
   - VÃ­ dá»¥: `category=Sneakers` sáº½ tráº£ vá» sáº£n pháº©m tá»« cáº£ CategoryID 1 (GiÃ y thá»ƒ thao) vÃ  CategoryID 5 (GiÃ y sneaker thá»i trang)
   - **CÃ¡ch láº¥y danh sÃ¡ch category:**
     - Láº¥y táº¥t cáº£ categories: `SELECT CategoryID, Name, Description FROM category ORDER BY CategoryID`
     - Láº¥y category names duy nháº¥t: `SELECT DISTINCT Name FROM category ORDER BY Name`
     - Tá»« API response: má»—i sáº£n pháº©m cÃ³ field `category` (Name) vÃ  `category_id` (CategoryID)
   - **Category names cÃ³ sáºµn:** `Sneakers`, `Boots`, `Sandals`, `Running`, `Formal`, `Slippers`, `Basketball`, `Soccer`, `Skateboarding`, `Casual`

3. **Keyword search:**
   - TÃ¬m kiáº¿m trong cáº£ `name` vÃ  `description`
   - KhÃ´ng phÃ¢n biá»‡t hoa thÆ°á»ng (LIKE query)
   - Há»— trá»£ tÃ¬m kiáº¿m má»™t pháº§n tá»« khÃ³a

4. **Pagination:**
   - Trang báº¯t Ä‘áº§u tá»« 1 (khÃ´ng pháº£i 0)
   - Náº¿u khÃ´ng chá»‰ Ä‘á»‹nh `limit`, máº·c Ä‘á»‹nh lÃ  20 sáº£n pháº©m/trang
   - Náº¿u `page` lá»›n hÆ¡n `total_pages`, sáº½ tráº£ vá» máº£ng rá»—ng

5. **Image URL:**
   - `image_url` Ä‘Æ°á»£c tá»± Ä‘á»™ng táº¡o tá»« `image`
   - Náº¿u `image` lÃ  URL Ä‘áº§y Ä‘á»§, `image_url` sáº½ giá»¯ nguyÃªn
   - Náº¿u `image` chá»‰ lÃ  tÃªn file, `image_url` sáº½ lÃ  `/assets/images/shoes/{image}`

6. **Product URL:**
   - `product_url` lÃ  URL Ä‘áº§y Ä‘á»§ Ä‘á»ƒ xem chi tiáº¿t sáº£n pháº©m
   - URL Ä‘Æ°á»£c tá»± Ä‘á»™ng táº¡o tá»« host cá»§a request (há»— trá»£ cáº£ ngrok vÃ  localhost)
   - Format: `{BASE_URL}/index.php?controller=products&action=detail&id={product_id}`

7. **Promotion:**
   - Chá»‰ hiá»ƒn thá»‹ promotion Ä‘ang active (trong khoáº£ng thá»i gian start_date vÃ  end_date)
   - Náº¿u cÃ³ nhiá»u promotion, sáº½ chá»n promotion cÃ³ discount cao nháº¥t hoáº·c giÃ¡ cá»‘ Ä‘á»‹nh tháº¥p nháº¥t
   - Náº¿u khÃ´ng cÃ³ promotion, `promotion` sáº½ lÃ  `null`

### VÃ­ dá»¥ sá»­ dá»¥ng vá»›i cURL

**LÆ°u Ã½:** Thay `{BASE_URL}` báº±ng host cá»§a báº¡n (vÃ­ dá»¥: `http://localhost:8080`)

```bash
# Äá»‹nh nghÄ©a BASE_URL
BASE_URL="http://localhost:8080"

# Láº¥y táº¥t cáº£ sáº£n pháº©m
curl "${BASE_URL}/index.php?controller=products&action=api"

# TÃ¬m kiáº¿m theo category
curl "${BASE_URL}/index.php?controller=products&action=api&category=Sneakers"

# TÃ¬m kiáº¿m theo keyword
curl "${BASE_URL}/index.php?controller=products&action=api&keyword=nike"

# Lá»c theo giÃ¡
curl "${BASE_URL}/index.php?controller=products&action=api&min_price=50&max_price=200"

# Káº¿t há»£p nhiá»u filter
curl "${BASE_URL}/index.php?controller=products&action=api&category=Sneakers&keyword=nike&min_price=100&max_price=300&page=1&limit=10"
```

### VÃ­ dá»¥ sá»­ dá»¥ng vá»›i JavaScript (Fetch API)

```javascript
// Äá»‹nh nghÄ©a BASE_URL
const BASE_URL = 'http://localhost:8080';

// Láº¥y táº¥t cáº£ sáº£n pháº©m
fetch(`${BASE_URL}/index.php?controller=products&action=api`)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Products:', data.data.products);
            console.log('Pagination:', data.data.pagination);
        }
    })
    .catch(error => console.error('Error:', error));

// TÃ¬m kiáº¿m vá»›i filter
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

### VÃ­ dá»¥ sá»­ dá»¥ng vá»›i PHP

```php
// Äá»‹nh nghÄ©a BASE_URL
$baseUrl = 'http://localhost:8080';

// Láº¥y táº¥t cáº£ sáº£n pháº©m
$url = $baseUrl . '/index.php?controller=products&action=api';
$response = file_get_contents($url);
$data = json_decode($response, true);

if ($data['success']) {
    foreach ($data['data']['products'] as $product) {
        echo $product['name'] . ' - $' . $product['final_price'] . "\n";
    }
}

// TÃ¬m kiáº¿m vá»›i filter
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

### Testing vá»›i Postman

1. **Method:** GET
2. **URL:** `{BASE_URL}/index.php?controller=products&action=api`
   - Thay `{BASE_URL}` báº±ng host cá»§a báº¡n (vÃ­ dá»¥: `http://localhost:8080`)
3. **Params:**
   - Key: `category`, Value: `Sneakers`
   - Key: `keyword`, Value: `nike`
   - Key: `min_price`, Value: `100`
   - Key: `max_price`, Value: `300`
   - Key: `page`, Value: `1`
   - Key: `limit`, Value: `10`

### Troubleshooting

1. **KhÃ´ng tráº£ vá» káº¿t quáº£:**
   - Kiá»ƒm tra category name cÃ³ Ä‘Ãºng khÃ´ng (case-sensitive)
   - Kiá»ƒm tra giÃ¡ min_price vÃ  max_price cÃ³ há»£p lÃ½ khÃ´ng
   - Kiá»ƒm tra database cÃ³ dá»¯ liá»‡u khÃ´ng

2. **Lá»—i 500:**
   - Kiá»ƒm tra káº¿t ná»‘i database
   - Kiá»ƒm tra log file trong `logs/errors.log`
   - Kiá»ƒm tra cÃ¡c tham sá»‘ cÃ³ Ä‘Ãºng Ä‘á»‹nh dáº¡ng khÃ´ng

3. **Image khÃ´ng hiá»ƒn thá»‹:**
   - Kiá»ƒm tra file áº£nh cÃ³ tá»“n táº¡i trong `assets/images/shoes/` khÃ´ng
   - Kiá»ƒm tra quyá»n truy cáº­p thÆ° má»¥c
   - Kiá»ƒm tra Ä‘Æ°á»ng dáº«n `image_url` cÃ³ Ä‘Ãºng khÃ´ng



