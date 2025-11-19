-- Rollback Seeder: Remove seeded shoes data
-- Date: 2025-05-08
-- Description: Remove additional shoe products added by seeder

DELETE FROM `shoes` WHERE `Name` IN (
    'Nike Air Max 95',
    'Dr. Martens 1460',
    'Adidas Ultraboost 22',
    'Converse Chuck Taylor',
    'Birkenstock Arizona',
    'Vans Old Skool',
    'New Balance 550',
    'Timberland Premium',
    'Nike Air Jordan 1',
    'Adidas Predator',
    'Nike Air Max 98',
    'Adidas Ultraboost',
    'Nike Air Max 95 Premium',
    'Dr. Martens 1460 Vintage',
    'Adidas Ultraboost 22 Pro',
    'Birkenstock Arizona Classic',
    'Converse Chuck Taylor All Star',
    'Nike Air Max 90',
    'Dr. Martens 1460 Fashion',
    'Adidas Ultraboost Comfort',
    'Birkenstock Arizona Summer',
    'Converse Chuck Taylor Street',
    'Nike Air Max 95 Sport',
    'Dr. Martens 1460 Leather',
    'Birkenstock Arizona Cork',
    'Adidas Ultraboost Performance',
    'Converse Chuck Taylor Original'
);

