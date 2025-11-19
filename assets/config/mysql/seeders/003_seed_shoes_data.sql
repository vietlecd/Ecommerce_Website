-- Seeder: Additional shoes data
-- Date: 2025-05-08
-- Description: Insert additional shoe products with Unsplash images
-- 
-- IMPORTANT: This seeder ONLY ADDS new data, does NOT delete or modify existing data
-- - Uses INSERT IGNORE to skip if duplicate entries exist
-- - ShoesID is auto-incremented, so no conflict with existing records
-- - All existing data in shoes table will remain untouched

INSERT IGNORE INTO `shoes` (`Name`, `Price`, `Stock`, `Description`, `DateCreate`, `DateUpdate`, `Image`, `CategoryID`, `shoes_size`) VALUES
('Nike Air Max 95', 2500.00, 85, 'Premium running sneaker with Air cushioning', '2024-03-15', '2024-11-20', 'https://images.unsplash.com/photo-1761942028306-6e0399c10088?ixid=M3w4MTI2OTd8MHwxfHNlYXJjaHwxfHxOaWtlJTIwQWlyJTIwTWF4JTIwcnVubmluZyUyMHNob2VzfGVufDB8fHx8MTc2MjY5NTQ4NHww&ixlib=rb-4.1.0', 1, 42.00),
('Dr. Martens 1460', 1800.00, 45, 'Classic leather boot with timeless style', '2024-05-10', '2024-09-25', 'https://images.unsplash.com/photo-1747083996241-3d86d9dbab11?ixid=M3w4MTI2OTd8MHwxfHNlYXJjaHwxfHxEciUyME1hcnRlbnMlMjBib290c3xlbnwwfHx8fDE3NjI2OTU0ODR8MA&ixlib=rb-4.1.0', 2, 39.00),
('Adidas Ultraboost 22', 2200.00, 75, 'Performance running shoe featuring Boost foam', '2024-07-20', '2024-12-05', 'https://images.unsplash.com/photo-1610945102998-749b4a3d798c?ixid=M3w4MTI2OTd8MHwxfHNlYXJjaHwxfHxBZGlkYXMlMjBVbHRyYWJvb3N0JTIwcnVubmluZ3xlbnwwfHx8fDE3NjI2OTU0ODR8MA&ixlib=rb-4.1.0', 4, 41.00),
('Converse Chuck Taylor', 850.00, 100, 'Iconic canvas sneaker for everyday wear', '2024-02-28', '2024-10-15', 'https://images.unsplash.com/photo-1536830220630-ce146cccac84?ixid=M3w4MTI2OTd8MHwxfHNlYXJjaHwxfHxDb252ZXJzZSUyMENodWNrJTIwVGF5bG9yJTIwc25lYWtlcnN8ZW58MHx8fHwxNzYyNjk1NDg0fDA&ixlib=rb-4.1.0', 5, 38.00),
('Birkenstock Arizona', 1200.00, 60, 'Genuine leather sandal with all-day comfort', '2024-06-12', '2024-11-30', 'https://images.unsplash.com/photo-1619423089747-d2574ffef365?ixid=M3w4MTI2OTd8MHwxfHNlYXJjaHwxfHxCaXJrZW5zdG9jayUyMHNhbmRhbHN8ZW58MHx8fHwxNzYyNjk1NDg0fDA&ixlib=rb-4.1.0', 3, 40.00),
('Vans Old Skool', 950.00, 90, 'Streetwear-inspired skateboard shoe', '2024-04-05', '2024-09-18', 'https://images.unsplash.com/photo-1615842867179-bc39bd16c3a2?ixid=M3w4MTI2OTd8MHwxfHNlYXJjaHwxfHxWYW5zJTIwc2thdGUlMjBzaG9lc3xlbnwwfHx8fDE3NjI2OTU0ODR8MA&ixlib=rb-4.1.0', 12, 37.00),
('New Balance 550', 1500.00, 55, 'Retro basketball sneaker with bold look', '2024-08-22', '2024-12-10', 'https://images.unsplash.com/photo-1632993819204-3ad5253a4a72?ixid=M3w4MTI2OTd8MHwxfHNlYXJjaHwxfHxOZXclMjBCYWxhbmNlJTIwYmFza2V0YmFsbCUyMHNob2VzfGVufDB8fHx8MTc2MjY5NTQ4NHww&ixlib=rb-4.1.0', 10, 43.00),
('Timberland Premium', 2800.00, 35, 'Waterproof work boot built for durability', '2024-01-15', '2024-07-08', 'https://images.unsplash.com/photo-1622760775556-b8c9996535f7?ixid=M3w4MTI2OTd8MHwxfHNlYXJjaHwxfHxUaW1iZXJsYW5kJTIwd29yayUyMGJvb3RzfGVufDB8fHx8MTc2MjY5NTQ4NHww&ixlib=rb-4.1.0', 2, 44.00),
('Nike Air Jordan 1', 3200.00, 25, 'Iconic basketball sneaker for collectors', '2024-09-30', '2024-12-28', 'https://images.unsplash.com/photo-1645833889386-2782e290ee3b?ixid=M3w4MTI2OTd8MHwxfHNlYXJjaHwxfHxOaWtlJTIwQWlyJTIwSm9yZGFuJTIwc25lYWtlcnN8ZW58MHx8fHwxNzYyNjk1NDg1fDA&ixlib=rb-4.1.0', 10, 42.00),
('Adidas Predator', 1900.00, 40, 'Pro-level soccer cleat with control fins', '2024-03-08', '2024-08-14', 'https://images.unsplash.com/photo-1571267434388-6a1df2649dce?ixid=M3w4MTI2OTd8MHwxfHNlYXJjaHw0fHxBZGlkYXMlMjBzb2NjZXIlMjBjbGVhdHN8ZW58MHx8fHwxNzYyNjk1NDg1fDA&ixlib=rb-4.1.0', 11, 41.00),
('Nike Air Max 98', 2500.00, 85, 'High-end trainer featuring Air Max technology', '2024-03-15', '2025-01-20', 'https://images.unsplash.com/photo-1581327512014-619407b6a116?ixid=M3w4MTI2OTd8MHwxfHNlYXJjaHwxfHxOaWtlJTIwQWlyJTIwTWF4JTIwc25lYWtlcnN8ZW58MHx8fHwxNzYyNjk1OTQ4fDA&ixlib=rb-4.1.0', 1, 42.00),
('Dr. Martens 1460', 2800.00, 45, 'Premium leather boot with classic silhouette', '2024-06-10', '2025-02-28', 'https://images.unsplash.com/photo-1747083996241-3d86d9dbab11?ixid=M3w4MTI2OTd8MHwxfHNlYXJjaHwxfHxEciUyME1hcnRlbnMlMjBib290c3xlbnwwfHx8fDE3NjI2OTU0ODR8MA&ixlib=rb-4.1.0', 2, 39.00),
('Adidas Ultraboost', 2200.00, 75, 'Soft Boost runner engineered for long distance', '2024-08-22', '2025-03-15', 'https://images.unsplash.com/photo-1719759674376-a001dc166cb6?ixid=M3w4MTI2OTd8MHwxfHNlYXJjaHwxfHxBZGlkYXMlMjBydW5uaW5nJTIwc2hvZXN8ZW58MHx8fHwxNzYyNjk1OTU2fDA&ixlib=rb-4.1.0', 4, 40.00),
('Converse Chuck Taylor', 1500.00, 95, 'Classic canvas sneaker with street style', '2024-11-05', '2025-04-10', 'https://images.unsplash.com/photo-1717095896816-1b7e7ed0132f?ixid=M3w4MTI2OTd8MHwxfHNlYXJjaHwxfHxDb252ZXJzZSUyMHNuZWFrZXJzJTIwY2FzdWFsfGVufDB8fHx8MTc2MjY5NjE3Mnww&ixlib=rb-4.1.0', 5, 38.00),
('Birkenstock Arizona', 1800.00, 60, 'Leather sandal ideal for summer days', '2024-12-18', '2025-05-25', 'https://images.unsplash.com/photo-1619423089747-d2574ffef365?ixid=M3w4MTI2OTd8MHwxfHNlYXJjaHwxfHxCaXJrZW5zdG9jayUyMHNhbmRhbHN8ZW58MHx8fHwxNzYyNjk1NDg0fDA&ixlib=rb-4.1.0', 3, 37.00),
('Nike Air Max 95 Premium', 2500.00, 85, 'Air Max runner with plush cushioning', '2024-03-15', '2024-11-20', 'https://images.unsplash.com/photo-1761942028306-6e0399c10088?ixid=M3w4MTI2OTd8MHwxfHNlYXJjaHwxfHxOaWtlJTIwQWlyJTIwTWF4JTIwcnVubmluZyUyMHNob2VzfGVufDB8fHx8MTc2MjY5NTQ4NHww&ixlib=rb-4.1.0', 1, 42.00),
('Dr. Martens 1460 Vintage', 1800.00, 45, 'Vintage leather boot designed to last', '2024-06-10', '2024-12-05', 'https://images.unsplash.com/photo-1747083996241-3d86d9dbab11?ixid=M3w4MTI2OTd8MHwxfHNlYXJjaHwxfHxEciUyME1hcnRlbnMlMjBib290c3xlbnwwfHx8fDE3NjI2OTU0ODR8MA&ixlib=rb-4.1.0', 2, 39.00),
('Adidas Ultraboost 22 Pro', 2200.00, 75, 'Boost-powered trainer for daily mileage', '2024-02-28', '2024-10-15', 'https://images.unsplash.com/photo-1613972798457-45fc5237ae32?ixid=M3w4MTI2OTd8MHwxfHNlYXJjaHwxfHxBZGlkYXMlMjBVbHRyYWJvb3N0JTIwcnVubmluZyUyMHNob2VzfGVufDB8fHx8MTc2MjY5NTY4M3ww&ixlib=rb-4.1.0', 4, 40.00),
('Birkenstock Arizona Classic', 1200.00, 60, 'Orthopedic leather sandal with cork footbed', '2024-05-20', '2024-09-12', 'https://images.unsplash.com/photo-1603487742131-4160ec999306?ixid=M3w4MTI2OTd8MHwxfHNlYXJjaHw1fHxCaXJrZW5zdG9jayUyMHNhbmRhbHN8ZW58MHx8fHwxNzYyNjk1NDg0fDA&ixlib=rb-4.1.0', 3, 38.00),
('Converse Chuck Taylor All Star', 900.00, 95, 'Classic Chuck Taylor with heritage vibes', '2024-01-08', '2024-08-22', 'https://images.unsplash.com/photo-1536830220630-ce146cccac84?ixid=M3w4MTI2OTd8MHwxfHNlYXJjaHwxfHxDb252ZXJzZSUyMENodWNrJTIwVGF5bG9yJTIwc25lYWtlcnN8ZW58MHx8fHwxNzYyNjk1NDg0fDA&ixlib=rb-4.1.0', 5, 41.00),
('Nike Air Max 90', 2500.00, 85, 'Air Max 90 with visible Air cushioning', '2024-03-15', '2024-11-20', 'https://images.unsplash.com/photo-1581327512014-619407b6a116?ixid=M3w4MTI2OTd8MHwxfHNlYXJjaHwxfHxOaWtlJTIwQWlyJTIwTWF4JTIwc25lYWtlcnN8ZW58MHx8fHwxNzYyNjk1OTQ4fDA&ixlib=rb-4.1.0', 1, 42.00),
('Dr. Martens 1460 Fashion', 1800.00, 45, 'Fashion-forward leather boot for every outfit', '2024-06-10', '2024-12-05', 'https://images.unsplash.com/photo-1747083996241-3d86d9dbab11?ixid=M3w4MTI2OTd8MHwxfHNlYXJjaHwxfHxEciUyME1hcnRlbnMlMjBib290c3xlbnwwfHx8fDE3NjI2OTU0ODR8MA&ixlib=rb-4.1.0', 2, 39.00),
('Adidas Ultraboost Comfort', 2200.00, 75, 'Ultra-cushioned Boost shoe for comfort', '2024-02-20', '2024-10-15', 'https://images.unsplash.com/photo-1719759674376-a001dc166cb6?ixid=M3w4MTI2OTd8MHwxfHNlYXJjaHwxfHxBZGlkYXMlMjBydW5uaW5nJTIwc2hvZXN8ZW58MHx8fHwxNzYyNjk1OTU2fDA&ixlib=rb-4.1.0', 4, 41.00),
('Birkenstock Arizona Summer', 1200.00, 60, 'Summer sandal with supportive straps', '2024-05-08', '2024-09-25', 'https://images.unsplash.com/photo-1603487742131-4160ec999306?ixid=M3w4MTI2OTd8MHwxfHNlYXJjaHw1fHxCaXJrZW5zdG9jayUyMHNhbmRhbHN8ZW58MHx8fHwxNzYyNjk1NDg0fDA&ixlib=rb-4.1.0', 3, 38.00),
('Converse Chuck Taylor Street', 950.00, 90, 'Street-ready Chuck Taylor iteration', '2024-01-12', '2024-08-30', 'https://images.unsplash.com/photo-1605973828881-d32d26aaf2ae?ixid=M3w4MTI2OTd8MHwxfHNlYXJjaHwxfHxDb252ZXJzZSUyMHNuZWFrZXJzfGVufDB8fHx8MTc2MjY5NTk2Nnww&ixlib=rb-4.1.0', 5, 40.00),
('Nike Air Max 95 Sport', 2500.00, 75, 'Air Max runner tuned for sport training', '2024-03-15', '2024-08-20', 'https://images.unsplash.com/photo-1761942028306-6e0399c10088?ixid=M3w4MTI2OTd8MHwxfHNlYXJjaHwxfHxOaWtlJTIwQWlyJTIwTWF4JTIwcnVubmluZyUyMHNob2VzfGVufDB8fHx8MTc2MjY5NTQ4NHww&ixlib=rb-4.1.0', 1, 42.00),
('Dr. Martens 1460 Leather', 3000.00, 50, 'Full-grain leather boot with rugged build', '2024-05-10', '2024-09-15', 'https://images.unsplash.com/photo-1747083996241-3d86d9dbab11?ixid=M3w4MTI2OTd8MHwxfHNlYXJjaHwxfHxEciUyME1hcnRlbnMlMjBib290c3xlbnwwfHx8fDE3NjI2OTU0ODR8MA&ixlib=rb-4.1.0', 2, 39.00),
('Birkenstock Arizona Cork', 1200.00, 80, 'Comfort sandal with cork sole', '2024-06-22', '2024-11-30', 'https://images.unsplash.com/photo-1603487742131-4160ec999306?ixid=M3w4MTI2OTd8MHwxfHNlYXJjaHw1fHxCaXJrZW5zdG9jayUyMHNhbmRhbHN8ZW58MHx8fHwxNzYyNjk1NDg0fDA&ixlib=rb-4.1.0', 3, 40.00),
('Adidas Ultraboost Performance', 2800.00, 60, 'Performance Boost runner for athletes', '2024-02-18', '2024-07-25', 'https://images.unsplash.com/photo-1613972798457-45fc5237ae32?ixid=M3w4MTI2OTd8MHwxfHNlYXJjaHwxfHxBZGlkYXMlMjBVbHRyYWJvb3N0JTIwcnVubmluZyUyMHNob2VzfGVufDB8fHx8MTc2MjY5NTY4M3ww&ixlib=rb-4.1.0', 4, 41.00),
('Converse Chuck Taylor Original', 850.00, 90, 'Heritage Chuck Taylor with clean lines', '2024-04-05', '2024-10-12', 'https://images.unsplash.com/photo-1601131831144-5d096d7a832c?ixid=M3w4MTI2OTd8MHwxfHNlYXJjaHwxfHxDb252ZXJzZSUyMENodWNrJTIwVGF5bG9yJTIwQWxsJTIwU3RhcnxlbnwwfHx8fDE3NjI2OTY0Mjd8MA&ixlib=rb-4.1.0', 5, 38.00);

