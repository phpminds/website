-- MySQL dump 10.13  Distrib 5.7.18, for Linux (x86_64)
--
-- Host: 10.131.22.108    Database: phpminds
-- ------------------------------------------------------
-- Server version	5.7.18-0ubuntu0.16.10.1

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
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `meetup_id` int(11) NOT NULL,
  `meetup_venue_id` int(11) NOT NULL,
  `joindin_event_name` varchar(60) NOT NULL,
  `joindin_talk_id` int(11) NOT NULL,
  `joindin_url` varchar(253) NOT NULL,
  `speaker_id` int(11) NOT NULL,
  `supporter_id` int(11) NOT NULL,
  `meetup_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `meetup_id` (`meetup_id`,`speaker_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `events`
--

LOCK TABLES `events` WRITE;
/*!40000 ALTER TABLE `events` DISABLE KEYS */;
INSERT INTO `events` VALUES (1,226158970,24159763,'PHPMiNDS December 2015',16610,'https://m.joind.in/talk/view/16610',1,1,'2015-12-17 19:00:00'),(2,227485754,24159763,'PHPMiNDS January 2016',16715,'https://m.joind.in/talk/view/16715',2,1,'2016-01-21 19:00:00'),(3,228265748,24319054,'PHPMiNDS February 2016',16757,'https://m.joind.in/talk/view/16757',3,1,'2016-02-11 19:00:00'),(4,228800268,24319054,'PHPMiNDS March 2016',17000,'https://m.joind.in/talk/view/17000',4,1,'2016-03-10 19:00:00'),(5,229495210,24319054,'PHPMiNDS April 2016',17343,'https://m.joind.in/talk/view/17343',5,1,'2016-04-14 18:00:00'),(6,230392445,24319054,'PHPMiNDS May 2016',17723,'https://m.joind.in/talk/view/17723',6,1,'2016-05-12 07:00:00'),(10,231081819,24319054,'PHPMiNDS June 2016',18139,'https://m.joind.in/talk/view/18139',7,1,'2016-06-09 18:00:00'),(11,231780882,24319054,'PHPMiNDS July 2016',18356,'https://m.joind.in/talk/view/18356',8,1,'2016-07-14 18:00:00'),(12,232726231,24319054,'PHPMiNDS August 2016',18609,'https://m.joind.in/talk/view/18609',8,1,'2016-08-11 18:00:00'),(13,234153982,24159763,'PHPMiNDS October 2016',19065,'https://m.joind.in/talk/view/19065',9,1,'2016-10-13 18:00:00'),(14,234856896,24319054,'PHPMiNDS November 2016',19562,'https://m.joind.in/talk/view/19562',10,1,'2016-11-10 19:00:00'),(15,236134043,24319054,'PHPMiNDS January 2017',20064,'https://m.joind.in/talk/view/20064',11,1,'2017-01-12 19:00:00'),(16,236898635,24319054,'PHPMiNDS February 2017',20066,'https://m.joind.in/talk/view/20066',12,1,'2017-02-09 19:00:00'),(17,238408087,24319054,'PHPMiNDS April 2017',20528,'https://m.joind.in/talk/view/20528',6,1,'2017-04-13 18:00:00');
/*!40000 ALTER TABLE `events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `phinxlog`
--

DROP TABLE IF EXISTS `phinxlog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `phinxlog` (
  `version` bigint(20) NOT NULL,
  `start_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `end_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `phinxlog`
--

LOCK TABLES `phinxlog` WRITE;
/*!40000 ALTER TABLE `phinxlog` DISABLE KEYS */;
INSERT INTO `phinxlog` VALUES (20151025204659,'2015-12-01 13:49:56','2015-12-01 13:49:56'),(20151101154328,'2015-12-01 13:49:56','2015-12-01 13:49:57'),(20151103203147,'2015-12-01 13:49:57','2015-12-01 13:49:57'),(20151106232831,'2015-12-01 13:49:57','2015-12-01 13:49:57'),(20151106233716,'2015-12-01 13:49:57','2015-12-01 13:49:57'),(20151125220951,'2015-12-01 13:49:57','2015-12-01 13:49:57'),(20151207231509,'2015-12-15 17:32:34','2015-12-15 17:32:34'),(20151220173520,'2015-12-31 10:59:51','2015-12-31 10:59:51');
/*!40000 ALTER TABLE `phinxlog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `speakers`
--

DROP TABLE IF EXISTS `speakers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `speakers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(60) NOT NULL,
  `last_name` varchar(60) NOT NULL,
  `email` varchar(254) NOT NULL,
  `twitter` varchar(15) NOT NULL,
  `avatar` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `twitter` (`twitter`,`email`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `speakers`
--

LOCK TABLES `speakers` WRITE;
/*!40000 ALTER TABLE `speakers` DISABLE KEYS */;
INSERT INTO `speakers` VALUES (1,'Rob','Allen','rob@akrabat.com','@akrabat',NULL),(2,'James','Titcumb','james@asgrim.com','@asgrim',NULL),(3,'Paul','Dragoonis','paul@dragoonis.com','@dr4goonis',NULL),(4,'John Knowles, ','Richard McLeod','speakers+1@phpminds.org','@wearejh',NULL),(5,'Derick','Rethans','derick@derickrethans.nl','@derickr',NULL),(6,'Matt','Brunt','openblue555@gmail.com','@brunty',NULL),(7,'Tim','Nash','speaker+timnash@phpminds.org','@tnash',NULL),(8,'James','Mallison','phpminds+J7mbo@phpminds.org','@J7mbo',NULL),(9,'PHP','School','speaker+phpschool@phpminds.org','@PHPSchoolTeam',NULL),(10,'David','McKay','david@rawkode.com','@rawkode',NULL),(11,'Liam','Wiltshire','liam@w.iltshi.re','@l_wiltshire',NULL),(12,'Simon','Ambridge','shaun@phpminds.org','@stratman1958',NULL);
/*!40000 ALTER TABLE `speakers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `supporters`
--

DROP TABLE IF EXISTS `supporters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `supporters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `url` varchar(253) NOT NULL,
  `twitter` varchar(15) NOT NULL,
  `email` varchar(254) NOT NULL,
  `logo` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `twitter` (`twitter`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `supporters`
--

LOCK TABLES `supporters` WRITE;
/*!40000 ALTER TABLE `supporters` DISABLE KEYS */;
INSERT INTO `supporters` VALUES (1,'PHPMinds Organiser','http://phpminds.org','phpminds','phpminds.org@gmail.com',NULL);
/*!40000 ALTER TABLE `supporters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(120) NOT NULL,
  `password` char(60) NOT NULL,
  `role` int(1) NOT NULL,
  `status` int(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'adoni@pavlakis.info','$2y$10$3Qs8H3tsC8he.rPBwXrcXuAGVv5lbhxxfMGcBJV2SsjXqadv8te5m',0,0),(6,'shaun@phpminds.org','$2y$10$GpshNhWzCvJM9PQMS90M3eq9VBUEP.9DrJNp0L1Czj/VdYoiZ8wfS',0,0);
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

-- Dump completed on 2017-07-19 15:38:32
