-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Feb 19, 2021 at 04:17 AM
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
DROP PROCEDURE IF EXISTS `DepositForFDInterest`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `DepositForFDInterest` ()  NO SQL
BEGIN
	DECLARE finished INTEGER DEFAULT 0;
    DECLARE a_id INTEGER;
    DECLARE blnc DECIMAL;
    DECLARE rte INTEGER;
	DECLARE c_date DATETIME;
    DECLARE resTDC BOOLEAN;
	-- declare cursor for employee email
	DEClARE curCDate 
		CURSOR FOR 
			SELECT `savingAcc_id`,`amount`,`startDate`,`rate` FROM `fd_active_details`;

	-- declare NOT FOUND handler
	DECLARE CONTINUE HANDLER 
        FOR NOT FOUND SET finished = 1;

	OPEN curCDate;

	getCDate: LOOP
		FETCH curCDate INTO a_id, blnc, c_date, rte;
		IF finished = 1 THEN 
			LEAVE getCDate;
		END IF;
		-- update interest
        CALL `thirtyDayCheck`(c_date, @p1); SELECT @p1 INTO resTDC;
       	IF resTDC = 1 THEN
        	INSERT INTO `deposit` (`deposit_id`, `accID`, `amount`, `Description`, `branchCode`, `deposit_by`, `time`) VALUES (NULL, a_id, ((blnc * rte)/100), 'Fixed Deposit', NULL, NULL, CURRENT_TIMESTAMP);
        END IF;      
	END LOOP getCDate;
	CLOSE curCDate;

END$$

DROP PROCEDURE IF EXISTS `DepositTosavingAccountInterest`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `DepositTosavingAccountInterest` ()  NO SQL
BEGIN
	DECLARE finished INTEGER DEFAULT 0;
    DECLARE a_id INTEGER;
    DECLARE blnc DECIMAL;
    DECLARE rte DECIMAL;
	DECLARE c_date DATETIME;
    DECLARE resTDC BOOLEAN;
	-- declare cursor for employee email
	DEClARE curCDate 
		CURSOR FOR 
			SELECT `accID`,`balance`,`createdDate`,`rate` FROM `savings_acc_details`;

	-- declare NOT FOUND handler
	DECLARE CONTINUE HANDLER 
        FOR NOT FOUND SET finished = 1;

	OPEN curCDate;

	getCDate: LOOP
		FETCH curCDate INTO a_id, blnc, c_date, rte;
		IF finished = 1 THEN 
			LEAVE getCDate;
		END IF;
		-- update interest
        CALL `thirtyDayCheck`(c_date, @p1); SELECT @p1 INTO resTDC;
       	IF resTDC = 1 THEN
        	INSERT INTO `deposit` (`deposit_id`, `accID`, `amount`, `Description`, `branchCode`, `deposit_by`, `time`) VALUES (NULL, a_id, ((blnc * rte)/100), 'Saving Interest', NULL, NULL, CURRENT_TIMESTAMP);
        END IF;      
	END LOOP getCDate;
	CLOSE curCDate;

END$$

DROP PROCEDURE IF EXISTS `getAge`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `getAge` (IN `nicd` VARCHAR(12))  NO SQL
SELECT DATE_FORMAT(FROM_DAYS(TO_DAYS(NOW())-TO_DAYS(dob)), '%Y')+0 AS age FROM customer WHERE NIC = nicd$$

DROP PROCEDURE IF EXISTS `getDays`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `getDays` (IN `inDate` DATETIME, OUT `outDays` INT)  NO SQL
SELECT DATEDIFF(NOW(), inDate) AS dateDiffInDay INTO outDays$$

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

DROP PROCEDURE IF EXISTS `thirtyDayCheck`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `thirtyDayCheck` (IN `inDate` DATETIME, OUT `dividedByThiry` BOOLEAN)  NO SQL
BEGIN
DECLARE rem INTEGER;
CALL `getDays`(inDate, @p1);
SELECT (@p1 % 30) INTO rem;
IF(rem = 0) THEN
	SET dividedByThiry = TRUE;
ELSE
	SET dividedByThiry = FALSE;
END IF;
END$$

