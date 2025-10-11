-- Rollback migration for 001_create_qna_tables.sql

-- Drop the answers table first (because it references the questions table)
DROP TABLE IF EXISTS `answers`;

-- Drop the questions table
DROP TABLE IF EXISTS `questions`;
