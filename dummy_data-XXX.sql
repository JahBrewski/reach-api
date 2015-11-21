-- MySQL dump 10.13  Distrib 5.5.46, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: reach_app2
-- ------------------------------------------------------
-- Server version	5.5.46-0ubuntu0.14.04.2

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
-- Table structure for table `bulletins`
--

DROP TABLE IF EXISTS `bulletins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bulletins` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` varchar(255) NOT NULL,
  `from_user_id` varchar(255) NOT NULL,
  `message_content` varchar(255) NOT NULL,
  `timestamp_queued` varchar(255) NOT NULL,
  `timestamp_dequeued` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bulletins`
--

LOCK TABLES `bulletins` WRITE;
/*!40000 ALTER TABLE `bulletins` DISABLE KEYS */;
INSERT INTO `bulletins` VALUES (2,'2','1','REACH APP IS COMING SOON!!!','1448066152','1448066152'),(3,'3','1','WE ARE NOW ALPHABET','1448066152','1448066152'),(8,'1','3','REACH APP IS COMING SOON !!!!','1448066152','1448066152');
/*!40000 ALTER TABLE `bulletins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `companies`
--

DROP TABLE IF EXISTS `companies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `companies` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `companies`
--

LOCK TABLES `companies` WRITE;
/*!40000 ALTER TABLE `companies` DISABLE KEYS */;
INSERT INTO `companies` VALUES (1,'Brewer Digital','We brew digital beer.'),(2,'Gym Fed','Work hard, get results.'),(3,'Google, Inc.','Look around for things.');
/*!40000 ALTER TABLE `companies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` varchar(255) NOT NULL,
  `from_user_id` varchar(255) NOT NULL,
  `recipient_user_id` varchar(255) NOT NULL,
  `message_content` varchar(255) NOT NULL,
  `timestamp_queued` varchar(255) NOT NULL,
  `timestamp_dequeued` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (1,'1','1','2','hey1','1448066152','1448066152'),(2,'1','2','1','hey2','1448066152','1448066152'),(3,'1','1','2','hey3','1448066152','1448066152'),(4,'1','2','1','hey4','1448066152','1448066152'),(5,'1','1','2','hey5','1448066152','1448066152'),(6,'1','1','2','hey6','1448066152','1448066152'),(7,'1','3','2','bang1','1448066152','1448066152'),(8,'1','3','2','bang2','1448066152','1448066152'),(9,'1','2','3','bang3','1448066152','1448066152'),(10,'1','2','3','bang4','1448066152','1448066152'),(11,'1','3','2','bang5','1448066152','1448066152'),(12,'1','3','1','bop1','1448066152','1448066152'),(13,'1','1','3','bop2','1448066152','1448066152'),(18,'1','3','1','bop3 dude !!!','1448066152','1448066152'),(19,'1','1','3','bop4 mang','1448066152','1448066152'),(20,'1','3','1','bop5 yo','1448066152','1448066152'),(21,'1','3','1','hey brook, how are you ?','1448066152','1448066152'),(22,'1','3','2','lappin, you are super cool !!!','1448066152','1448066152'),(23,'1','1','3','doing great Joel !!!','1448066152','1448066152'),(24,'1','3','1','fdsafds','1448066152','1448066152'),(25,'1','3','1','fdafdsafdsa','1448066152','1448066152'),(26,'1','3','1','fdsafdsa','1448066152','1448066152'),(27,'1','3','1','Cool','1448066152','1448066152'),(28,'1','3','1','something','1448066152','1448066152'),(29,'1','3','2','buddy','1448066152','1448066152'),(30,'1','3','2','this and that','1448066152','1448066152'),(31,'1','3','1','yo dahg','1448066152','1448066152'),(32,'1','1','3','fdsafdsa','1448066152','1448066152'),(33,'1','1','3','Hi','1448066152','1448066152'),(34,'1','3','2','holla','1448066152','1448066152'),(35,'1','3','1','Hey man','1448066152','1448066152'),(36,'1','3','1','\'tis Joel','1448066152','1448066152'),(37,'1','1','3','yo, tis me !   Brook','1448066152','1448066152'),(38,'1','1','3','how goes ?','1448066152','1448066152'),(39,'1','3','1','yo yo! go REACH','1448066152','1448066152'),(40,'1','1','3','reach out and touch, babe!','1448066152','1448066152');
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `perms`
--

DROP TABLE IF EXISTS `perms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perms` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `company_id` varchar(255) NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perms`
--

LOCK TABLES `perms` WRITE;
/*!40000 ALTER TABLE `perms` DISABLE KEYS */;
INSERT INTO `perms` VALUES (1,'1','1','admin'),(5,'1','3','admin'),(6,'2','3','employee'),(7,'3','3','customer');
/*!40000 ALTER TABLE `perms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `encrypted_password` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `position` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'mrgoby@gmail.com','ede97144247e25cb1f63c8a6a5f0b57d','Brook','Davis',NULL,NULL),(2,'lappin.hammond@gmail.com','ede97144247e25cb1f63c8a6a5f0b57d','Lappin','Hammond',NULL,NULL),(3,'joelbrewer01@gmail.com','ede97144247e25cb1f63c8a6a5f0b57d','Joel','Brewer','Brewer Digital','Owner');
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

-- Dump completed on 2015-11-21  0:43:46
