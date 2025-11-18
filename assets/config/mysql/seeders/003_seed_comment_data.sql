-- Seeder: Initial data for Comment table
-- Date: 2025
-- Description: Insert sample Comment items

SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- Insert sample Comment data
INSERT INTO `comment` (`CommentID`, `NewsID`, `CreatedBy`, `Content`, `CreatedAt`) VALUES
(1, 1, 1, 'Bài viết rất hữu ích!', '2025-05-01 10:00:00'),
(2, 2, 2, 'Mình rất thích chương trình này.', '2025-05-02 11:00:00'),
(3, 3, 3, 'Cảm ơn vì đã chia sẻ thông tin.', '2025-05-03 12:00:00'),
(4, 4, 4, 'Chương trình khuyến mãi hấp dẫn.', '2025-05-04 13:00:00'),
(5, 5, 5, 'Mong chờ các bài viết tiếp theo.', '2025-05-05 14:00:00');
