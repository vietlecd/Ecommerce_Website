-- Seeder: Initial data for Promotion table
-- Date: 2025
-- Description: Insert sample Promotion items

SET NAMES utf8mb4;
SET CHARACTER SET utf8mb4;

-- Insert sample Promotion data
INSERT INTO `promotion` (`PromotionID`, `PromotionType`, `PromotionName`, `DiscountPercentage`, `FixedPrice`, `StartDate`, `EndDate`) VALUES
(1,'Black Friday', 'Black Friday Sale', 20.00, NULL, '2025-11-24 00:00:00', '2025-11-30 23:59:59'),
(2,'Cyber Monday', 'Cyber Monday Deals', 15.00, NULL, '2025-11-27 00:00:00', '2025-11-30 23:59:59'),
(3,'Christmas', 'Christmas Discounts', NULL, 50.00, '2025-12-01 00:00:00', '2025-12-25 23:59:59'),
(4,'discount', 'Flash Sale 50%', 50.00, NULL, '2025-04-16 00:00:00', '2025-05-09 23:59:00'),
(5,'fixed', 'Rẻ Vô Địch 10$', NULL, 10.00, '2025-04-29 00:00:00', '2025-06-07 23:59:00'),
(6,'fixed', 'Xả kho 20$', NULL, 20.00, '2025-04-29 00:00:00', '2025-05-30 23:59:00'),
(7,'discount', 'Sale 10%', 10.00, NULL, '2025-05-01 00:04:00', '2025-05-29 00:05:00'),
(8,'discount', 'Sale 20%', 20.00, NULL, '2025-05-03 00:08:00', '2025-05-29 00:08:00'),
(9,'discount', 'Sale 90%', 90.00, NULL, '2025-04-09 17:35:00', '2025-05-08 17:35:00'),
(10,'discount', 'Sale 40%', 40.00, NULL, '2025-04-12 17:35:00', '2025-05-09 17:35:00');


-- Insert sample News-Promotion data

INSERT INTO `news_promotion` (`NewsID`, `PromotionID`) VALUES
(1, 1),
(2, 4),
(3, 2),
(4, 5),
(7, 7),
(7, 8),
(8, 9),
(8, 10),
(18, 10);


-- Insert sample Promotion-Shoes data

INSERT INTO `promotion_shoes` (`PromotionID`, `ShoesID`) VALUES
(1, 1),
(2, 4),
(3, 2),
(4, 5),
(5, 3),
(6, 6),
(7, 7),
(7, 8),
(8, 9),
(8, 10),
(9, 11);