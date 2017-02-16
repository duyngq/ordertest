CREATE DATABASE  IF NOT EXISTS `order_mgmt` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_bin */;
USE `order_mgmt`;
-- MySQL dump 10.13  Distrib 5.5.54, for debian-linux-gnu (x86_64)
--
-- Host: 127.0.0.1    Database: order_mgmt
-- ------------------------------------------------------
-- Server version	5.5.43-enterprise-commercial-advanced

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `date` varchar(16) CHARACTER SET latin1 NOT NULL,
  `comment` varchar(255) CHARACTER SET latin1 NOT NULL,
  `order_id` int(10) unsigned NOT NULL,
  `user_name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_comments_orderId` (`order_id`),
  CONSTRAINT `FK_comments_custId` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES (23,'16/02/2017 13:25','<em><span style=\'color:#FF0000\'>*System comment:</span> <strong>Ship status</strong> changed from <strong>0</strong> to <strong>1</strong>.. </em><em><span style=\'color:#FF0000\'>*System comment:</span> <strong>Price per weight 1</strong> changed from <str',24,'admin'),(24,'16/02/2017 13:25','Tets',24,'admin');
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orderdetails`
--

DROP TABLE IF EXISTS `orderdetails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orderdetails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `product_name` varchar(60) COLLATE utf8_bin NOT NULL,
  `product_price` double NOT NULL,
  `product_quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `FK_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orderdetails`
--

LOCK TABLES `orderdetails` WRITE;
/*!40000 ALTER TABLE `orderdetails` DISABLE KEYS */;
/*!40000 ALTER TABLE `orderdetails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
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
  `fee` double NOT NULL,
  `product_desc` longtext NOT NULL,
  `additional_fee` longtext NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_orders_userId` (`user_id`),
  KEY `FK_orders_custid` (`send_cust_id`),
  KEY `recv_cust_id` (`recv_cust_id`),
  CONSTRAINT `FK_cust` FOREIGN KEY (`send_cust_id`) REFERENCES `sendcustomers` (`id`),
  CONSTRAINT `FK_customers_userId` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_recvCust` FOREIGN KEY (`recv_cust_id`) REFERENCES `recvcustomers` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (24,6,1,1,'16/02/2017',2,3,70,8,5,8,2,7,10,'asdas asdh\r\nfads\r\ndfadf\r\ndaf','sdfksdfk\r\ndasfa\r\ndasf\r\nadf');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recvcustomers`
--

DROP TABLE IF EXISTS `recvcustomers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recvcustomers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cust_name` varchar(100) COLLATE utf8_bin NOT NULL,
  `phone` varchar(20) COLLATE utf8_bin NOT NULL,
  `address` varchar(100) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `phone` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recvcustomers`
--

LOCK TABLES `recvcustomers` WRITE;
/*!40000 ALTER TABLE `recvcustomers` DISABLE KEYS */;
INSERT INTO `recvcustomers` VALUES (8,'Thuy','0979691499','Bui Dinh Tuy');
/*!40000 ALTER TABLE `recvcustomers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_name` varchar(45) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'admin','the highest privilege'),(2,'user','just able to see the data');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sendcustomers`
--

DROP TABLE IF EXISTS `sendcustomers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sendcustomers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `cust_name` varchar(100) COLLATE utf8_bin NOT NULL,
  `phone` varchar(20) COLLATE utf8_bin NOT NULL,
  `address` varchar(100) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `phone` (`phone`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sendcustomers`
--

LOCK TABLES `sendcustomers` WRITE;
/*!40000 ALTER TABLE `sendcustomers` DISABLE KEYS */;
INSERT INTO `sendcustomers` VALUES (6,'Duy','0989621756','Bui Dinh Tuy');
/*!40000 ALTER TABLE `sendcustomers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `userroles`
--

DROP TABLE IF EXISTS `userroles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `userroles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `role_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_id` (`user_id`,`role_id`),
  KEY `FK_userroles_userId` (`user_id`),
  KEY `FK_userroles_roleId` (`role_id`),
  CONSTRAINT `FK_userroles_roleId` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  CONSTRAINT `FK_userroles_userId` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `userroles`
--

LOCK TABLES `userroles` WRITE;
/*!40000 ALTER TABLE `userroles` DISABLE KEYS */;
INSERT INTO `userroles` VALUES (1,1,1);
/*!40000 ALTER TABLE `userroles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `enabled` tinyint(3) unsigned NOT NULL,
  `date_last_entered` varchar(16) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','$2y$10$NzEJDTu0tYZnawVFqGvPv.nd.7k2w2b5AS3OPS/GSDh1I.ZgECF4W',1,'16/02/2017 13:20'),(5,'khoa','$2y$10$xaxBADES/DDDFk2xIv86BuQuAveQUyOB7dm4tJjje5IJq2zZkecC2',1,'13/02/2017 02:07');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-02-16 13:52:26
