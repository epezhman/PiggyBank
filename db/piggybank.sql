-- Adminer 4.1.0 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP DATABASE IF EXISTS `piggybank`;
CREATE DATABASE `piggybank` /*!40100 DEFAULT CHARACTER SET latin1 COLLATE latin1_bin */;
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
('PB33845364',	'9758739787',	0,	1300),
('PB93940281',	'8804500619',	0,	13820);

DROP TABLE IF EXISTS `Customer`;
CREATE TABLE `Customer` (
  `customerID` varchar(10) COLLATE latin1_bin NOT NULL,
  `customerName` varchar(256) COLLATE latin1_bin NOT NULL,
  `customerDOB` date NOT NULL,
  `customerEmail` varchar(256) COLLATE latin1_bin NOT NULL,
  `customerAddress` varchar(256) COLLATE latin1_bin DEFAULT NULL,
  `customerUsername` varchar(50) COLLATE latin1_bin NOT NULL,
  `customerPIN` varchar(64) COLLATE latin1_bin DEFAULT NULL,
  `customerTransferSecurityMethod` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`customerID`),
  KEY `customerUsername` (`customerUsername`),
  CONSTRAINT `Customer_ibfk_1` FOREIGN KEY (`customerUsername`) REFERENCES `User` (`userUsername`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

INSERT INTO `Customer` (`customerID`, `customerName`, `customerDOB`, `customerEmail`, `customerAddress`, `customerUsername`, `customerPIN`, `customerTransferSecurityMethod`) VALUES
('8804500619',	'John Doe',	'1987-06-12',	'aleieldin.salem@gmail.com',	'1 Main St.',	'john',	'0',	1),
('9758739787',	'Ray Charles',	'1922-11-12',	'aleieldin.salem@gmail.com',	'1 Blues Ave',	'ray',	'0',	1);

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
('E285277066',	'Dame Helen Mirren',	'1950-11-12',	'15 Tottehnham Court Road',	'aleieldin.salem@gmail.com',	1,	2,	'helen');

DROP TABLE IF EXISTS `ResetTokens`;
CREATE TABLE `ResetTokens` (
  `resetTokenID` varchar(40) COLLATE latin1_bin NOT NULL,
  `resetTokenTimestamp` int(11) NOT NULL,
  `resetTokenUsername` varchar(50) COLLATE latin1_bin NOT NULL,
  PRIMARY KEY (`resetTokenID`),
  KEY `resetTokenUsername` (`resetTokenUsername`),
  CONSTRAINT `ResetTokens_ibfk_1` FOREIGN KEY (`resetTokenUsername`) REFERENCES `User` (`userUsername`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;


DROP TABLE IF EXISTS `Role`;
CREATE TABLE `Role` (
  `roleID` int(11) NOT NULL AUTO_INCREMENT,
  `roleDesc` varchar(50) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`roleID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

INSERT INTO `Role` (`roleID`, `roleDesc`) VALUES
(1,	'employee'),
(2,	'customer'),
(3,	'admin');

DROP TABLE IF EXISTS `SecurityQuestion`;
CREATE TABLE `SecurityQuestion` (
  `securityQuestionID` int(11) NOT NULL AUTO_INCREMENT,
  `securityQuestionDesc` varchar(500) COLLATE latin1_bin NOT NULL,
  PRIMARY KEY (`securityQuestionID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

INSERT INTO `SecurityQuestion` (`securityQuestionID`, `securityQuestionDesc`) VALUES
(1,	'What was the make and model of your first car?'),
(2,	'What was the name of your elementary / primary school?'),
(3,	'In what city or town did you meet your spouse/partner?'),
(4,	'What is the name of your favorite school teacher?'),
(5,	'Who was your childhood hero?'),
(6,	'Where were you New Year\'s 2000?');

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
('00cec2e825ec40c',	'9758739787',	CONV('0', 2, 10) + 0),
('03efabc7fa0d5a7',	'8804500619',	CONV('0', 2, 10) + 0),
('05038628c5e02c4',	'9758739787',	CONV('0', 2, 10) + 0),
('069327d2303fcd7',	'8804500619',	CONV('0', 2, 10) + 0),
('07a0ec29ed23bb5',	'8804500619',	CONV('0', 2, 10) + 0),
('08d2b0bc010982b',	'8804500619',	CONV('0', 2, 10) + 0),
('093ed821e03f82c',	'8804500619',	CONV('0', 2, 10) + 0),
('0adf5f17e6e2b6c',	'9758739787',	CONV('0', 2, 10) + 0),
('0d5227adf3825dd',	'8804500619',	CONV('0', 2, 10) + 0),
('0e703599d1e6d8f',	'8804500619',	CONV('0', 2, 10) + 0),
('0edc26ce9bef992',	'8804500619',	CONV('0', 2, 10) + 0),
('112a3e43a0105b3',	'9758739787',	CONV('0', 2, 10) + 0),
('13d73e569e1343b',	'8804500619',	CONV('0', 2, 10) + 0),
('150b6a001f9e4a8',	'9758739787',	CONV('0', 2, 10) + 0),
('1598c3a9a9424f9',	'8804500619',	CONV('0', 2, 10) + 0),
('16c05afc69db176',	'9758739787',	CONV('0', 2, 10) + 0),
('173bd131e014b6f',	'8804500619',	CONV('0', 2, 10) + 0),
('17b97775c6486fc',	'8804500619',	CONV('0', 2, 10) + 0),
('17f5115d4a54629',	'8804500619',	CONV('0', 2, 10) + 0),
('17fd259650d5ef7',	'9758739787',	CONV('0', 2, 10) + 0),
('1d423de03c63d9e',	'9758739787',	CONV('0', 2, 10) + 0),
('1d81d657aef68c6',	'8804500619',	CONV('0', 2, 10) + 0),
('1dc87fab85ab5fb',	'9758739787',	CONV('0', 2, 10) + 0),
('1f172a91ac489ad',	'9758739787',	CONV('0', 2, 10) + 0),
('1f882c8b8906e96',	'9758739787',	CONV('0', 2, 10) + 0),
('1ffd80d00f6e0ea',	'8804500619',	CONV('0', 2, 10) + 0),
('202aa9933c4be10',	'8804500619',	CONV('0', 2, 10) + 0),
('21489a6bb750bd9',	'9758739787',	CONV('0', 2, 10) + 0),
('24c22e7325915e7',	'9758739787',	CONV('0', 2, 10) + 0),
('253165092df4ace',	'8804500619',	CONV('0', 2, 10) + 0),
('256564eb7c1f607',	'8804500619',	CONV('0', 2, 10) + 0),
('264526ae620060d',	'9758739787',	CONV('0', 2, 10) + 0),
('27721b5b03bc3b2',	'9758739787',	CONV('0', 2, 10) + 0),
('2884a610bf7c73d',	'9758739787',	CONV('0', 2, 10) + 0),
('2a74bd9d397ba3d',	'8804500619',	CONV('0', 2, 10) + 0),
('2a84fdb793622ae',	'8804500619',	CONV('0', 2, 10) + 0),
('2da471f279fbec8',	'8804500619',	CONV('0', 2, 10) + 0),
('2e0fbd6540c0dc6',	'8804500619',	CONV('0', 2, 10) + 0),
('2f313485a2d286f',	'8804500619',	CONV('0', 2, 10) + 0),
('301affe29ce1b4b',	'8804500619',	CONV('0', 2, 10) + 0),
('303df2ad94a484d',	'8804500619',	CONV('0', 2, 10) + 0),
('30839173c42968e',	'9758739787',	CONV('0', 2, 10) + 0),
('3093b5619db8af3',	'9758739787',	CONV('0', 2, 10) + 0),
('30c3218e190cd51',	'9758739787',	CONV('0', 2, 10) + 0),
('31763ff02411a09',	'8804500619',	CONV('0', 2, 10) + 0),
('3184f85f9a38ee8',	'8804500619',	CONV('0', 2, 10) + 0),
('329670bf277eb50',	'8804500619',	CONV('1', 2, 10) + 0),
('3392b5e7c192d0f',	'9758739787',	CONV('0', 2, 10) + 0),
('3487121dded86ad',	'8804500619',	CONV('0', 2, 10) + 0),
('350aa4ec48b5f56',	'9758739787',	CONV('0', 2, 10) + 0),
('3585ee39339fd05',	'9758739787',	CONV('0', 2, 10) + 0),
('35b85bac34f78eb',	'8804500619',	CONV('0', 2, 10) + 0),
('36c83ea609bc9e0',	'8804500619',	CONV('0', 2, 10) + 0),
('370ab5ab881506a',	'9758739787',	CONV('0', 2, 10) + 0),
('39eefe118c651ba',	'8804500619',	CONV('0', 2, 10) + 0),
('3b27cb477011a3d',	'9758739787',	CONV('0', 2, 10) + 0),
('3bf373cef975a7b',	'9758739787',	CONV('0', 2, 10) + 0),
('3e897ccf87dd9bb',	'8804500619',	CONV('0', 2, 10) + 0),
('3ed8879d15ce9e6',	'9758739787',	CONV('0', 2, 10) + 0),
('402b6b17f886e47',	'8804500619',	CONV('0', 2, 10) + 0),
('407276c620a7db6',	'9758739787',	CONV('0', 2, 10) + 0),
('407c58794ab5ae4',	'8804500619',	CONV('0', 2, 10) + 0),
('435592088f39d23',	'9758739787',	CONV('0', 2, 10) + 0),
('4438ea05c78d602',	'8804500619',	CONV('0', 2, 10) + 0),
('44c10ce4c464f5b',	'9758739787',	CONV('0', 2, 10) + 0),
('47815cf09d27218',	'9758739787',	CONV('0', 2, 10) + 0),
('493613656b565b4',	'9758739787',	CONV('0', 2, 10) + 0),
('4e549e13d5f050c',	'9758739787',	CONV('0', 2, 10) + 0),
('4e8c9e7c92a5493',	'9758739787',	CONV('0', 2, 10) + 0),
('4f3b277ac3cd527',	'9758739787',	CONV('0', 2, 10) + 0),
('5191461fd69e393',	'9758739787',	CONV('0', 2, 10) + 0),
('544daffa79424e9',	'9758739787',	CONV('0', 2, 10) + 0),
('562298bd896523d',	'8804500619',	CONV('0', 2, 10) + 0),
('562cd73a12810b9',	'9758739787',	CONV('0', 2, 10) + 0),
('575990dc2d5bd79',	'9758739787',	CONV('0', 2, 10) + 0),
('57f357c7ad21e6c',	'8804500619',	CONV('0', 2, 10) + 0),
('5b9caaa7ded977c',	'8804500619',	CONV('0', 2, 10) + 0),
('5da4e09c5402217',	'8804500619',	CONV('0', 2, 10) + 0),
('5dc56a59b5e8674',	'8804500619',	CONV('0', 2, 10) + 0),
('5ed99a2f5fd4768',	'8804500619',	CONV('0', 2, 10) + 0),
('5f2d983e1f95846',	'9758739787',	CONV('0', 2, 10) + 0),
('602761a5648e166',	'9758739787',	CONV('0', 2, 10) + 0),
('60e2f294fe39373',	'8804500619',	CONV('0', 2, 10) + 0),
('617bb03e9daa497',	'9758739787',	CONV('0', 2, 10) + 0),
('63d2a9eb3e70508',	'8804500619',	CONV('0', 2, 10) + 0),
('64e1c6cedbe84b1',	'8804500619',	CONV('0', 2, 10) + 0),
('665299cbe6c33b4',	'9758739787',	CONV('0', 2, 10) + 0),
('66b9200ca61c545',	'9758739787',	CONV('0', 2, 10) + 0),
('66c6f19fbe6a4ac',	'8804500619',	CONV('0', 2, 10) + 0),
('678454a266ade29',	'9758739787',	CONV('0', 2, 10) + 0),
('67ab3ead548c21d',	'9758739787',	CONV('0', 2, 10) + 0),
('6872c5b0c36a043',	'9758739787',	CONV('0', 2, 10) + 0),
('6a6a13384b7db9b',	'8804500619',	CONV('0', 2, 10) + 0),
('6d6a7a494358c33',	'8804500619',	CONV('0', 2, 10) + 0),
('6f54c400dd6355a',	'8804500619',	CONV('0', 2, 10) + 0),
('6f87ef0e0918184',	'8804500619',	CONV('0', 2, 10) + 0),
('6fc391797bc5f3c',	'8804500619',	CONV('0', 2, 10) + 0),
('703ca44f85ec483',	'9758739787',	CONV('0', 2, 10) + 0),
('77742cacbe48fc3',	'8804500619',	CONV('0', 2, 10) + 0),
('79bd484e4a3c0b4',	'8804500619',	CONV('0', 2, 10) + 0),
('7a43831e79560f2',	'8804500619',	CONV('0', 2, 10) + 0),
('7aea3ada8a7513e',	'8804500619',	CONV('0', 2, 10) + 0),
('7c3acef32945a5d',	'8804500619',	CONV('1', 2, 10) + 0),
('7e342ff9fdc9d00',	'9758739787',	CONV('0', 2, 10) + 0),
('7f473feeaaed485',	'9758739787',	CONV('0', 2, 10) + 0),
('7fc3dff259e9189',	'8804500619',	CONV('0', 2, 10) + 0),
('8015ed2be2cac23',	'8804500619',	CONV('0', 2, 10) + 0),
('812ef3e16e7a455',	'8804500619',	CONV('0', 2, 10) + 0),
('83b0af8327e8366',	'9758739787',	CONV('0', 2, 10) + 0),
('8480dd49a4a890a',	'9758739787',	CONV('0', 2, 10) + 0),
('84f23ce7e73e3ce',	'9758739787',	CONV('0', 2, 10) + 0),
('860d556f794bcf8',	'9758739787',	CONV('0', 2, 10) + 0),
('8624952a0bb1ea9',	'8804500619',	CONV('0', 2, 10) + 0),
('8812d91172f1934',	'9758739787',	CONV('0', 2, 10) + 0),
('88a754c2503310f',	'9758739787',	CONV('0', 2, 10) + 0),
('88ee725069cd931',	'9758739787',	CONV('0', 2, 10) + 0),
('8a969f6aeb5c966',	'9758739787',	CONV('0', 2, 10) + 0),
('8b4e222470baa91',	'8804500619',	CONV('0', 2, 10) + 0),
('8b804a7456ec709',	'9758739787',	CONV('0', 2, 10) + 0),
('8fbf4b40f9c1f99',	'9758739787',	CONV('0', 2, 10) + 0),
('93152182e6afa9f',	'9758739787',	CONV('0', 2, 10) + 0),
('944be74e361e34d',	'8804500619',	CONV('0', 2, 10) + 0),
('94e028bdad8ee55',	'8804500619',	CONV('1', 2, 10) + 0),
('968f22da21ecdfd',	'9758739787',	CONV('0', 2, 10) + 0),
('96c6a23b274f3c0',	'8804500619',	CONV('0', 2, 10) + 0),
('98aaeba6cac878a',	'9758739787',	CONV('0', 2, 10) + 0),
('99c3004c7b83459',	'9758739787',	CONV('0', 2, 10) + 0),
('a0ee3c9acbd4877',	'9758739787',	CONV('0', 2, 10) + 0),
('a1951616557e14c',	'8804500619',	CONV('0', 2, 10) + 0),
('a3814f7f8998dbb',	'9758739787',	CONV('0', 2, 10) + 0),
('a3fc16b25c5b442',	'8804500619',	CONV('0', 2, 10) + 0),
('a410947f1371d36',	'8804500619',	CONV('0', 2, 10) + 0),
('a57cf270f4f873e',	'8804500619',	CONV('0', 2, 10) + 0),
('a5e882d4fd5ec51',	'9758739787',	CONV('0', 2, 10) + 0),
('a7ce23fc221e985',	'9758739787',	CONV('0', 2, 10) + 0),
('a98ed9db9f5daf3',	'8804500619',	CONV('0', 2, 10) + 0),
('aa2a62d3101a438',	'9758739787',	CONV('0', 2, 10) + 0),
('aa7c4d2391100f2',	'9758739787',	CONV('0', 2, 10) + 0),
('ae51d459f0d2e7c',	'8804500619',	CONV('0', 2, 10) + 0),
('b244a38f09fc662',	'8804500619',	CONV('0', 2, 10) + 0),
('b36890d110e163e',	'9758739787',	CONV('0', 2, 10) + 0),
('b39631c7d3cb85f',	'9758739787',	CONV('0', 2, 10) + 0),
('b5107f201a5ccb5',	'8804500619',	CONV('0', 2, 10) + 0),
('b5ccb30dd3a98ac',	'8804500619',	CONV('0', 2, 10) + 0),
('b66f1dc824c4f4f',	'8804500619',	CONV('0', 2, 10) + 0),
('b8f14ea9012d10a',	'9758739787',	CONV('0', 2, 10) + 0),
('bb30094b09454c3',	'9758739787',	CONV('0', 2, 10) + 0),
('bb7383298c3c775',	'8804500619',	CONV('0', 2, 10) + 0),
('bbde8ba0525e022',	'9758739787',	CONV('0', 2, 10) + 0),
('bcd16ecfa9786e4',	'8804500619',	CONV('0', 2, 10) + 0),
('bce987e94062fef',	'9758739787',	CONV('0', 2, 10) + 0),
('bd54c6d5f81174c',	'9758739787',	CONV('0', 2, 10) + 0),
('bdc1dc94ac39e71',	'9758739787',	CONV('0', 2, 10) + 0),
('be98b07ee4577b7',	'8804500619',	CONV('0', 2, 10) + 0),
('bf3df8365eb1fb6',	'8804500619',	CONV('0', 2, 10) + 0),
('c098cb7db5032e7',	'8804500619',	CONV('0', 2, 10) + 0),
('c5578e0aa4d1100',	'9758739787',	CONV('0', 2, 10) + 0),
('c676cef099822de',	'9758739787',	CONV('0', 2, 10) + 0),
('c76c9864083cec5',	'8804500619',	CONV('0', 2, 10) + 0),
('c7cb622b0cf68fb',	'9758739787',	CONV('0', 2, 10) + 0),
('cac58d524f2bccf',	'9758739787',	CONV('0', 2, 10) + 0),
('cc27dfb8d6d3f03',	'9758739787',	CONV('0', 2, 10) + 0),
('ccfb397802e5342',	'9758739787',	CONV('0', 2, 10) + 0),
('d382470784098d8',	'8804500619',	CONV('0', 2, 10) + 0),
('d3986a36039658b',	'8804500619',	CONV('0', 2, 10) + 0),
('d4288ad2d9ddfd2',	'9758739787',	CONV('0', 2, 10) + 0),
('d55834c65b73e4d',	'8804500619',	CONV('0', 2, 10) + 0),
('d6ac912a4fd0bc7',	'9758739787',	CONV('0', 2, 10) + 0),
('d7c65a484ed65ae',	'9758739787',	CONV('0', 2, 10) + 0),
('d821308af0e0f33',	'8804500619',	CONV('0', 2, 10) + 0),
('dc9077601db16e4',	'9758739787',	CONV('0', 2, 10) + 0),
('dced19f19cd4544',	'8804500619',	CONV('0', 2, 10) + 0),
('dda444feba51338',	'9758739787',	CONV('0', 2, 10) + 0),
('dddf88e1f90a756',	'8804500619',	CONV('0', 2, 10) + 0),
('de3d8785badfed7',	'9758739787',	CONV('0', 2, 10) + 0),
('dfec3ea8e2a752c',	'8804500619',	CONV('0', 2, 10) + 0),
('dffa59f4a079ae2',	'9758739787',	CONV('0', 2, 10) + 0),
('e07163f479eed72',	'9758739787',	CONV('0', 2, 10) + 0),
('e5154f1ada3b108',	'8804500619',	CONV('0', 2, 10) + 0),
('e517484bac2d01c',	'8804500619',	CONV('0', 2, 10) + 0),
('e736ce72d30fc9c',	'9758739787',	CONV('0', 2, 10) + 0),
('e82f9028f287544',	'8804500619',	CONV('0', 2, 10) + 0),
('eb3dd124ab1ebf6',	'9758739787',	CONV('0', 2, 10) + 0),
('eda9e7bced4c9b5',	'8804500619',	CONV('0', 2, 10) + 0),
('ee9f2dd65ac6045',	'9758739787',	CONV('0', 2, 10) + 0),
('eeb34373057f75b',	'8804500619',	CONV('0', 2, 10) + 0),
('f188f2d77bd1c6c',	'9758739787',	CONV('0', 2, 10) + 0),
('f1912db31f59566',	'9758739787',	CONV('0', 2, 10) + 0),
('f1e0305155e5e39',	'8804500619',	CONV('0', 2, 10) + 0),
('f3b0be8764102d2',	'8804500619',	CONV('1', 2, 10) + 0),
('f5b498c06ed3159',	'9758739787',	CONV('0', 2, 10) + 0),
('f5ed129817da6dc',	'8804500619',	CONV('0', 2, 10) + 0),
('f6ce3df80b76a47',	'8804500619',	CONV('0', 2, 10) + 0),
('f93957d9f4482c9',	'8804500619',	CONV('0', 2, 10) + 0),
('f944ea660cbbe57',	'8804500619',	CONV('0', 2, 10) + 0),
('f97c2c8fa142d20',	'9758739787',	CONV('0', 2, 10) + 0),
('fc092d42c8e61a9',	'9758739787',	CONV('0', 2, 10) + 0),
('fd8e0710029bba4',	'9758739787',	CONV('0', 2, 10) + 0),
('fe9f630288920d7',	'8804500619',	CONV('0', 2, 10) + 0),
('ffadeb28269e513',	'8804500619',	CONV('0', 2, 10) + 0);

DROP TABLE IF EXISTS `Transaction`;
CREATE TABLE `Transaction` (
  `transactionID` varchar(20) COLLATE latin1_bin NOT NULL,
  `transactionSender` varchar(10) COLLATE latin1_bin NOT NULL,
  `transactionReceiver` varchar(10) COLLATE latin1_bin NOT NULL,
  `transactionAmount` double NOT NULL,
  `transactionTime` datetime NOT NULL,
  `transactionApproved` int(11) NOT NULL DEFAULT '0',
  `transactionToken` varchar(15) COLLATE latin1_bin DEFAULT NULL,
  `transactionDesc` varchar(255) COLLATE latin1_bin DEFAULT NULL,
  PRIMARY KEY (`transactionID`),
  KEY `transactionSender` (`transactionSender`),
  KEY `transactionReceiver` (`transactionReceiver`),
  KEY `transactionToken` (`transactionToken`),
  CONSTRAINT `Transaction_ibfk_3` FOREIGN KEY (`transactionToken`) REFERENCES `Token` (`tokenID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `Transaction_ibfk_4` FOREIGN KEY (`transactionSender`) REFERENCES `Account` (`accountNumber`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Transaction_ibfk_5` FOREIGN KEY (`transactionReceiver`) REFERENCES `Account` (`accountNumber`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

INSERT INTO `Transaction` (`transactionID`, `transactionSender`, `transactionReceiver`, `transactionAmount`, `transactionTime`, `transactionApproved`, `transactionToken`, `transactionDesc`) VALUES
('EqmKFCg_uydiIIEgjayA',	'PB93940281',	'PB33845364',	10001,	'2014-12-01 02:27:39',	0,	'7c3acef32945a5d',	'I just want to make Ray Charles happy, mate.'),
('FpRWmIBTuRODGONSaHFC',	'PB93940281',	'PB33845364',	1000,	'2014-12-01 03:01:57',	1,	'329670bf277eb50',	'Here\\\'s your pocket money Ray.'),
('PFLoVnfRlRoHsbrTkRLn',	'PB93940281',	'PB33845364',	180,	'2014-12-01 01:07:16',	1,	'f3b0be8764102d2',	'Giving Charles.'),
('aPQjxXbFctPfQFCObCgC',	'PB93940281',	'PB33845364',	10001,	'2014-12-01 03:01:57',	0,	'94e028bdad8ee55',	'');

DROP TABLE IF EXISTS `User`;
CREATE TABLE `User` (
  `userUsername` varchar(50) COLLATE latin1_bin NOT NULL,
  `userPassword` varchar(64) COLLATE latin1_bin NOT NULL,
  `userRole` int(11) NOT NULL,
  `userApproved` bit(1) NOT NULL DEFAULT b'0',
  `userSecurityQuestion` int(11) NOT NULL,
  `userSecurityAnswer` varchar(64) COLLATE latin1_bin DEFAULT NULL,
  PRIMARY KEY (`userUsername`),
  KEY `userRole` (`userRole`),
  KEY `userSecurityQuestion` (`userSecurityQuestion`),
  CONSTRAINT `User_ibfk_1` FOREIGN KEY (`userRole`) REFERENCES `Role` (`roleID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `User_ibfk_2` FOREIGN KEY (`userSecurityQuestion`) REFERENCES `SecurityQuestion` (`securityQuestionID`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

INSERT INTO `User` (`userUsername`, `userPassword`, `userRole`, `userApproved`, `userSecurityQuestion`, `userSecurityAnswer`) VALUES
('helen',	'd8a928b2043db77e340b523547bf16cb4aa483f0645fe0a290ed1f20aab76257',	1,	CONV('1', 2, 10) + 0,	1,	'94e8e30b623d1ab9a9aff11209284ac1d7ad87cca776c5394bb83de22f3d5ec5'),
('john',	'7e66205badf67cff28cb672dd83d4a85fbb3dcc9906167997094ecda5b3fce8e',	2,	CONV('1', 2, 10) + 0,	1,	'bd0b86fff870580859bca913f66994702c70876cafec005ad20f9ca6802d1bbe'),
('luke',	'743edcf941b967222a1b21a084ced8f7493e0a2701d3bef99eb1d5f5a0455f14',	3,	CONV('1', 2, 10) + 0,	1,	NULL),
('ray',	'a407b1ead1d985bad28330bdcba8fcc94bf8abfa58dfcfa3ca12279cf20dc0cf',	2,	CONV('1', 2, 10) + 0,	5,	'cc172a7c8269911b3ba1223fcdab399931b67ba12cd917fd0e5b3fb9c3707ad4');

-- 2014-12-01 02:04:48
