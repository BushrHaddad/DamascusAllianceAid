-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.31 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             9.3.0.4984
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping structure for table churchcrm_master_table.bags
CREATE TABLE IF NOT EXISTS `bags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `note1` varchar(50) DEFAULT NULL,
  `note2` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Dumping data for table churchcrm_master_table.bags: ~2 rows (approximately)
/*!40000 ALTER TABLE `bags` DISABLE KEYS */;
INSERT INTO `bags` (`id`, `name`, `note1`, `note2`) VALUES
	(1, 'milk', NULL, NULL),
	(2, 'Diabers', NULL, NULL),
	(3, 'milk + Diabers', NULL, NULL);
/*!40000 ALTER TABLE `bags` ENABLE KEYS */;


-- Dumping structure for table churchcrm_master_table.cash
CREATE TABLE IF NOT EXISTS `cash` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `note1` varchar(50) DEFAULT NULL,
  `note2` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Dumping data for table churchcrm_master_table.cash: ~2 rows (approximately)
/*!40000 ALTER TABLE `cash` DISABLE KEYS */;
INSERT INTO `cash` (`id`, `name`, `note1`, `note2`) VALUES
	(1, '5000', NULL, NULL),
	(2, '10000', NULL, NULL),
	(3, '15000', NULL, NULL);
/*!40000 ALTER TABLE `cash` ENABLE KEYS */;


-- Dumping structure for table churchcrm_master_table.dates_months
CREATE TABLE IF NOT EXISTS `dates_months` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `note1` varchar(50) DEFAULT NULL,
  `note2` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- Dumping data for table churchcrm_master_table.dates_months: ~11 rows (approximately)
/*!40000 ALTER TABLE `dates_months` DISABLE KEYS */;
INSERT INTO `dates_months` (`id`, `name`, `note1`, `note2`) VALUES
	(1, '1', NULL, NULL),
	(2, '2', NULL, NULL),
	(3, '3', NULL, NULL),
	(4, '4', NULL, NULL),
	(5, '5', NULL, NULL),
	(6, '6', NULL, NULL),
	(7, '7', NULL, NULL),
	(8, '8', NULL, NULL),
	(9, '9', NULL, NULL),
	(10, '10', NULL, NULL),
	(11, '11', NULL, NULL),
	(12, '12', NULL, NULL);
/*!40000 ALTER TABLE `dates_months` ENABLE KEYS */;


-- Dumping structure for table churchcrm_master_table.dates_year
CREATE TABLE IF NOT EXISTS `dates_year` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `note1` varchar(50) DEFAULT NULL,
  `note2` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Dumping data for table churchcrm_master_table.dates_year: ~2 rows (approximately)
/*!40000 ALTER TABLE `dates_year` DISABLE KEYS */;
INSERT INTO `dates_year` (`id`, `name`, `note1`, `note2`) VALUES
	(1, '2019', NULL, NULL),
	(2, '2020', NULL, NULL),
	(3, '2021', NULL, NULL);
/*!40000 ALTER TABLE `dates_year` ENABLE KEYS */;


-- Dumping structure for table churchcrm_master_table.family_master
CREATE TABLE IF NOT EXISTS `family_master` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(180) NOT NULL,
  `year_id` int(11) NOT NULL,
  `month_id` int(11) NOT NULL,
  `visited_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `cash_id` int(11) NOT NULL,
  `bag_id` int(11) NOT NULL,
  `sup_id` int(11) NOT NULL,
  `famly_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `famly_id` (`famly_id`),
  KEY `sup_id` (`sup_id`),
  KEY `bag_id` (`bag_id`),
  KEY `cash_id` (`cash_id`),
  KEY `team_id` (`team_id`),
  KEY `visited_id` (`visited_id`),
  KEY `date_id` (`year_id`),
  KEY `month_id` (`month_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- Dumping data for table churchcrm_master_table.family_master: ~0 rows (approximately)
/*!40000 ALTER TABLE `family_master` DISABLE KEYS */;
INSERT INTO `family_master` (`id`, `name`, `year_id`, `month_id`, `visited_id`, `team_id`, `cash_id`, `bag_id`, `sup_id`, `famly_id`) VALUES
	(1, '1', 3, 1, 1, 1, 1, 1000, 1000, 1);
/*!40000 ALTER TABLE `family_master` ENABLE KEYS */;


-- Dumping structure for table churchcrm_master_table.suppliments
CREATE TABLE IF NOT EXISTS `suppliments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `note1` varchar(50) DEFAULT NULL,
  `note2` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table churchcrm_master_table.suppliments: ~2 rows (approximately)
/*!40000 ALTER TABLE `suppliments` DISABLE KEYS */;
INSERT INTO `suppliments` (`id`, `name`, `note1`, `note2`) VALUES
	(1, 'sp1', NULL, NULL),
	(2, 'sp2', NULL, NULL);
/*!40000 ALTER TABLE `suppliments` ENABLE KEYS */;


-- Dumping structure for table churchcrm_master_table.teams
CREATE TABLE IF NOT EXISTS `teams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `note1` varchar(50) DEFAULT NULL,
  `note2` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Dumping data for table churchcrm_master_table.teams: ~4 rows (approximately)
/*!40000 ALTER TABLE `teams` DISABLE KEYS */;
INSERT INTO `teams` (`id`, `name`, `note1`, `note2`) VALUES
	(1, 'T1', NULL, NULL),
	(2, 'T2', NULL, NULL),
	(3, 'T3', NULL, NULL),
	(4, 'T4', NULL, NULL);
/*!40000 ALTER TABLE `teams` ENABLE KEYS */;


-- Dumping structure for table churchcrm_master_table.visiting
CREATE TABLE IF NOT EXISTS `visiting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `note1` varchar(50) DEFAULT NULL,
  `note2` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- Dumping data for table churchcrm_master_table.visiting: ~2 rows (approximately)
/*!40000 ALTER TABLE `visiting` DISABLE KEYS */;
INSERT INTO `visiting` (`id`, `name`, `note1`, `note2`) VALUES
	(1, 'yes', NULL, NULL),
	(2, 'no', NULL, NULL);
/*!40000 ALTER TABLE `visiting` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
