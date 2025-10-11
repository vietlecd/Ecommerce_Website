-- Add About and Q&A tables for Issue #2

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

-- Insert default About page content
INSERT INTO `about` (`AboutID`, `Title`, `Content`, `Image`, `LastUpdated`, `UpdatedBy`) VALUES
(1, 'About ShoeStore', 
'Welcome to ShoeStore, your premier destination for high-quality footwear. We have been serving customers since 2020, offering the latest trends in shoes for all occasions.\n\nOur mission is to provide comfortable, stylish, and affordable footwear to everyone. We carefully curate our collection from trusted brands and manufacturers worldwide.\n\nAt ShoeStore, customer satisfaction is our top priority. We offer a wide range of shoes including casual wear, formal shoes, sports shoes, and more. Our knowledgeable staff is always ready to help you find the perfect pair.\n\nThank you for choosing ShoeStore!', 
NULL, 
NOW(), 
1);

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

-- Insert sample Q&A data
INSERT INTO `qna` (`Question`, `Answer`, `DisplayOrder`, `IsActive`, `CreatedBy`) VALUES
('What are your shipping options?', 'We offer free standard shipping on orders over $50. Express shipping is available for an additional fee. Standard delivery takes 3-5 business days, while express delivery takes 1-2 business days.', 1, 1, 1),
('What is your return policy?', 'We accept returns within 30 days of purchase. Items must be in original condition with tags attached. Refunds will be processed within 5-7 business days after we receive the returned item.', 2, 1, 1),
('How do I know my shoe size?', 'We provide a detailed size guide on each product page. You can also visit our store for a professional fitting. If you order online and the size doesn''t fit, you can exchange it for free.', 3, 1, 1),
('Do you offer warranty on your products?', 'Yes, all our shoes come with a manufacturer''s warranty. The warranty period varies by brand but typically covers manufacturing defects for 6-12 months.', 4, 1, 1),
('How can I track my order?', 'Once your order ships, you will receive a tracking number via email. You can use this number to track your package on our website or the carrier''s website.', 5, 1, 1),
('Do you have physical stores?', 'Yes, we have multiple physical stores across major cities. You can find our store locations on the Contact page. Visit us for personalized service and to try on shoes before purchasing.', 6, 1, 1);
