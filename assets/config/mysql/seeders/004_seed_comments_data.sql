-- Seeder: Comments data for products
-- Date: 2025-01-20
-- Description: Insert sample comments for each product with ratings and content
-- 
-- IMPORTANT: This seeder ONLY ADDS new data, does NOT delete or modify existing data
-- - Uses INSERT IGNORE to skip if duplicate entries exist
-- - CommentID is auto-incremented, so no conflict with existing records
-- - All existing data in comment table will remain untouched

-- Delete existing comments to avoid duplicates (optional - comment out if you want to keep existing data)
-- DELETE FROM comment WHERE Content IS NOT NULL;

-- Comments for ShoesID 1 (Nike Air Max)
INSERT IGNORE INTO `comment` (`ShoesID`, `Mem_ID`, `Rating`, `Content`, `GuestName`, `Date`) VALUES
(1, 1, 5, 'Beautiful shoes with excellent quality. Can walk all day without foot fatigue. Worth the money!', NULL, '2024-12-15'),
(1, 2, 4, 'Beautiful design, eye-catching colors. However, the size is a bit small compared to expectations.', NULL, '2024-12-20'),
(1, NULL, 5, 'Great product! Durable materials, comfortable soles. Will buy more colors.', 'John Smith', '2025-01-05'),
(1, NULL, 4, 'Beautiful shoes, reasonable price. Fast shipping, careful packaging.', 'Emma Johnson', '2025-01-10');

-- Comments for ShoesID 2 (Dr. Martens)
INSERT IGNORE INTO `comment` (`ShoesID`, `Mem_ID`, `Rating`, `Content`, `GuestName`, `Date`) VALUES
(2, 3, 5, 'Classic boots are very beautiful. Good leather material, can last for years. Very satisfied!', NULL, '2024-11-25'),
(2, NULL, 4, 'Beautiful design but a bit heavy when walking. Need time to get used to.', 'Michael Brown', '2024-12-01'),
(2, 4, 5, 'High quality product. Durable boots, waterproof. Worth buying!', NULL, '2024-12-08'),
(2, NULL, 5, 'Love it! Beautiful shoes, good quality, reasonable price.', 'Sarah Davis', '2024-12-15');

-- Comments for ShoesID 3 (Nike Air Zoom)
INSERT IGNORE INTO `comment` (`ShoesID`, `Mem_ID`, `Rating`, `Content`, `GuestName`, `Date`) VALUES
(3, 1, 5, 'Best running shoes I have ever used. Very comfortable air cushion, can run long without foot fatigue.', NULL, '2024-12-20'),
(3, NULL, 4, 'Good quality but price is a bit high. Worth the money if you run regularly.', 'David Wilson', '2024-12-25'),
(3, 2, 5, 'Very satisfied with the product. Beautiful design, premium quality.', NULL, '2025-01-02'),
(3, NULL, 3, 'Beautiful shoes but not as durable as expected. After 3 months, there are signs of wear.', 'Lisa Anderson', '2025-01-08');

-- Comments for ShoesID 4 (Adidas Ultraboost)
INSERT IGNORE INTO `comment` (`ShoesID`, `Mem_ID`, `Rating`, `Content`, `GuestName`, `Date`) VALUES
(4, 3, 5, 'Amazing Boost technology! Super lightweight shoes, comfortable cushioning. Perfect for running.', NULL, '2024-11-30'),
(4, NULL, 5, 'High quality product. Beautiful colors, suitable for many styles.', 'James Taylor', '2024-12-05'),
(4, 4, 4, 'Good shoes, modern design. However, size is a bit tight, should choose a larger size.', NULL, '2024-12-12'),
(4, NULL, 5, 'Worth the money! Beautiful shoes, good quality, very comfortable to wear.', 'Maria Martinez', '2024-12-18');

-- Comments for ShoesID 5 (Converse Classic)
INSERT IGNORE INTO `comment` (`ShoesID`, `Mem_ID`, `Rating`, `Content`, `GuestName`, `Date`) VALUES
(5, 1, 4, 'Classic sneakers are very beautiful. Reasonable price, suitable for all ages.', NULL, '2024-12-10'),
(5, NULL, 5, 'Love Converse! Beautiful shoes, comfortable, easy to match. Will buy more colors.', 'Robert Thomas', '2024-12-22'),
(5, 2, 4, 'Good product but fabric material gets dirty easily. Need regular cleaning.', NULL, '2025-01-01'),
(5, NULL, 5, 'Beautiful shoes, cheap price. Consistent quality like other Converse lines.', 'Jennifer Garcia', '2025-01-06');

