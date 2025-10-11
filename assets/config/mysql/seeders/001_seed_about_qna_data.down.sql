-- Rollback Seeder: Remove seeded data for About and Q&A tables
-- Date: 2025-10-11
-- Description: Remove default content for About page and sample Q&A items

-- Delete all Q&A items
DELETE FROM `qna`;

-- Reset the auto-increment counter for qna table
ALTER TABLE `qna` AUTO_INCREMENT = 1;

-- Delete About page content
DELETE FROM `about` WHERE AboutID = 1;

-- Reset the auto-increment counter for about table
ALTER TABLE `about` AUTO_INCREMENT = 1;
