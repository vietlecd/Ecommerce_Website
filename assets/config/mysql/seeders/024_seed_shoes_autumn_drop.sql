-- Seeder: Autumn capsule + replenishment
-- Date: 2025-11-18
-- Description: Adds curated shoes with verified Unsplash assets (checked via browser)
-- Notes:
--   * Uses INSERT IGNORE so reruns stay idempotent
--   * CategoryID references existing taxonomy from `category` table

INSERT IGNORE INTO `shoes`
(`Name`, `Price`, `Stock`, `Description`, `DateCreate`, `DateUpdate`, `Image`, `CategoryID`, `shoes_size`)
VALUES
('Nike Pegasus Trail 5', 2650.00, 70, 'All-terrain Pegasus built for misty mountain mileage.', '2025-09-12', '2025-11-18', 'https://images.unsplash.com/photo-1507537297725-24a1c029d3ca?auto=format&fit=crop&w=900&q=80', 4, 42.00),
('New Balance Fresh Foam More', 2380.00, 80, 'Max-stack Fresh Foam for city loops and recovery jogs.', '2025-10-02', '2025-11-18', 'https://images.unsplash.com/photo-1523381210434-271e8be1f52b?auto=format&fit=crop&w=900&q=80', 1, 41.00),
('Adidas Gazelle Indoor', 1750.00, 120, 'Velvet suede Gazelle tuned for lounge lighting.', '2025-08-29', '2025-11-18', 'https://images.unsplash.com/photo-1475180098004-ca77a66827be?auto=format&fit=crop&w=900&q=80', 5, 40.00),
('Converse Run Star Motion', 1950.00, 95, 'Chunky Run Star with sculpted platform outsole.', '2025-07-18', '2025-11-18', 'https://images.unsplash.com/photo-1507835661091-2984bff0a0c1?auto=format&fit=crop&w=900&q=80', 5, 39.00),
('Dr. Martens Sinclair Quad', 3150.00, 45, 'Zip-front Sinclair boot with double-stacked sole.', '2025-09-05', '2025-11-18', 'https://images.unsplash.com/photo-1525966222134-fcfa99b8ae77?auto=format&fit=crop&w=900&q=80', 2, 38.00),
('Timberland Field Trekker', 2850.00, 60, 'Field-ready boot using regenerative leather panels.', '2025-09-25', '2025-11-18', 'https://images.unsplash.com/photo-1441985347634-22816cd3f12c?auto=format&fit=crop&w=900&q=80', 6, 43.00),
('Birkenstock Kyoto Soft Footbed', 1550.00, 85, 'Felt + nubuck slip-on with adjustable strap.', '2025-04-19', '2025-11-18', 'https://images.unsplash.com/photo-1611243017235-432a2413e850?auto=format&fit=crop&w=900&q=80', 3, 41.00),
('Hoka Hopara 2', 2100.00, 65, 'Hybrid sandal-runner for wet trails and resort docks.', '2025-08-09', '2025-11-18', 'https://images.unsplash.com/photo-1556761175-5973dc0f32e7?auto=format&fit=crop&w=900&q=80', 7, 42.00),
('Cole Haan ØriginalGrand Wingtip', 2450.00, 40, 'Featherweight wingtip with ergonomic cushioning.', '2025-05-22', '2025-11-18', 'https://images.unsplash.com/photo-1518896013755-af874f6cf825?auto=format&fit=crop&w=900&q=80', 8, 42.00),
('Nike Zoom GT Cut 3', 3300.00, 55, 'Pro-hoops GT Cut with ZoomX strobel.', '2025-09-30', '2025-11-18', 'https://images.unsplash.com/photo-1509080012687-4f509b8377b1?auto=format&fit=crop&w=900&q=80', 10, 44.00),
('Adidas Copa Pure.2 FG', 2250.00, 48, 'Premium leather Copa for firm ground matches.', '2025-07-02', '2025-11-18', 'https://images.unsplash.com/photo-1508609349937-5ec4ae374ebf?auto=format&fit=crop&w=900&q=80', 11, 42.00),
('Vans Skate Half Cab', 1650.00, 90, 'Half Cab remastered for impact protection.', '2025-08-11', '2025-11-18', 'https://images.unsplash.com/photo-1504639725590-34d0984388bd?auto=format&fit=crop&w=900&q=80', 12, 40.00),
('UGG Tasman Lined', 1350.00, 110, 'Suede Tasman slipper with plush interior.', '2025-10-10', '2025-11-18', 'https://images.unsplash.com/photo-1514986888952-8cd320577b68?auto=format&fit=crop&w=900&q=80', 9, 39.00),
('Veja V-10 CWL', 2050.00, 75, 'Bio-based Veja sneaker with autumn color pops.', '2025-10-01', '2025-11-18', 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?auto=format&fit=crop&w=900&q=80', 13, 41.00);

