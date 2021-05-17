-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.6.47-log


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema sqlia_api
--

CREATE DATABASE IF NOT EXISTS sqlia_api;
USE sqlia_api;

--
-- Definition of table `sqlia_api`
--

DROP TABLE IF EXISTS `sqlia_api`;
CREATE TABLE `sqlia_api` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `symbol_and _keyword` varchar(100) DEFAULT NULL,
  `sqlia_description` varchar(1000) DEFAULT NULL,
  `importance_order` int(10) unsigned DEFAULT NULL,
  `sqlia_type` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sqlia_api`
--

/*!40000 ALTER TABLE `sqlia_api` DISABLE KEYS */;
INSERT INTO `sqlia_api` (`id`,`symbol_and _keyword`,`sqlia_description`,`importance_order`,`sqlia_type`) VALUES 
 (1,'ADD','Current T-SQL Keyword',2,'Database Fingerprinting'),
 (2,'ALL','Current T-SQL Keyword',2,'Database Fingerprinting'),
 (3,'ALTER','Current T-SQL Keyword',2,'Database Fingerprinting'),
 (4,'AND','Current T-SQL Keyword',12,'Conditional Response'),
 (5,'ANY','Current T-SQL Keyword',12,'Conditional Response'),
 (6,'ABSOLUTE','Future SQL Keyword',13,'Illegal/Invalid/Logical Incorrect'),
 (7,'ACTION','Future SQL Keyword',13,'Illegal/Invalid/Logical Incorrect'),
 (8,'ADMIN','Future SQL Keyword',2,'Database Fingerprinting'),
 (9,'AFTER','Future SQL Keyword',16,'Database Mapping'),
 (10,'AGGREGATE','Future SQL Keyword',13,'Illegal/Invalid/Logical Incorrect'),
 (11,'\'','SQL Symbol',18,'Tautology'),
 (12,',','SQL Symbol',5,'Second Order'),
 (13,'.','SQL Symbol',18,'Tautology'),
 (14,';','SQL Symbol',14,'Piggy Back'),
 (15,':','SQL Symbol',18,'Tautology'),
 (16,'waitfor','SQLIA TYPE',1,'Time-based Error'),
 (17,'all_tab_columns','SQLIA TYPE',2,'Database Fingerprinting'),
 (18,'Exec','SQLIA TYPE',8,'Alternate Encoding'),
 (19,'Printf','SQLIA TYPE',4,'Buffer Overflow'),
 (20,'TRUE','SQLIA TYPE',6,'Deep Blind');
/*!40000 ALTER TABLE `sqlia_api` ENABLE KEYS */;


--
-- Definition of table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(80) NOT NULL,
  `name` varchar(80) NOT NULL,
  `password` varchar(80) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`,`username`,`name`,`password`) VALUES 
 (1,'Admin','Administrator','admin');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
