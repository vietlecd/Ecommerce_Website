-- Migration: Create About and Q&A tables for Issue #2
-- Date: 2025-10-11
-- Description: Add About page content management and Q&A (FAQ) system tables

-- Table structure for About page content
CREATE TABLE IF NOT EXISTS `about` (
  `AboutID` int(11) NOT NULL AUTO_INCREMENT,
  `Title` varchar(200) NOT NULL,
  `Content` text NOT NULL,
  `Image` varchar(255) DEFAULT NULL,
  `LastUpdated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `UpdatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`AboutID`),
  KEY `UpdatedBy` (`UpdatedBy`),
  CONSTRAINT `about_ibfk_1` FOREIGN KEY (`UpdatedBy`) REFERENCES `admin` (`AdminID`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table structure for Q&A
CREATE TABLE IF NOT EXISTS `qna` (
  `QnaID` int(11) NOT NULL AUTO_INCREMENT,
  `Question` varchar(500) NOT NULL,
  `Answer` text NOT NULL,
  `DisplayOrder` int(11) DEFAULT 0,
  `IsActive` tinyint(1) DEFAULT 1,
  `DateCreated` datetime DEFAULT current_timestamp(),
  `LastUpdated` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `CreatedBy` int(11) DEFAULT NULL,
  PRIMARY KEY (`QnaID`),
  KEY `CreatedBy` (`CreatedBy`),
  CONSTRAINT `qna_ibfk_1` FOREIGN KEY (`CreatedBy`) REFERENCES `admin` (`AdminID`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
