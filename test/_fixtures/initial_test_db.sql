-- MySQL dump 10.13  Distrib 5.5.20, for osx10.7 (i386)
--
-- Host: localhost    Database: testing
-- ------------------------------------------------------
-- Server version	5.5.20

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
-- Table structure for table `sprockets`
--

DROP TABLE IF EXISTS `sprockets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sprockets` (
  `title` varchar(255) DEFAULT NULL,
  `sprocket_id` int(11) NOT NULL AUTO_INCREMENT,
  `descr` text,
  `type_id` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`sprocket_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sprockets`
--

LOCK TABLES `sprockets` WRITE;
/*!40000 ALTER TABLE `sprockets` DISABLE KEYS */;
INSERT INTO `sprockets` VALUES ('5397182fed3869547139e31bcd36e536',1,'627d1a84-a976-11e2-910d-973c674f56c2',24,'2013-04-19 23:54:29'),('59937e546341411e72996003bb97bcd1',2,'627d1bba-a976-11e2-910d-973c674f56c2',25,'2013-04-19 23:54:29'),('155e5d75b8461058ce49f26f9a6e8440',3,'627d1c00-a976-11e2-910d-973c674f56c2',26,'2013-04-19 23:54:29'),('ec9cdeaa47e8dfa4c593029a842407f5',4,'627d1c46-a976-11e2-910d-973c674f56c2',27,'2013-04-19 23:54:29'),('3ab3b1eaa3cd8049b89f4133fa54c531',5,'627d1c82-a976-11e2-910d-973c674f56c2',28,'2013-04-19 23:54:29'),('bbd68671b9b3841a3b43a9a018708c13',6,'627d1cb4-a976-11e2-910d-973c674f56c2',29,'2013-04-19 23:54:29'),('cfdd54ded8c9d81729569f37cf01fdcf',7,'627d1d2c-a976-11e2-910d-973c674f56c2',30,'2013-04-19 23:54:29'),('815ff2889752897f120037010b36f921',8,'627d1d68-a976-11e2-910d-973c674f56c2',31,'2013-04-19 23:54:29'),('245344a9c9fdcd00a97482e2b42efd59',9,'627d1da4-a976-11e2-910d-973c674f56c2',32,'2013-04-19 23:54:29'),('962e9985f90fe6e1d438e042487c0725',10,'627d1dd6-a976-11e2-910d-973c674f56c2',33,'2013-04-19 23:54:29');
/*!40000 ALTER TABLE `sprockets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `widgets`
--

DROP TABLE IF EXISTS `widgets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `widgets` (
  `title` varchar(255) DEFAULT NULL,
  `widget_id` int(11) NOT NULL AUTO_INCREMENT,
  `descr` text,
  `type_id` int(11) DEFAULT NULL,
  `updated` datetime DEFAULT NULL,
  PRIMARY KEY (`widget_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `widgets`
--

LOCK TABLES `widgets` WRITE;
/*!40000 ALTER TABLE `widgets` DISABLE KEYS */;
INSERT INTO `widgets` VALUES ('2a067a09feb7ff9469b28a2da8633326',1,'6eb54e2a-a949-11e2-910d-973c674f56c2',38,'2013-04-19 23:50:10'),('f44d6c28dd0ccd5aab7d8ac6f3779317',2,'6eb55118-a949-11e2-910d-973c674f56c2',39,'2013-04-19 23:50:10'),('bfcf5c5605bfac4771339ba506fe010a',3,'6eb55302-a949-11e2-910d-973c674f56c2',40,'2013-04-19 23:50:10'),('6b28d86415f1dca50408d68729870606',4,'6eb5542e-a949-11e2-910d-973c674f56c2',41,'2013-04-19 23:50:10'),('d6813682ef256981af0804131c6e511b',5,'6eb55550-a949-11e2-910d-973c674f56c2',42,'2013-04-19 23:50:10'),('3f0ea5d32694d6e9e5e48b151f2a110e',6,'6eb55668-a949-11e2-910d-973c674f56c2',43,'2013-04-19 23:50:10'),('91a865b2a9203759adc67cc782f106f3',7,'6eb55780-a949-11e2-910d-973c674f56c2',44,'2013-04-19 23:50:10'),('c07b9092ab18cdf2d7f1b16c45aa661a',8,'6eb558a2-a949-11e2-910d-973c674f56c2',45,'2013-04-19 23:50:10'),('5c47eb59a87972b52ef54d28468af150',9,'6eb559b0-a949-11e2-910d-973c674f56c2',46,'2013-04-19 23:50:10'),('991e4d03df889f9b6c30a1730a1643ae',10,'6eb55ac8-a949-11e2-910d-973c674f56c2',47,'2013-04-19 23:50:10');
/*!40000 ALTER TABLE `widgets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `widgets_to_sprockets`
--

DROP TABLE IF EXISTS `widgets_to_sprockets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `widgets_to_sprockets` (
  `widget_id` int(11) DEFAULT NULL,
  `sprocket_id` int(11) DEFAULT NULL,
  UNIQUE KEY `test_id` (`widget_id`,`sprocket_id`),
  KEY `subtest_id` (`sprocket_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `widgets_to_sprockets`
--

LOCK TABLES `widgets_to_sprockets` WRITE;
/*!40000 ALTER TABLE `widgets_to_sprockets` DISABLE KEYS */;
INSERT INTO `widgets_to_sprockets` VALUES (1,2),(2,2),(3,1),(4,3),(4,6),(5,1),(5,7),(8,3),(9,9);
/*!40000 ALTER TABLE `widgets_to_sprockets` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-04-20  0:08:40
