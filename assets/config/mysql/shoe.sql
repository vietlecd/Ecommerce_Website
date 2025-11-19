-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 08, 2025 at 02:26 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shoe`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `AdminID` int(11) NOT NULL,
  `Adname` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Fname` varchar(50) DEFAULT NULL,
  `Lname` varchar(50) DEFAULT NULL,
  `Address` text DEFAULT NULL,
  `Phone` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`AdminID`, `Adname`, `Password`, `Email`, `Fname`, `Lname`, `Address`, `Phone`) VALUES
(1, 'admin1', 'pass123', 'admin1@email.com', 'Alice', 'Nguyen', '123 Street, Hanoi', '0909123456'),
(2, 'admin01', 'pass123', 'admin1@example.com', 'John', 'Smith', '123 Main St', '0123456789'),
(3, 'admin02', 'pass124', 'admin2@example.com', 'Jane', 'Doe', '456 Central Ave', '0123456790'),
(4, 'admin03', 'pass125', 'admin3@example.com', 'Alice', 'Brown', '789 Maple Rd', '0123456791'),
(5, 'admin04', 'pass126', 'admin4@example.com', 'Bob', 'Davis', '101 Oak St', '0123456792'),
(6, 'admin05', 'pass127', 'admin5@example.com', 'Carol', 'Wilson', '202 Pine St', '0123456793'),
(7, 'admin06', 'pass128', 'admin6@example.com', 'David', 'Lee', '303 Elm St', '0123456794'),
(8, 'admin07', 'pass129', 'admin7@example.com', 'Eva', 'Taylor', '404 Cedar St', '0123456795'),
(9, 'admin08', 'pass130', 'admin8@example.com', 'Frank', 'Martin', '505 Birch Rd', '0123456796'),
(10, 'admin09', 'pass131', 'admin9@example.com', 'Grace', 'Walker', '606 Spruce Ln', '0123456797'),
(11, 'admin10', 'pass132', 'admin10@example.com', 'Henry', 'Clark', '707 Walnut St', '0123456798');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `CartID` int(11) NOT NULL,
  `MemberID` int(11) DEFAULT NULL,
  `Total_price` decimal(10,2) DEFAULT NULL,
  `Quantity` int(11) DEFAULT NULL,
  `Date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`CartID`, `MemberID`, `Total_price`, `Quantity`, `Date`) VALUES
(1, 1, 2500000.00, 1, '2025-04-01'),
(2, 1, 240.00, 2, '2024-04-01'),
(3, 2, 150.00, 1, '2024-04-02'),
(4, 3, 130.00, 2, '2024-04-03'),
(5, 4, 70.00, 1, '2024-04-04'),
(6, 5, 180.00, 1, '2024-04-05'),
(7, 6, 135.00, 3, '2024-04-06'),
(8, 7, 200.00, 2, '2024-04-07'),
(9, 8, 35.00, 1, '2024-04-08'),
(10, 9, 210.00, 3, '2024-04-09'),
(11, 10, 95.00, 1, '2024-04-10');

-- --------------------------------------------------------

--
-- Table structure for table `cart_shoes`
--

CREATE TABLE `cart_shoes` (
  `CartID` int(11) NOT NULL,
  `ShoesID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart_shoes`
--

INSERT INTO `cart_shoes` (`CartID`, `ShoesID`) VALUES
(1, 1),
(1, 2),
(2, 3),
(3, 3),
(3, 4),
(4, 4),
(5, 5),
(6, 2),
(6, 7),
(6, 8),
(7, 9),
(7, 10),
(8, 8),
(9, 1),
(9, 5),
(9, 9),
(10, 10);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `CategoryID` int(11) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `ImageUrl` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`CategoryID`, `Name`, `Description`, `ImageUrl`) VALUES
(1, 'Sneakers', 'Performance sports sneakers', 'https://cdn-icons-png.flaticon.com/512/3205/3205973.png'),
(2, 'Boots', 'Versatile boot silhouettes', 'https://cdn-icons-png.flaticon.com/512/2977/2977951.png'),
(3, 'Sandals', 'Comfort-first sandals', 'https://cdn-icons-png.flaticon.com/512/554/554857.png'),
(4, 'Running', 'Mileage-ready running shoes', 'https://cdn-icons-png.flaticon.com/512/1043/1043493.png'),
(5, 'Sneakers', 'Lifestyle sneaker edits', 'https://cdn-icons-png.flaticon.com/128/5026/5026374.png'),
(6, 'Boots', 'Boots for men and women', 'https://cdn-icons-png.flaticon.com/512/2377/2377810.png'),
(7, 'Sandals', 'Statement sandal picks', 'https://cdn-icons-png.flaticon.com/512/204/204274.png'),
(8, 'Formal', 'Office-ready formal shoes', 'https://cdn-icons-png.flaticon.com/512/836/836923.png'),
(9, 'Slippers', 'Cozy indoor slippers', 'https://cdn-icons-png.flaticon.com/512/4158/4158972.png'),
(10, 'Basketball', 'Court-ready basketball shoes', 'https://cdn-icons-png.flaticon.com/512/3005/3005722.png'),
(11, 'Soccer', 'Pro-fit soccer cleats', 'https://cdn-icons-png.flaticon.com/512/3205/3205970.png'),
(12, 'Skateboarding', 'Skateboarding footwear', 'https://cdn-icons-png.flaticon.com/512/1043/1043470.png'),
(13, 'Casual', 'Everyday casual essentials', 'https://cdn-icons-png.flaticon.com/512/1043/1043464.png');

-- --------------------------------------------------------
--
-- Table structure for table `sales`
--
CREATE TABLE `sales` (
  `SaleID` int(11) NOT NULL AUTO_INCREMENT,
  `ShoesID` int(11) NOT NULL,
  `DiscountPercent` decimal(5,2) NOT NULL CHECK (`DiscountPercent` BETWEEN 0 AND 100),
  `ExpiresAt` datetime DEFAULT NULL,
  PRIMARY KEY (`SaleID`),
  KEY `FK_sales_shoes` (`ShoesID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `sales` (`SaleID`, `ShoesID`, `DiscountPercent`, `ExpiresAt`) VALUES