-- Comments for ShoesID 6-10
INSERT IGNORE INTO `comment` (`ShoesID`, `Mem_ID`, `Rating`, `Content`, `GuestName`, `Date`) VALUES
(6, 3, 5, 'Very comfortable sandals. Good leather material, no bad odor. Worth buying!', NULL, '2024-11-20'),
(6, NULL, 4, 'Simple but beautiful design. Reasonable price for this quality.', 'William Rodriguez', '2024-12-03'),
(7, 4, 5, 'Very beautiful skateboard shoes. Good quality, durable over time.', NULL, '2024-12-15'),
(7, NULL, 4, 'Love streetwear style. Beautiful shoes, suitable for young people.', 'Patricia Lewis', '2024-12-28'),
(8, 1, 5, 'Very beautiful retro basketball shoes. High quality, comfortable to wear.', NULL, '2024-11-25'),
(8, NULL, 5, 'Excellent product! Beautiful design, good quality.', 'Daniel Walker', '2024-12-10'),
(9, 2, 4, 'Quality work boots. Durable, good waterproof. Suitable for heavy work.', NULL, '2024-11-18'),
(9, NULL, 5, 'Very satisfied! Beautiful shoes, premium quality.', 'Linda Hall', '2024-12-05'),
(10, 3, 5, 'Good soccer shoes. High quality, suitable for playing on grass.', NULL, '2024-12-20'),
(10, NULL, 4, 'Good product but need time to get used to the sole.', 'Mark Allen', '2024-12-30');

-- Comments for ShoesID 11-20
INSERT IGNORE INTO `comment` (`ShoesID`, `Mem_ID`, `Rating`, `Content`, `GuestName`, `Date`) VALUES
(11, 4, 5, 'Best basketball shoes. High quality, good support when playing.', NULL, '2024-12-12'),
(11, NULL, 5, 'Very satisfied with the product. Worth the money!', 'Elizabeth Young', '2024-12-25'),
(12, 1, 4, 'Good soccer shoes. Stable quality, reasonable price.', NULL, '2024-11-30'),
(12, NULL, 4, 'Beautiful product, good quality. Suitable for amateur players.', 'Christopher King', '2024-12-15'),
(13, 2, 5, 'Very beautiful sneakers. Modern design, high quality.', NULL, '2024-12-08'),
(13, NULL, 5, 'Love it! Beautiful shoes, comfortable, easy to match.', 'Susan Wright', '2024-12-22'),
(14, 3, 4, 'Good shoes but need time to get used to. Stable quality.', NULL, '2024-11-25'),
(14, NULL, 5, 'High quality product. Very satisfied!', 'Joseph Lopez', '2024-12-10'),
(15, 4, 5, 'Beautiful shoes, good quality. Worth buying!', NULL, '2024-12-18'),
(15, NULL, 4, 'Beautiful design but price is a bit high. Quality is worth the money.', 'Jessica Hill', '2024-12-28'),
(16, 1, 5, 'Very beautiful and high quality shoes. Can walk all day without foot fatigue.', NULL, '2025-01-02'),
(16, NULL, 5, 'Excellent product! Will buy more colors.', 'Matthew Scott', '2025-01-08'),
(17, 2, 4, 'Beautiful shoes, comfortable. Good quality for the price.', NULL, '2024-12-15'),
(17, NULL, 4, 'Very satisfied with the product. Worth buying!', 'Nancy Green', '2024-12-30'),
(18, 3, 5, 'High quality shoes. Beautiful design, durable over time.', NULL, '2024-11-20'),
(18, NULL, 5, 'Love it! Beautiful shoes, good quality.', 'Anthony Adams', '2024-12-05'),
(19, 4, 5, 'Very beautiful shoes. Good quality, worth the money.', NULL, '2024-12-22'),
(19, NULL, 4, 'Good product but size is a bit small. Should choose a larger size.', 'Betty Baker', '2025-01-01'),
(20, 1, 5, 'Beautiful shoes, high quality. Very satisfied!', NULL, '2024-12-10'),
(20, NULL, 5, 'Excellent product! Worth buying!', 'Steven Nelson', '2024-12-28');

