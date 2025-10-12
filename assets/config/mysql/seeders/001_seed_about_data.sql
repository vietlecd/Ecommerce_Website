-- Seeder: Initial data for About table
-- Date: 2025-10-12
-- Description: Insert default content for About page

-- Insert default About page content
INSERT INTO `about` (`AboutID`, `Title`, `Content`, `Image`, `UpdatedAt`, `UpdatedBy`) VALUES
(1, 'About ShoeStore',
'Welcome to ShoeStore, your premier destination for high-quality footwear. We have been serving customers since 2020, offering the latest trends in shoes for all occasions.

Our mission is to provide comfortable, stylish, and affordable footwear to everyone. We carefully curate our collection from trusted brands and manufacturers worldwide.

At ShoeStore, customer satisfaction is our top priority. We offer a wide range of shoes including casual wear, formal shoes, sports shoes, and more. Our knowledgeable staff is always ready to help you find the perfect pair.

Thank you for choosing ShoeStore!',
NULL,
NOW(),
1);
