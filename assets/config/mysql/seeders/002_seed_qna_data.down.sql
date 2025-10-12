-- Rollback Seeder: Remove seeded data for Q&A table
-- Date: 2025-10-12
-- Description: Remove sample Q&A items

-- Delete all Q&A items
DELETE FROM `qna`;

-- Reset the auto-increment counter for qna table
ALTER TABLE `qna` AUTO_INCREMENT = 1;
