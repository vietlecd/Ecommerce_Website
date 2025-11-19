-- Migration: Add Content and GuestName columns to comment table
-- Description: Adds Content column for comment text and GuestName for anonymous users

ALTER TABLE `comment` 
ADD COLUMN `Content` text DEFAULT NULL AFTER `Rating`,
ADD COLUMN `GuestName` varchar(100) DEFAULT NULL AFTER `Content`;
