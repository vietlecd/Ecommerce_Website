-- Migration file to create the about table

-- Create about table
CREATE TABLE IF NOT EXISTS `about` (
  `AboutID` INT AUTO_INCREMENT PRIMARY KEY,
  `Title` VARCHAR(255) NOT NULL,
  `Content` TEXT NOT NULL,
  `Image` VARCHAR(255) DEFAULT NULL,
  `UpdatedBy` INT,
  `UpdatedAt` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`UpdatedBy`) REFERENCES `admin`(`AdminID`) ON DELETE SET NULL
);
