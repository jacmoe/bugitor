-- MySQL dump 10.11
--
-- Host: localhost    Database: dfdemo
-- ------------------------------------------------------
-- Server version	5.0.45-community-nt

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
-- Table structure for table `df_cities`
--

DROP TABLE IF EXISTS `df_cities`;
CREATE TABLE `df_cities` (
  `id` int(11) NOT NULL auto_increment,
  `countries_id` int(11) NOT NULL,
  `name` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `countries_id` (`countries_id`),
  CONSTRAINT `df_cities_counties_fk` FOREIGN KEY (`countries_id`) REFERENCES `df_countries` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `df_cities`
--

LOCK TABLES `df_cities` WRITE;
/*!40000 ALTER TABLE `df_cities` DISABLE KEYS */;
INSERT INTO `df_cities` (`id`, `countries_id`, `name`) VALUES (1,1,'Moscow'),(2,1,'St. Petersburg'),(3,2,'Kyiv'),(4,2,'Donetsk');
/*!40000 ALTER TABLE `df_cities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `df_countries`
--

DROP TABLE IF EXISTS `df_countries`;
CREATE TABLE `df_countries` (
  `id` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `df_countries`
--

LOCK TABLES `df_countries` WRITE;
/*!40000 ALTER TABLE `df_countries` DISABLE KEYS */;
INSERT INTO `df_countries` (`id`, `name`) VALUES (1,'Russia'),(2,'Ukraine');
/*!40000 ALTER TABLE `df_countries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `df_user_groups`
--

DROP TABLE IF EXISTS `df_user_groups`;
CREATE TABLE `df_user_groups` (
  `id` int(11) NOT NULL auto_increment,
  `name` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `df_user_groups`
--

LOCK TABLES `df_user_groups` WRITE;
/*!40000 ALTER TABLE `df_user_groups` DISABLE KEYS */;
INSERT INTO `df_user_groups` (`id`, `name`) VALUES (1,'admin'),(2,'editor'),(3,'reader');
/*!40000 ALTER TABLE `df_user_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `df_users`
--

DROP TABLE IF EXISTS `df_users`;
CREATE TABLE `df_users` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(45) NOT NULL default '',
  `username` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL default '',
  `is_active` tinyint(1) NOT NULL,
  `user_groups_id` int(11) default NULL,
  `cities_id` int(11) default NULL,
  PRIMARY KEY  (`id`),
  KEY `user_groups_id` (`user_groups_id`),
  KEY `cities_id` (`cities_id`),
  CONSTRAINT `df_users_cities_fk` FOREIGN KEY (`cities_id`) REFERENCES `df_cities` (`id`),
  CONSTRAINT `df_users_groups_fk` FOREIGN KEY (`user_groups_id`) REFERENCES `df_user_groups` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `df_users`
--

LOCK TABLES `df_users` WRITE;
/*!40000 ALTER TABLE `df_users` DISABLE KEYS */;
INSERT INTO `df_users` (`id`, `name`, `username`, `password`, `is_active`, `user_groups_id`, `cities_id`) VALUES (1,'admin1','admin1','admin1',1,1,1),(2,'user1','user1','user1',1,2,2),(3,'user2','user2','user2',0,3,4),(4,'anonymous','anonymous','anonymous',0,NULL,NULL),(5,'John D','jd','jd',0,2,2),(6,'Alex B','alex','alex',1,3,3),(7,'Eugeny K','kas','kas',1,1,2),(8,'Serg','serg','serg',1,3,1),(9,'Goodle','goo','goo',1,2,4),(10,'Frank A','andy','andy',1,2,3),(11,'Anton','key','key',0,1,4),(12,'Mark','mark','mark',1,3,2),(13,'Beetle','juice','juice',1,3,4),(14,'Johnny','jo','jo',1,1,3),(15,'Tolan','tolja','tolja',1,3,4),(16,'Cool','cck','cck',0,1,4),(17,'Mango','man','man',1,2,3),(18,'Dean','tom','tom',1,2,4),(19,'Willy','will','will',1,3,3),(20,'Max','max','max',1,1,4),(21,'Peter','pete','pete',1,2,4),(22,'Van','van','van',1,1,4),(23,'Ken','ken','ken',1,3,4);
/*!40000 ALTER TABLE `df_users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2009-08-19 17:05:43
