-- Create comment table

CREATE TABLE IF NOT EXISTS `comment` (
  `CommentID` int(11) AUTO_INCREMENT,
  `NewsID` int(11) NOT NULL,
  `Content` text NOT NULL,
  `CreatedBy` int(11) DEFAULT NULL,
  `CreatedAt` DATETIME DEFAULT CURRENT_TIMESTAMP,

  PRIMARY KEY (`CommentID`),
  KEY `idx_comment_createdby` (`CreatedBy`),
  KEY `idx_comment_news` (`NewsID`),
  CONSTRAINT `comment_news_fk`
    FOREIGN KEY (`NewsID`) REFERENCES `news`(`NewsID`) ON DELETE CASCADE,
  CONSTRAINT `comment_member_fk`
    FOREIGN KEY (`CreatedBy`) REFERENCES `member`(`MemberID`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;