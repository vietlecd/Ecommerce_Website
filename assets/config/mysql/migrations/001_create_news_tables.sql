-- Create news table

CREATE TABLE IF NOT EXISTS `news` (
  `NewsID` int(11) AUTO_INCREMENT,
  `Title` varchar(200) DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `Content` text DEFAULT NULL,
  `Thumbnail` varchar(255) DEFAULT NULL,
  `NewsType` varchar(50) DEFAULT 'normal',
  
  `CreatedBy` int(11) DEFAULT NULL,
  `CreatedAt` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `UpdatedAt` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

   PRIMARY KEY (`NewsID`),
   KEY `idx_news_createdby` (`CreatedBy`),
   CONSTRAINT `news_admin_fk`
        FOREIGN KEY (`CreatedBy`) REFERENCES `admin`(`AdminID`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create news_clicks table

CREATE TABLE `news_clicks` (
  `click_id` int(11) AUTO_INCREMENT,
  `news_id` int(11) NOT NULL,
  `click_count` int(11) DEFAULT 0,
  `last_clicked_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),

  PRIMARY KEY (`click_id`),
  KEY `news_id` (`news_id`),
  CONSTRAINT `news_clicks_news_fk`
        FOREIGN KEY (`news_id`) REFERENCES `news`(`NewsID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;