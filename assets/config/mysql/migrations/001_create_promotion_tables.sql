-- Create promotion table

CREATE TABLE IF NOT EXISTS `promotion` (
  `PromotionID` int(11) PRIMARY KEY AUTO_INCREMENT,
  `PromotionType` varchar(50) NOT NULL,
  `PromotionName` varchar(100) NOT NULL,
  `DiscountPercentage` decimal(5,2) DEFAULT NULL,
  `FixedPrice` decimal(10,2) DEFAULT NULL,
  `StartDate` datetime NOT NULL,
  `EndDate` datetime NOT NULL

) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create news promotion table

CREATE TABLE IF NOT EXISTS `news_promotion` (
  `NewsID` INT(11) NOT NULL,
  `PromotionID` INT(11) NOT NULL,
  PRIMARY KEY (`NewsID`, `PromotionID`),
  CONSTRAINT `news_promotion_news_fk`
    FOREIGN KEY (`NewsID`) REFERENCES `news`(`NewsID`) ON DELETE CASCADE,
  CONSTRAINT `news_promotion_promotion_fk`
    FOREIGN KEY (`PromotionID`) REFERENCES `promotion`(`PromotionID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- Create promotion shoes table

CREATE TABLE IF NOT EXISTS `promotion_shoes` (
  `PromotionID` INT(11) NOT NULL,
  `ShoesID` INT(11) NOT NULL,
  PRIMARY KEY (`PromotionID`, `ShoesID`),
  CONSTRAINT `promotion_shoe_promotion_fk`
    FOREIGN KEY (`PromotionID`) REFERENCES `promotion`(`PromotionID`) ON DELETE CASCADE,
  CONSTRAINT `promotion_shoe_shoe_fk`
    FOREIGN KEY (`ShoesID`) REFERENCES `shoes`(`ShoesID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
