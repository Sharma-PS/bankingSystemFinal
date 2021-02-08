-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 08, 2021 at 08:27 AM
-- Server version: 5.7.31
-- PHP Version: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `core_bank`
--

DELIMITER $$
--
-- Procedures
--
DROP PROCEDURE IF EXISTS `getAge`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `getAge` (IN `nicd` VARCHAR(12))  NO SQL
SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(dob)), '%Y')+0 AS age FROM customer WHERE NIC = nicd$$

DROP PROCEDURE IF EXISTS `getSavingPlanID`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `getSavingPlanID` (IN `nicd` VARCHAR(12), OUT `splanID` VARCHAR(50))  NO SQL
BEGIN
  DECLARE c_age INTEGER;
  SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(dob)), '%Y')+0 AS age INTO c_age FROM customer WHERE NIC = nicd;
  if(c_age > 60) THEN
  	SET splanID = "Senior";
  ELSEIF(c_age > 18) THEN
  	SET splanID = "Adult";
  ELSEIF(c_age > 12) THEN
  	SET splanID = "Teen";
  ELSE
  	SET splanID = "Children";
  END IF;
 END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

DROP TABLE IF EXISTS `account`;
CREATE TABLE IF NOT EXISTS `account` (
  `accID` int(11) NOT NULL AUTO_INCREMENT,
  `NIC` varchar(12) NOT NULL,
  `branchCode` varchar(50) NOT NULL,
  `balance` decimal(30,2) NOT NULL,
  `createdDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `type` enum('current','saving') NOT NULL DEFAULT 'current',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `closed_date` date DEFAULT NULL,
  PRIMARY KEY (`accID`),
  KEY `has` (`NIC`),
  KEY `Opened branch code` (`branchCode`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`accID`, `NIC`, `branchCode`, `balance`, `createdDate`, `updatedDate`, `type`, `status`, `closed_date`) VALUES
(7, '990022984v', 'b001', '90500.00', '2021-02-01 15:02:19', '2021-02-08 12:49:12', 'saving', 1, NULL),
(8, '980021422v', 'b002', '145000.00', '2021-02-01 17:48:51', '2021-02-08 00:54:50', 'current', 0, '2021-02-04');

-- --------------------------------------------------------

--
-- Stand-in structure for view `accountdetails`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `accountdetails`;
CREATE TABLE IF NOT EXISTS `accountdetails` (
`accID` int(11)
,`NIC` varchar(12)
,`balance` decimal(30,2)
,`createdDate` datetime
,`type` enum('current','saving')
,`status` tinyint(1)
,`closed_date` date
,`branchName` varchar(50)
,`no_of_withdrawals` int(2)
);

-- --------------------------------------------------------

--
-- Table structure for table `branch`
--

DROP TABLE IF EXISTS `branch`;
CREATE TABLE IF NOT EXISTS `branch` (
  `branchCode` varchar(50) NOT NULL,
  `branchName` varchar(50) NOT NULL,
  `Address` text NOT NULL,
  `type` enum('H_O','br') NOT NULL DEFAULT 'br',
  `contactNo` int(10) NOT NULL,
  `openedDate` date NOT NULL,
  `updatedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  PRIMARY KEY (`branchCode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `branch`
--

INSERT INTO `branch` (`branchCode`, `branchName`, `Address`, `type`, `contactNo`, `openedDate`, `updatedDate`, `status`) VALUES
('b001', 'Jaffna', 'Jaffna town', 'H_O', 1234567890, '2021-01-14', '2021-02-08 13:38:19', '1'),
('b002', 'Colombo', 'Colombo', 'br', 987654321, '2021-01-01', '2021-02-08 13:38:21', '0'),
('b020', 'Kandy', 'Pera', 'br', 771234567, '2021-02-07', '2021-02-08 13:11:35', '1');

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `NIC` varchar(12) NOT NULL,
  `name` varchar(50) NOT NULL,
  `eMail` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `mobileNo` int(10) NOT NULL,
  `tempAddress` text NOT NULL,
  `permanantAddress` text NOT NULL,
  `job` text,
  `officialAddress` text,
  `DOB` date NOT NULL,
  `dp` varchar(500) DEFAULT NULL,
  `openedBy` int(11) NOT NULL,
  `openedBranch` varchar(50) NOT NULL,
  `joinedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updatedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `leftDate` datetime DEFAULT NULL,
  PRIMARY KEY (`NIC`),
  UNIQUE KEY `eMail` (`eMail`),
  KEY `Opened Branch` (`openedBranch`),
  KEY `openedBy` (`openedBy`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`NIC`, `name`, `eMail`, `password`, `mobileNo`, `tempAddress`, `permanantAddress`, `job`, `officialAddress`, `DOB`, `dp`, `openedBy`, `openedBranch`, `joinedDate`, `updatedDate`, `leftDate`) VALUES
('980021422v', 'Thuva', 'Thuva@gmail.com', 'b75ec0d4f5234f39b4a40f3c83484faf', 432167898, 'No Sample address for \r\nthuvaragan', 'No Sample address for \r\nthuvaragan', 'Senior software engineer', 'UoM', '1998-04-02', NULL, 5, 'b002', '2021-01-15 09:27:17', '2021-01-15 09:28:10', NULL),
('981234567v', 'Sathu', 'sathu@gmail.com', '44784fdfab98e13649ac26593ea84455', 1234567890, 'Madduvil', 'Madduvil', 'Senior Software Engineer', 'Chavakacheri', '2000-02-03', NULL, 4, 'b002', '2021-02-06 10:32:39', '2021-02-06 10:32:39', NULL),
('990022984v', 'Sharma', 'sarves021999@gmail.com', 'b8b507db0b52442269c5c0bd23cf4189', 778079610, 'No, sample address location', 'No, sample address location', 'Student', 'UoM', '1999-01-02', NULL, 4, 'b001', '2021-01-15 09:27:17', '2021-01-15 09:27:17', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `deposit`
--

DROP TABLE IF EXISTS `deposit`;
CREATE TABLE IF NOT EXISTS `deposit` (
  `deposit_id` int(11) NOT NULL AUTO_INCREMENT,
  `accID` int(11) NOT NULL,
  `amount` decimal(30,2) NOT NULL,
  `Description` text,
  `branchCode` varchar(50) NOT NULL,
  `deposit_by` int(11) DEFAULT NULL,
  `time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`deposit_id`),
  KEY `depositBrach` (`branchCode`),
  KEY `deposit Account` (`accID`),
  KEY `depositBy` (`deposit_by`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `deposit`
--

INSERT INTO `deposit` (`deposit_id`, `accID`, `amount`, `Description`, `branchCode`, `deposit_by`, `time`) VALUES
(8, 7, '1800.00', NULL, 'b001', 5, '2021-02-01 15:20:51'),
(9, 7, '800.00', NULL, 'b002', 5, '2021-02-08 00:35:21'),
(10, 8, '4000.00', NULL, 'b001', 1, '2021-02-08 00:39:46'),
(11, 7, '1600.00', 'Fund Rise', 'b001', 1, '2021-02-08 00:40:43');

--
-- Triggers `deposit`
--
DROP TRIGGER IF EXISTS `Deposit_to_account`;
DELIMITER $$
CREATE TRIGGER `Deposit_to_account` AFTER INSERT ON `deposit` FOR EACH ROW UPDATE account
SET balance = (balance + NEW.amount)
WHERE accID = NEW.accID
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

DROP TABLE IF EXISTS `employee`;
CREATE TABLE IF NOT EXISTS `employee` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `NIC` varchar(12) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `branchCode` varchar(7) NOT NULL,
  `designation` enum('staff','manager','head_manager') NOT NULL,
  `mobileNo` int(10) NOT NULL,
  `Address` text NOT NULL,
  `DOB` date NOT NULL,
  `dp` varchar(500) DEFAULT NULL,
  `JoinedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdatedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `leftDate` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `uniqueAttribur` (`NIC`,`email`) USING BTREE,
  KEY `WorkingBranch` (`branchCode`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`ID`, `name`, `NIC`, `email`, `password`, `branchCode`, `designation`, `mobileNo`, `Address`, `DOB`, `dp`, `JoinedDate`, `UpdatedDate`, `leftDate`) VALUES
(1, 'HM_xxx', '123456789v', 'headofficemanager@gmail.com', '950a2c1b68ef6dd154800e089f20282a', 'b001', 'head_manager', 1234567890, 'Sample address', '2019-08-08', NULL, '2021-01-15 08:58:48', '2021-01-17 10:08:07', NULL),
(2, 'Man_yyy', '987654321v', 'manager@gmail.com', '1d0258c2440a8d19e716292b231e3190', 'b002', 'manager', 1234567890, 'Sample 2 address', '2020-08-03', NULL, '2021-01-15 09:05:36', '2021-01-15 09:05:36', NULL),
(4, 'S_jaffna', '543216789v', 'staffjaffna@gmail.com', '03a9b752cdc8c8d5f1fb3cac18bf7131', 'b001', 'staff', 1234567890, 'jaffna', '2020-06-15', NULL, '2021-01-15 09:13:24', '2021-02-07 00:52:26', NULL),
(5, 'S_colombo', '990022132v', 'staffcolombo@gmail.com', '8be46aff6e2601f09204dd35268c4114', 'b002', 'staff', 987654321, 'sample', '2020-08-17', NULL, '2021-01-15 09:13:24', '2021-01-15 09:13:24', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `fd`
--

DROP TABLE IF EXISTS `fd`;
CREATE TABLE IF NOT EXISTS `fd` (
  `FD_ID` int(11) NOT NULL AUTO_INCREMENT,
  `savingAcc_id` int(11) NOT NULL,
  `FD_plan_id` varchar(30) NOT NULL,
  `amount` decimal(30,2) NOT NULL,
  `startDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `maturityDate` datetime NOT NULL,
  PRIMARY KEY (`FD_ID`),
  KEY `savings` (`savingAcc_id`),
  KEY `selected_plan` (`FD_plan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `fd_plan`
--

DROP TABLE IF EXISTS `fd_plan`;
CREATE TABLE IF NOT EXISTS `fd_plan` (
  `fd_plan_id` varchar(30) NOT NULL,
  `description` varchar(1000) NOT NULL,
  `rate` smallint(3) NOT NULL,
  `minimumAmount` decimal(30,2) NOT NULL DEFAULT '0.00',
  `maximumAmount` decimal(30,2) NOT NULL DEFAULT '500000.00',
  `duration_in_months` smallint(5) NOT NULL,
  PRIMARY KEY (`fd_plan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `fd_plan`
--

INSERT INTO `fd_plan` (`fd_plan_id`, `description`, `rate`, `minimumAmount`, `maximumAmount`, `duration_in_months`) VALUES
('3 year', '36 months period with highest rate', 15, '0.00', '500000.00', 36),
('half year', 'period 6 months ', 13, '0.00', '500000.00', 6),
('one year', '12 months period', 14, '0.00', '500000.00', 12);

-- --------------------------------------------------------

--
-- Table structure for table `saving_account`
--

DROP TABLE IF EXISTS `saving_account`;
CREATE TABLE IF NOT EXISTS `saving_account` (
  `accID` int(11) NOT NULL,
  `s_plan_id` varchar(20) NOT NULL,
  `no_of_withdrawals` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`accID`),
  KEY `planID` (`s_plan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `saving_account`
--

INSERT INTO `saving_account` (`accID`, `s_plan_id`, `no_of_withdrawals`) VALUES
(7, 'Adult', 2);

-- --------------------------------------------------------

--
-- Table structure for table `saving_interest_plan`
--

DROP TABLE IF EXISTS `saving_interest_plan`;
CREATE TABLE IF NOT EXISTS `saving_interest_plan` (
  `s_plan_id` varchar(20) NOT NULL,
  `s_plan_des` varchar(50) NOT NULL,
  `minimum_amount` decimal(30,2) NOT NULL,
  `rate` int(3) NOT NULL,
  PRIMARY KEY (`s_plan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `saving_interest_plan`
--

INSERT INTO `saving_interest_plan` (`s_plan_id`, `s_plan_des`, `minimum_amount`, `rate`) VALUES
('Adult', 'Saving Account for adult', '1000.00', 10),
('Children ', 'Children account deposit', '0.00', 12),
('Senior', 'Saving account for 60+ people', '1000.00', 13),
('Teen', 'Saving Account for Teenage', '500.00', 11);

-- --------------------------------------------------------

--
-- Table structure for table `transaction`
--

DROP TABLE IF EXISTS `transaction`;
CREATE TABLE IF NOT EXISTS `transaction` (
  `transaction_id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) NOT NULL,
  `recipient_id` int(11) NOT NULL,
  `amount` decimal(30,2) NOT NULL,
  `description` text,
  `time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`transaction_id`),
  KEY `sends` (`sender_id`),
  KEY `receives` (`recipient_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`transaction_id`, `sender_id`, `recipient_id`, `amount`, `description`, `time`) VALUES
(1, 8, 7, '4000.00', 'Testing', '2021-02-04 08:45:57');

--
-- Triggers `transaction`
--
DROP TRIGGER IF EXISTS `Atomic_Transfer`;
DELIMITER $$
CREATE TRIGGER `Atomic_Transfer` AFTER INSERT ON `transaction` FOR EACH ROW BEGIN
UPDATE account
SET balance = (balance - NEW.amount)
WHERE accID = NEW.sender_id;
UPDATE account
SET balance = (balance + NEW.amount)
WHERE accID = NEW.recipient_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `withdrawal`
--

DROP TABLE IF EXISTS `withdrawal`;
CREATE TABLE IF NOT EXISTS `withdrawal` (
  `withdrawal_id` int(11) NOT NULL AUTO_INCREMENT,
  `accID` int(11) NOT NULL,
  `amount` decimal(30,2) NOT NULL,
  `Description` text,
  `branchCode` varchar(50) NOT NULL,
  `withdrew_by` int(11) NOT NULL,
  `time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`withdrawal_id`),
  KEY `take Money` (`accID`),
  KEY `location` (`branchCode`),
  KEY `withdrewBy` (`withdrew_by`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `withdrawal`
--

INSERT INTO `withdrawal` (`withdrawal_id`, `accID`, `amount`, `Description`, `branchCode`, `withdrew_by`, `time`) VALUES
(1, 7, '4500.00', NULL, 'b001', 1, '2021-02-08 12:47:59'),
(2, 7, '3000.00', NULL, 'b001', 1, '2021-02-08 12:48:41'),
(3, 7, '2000.00', NULL, 'b001', 1, '2021-02-08 12:49:12');

--
-- Triggers `withdrawal`
--
DROP TRIGGER IF EXISTS `Withdraw_from_account`;
DELIMITER $$
CREATE TRIGGER `Withdraw_from_account` AFTER INSERT ON `withdrawal` FOR EACH ROW UPDATE account
SET balance = (balance - NEW.amount)
WHERE accID = NEW.accID
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure for view `accountdetails`
--
DROP TABLE IF EXISTS `accountdetails`;

DROP VIEW IF EXISTS `accountdetails`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `accountdetails`  AS  select `a`.`accID` AS `accID`,`a`.`NIC` AS `NIC`,`a`.`balance` AS `balance`,`a`.`createdDate` AS `createdDate`,`a`.`type` AS `type`,`a`.`status` AS `status`,`a`.`closed_date` AS `closed_date`,`b`.`branchName` AS `branchName`,`s`.`no_of_withdrawals` AS `no_of_withdrawals` from ((`account` `a` left join `saving_account` `s` on((`a`.`accID` = `s`.`accID`))) join `branch` `b` on((`a`.`branchCode` = `b`.`branchCode`))) ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account`
--
ALTER TABLE `account`
  ADD CONSTRAINT `Opened branch code` FOREIGN KEY (`branchCode`) REFERENCES `branch` (`branchCode`),
  ADD CONSTRAINT `has` FOREIGN KEY (`NIC`) REFERENCES `customer` (`NIC`);

--
-- Constraints for table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `Opened Branch` FOREIGN KEY (`openedBranch`) REFERENCES `branch` (`branchCode`),
  ADD CONSTRAINT `customer_ibfk_1` FOREIGN KEY (`openedBy`) REFERENCES `employee` (`ID`);

--
-- Constraints for table `deposit`
--
ALTER TABLE `deposit`
  ADD CONSTRAINT `deposit Account` FOREIGN KEY (`accID`) REFERENCES `account` (`accID`),
  ADD CONSTRAINT `depositBrach` FOREIGN KEY (`branchCode`) REFERENCES `branch` (`branchCode`),
  ADD CONSTRAINT `depositBy` FOREIGN KEY (`deposit_by`) REFERENCES `employee` (`ID`);

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `WorkingBranch` FOREIGN KEY (`branchCode`) REFERENCES `branch` (`branchCode`);

--
-- Constraints for table `fd`
--
ALTER TABLE `fd`
  ADD CONSTRAINT `savings` FOREIGN KEY (`savingAcc_id`) REFERENCES `account` (`accID`),
  ADD CONSTRAINT `selected_plan` FOREIGN KEY (`FD_plan_id`) REFERENCES `fd_plan` (`fd_plan_id`);

--
-- Constraints for table `saving_account`
--
ALTER TABLE `saving_account`
  ADD CONSTRAINT `planID` FOREIGN KEY (`s_plan_id`) REFERENCES `saving_interest_plan` (`s_plan_id`),
  ADD CONSTRAINT `savingPlan` FOREIGN KEY (`accID`) REFERENCES `account` (`accID`);

--
-- Constraints for table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `receives` FOREIGN KEY (`recipient_id`) REFERENCES `account` (`accID`),
  ADD CONSTRAINT `sends` FOREIGN KEY (`sender_id`) REFERENCES `account` (`accID`);

--
-- Constraints for table `withdrawal`
--
ALTER TABLE `withdrawal`
  ADD CONSTRAINT `location` FOREIGN KEY (`branchCode`) REFERENCES `branch` (`branchCode`),
  ADD CONSTRAINT `take Money` FOREIGN KEY (`accID`) REFERENCES `account` (`accID`),
  ADD CONSTRAINT `withdrewBy` FOREIGN KEY (`withdrew_by`) REFERENCES `employee` (`ID`);

DELIMITER $$
--
-- Events
--
DROP EVENT `withdrawal_set_0`$$
CREATE DEFINER=`root`@`localhost` EVENT `withdrawal_set_0` ON SCHEDULE EVERY 1 MONTH STARTS '2021-02-04 00:00:00' ON COMPLETION PRESERVE ENABLE DO UPDATE `saving_account` SET `no_of_withdrawals` =0$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
