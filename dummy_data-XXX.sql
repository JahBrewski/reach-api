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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bulletins`
--

LOCK TABLES `bulletins` WRITE;
/*!40000 ALTER TABLE `bulletins` DISABLE KEYS */;
INSERT INTO `bulletins` VALUES (1,'1','1','we have an important announcement!!!','2015-11-20 00:37:56','2015-11-20 00:37:56'),(2,'2','1','REACH APP IS COMING SOON!!!','2015-11-20 02:34:13','2015-11-20 02:34:13'),(3,'3','1','WE ARE NOW ALPHABET','2015-11-20 02:34:36','2015-11-20 02:34:36');
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
INSERT INTO `messages` VALUES (1,'1','1','2','hey1','2015-11-04 19:16:07','2015-11-04 19:16:07'),(2,'1','2','1','hey2','2015-11-04 19:16:43','2015-11-04 19:16:43'),(3,'1','1','2','hey3','2015-11-04 19:17:03','2015-11-04 19:17:03'),(4,'1','2','1','hey4','2015-11-12 19:04:54','2015-11-12 19:04:54'),(5,'1','1','2','hey5','2015-11-12 19:05:15','2015-11-12 19:05:15'),(6,'1','1','2','hey6','2015-11-12 19:05:25','2015-11-12 19:05:25'),(7,'1','3','2','bang1','2015-11-12 19:35:30','2015-11-12 19:35:30'),(8,'1','3','2','bang2','2015-11-12 19:35:38','2015-11-12 19:35:38'),(9,'1','2','3','bang3','2015-11-12 19:35:49','2015-11-12 19:35:49'),(10,'1','2','3','bang4','2015-11-12 19:35:58','2015-11-12 19:35:58'),(11,'1','3','2','bang5','2015-11-12 19:36:12','2015-11-12 19:36:12'),(12,'1','3','1','bop1','2015-11-12 19:36:25','2015-11-12 19:36:25'),(13,'1','1','3','bop2','2015-11-12 19:36:41','2015-11-12 19:36:41'),(18,'1','3','1','bop3 dude !!!','2015-11-14 09:45:52','2015-11-14 09:45:52'),(19,'1','1','3','bop4 mang','2015-11-14 10:08:56','2015-11-14 10:08:56'),(20,'1','3','1','bop5 yo','2015-11-14 10:09:27','2015-11-14 10:09:27'),(21,'1','3','1','hey brook, how are you ?','2015-11-15 09:12:07','2015-11-15 09:12:07'),(22,'1','3','2','lappin, you are super cool !!!','2015-11-15 09:12:35','2015-11-15 09:12:35'),(23,'1','1','3','doing great Joel !!!','2015-11-17 01:07:47','2015-11-17 01:07:47'),(24,'1','3','1','fdsafds','2015-11-17 01:39:00','2015-11-17 01:39:00'),(25,'1','3','1','fdafdsafdsa','2015-11-17 01:39:52','2015-11-17 01:39:52'),(26,'1','3','1','fdsafdsa','2015-11-17 01:39:58','2015-11-17 01:39:58'),(27,'1','3','1','Cool','2015-11-17 02:02:46','2015-11-17 02:02:46'),(28,'1','3','1','something','2015-11-18 01:19:30','2015-11-18 01:19:30'),(29,'1','3','2','buddy','2015-11-18 01:20:09','2015-11-18 01:20:09'),(30,'1','3','2','this and that','2015-11-18 04:11:57','2015-11-18 04:11:57'),(31,'1','3','1','yo dahg','2015-11-18 04:12:45','2015-11-18 04:12:45'),(32,'1','1','3','fdsafdsa','2015-11-18 04:26:54','2015-11-18 04:26:54'),(33,'1','1','3','Hi','2015-11-19 02:09:14','2015-11-19 02:09:14'),(34,'1','3','2','holla','2015-11-19 09:34:04','2015-11-19 09:34:04'),(35,'1','3','1','Hey man','2015-11-19 09:34:42','2015-11-19 09:34:42'),(36,'1','3','1','\'tis Joel','2015-11-19 09:34:52','2015-11-19 09:34:52'),(37,'1','1','3','yo, tis me !   Brook','2015-11-19 09:36:30','2015-11-19 09:36:30'),(38,'1','1','3','how goes ?','2015-11-19 09:36:50','2015-11-19 09:36:50'),(39,'1','3','1','yo yo! go REACH','2015-11-19 09:36:51','2015-11-19 09:36:51'),(40,'1','1','3','reach out and touch, babe!','2015-11-19 09:37:31','2015-11-19 09:37:31');
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

-- Dump completed on 2015-11-20 22:28:38