DROP PROCEDURE IF EXISTS `updateSavingAccPlan`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `updateSavingAccPlan` ()  NO SQL
BEGIN
	DECLARE finished INTEGER DEFAULT 0;
    DECLARE a_id INTEGER;
    DECLARE nicID VARCHAR(12);
    DECLARE s_id VARCHAR(20);
    DECLARE resTDC VARCHAR(20);
	-- declare cursor for employee email
	DEClARE curCDate 
		CURSOR FOR 
			SELECT `accID`,`NIC`,`s_plan_id` FROM `savings_acc_details`;

	-- declare NOT FOUND handler
	DECLARE CONTINUE HANDLER 
        FOR NOT FOUND SET finished = 1;

	OPEN curCDate;

	getCDate: LOOP
		FETCH curCDate INTO a_id, nicID, s_id;
		IF finished = 1 THEN 
			LEAVE getCDate;
		END IF;
		-- update interest
        CALL `getSavingPlanID`(nicID, @p1);SELECT @p1 INTO resTDC;
       	IF resTDC != s_id THEN
        	UPDATE `saving_account` SET `s_plan_id` = resTDC WHERE `saving_account`.`accID` = a_id;
        END IF;      
	END LOOP getCDate;
	CLOSE curCDate;

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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`accID`, `NIC`, `branchCode`, `balance`, `createdDate`, `updatedDate`, `type`, `status`, `closed_date`) VALUES
(7, '990022984v', 'b001', '427876.70', '2021-01-19 15:02:19', '2021-02-18 17:01:54', 'saving', 1, NULL),
(8, '980021422v', 'b002', '144440.00', '2021-02-01 17:48:51', '2021-02-18 12:58:47', 'current', 1, NULL),
(9, '981234567v', 'b002', '244406.40', '2021-02-18 13:30:07', '2021-02-18 15:58:34', 'saving', 1, NULL);

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
-- Table structure for table `approvedloan`
--

