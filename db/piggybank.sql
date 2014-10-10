-- Adminer 4.1.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

CREATE DATABASE `piggybank` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `piggybank`;

DROP TABLE IF EXISTS `Account`;
CREATE TABLE `Account` (
  `accountID` int(11) NOT NULL AUTO_INCREMENT,
  `accountOwner` varchar(10) COLLATE latin1_bin NOT NULL,
  `accountType` int(11) NOT NULL,
  `accountBalance` double NOT NULL,
  PRIMARY KEY (`accountID`),
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
  `customerEnabled` bit(1) NOT NULL DEFAULT b'0',
  `customerUsername` varchar(50) COLLATE latin1_bin NOT NULL,
  PRIMARY KEY (`customerID`),
  KEY `customerUsername` (`customerUsername`),
  CONSTRAINT `Customer_ibfk_1` FOREIGN KEY (`customerUsername`) REFERENCES `User` (`userUsername`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;


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
  CONSTRAINT `Transaction_ibfk_3` FOREIGN KEY (`transactionToken`) REFERENCES `Token` (`tokenID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `Transaction_ibfk_1` FOREIGN KEY (`transactionSender`) REFERENCES `Customer` (`customerID`),
  CONSTRAINT `Transaction_ibfk_2` FOREIGN KEY (`transactionReceiver`) REFERENCES `Customer` (`customerID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;


DROP TABLE IF EXISTS `User`;
CREATE TABLE `User` (
  `userUsername` varchar(50) COLLATE latin1_bin NOT NULL,
  `userPassword` varchar(64) COLLATE latin1_bin NOT NULL,
  `userRole` int(11) NOT NULL,
  PRIMARY KEY (`userUsername`),
  KEY `userRole` (`userRole`),
  CONSTRAINT `User_ibfk_1` FOREIGN KEY (`userRole`) REFERENCES `Role` (`roleID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;


-- 2014-10-10 15:59:08
