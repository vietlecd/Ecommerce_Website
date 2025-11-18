-- Seeder: Initial data for News table
-- Date: 2025
-- Description: Insert sample News items

-- Insert sample News data

SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

INSERT INTO `news` (`NewsID`, `Title`, `Description`, `Content`, `CreatedBy`, `Thumbnail`, `CreatedAt`, `NewTags`, `NewsType`) VALUES
(1, 'Giảm giá mùa hè', 'Lorem ipsum dolor sit amet consectetur adipisicing elit sed', '\n                <p><ul><li><span style=\"background-color: initial;\">Viết nội dung ở đây... (x</span><b style=\"background-color: initial;\">óa nội dung này nếu muốn</b><span style=\"background-color: initial;\"> chèn ảnh inline)</span></li></ul><div>Xin chào toy tên là Nguyễn Bảo Trâ,</div></p>\n            ', 1, 'assets/images/news/news_1_1746506531.png', '2024-01-01 10:00:00', 'Khuyến mãi', 'fixed_price'),
(2, 'Khuyến mãi hè', 'Giảm giá 20% cho tất cả giày thể thao', 'Chi tiết chương trình khuyến mãi...', 1, 'assets/images/news/news_2_1746290159.jpg', '2024-02-01 10:00:00', 'Khuyến mãi', 'flash_sale'),
(3, 'Ra mắt dòng mới', 'Giới thiệu bộ sưu tập thu đông', 'Chi tiết bộ sưu tập thu đông...', 2, 'assets/images/news/news_3_1746372971.jpg', '2024-03-01 10:00:00', 'Thông tin', 'flash_sale'),
(4, 'Flash Sale', 'Giảm giá sốc trong 2 giờ', 'Chi tiết flash sale...', 3, 'assets/images/news/news_4_1746290190.jpg', '2024-04-01 10:00:00', 'Khuyến mãi', 'flash_sale'),
(5, 'Mở cửa hàng mới', 'Khai trương cửa hàng tại Hà Nội', 'Thông tin cửa hàng mới...', 4, 'assets/images/news/news_5_1746290238.jpg', '2024-05-01 10:00:00', 'Thông tin', 'flash_sale'),
(6, 'Giao hàng miễn phí', 'Miễn phí vận chuyển toàn quốc', 'Chi tiết chương trình...', 5, 'assets/images/news/news_6_1746290493.jpg', '2024-06-01 10:00:00', 'Thông tin', 'general'),
(7, 'Đổi trả linh hoạt', 'Chính sách đổi trả 30 ngày', 'Chi tiết chính sách...', 6, 'assets/images/news/news_7_1746290558.jpg', '2024-07-01 10:00:00', 'Khuyến mãi', 'general'),
(8, 'Khuyến mãi abc', 'abc', 'abc', 7, 'assets/images/news/news_8_1746290389.jpg', '2024-08-01 10:00:00', 'Khuyến mãi', 'flash_sale'),
(9, 'Combo abc', 'xyz', 'mno', 8, 'assets/images/news/news_9_1746526511.png', '2024-09-01 10:00:00', 'Khuyến mãi', 'fixed_price'),
(10, 'Sự kiện offline', 'Tham gia sự kiện offline tại TP.HCM', 'Chi tiết sự kiện...', 9, 'assets/images/news/news_10_1746290897.jpg', '2024-10-01 10:00:00', 'Thông tin', 'general'),
(11, 'Tin tuyển dụng', 'Tuyển dụng nhân viên kinh doanh', 'Thông tin tuyển dụng...', 10, 'assets/images/news/news_11_1746526581.jpg', '2024-11-01 10:00:00', 'Tuyển dụng', 'general'),
(18, 'Xả kho hè', 'adadad', 'adadsd', 1, 'assets/images/news/news_18_1746506547.png', '2025-04-29 21:29:49', 'Khuyến mãi', 'fixed_price'),
(19, 'Rẻ vô địch', 'sdasd', 'ádasd', 1, 'assets/images/news/news_19_1746506556.png', '2025-04-30 17:20:49', 'Khuyến mãi', 'fixed_price');

