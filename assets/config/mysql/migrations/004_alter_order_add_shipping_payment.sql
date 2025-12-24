ALTER TABLE `order`
    ADD COLUMN `ShippingName` varchar(150) DEFAULT NULL AFTER `Quantity`,
    ADD COLUMN `ShippingEmail` varchar(150) DEFAULT NULL AFTER `ShippingName`,
    ADD COLUMN `ShippingPhone` varchar(20) DEFAULT NULL AFTER `ShippingEmail`,
    ADD COLUMN `ShippingAddress` text DEFAULT NULL AFTER `ShippingPhone`,
    ADD COLUMN `ShippingCity` varchar(100) DEFAULT NULL AFTER `ShippingAddress`,
    ADD COLUMN `ShippingZip` varchar(20) DEFAULT NULL AFTER `ShippingCity`,
    ADD COLUMN `PaymentMethod` varchar(20) DEFAULT NULL AFTER `Status`;


