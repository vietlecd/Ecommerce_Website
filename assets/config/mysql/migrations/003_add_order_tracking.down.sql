-- Migration: Remove tracking_id and guest order fields from order table
-- Description: Removes tracking_id and guest information fields

SET @exist_idx := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.STATISTICS 
                   WHERE table_schema = DATABASE() 
                   AND table_name = 'order' 
                   AND index_name = 'idx_tracking_id');
SET @sqlstmt_idx := IF(@exist_idx > 0, 'ALTER TABLE `order` DROP INDEX `idx_tracking_id`', 'SELECT "Index does not exist"');
PREPARE stmt_idx FROM @sqlstmt_idx;
EXECUTE stmt_idx;
DEALLOCATE PREPARE stmt_idx;

SET @exist_tracking := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
                        WHERE table_schema = DATABASE() 
                        AND table_name = 'order' 
                        AND column_name = 'tracking_id');
SET @sqlstmt_tracking := IF(@exist_tracking > 0, 'ALTER TABLE `order` DROP COLUMN `tracking_id`', 'SELECT "Column tracking_id does not exist"');
PREPARE stmt_tracking FROM @sqlstmt_tracking;
EXECUTE stmt_tracking;
DEALLOCATE PREPARE stmt_tracking;

SET @exist_guest_name := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
                          WHERE table_schema = DATABASE() 
                          AND table_name = 'order' 
                          AND column_name = 'guest_name');
SET @sqlstmt_guest_name := IF(@exist_guest_name > 0, 'ALTER TABLE `order` DROP COLUMN `guest_name`', 'SELECT "Column guest_name does not exist"');
PREPARE stmt_guest_name FROM @sqlstmt_guest_name;
EXECUTE stmt_guest_name;
DEALLOCATE PREPARE stmt_guest_name;

SET @exist_guest_email := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
                           WHERE table_schema = DATABASE() 
                           AND table_name = 'order' 
                           AND column_name = 'guest_email');
SET @sqlstmt_guest_email := IF(@exist_guest_email > 0, 'ALTER TABLE `order` DROP COLUMN `guest_email`', 'SELECT "Column guest_email does not exist"');
PREPARE stmt_guest_email FROM @sqlstmt_guest_email;
EXECUTE stmt_guest_email;
DEALLOCATE PREPARE stmt_guest_email;

SET @exist_guest_address := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
                             WHERE table_schema = DATABASE() 
                             AND table_name = 'order' 
                             AND column_name = 'guest_address');
SET @sqlstmt_guest_address := IF(@exist_guest_address > 0, 'ALTER TABLE `order` DROP COLUMN `guest_address`', 'SELECT "Column guest_address does not exist"');
PREPARE stmt_guest_address FROM @sqlstmt_guest_address;
EXECUTE stmt_guest_address;
DEALLOCATE PREPARE stmt_guest_address;

SET @exist_guest_city := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
                          WHERE table_schema = DATABASE() 
                          AND table_name = 'order' 
                          AND column_name = 'guest_city');
SET @sqlstmt_guest_city := IF(@exist_guest_city > 0, 'ALTER TABLE `order` DROP COLUMN `guest_city`', 'SELECT "Column guest_city does not exist"');
PREPARE stmt_guest_city FROM @sqlstmt_guest_city;
EXECUTE stmt_guest_city;
DEALLOCATE PREPARE stmt_guest_city;

SET @exist_guest_zip := (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS 
                         WHERE table_schema = DATABASE() 
                         AND table_name = 'order' 
                         AND column_name = 'guest_zip');
SET @sqlstmt_guest_zip := IF(@exist_guest_zip > 0, 'ALTER TABLE `order` DROP COLUMN `guest_zip`', 'SELECT "Column guest_zip does not exist"');
PREPARE stmt_guest_zip FROM @sqlstmt_guest_zip;
EXECUTE stmt_guest_zip;
DEALLOCATE PREPARE stmt_guest_zip;