DROP TABLE IF EXISTS `approvedloan`;
CREATE TABLE IF NOT EXISTS `approvedloan` (
  `loan_id` int(11) NOT NULL,
  `approvedBy` int(11) NOT NULL,
  `approvedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nextPaymentDate` datetime NOT NULL,
  `countPayments` int(11) NOT NULL,
  `arrear` decimal(30,2) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`loan_id`),
  KEY `approvedBy` (`approvedBy`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `approvedloan`
--

INSERT INTO `approvedloan` (`loan_id`, `approvedBy`, `approvedDate`, `nextPaymentDate`, `countPayments`, `arrear`, `status`) VALUES
(1, 6, '2021-02-18 20:14:34', '2021-03-18 20:14:32', 0, '0.00', 1);

--
-- Triggers `approvedloan`
--
DROP TRIGGER IF EXISTS `giveApprovalOfLoan`;
DELIMITER $$
CREATE TRIGGER `giveApprovalOfLoan` AFTER INSERT ON `approvedloan` FOR EACH ROW UPDATE `requestedloan` SET `approved` = '1' WHERE `requestedloan`.`loan_id` = NEW.loan_id
$$
DELIMITER ;

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
('b002', 'Colombo', 'Colombo', 'br', 987654321, '2021-01-01', '2021-02-18 16:40:08', '0'),
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
  `branchCode` varchar(50) DEFAULT NULL,
  `deposit_by` int(11) DEFAULT NULL,
  `time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`deposit_id`),
  KEY `depositBrach` (`branchCode`),
  KEY `deposit Account` (`accID`),
  KEY `depositBy` (`deposit_by`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `deposit`
--

INSERT INTO `deposit` (`deposit_id`, `accID`, `amount`, `Description`, `branchCode`, `deposit_by`, `time`) VALUES
(8, 7, '1800.00', NULL, 'b001', 5, '2021-02-01 15:20:51'),
(9, 7, '800.00', NULL, 'b002', 5, '2021-02-08 00:35:21'),
(10, 8, '4000.00', NULL, 'b001', 1, '2021-02-08 00:39:46'),
(11, 7, '1600.00', 'Fund Rise', 'b001', 1, '2021-02-08 00:40:43'),
(16, 7, '600.00', 'By Transferring', NULL, NULL, '2021-02-16 11:59:31'),
(17, 7, '600.00', 'By Transferring', NULL, NULL, '2021-02-18 10:08:20'),
(18, 7, '500.00', 'By Transferring', NULL, NULL, '2021-02-18 10:15:10'),
(25, 9, '17162.64', 'Saving Interest', NULL, NULL, '2021-02-18 14:59:41'),
(26, 7, '11579.70', 'Saving Interest', NULL, NULL, '2021-02-18 15:06:00'),
(27, 9, '19222.08', 'Saving Interest', NULL, NULL, '2021-02-18 15:06:00'),
(34, 7, '60000.00', 'Fixed Deposit', NULL, NULL, '2021-02-18 15:57:34'),
(35, 9, '13000.00', 'Fixed Deposit', NULL, NULL, '2021-02-18 15:57:34'),
(38, 7, '500.00', NULL, 'b001', 1, '2021-02-18 17:01:54');

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`ID`, `name`, `NIC`, `email`, `password`, `branchCode`, `designation`, `mobileNo`, `Address`, `DOB`, `dp`, `JoinedDate`, `UpdatedDate`, `leftDate`) VALUES
(1, 'HM_xxx', '123456789v', 'headofficemanager@gmail.com', '950a2c1b68ef6dd154800e089f20282a', 'b001', 'head_manager', 1234567890, 'Sample address', '2019-08-08', NULL, '2021-01-15 08:58:48', '2021-01-17 10:08:07', NULL),
(2, 'Man_yyy', '987654321v', 'manager@gmail.com', '1d0258c2440a8d19e716292b231e3190', 'b002', 'manager', 1234567890, 'Sample 2 address', '2020-08-03', NULL, '2021-01-15 09:05:36', '2021-01-15 09:05:36', NULL),
(4, 'S_jaffna', '543216789v', 'staffjaffna@gmail.com', '03a9b752cdc8c8d5f1fb3cac18bf7131', 'b001', 'staff', 1234567890, 'jaffna', '2020-06-15', NULL, '2021-01-15 09:13:24', '2021-02-07 00:52:26', NULL),
(5, 'S_colombo', '990022132v', 'staffcolombo@gmail.com', '8be46aff6e2601f09204dd35268c4114', 'b002', 'staff', 987654321, 'sample', '2020-08-17', NULL, '2021-01-15 09:13:24', '2021-01-15 09:13:24', NULL),
(6, 'Manager sample', '960222984v', 'manager2@gmail.com', '1d0258c2440a8d19e716292b231e3190', 'b020', 'manager', 76123456, 'Sample addres', '2021-02-15', NULL, '2021-02-15 16:49:31', '2021-02-15 16:49:31', NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `fd`
--

INSERT INTO `fd` (`FD_ID`, `savingAcc_id`, `FD_plan_id`, `amount`, `startDate`, `maturityDate`) VALUES
(1, 7, '3 year', '400000.00', '2021-02-18 15:11:55', '2024-07-22 15:10:35'),
(2, 9, 'half year', '100000.00', '2021-02-18 15:13:20', '2021-08-18 15:12:31');

-- --------------------------------------------------------

--
-- Stand-in structure for view `fd_active_details`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `fd_active_details`;
CREATE TABLE IF NOT EXISTS `fd_active_details` (
`savingAcc_id` int(11)
,`amount` decimal(30,2)
,`startDate` datetime
,`rate` smallint(3)
,`FD_ID` int(11)
);

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
-- Table structure for table `installment`
--

DROP TABLE IF EXISTS `installment`;
CREATE TABLE IF NOT EXISTS `installment` (
  `installment_ID` int(11) NOT NULL AUTO_INCREMENT,
  `loan_id` int(11) NOT NULL,
  `amount` decimal(30,0) NOT NULL,
  `paid_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`installment_ID`),
  KEY `loan_id` (`loan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `loan_plan`
--

DROP TABLE IF EXISTS `loan_plan`;
CREATE TABLE IF NOT EXISTS `loan_plan` (
  `loanPlanId` int(11) NOT NULL AUTO_INCREMENT,
  `description` varchar(2000) NOT NULL,
  `rate` int(3) NOT NULL,
  `maximumAmount` decimal(11,2) NOT NULL,
  `max_loan_in_SA` int(3) NOT NULL,
  PRIMARY KEY (`loanPlanId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `loan_plan`
--

INSERT INTO `loan_plan` (`loanPlanId`, `description`, `rate`, `maximumAmount`, `max_loan_in_SA`) VALUES
(1, 'Standard', 5, '500000.00', 60);

-- --------------------------------------------------------

--
-- Table structure for table `requestedloan`
--

DROP TABLE IF EXISTS `requestedloan`;
CREATE TABLE IF NOT EXISTS `requestedloan` (
  `loan_id` int(11) NOT NULL AUTO_INCREMENT,
  `NIC` varchar(12) NOT NULL,
  `Amount` decimal(30,2) NOT NULL,
  `interestPlanId` int(11) NOT NULL,
  `reason` text NOT NULL,
  `requestedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Duration_in_months` int(11) NOT NULL,
  `maturedDate` datetime NOT NULL,
  `updatedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `pending` tinyint(1) NOT NULL DEFAULT '1',
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`loan_id`),
  KEY `customer_nic` (`NIC`),
  KEY `loan_plan_id` (`interestPlanId`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `requestedloan`
--

INSERT INTO `requestedloan` (`loan_id`, `NIC`, `Amount`, `interestPlanId`, `reason`, `requestedDate`, `Duration_in_months`, `maturedDate`, `updatedDate`, `pending`, `approved`) VALUES
(1, '990022984v', '200000.00', 1, 'Home Loan', '2021-02-18 19:29:28', 12, '2022-02-18 19:28:49', '2021-02-18 20:14:34', 1, 1);

-- --------------------------------------------------------

--
-- Stand-in structure for view `savings_acc_details`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `savings_acc_details`;
CREATE TABLE IF NOT EXISTS `savings_acc_details` (
`accID` int(11)
,`NIC` varchar(12)
,`balance` decimal(30,2)
,`createdDate` datetime
,`s_plan_id` varchar(20)
,`rate` int(3)
);

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
(7, 'Adult', 2),
(9, 'Adult', 0);

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`transaction_id`, `sender_id`, `recipient_id`, `amount`, `description`, `time`) VALUES
(1, 8, 7, '4000.00', 'Testing', '2021-02-04 08:45:57'),
(3, 8, 7, '1800.00', 'Sample', '2021-02-16 11:47:10'),
(4, 7, 8, '1800.00', 'sample', '2021-02-16 11:49:00'),
(6, 8, 7, '600.00', 'Sample', '2021-02-16 11:59:31'),
(7, 8, 7, '600.00', 'Sample', '2021-02-18 10:08:20'),
(8, 8, 7, '500.00', NULL, '2021-02-18 10:15:10');

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
INSERT INTO `deposit` (`deposit_id`, `accID`, `amount`, `Description`, `branchCode`, `deposit_by`, `time`) VALUES (NULL, NEW.recipient_id, NEW.amount, 'By Transferring', NULL, NULL, CURRENT_TIMESTAMP);
INSERT INTO `withdrawal` (`withdrawal_id`, `accID`, `amount`, `Description`, `branchCode`, `withdrew_by`, `time`) VALUES (NULL, NEW.sender_id, NEW.amount, 'By Transferring', NULL, NULL, CURRENT_TIMESTAMP);
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
  `branchCode` varchar(50) DEFAULT NULL,
  `withdrew_by` int(11) DEFAULT NULL,
  `time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`withdrawal_id`),
  KEY `take Money` (`accID`),
  KEY `location` (`branchCode`),
  KEY `withdrewBy` (`withdrew_by`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `withdrawal`
--

INSERT INTO `withdrawal` (`withdrawal_id`, `accID`, `amount`, `Description`, `branchCode`, `withdrew_by`, `time`) VALUES
(1, 7, '4500.00', NULL, 'b001', 1, '2021-02-08 12:47:59'),
(2, 7, '3000.00', NULL, 'b001', 1, '2021-02-08 12:48:41'),
(3, 7, '2000.00', NULL, 'b001', 1, '2021-02-08 12:49:12'),
(4, 8, '760.00', 'By Transferring', 'b020', 5, '2021-02-16 11:53:05'),
(5, 8, '600.00', 'By Transferring', NULL, NULL, '2021-02-16 11:59:31'),
(6, 8, '600.00', 'By Transferring', NULL, NULL, '2021-02-18 10:08:20'),
(7, 8, '500.00', 'By Transferring', NULL, NULL, '2021-02-18 10:15:10');

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

-- --------------------------------------------------------

--
-- Structure for view `fd_active_details`
--
DROP TABLE IF EXISTS `fd_active_details`;

DROP VIEW IF EXISTS `fd_active_details`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `fd_active_details`  AS  select `f`.`savingAcc_id` AS `savingAcc_id`,`f`.`amount` AS `amount`,`f`.`startDate` AS `startDate`,`fp`.`rate` AS `rate`,`f`.`FD_ID` AS `FD_ID` from (`fd` `f` join `fd_plan` `fp` on((`f`.`FD_plan_id` = `fp`.`fd_plan_id`))) where (`f`.`maturityDate` > now()) order by `f`.`FD_ID` ;

-- --------------------------------------------------------

--
-- Structure for view `savings_acc_details`
--
DROP TABLE IF EXISTS `savings_acc_details`;

DROP VIEW IF EXISTS `savings_acc_details`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `savings_acc_details`  AS  select `a`.`accID` AS `accID`,`a`.`NIC` AS `NIC`,`a`.`balance` AS `balance`,`a`.`createdDate` AS `createdDate`,`s`.`s_plan_id` AS `s_plan_id`,`sp`.`rate` AS `rate` from ((`account` `a` join `saving_account` `s` on((`a`.`accID` = `s`.`accID`))) join `saving_interest_plan` `sp` on((`s`.`s_plan_id` = `sp`.`s_plan_id`))) where ((`a`.`type` = 'saving') and isnull(`a`.`closed_date`)) order by `a`.`accID` ;

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
-- Constraints for table `approvedloan`
--
ALTER TABLE `approvedloan`
  ADD CONSTRAINT `approvedloan_ibfk_1` FOREIGN KEY (`loan_id`) REFERENCES `requestedloan` (`loan_id`),
  ADD CONSTRAINT `approvedloan_ibfk_2` FOREIGN KEY (`approvedBy`) REFERENCES `employee` (`ID`);

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
-- Constraints for table `installment`
--
ALTER TABLE `installment`
  ADD CONSTRAINT `installment_ibfk_1` FOREIGN KEY (`loan_id`) REFERENCES `approvedloan` (`loan_id`);

--
-- Constraints for table `requestedloan`
--
ALTER TABLE `requestedloan`
  ADD CONSTRAINT `customer_nic` FOREIGN KEY (`NIC`) REFERENCES `customer` (`NIC`),
  ADD CONSTRAINT `loan_plan_id` FOREIGN KEY (`interestPlanId`) REFERENCES `loan_plan` (`loanPlanId`);

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

DROP EVENT `DepositsavingAccountInterest`$$
CREATE DEFINER=`root`@`localhost` EVENT `DepositsavingAccountInterest` ON SCHEDULE EVERY 1 DAY STARTS '2021-02-18 18:01:26' ON COMPLETION NOT PRESERVE ENABLE DO CALL `DepositTosavingAccountInterest`()$$

DROP EVENT `DepositForFDInterestToSA`$$
CREATE DEFINER=`root`@`localhost` EVENT `DepositForFDInterestToSA` ON SCHEDULE EVERY 1 DAY STARTS '2021-02-18 15:59:49' ON COMPLETION NOT PRESERVE ENABLE DO CALL `DepositForFDInterest`()$$

DROP EVENT `updateSavingAccountPlan`$$
CREATE DEFINER=`root`@`localhost` EVENT `updateSavingAccountPlan` ON SCHEDULE EVERY 1 DAY STARTS '2021-02-18 00:01:00' ON COMPLETION NOT PRESERVE ENABLE DO CALL `updateSavingAccPlan`()$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
