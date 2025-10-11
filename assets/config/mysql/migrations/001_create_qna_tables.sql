-- Migration: Create Q&A tables
-- Description: Creates tables for storing customer questions and answers

-- Create the questions table
CREATE TABLE IF NOT EXISTS `questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `MemberID` int(11) DEFAULT NULL,
  `ShoesID` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `ShoesID` (`ShoesID`),
  KEY `MemberID` (`MemberID`),
  CONSTRAINT `questions_shoes_fk` FOREIGN KEY (`ShoesID`) REFERENCES `shoes` (`ShoesID`) ON DELETE CASCADE,
  CONSTRAINT `questions_member_fk` FOREIGN KEY (`MemberID`) REFERENCES `member` (`MemberID`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create the answers table
CREATE TABLE IF NOT EXISTS `answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question_id` int(11) NOT NULL,
  `MemberID` int(11) DEFAULT NULL,
  `answer_text` text NOT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `question_id` (`question_id`),
  KEY `MemberID` (`MemberID`),
  CONSTRAINT `answers_question_fk` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE,
  CONSTRAINT `answers_member_fk` FOREIGN KEY (`MemberID`) REFERENCES `member` (`MemberID`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