-- Populate multi-size inventory entries for any newly added shoes
INSERT INTO `shoe_sizes` (`ShoeID`, `Size`, `Quantity`)
SELECT seed.ShoesID,
       ROUND(seed.base_size + offsets.offset, 2) AS Size,
       CASE offsets.offset
           WHEN -1 THEN CASE WHEN seed.total_stock <= 0 THEN 0 ELSE seed.base_qty + CASE WHEN seed.remainder > 0 THEN 1 ELSE 0 END END
           WHEN 0 THEN CASE WHEN seed.total_stock <= 0 THEN 0 ELSE seed.base_qty + CASE WHEN seed.remainder > 1 THEN 1 ELSE 0 END END
           ELSE CASE WHEN seed.total_stock <= 0 THEN 0 ELSE GREATEST(0, seed.total_stock - (seed.base_qty * 2 + CASE WHEN seed.remainder > 0 THEN 1 ELSE 0 END + CASE WHEN seed.remainder > 1 THEN 1 ELSE 0 END)) END
       END AS Quantity
FROM (
    SELECT s.ShoesID,
           ROUND(COALESCE(s.shoes_size, 40.00), 2) AS base_size,
           GREATEST(COALESCE(s.Stock, 0), 0) AS total_stock,
           FLOOR(GREATEST(COALESCE(s.Stock, 0), 0) / 3) AS base_qty,
           MOD(GREATEST(COALESCE(s.Stock, 0), 0), 3) AS remainder
    FROM shoes s
    WHERE NOT EXISTS (
        SELECT 1 FROM shoe_sizes ss WHERE ss.ShoeID = s.ShoesID
    )
) AS seed
JOIN (
    SELECT -1 AS offset
    UNION ALL SELECT 0
    UNION ALL SELECT 1
) AS offsets;

UPDATE `shoes` s
SET s.Stock = (
    SELECT COALESCE(SUM(ss.Quantity), 0)
    FROM shoe_sizes ss
    WHERE ss.ShoeID = s.ShoesID
);

