-- Migration: Create sales table
-- Description: Creates a table for storing product sales/discounts with discount percentage and expiration dates

CREATE TABLE IF NOT EXISTS `sales` (
  `SaleID` int(11) NOT NULL AUTO_INCREMENT,
  `ShoesID` int(11) NOT NULL,
  `DiscountPercent` decimal(5,2) NOT NULL CHECK (`DiscountPercent` BETWEEN 0 AND 100),
  `ExpiresAt` datetime DEFAULT NULL,
  PRIMARY KEY (`SaleID`),
  KEY `FK_sales_shoes` (`ShoesID`),
  CONSTRAINT `fk_sales_shoes` FOREIGN KEY (`ShoesID`) REFERENCES `shoes` (`ShoesID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


