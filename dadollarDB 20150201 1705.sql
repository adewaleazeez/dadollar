-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.0.24a-community-nt


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


--
-- Create schema dadollar
--

CREATE DATABASE IF NOT EXISTS dadollar;
USE dadollar;

--
-- Definition of table `usersmenu`
--

DROP TABLE IF EXISTS `usersmenu`;
CREATE TABLE `usersmenu` (
  `serialno` int(11) NOT NULL auto_increment,
  `userName` varchar(50) NOT NULL,
  `menuOption` varchar(50) NOT NULL,
  `accessibility` varchar(10) NOT NULL,
  PRIMARY KEY  (`serialno`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `usersmenu`
--

/*!40000 ALTER TABLE `usersmenu` DISABLE KEYS */;
INSERT INTO `usersmenu` (`serialno`,`userName`,`menuOption`,`accessibility`) VALUES 
 (10,'','Customers Setup','No'),
 (20,'','Deposits Posting','No'),
 (30,'','Withdrawals Posting','No'),
 (40,'','Loans Posting','No'),
 (50,'','Lock Records','No'),
 (60,'','Lock Customers','No'),
 (62,'','Lock Withdrawal','No'),
 (64,'','Delete Record','No'),
 (70,'','Manage Users','No'),
 (80,'','Users Access Control','No'),
 (90,'','Change Users Password','Yes'),
 (100,'','Customer Balances','No'),
 (110,'','Customers Statement','No'),
 (120,'','Transactions Listing','No'),
 (130,'','Loans Report','No'),
 (140,'Admin','Customers Setup','Yes'),
 (150,'Admin','Deposits Posting','Yes'),
 (160,'Admin','Withdrawals Posting','Yes'),
 (170,'Admin','Loans Posting','Yes'),
 (180,'Admin','Lock Records','Yes'),
 (190,'Admin','Lock Customers','Yes'),
 (192,'Admin','Lock Withdrawal','No'),
 (194,'Admin','Delete Record','Yes'),
 (200,'Admin','Manage Users','Yes'),
 (210,'Admin','Users Access Control','Yes'),
 (220,'Admin','Change Users Password','Yes'),
 (230,'Admin','Customer Balances','Yes'),
 (240,'Admin','Customers Statement','Yes'),
 (250,'Admin','Transactions Listing','Yes'),
 (260,'Admin','Loans Report','Yes'),
 (261,'user2','Customers Setup','No'),
 (262,'user2','Deposits Posting','No'),
 (263,'user2','Withdrawals Posting','No'),
 (264,'user2','Loans Posting','No'),
 (265,'user2','Lock Records','No'),
 (266,'user2','Lock Customers','No'),
 (267,'user2','Manage Users','No'),
 (268,'user2','Users Access Control','No'),
 (269,'user2','Change Users Password','Yes'),
 (270,'user2','Customer Balances','No'),
 (271,'user2','Customers Statement','No'),
 (272,'user2','Transactions Listing','No'),
 (273,'user2','Loans Report','No');
/*!40000 ALTER TABLE `usersmenu` ENABLE KEYS */;




/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
