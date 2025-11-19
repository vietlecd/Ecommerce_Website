-- Migration: Remove payment_method column from order table
-- Description: Removes payment method field

SET @exist_payment_method := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
                              WHERE table_schema = DATABASE() 
                              AND table_name = 'order' 
                              AND column_name = 'payment_method');
SET @sqlstmt_payment_method := IF(@exist_payment_method > 0, 'ALTER TABLE `order` DROP COLUMN `payment_method`', 'SELECT "Column payment_method does not exist"');
PREPARE stmt_payment_method FROM @sqlstmt_payment_method;
EXECUTE stmt_payment_method;
DEALLOCATE PREPARE stmt_payment_method;

