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
('PB19988878',	'6217117301',	0,	1000),
('PB32018682',	'1403734041',	0,	0),
('PB50180339',	'9422989251',	0,	11425),
('PB99536131',	'2747599883',	0,	1075);

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
('1403734041',	'hashy',	'1984-12-12',	'aleieldin.salem@gmail.com',	'1 Hashy Hash',	'hashy',	'0',	1),
('2747599883',	'Madison Elizabeth Frank',	'1982-12-25',	'aleieldin.salem@gmail.com',	'12 Massachusetts Avenue',	'mef',	NULL,	1),
('6217117301',	'Ezio Auditore',	'1985-10-12',	'aleieldin.salem@gmail.com',	'15 Via Tartaruga',	'ezio',	'0',	1),
('9422989251',	'John Doe',	'1974-05-12',	'aleieldin.salem@gmail.com',	'1 Main St.',	'john',	NULL,	1);

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
('E297024158',	'Dame Helen Mirren',	'1952-08-15',	'15 Tottehnham Court Rd.',	'hellen@piggy.de',	1,	2,	'helen'),
('E310679167',	'Jack Sparrow',	'1970-05-26',	'1 Black Pearl',	'aleieldin.salem@gmail.com',	0,	0,	'jacksparrow');

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
(1,	'admin'),
(2,	'customer');

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
('0070f76aa14c6aa',	'9422989251',	CONV('0', 2, 10) + 0),
('00c5f0e88066111',	'2747599883',	CONV('0', 2, 10) + 0),
('0106c8ae09a16aa',	'6217117301',	CONV('0', 2, 10) + 0),
('01c018ff3326d2e',	'9422989251',	CONV('0', 2, 10) + 0),
('022c23df1e253f0',	'6217117301',	CONV('0', 2, 10) + 0),
('0347d11c3b5ad37',	'2747599883',	CONV('0', 2, 10) + 0),
('03736334fe9c5dd',	'2747599883',	CONV('0', 2, 10) + 0),
('0384283cdfc3ae0',	'9422989251',	CONV('0', 2, 10) + 0),
('045d61e3eb337d9',	'9422989251',	CONV('0', 2, 10) + 0),
('049f724a83ce743',	'9422989251',	CONV('0', 2, 10) + 0),
('04bf4c5cd2a04cd',	'6217117301',	CONV('0', 2, 10) + 0),
('050343345b95589',	'9422989251',	CONV('0', 2, 10) + 0),
('06c27e1df993870',	'2747599883',	CONV('0', 2, 10) + 0),
('07fa710e97c757c',	'6217117301',	CONV('0', 2, 10) + 0),
('0895dbdc65b161e',	'9422989251',	CONV('0', 2, 10) + 0),
('097baa9f9a946fd',	'2747599883',	CONV('0', 2, 10) + 0),
('09cf2fcd6142635',	'9422989251',	CONV('0', 2, 10) + 0),
('0a43996924cca35',	'2747599883',	CONV('0', 2, 10) + 0),
('0a5836a0cb3ef72',	'6217117301',	CONV('0', 2, 10) + 0),
('0ef46a5feb4cc14',	'9422989251',	CONV('0', 2, 10) + 0),
('0f55b74c0d2d017',	'9422989251',	CONV('0', 2, 10) + 0),
('104f5ed60ef0c22',	'9422989251',	CONV('0', 2, 10) + 0),
('107a11b818eb06e',	'9422989251',	CONV('0', 2, 10) + 0),
('113bf02c0da0e1d',	'6217117301',	CONV('0', 2, 10) + 0),
('124260146fa4cdf',	'6217117301',	CONV('0', 2, 10) + 0),
('13686c13db8d81e',	'2747599883',	CONV('0', 2, 10) + 0),
('148f4654f1fc9a4',	'2747599883',	CONV('0', 2, 10) + 0),
('1494683c9cc68f3',	'9422989251',	CONV('0', 2, 10) + 0),
('149556b4421d52c',	'2747599883',	CONV('0', 2, 10) + 0),
('1699cfe5739e2d6',	'9422989251',	CONV('0', 2, 10) + 0),
('16d6abab59e2dba',	'6217117301',	CONV('0', 2, 10) + 0),
('16e62d3256c4907',	'2747599883',	CONV('0', 2, 10) + 0),
('178d5d0d3b3422a',	'6217117301',	CONV('0', 2, 10) + 0),
('18acae2d2a3954d',	'6217117301',	CONV('0', 2, 10) + 0),
('18c9bae46038fee',	'6217117301',	CONV('0', 2, 10) + 0),
('191d3e25f282b83',	'2747599883',	CONV('0', 2, 10) + 0),
('1b05e3f6c4217e4',	'6217117301',	CONV('0', 2, 10) + 0),
('1b2c0d0e01f2ad0',	'2747599883',	CONV('0', 2, 10) + 0),
('1c002da3aef5400',	'9422989251',	CONV('0', 2, 10) + 0),
('1db4f20f25737e6',	'6217117301',	CONV('0', 2, 10) + 0),
('1f056f3be2993f7',	'6217117301',	CONV('0', 2, 10) + 0),
('1f08a89c8747d0f',	'6217117301',	CONV('0', 2, 10) + 0),
('20220ccc2c850b0',	'6217117301',	CONV('0', 2, 10) + 0),
('20ca8c5c38d2aa0',	'2747599883',	CONV('0', 2, 10) + 0),
('2151d37da93bc07',	'2747599883',	CONV('0', 2, 10) + 0),
('2170023599fb3bc',	'6217117301',	CONV('0', 2, 10) + 0),
('221743931936156',	'6217117301',	CONV('0', 2, 10) + 0),
('22187c4f91120c9',	'9422989251',	CONV('0', 2, 10) + 0),
('24d6e16299b442e',	'6217117301',	CONV('0', 2, 10) + 0),
('24ddfe387eb8d89',	'2747599883',	CONV('0', 2, 10) + 0),
('254d87a002583ef',	'9422989251',	CONV('0', 2, 10) + 0),
('264fbc0285bd009',	'6217117301',	CONV('0', 2, 10) + 0),
('2708426b4ec3852',	'9422989251',	CONV('0', 2, 10) + 0),
('27737108c5a63f1',	'6217117301',	CONV('0', 2, 10) + 0),
('277b4eef1e56816',	'6217117301',	CONV('0', 2, 10) + 0),
('27d56acd49448a1',	'9422989251',	CONV('0', 2, 10) + 0),
('2a20b51c7dcaa4f',	'6217117301',	CONV('0', 2, 10) + 0),
('2b139dec6535917',	'9422989251',	CONV('0', 2, 10) + 0),
('2b596d8c1defcf3',	'6217117301',	CONV('0', 2, 10) + 0),
('2bbda9a7a0b563a',	'9422989251',	CONV('0', 2, 10) + 0),
('2d29f7fbb92b260',	'9422989251',	CONV('0', 2, 10) + 0),
('306fb14e12829ac',	'2747599883',	CONV('0', 2, 10) + 0),
('338bce23b1978d0',	'2747599883',	CONV('0', 2, 10) + 0),
('358387bdbbc86c8',	'2747599883',	CONV('0', 2, 10) + 0),
('36ae9fb21a98ff9',	'9422989251',	CONV('0', 2, 10) + 0),
('3808accd5a9943a',	'2747599883',	CONV('0', 2, 10) + 0),
('39ab71ded2a46d0',	'2747599883',	CONV('0', 2, 10) + 0),
('3a3d8d9dee1f4ed',	'9422989251',	CONV('0', 2, 10) + 0),
('3a6bf2f360559a2',	'9422989251',	CONV('0', 2, 10) + 0),
('3abe84250fad63d',	'9422989251',	CONV('1', 2, 10) + 0),
('3c040a4f1d93148',	'9422989251',	CONV('0', 2, 10) + 0),
('3ce0224df48c9e5',	'9422989251',	CONV('0', 2, 10) + 0),
('3fa904dd35f0d4a',	'6217117301',	CONV('0', 2, 10) + 0),
('3ff8972a0f4188e',	'6217117301',	CONV('0', 2, 10) + 0),
('4128debad0b7208',	'2747599883',	CONV('0', 2, 10) + 0),
('429b460beaf94f6',	'2747599883',	CONV('0', 2, 10) + 0),
('4368f308790020f',	'9422989251',	CONV('0', 2, 10) + 0),
('43e019475af8871',	'6217117301',	CONV('0', 2, 10) + 0),
('44045b541b6ea48',	'6217117301',	CONV('0', 2, 10) + 0),
('44ad75e121f36f4',	'2747599883',	CONV('0', 2, 10) + 0),
('4802b5d25631de6',	'2747599883',	CONV('0', 2, 10) + 0),
('48166adfe073ca8',	'2747599883',	CONV('0', 2, 10) + 0),
('4888e3153d5e15d',	'6217117301',	CONV('0', 2, 10) + 0),
('48eba873bbbd5f5',	'6217117301',	CONV('0', 2, 10) + 0),
('496a23170801299',	'6217117301',	CONV('0', 2, 10) + 0),
('49fbadbbd10adf1',	'6217117301',	CONV('0', 2, 10) + 0),
('4a7a6bfc981fb1d',	'6217117301',	CONV('0', 2, 10) + 0),
('4a7e9bd3f9755d9',	'6217117301',	CONV('0', 2, 10) + 0),
('4c30a2fed581516',	'2747599883',	CONV('0', 2, 10) + 0),
('4c444fbdb5a1e6f',	'9422989251',	CONV('0', 2, 10) + 0),
('4d4c5d761e8175a',	'6217117301',	CONV('0', 2, 10) + 0),
('4e12c49eeb788de',	'2747599883',	CONV('0', 2, 10) + 0),
('4ea99fcb6351228',	'2747599883',	CONV('0', 2, 10) + 0),
('4f0571dc2ec7a20',	'2747599883',	CONV('0', 2, 10) + 0),
('4f075f3c22d7b7e',	'9422989251',	CONV('0', 2, 10) + 0),
('505ed1acd9029ae',	'9422989251',	CONV('0', 2, 10) + 0),
('51463701e765d24',	'9422989251',	CONV('0', 2, 10) + 0),
('51b4db0792fd431',	'9422989251',	CONV('0', 2, 10) + 0),
('53adc26eadc54ff',	'9422989251',	CONV('0', 2, 10) + 0),
('540f33f01b54c06',	'6217117301',	CONV('0', 2, 10) + 0),
('548f934455b3ab3',	'2747599883',	CONV('0', 2, 10) + 0),
('568a892cd9723cb',	'6217117301',	CONV('0', 2, 10) + 0),
('56ab865b8f5ad44',	'2747599883',	CONV('0', 2, 10) + 0),
('56f317dbf23cdc1',	'6217117301',	CONV('0', 2, 10) + 0),
('58179c2b5304112',	'6217117301',	CONV('0', 2, 10) + 0),
('58c84cd7094bd07',	'6217117301',	CONV('0', 2, 10) + 0),
('597c017d0f1a7dc',	'6217117301',	CONV('0', 2, 10) + 0),
('597e391ff9a0e09',	'9422989251',	CONV('0', 2, 10) + 0),
('5af0ea361e062cb',	'9422989251',	CONV('1', 2, 10) + 0),
('5b971a9d52bac7a',	'6217117301',	CONV('0', 2, 10) + 0),
('5c12f24cbf389a5',	'6217117301',	CONV('0', 2, 10) + 0),
('5ef3d3116633fd2',	'6217117301',	CONV('0', 2, 10) + 0),
('5f72863d2ea2cb6',	'6217117301',	CONV('0', 2, 10) + 0),
('5fd2a40a440f203',	'2747599883',	CONV('0', 2, 10) + 0),
('5fd3791f9869954',	'9422989251',	CONV('0', 2, 10) + 0),
('604c4a6440b7fa8',	'6217117301',	CONV('0', 2, 10) + 0),
('6267531677be71c',	'2747599883',	CONV('0', 2, 10) + 0),
('62d449d27cf4f48',	'2747599883',	CONV('0', 2, 10) + 0),
('63662785bee661f',	'2747599883',	CONV('0', 2, 10) + 0),
('63e129b2cc90366',	'6217117301',	CONV('0', 2, 10) + 0),
('63e420df51e2e19',	'9422989251',	CONV('0', 2, 10) + 0),
('6529424de8b335f',	'9422989251',	CONV('1', 2, 10) + 0),
('662bb1f9c8aacc7',	'9422989251',	CONV('0', 2, 10) + 0),
('66c02143724dbbf',	'2747599883',	CONV('0', 2, 10) + 0),
('68538ca3f1150b0',	'2747599883',	CONV('0', 2, 10) + 0),
('6860b5f0fd41648',	'6217117301',	CONV('0', 2, 10) + 0),
('68dec372b0c2f83',	'9422989251',	CONV('0', 2, 10) + 0),
('68f6b0a2a21e06e',	'2747599883',	CONV('0', 2, 10) + 0),
('6986e6e95671b0d',	'9422989251',	CONV('0', 2, 10) + 0),
('6994e7c10aaac3c',	'6217117301',	CONV('0', 2, 10) + 0),
('6bc6b27cb455d40',	'9422989251',	CONV('0', 2, 10) + 0),
('6d36fa6f166e2ee',	'2747599883',	CONV('0', 2, 10) + 0),
('6f421261d35b64f',	'2747599883',	CONV('0', 2, 10) + 0),
('6f9f0b0f32842f6',	'9422989251',	CONV('0', 2, 10) + 0),
('7107f0f389dfbad',	'6217117301',	CONV('0', 2, 10) + 0),
('712446ce20e0763',	'6217117301',	CONV('0', 2, 10) + 0),
('712d063bdf7a33f',	'6217117301',	CONV('0', 2, 10) + 0),
('71742d98612fc76',	'6217117301',	CONV('0', 2, 10) + 0),
('735d28d5c47a64c',	'6217117301',	CONV('0', 2, 10) + 0),
('75b91cfbbd6bf8a',	'6217117301',	CONV('0', 2, 10) + 0),
('75ec4f00ccad68e',	'2747599883',	CONV('0', 2, 10) + 0),
('76abffda12d87dd',	'2747599883',	CONV('0', 2, 10) + 0),
('76ba15c9e5980cc',	'2747599883',	CONV('0', 2, 10) + 0),
('770b58a55dc70a4',	'9422989251',	CONV('0', 2, 10) + 0),
('7736a45db015344',	'2747599883',	CONV('0', 2, 10) + 0),
('77840deec8fe73b',	'6217117301',	CONV('0', 2, 10) + 0),
('78573d39448ce93',	'6217117301',	CONV('0', 2, 10) + 0),
('78ad3c8d8fcd5e6',	'9422989251',	CONV('0', 2, 10) + 0),
('78db838f6ef4284',	'9422989251',	CONV('0', 2, 10) + 0),
('790f2b0f0253eb7',	'9422989251',	CONV('0', 2, 10) + 0),
('7954141b56e6342',	'2747599883',	CONV('0', 2, 10) + 0),
('798630e12584c9a',	'2747599883',	CONV('0', 2, 10) + 0),
('7986d7c52ae9f81',	'2747599883',	CONV('0', 2, 10) + 0),
('79abec9edce7586',	'6217117301',	CONV('0', 2, 10) + 0),
('7b0d44e1680fc14',	'9422989251',	CONV('0', 2, 10) + 0),
('7b55adb6cf4ae38',	'2747599883',	CONV('0', 2, 10) + 0),
('7baaef4e33daef4',	'2747599883',	CONV('0', 2, 10) + 0),
('7bc18e05f4664b5',	'6217117301',	CONV('0', 2, 10) + 0),
('7bd558a35121b79',	'9422989251',	CONV('0', 2, 10) + 0),
('7cb2ba490b8187b',	'2747599883',	CONV('0', 2, 10) + 0),
('7ea186e5491e03f',	'9422989251',	CONV('0', 2, 10) + 0),
('7f832a303a6e78f',	'6217117301',	CONV('0', 2, 10) + 0),
('825f13854037b83',	'9422989251',	CONV('0', 2, 10) + 0),
('83a91ecff885e70',	'6217117301',	CONV('0', 2, 10) + 0),
('84c2069c6a1f326',	'9422989251',	CONV('0', 2, 10) + 0),
('84f50dcefc7450f',	'9422989251',	CONV('0', 2, 10) + 0),
('85da9879390011f',	'9422989251',	CONV('0', 2, 10) + 0),
('874e8ec6f6674c8',	'9422989251',	CONV('0', 2, 10) + 0),
('8a2ec6db6752c99',	'6217117301',	CONV('0', 2, 10) + 0),
('8a7bf35768d3f96',	'6217117301',	CONV('0', 2, 10) + 0),
('8bb694d3d11c58a',	'2747599883',	CONV('0', 2, 10) + 0),
('8ca32d7587351cd',	'9422989251',	CONV('0', 2, 10) + 0),
('8e5d15d6133cf74',	'6217117301',	CONV('0', 2, 10) + 0),
('90d1f0a7a6f0a05',	'9422989251',	CONV('0', 2, 10) + 0),
('92db5af46acaf67',	'2747599883',	CONV('0', 2, 10) + 0),
('93bbf42d1c950e7',	'9422989251',	CONV('0', 2, 10) + 0),
('93d8551dcc6f0d9',	'2747599883',	CONV('0', 2, 10) + 0),
('9456ab145c3b926',	'6217117301',	CONV('0', 2, 10) + 0),
('94b2898ca9301f4',	'2747599883',	CONV('0', 2, 10) + 0),
('94b4044793c6fa9',	'2747599883',	CONV('0', 2, 10) + 0),
('96fc1bb062bf4f4',	'9422989251',	CONV('0', 2, 10) + 0),
('9865f6777cf3e02',	'9422989251',	CONV('0', 2, 10) + 0),
('9b0a451ef01d142',	'6217117301',	CONV('0', 2, 10) + 0),
('9b7f14aaaf72050',	'2747599883',	CONV('0', 2, 10) + 0),
('9c3e050cf4febd0',	'9422989251',	CONV('1', 2, 10) + 0),
('9d7db73e99d210e',	'9422989251',	CONV('0', 2, 10) + 0),
('a11ed1d5d91e035',	'9422989251',	CONV('0', 2, 10) + 0),
('a1285892380b92f',	'9422989251',	CONV('0', 2, 10) + 0),
('a144c768f66e452',	'2747599883',	CONV('0', 2, 10) + 0),
('a1b622ebb3f049e',	'2747599883',	CONV('0', 2, 10) + 0),
('a2f9063c9ad2384',	'9422989251',	CONV('0', 2, 10) + 0),
('a30c5754dfa6ad3',	'2747599883',	CONV('0', 2, 10) + 0),
('a4a7ccced6b1625',	'2747599883',	CONV('0', 2, 10) + 0),
('a5a41950dd812a1',	'2747599883',	CONV('0', 2, 10) + 0),
('a7435284c8fa7a9',	'2747599883',	CONV('0', 2, 10) + 0),
('aa2727c1d5fcf66',	'9422989251',	CONV('0', 2, 10) + 0),
('ac089516871ae69',	'6217117301',	CONV('0', 2, 10) + 0),
('ac4cbb78e5dd0b4',	'2747599883',	CONV('0', 2, 10) + 0),
('ac761c64a24e0ba',	'2747599883',	CONV('0', 2, 10) + 0),
('acf2ff4e34a352d',	'6217117301',	CONV('0', 2, 10) + 0),
('adf5e878e0386d8',	'2747599883',	CONV('0', 2, 10) + 0),
('ae50a7f058d44f2',	'6217117301',	CONV('0', 2, 10) + 0),
('ae6b15443038ceb',	'9422989251',	CONV('0', 2, 10) + 0),
('b01050ab6c464eb',	'2747599883',	CONV('0', 2, 10) + 0),
('b12a153ebb45021',	'2747599883',	CONV('0', 2, 10) + 0),
('b215a87b7b9a84c',	'2747599883',	CONV('0', 2, 10) + 0),
('b2e30f9c2db1ed3',	'6217117301',	CONV('0', 2, 10) + 0),
('b36f4d6d0cb165c',	'6217117301',	CONV('0', 2, 10) + 0),
('b49244fc74b3533',	'2747599883',	CONV('0', 2, 10) + 0),
('b4aabb00f215956',	'2747599883',	CONV('0', 2, 10) + 0),
('b4f313899e9d33e',	'9422989251',	CONV('0', 2, 10) + 0),
('b66cfa2b056b00b',	'9422989251',	CONV('0', 2, 10) + 0),
('b840b7aa0913ce9',	'9422989251',	CONV('0', 2, 10) + 0),
('bb3475c90ded27d',	'2747599883',	CONV('0', 2, 10) + 0),
('bbb2945ce432262',	'9422989251',	CONV('0', 2, 10) + 0),
('bbda0a050d28360',	'9422989251',	CONV('0', 2, 10) + 0),
('bc593c47b5d8e95',	'6217117301',	CONV('0', 2, 10) + 0),
('bcff5d0d926398e',	'9422989251',	CONV('0', 2, 10) + 0),
('bd497b450f290b3',	'2747599883',	CONV('0', 2, 10) + 0),
('be3d4650beed6a1',	'2747599883',	CONV('0', 2, 10) + 0),
('be59e97fb5b62be',	'6217117301',	CONV('0', 2, 10) + 0),
('bf1c3454e6dbfc5',	'2747599883',	CONV('0', 2, 10) + 0),
('c0182f592379a7e',	'2747599883',	CONV('0', 2, 10) + 0),
('c10430f58ac31b0',	'9422989251',	CONV('0', 2, 10) + 0),
('c2f1ce99cf4f599',	'9422989251',	CONV('0', 2, 10) + 0),
('c35c31ed4986ed1',	'2747599883',	CONV('0', 2, 10) + 0),
('c37882e8bb5a101',	'9422989251',	CONV('0', 2, 10) + 0),
('c3949c71b647850',	'2747599883',	CONV('0', 2, 10) + 0),
('c4f358bb323fbc3',	'9422989251',	CONV('0', 2, 10) + 0),
('c50f48712952e07',	'2747599883',	CONV('0', 2, 10) + 0),
('c5fc965590518a5',	'9422989251',	CONV('0', 2, 10) + 0),
('c631a7cd20918e7',	'2747599883',	CONV('0', 2, 10) + 0),
('c660912a7124ffa',	'2747599883',	CONV('0', 2, 10) + 0),
('c6efa8d99c1eba2',	'6217117301',	CONV('0', 2, 10) + 0),
('c79ada67c099a55',	'9422989251',	CONV('0', 2, 10) + 0),
('ca059cb1341fcdb',	'6217117301',	CONV('0', 2, 10) + 0),
('ca1df0b960247f5',	'6217117301',	CONV('0', 2, 10) + 0),
('ca5c1ebebb7039f',	'6217117301',	CONV('0', 2, 10) + 0),
('cd05224a9df0030',	'9422989251',	CONV('1', 2, 10) + 0),
('ce16580bc9e71bc',	'2747599883',	CONV('0', 2, 10) + 0),
('ce2ed9aac7b3394',	'9422989251',	CONV('0', 2, 10) + 0),
('ce6e8c9d72b9049',	'9422989251',	CONV('0', 2, 10) + 0),
('cf5ae0d0ccc3d42',	'6217117301',	CONV('0', 2, 10) + 0),
('d2bbe0227b6eda7',	'2747599883',	CONV('0', 2, 10) + 0),
('d420a5ee06ff8c8',	'2747599883',	CONV('0', 2, 10) + 0),
('d44d562a613a04e',	'2747599883',	CONV('0', 2, 10) + 0),
('d566b7eef19ebb6',	'6217117301',	CONV('0', 2, 10) + 0),
('d5a49d35d4a2c70',	'6217117301',	CONV('0', 2, 10) + 0),
('d670abe9de2317d',	'6217117301',	CONV('0', 2, 10) + 0),
('d75324c5c9ee21e',	'6217117301',	CONV('0', 2, 10) + 0),
('d793d734da52bf4',	'2747599883',	CONV('0', 2, 10) + 0),
('d8a9572fb6edaf8',	'9422989251',	CONV('1', 2, 10) + 0),
('dbc8f4e7d029e08',	'6217117301',	CONV('0', 2, 10) + 0),
('dceeda840b68173',	'9422989251',	CONV('0', 2, 10) + 0),
('dcf38e5f0ed4ffb',	'6217117301',	CONV('0', 2, 10) + 0),
('ddad51323265b34',	'2747599883',	CONV('0', 2, 10) + 0),
('ddda40beb3c1be8',	'2747599883',	CONV('0', 2, 10) + 0),
('de15bb410f726f8',	'6217117301',	CONV('0', 2, 10) + 0),
('df73830b85751ae',	'2747599883',	CONV('0', 2, 10) + 0),
('dfd95e0d0a91759',	'9422989251',	CONV('0', 2, 10) + 0),
('dff80b581642bf0',	'6217117301',	CONV('0', 2, 10) + 0),
('e02001275b79c1d',	'2747599883',	CONV('0', 2, 10) + 0),
('e098476341fb4b8',	'6217117301',	CONV('0', 2, 10) + 0),
('e113e5f0bb5a0cc',	'9422989251',	CONV('0', 2, 10) + 0),
('e30bc4461aea238',	'2747599883',	CONV('0', 2, 10) + 0),
('e33dc5351a46f4f',	'2747599883',	CONV('0', 2, 10) + 0),
('e39270684ba16c9',	'9422989251',	CONV('0', 2, 10) + 0),
('e3d53b3a69ff207',	'6217117301',	CONV('0', 2, 10) + 0),
('e46a2e29fcfbac0',	'6217117301',	CONV('0', 2, 10) + 0),
('e574c85f6adcfaa',	'9422989251',	CONV('0', 2, 10) + 0),
('e9fbe01c0f2eccf',	'2747599883',	CONV('0', 2, 10) + 0),
('ea77ae3fa9a27c5',	'9422989251',	CONV('1', 2, 10) + 0),
('ead73fe9f11bdc5',	'2747599883',	CONV('0', 2, 10) + 0),
('eb736bd01f926a8',	'9422989251',	CONV('1', 2, 10) + 0),
('ec34664b319b076',	'9422989251',	CONV('0', 2, 10) + 0),
('ec3f625d56acb3f',	'9422989251',	CONV('0', 2, 10) + 0),
('ec6644e0cdb5f53',	'6217117301',	CONV('0', 2, 10) + 0),
('edb8c69276fe133',	'2747599883',	CONV('0', 2, 10) + 0),
('ee202e2fca61e10',	'6217117301',	CONV('0', 2, 10) + 0),
('ee831a20b8afabb',	'6217117301',	CONV('0', 2, 10) + 0),
('efc319ed693802f',	'2747599883',	CONV('0', 2, 10) + 0),
('efc936d9af2b6fd',	'6217117301',	CONV('0', 2, 10) + 0),
('f165941fe3ccb96',	'9422989251',	CONV('0', 2, 10) + 0),
('f2276dea4616cab',	'2747599883',	CONV('0', 2, 10) + 0),
('f248708e392065b',	'2747599883',	CONV('0', 2, 10) + 0),
('f35831f453afac3',	'6217117301',	CONV('0', 2, 10) + 0),
('f5e92e5c37ce82b',	'9422989251',	CONV('0', 2, 10) + 0),
('f67a36523cc4fa3',	'6217117301',	CONV('0', 2, 10) + 0),
('f6da4dd2d79611f',	'6217117301',	CONV('0', 2, 10) + 0),
('f724bdb1a17ed83',	'6217117301',	CONV('0', 2, 10) + 0),
('f7efa8b1a53d865',	'9422989251',	CONV('0', 2, 10) + 0),
('f8a190371b136ac',	'6217117301',	CONV('0', 2, 10) + 0),
('f9601a9fcd11006',	'2747599883',	CONV('0', 2, 10) + 0),
('fb4fb35cae8296b',	'6217117301',	CONV('0', 2, 10) + 0),
('fbd106310d6dbdc',	'9422989251',	CONV('0', 2, 10) + 0),
('fcfb2a21466e645',	'2747599883',	CONV('0', 2, 10) + 0),
('fe9c159c2ee03a8',	'2747599883',	CONV('0', 2, 10) + 0),
('ff9093269232d16',	'9422989251',	CONV('0', 2, 10) + 0),
('ffad84b6fc8b154',	'6217117301',	CONV('0', 2, 10) + 0),
('ffef3ac0a068101',	'9422989251',	CONV('0', 2, 10) + 0);

