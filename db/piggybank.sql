-- Adminer 4.1.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP DATABASE IF EXISTS `piggybank`;
CREATE DATABASE `piggybank` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `piggybank`;

DELIMITER ;;

CREATE PROCEDURE `authUser`(IN `iUsername` varchar(50), IN `iPassword` varchar(64), OUT `oRole` varchar(50))
BEGIN
SELECT Role.roleDesc INTO oRole
FROM User INNER JOIN Role
WHERE User.userRole = Role.roleID
AND User.userUsername = iUsername
AND User.userPassword = iPassword
AND User.userApproved = 1;
END;;

DELIMITER ;

DROP TABLE IF EXISTS `Account`;
CREATE TABLE `Account` (
  `accountNumber` varchar(10) COLLATE latin1_bin NOT NULL,
  `accountOwner` varchar(10) COLLATE latin1_bin NOT NULL,
  `accountType` int(11) NOT NULL,
  `accountBalance` double NOT NULL,
  PRIMARY KEY (`accountNumber`),
  KEY `accountOwner` (`accountOwner`),
  CONSTRAINT `Account_ibfk_1` FOREIGN KEY (`accountOwner`) REFERENCES `Customer` (`customerID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;


DROP TABLE IF EXISTS `Customer`;
CREATE TABLE `Customer` (
  `customerID` varchar(10) COLLATE latin1_bin NOT NULL,
  `customerName` varchar(256) COLLATE latin1_bin NOT NULL,
  `customerDOB` date NOT NULL,
  `customerEmail` varchar(256) COLLATE latin1_bin NOT NULL,
  `customerAddress` varchar(256) COLLATE latin1_bin DEFAULT NULL,
  `customerUsername` varchar(50) COLLATE latin1_bin NOT NULL,
  PRIMARY KEY (`customerID`),
  KEY `customerUsername` (`customerUsername`),
  CONSTRAINT `Customer_ibfk_1` FOREIGN KEY (`customerUsername`) REFERENCES `User` (`userUsername`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

INSERT INTO `Customer` (`customerID`, `customerName`, `customerDOB`, `customerEmail`, `customerAddress`, `customerUsername`) VALUES
('WLMmsxCEWf',	'Alei',	'0000-00-00',	'aesalem@bla.com',	'1 elbosta st.',	'aesalem'),
('eTEcvT_N_i',	'John Wayne',	'0000-00-00',	'wayne@www.com',	'1 Wild Wild West Ave',	'johnwayne'),
('rEXEzhGiCe',	'a',	'0000-00-00',	'a@a.com',	'a',	'a.b');

DROP TABLE IF EXISTS `Employee`;
CREATE TABLE `Employee` (
  `employeeID` int(11) NOT NULL,
  `employeeName` varchar(50) COLLATE latin1_bin NOT NULL,
  `employeeDOB` date NOT NULL,
  `employeeAddress` varchar(256) COLLATE latin1_bin NOT NULL,
  `employeeDepartment` int(11) NOT NULL,
  `employeeBranch` int(11) NOT NULL,
  `employeeUsername` varchar(50) COLLATE latin1_bin NOT NULL,
  PRIMARY KEY (`employeeID`),
  KEY `employeeUsername` (`employeeUsername`),
  CONSTRAINT `Employee_ibfk_1` FOREIGN KEY (`employeeUsername`) REFERENCES `User` (`userUsername`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;


DROP TABLE IF EXISTS `Role`;
CREATE TABLE `Role` (
  `roleID` int(11) NOT NULL AUTO_INCREMENT,
  `roleDesc` varchar(50) NOT NULL,
  PRIMARY KEY (`roleID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO `Role` (`roleID`, `roleDesc`) VALUES
(1,	'admin'),
(2,	'customer');

DROP TABLE IF EXISTS `Token`;
CREATE TABLE `Token` (
  `tokenID` varchar(15) COLLATE latin1_bin NOT NULL,
  `tokenCustomer` varchar(10) COLLATE latin1_bin NOT NULL,
  `tokenUsed` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`tokenID`),
  KEY `tokenCustomer` (`tokenCustomer`),
  CONSTRAINT `Token_ibfk_1` FOREIGN KEY (`tokenCustomer`) REFERENCES `Customer` (`customerID`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;


DROP TABLE IF EXISTS `Transaction`;
CREATE TABLE `Transaction` (
  `transactionID` varchar(20) COLLATE latin1_bin NOT NULL,
  `transactionSender` varchar(10) COLLATE latin1_bin NOT NULL,
  `transactionReceiver` varchar(10) COLLATE latin1_bin NOT NULL,
  `transactionAmont` double NOT NULL,
  `transactionTime` datetime NOT NULL,
  `transactionApproved` bit(1) NOT NULL DEFAULT b'0',
  `transactionToken` varchar(15) COLLATE latin1_bin NOT NULL,
  PRIMARY KEY (`transactionID`),
  KEY `transactionSender` (`transactionSender`),
  KEY `transactionReceiver` (`transactionReceiver`),
  KEY `transactionToken` (`transactionToken`),
  CONSTRAINT `Transaction_ibfk_1` FOREIGN KEY (`transactionSender`) REFERENCES `Customer` (`customerID`),
  CONSTRAINT `Transaction_ibfk_2` FOREIGN KEY (`transactionReceiver`) REFERENCES `Customer` (`customerID`),
  CONSTRAINT `Transaction_ibfk_3` FOREIGN KEY (`transactionToken`) REFERENCES `Token` (`tokenID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;


DROP TABLE IF EXISTS `User`;
CREATE TABLE `User` (
  `userUsername` varchar(50) COLLATE latin1_bin NOT NULL,
  `userPassword` varchar(64) COLLATE latin1_bin NOT NULL,
  `userRole` int(11) NOT NULL,
  `userApproved` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`userUsername`),
  KEY `userRole` (`userRole`),
  CONSTRAINT `User_ibfk_1` FOREIGN KEY (`userRole`) REFERENCES `Role` (`roleID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

INSERT INTO `User` (`userUsername`, `userPassword`, `userRole`, `userApproved`) VALUES
('a.b',	'd8a928b2043db77e340b523547bf16cb4aa483f0645fe0a290ed1f20aab76257',	2,	CONV('0', 2, 10) + 0),
('aesalem',	'743edcf941b967222a1b21a084ced8f7493e0a2701d3bef99eb1d5f5a0455f14',	2,	CONV('1', 2, 10) + 0),
('johnwayne',	'd8a928b2043db77e340b523547bf16cb4aa483f0645fe0a290ed1f20aab76257',	2,	CONV('0', 2, 10) + 0);

-- 2014-10-24 17:01:09
