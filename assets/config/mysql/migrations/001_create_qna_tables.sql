-- Migration: Create Q&A table
-- Description: Creates a table for storing frequently asked questions and answers

-- Create the qna table
CREATE TABLE IF NOT EXISTS `qna` (
  `QnaID` int(11) NOT NULL AUTO_INCREMENT,
  `Question` varchar(500) NOT NULL,
  `Answer` text NOT NULL,
  `DisplayOrder` int(11) DEFAULT 0,
  `IsActive` tinyint(1) DEFAULT 1,
  `CreatedBy` int(11) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdatedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`QnaID`),
  KEY `CreatedBy` (`CreatedBy`),
  CONSTRAINT `qna_admin_fk` FOREIGN KEY (`CreatedBy`) REFERENCES `admin` (`AdminID`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
