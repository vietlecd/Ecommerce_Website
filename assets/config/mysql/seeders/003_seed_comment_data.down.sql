-- Rollback Seeder: Remove seeded data for Comment table
-- Date: 2025
-- Description: Remove sample Comment items

-- Delete all Comment items
DELETE FROM `comment`;

-- Reset the auto-increment counter for comment table
ALTER TABLE `comment` AUTO_INCREMENT = 1;
