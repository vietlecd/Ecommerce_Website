-- Migration: Create shoe_sizes table
-- Description: Creates a table for storing available sizes and quantities for each shoe product

CREATE TABLE IF NOT EXISTS `shoe_sizes` (
  `SizeID` int(11) NOT NULL AUTO_INCREMENT,
  `ShoeID` int(11) NOT NULL,
  `Size` decimal(5,2) NOT NULL,
  `Quantity` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`SizeID`),
  UNIQUE KEY `uniq_shoe_size` (`ShoeID`,`Size`),
  KEY `ShoeID` (`ShoeID`),
  CONSTRAINT `shoe_sizes_ibfk_1` FOREIGN KEY (`ShoeID`) REFERENCES `shoes` (`ShoesID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

