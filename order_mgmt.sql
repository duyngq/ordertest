-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 07, 2017 at 07:02 PM
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=61 ;

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
(48, '17/02/2017 17:52', '<em><span style=''color:#FF0000''>*System comment:</span> <strong>Customer Phone</strong> changed from <strong>98962175612</strong> to <strong>098962175612</strong>.. </em>', 35, 'admin'),
(49, '02/03/2017 17:53', '<em><span style=''color:#FF0000''>*System comment:</span> <strong>Price per weight 4</strong> changed from <strong>12</strong> to <strong>8</strong>.. </em><em><span style=''color:#FF0000''>*System comment:</span> <strong>Total</strong> changed from <strong>3', 38, 'admin'),
(50, '02/03/2017 17:53', 'test', 38, 'admin'),
(51, '02/03/2017 17:53', '<em><span style=''color:#FF0000''>*System comment:</span> <strong>Total</strong> changed from <strong>332</strong> to <strong> 332</strong>.. </em>', 38, 'admin'),
(52, '02/03/2017 17:53', 'test', 38, 'admin'),
(53, '05/03/2017 16:53', '<em><span style=''color:#FF0000''>*System comment:</span> <strong>Order date</strong> changed from <strong>21/03/2017</strong> to <strong>05/03/2017</strong>.. </em><em><span style=''color:#FF0000''>*System comment:</span> <strong>Product description 2</stron', 41, 'admin'),
(54, '05/03/2017 16:53', 'Test', 41, 'admin'),
(55, '05/03/2017 16:56', '<em><span style=''color:#FF0000''>*System comment:</span> <strong>Code</strong> changed from <strong>3d</strong> to <strong>3a</strong>.. </em>', 41, 'admin'),
(56, '05/03/2017 16:58', '<em><span style=''color:#FF0000''>*System comment:</span> <strong>Order date</strong> changed from <strong>21/03/2017</strong> to <strong>01/04/2017</strong>.. </em><em><span style=''color:#FF0000''>*System comment:</span> <strong>Product description 2</stron', 40, 'admin'),
(57, '05/03/2017 16:58', 'Test', 40, 'admin'),
(58, '05/03/2017 17:00', 'Test', 40, 'admin'),
(59, '05/03/2017 17:21', '<em><span style=''color:#FF0000''>*System comment:</span> <strong>Order date</strong> changed from <strong>2017-03-05</strong> to <strong>2017/05/03</strong>.. </em>', 43, 'admin'),
(60, '05/03/2017 17:22', '<em><span style=''color:#FF0000''>*System comment:</span> <strong>Order date</strong> changed from <strong>2017/05/03</strong> to <strong>2017/05/05</strong>.. </em>', 43, 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `orderdetails`
--

CREATE TABLE IF NOT EXISTS `orderdetails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `p_desc` text COLLATE utf8_bin NOT NULL,
  `weight` double NOT NULL,
  `price_weight` double NOT NULL,
  `unit` int(11) NOT NULL,
  `price_unit` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=17 ;

--
-- Dumping data for table `orderdetails`
--

INSERT INTO `orderdetails` (`id`, `order_id`, `p_desc`, `weight`, `price_weight`, `unit`, `price_unit`) VALUES
(2, 43, 'B', 1, 2, 3, 4),
(3, 42, '', 2, 0, 0, 0),
(5, 43, '', 1, 2, 3, 4),
(6, 46, 'A', 1, 2, 3, 4),
(7, 46, 'B', 1, 2, 3, 4),
(8, 46, 'C', 1, 2, 3, 4),
(9, 46, 'D', 1, 2, 3, 4),
(10, 46, 'E', 1, 2, 3, 4),
(11, 46, 'F', 1, 2, 3, 4),
(12, 46, 'G', 1, 2, 3, 4),
(13, 46, 'H', 1, 2, 3, 4),
(14, 46, 'I', 1, 2, 3, 4),
(15, 46, 'J', 1, 2, 3, 4),
(16, 46, 'K', 1, 2, 3, 4);

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
  `desc_0` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `total_weight` double NOT NULL,
  `price_per_weight` double NOT NULL,
  `total` double DEFAULT NULL,
  `recv_cust_id` int(10) unsigned NOT NULL,
  `desc_1` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `total_weight_1` double NOT NULL,
  `price_per_weight_1` double NOT NULL,
  `desc_2` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `total_weight_2` double NOT NULL,
  `price_per_weight_2` double NOT NULL,
  `desc_3` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `total_weight_3` double NOT NULL,
  `price_per_weight_3` double NOT NULL,
  `desc_4` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `total_weight_4` double NOT NULL,
  `price_per_weight_4` double NOT NULL,
  `desc_5` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL,
  `total_weight_5` double NOT NULL,
  `price_per_weight_5` double NOT NULL,
  `code` varchar(5) NOT NULL,
  `fee` double NOT NULL,
  `product_desc` longtext NOT NULL,
  `additional_fee` longtext NOT NULL,
  `new_type` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_orders_userId` (`user_id`),
  KEY `FK_orders_custid` (`send_cust_id`),
  KEY `recv_cust_id` (`recv_cust_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=47 ;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `send_cust_id`, `user_id`, `status`, `date`, `desc_0`, `total_weight`, `price_per_weight`, `total`, `recv_cust_id`, `desc_1`, `total_weight_1`, `price_per_weight_1`, `desc_2`, `total_weight_2`, `price_per_weight_2`, `desc_3`, `total_weight_3`, `price_per_weight_3`, `desc_4`, `total_weight_4`, `price_per_weight_4`, `desc_5`, `total_weight_5`, `price_per_weight_5`, `code`, `fee`, `product_desc`, `additional_fee`, `new_type`) VALUES
(35, 7, 1, 1, '19/02/2017', '', 2, 3, 90, 10, '', 4, 5, '', 6, 7, '', 0, 0, '', 0, 0, '', 0, 0, '', 22, '1.a\r\n2.b\r\n3.c\r\n4.\r\n5.6.\r\n7.', '1.10\r\n2.1\r\n3.9\r\n53\r\n3.\r\n4.\r\n8.', 0),
(36, 7, 1, 0, '02/03/2017', '', 2, 3, 100, 10, '', 6, 6, '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', 58, 'asdsdaa', 'weight,price,total\r\n2,3,6\r\n6,6,36\r\n\r\n Additional fee : 58\r\n Total          : 100', 0),
(37, 7, 1, 0, '02/03/2017', '', 2, 3, 100, 10, '', 5, 6, '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', 64, 'asdbsa\r\nasd\r\n asdf\r\nds\r\nfsd\r\nf\r\nasd f\r\nsdaf\r\n asd\r\nf \r\nsdf\r\n sdaf\r\n sda\r\nf \r\nsadf\r\n sdf\r\n sad\r\nf\r\nsadf \r\nasdf\r\n asd\r\nf ds', 'weight,price,total\r\n2,3,6\r\n5,6,30\r\n\r\n Additional fee : 64\r\n Total          : 100', 0),
(38, 7, 1, 0, '02/03/2017', '', 1, 2, 332, 10, '', 3, 4, '', 5, 6, '', 7, 8, '', 9, 8, '', 11, 12, '3a', 28, '12\r\n3\r\n23\r\ns\r\nfds\r\nf\r\nfv\r\nzcx\r\nv\r\nsfd\r\nsdf\r\n\r\nDuy', 'weight,price,total\r\n1,2,2\r\n3,4,12\r\n5,6,30\r\n7,8,56\r\n9,8,72\r\n11,12,132\r\n\r\n Additional fee : 28\r\n Total          : 332', 0),
(39, 7, 1, 0, '21/03/2017', 'BCD', 1, 2, 0, 10, 'B', 2, 3, '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '3d', 14, 'sadasd\r\nsad sa', '', 0),
(40, 7, 1, 0, '01/04/2017', 'A', 1, 2, 37, 10, 'B', 2, 3, 'C', 3, 5, '', 0, 0, '', 0, 0, '', 0, 0, '4a', 14, 'sadasd\r\nsad sa\r\nDUY TEST', '', 0),
(41, 7, 1, 0, '05/03/2017', 'A', 1, 2, 30, 10, 'B', 2, 3, 'C', 2, 3, '', 0, 0, '', 0, 0, '', 0, 0, '3a', 16, 'sadasd\r\nsad sa\r\nDuy', '', 0),
(42, 7, 1, 0, '2017-03-05', 'A', 1, 7, 8, 10, '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '1a', 1, 'Hansha aha s sua\r\n', '', 0),
(43, 7, 1, 0, '2017/05/05', 'A', 1, 7, 8, 10, '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '1a', 1, 'Hansha aha s sua\r\n', '', 0),
(46, 7, 1, 0, '07/03/2017', '', 0, 0, 0, 10, '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '', 0, 0, '3b', 12, '1\r\n2\r\n3\r\n4\r\n56\r\n7\r\n\r\n89\r\n87\r\n5\r\n3\r\n453\r\n21', '', 1);

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
(1, 'admin', '$2y$10$NzEJDTu0tYZnawVFqGvPv.nd.7k2w2b5AS3OPS/GSDh1I.ZgECF4W', 1, '07/03/2017 23:37'),
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
