-- Rollback Seeder: Remove seeded data for News table
-- Date: 2025
-- Description: Remove sample News items

-- Delete all News items
DELETE FROM `news`;

-- Reset the auto-increment counter for news table
ALTER TABLE `news` AUTO_INCREMENT = 1;
