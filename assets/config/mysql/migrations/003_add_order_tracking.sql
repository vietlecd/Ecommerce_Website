-- Migration: Add tracking_id and guest order fields to order table
-- Description: Adds tracking_id for guest orders and guest information fields

ALTER TABLE `order` 
ADD COLUMN `tracking_id` VARCHAR(32) UNIQUE NULL AFTER `OrderID`,
ADD COLUMN `guest_name` VARCHAR(255) NULL AFTER `MemberID`,
ADD COLUMN `guest_email` VARCHAR(255) NULL AFTER `guest_name`,
ADD COLUMN `guest_address` TEXT NULL AFTER `guest_email`,
ADD COLUMN `guest_city` VARCHAR(100) NULL AFTER `guest_address`,
ADD COLUMN `guest_zip` VARCHAR(20) NULL AFTER `guest_city`;

CREATE INDEX `idx_tracking_id` ON `order` (`tracking_id`);