(1, 1, 55.00, '2025-11-19 23:59:59'),
(2, 2, 62.00, '2025-11-19 23:59:59'),
(3, 3, 58.00, '2025-11-20 23:59:59'),
(4, 4, 52.00, '2025-11-20 23:59:59'),
(5, 5, 65.00, '2025-11-21 23:59:59'),
(6, 6, 54.00, '2025-11-21 23:59:59'),
(7, 7, 68.00, '2025-11-22 23:59:59'),
(8, 8, 57.00, '2025-11-22 23:59:59'),
(9, 9, 51.00, '2025-11-23 23:59:59'),
(10, 10, 70.00, '2025-11-23 23:59:59'),
(11, 11, 60.00, '2025-11-24 23:59:59'),
(12, 12, 56.00, '2025-11-25 23:59:59'),
(13, 13, 62.00, '2025-12-05 23:59:59'),
(14, 14, 35.00, '2025-12-12 23:59:59'),
(15, 15, 32.00, '2025-12-18 23:59:59'),
(16, 16, 45.00, '2025-12-28 23:59:59'),
(17, 17, 59.00, '2026-01-05 23:59:59'),
(18, 18, 61.00, '2026-01-18 23:59:59'),
(19, 19, 27.50, '2026-02-01 23:59:59'),
(20, 20, 53.00, '2026-02-15 23:59:59'),
(21, 21, 38.00, '2026-03-01 23:59:59'),
(22, 22, 64.00, '2026-03-18 23:59:59'),
(23, 23, 55.00, '2026-04-05 23:59:59'),
(24, 24, 30.00, '2026-04-20 23:59:59');

