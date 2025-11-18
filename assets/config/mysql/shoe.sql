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
  `Description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`CategoryID`, `Name`, `Description`) VALUES
(1, 'Sneakers', 'Giày thể thao'),
(2, 'Boots', 'Giày boot'),
(3, 'Sandals', 'Dép sandal'),
(4, 'Running', 'Giày chạy bộ'),
(5, 'Sneakers', 'Giày sneaker thời trang'),
(6, 'Boots', 'Giày bốt nam/nữ'),
(7, 'Sandals', 'Giày dép sandal'),
(8, 'Formal', 'Giày tây công sở'),
(9, 'Slippers', 'Dép đi trong nhà'),
(10, 'Basketball', 'Giày bóng rổ'),
(11, 'Soccer', 'Giày đá bóng'),
(12, 'Skateboarding', 'Giày trượt ván'),
(13, 'Casual', 'Giày đi hàng ngày');

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
(1, 'Khách A', '0909333444', 'Tôi muốn đổi size', 'Chưa xử lý', 1),
(2, 'Nguyễn A', '0911000101', 'Cần hỗ trợ đơn hàng #1', 'Open', 1),
(3, 'Trần B', '0911000102', 'Hỏi về chính sách đổi trả', 'Closed', 2),
(4, 'Lê C', '0911000103', 'Phản hồi về sản phẩm lỗi', 'Open', 3),
(5, 'Phạm D', '0911000104', 'Yêu cầu tư vấn kích cỡ', 'Closed', 4),
(6, 'Hoàng E', '0911000105', 'Phản hồi dịch vụ giao hàng', 'Open', 5),
(7, 'Vũ F', '0911000106', 'Thắc mắc về giá', 'Open', 6),
(8, 'Bùi G', '0911000107', 'Yêu cầu hủy đơn', 'Closed', 7),
(9, 'Đặng H', '0911000108', 'Hỏi về khuyến mãi', 'Open', 8),
(10, 'Hà I', '0911000109', 'Góp ý giao diện website', 'Closed', 9),
(11, 'Phan J', '0911000110', 'Phản hồi trải nghiệm mua hàng', 'Open', 10);

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
(2, 'user1', 'pwd1', 'Nam Nguyễn', 'nam1@mail.com', '0911000001', '2025-12-31', 1),
(3, 'user2', 'pwd2', 'Linh Trần', 'linh2@mail.com', '0911000002', '2025-12-30', 1),
(4, 'user3', 'pwd3', 'Hùng Phạm', 'hung3@mail.com', '0911000003', '2025-12-29', 2),
(5, 'user4', 'pwd4', 'Mai Lê', 'mai4@mail.com', '0911000004', '2025-12-28', 2),
(6, 'user5', 'pwd5', 'Minh Đỗ', 'minh5@mail.com', '0911000005', '2025-12-27', 3),
(7, 'user6', 'pwd6', 'Thảo Hồ', 'thao6@mail.com', '0911000006', '2025-12-26', 3),
(8, 'user7', 'pwd7', 'Khang Võ', 'khang7@mail.com', '0911000007', '2025-12-25', 4),
(9, 'user8', 'pwd8', 'Yến Bùi', 'yen8@mail.com', '0911000008', '2025-12-24', 4),
(10, 'user9', 'pwd9', 'Quân Đặng', 'quan9@mail.com', '0911000009', '2025-12-23', 5),
(11, 'user10', 'pwd10', 'Hà Vũ', 'ha10@mail.com', '0911000010', '2025-12-22', 5);

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

INSERT INTO `news_clicks` (`click_id`, `news_id`, `click_count`, `last_clicked_at`) VALUES
(1, 4, 16, '2025-05-06 03:39:29'),
(2, 6, 29, '2025-05-07 07:49:48'),
(3, 5, 6, '2025-05-07 07:49:49'),
(4, 7, 5, '2025-05-06 11:30:18'),
(5, 3, 11, '2025-05-06 11:34:43'),
(6, 1, 26, '2025-05-07 07:50:49'),
(7, 2, 28, '2025-05-07 07:47:39'),
(8, 19, 7, '2025-05-06 11:35:35'),
(9, 18, 7, '2025-05-06 11:35:37'),
(10, 11, 4, '2025-05-06 14:50:11'),
(11, 8, 3, '2025-05-07 07:49:51'),
(12, 9, 1, '2025-05-06 11:35:32');

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
(2, 'fixed', 'Rẻ Vô Địch 10$', NULL, 10.00, '2025-04-29 00:00:00', '2025-06-07 23:59:00'),
(3, 'fixed', 'Xả kho 20$', NULL, 20.00, '2025-04-29 00:00:00', '2025-05-30 23:59:00'),
(4, 'discount', 'Sale 10%', 10.00, NULL, '2025-05-01 00:04:00', '2025-05-29 00:05:00'),
(5, 'discount', 'Sale 20%', 20.00, NULL, '2025-05-03 00:08:00', '2025-05-29 00:08:00'),
(6, 'discount', 'Sale 90%', 90.00, NULL, '2025-04-09 17:35:00', '2025-05-08 17:35:00'),
(7, 'discount', 'Sale 40%', 40.00, NULL, '2025-04-12 17:35:00', '2025-05-09 17:35:00'),
(8, 'discount', 'Sale 30%', 30.00, NULL, '2025-04-19 17:35:00', '2025-05-09 17:35:00'),
(13, 'fixed', '30$', NULL, 30.00, '2025-04-30 00:17:00', '2025-06-05 00:17:00');


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
(1, 'Nike Air Max', 2500.00, 100, 'Giày chạy bộ cao cấp', '2025-01-01', '2025-05-07', 'https://sneakerdaily.vn/wp-content/uploads/2024/01/Giay-Nike-Air-Max-1-White-Black-FD9082-107-1.jpg', 1, 38.00),
(2, 'Dr. Martens', 3000000.00, 50, 'Giày boot thời trang', '2025-02-01', '2025-03-15', 'https://images-na.ssl-images-amazon.com/images/I/71LL6yCVP4L.jpg', 2, 39.00),
(3, 'Nike Air Zoom', 120.00, 50, 'Giày chạy bộ cao cấp', '2024-01-01', '2024-03-01', 'https://supersports.com.vn/cdn/shop/files/FD2722-002-2_1024x1024.jpg?v=1726656415', 1, 40.00),
(4, 'Adidas Ultraboost', 150.00, 30, 'Giày thể thao Adidas', '2024-02-01', '2024-04-01', 'https://product.hstatic.net/1000361048/product/giay_ultraboost_light_djen_gy9351_01_standard_f5f5bedd68df46a9bc78d9dcdccb49f8_master.jpg', 1, 41.00),
(5, 'Converse Classic', 60.00, 40, 'Giày cổ điển Converse', '2024-01-15', '2024-03-10', 'https://drake.vn/image/cache/catalog/Converse/GIA%CC%80Y%202/M9160C/M9160C_1-650x650.jpg', 2, 42.00),
(6, 'Vans Old Skool', 70.00, 35, 'Giày Vans thời trang', '2024-02-10', '2024-04-12', 'https://product.hstatic.net/1000382698/product/vn0a5fcby28-2s_147ed67b9ed04d679f3a56e5e9ae2595_master.jpg', 2, 43.00),
(7, 'Timberland Boots', 180.00, 20, 'Giày bốt cao cấp', '2024-01-25', '2024-04-05', 'https://assets.timberland.com/images/t_img/f_auto,h_650,w_650,e_sharpen:60/dpr_2.0/v1719373359/TB165016713-HERO/Mens-Direct-Attach-6-Steel-Toe-Waterproof-Work-Boot.png', 3, 44.00),
(8, 'Nike Sandals', 40.00, 25, 'Dép sandal Nike', '2024-03-01', '2024-03-20', 'https://supersports.com.vn/cdn/shop/files/FJ6043-001-1_1200x1200.jpg?v=1725613858', 4, 45.00),
(9, 'Oxford Shoes', 90.00, 15, 'Giày tây nam lịch sự', '2024-01-18', '2024-02-25', 'https://www.beckettsimonon.com/cdn/shop/products/color_black_1_dean_oxford.jpg?v=1618340935', 5, 46.00),
(10, 'Adidas Slides', 35.00, 60, 'Dép đi trong nhà Adidas', '2024-02-22', '2024-03-30', 'https://assets.adidas.com/images/w_600,f_auto,q_auto/854a6fec31404ffd8cfaaf4200bd0b13_9366/Dep_adilette_22_trang_HQ4672_01_standard.jpg', 6, 47.00),
(11, 'Jordan 1', 200.00, 10, 'Giày bóng rổ Jordan', '2024-01-05', '2024-04-10', 'https://product.hstatic.net/200000858039/product/jordan-1-high-black-white-trang-den_5f542b2addee453e9868730c6623d06b.png', 7, 48.00),
(12, 'Nike Tiempo', 95.00, 12, 'Giày đá bóng Nike Tiempo', '2024-02-14', '2024-03-28', 'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/a0f2b725-0806-41ce-b437-e0c3eacfba09/LEGEND+10+ELITE+FG+NU1.png', 8, 49.00);

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
-- Constraints for table `shoes`
--
ALTER TABLE `shoes`
  ADD CONSTRAINT `shoes_ibfk_1` FOREIGN KEY (`CategoryID`) REFERENCES `category` (`CategoryID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
