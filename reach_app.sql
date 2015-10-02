-- MySQL dump 10.13  Distrib 5.5.44, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: reach_app
-- ------------------------------------------------------
-- Server version	5.5.44-0ubuntu0.14.04.1

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
-- Table structure for table `companies`
--

DROP TABLE IF EXISTS `companies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `companies` (
  `cid` int(11) NOT NULL,
  `company_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `companies`
--

LOCK TABLES `companies` WRITE;
/*!40000 ALTER TABLE `companies` DISABLE KEYS */;
INSERT INTO `companies` VALUES (1,'Brewer Digital');
/*!40000 ALTER TABLE `companies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `sender_uid` int(11) DEFAULT NULL,
  `recipient_uid` int(11) DEFAULT NULL,
  `message_content` varchar(255) DEFAULT NULL,
  `timestamp_sent` int(11) DEFAULT NULL,
  `timestamp_received` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (1,2,'hey there how are you',123455,0),(1,2,'hello there',1442041971,0),(1,2,'hello there',1442042013,0),(1,2,'hello there',1442042613,0),(1,2,'hello there',1442042648,0),(1,2,'hello there',1442042696,0),(1,2,'hello there',1442042868,0),(1,2,'hello there',1442043096,0),(1,2,'hello there',1442043232,0),(1,2,'hello there',1442043254,0),(1,2,'hello there',1442043349,0),(1,2,'hello there',1442043560,0),(1,2,'hello there',1442043893,0),(1,2,'hello there',1442043961,0),(1,2,'hello there',1442043993,0),(1,2,'hello there',1442044009,0),(1,2,'hello there',1442085235,0),(1,2,'hello there',1442085257,0),(1,2,'hello there',1442085295,0),(1,2,'hello there',1442085312,0),(2,1,'something',1442191874,0),(2,1,'how are you',1442191915,0),(2,1,'fdsafdsa',1442191974,0),(2,1,'fafdsd',1442192002,0),(1,2,'hehehehehe',1442192162,0),(1,2,'ddddd',1442192221,0),(2,1,'sometihing',1442192321,0),(2,1,'blahblah',1442192443,0),(2,1,'hahaha',1442263973,0),(2,2,'blahblah',1442264019,0),(1,2,'blah',1442264167,0),(1,2,'yeah dog',1442264184,0),(2,1,'something',1442265018,0),(2,1,'fdsafds',1442265399,0),(3,1,'hey there',1442266399,0),(3,1,'something',1442266420,0),(3,1,'www',1442266430,0),(3,1,'fdsfdsafdsafds',1442268192,0),(3,1,'fdsafdsa',1442296153,0),(3,1,'sometihgn',1442297196,0),(3,1,'fdsafds',1442297556,0),(1,3,'how are you',1442298944,0),(3,1,'how are you',1442298962,0),(3,1,'bobdude',1442299219,0),(1,3,'fdsafdsa',1442299226,0),(1,3,'fdsafdsafdsafasd',1442299237,0),(1,3,'hey',1442299310,0),(3,1,'oh hi there',1442299326,0),(3,1,'hey there',1442299443,0),(1,3,'sup dude, how\'s it going ?',1442299465,0),(1,3,'fasfasd',1442299514,0),(1,2,'fafdsafsdafdsafads',1442299521,0),(3,2,'fadsfdasfd',1442299529,0),(2,3,'fsafdafdsafdsafds',1442299589,0),(3,2,'fdsafdsfdsa',1442299772,0),(3,2,'ffffaaaaaaa',1442299784,0),(3,1,'something',1442339959,0),(1,3,'something',1442340003,0),(1,3,'hey ho',1442340010,0),(3,1,'that\'s right',1442340016,0),(3,1,'hey there',1442344109,0);
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `perms`
--

DROP TABLE IF EXISTS `perms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `perms` (
  `uid` int(11) NOT NULL,
  `cid` int(11) NOT NULL,
  `role` enum('customer','employee','admin') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `perms`
--

LOCK TABLES `perms` WRITE;
/*!40000 ALTER TABLE `perms` DISABLE KEYS */;
INSERT INTO `perms` VALUES (1,1,'admin'),(2,1,'customer'),(1,1,'employee'),(3,1,'employee');
/*!40000 ALTER TABLE `perms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `uid` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `encrypted_password` varchar(50) NOT NULL,
  `auth_token` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'mrgoby@gmail.com','ede97144247e25cb1f63c8a6a5f0b57d','1234','Brook','Davis'),(2,'lappin.hammond@gmail.com','ede97144247e25cb1f63c8a6a5f0b57d','1235','Lappin','Hammond'),(3,'joelbrewer01@gmail.com','ede97144247e25cb1f63c8a6a5f0b57d','1235','Joel','Brewer');
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

-- Dump completed on 2015-09-15 14:53:27
