-- Seeder: Low stock and out-of-stock coverage
-- Date: 2025-11-18
-- Purpose: Ensures category metrics surface low inventory signals for home widgets
-- Notes:
--   * Uses INSERT IGNORE to keep runs idempotent
--   * Unsplash assets verified for availability

INSERT IGNORE INTO `shoes`
(`Name`, `Price`, `Stock`, `Description`, `DateCreate`, `DateUpdate`, `Image`, `CategoryID`, `shoes_size`)
VALUES
('Arc Runner Pulse', 1850.00, 6, 'Lightweight runner slated for studio jog clubs.', '2025-11-10', '2025-11-18', 'https://images.unsplash.com/photo-1514986888894-05a02b2f69ae?auto=format&fit=crop&w=900&q=80', 1, 42.00),
('Sable Ridge Boot', 2980.00, 0, 'Waxed leather boot resting between production waves.', '2025-10-28', '2025-11-18', 'https://images.unsplash.com/photo-1483985988355-763728e1935b?auto=format&fit=crop&w=900&q=80', 2, 41.00),
('Cove Drift Slide', 1320.00, 4, 'Foam-lined slide for resort decks and spa corridors.', '2025-11-05', '2025-11-18', 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=900&q=80', 3, 40.00),
('Helix Tempo Racer', 2120.00, 0, 'Track-tuned racer awaiting the next capsule drop.', '2025-11-12', '2025-11-18', 'https://images.unsplash.com/photo-1528701800489-20be3c7a2e47?auto=format&fit=crop&w=900&q=80', 4, 43.00),
('Gallery City Low', 1640.00, 7, 'Gallery-ready lifestyle sneaker with waxed laces.', '2025-11-01', '2025-11-18', 'https://images.unsplash.com/photo-1503341455253-b2e723bb3dbb?auto=format&fit=crop&w=900&q=80', 5, 41.00),
('Harborline Trek Boot', 3050.00, 0, 'Salt-ready trek boot taking a breather post drop.', '2025-10-20', '2025-11-18', 'https://images.unsplash.com/photo-1515955656352-a1fa3ffcd111?auto=format&fit=crop&w=900&q=80', 6, 44.00),
('Solstice Wrap Sandal', 1480.00, 8, 'Luxe wrap sandal with braided straps and cork footbed.', '2025-11-07', '2025-11-18', 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=900&q=80', 7, 39.00),
('Midnight Ledger Oxford', 3250.00, 0, 'Midnight patent oxford held for atelier restock.', '2025-10-30', '2025-11-18', 'https://images.unsplash.com/photo-1460353906551-9c032c0cdb49?auto=format&fit=crop&w=900&q=80', 8, 42.00),
('Cloud Cabin Mule', 980.00, 5, 'Plush cabin mule for studio downtime rituals.', '2025-11-03', '2025-11-18', 'https://images.unsplash.com/photo-1456948927032-905a8cec1cdd?auto=format&fit=crop&w=900&q=80', 9, 38.00),
('Crimson Court Mid', 2870.00, 0, 'Court-ready mid with crimson overlays, currently sold out.', '2025-10-25', '2025-11-18', 'https://images.unsplash.com/photo-1523387438051-a5e358c4dfc8?auto=format&fit=crop&w=900&q=80', 10, 44.00),
('Volt Strike Elite FG', 2180.00, 3, 'Firm-ground cleat flagged for replenishment priority.', '2025-11-09', '2025-11-18', 'https://images.unsplash.com/photo-1504280390368-3971a04b2110?auto=format&fit=crop&w=900&q=80', 11, 42.00),
('Slate Rail Skate', 1520.00, 0, 'Slate-toned skate shoe pausing for the next batch.', '2025-10-18', '2025-11-18', 'https://images.unsplash.com/photo-1528701800485-2c49ddbf86c2?auto=format&fit=crop&w=900&q=80', 12, 40.00),
('Streetlight Canvas', 1270.00, 2, 'Everyday canvas sneaker with reflective piping.', '2025-11-11', '2025-11-18', 'https://images.unsplash.com/photo-1518544889280-37f4ca38e4b4?auto=format&fit=crop&w=900&q=80', 13, 41.00);

-- Create shoe size rows for the freshly inserted low-stock entries
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