-- INSERT INTO `news` (`NewsID`, `Title`, `Description`, `Content`, `AdminID`, `Thumbnail`, `DateCreated`, `NewTags`, `NewsType`, `promotion_id`) VALUES
-- (1, 'Giảm giá mùa hè', 'Lorem ipsum dolor sit amet consectetur adipisicing elit sed', '\n                <p><ul><li><span style=\"background-color: initial;\">Viết nội dung ở đây... (x</span><b style=\"background-color: initial;\">óa nội dung này nếu muốn</b><span style=\"background-color: initial;\"> chèn ảnh inline)</span></li></ul><div>Xin chào toy tên là Nguyễn Bảo Trâ,</div></p>\n            ', 1, 'assets/images/news/news_1_1746506531.png', '2024-01-01 10:00:00', 'Khuyến mãi', 'fixed_price', 2),
-- (2, 'Khuyến mãi hè', 'Giảm giá 20% cho tất cả giày thể thao', 'Chi tiết chương trình khuyến mãi...', 1, 'assets/images/news/news_2_1746290159.jpg', '2024-02-01 10:00:00', 'Khuyến mãi', 'flash_sale', 5),
-- (3, 'Ra mắt dòng mới', 'Giới thiệu bộ sưu tập thu đông', 'Chi tiết bộ sưu tập thu đông...', 2, 'assets/images/news/news_3_1746372971.jpg', '2024-03-01 10:00:00', 'Thông tin', 'flash_sale', 4),
-- (4, 'Flash Sale', 'Giảm giá sốc trong 2 giờ', 'Chi tiết flash sale...', 3, 'assets/images/news/news_4_1746290190.jpg', '2024-04-01 10:00:00', 'Khuyến mãi', 'flash_sale', 6),
-- (5, 'Mở cửa hàng mới', 'Khai trương cửa hàng tại Hà Nội', 'Thông tin cửa hàng mới...', 4, 'assets/images/news/news_5_1746290238.jpg', '2024-05-01 10:00:00', 'Thông tin', 'flash_sale', 5),
-- (6, 'Giao hàng miễn phí', 'Miễn phí vận chuyển toàn quốc', 'Chi tiết chương trình...', 5, 'assets/images/news/news_6_1746290493.jpg', '2024-06-01 10:00:00', 'Thông tin', 'general', NULL),
-- (7, 'Đổi trả linh hoạt', 'Chính sách đổi trả 30 ngày', 'Chi tiết chính sách...', 6, 'assets/images/news/news_7_1746290558.jpg', '2024-07-01 10:00:00', 'Khuyến mãi', 'general', NULL),
-- (8, 'Khuyến mãi abc', 'abc', 'abc', 7, 'assets/images/news/news_8_1746290389.jpg', '2024-08-01 10:00:00', 'Khuyến mãi', 'flash_sale', 7),
-- (9, 'Combo abc', 'xyz', 'mno', 8, 'assets/images/news/news_9_1746526511.png', '2024-09-01 10:00:00', 'Khuyến mãi', 'fixed_price', 2),
-- (10, 'Sự kiện offline', 'Tham gia sự kiện offline tại TP.HCM', 'Chi tiết sự kiện...', 9, 'assets/images/news/news_10_1746290897.jpg', '2024-10-01 10:00:00', 'Thông tin', 'general', NULL),
-- (11, 'Tin tuyển dụng', 'Tuyển dụng nhân viên kinh doanh', 'Thông tin tuyển dụng...', 10, 'assets/images/news/news_11_1746526581.jpg', '2024-11-01 10:00:00', 'Tuyển dụng', 'general', NULL),
-- (18, 'Xả kho hè', 'adadad', 'adadsd', 1, 'assets/images/news/news_18_1746506547.png', '2025-04-29 21:29:49', 'Khuyến mãi', 'fixed_price', 3),
-- (19, 'Rẻ vô địch', 'sdasd', 'ádasd', 1, 'assets/images/news/news_19_1746506556.png', '2025-04-30 17:20:49', 'Khuyến mãi', 'fixed_price', 13);