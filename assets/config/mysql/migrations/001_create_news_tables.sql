-- Create news table

CREATE TABLE IF NOT EXISTS `news` (
  `NewsID` int(11) AUTO_INCREMENT,
  `Title` varchar(200) DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `Content` text DEFAULT NULL,
  `Thumbnail` varchar(255) DEFAULT NULL,
  `NewTags` varchar(100) DEFAULT NULL,
  `NewsType` varchar(50) DEFAULT 'normal',
  
  `CreatedBy` int(11) DEFAULT NULL,
  `CreatedAt` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `UpdatedAt` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

   PRIMARY KEY (`NewsID`),
   KEY `idx_news_createdby` (`CreatedBy`),
   CONSTRAINT `news_admin_fk`
        FOREIGN KEY (`CreatedBy`) REFERENCES `admin`(`AdminID`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;