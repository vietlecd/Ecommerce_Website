-- Migration: Add indexes to the Q&A tables
-- Description: Adds additional indexes for performance

-- Add indexes for the questions table
ALTER TABLE `questions` 
  ADD INDEX `idx_questions_status` (`status`),
  ADD INDEX `idx_questions_created_at` (`created_at`);

-- Add indexes for the answers table
ALTER TABLE `answers`
  ADD INDEX `idx_answers_created_at` (`created_at`),
  ADD INDEX `idx_answers_is_admin` (`is_admin`);
