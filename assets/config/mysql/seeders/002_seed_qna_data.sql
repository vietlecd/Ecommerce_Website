-- Seeder: Initial data for Q&A table
-- Date: 2025-10-12
-- Description: Insert sample Q&A items

-- Insert sample Q&A data
INSERT INTO `qna` (`Question`, `Answer`, `DisplayOrder`, `IsActive`, `CreatedBy`) VALUES
('What are your shipping options?', 'We offer free standard shipping on orders over $50. Express shipping is available for an additional fee. Standard delivery takes 3-5 business days, while express delivery takes 1-2 business days.', 1, 1, 1),
('What is your return policy?', 'We accept returns within 30 days of purchase. Items must be in original condition with tags attached. Refunds will be processed within 5-7 business days after we receive the returned item.', 2, 1, 1),
('How do I know my shoe size?', 'We provide a detailed size guide on each product page. You can also visit our store for a professional fitting. If you order online and the size doesn''t fit, you can exchange it for free.', 3, 1, 1),
('Do you offer warranty on your products?', 'Yes, all our shoes come with a manufacturer''s warranty. The warranty period varies by brand but typically covers manufacturing defects for 6-12 months.', 4, 1, 1),
('How can I track my order?', 'Once your order ships, you will receive a tracking number via email. You can use this number to track your package on our website or the carrier''s website.', 5, 1, 1),
('Do you have physical stores?', 'Yes, we have multiple physical stores across major cities. You can find our store locations on the Contact page. Visit us for personalized service and to try on shoes before purchasing.', 6, 1, 1);
