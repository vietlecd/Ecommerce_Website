-- Rollback Seeder: Remove seeded data for Promotion table
-- Date: 2025
-- Description: Remove sample Promotion items

-- Delete all Promotion items
DELETE FROM `news_promotion`;
DELETE FROM `promotion`;

-- Reset the auto-increment counter for Promotion table
ALTER TABLE `promotion` AUTO_INCREMENT = 1;