-- Comments for ShoesID 21-30
INSERT IGNORE INTO `comment` (`ShoesID`, `Mem_ID`, `Rating`, `Content`, `GuestName`, `Date`) VALUES
(21, 2, 4, 'Good shoes, beautiful design. Stable quality.', NULL, '2024-12-15'),
(21, NULL, 5, 'Very satisfied! Beautiful shoes, comfortable.', 'Margaret Carter', '2024-12-30'),
(22, 3, 5, 'High quality shoes. Worth the money!', NULL, '2024-11-25'),
(22, NULL, 4, 'Good product but price is a bit high. Quality is worth it.', 'Ryan Mitchell', '2024-12-12'),
(23, 4, 5, 'Very beautiful shoes. Good quality, durable over time.', NULL, '2024-12-20'),
(23, NULL, 5, 'Love it! Will buy more colors.', 'Dorothy Perez', '2025-01-05'),
(24, 1, 4, 'Beautiful shoes, comfortable. Suitable for many styles.', NULL, '2024-12-08'),
(24, NULL, 4, 'Good product. Reasonable price.', 'Kevin Roberts', '2024-12-25'),
(25, 2, 5, 'High quality shoes. Very satisfied!', NULL, '2024-11-30'),
(25, NULL, 5, 'Excellent product! Worth buying!', 'Sharon Turner', '2024-12-18'),
(26, 3, 4, 'Good shoes but need time to get used to. Stable quality.', NULL, '2024-12-05'),
(26, NULL, 5, 'Very satisfied with the product. Beautiful and quality!', 'Brian Phillips', '2024-12-22'),
(27, 4, 5, 'Beautiful shoes, good quality. Worth the money!', NULL, '2024-12-15'),
(27, NULL, 4, 'Good product. Beautiful design, suitable for many people.', 'Lisa Campbell', '2024-12-28'),
(28, 1, 5, 'Very beautiful shoes. Premium quality.', NULL, '2025-01-02'),
(28, NULL, 5, 'Love it! Will recommend to friends.', 'Jason Parker', '2025-01-08'),
(29, 2, 4, 'Good shoes, beautiful design. Stable quality.', NULL, '2024-12-10'),
(29, NULL, 4, 'Beautiful product but price is a bit high. Quality is worth it.', 'Michelle Evans', '2024-12-30'),
(30, 3, 5, 'Good quality shoes. Very satisfied!', NULL, '2024-11-20'),
(30, NULL, 5, 'Excellent product! Worth buying!', 'Gary Edwards', '2024-12-05');

-- Comments for ShoesID 31-42
INSERT IGNORE INTO `comment` (`ShoesID`, `Mem_ID`, `Rating`, `Content`, `GuestName`, `Date`) VALUES
(31, 4, 5, 'Very beautiful shoes. High quality, worth the money!', NULL, '2024-12-20'),
(31, NULL, 4, 'Good product. Beautiful design, suitable for many styles.', 'Kimberly Collins', '2024-12-28'),
(32, 1, 5, 'Good quality shoes. Very satisfied with the product!', NULL, '2025-01-01'),
(32, NULL, 5, 'Love it! Will buy more colors.', 'Eric Stewart', '2025-01-06'),
(33, 2, 4, 'Beautiful shoes, comfortable. Stable quality.', NULL, '2024-12-12'),
(33, NULL, 4, 'Good product. Reasonable price for this quality.', 'Angela Sanchez', '2024-12-25'),
(34, 3, 5, 'Very beautiful shoes. Premium quality.', NULL, '2024-11-30'),
(34, NULL, 5, 'Excellent product! Worth buying!', 'Frank Morris', '2024-12-15'),
(35, 4, 4, 'Good shoes but need time to get used to. Stable quality.', NULL, '2024-12-08'),
(35, NULL, 5, 'Very satisfied! Beautiful shoes, good quality.', 'Ruth Rogers', '2024-12-22'),
(36, 1, 5, 'High quality shoes. Very comfortable to wear.', NULL, '2024-12-18'),
(36, NULL, 4, 'Good product. Beautiful design, reasonable price.', 'Scott Reed', '2024-12-30'),
(37, 2, 5, 'Very beautiful shoes. Good quality, durable over time.', NULL, '2025-01-02'),
(37, NULL, 5, 'Love it! Will recommend to friends.', 'Helen Cook', '2025-01-08'),
(38, 3, 4, 'Beautiful shoes, comfortable. Suitable for many styles.', NULL, '2024-12-05'),
(38, NULL, 4, 'Good product. Stable quality.', 'Sean Morgan', '2024-12-20'),
(39, 4, 5, 'High quality shoes. Very satisfied!', NULL, '2024-11-25'),
(39, NULL, 5, 'Excellent product! Worth buying!', 'Deborah Bell', '2024-12-12'),
(40, 1, 5, 'Very beautiful shoes. Good quality, worth the money!', NULL, '2024-12-28'),
(40, NULL, 4, 'Good product but price is a bit high. Quality is worth it.', 'Ralph Murphy', '2025-01-05'),
(41, 2, 5, 'Good quality shoes. Can walk all day without foot fatigue.', NULL, '2024-12-15'),
(41, NULL, 5, 'Very satisfied! Will buy more colors.', 'Carol Bailey', '2024-12-30'),
(42, 3, 4, 'Beautiful shoes, modern design. Stable quality.', NULL, '2024-12-10'),
(42, NULL, 5, 'Love it! Beautiful shoes, good quality.', 'Wayne Rivera', '2024-12-25');