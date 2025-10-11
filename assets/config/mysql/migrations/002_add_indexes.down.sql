-- Rollback migration for 002_add_indexes.sql

-- Remove indexes from the questions table
ALTER TABLE `questions` 
  DROP INDEX `idx_questions_status`,
  DROP INDEX `idx_questions_created_at`;

-- Remove indexes from the answers table
ALTER TABLE `answers`
  DROP INDEX `idx_answers_created_at`,
  DROP INDEX `idx_answers_is_admin`;
