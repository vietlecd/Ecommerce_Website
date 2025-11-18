-- Seeder Rollback: Delete seeded comments
-- Date: 2025-01-20
-- Description: Remove comments that were added by the seeder
-- IMPORTANT: This will only delete comments with Content (seeded comments)
-- Existing comments without Content will be preserved

DELETE FROM `comment` WHERE `Content` IS NOT NULL;
