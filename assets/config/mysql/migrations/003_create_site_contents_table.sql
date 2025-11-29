-- Migration: Create site_contents table
-- Description: Creates a table for storing dynamic HTML content for different pages (About Us, Q&A, etc.)

CREATE TABLE IF NOT EXISTS `site_contents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_key` varchar(100) NOT NULL,
  `html_content` LONGTEXT NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `page_key` (`page_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