-- --------------------------------------------------------
--
-- Table structure for table `discount_codes`
--
CREATE TABLE `discount_codes` (
  `CodeID` int(11) NOT NULL,
  `CodeTitle` varchar(120) NOT NULL,
  `CodePercent` decimal(5,2) NOT NULL,
  `CodeDescription` varchar(255) DEFAULT NULL,
  `IsActive` tinyint(1) DEFAULT 1,
  `ValidUntil` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `discount_codes`
--
INSERT INTO `discount_codes` (`CodeID`, `CodeTitle`, `CodePercent`, `CodeDescription`, `IsActive`, `ValidUntil`) VALUES
(1, 'SHOE-LOUNGE-10', 10.00, 'Concierge welcome treat for the ShoeStore lounge', 1, '2026-01-01 23:59:59'),
(2, 'SNEAKER-STAPLE-15', 15.00, 'Daily sneaker staples curated by ShoeStore stylists', 1, '2025-12-31 23:59:59'),
(3, 'BOOT-BOUTIQUE-20', 20.00, 'Boot boutique spotlight for seasonal icons', 1, '2025-12-15 23:59:59'),
(4, 'RUNWAY-RUSH-25', 25.00, 'Runway rush drop for limited-edition pairs', 1, '2025-11-30 23:59:59');

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

CREATE TABLE `comment` (
  `CommentID` int(11) NOT NULL,
  `Mem_ID` int(11) DEFAULT NULL,
  `Rating` int(11) DEFAULT NULL,
  `Date` date DEFAULT NULL,
  `ShoesID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comment`
--

INSERT INTO `comment` (`CommentID`, `Mem_ID`, `Rating`, `Date`, `ShoesID`) VALUES
(1, 1, 5, '2025-04-03', 1),
(2, 1, 5, '2024-04-01', 1),
(3, 2, 4, '2024-04-02', 2),
(4, 3, 3, '2024-04-03', 3),
(5, 4, 5, '2024-04-04', 4),
(6, 5, 2, '2024-04-05', 5),
(7, 6, 4, '2024-04-06', 2),
(8, 7, 5, '2024-04-07', 9),
(9, 8, 3, '2024-04-08', 8),
(10, 9, 4, '2024-04-09', 1),
(11, 10, 5, '2024-04-10', 10);

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `ContactID` int(11) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Phone` varchar(20) DEFAULT NULL,
  `Message` text DEFAULT NULL,
  `Status` varchar(50) DEFAULT NULL,
  `AdminID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`ContactID`, `Name`, `Phone`, `Message`, `Status`, `AdminID`) VALUES
(1, 'Alex Carter', '0909333444', 'Requesting a size exchange', 'Pending', 1),
(2, 'Nathan Nguyen', '0911000101', 'Need help with order #1', 'Open', 1),
(3, 'Brian Tran', '0911000102', 'Question about return policy', 'Closed', 2),
(4, 'Liam Lee', '0911000103', 'Reporting a defective product', 'Open', 3),
(5, 'Daniel Pham', '0911000104', 'Sizing advice needed', 'Closed', 4),
(6, 'Ethan Hoang', '0911000105', 'Feedback on delivery service', 'Open', 5),
(7, 'Victor Vu', '0911000106', 'Price clarification request', 'Open', 6),
(8, 'Bella Bui', '0911000107', 'Requesting order cancellation', 'Closed', 7),
(9, 'Hannah Dang', '0911000108', 'Question about promotions', 'Open', 8),
(10, 'Isla Ha', '0911000109', 'Website UI suggestion', 'Closed', 9),
(11, 'Jason Phan', '0911000110', 'Feedback on shopping experience', 'Open', 10);

-- --------------------------------------------------------

--
-- Table structure for table `member`
--

CREATE TABLE `member` (
  `MemberID` int(11) NOT NULL,
  `Username` varchar(50) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `Phone` varchar(20) DEFAULT NULL,
  `Exp_VIP` date DEFAULT NULL,
  `AdminID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `member`
--

INSERT INTO `member` (`MemberID`, `Username`, `Password`, `Name`, `Email`, `Phone`, `Exp_VIP`, `AdminID`) VALUES
(1, 'user1', 'userpass', 'Bob Tran', 'bob@email.com', '0909111222', '2025-12-31', 1),
(2, 'user1', 'pwd1', 'Nam Nguyen', 'nam1@mail.com', '0911000001', '2025-12-31', 1),
(3, 'user2', 'pwd2', 'Linh Tran', 'linh2@mail.com', '0911000002', '2025-12-30', 1),
(4, 'user3', 'pwd3', 'Hung Pham', 'hung3@mail.com', '0911000003', '2025-12-29', 2),
(5, 'user4', 'pwd4', 'Mai Le', 'mai4@mail.com', '0911000004', '2025-12-28', 2),
(6, 'user5', 'pwd5', 'Minh Do', 'minh5@mail.com', '0911000005', '2025-12-27', 3),
(7, 'user6', 'pwd6', 'Thao Ho', 'thao6@mail.com', '0911000006', '2025-12-26', 3),
(8, 'user7', 'pwd7', 'Khang Vo', 'khang7@mail.com', '0911000007', '2025-12-25', 4),
(9, 'user8', 'pwd8', 'Yen Bui', 'yen8@mail.com', '0911000008', '2025-12-24', 4),
(10, 'user9', 'pwd9', 'Quan Dang', 'quan9@mail.com', '0911000009', '2025-12-23', 5),
(11, 'user10', 'pwd10', 'Ha Vu', 'ha10@mail.com', '0911000010', '2025-12-22', 5);

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE `news` (
  `NewsID` int(11) NOT NULL,
  `Title` varchar(200) DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `Content` text DEFAULT NULL,
  `AdminID` int(11) DEFAULT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `DateCreated` datetime DEFAULT current_timestamp(),
  `news_type` varchar(50) DEFAULT 'normal',
  `promotion_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- (Seed data intentionally omitted; use dedicated news seeders.)

-- --------------------------------------------------------

--
-- Table structure for table `news_clicks`
--

CREATE TABLE `news_clicks` (
  `click_id` int(11) NOT NULL,
  `news_id` int(11) NOT NULL,
  `click_count` int(11) DEFAULT 0,
  `last_clicked_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `news_clicks`
--
-- (Seed data intentionally omitted; use dedicated seeders.)

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `OrderID` int(11) NOT NULL,
  `MemberID` int(11) DEFAULT NULL,
  `Total_price` decimal(10,2) DEFAULT NULL,
  `Quantity` int(11) DEFAULT NULL,
  `Date` date DEFAULT NULL,
  `Earned_VIP` decimal(10,2) DEFAULT NULL,
  `Status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`OrderID`, `MemberID`, `Total_price`, `Quantity`, `Date`, `Earned_VIP`, `Status`) VALUES
(1, 1, 2500000.00, 1, '2025-04-02', 100000.00, 'Delivered'),
(2, 1, 240.00, 2, '2024-04-11', 10.00, 'Delivered'),
(3, 2, 150.00, 1, '2024-04-12', 0.00, 'Pending'),
(4, 3, 130.00, 2, '2024-04-13', 0.00, 'Cancelled'),
(5, 4, 70.00, 1, '2024-04-14', 5.00, 'Delivered'),
(6, 5, 180.00, 1, '2024-04-15', 8.00, 'Shipped'),
(7, 6, 135.00, 3, '2024-04-16', 12.00, 'Delivered'),
(8, 7, 200.00, 2, '2024-04-17', 0.00, 'Processing'),
(9, 8, 35.00, 1, '2024-04-18', 3.00, 'Delivered'),
(10, 9, 210.00, 3, '2024-04-19', 15.00, 'Shipped'),
(11, 10, 95.00, 1, '2024-04-20', 0.00, 'Pending'),
(12, 1, 240.00, 2, '2024-04-11', 10.00, 'Delivered'),
(13, 2, 150.00, 1, '2024-04-12', 0.00, 'Pending'),
(14, 3, 130.00, 2, '2024-04-13', 0.00, 'Cancelled'),
(15, 4, 70.00, 1, '2024-04-14', 5.00, 'Delivered'),
(16, 5, 180.00, 1, '2024-04-15', 8.00, 'Shipped'),
(17, 6, 135.00, 3, '2024-04-16', 12.00, 'Delivered'),
(18, 7, 200.00, 2, '2024-04-17', 0.00, 'Processing'),
(19, 8, 35.00, 1, '2024-04-18', 3.00, 'Delivered'),
(20, 9, 210.00, 3, '2024-04-19', 15.00, 'Shipped'),
(21, 10, 95.00, 1, '2024-04-20', 0.00, 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `order_shoes`
--

CREATE TABLE `order_shoes` (
  `OrderID` int(11) NOT NULL,
  `ShoesID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_shoes`
--

INSERT INTO `order_shoes` (`OrderID`, `ShoesID`) VALUES
(1, 1),
(1, 2),
(2, 3),
(3, 3),
(3, 4),
(4, 4),
(5, 5),
(6, 2),
(6, 8),
(7, 9),
(7, 10),
(8, 8),
(9, 1),
(9, 9),
(10, 10);

-- --------------------------------------------------------

--
-- Table structure for table `promotions`
--

CREATE TABLE `promotions` (
  `promotion_id` int(11) NOT NULL,
  `promotion_type` varchar(50) NOT NULL,
  `promotion_name` varchar(100) NOT NULL,
  `discount_percentage` decimal(5,2) DEFAULT NULL,
  `fixed_price` decimal(10,2) DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `promotions`
--

INSERT INTO `promotions` (`promotion_id`, `promotion_type`, `promotion_name`, `discount_percentage`, `fixed_price`, `start_date`, `end_date`) VALUES
(1, 'discount', 'Flash Sale 50%', 50.00, NULL, '2025-04-16 00:00:00', '2025-05-09 23:59:00'),
(2, 'fixed', 'Unbeatable $10 Deal', NULL, 10.00, '2025-04-29 00:00:00', '2025-06-07 23:59:00'),
(3, 'fixed', 'Warehouse Blowout $20', NULL, 20.00, '2025-04-29 00:00:00', '2025-05-30 23:59:00'),
(4, 'discount', 'Sale 10%', 10.00, NULL, '2025-05-01 00:04:00', '2025-05-29 00:05:00'),
(5, 'discount', 'Sale 20%', 20.00, NULL, '2025-05-03 00:08:00', '2025-05-29 00:08:00'),
(6, 'discount', 'Sale 90%', 90.00, NULL, '2025-04-09 17:35:00', '2025-05-08 17:35:00'),
(7, 'discount', 'Sale 40%', 40.00, NULL, '2025-04-12 17:35:00', '2025-05-09 17:35:00'),
(8, 'discount', 'Sale 30%', 30.00, NULL, '2025-04-19 17:35:00', '2025-05-09 17:35:00'),
(13, 'fixed', '$30 Price Lock', NULL, 30.00, '2025-04-30 00:17:00', '2025-06-05 00:17:00');

-- --------------------------------------------------------

--
-- Table structure for table `promotion_shoes`
--

CREATE TABLE `promotion_shoes` (
  `promotion_id` int(11) NOT NULL,
  `shoe_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `promotion_shoes`
--

INSERT INTO `promotion_shoes` (`promotion_id`, `shoe_id`) VALUES
(1, 1),
(2, 4),
(3, 2),
(4, 5),
(5, 3),
(6, 6),
(7, 7),
(7, 8),
(8, 9),
(8, 10),
(13, 11);

-- --------------------------------------------------------

--
-- Table structure for table `shoes`
--

CREATE TABLE `shoes` (
  `ShoesID` int(11) NOT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `Price` decimal(10,2) DEFAULT NULL,
  `Stock` int(11) DEFAULT NULL,
  `Description` text DEFAULT NULL,
  `DateCreate` date DEFAULT NULL,
  `DateUpdate` date DEFAULT NULL,
  `Image` varchar(255) DEFAULT NULL,
  `CategoryID` int(11) DEFAULT NULL,
  `shoes_size` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shoes`
--

INSERT INTO `shoes` (`ShoesID`, `Name`, `Price`, `Stock`, `Description`, `DateCreate`, `DateUpdate`, `Image`, `CategoryID`, `shoes_size`) VALUES
(1, 'Nike Air Max', 2500.00, 100, 'Premium running shoe with Air cushioning', '2025-01-01', '2025-05-07', 'https://sneakerdaily.vn/wp-content/uploads/2024/01/Giay-Nike-Air-Max-1-White-Black-FD9082-107-1.jpg', 1, 38.00),
(2, 'Dr. Martens', 3000000.00, 50, 'Fashion-forward leather boot', '2025-02-01', '2025-03-15', 'https://images-na.ssl-images-amazon.com/images/I/71LL6yCVP4L.jpg', 2, 39.00),
(3, 'Nike Air Zoom', 120.00, 50, 'High-performance running sneaker', '2024-01-01', '2024-03-01', 'https://supersports.com.vn/cdn/shop/files/FD2722-002-2_1024x1024.jpg?v=1726656415', 1, 40.00),
(4, 'Adidas Ultraboost', 150.00, 30, 'Adidas performance trainer', '2024-02-01', '2024-04-01', 'https://product.hstatic.net/1000361048/product/giay_ultraboost_light_djen_gy9351_01_standard_f5f5bedd68df46a9bc78d9dcdccb49f8_master.jpg', 1, 41.00),
(5, 'Converse Classic', 60.00, 40, 'Classic Converse high-top', '2024-01-15', '2024-03-10', 'https://drake.vn/image/cache/catalog/Converse/GIA%CC%80Y%202/M9160C/M9160C_1-650x650.jpg', 2, 42.00),
(6, 'Vans Old Skool', 70.00, 35, 'Streetwear-ready Vans sneaker', '2024-02-10', '2024-04-12', 'https://product.hstatic.net/1000382698/product/vn0a5fcby28-2s_147ed67b9ed04d679f3a56e5e9ae2595_master.jpg', 2, 43.00),
(7, 'Timberland Boots', 180.00, 20, 'Premium waterproof boot', '2024-01-25', '2024-04-05', 'https://assets.timberland.com/images/t_img/f_auto,h_650,w_650,e_sharpen:60/dpr_2.0/v1719373359/TB165016713-HERO/Mens-Direct-Attach-6-Steel-Toe-Waterproof-Work-Boot.png', 3, 44.00),
(8, 'Nike Sandals', 40.00, 25, 'Casual Nike sandal', '2024-03-01', '2024-03-20', 'https://supersports.com.vn/cdn/shop/files/FJ6043-001-1_1200x1200.jpg?v=1725613858', 4, 45.00),
(9, 'Oxford Shoes', 90.00, 15, 'Polished oxford dress shoe', '2024-01-18', '2024-02-25', 'https://www.beckettsimonon.com/cdn/shop/products/color_black_1_dean_oxford.jpg?v=1618340935', 5, 46.00),
(10, 'Adidas Slides', 35.00, 60, 'Adidas comfort slides', '2024-02-22', '2024-03-30', 'https://assets.adidas.com/images/w_600,f_auto,q_auto/854a6fec31404ffd8cfaaf4200bd0b13_9366/Dep_adilette_22_trang_HQ4672_01_standard.jpg', 6, 47.00),
(11, 'Jordan 1', 200.00, 10, 'Iconic Jordan basketball shoe', '2024-01-05', '2024-04-10', 'https://product.hstatic.net/200000858039/product/jordan-1-high-black-white-trang-den_5f542b2addee453e9868730c6623d06b.png', 7, 48.00),
(12, 'Nike Tiempo', 95.00, 12, 'Nike Tiempo soccer cleat', '2024-02-14', '2024-03-28', 'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/a0f2b725-0806-41ce-b437-e0c3eacfba09/LEGEND+10+ELITE+FG+NU1.png', 8, 49.00),
(13, 'Luminous Glide', 185.00, 28, 'Light-reactive runner inspired by neon bazaars.', '2025-03-02', '2025-05-01', 'https://images.unsplash.com/photo-1519741497674-611481863552?auto=format&fit=crop&w=900&q=80', 1, 40.00),
(14, 'Monsoon Trekker', 220.00, 18, 'Seam-sealed boot for city storms and hikes.', '2025-02-14', '2025-04-11', 'https://images.unsplash.com/photo-1460353581641-37baddab0fa2?auto=format&fit=crop&w=900&q=80', 2, 42.00),
(15, 'Cerulean Slide', 58.00, 42, 'Pool-ready comfort slide with algae foam.', '2025-03-21', '2025-04-30', 'https://images.unsplash.com/photo-1528701800489-20be3c4f5691?auto=format&fit=crop&w=900&q=80', 6, 43.00),
(16, 'Midnight Derby', 145.00, 26, 'Dress shoe lined with eucalyptus fiber.', '2025-01-18', '2025-05-03', 'https://images.unsplash.com/photo-1521572267360-ee0c2909d518?auto=format&fit=crop&w=900&q=80', 5, 44.00),
(17, 'Atlas Voyager', 210.00, 32, 'Hybrid sneaker-boot for long-haul travelers.', '2025-02-05', '2025-05-05', 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?auto=format&fit=crop&w=900&q=80', 3, 45.00),
(18, 'Studio Whisper', 125.00, 38, 'Ultra-quiet sole for dancers and presenters.', '2025-03-08', '2025-04-25', 'https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=900&q=80', 7, 39.00),
(19, 'Lagoon Runner', 175.00, 22, 'Breathable mesh runner dyed with seaweed pigments.', '2025-03-15', '2025-05-06', 'https://images.unsplash.com/photo-1500530855697-b586d89ba3ee?auto=format&fit=crop&w=900&q=80', 4, 41.00),
(20, 'Amber Pathway', 205.00, 17, 'Hand-burnished leather boot with sheepskin collar.', '2025-02-27', '2025-05-04', 'https://images.unsplash.com/photo-1475180098004-ca77a66827be?auto=format&fit=crop&w=900&q=80', 2, 43.00),
(21, 'Orbit Cleat', 130.00, 24, 'Next-gen soccer cleat with micro traction domes.', '2025-03-10', '2025-05-02', 'https://images.unsplash.com/photo-1460353581641-37baddab0fa2?auto=format&fit=crop&w=900&q=80', 8, 44.00),
(22, 'Velvet Harbor', 165.00, 19, 'Loafer crafted from marine velvet with cork footbed.', '2025-03-04', '2025-05-01', 'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=900&q=80', 5, 42.00),
(23, 'Solar Pulse', 190.00, 27, 'High-top with photovoltaic lace guards charging NFC tag.', '2025-02-23', '2025-05-07', 'https://images.unsplash.com/photo-1503341455253-b2e723bb3dbb?auto=format&fit=crop&w=900&q=80', 1, 40.50),
(24, 'Nimbus Slide', 78.00, 55, 'Indoor slide sculpted from memory foam clouds.', '2025-03-30', '2025-05-06', 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?auto=format&fit=crop&w=900&q=80', 6, 41.00);

-- --------------------------------------------------------

--
-- Table structure for table `shoe_sizes`
--

CREATE TABLE `shoe_sizes` (
  `SizeID` int(11) NOT NULL AUTO_INCREMENT,
  `ShoeID` int(11) NOT NULL,
  `Size` decimal(5,2) NOT NULL,
  `Quantity` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`SizeID`),
  UNIQUE KEY `uniq_shoe_size` (`ShoeID`,`Size`),
  KEY `ShoeID` (`ShoeID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shoe_sizes`
--

INSERT INTO `shoe_sizes` (`ShoeID`, `Size`, `Quantity`)
SELECT seed.ShoesID,
       ROUND(seed.base_size + offsets.offset, 2) AS Size,
       CASE offsets.offset
           WHEN -1 THEN CASE WHEN seed.total_stock <= 0 THEN 0 ELSE seed.base_qty + CASE WHEN seed.remainder > 0 THEN 1 ELSE 0 END END
           WHEN 0 THEN CASE WHEN seed.total_stock <= 0 THEN 0 ELSE seed.base_qty + CASE WHEN seed.remainder > 1 THEN 1 ELSE 0 END END
           ELSE CASE WHEN seed.total_stock <= 0 THEN 0 ELSE GREATEST(0, seed.total_stock - (seed.base_qty * 2 + CASE WHEN seed.remainder > 0 THEN 1 ELSE 0 END + CASE WHEN seed.remainder > 1 THEN 1 ELSE 0 END)) END
       END AS Quantity
FROM (
    SELECT s.ShoesID,
           ROUND(COALESCE(s.shoes_size, 40.00), 2) AS base_size,
           GREATEST(COALESCE(s.Stock, 0), 0) AS total_stock,
           FLOOR(GREATEST(COALESCE(s.Stock, 0), 0) / 3) AS base_qty,
           MOD(GREATEST(COALESCE(s.Stock, 0), 0), 3) AS remainder
    FROM shoes s
) AS seed
JOIN (
    SELECT -1 AS offset
    UNION ALL SELECT 0
    UNION ALL SELECT 1
) AS offsets;

UPDATE `shoes` s
SET s.Stock = (
    SELECT COALESCE(SUM(ss.Quantity), 0)
    FROM shoe_sizes ss
    WHERE ss.ShoeID = s.ShoesID
);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`AdminID`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`CartID`),
  ADD KEY `MemberID` (`MemberID`);

--
-- Indexes for table `cart_shoes`
--
ALTER TABLE `cart_shoes`
  ADD PRIMARY KEY (`CartID`,`ShoesID`),
  ADD KEY `ShoesID` (`ShoesID`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`CategoryID`);

--
-- Indexes for table `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`CommentID`),
  ADD KEY `Mem_ID` (`Mem_ID`),
  ADD KEY `ShoesID` (`ShoesID`);

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`ContactID`),
  ADD KEY `AdminID` (`AdminID`);

--
-- Indexes for table `discount_codes`
--
ALTER TABLE `discount_codes`
  ADD PRIMARY KEY (`CodeID`);

--
-- Indexes for table `member`
--
ALTER TABLE `member`
  ADD PRIMARY KEY (`MemberID`),
  ADD KEY `AdminID` (`AdminID`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`NewsID`),
  ADD KEY `AdminID` (`AdminID`),
  ADD KEY `fk_news_promotion` (`promotion_id`);

--
-- Indexes for table `news_clicks`
--
ALTER TABLE `news_clicks`
  ADD PRIMARY KEY (`click_id`),
  ADD KEY `news_id` (`news_id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `MemberID` (`MemberID`);

--
-- Indexes for table `order_shoes`
--
ALTER TABLE `order_shoes`
  ADD PRIMARY KEY (`OrderID`,`ShoesID`),
  ADD KEY `ShoesID` (`ShoesID`);

--
-- Indexes for table `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`promotion_id`);

--
-- Indexes for table `promotion_shoes`
--
ALTER TABLE `promotion_shoes`
  ADD PRIMARY KEY (`promotion_id`,`shoe_id`),
  ADD KEY `promotion_shoes_ibfk_2` (`shoe_id`);

--
-- Indexes for table `shoes`
--
ALTER TABLE `shoes`
  ADD PRIMARY KEY (`ShoesID`),
  ADD KEY `CategoryID` (`CategoryID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `AdminID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `CartID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `CategoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `comment`
--
ALTER TABLE `comment`
  MODIFY `CommentID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `ContactID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `discount_codes`
--
ALTER TABLE `discount_codes`
  MODIFY `CodeID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `member`
--
ALTER TABLE `member`
  MODIFY `MemberID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `NewsID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `news_clicks`
--
ALTER TABLE `news_clicks`
  MODIFY `click_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `OrderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `promotions`
--
ALTER TABLE `promotions`
  MODIFY `promotion_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `shoes`
--
ALTER TABLE `shoes`
  MODIFY `ShoesID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`MemberID`) REFERENCES `member` (`MemberID`);

--
-- Constraints for table `cart_shoes`
--
ALTER TABLE `cart_shoes`
  ADD CONSTRAINT `cart_shoes_ibfk_1` FOREIGN KEY (`CartID`) REFERENCES `cart` (`CartID`),
  ADD CONSTRAINT `cart_shoes_ibfk_2` FOREIGN KEY (`ShoesID`) REFERENCES `shoes` (`ShoesID`);

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`Mem_ID`) REFERENCES `member` (`MemberID`),
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`ShoesID`) REFERENCES `shoes` (`ShoesID`);

--
-- Constraints for table `contact`
--
ALTER TABLE `contact`
  ADD CONSTRAINT `contact_ibfk_1` FOREIGN KEY (`AdminID`) REFERENCES `admin` (`AdminID`);

--
-- Constraints for table `member`
--
ALTER TABLE `member`
  ADD CONSTRAINT `member_ibfk_1` FOREIGN KEY (`AdminID`) REFERENCES `admin` (`AdminID`);

--
-- Constraints for table `news`
--
ALTER TABLE `news`
  ADD CONSTRAINT `fk_news_promotion` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`promotion_id`),
  ADD CONSTRAINT `news_ibfk_1` FOREIGN KEY (`AdminID`) REFERENCES `admin` (`AdminID`);

--
-- Constraints for table `news_clicks`
--
ALTER TABLE `news_clicks`
  ADD CONSTRAINT `news_clicks_ibfk_1` FOREIGN KEY (`news_id`) REFERENCES `news` (`NewsID`) ON DELETE CASCADE;

--
-- Constraints for table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`MemberID`) REFERENCES `member` (`MemberID`);

--
-- Constraints for table `order_shoes`
--
ALTER TABLE `order_shoes`
  ADD CONSTRAINT `order_shoes_ibfk_1` FOREIGN KEY (`OrderID`) REFERENCES `order` (`OrderID`),
  ADD CONSTRAINT `order_shoes_ibfk_2` FOREIGN KEY (`ShoesID`) REFERENCES `shoes` (`ShoesID`);

--
-- Constraints for table `promotion_shoes`
--
ALTER TABLE `promotion_shoes`
  ADD CONSTRAINT `promotion_shoes_ibfk_1` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`promotion_id`),
  ADD CONSTRAINT `promotion_shoes_ibfk_2` FOREIGN KEY (`shoe_id`) REFERENCES `shoes` (`ShoesID`) ON DELETE CASCADE;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `fk_sales_shoes` FOREIGN KEY (`ShoesID`) REFERENCES `shoes` (`ShoesID`) ON DELETE CASCADE;

--
-- Constraints for table `shoes`
--
ALTER TABLE `shoes`
  ADD CONSTRAINT `shoes_ibfk_1` FOREIGN KEY (`CategoryID`) REFERENCES `category` (`CategoryID`);

--
-- Constraints for table `shoe_sizes`
--
ALTER TABLE `shoe_sizes`
  ADD CONSTRAINT `shoe_sizes_ibfk_1` FOREIGN KEY (`ShoeID`) REFERENCES `shoes` (`ShoesID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
