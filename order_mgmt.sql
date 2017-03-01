-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 01, 2017 at 07:54 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `order_mgmt`
--

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` varchar(16) CHARACTER SET latin1 NOT NULL,
  `comment` varchar(255) CHARACTER SET latin1 NOT NULL,
  `order_id` int(10) unsigned NOT NULL,
  `user_name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_comments_orderId` (`order_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=49 ;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `date`, `comment`, `order_id`, `user_name`) VALUES
(28, '17/02/2017 17:24', '<em><span style=''color:#FF0000''>*System comment:</span> <strong>Ship status</strong> changed from <strong>0</strong> to <strong>1</strong>.. </em>', 35, 'admin'),
(29, '17/02/2017 17:24', 'Shippped', 35, 'admin'),
(30, '17/02/2017 17:29', '<em><span style=''color:#FF0000''>*System comment:</span> <strong>Ship status</strong> changed from <strong>1</strong> to <strong>0</strong>.. </em>', 35, 'admin'),
(31, '17/02/2017 17:30', '<em><span style=''color:#FF0000''>*System comment:</span> <strong>Ship status</strong> changed from <strong>0</strong> to <strong>1</strong>.. </em>', 35, 'admin'),
(32, '17/02/2017 17:31', '<em><span style=''color:#FF0000''>*System comment:</span> <strong>Ship status</strong> changed from <strong>1</strong> to <strong>0</strong>.. </em>', 35, 'admin'),
(33, '17/02/2017 17:36', '<em><span style=''color:#FF0000''>*System comment:</span> <strong>Sender Name</strong> changed from <strong>Duy</strong> to <strong>DuyNguyen</strong>.. </em><em><span style=''color:#FF0000''>*System comment:</span> <strong>Receiver Name</strong> changed from', 35, 'admin'),
(34, '17/02/2017 17:37', '<em><span style=''color:#FF0000''>*System comment:</span> <strong>Sender Name</strong> changed from <strong>DuyNguyen</strong> to <strong>Duy</strong>.. </em><em><span style=''color:#FF0000''>*System comment:</span> <strong>Receiver Name</strong> changed from', 35, 'admin'),
(35, '17/02/2017 17:38', '<em><span style=''color:#FF0000''>*System comment:</span> <strong>Sender Name</strong> changed from <strong>Duy</strong> to <strong>DuyNguyenQuoc</strong>.. </em><em><span style=''color:#FF0000''>*System comment:</span> <strong>Address</strong> changed from <', 35, 'admin'),
(36, '17/02/2017 17:38', 'test', 35, 'admin'),
(37, '17/02/2017 17:39', '<em><span style=''color:#FF0000''>*System comment:</span> <strong>Sender Name</strong> changed from <strong>DuyNguyenQuoc</strong> to <strong>DuyNguyenQuoc1</strong>.. </em><em><span style=''color:#FF0000''>*System comment:</span> <strong>Address</strong> cha', 35, 'admin'),
(38, '17/02/2017 17:39', 'ttr', 35, 'admin'),
(39, '17/02/2017 17:40', '<em><span style=''color:#FF0000''>*System comment:</span> <strong>Fee</strong> changed from <strong>32</strong> to <strong>20</strong>.. </em><em><span style=''color:#FF0000''>*System comment:</span> <strong>Total</strong> changed from <strong>100</strong> to', 35, 'admin'),
(40, '17/02/2017 17:40', 'test', 35, 'admin'),
(41, '17/02/2017 17:42', '<em><span style=''color:#FF0000''>*System comment:</span> <strong>Sender Name</strong> changed from <strong>DuyNguyenQuoc1</strong> to <strong>DuyNguyenQuoc12</strong>.. </em><em><span style=''color:#FF0000''>*System comment:</span> <strong>Address</strong> c', 35, 'admin'),
(42, '17/02/2017 17:42', 'test', 35, 'admin'),
(43, '17/02/2017 17:43', '<em><span style=''color:#FF0000''>*System comment:</span> <strong></strong> changed from <strong>1.a\r\n2.b\r\n3.c\r\n4.\r\n5.</strong> to <strong>1.a\r\n2.b\r\n3.c\r\n4.\r\n5.6.</strong>.. </em><em><span style=''color:#FF0000''>*System comment:</span> <strong></strong> chan', 35, 'admin'),
(44, '17/02/2017 17:46', '<em><span style=''color:#FF0000''>*System comment:</span> <strong>Sender Name</strong> changed from <strong>DuyNguyenQuoc12</strong> to <strong>DuyNguyenQuoc</strong>.. </em><em><span style=''color:#FF0000''>*System comment:</span> <strong>Address</strong> ch', 35, 'admin'),
(45, '17/02/2017 17:46', 'tryrd', 35, 'admin'),
(46, '17/02/2017 17:47', '<em><span style=''color:#FF0000''>*System comment:</span> <strong>Customer Phone</strong> changed from <strong>989621756</strong> to <strong>098962175612</strong>.. </em>', 35, 'admin'),
(47, '17/02/2017 17:51', '<em><span style=''color:#FF0000''>*System comment:</span> <strong>Customer Phone</strong> changed from <strong>98962175612</strong> to <strong>098962175612</strong>.. </em><em><span style=''color:#FF0000''>*System comment:</span> <strong>Customer Phone</stron', 35, 'admin'),
(48, '17/02/2017 17:52', '<em><span style=''color:#FF0000''>*System comment:</span> <strong>Customer Phone</strong> changed from <strong>98962175612</strong> to <strong>098962175612</strong>.. </em>', 35, 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `orderdetails`
--

CREATE TABLE IF NOT EXISTS `orderdetails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `product_name` varchar(60) COLLATE utf8_bin NOT NULL,
  `product_price` double NOT NULL,
  `product_quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `send_cust_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `status` tinyint(1) NOT NULL,
  `date` varchar(16) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `total_weight` double NOT NULL,
  `price_per_weight` double NOT NULL,
  `total` double DEFAULT NULL,
  `recv_cust_id` int(10) unsigned NOT NULL,
  `total_weight_1` double NOT NULL,
  `price_per_weight_1` double NOT NULL,
  `total_weight_2` double NOT NULL,
  `price_per_weight_2` double NOT NULL,
  `total_weight_3` double NOT NULL,
  `price_per_weight_3` double NOT NULL,
  `total_weight_4` double NOT NULL,
  `price_per_weight_4` double NOT NULL,
  `total_weight_5` double NOT NULL,
  `price_per_weight_5` double NOT NULL,
  `fee` double NOT NULL,
  `product_desc` longtext NOT NULL,
  `additional_fee` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_orders_userId` (`user_id`),
  KEY `FK_orders_custid` (`send_cust_id`),
  KEY `recv_cust_id` (`recv_cust_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=38 ;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `send_cust_id`, `user_id`, `status`, `date`, `total_weight`, `price_per_weight`, `total`, `recv_cust_id`, `total_weight_1`, `price_per_weight_1`, `total_weight_2`, `price_per_weight_2`, `total_weight_3`, `price_per_weight_3`, `total_weight_4`, `price_per_weight_4`, `total_weight_5`, `price_per_weight_5`, `fee`, `product_desc`, `additional_fee`) VALUES
(35, 7, 1, 1, '19/02/2017', 2, 3, 90, 10, 4, 5, 6, 7, 0, 0, 0, 0, 0, 0, 22, '1.a\r\n2.b\r\n3.c\r\n4.\r\n5.6.\r\n7.', '1.10\r\n2.1\r\n3.9\r\n53\r\n3.\r\n4.\r\n8.'),
(36, 7, 1, 0, '02/03/2017', 2, 3, 100, 10, 6, 6, 0, 0, 0, 0, 0, 0, 0, 0, 58, 'asdsdaa', 'weight,price,total\r\n2,3,6\r\n6,6,36\r\n\r\n Additional fee : 58\r\n Total          : 100'),
(37, 7, 1, 0, '02/03/2017', 2, 3, 100, 10, 5, 6, 0, 0, 0, 0, 0, 0, 0, 0, 64, 'asdbsa\r\nasd\r\n asdf\r\nds\r\nfsd\r\nf\r\nasd f\r\nsdaf\r\n asd\r\nf \r\nsdf\r\n sdaf\r\n sda\r\nf \r\nsadf\r\n sdf\r\n sad\r\nf\r\nsadf \r\nasdf\r\n asd\r\nf ds', 'weight,price,total\r\n2,3,6\r\n5,6,30\r\n\r\n Additional fee : 64\r\n Total          : 100');

-- --------------------------------------------------------

--
-- Table structure for table `recvcustomers`
--

CREATE TABLE IF NOT EXISTS `recvcustomers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cust_name` varchar(100) COLLATE utf8_bin NOT NULL,
  `phone` varchar(20) COLLATE utf8_bin NOT NULL,
  `address` varchar(100) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `phone` (`phone`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=11 ;

--
-- Dumping data for table `recvcustomers`
--

INSERT INTO `recvcustomers` (`id`, `cust_name`, `phone`, `address`) VALUES
(10, 'ThuyNguyenThi', '097969149922', 'Bui Dinh Tuy');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(45) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`, `description`) VALUES
(1, 'admin', 'the highest privilege'),
(2, 'user', 'just able to see the data');

-- --------------------------------------------------------

--
-- Table structure for table `sendcustomers`
--

CREATE TABLE IF NOT EXISTS `sendcustomers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cust_name` varchar(100) COLLATE utf8_bin NOT NULL,
  `phone` varchar(20) COLLATE utf8_bin NOT NULL,
  `address` varchar(100) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `phone` (`phone`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=8 ;

--
-- Dumping data for table `sendcustomers`
--

INSERT INTO `sendcustomers` (`id`, `cust_name`, `phone`, `address`) VALUES
(7, 'DuyNguyenQuoc', '098962175612', 'Bui Dinh Tuy');

-- --------------------------------------------------------

--
-- Table structure for table `userroles`
--

CREATE TABLE IF NOT EXISTS `userroles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`role_id`),
  KEY `FK_userroles_userId` (`user_id`),
  KEY `FK_userroles_roleId` (`role_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `userroles`
--

INSERT INTO `userroles` (`id`, `user_id`, `role_id`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `enabled` tinyint(3) unsigned NOT NULL,
  `date_last_entered` varchar(16) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `enabled`, `date_last_entered`) VALUES
(1, 'admin', '$2y$10$NzEJDTu0tYZnawVFqGvPv.nd.7k2w2b5AS3OPS/GSDh1I.ZgECF4W', 1, '02/03/2017 01:36'),
(5, 'khoa', '$2y$10$xaxBADES/DDDFk2xIv86BuQuAveQUyOB7dm4tJjje5IJq2zZkecC2', 1, '13/02/2017 02:07');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `FK_comments_custId` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `orderdetails`
--
ALTER TABLE `orderdetails`
  ADD CONSTRAINT `FK_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `FK_cust` FOREIGN KEY (`send_cust_id`) REFERENCES `sendcustomers` (`id`),
  ADD CONSTRAINT `FK_customers_userId` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `FK_recvCust` FOREIGN KEY (`recv_cust_id`) REFERENCES `recvcustomers` (`id`);

--
-- Constraints for table `userroles`
--
ALTER TABLE `userroles`
  ADD CONSTRAINT `FK_userroles_roleId` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `FK_userroles_userId` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