DROP TABLE IF EXISTS `Transaction`;
CREATE TABLE `Transaction` (
  `transactionID` varchar(20) COLLATE latin1_bin NOT NULL,
  `transactionSender` varchar(10) COLLATE latin1_bin NOT NULL,
  `transactionReceiver` varchar(10) COLLATE latin1_bin NOT NULL,
  `transactionAmount` double NOT NULL,
  `transactionTime` datetime NOT NULL,
  `transactionApproved` int(11) NOT NULL DEFAULT '0',
  `transactionToken` varchar(15) COLLATE latin1_bin DEFAULT NULL,
  PRIMARY KEY (`transactionID`),
  KEY `transactionSender` (`transactionSender`),
  KEY `transactionReceiver` (`transactionReceiver`),
  KEY `transactionToken` (`transactionToken`),
  CONSTRAINT `Transaction_ibfk_3` FOREIGN KEY (`transactionToken`) REFERENCES `Token` (`tokenID`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `Transaction_ibfk_4` FOREIGN KEY (`transactionSender`) REFERENCES `Account` (`accountNumber`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `Transaction_ibfk_5` FOREIGN KEY (`transactionReceiver`) REFERENCES `Account` (`accountNumber`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

INSERT INTO `Transaction` (`transactionID`, `transactionSender`, `transactionReceiver`, `transactionAmount`, `transactionTime`, `transactionApproved`, `transactionToken`) VALUES
('XwJTxfiRCRDuHqPsfcWj',	'PB50180339',	'PB99536131',	100,	'2014-11-28 03:18:23',	1,	'ea77ae3fa9a27c5'),
('kHDiYmEuAYALADhvuHgs',	'PB50180339',	'PB99536131',	75,	'2014-11-28 03:32:46',	1,	'9c3e050cf4febd0'),
('qMfHMtLIVlWhhsMTEeeE',	'PB50180339',	'PB99536131',	10001,	'2014-11-28 03:33:23',	0,	'5af0ea361e062cb');

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
('ezio',	'8bb28a0f20d5b3b4aee967621bba3a722e2ddd8cf224a83c0a5722fa1107d919',	2,	CONV('1', 2, 10) + 0,	5,	'84d5d0f994db379717afe99d1c707fca564821e757087093b0e390d3b710af24'),
('hashy',	'd8a928b2043db77e340b523547bf16cb4aa483f0645fe0a290ed1f20aab76257',	2,	CONV('0', 2, 10) + 0,	1,	NULL),
('helen',	'd8a928b2043db77e340b523547bf16cb4aa483f0645fe0a290ed1f20aab76257',	1,	CONV('0', 2, 10) + 0,	1,	NULL),
('jacksparrow',	'd8a928b2043db77e340b523547bf16cb4aa483f0645fe0a290ed1f20aab76257',	1,	CONV('0', 2, 10) + 0,	6,	'e84b5614f9012ef0a276a5dc8919208d6dc44f8cc17e1fd1e87e5afc4ecb1bb6'),
('john',	'd8a928b2043db77e340b523547bf16cb4aa483f0645fe0a290ed1f20aab76257',	2,	CONV('1', 2, 10) + 0,	1,	'd8a928b2043db77e340b523547bf16cb4aa483f0645fe0a290ed1f20aab76257'),
('luke',	'743edcf941b967222a1b21a084ced8f7493e0a2701d3bef99eb1d5f5a0455f14',	1,	CONV('1', 2, 10) + 0,	1,	NULL),
('mef',	'd8a928b2043db77e340b523547bf16cb4aa483f0645fe0a290ed1f20aab76257',	2,	CONV('1', 2, 10) + 0,	1,	NULL);

-- 2014-11-30 03:47:22
