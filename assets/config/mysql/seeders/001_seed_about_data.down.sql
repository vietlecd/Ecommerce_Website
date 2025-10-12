-- Rollback Seeder: Remove seeded data for About table
-- Date: 2025-10-12
-- Description: Remove default content for About page

-- Delete About page content
DELETE FROM `about` WHERE AboutID = 1;

-- Reset the auto-increment counter for about table
ALTER TABLE `about` AUTO_INCREMENT = 1;
