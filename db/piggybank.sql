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

INSERT INTO `Account` (`accountNumber`, `accountOwner`, `accountType`, `accountBalance`) VALUES
('eiaCbrqlXP',	'WqCLlDRhpy',	0,	185);

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
('WqCLlDRhpy',	'test',	'1970-10-10',	'aleieldin.salem@gmail.com',	'15 street',	'testuser');

DROP TABLE IF EXISTS `Employee`;
CREATE TABLE `Employee` (
  `employeeID` varchar(10) COLLATE latin1_bin NOT NULL,
  `employeeName` varchar(50) COLLATE latin1_bin NOT NULL,
  `employeeDOB` date NOT NULL,
  `employeeAddress` varchar(256) COLLATE latin1_bin NOT NULL,
  `employeeEmail` varchar(256) COLLATE latin1_bin NOT NULL,
  `employeeDepartment` int(11) NOT NULL,
  `employeeBranch` int(11) NOT NULL,
  `employeeUsername` varchar(50) COLLATE latin1_bin NOT NULL,
  PRIMARY KEY (`employeeID`),
  KEY `employeeUsername` (`employeeUsername`),
  CONSTRAINT `Employee_ibfk_1` FOREIGN KEY (`employeeUsername`) REFERENCES `User` (`userUsername`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

INSERT INTO `Employee` (`employeeID`, `employeeName`, `employeeDOB`, `employeeAddress`, `employeeEmail`, `employeeDepartment`, `employeeBranch`, `employeeUsername`) VALUES
('eAWMqgPUDG',	'Luke Skywalker',	'1980-12-12',	'1 The Empire Ave.',	'aleieldin.salem@gmail.com',	2,	7,	'luke');

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

INSERT INTO `Token` (`tokenID`, `tokenCustomer`, `tokenUsed`) VALUES
('0582ef2bc179372',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('06177ecd08b5ec6',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('0da050cd5b92d76',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('10c585cd5149f3f',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('11ac4fceb026fef',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('19cfd0ee4eacfdd',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('19f938363098b3b',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('1ab48ead2aacf26',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('1e193c5ead0e4ca',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('1eaeab00b7e732e',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('21c6f1c3105e6db',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('2231b59dab0c96a',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('2d9a4c7e351a6c1',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('30be4d2bd18ff2a',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('344621b9cfe82dd',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('34f412954edd603',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('355bdbe6d54f40d',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('3b986d1a9c8497d',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('3cd72a2b1ad020d',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('3d1006d62c6e789',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('3d50882d3749949',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('401c9e1b08eb66e',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('4145fa1b80b80eb',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('42bc06f432c454b',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('445b14e017beb8c',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('47e52298ae1fcc2',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('48060fe31a1c122',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('49177672c2e7458',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('4ac80ef2e450126',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('4b77f0553612df7',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('55c3624c64f774e',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('5bd70860bfa8655',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('62256698207a07d',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('64f81782a283d11',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('662fd1ef920883a',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('66325f3efb36a65',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('6830b1646005b61',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('6833780b861204d',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('685812550f43ffd',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('6c06f4a111fc5e8',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('6c637d39e6d253c',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('71df1d380351d87',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('72cf3b23cf6031a',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('75fab082c9b4c9e',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('7715cc130305193',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('7a19e7702ea5533',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('7bcbe96ead30c3f',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('7e06bdce584fb63',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('7e2d786f956293b',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('7e94b1cfd55cbd0',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('80be7ef57464278',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('85e677325663e53',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('8807fd08dc86881',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('8b47735668b7bf8',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('8ddeb1481dde026',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('9324cd1ec9f5a69',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('a2ba91d932366d7',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('a3336faa38eb204',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('a4e383b88a6d546',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('a7aa42845cfaefa',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('a8dbdfa8cd02240',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('a97638c04607f08',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('adb5b2c9735d2dd',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('b1c19a261ef21c1',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('b568e1a8f9639d3',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('b864cf506f3fc92',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('bc2110d898505ea',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('c37b3cc45612fdc',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('c707474f19164fe',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('c985964b75d3a70',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('c9afea30af6e31e',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('ca0682d07f07a2c',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('cacf09229fbcf5a',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('ce2ada7f2034784',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('cf0fb55f5820e9f',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('d280bce3c4df655',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('d617dc81d6c70fa',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('d8d8cec79023f6e',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('da8a6568969f5b8',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('ddbc9dec3e87da7',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('e29c2276a9ae6d4',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('e34f3cb6d7ca6f5',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('e39de72297fb40e',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('e3d821501068c39',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('e569f5c50099799',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('e7414d04e1fa838',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('e9d1750d60413db',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('ecffa414a04e956',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('ee745ecda2bafb8',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('eec7b33563a9778',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('f156d34a78cb31e',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('f453153b709c913',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('f8049d30bbd514c',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('f9a323d3dfdd06f',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('f9e123497fbffe3',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('f9f260a4d73cfbf',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('fb0672633822ac7',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('fcaa5c89dbaa0c9',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('fcb6a6bc181b651',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0),
('fe76d40eafe8a42',	'WqCLlDRhpy',	CONV('0', 2, 10) + 0);

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
('luke',	'743edcf941b967222a1b21a084ced8f7493e0a2701d3bef99eb1d5f5a0455f14',	1,	CONV('1', 2, 10) + 0),
('testuser',	'd8a928b2043db77e340b523547bf16cb4aa483f0645fe0a290ed1f20aab76257',	2,	CONV('1', 2, 10) + 0);

-- 2014-10-27 18:06:33
