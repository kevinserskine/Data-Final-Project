-- MySQL dump 10.13  Distrib 8.0.26, for Win64 (x86_64)
--
-- Host: localhost    Database: finalproject2
-- ------------------------------------------------------
-- Server version	8.0.26

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `address`
--

DROP TABLE IF EXISTS `address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `address` (
  `address_id` int NOT NULL AUTO_INCREMENT,
  `street_number` varchar(45) NOT NULL,
  `street_name` text NOT NULL,
  `city` varchar(45) NOT NULL,
  `postal_code` varchar(45) NOT NULL,
  `unit_number` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`address_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `address`
--

LOCK TABLES `address` WRITE;
/*!40000 ALTER TABLE `address` DISABLE KEYS */;
INSERT INTO `address` VALUES (1,'1700','Simcoe St N.','Oshawa','L1G 4X9','309');
/*!40000 ALTER TABLE `address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `author`
--

DROP TABLE IF EXISTS `author`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `author` (
  `author_id` int NOT NULL AUTO_INCREMENT,
  `author_name` text NOT NULL,
  PRIMARY KEY (`author_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `author`
--

LOCK TABLES `author` WRITE;
/*!40000 ALTER TABLE `author` DISABLE KEYS */;
INSERT INTO `author` VALUES (1,'Margaret Atwood'),(2,'Lucy Montgomery'),(3,'Douglas Coupland'),(4,'Robertson Davies'),(5,'Joseph Boyden'),(6,'Yann Martel');
/*!40000 ALTER TABLE `author` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `book`
--

DROP TABLE IF EXISTS `book`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `book` (
  `book_id` int NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `ISBN13` varchar(30) NOT NULL,
  `publisher_id` int NOT NULL,
  `publication_date` text NOT NULL,
  `img_link` text NOT NULL,
  `price` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`book_id`),
  UNIQUE KEY `ISBN13_UNIQUE` (`ISBN13`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `book`
--

LOCK TABLES `book` WRITE;
/*!40000 ALTER TABLE `book` DISABLE KEYS */;
INSERT INTO `book` VALUES (1,'Fantastic Mr Fox','9780141346441',0,'Apr 07, 2013','https://covers.openlibrary.org/b/id/10835919-L.jpg',4.99),(2,'Hatchet','9780140327243',0,'1988','https://covers.openlibrary.org/b/id/9391867-L.jpg',5.99),(3,'Percy Jackson and the Lightning Thief','9780141329901',0,'Apr 05, 2009','https://covers.openlibrary.org/b/id/10833145-L.jpg',6.99),(4,'Rodrick Rules','9780141324913',0,'2009','https://covers.openlibrary.org/b/id/7555639-L.jpg',6.5),(5,'Charlie and the Chocolate Factory','9780141322711',0,'2007','https://covers.openlibrary.org/b/id/10511330-L.jpg',4.2);
/*!40000 ALTER TABLE `book` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `book_author`
--

DROP TABLE IF EXISTS `book_author`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `book_author` (
  `book_id` int NOT NULL,
  `author_id` int NOT NULL,
  PRIMARY KEY (`book_id`,`author_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `book_author`
--

LOCK TABLES `book_author` WRITE;
/*!40000 ALTER TABLE `book_author` DISABLE KEYS */;
INSERT INTO `book_author` VALUES (1,1),(2,2),(3,3),(4,4),(5,5);
/*!40000 ALTER TABLE `book_author` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `borrows`
--

DROP TABLE IF EXISTS `borrows`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `borrows` (
  `borrow_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `book_id` int NOT NULL,
  `borrow_date` datetime NOT NULL,
  `return_date` datetime NOT NULL,
  `borrow_address_id` int NOT NULL,
  PRIMARY KEY (`borrow_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `borrows`
--

LOCK TABLES `borrows` WRITE;
/*!40000 ALTER TABLE `borrows` DISABLE KEYS */;
/*!40000 ALTER TABLE `borrows` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_history`
--

DROP TABLE IF EXISTS `order_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_history` (
  `history_id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `status_id` int NOT NULL,
  PRIMARY KEY (`history_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_history`
--

LOCK TABLES `order_history` WRITE;
/*!40000 ALTER TABLE `order_history` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_list`
--

DROP TABLE IF EXISTS `order_list`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_list` (
  `ord_list_id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `book_id` int NOT NULL,
  `price` double NOT NULL,
  PRIMARY KEY (`ord_list_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_list`
--

LOCK TABLES `order_list` WRITE;
/*!40000 ALTER TABLE `order_list` DISABLE KEYS */;
INSERT INTO `order_list` VALUES (1,3,2,5.99),(2,4,1,4.99),(12,14,4,6.5),(13,15,3,0),(14,16,4,0),(15,17,4,6.5),(16,18,5,4.2),(17,19,4,0),(18,20,5,0);
/*!40000 ALTER TABLE `order_list` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `publisher`
--

DROP TABLE IF EXISTS `publisher`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `publisher` (
  `publisher_id` int NOT NULL AUTO_INCREMENT,
  `publisher_name` text NOT NULL,
  PRIMARY KEY (`publisher_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publisher`
--

LOCK TABLES `publisher` WRITE;
/*!40000 ALTER TABLE `publisher` DISABLE KEYS */;
INSERT INTO `publisher` VALUES (0,'Puffin'),(1,'Penguin Random House Canada'),(2,'CanBooks'),(3,'ECW Press'),(4,'Gordon Hill Press'),(5,'Mother Tongue'),(6,'Anvil Press');
/*!40000 ALTER TABLE `publisher` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `password` varchar(60) NOT NULL,
  `name` text NOT NULL,
  `email` varchar(128) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'$2y$10$odFA4YP29ItNT7icirHLWe7AtVDygXvWyBTsFj4VnitijKAeFz9O2','Ivan Bisol','email@login.com');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_address`
--

DROP TABLE IF EXISTS `user_address`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_address` (
  `user_id` int NOT NULL,
  `address_id` int NOT NULL,
  PRIMARY KEY (`user_id`,`address_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_address`
--

LOCK TABLES `user_address` WRITE;
/*!40000 ALTER TABLE `user_address` DISABLE KEYS */;
INSERT INTO `user_address` VALUES (1,1);
/*!40000 ALTER TABLE `user_address` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_order`
--

DROP TABLE IF EXISTS `user_order`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_order` (
  `order_id` int NOT NULL AUTO_INCREMENT,
  `order_date` datetime NOT NULL,
  `user_id` int NOT NULL,
  `dest_address_id` int NOT NULL,
  PRIMARY KEY (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_order`
--

LOCK TABLES `user_order` WRITE;
/*!40000 ALTER TABLE `user_order` DISABLE KEYS */;
INSERT INTO `user_order` VALUES (1,'2021-11-26 05:29:42',1,1),(2,'2021-11-26 05:39:05',1,1),(3,'2021-11-26 05:41:08',1,1),(4,'2021-11-26 05:41:08',1,1),(5,'2021-11-26 05:41:08',1,1),(6,'2021-11-26 05:41:08',1,1),(7,'2021-11-26 05:41:08',1,1),(8,'2021-11-26 09:52:50',1,1),(9,'2021-11-26 09:52:50',1,1),(10,'2021-11-26 09:52:50',1,1),(11,'2021-11-26 09:52:50',1,1),(12,'2021-11-26 09:52:50',1,1),(13,'2021-11-26 09:52:50',1,1),(14,'2021-11-26 11:05:24',1,1),(15,'2021-11-26 11:05:24',1,1),(16,'2021-11-26 11:05:24',1,1),(17,'2021-11-26 11:48:46',1,1),(18,'2021-11-26 11:48:46',1,1),(19,'2021-11-26 11:48:46',1,1),(20,'2021-11-26 11:48:46',1,1);
/*!40000 ALTER TABLE `user_order` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2021-11-29 20:19:27
