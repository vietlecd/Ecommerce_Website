-- Migration: Add payment_method column to order table
-- Description: Adds payment method field to store payment option selected by customer

SET @exist_payment_method := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
                              WHERE table_schema = DATABASE() 
                              AND table_name = 'order' 
                              AND column_name = 'payment_method');
SET @sqlstmt_payment_method := IF(@exist_payment_method = 0, 'ALTER TABLE `order` ADD COLUMN `payment_method` VARCHAR(50) NULL AFTER `guest_zip`', 'SELECT "Column payment_method already exists"');
PREPARE stmt_payment_method FROM @sqlstmt_payment_method;
EXECUTE stmt_payment_method;
DEALLOCATE PREPARE stmt_payment_method;

