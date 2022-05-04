-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: database:3306
-- Generation Time: Dec 29, 2021 at 03:33 PM
-- Server version: 10.4.22-MariaDB-1:10.4.22+maria~focal
-- PHP Version: 7.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `webshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `ID` bigint(8) UNSIGNED NOT NULL,
  `name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`ID`, `name`) VALUES
(1, 'Skimmed'),
(2, 'Low fat'),
(3, 'Whole'),
(4, 'Lactose-Free'),
(5, 'Milk'),
(6, 'Cheese'),
(7, 'Vegan');

-- --------------------------------------------------------

--
-- Table structure for table `orderProducts`
--

CREATE TABLE `orderProducts` (
  `orderID` bigint(8) UNSIGNED NOT NULL,
  `productID` bigint(8) UNSIGNED NOT NULL,
  `amount` bigint(8) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `orderProducts`
--

INSERT INTO `orderProducts` (`orderID`, `productID`, `amount`) VALUES
(3, 28, 1),
(3, 29, 1),
(3, 30, 147),
(4, 27, 1),
(5, 27, 1),
(6, 27, 1),
(7, 27, 2),
(7, 29, 1),
(7, 30, 1),
(8, 30, 8),
(9, 27, 1),
(10, 30, 1),
(11, 30, 1),
(11, 39, 2),
(11, 40, 1),
(12, 30, 50),
(12, 39, 1),
(13, 39, 20),
(14, 30, 1),
(15, 27, 1),
(15, 28, 1),
(15, 29, 1),
(15, 39, 1),
(15, 40, 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `ID` bigint(8) UNSIGNED NOT NULL,
  `orderDate` date DEFAULT NULL,
  `paymentDate` date DEFAULT NULL,
  `shipDate` date DEFAULT NULL,
  `fulfilled` tinyint(1) DEFAULT 0,
  `userID` bigint(8) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`ID`, `orderDate`, `paymentDate`, `shipDate`, `fulfilled`, `userID`) VALUES
(3, '2021-12-26', '2021-12-26', '2021-12-26', 1, 2),
(4, '2021-12-26', '2021-12-26', '2021-12-26', 1, 2),
(5, '2021-12-26', '2021-12-26', '2021-12-26', 1, 2),
(6, '2021-12-28', '2021-12-28', '2021-12-28', 1, 2),
(7, '2021-12-28', '2021-12-28', '2021-12-28', 1, 3),
(8, '2021-12-28', '2021-12-28', '2021-12-28', 1, 3),
(9, '2021-12-29', '2021-12-29', '2021-12-29', 1, 2),
(10, '2021-12-28', '2021-12-28', '2021-12-28', 1, 3),
(11, '2021-12-28', '2021-12-28', '2021-12-28', 1, 3),
(12, '2021-12-29', '2021-12-29', '2021-12-29', 1, 5),
(13, '2021-12-29', '2021-12-29', '2021-12-29', 1, 5),
(14, '2021-12-29', '2021-12-29', '2021-12-29', 1, 5),
(15, '2021-12-29', '2021-12-29', '2021-12-29', 1, 2),
(16, NULL, NULL, NULL, 0, 2);

-- --------------------------------------------------------

--
-- Table structure for table `pictures`
--

CREATE TABLE `pictures` (
  `productID` bigint(8) UNSIGNED NOT NULL,
  `picturePath` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pictures`
--

INSERT INTO `pictures` (`productID`, `picturePath`) VALUES
(27, 'products/2021-12-25-14-25-40-id-27.jpg'),
(28, 'products/2021-12-25-14-26-16-id-28.jpg'),
(29, 'products/2021-12-25-14-26-57-id-29.jpg'),
(30, 'products/2021-12-25-14-30-32-id-30.jpg'),
(31, 'products/2021-12-25-14-33-19-id-31.jpg'),
(39, 'products/2021-12-28-17-14-39-id-39.jpg'),
(40, 'products/2021-12-28-17-20-37-id-40.jpg'),
(43, 'products/2021-12-29-13-53-25-id-43.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `productCategory`
--

CREATE TABLE `productCategory` (
  `productID` bigint(8) UNSIGNED NOT NULL,
  `categoryID` bigint(8) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `productCategory`
--

INSERT INTO `productCategory` (`productID`, `categoryID`) VALUES
(27, 2),
(27, 5),
(28, 3),
(28, 5),
(29, 1),
(29, 5),
(30, 2),
(30, 4),
(30, 5),
(30, 7),
(39, 6),
(40, 5);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `ID` bigint(8) UNSIGNED NOT NULL,
  `name` varchar(64) NOT NULL,
  `description` text NOT NULL,
  `unitPrice` decimal(10,2) NOT NULL,
  `unitsInStock` bigint(8) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`ID`, `name`, `description`, `unitPrice`, `unitsInStock`, `active`) VALUES
(27, 'Halfvolle melk', 'Lekkere halfvolle melk van Everyday', '0.65', 194, 1),
(28, 'Volle melk', 'Lekkere volle melk van Everyday.', '0.69', 199, 1),
(29, 'Magere melk', 'Lekkere magere melk van Everyday.', '0.59', 148, 1),
(30, 'Amandel melk', 'Deze amandelmelk is ongezoet en 100% plantaardig. De Amandelmelk van Blue Diamond is vrij van gluten en lactose en heeft een heerlijke smaak. De drank is een bron van calcium, potasium en vitamine A, B, D en E. Ideaal om te gebruiken voor een gezond en gebalanceerd dieet.', '1.85', 88, 1),
(31, ' Landers Premium Cheese ', 'Premium limited time Landers Cheese!', '580.00', 5, 0),
(37, 'Empty product', 'This product has no picture', '5.00', 15, 0),
(39, 'Geraspte kaas', 'Lekkere geraspte kaas omdat wij weten dat jij lui bent\r\n<3', '2.80', 4, 1),
(40, 'Melk poeder', 'DIY melk :)\r\n0.01g', '1.80', 1998, 1),
(42, '<script>alert(\'test\')</script>', 'This is a <strong>line</strong>\r\nThis is a new line!', '5.00', 15, 0),
(43, 'Anarchy dog', 'idk I kinda like him', '400.00', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `ID` int(10) UNSIGNED NOT NULL,
  `name` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`ID`, `name`) VALUES
(1, 'Admin'),
(2, 'Registered user');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` bigint(8) UNSIGNED NOT NULL,
  `firstName` varchar(64) NOT NULL,
  `lastName` varchar(64) NOT NULL,
  `email` varchar(255) NOT NULL,
  `passwordHash` varchar(255) NOT NULL,
  `address` varchar(128) NOT NULL,
  `billingAddress` varchar(128) DEFAULT NULL,
  `phone` varchar(64) DEFAULT NULL,
  `DOB` date DEFAULT NULL,
  `DOC` datetime NOT NULL DEFAULT current_timestamp(),
  `roleID` int(10) UNSIGNED NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `firstName`, `lastName`, `email`, `passwordHash`, `address`, `billingAddress`, `phone`, `DOB`, `DOC`, `roleID`, `active`) VALUES
(2, 'Peter', 'Leconte', 'peter5leconte@gmail.com', '$2y$10$yqStd7febdmn8lGG1ZFdHexwFMjRE6wnPxvjL9tB2Ok.Oscw177Jm', 'ma house 123', 'ma second house 456', '0478894512', '2002-03-24', '2021-12-25 11:37:10', 1, 1),
(3, 'A normal', 'user', 'example@email.com', '$2y$10$83xlAxpWbL7pMJz3UJraNOPFfc5iUgCuxDXRe.DQMjToXvaxeOx0O', 'Basic house', '', '', NULL, '2021-12-25 11:38:09', 2, 1),
(4, 'Test', 'Subject', 'test@gmail.com', '$2y$10$TURXh3sQnRlYdM41CP03Beu82PlRjRqVqFdlH6KQn3UL18YsbJUby', 'Booleanstreet 29, somewhere in belgium', 'Example billing address 400', '4564616547', '2002-11-14', '2021-12-27 20:48:31', 1, 1),
(5, 'Bartje', 'Goossens', 'test@test.com', '$2y$10$7rKa9Ct7QvjVgru.unXstOA7WGGTHVXCbT.ZQUC8iueFf7iIbbM0K', 'Bartje zijn straat, 45', NULL, NULL, '1968-12-06', '2021-12-29 14:28:46', 2, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `orderProducts`
--
ALTER TABLE `orderProducts`
  ADD PRIMARY KEY (`orderID`,`productID`),
  ADD KEY `productID` (`productID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `pictures`
--
ALTER TABLE `pictures`
  ADD PRIMARY KEY (`productID`,`picturePath`);

--
-- Indexes for table `productCategory`
--
ALTER TABLE `productCategory`
  ADD PRIMARY KEY (`productID`,`categoryID`),
  ADD KEY `categoryID` (`categoryID`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `roleID` (`roleID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `ID` bigint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `ID` bigint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `ID` bigint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` bigint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orderProducts`
--
ALTER TABLE `orderProducts`
  ADD CONSTRAINT `orderProducts_ibfk_1` FOREIGN KEY (`orderID`) REFERENCES `orders` (`ID`),
  ADD CONSTRAINT `orderProducts_ibfk_2` FOREIGN KEY (`productID`) REFERENCES `products` (`ID`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`ID`);

--
-- Constraints for table `pictures`
--
ALTER TABLE `pictures`
  ADD CONSTRAINT `pictures_ibfk_1` FOREIGN KEY (`productID`) REFERENCES `products` (`ID`);

--
-- Constraints for table `productCategory`
--
ALTER TABLE `productCategory`
  ADD CONSTRAINT `productCategory_ibfk_1` FOREIGN KEY (`productID`) REFERENCES `products` (`ID`),
  ADD CONSTRAINT `productCategory_ibfk_2` FOREIGN KEY (`categoryID`) REFERENCES `categories` (`ID`);

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`roleID`) REFERENCES `roles` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
