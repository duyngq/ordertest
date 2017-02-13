-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 13, 2017 at 06:47 PM
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=23 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=27 ;

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
  `product_desc` longtext NOT NULL,
  `additional_fee` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_orders_userId` (`user_id`),
  KEY `FK_orders_custid` (`send_cust_id`),
  KEY `recv_cust_id` (`recv_cust_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=6 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=4 ;

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
(1, 'admin', '$2y$10$NzEJDTu0tYZnawVFqGvPv.nd.7k2w2b5AS3OPS/GSDh1I.ZgECF4W', 1, '13/02/2017 23:50'),
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
