-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 03, 2021 at 01:10 PM
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
        	INSERT INTO deposit_online (`deposit_id`, `accID`, `amount`, `Description`, `time`) VALUES (NULL, a_id, ((blnc * rte)/100), 'Fixed Deposit', CURRENT_TIMESTAMP);
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
        	INSERT INTO deposit_online (`deposit_id`, `accID`, `amount`, `Description`, `time`) VALUES (NULL, a_id, ((blnc * rte)/100), 'Saving Interest', CURRENT_TIMESTAMP);
        END IF;      
	END LOOP getCDate;
	CLOSE curCDate;

END$$

DROP PROCEDURE IF EXISTS `generateAnnuallyReport`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `generateAnnuallyReport` (IN `brC` VARCHAR(50))  NO SQL
BEGIN
	DECLARE startDte DATETIME;
    DECLARE endDte DATETIME;
    DECLARE totalDepo INTEGER;
    DECLARE totalWD INTEGER;
    DECLARE totalTra INTEGER;
    DECLARE t_a_cus INTEGER;
    DECLARE t_a_emp INTEGER;
    DECLARE no_a_FD INTEGER;
    DECLARE no_a_loan INTEGER;
    DECLARE no_p_ins INTEGER;
    DECLARE t_d_a DECIMAL(30,2);
    DECLARE t_w_a DECIMAL(30,2);
    DECLARE t_t_a DECIMAL(30,2);
    DECLARE t_fd_a DECIMAL(30,2);
    DECLARE t_lo_a DECIMAL(30,2);

	SET endDte = NOW();
    SET startDte = DATE_ADD(endDte, INTERVAL -1 YEAR);
    
    SELECT COUNT(deposit_id), SUM(amount) INTO totalDepo, t_d_a FROM deposit WHERE branchCode = brC AND time BETWEEN startDte AND endDte;
    
    SELECT COUNT(withdrawal_id ), SUM(amount) INTO totalWD, t_w_a FROM withdrawal WHERE branchCode = brC AND time BETWEEN startDte AND endDte;
    
    SELECT COUNT(transaction_id), SUM(amount) INTO totalTra, t_t_a FROM transaction WHERE time BETWEEN startDte AND endDte;
    
    SELECT COUNT(NIC) INTO t_a_cus FROM customer WHERE openedBranch = brC AND leftDate IS NULL;
    
    SELECT COUNT(ID) INTO t_a_emp FROM employee WHERE branchCode = brC AND leftDate IS NULL;
    
    SELECT COUNT(f.FD_ID),SUM(amount) INTO no_a_FD, t_fd_a FROM fd f INNER JOIN account a WHERE f.maturityDate >= endDte AND f.savingAcc_id = a.accID AND a.branchCode = brC; 
    
    SELECT COUNT(loan_id), SUM(Amount) INTO no_a_loan, t_lo_a FROM approvedloandetails WHERE endDate >= NOW();
    
    SELECT COUNT(installment_ID) INTO no_p_ins FROM `installment` WHERE paid_time BETWEEN startDte AND endDte;
    
    INSERT INTO annual_report VALUES(NULL, YEAR(startDte), totalDepo, t_d_a, totalWD, t_w_a, totalTra, t_t_a, t_a_cus, t_a_emp, no_a_FD, t_fd_a, no_a_loan, t_lo_a, no_p_ins, brC, CURRENT_TIMESTAMP);
    
END$$

DROP PROCEDURE IF EXISTS `generateAnnuallyReportBranchwise`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `generateAnnuallyReportBranchwise` ()  NO SQL
BEGIN
	DECLARE finished INTEGER DEFAULT 0;
    DECLARE BraCd VARCHAR(50);
	DEClARE brc 
		CURSOR FOR 
			SELECT branchCode FROM branch;

	-- declare NOT FOUND handler
	DECLARE CONTINUE HANDLER 
        FOR NOT FOUND SET finished = 1;

	OPEN brc;

	getBrc: LOOP
		FETCH brc INTO BraCd;
		IF finished = 1 THEN 
			LEAVE getBrc;
		END IF;
        CALL `generateAnnuallyReport`(BraCd);     
	END LOOP getBrc;
    
	CLOSE brc;

END$$

DROP PROCEDURE IF EXISTS `generateMonthlyReport`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `generateMonthlyReport` (IN `brC` VARCHAR(50))  NO SQL
BEGIN
	DECLARE startDte DATETIME;
    DECLARE endDte DATETIME;
    DECLARE totalDepo INTEGER;
    DECLARE totalWD INTEGER;
    DECLARE totalTra INTEGER;
    DECLARE t_a_cus INTEGER;
    DECLARE t_a_emp INTEGER;
    DECLARE no_a_FD INTEGER;
    DECLARE no_a_loan INTEGER;
    DECLARE no_p_ins INTEGER;
    DECLARE t_d_a DECIMAL(30,2);
    DECLARE t_w_a DECIMAL(30,2);
    DECLARE t_t_a DECIMAL(30,2);
    DECLARE t_fd_a DECIMAL(30,2);
    DECLARE t_lo_a DECIMAL(30,2);

	SET endDte = NOW();
    SET startDte = DATE_ADD(endDte, INTERVAL -1 MONTH);
    
    SELECT COUNT(deposit_id), SUM(amount) INTO totalDepo, t_d_a FROM deposit WHERE branchCode = brC AND time BETWEEN startDte AND endDte;
    
    SELECT COUNT(withdrawal_id ), SUM(amount) INTO totalWD, t_w_a FROM withdrawal WHERE branchCode = brC AND time BETWEEN startDte AND endDte;
    
    SELECT COUNT(transaction_id), SUM(amount) INTO totalTra, t_t_a FROM transaction WHERE time BETWEEN startDte AND endDte;
    
    SELECT COUNT(NIC) INTO t_a_cus FROM customer WHERE openedBranch = brC AND leftDate IS NULL;
    
    SELECT COUNT(ID) INTO t_a_emp FROM employee WHERE branchCode = brC AND leftDate IS NULL;
    
    SELECT COUNT(f.FD_ID),SUM(amount) INTO no_a_FD, t_fd_a FROM fd f INNER JOIN account a WHERE f.maturityDate >= endDte AND f.savingAcc_id = a.accID AND a.branchCode = brC; 
    
    SELECT COUNT(loan_id), SUM(Amount) INTO no_a_loan, t_lo_a FROM approvedloandetails WHERE endDate >= NOW();
    
    SELECT COUNT(installment_ID) INTO no_p_ins FROM `installment` WHERE paid_time BETWEEN startDte AND endDte;
    
    INSERT INTO monthly_report VALUES(NULL, startDte, endDte, totalDepo, t_d_a, totalWD, t_w_a, totalTra, t_t_a, t_a_cus, t_a_emp, no_a_FD, t_fd_a, no_a_loan, t_lo_a, no_p_ins, brC, CURRENT_TIMESTAMP);
    
END$$

DROP PROCEDURE IF EXISTS `generateMonthlyReportBranchwise`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `generateMonthlyReportBranchwise` ()  NO SQL
BEGIN
	DECLARE finished INTEGER DEFAULT 0;
    DECLARE BraCd VARCHAR(50);
	DEClARE brc 
		CURSOR FOR 
			SELECT branchCode FROM branch;

	-- declare NOT FOUND handler
	DECLARE CONTINUE HANDLER 
        FOR NOT FOUND SET finished = 1;

	OPEN brc;

	getBrc: LOOP
		FETCH brc INTO BraCd;
		IF finished = 1 THEN 
			LEAVE getBrc;
		END IF;
        CALL `generateMonthlyReport`(BraCd);     
	END LOOP getBrc;
    
	CLOSE brc;

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

DROP PROCEDURE IF EXISTS `giveApprovalOfLoan`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `giveApprovalOfLoan` (IN `l_id` INT)  NO SQL
UPDATE `requestedloan` SET `approved` = '1' WHERE `requestedloan`.`loan_id` = l_id$$

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
(7, '990022984v', 'b001', '557977.70', '2021-01-19 15:02:19', '2021-03-03 18:20:02', 'saving', 1, NULL),
(8, '980021422v', 'b002', '143340.00', '2021-02-01 17:48:51', '2021-03-03 18:20:42', 'current', 1, NULL),
(9, '981234567v', 'b002', '192406.40', '2021-02-18 13:30:07', '2021-03-03 17:21:14', 'saving', 1, NULL);

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
-- Table structure for table `annual_report`
--

DROP TABLE IF EXISTS `annual_report`;
CREATE TABLE IF NOT EXISTS `annual_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `year` int(4) NOT NULL,
  `totalDeposit` int(11) DEFAULT NULL,
  `total_deposit_amount` decimal(30,2) DEFAULT NULL,
  `totalWithdrawal` int(11) DEFAULT NULL,
  `total_withdrawal_amount` decimal(30,2) DEFAULT NULL,
  `total transaction` int(11) DEFAULT NULL,
  `total_transaction_amount` decimal(30,2) DEFAULT NULL,
  `total_active_customer` int(11) DEFAULT NULL,
  `total_active_employee` int(11) DEFAULT NULL,
  `no_active_FD` int(11) DEFAULT NULL,
  `active_FD_amount` decimal(30,2) DEFAULT NULL,
  `no_active_loan` int(11) DEFAULT NULL,
  `active_loan_amount` decimal(30,2) DEFAULT NULL,
  `no_pending_installments` int(11) DEFAULT NULL,
  `branchCode` varchar(50) NOT NULL,
  `generated_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `annual_report`
--

INSERT INTO `annual_report` (`id`, `year`, `totalDeposit`, `total_deposit_amount`, `totalWithdrawal`, `total_withdrawal_amount`, `total transaction`, `total_transaction_amount`, `total_active_customer`, `total_active_employee`, `no_active_FD`, `active_FD_amount`, `no_active_loan`, `active_loan_amount`, `no_pending_installments`, `branchCode`, `generated_on`) VALUES
(2, 2020, 4, '7900.00', 3, '9500.00', 6, '9300.00', 1, 2, 1, '400000.00', 1, '200000.00', 3, 'b001', '2021-02-28 22:52:23'),
(3, 2020, 1, '800.00', 0, NULL, 6, '9300.00', 2, 2, 1, '100000.00', 1, '200000.00', 3, 'b002', '2021-02-28 22:52:23'),
(4, 2020, 0, NULL, 1, '760.00', 6, '9300.00', 0, 0, 0, NULL, 1, '200000.00', 3, 'b020', '2021-02-28 22:52:23');

-- --------------------------------------------------------

--
-- Table structure for table `approvedloan`
--

DROP TABLE IF EXISTS `approvedloan`;
CREATE TABLE IF NOT EXISTS `approvedloan` (
  `loan_id` int(11) NOT NULL,
  `installment_amount` decimal(30,2) NOT NULL,
  `approvedBy` int(11) DEFAULT NULL,
  `approvedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nextPaymentDate` datetime NOT NULL,
  `endDate` datetime NOT NULL,
  `countPayments` int(11) NOT NULL,
  `arrear` decimal(30,2) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`loan_id`),
  KEY `approvedBy` (`approvedBy`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `approvedloan`
--

INSERT INTO `approvedloan` (`loan_id`, `installment_amount`, `approvedBy`, `approvedDate`, `nextPaymentDate`, `endDate`, `countPayments`, `arrear`, `status`) VALUES
(1, '17500.00', 2, '2021-02-18 20:14:34', '2021-02-28 20:14:32', '2022-03-18 10:15:10', 6, '1500.00', 1),
(4, '17500.00', 1, '2021-03-02 00:54:12', '2021-06-01 07:24:12', '2022-09-01 07:24:12', 2, '5000.00', 1),
(101, '5833.33', NULL, '2021-03-02 02:20:56', '2021-04-02 02:20:56', '2022-09-02 02:20:56', 0, '0.00', 1);

-- --------------------------------------------------------

--
-- Stand-in structure for view `approvedloandetails`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `approvedloandetails`;
CREATE TABLE IF NOT EXISTS `approvedloandetails` (
`loan_id` int(11)
,`NIC` varchar(12)
,`Amount` decimal(30,2)
,`interestPlanId` int(11)
,`reason` text
,`requestedDate` datetime
,`Duration_in_months` int(11)
,`installment_amount` decimal(30,2)
,`approvedBy` int(11)
,`approvedDate` datetime
,`nextPaymentDate` datetime
,`endDate` datetime
,`countPayments` int(11)
,`arrear` decimal(30,2)
,`status` tinyint(1)
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
('b001', 'Jaffna', 'Jaffna town', 'H_O', 1234567890, '2021-01-14', '2021-03-02 21:21:10', '1'),
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
  `deposit_id` int(11) NOT NULL,
  `accID` int(11) NOT NULL,
  `amount` decimal(30,2) NOT NULL,
  `Description` text,
  `branchCode` varchar(50) NOT NULL,
  `deposit_by` int(11) NOT NULL,
  `time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`deposit_id`),
  KEY `depositBrach` (`branchCode`),
  KEY `deposit Account` (`accID`),
  KEY `depositBy` (`deposit_by`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `deposit`
--

INSERT INTO `deposit` (`deposit_id`, `accID`, `amount`, `Description`, `branchCode`, `deposit_by`, `time`) VALUES
(8, 7, '1800.00', NULL, 'b001', 5, '2021-02-01 15:20:51'),
(9, 7, '800.00', NULL, 'b002', 5, '2021-02-08 00:35:21'),
(10, 8, '4000.00', NULL, 'b001', 1, '2021-02-08 00:39:46'),
(11, 7, '1600.00', 'Fund Rise', 'b001', 1, '2021-02-08 00:40:43'),
(38, 7, '500.00', NULL, 'b001', 1, '2021-02-18 17:01:54'),
(39, 7, '5000.00', 'Mahalpola', 'b001', 1, '2021-03-01 13:18:26'),
(45, 7, '1800.00', 'ATM', 'b020', 2, '2021-03-03 10:11:39');

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
DROP TRIGGER IF EXISTS `setDepositID`;
DELIMITER $$
CREATE TRIGGER `setDepositID` BEFORE INSERT ON `deposit` FOR EACH ROW BEGIN
DECLARE newId Integer;

SELECT MAX(deposit_id) INTO newId FROM deposit_collection;
SET NEW.deposit_id = newId +1;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `deposit_collection`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `deposit_collection`;
CREATE TABLE IF NOT EXISTS `deposit_collection` (
`deposit_id` int(11)
,`accID` int(11)
,`amount` decimal(30,2)
,`Description` text
,`branchCode` varchar(50)
,`deposit_by` int(11)
,`time` datetime
);

-- --------------------------------------------------------

--
-- Table structure for table `deposit_online`
--

DROP TABLE IF EXISTS `deposit_online`;
CREATE TABLE IF NOT EXISTS `deposit_online` (
  `deposit_id` int(11) NOT NULL,
  `accID` int(11) NOT NULL,
  `amount` decimal(30,2) NOT NULL,
  `Description` text,
  `time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`deposit_id`),
  KEY `accID` (`accID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `deposit_online`
--

INSERT INTO `deposit_online` (`deposit_id`, `accID`, `amount`, `Description`, `time`) VALUES
(46, 8, '2000.00', 'Deposit Onlie', '2021-03-03 17:32:33'),
(47, 8, '2000.00', 'By Transferring', '2021-03-03 17:33:27'),
(48, 8, '600.00', 'By Transferring', '2021-03-03 17:46:24'),
(49, 7, '60000.00', 'Saving Interest', '2021-03-03 17:53:56'),
(50, 8, '500.00', 'By Transferring', '2021-03-03 18:00:56'),
(51, 8, '750.00', 'By Transferring', '2021-03-03 18:20:02');

--
-- Triggers `deposit_online`
--
DROP TRIGGER IF EXISTS `Deposit_to_account_online`;
DELIMITER $$
CREATE TRIGGER `Deposit_to_account_online` BEFORE INSERT ON `deposit_online` FOR EACH ROW UPDATE account
SET balance =(balance + NEW.amount)
WHERE accID=NEW.accID
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `setDepositIDOnline`;
DELIMITER $$
CREATE TRIGGER `setDepositIDOnline` BEFORE INSERT ON `deposit_online` FOR EACH ROW BEGIN
DECLARE newId Integer;

SELECT MAX(deposit_id) INTO newId FROM deposit_collection;
SET NEW.deposit_id = newId +1;

END
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
  `withdrewOrNot` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`FD_ID`),
  KEY `savings` (`savingAcc_id`),
  KEY `selected_plan` (`FD_plan_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `fd`
--

INSERT INTO `fd` (`FD_ID`, `savingAcc_id`, `FD_plan_id`, `amount`, `startDate`, `maturityDate`, `withdrewOrNot`) VALUES
(1, 7, '3 year', '400000.00', '2021-02-01 15:11:55', '2024-02-01 15:10:35', 0),
(2, 9, 'half year', '100000.00', '2021-02-18 15:13:20', '2021-08-18 15:12:31', 0);

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
  `amount` decimal(30,2) NOT NULL,
  `paid_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`installment_ID`),
  KEY `loan_id` (`loan_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `installment`
--

INSERT INTO `installment` (`installment_ID`, `loan_id`, `amount`, `paid_time`) VALUES
(1, 1, '20000.00', '2021-02-22 11:13:46'),
(2, 1, '40000.00', '2021-02-22 11:14:41'),
(4, 1, '20000.00', '2021-02-28 15:11:56'),
(5, 4, '15000.00', '2021-03-02 00:55:25'),
(6, 4, '25000.00', '2021-03-02 00:56:25'),
(7, 1, '5000.00', '2021-03-02 18:18:23'),
(8, 1, '5000.00', '2021-03-03 18:08:59'),
(9, 1, '5000.00', '2021-03-03 18:09:28'),
(10, 1, '5000.00', '2021-03-03 18:10:12');

--
-- Triggers `installment`
--
DROP TRIGGER IF EXISTS `installment_count_set`;
DELIMITER $$
CREATE TRIGGER `installment_count_set` AFTER INSERT ON `installment` FOR EACH ROW BEGIN
	DECLARE m_arr DECIMAL(30,2);
    DECLARE m_ins DECIMAL(30,2);
    DECLARE new_arrear DECIMAL(30,2);
    DECLARE new_count INTEGER;    
    DECLARE next_p_time DATETIME;
    DECLARE new_next_p_time DATETIME;
    
    SELECT `installment_amount`,`arrear`,`nextPaymentDate` INTO m_ins, m_arr, next_p_time FROM `approvedloan` WHERE `loan_id` = NEW.loan_id;
    
    SET new_arrear = (m_arr + NEW.amount) % m_ins;
    SET new_count = (m_arr + NEW.amount) DIV m_ins;
    SET new_count = (m_arr + NEW.amount) DIV m_ins;
    SET new_next_p_time = DATE_ADD(next_p_time, INTERVAL new_count MONTH);
    
    UPDATE `approvedloan` SET `countPayments` = countPayments + new_count, `arrear` = new_arrear, `nextPaymentDate` = new_next_p_time WHERE `approvedloan`.`loan_id` = NEW.loan_id;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `late_loan_installment`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `late_loan_installment`;
CREATE TABLE IF NOT EXISTS `late_loan_installment` (
`loan_id` int(11)
,`NIC` varchar(12)
,`Amount` decimal(30,2)
,`installment_amount` decimal(30,2)
,`reason` text
,`nextPaymentDate` datetime
,`arrear` decimal(30,2)
,`endDate` datetime
,`name` varchar(50)
,`eMail` varchar(50)
,`mobileNo` int(10)
,`openedBranch` varchar(50)
);

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
-- Table structure for table `monthly_report`
--

DROP TABLE IF EXISTS `monthly_report`;
CREATE TABLE IF NOT EXISTS `monthly_report` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `startDate` datetime NOT NULL,
  `endDate` datetime NOT NULL,
  `totalDeposit` int(11) DEFAULT NULL,
  `total_deposit_amount` decimal(30,2) DEFAULT NULL,
  `totalWithdrawal` int(11) DEFAULT NULL,
  `total_withdrawal_amount` decimal(30,2) DEFAULT NULL,
  `total transaction` int(11) DEFAULT NULL,
  `total_transaction_amount` decimal(30,2) DEFAULT NULL,
  `total_active_customer` int(11) DEFAULT NULL,
  `total_active_employee` int(11) DEFAULT NULL,
  `no_active_FD` int(11) DEFAULT NULL,
  `active_FD_amount` decimal(30,2) DEFAULT NULL,
  `no_active_loan` int(11) DEFAULT NULL,
  `active_loan_amount` decimal(30,2) DEFAULT NULL,
  `no_pending_installments` int(11) DEFAULT NULL,
  `branchCode` varchar(50) NOT NULL,
  `generated_on` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `generatedBranch` (`branchCode`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `monthly_report`
--

INSERT INTO `monthly_report` (`id`, `startDate`, `endDate`, `totalDeposit`, `total_deposit_amount`, `totalWithdrawal`, `total_withdrawal_amount`, `total transaction`, `total_transaction_amount`, `total_active_customer`, `total_active_employee`, `no_active_FD`, `active_FD_amount`, `no_active_loan`, `active_loan_amount`, `no_pending_installments`, `branchCode`, `generated_on`) VALUES
(1, '2021-01-25 13:18:00', '2021-02-25 13:18:00', 4, '7900.00', 3, '9500.00', 6, '9300.00', 1, 2, 1, '400000.00', 1, '200000.00', 2, 'b001', '2021-02-25 13:18:00'),
(2, '2021-01-25 13:18:00', '2021-02-25 13:18:00', 1, '800.00', 0, NULL, 6, '9300.00', 2, 2, 1, '100000.00', 1, '200000.00', 2, 'b002', '2021-02-25 13:18:00'),
(3, '2021-01-25 13:18:00', '2021-02-25 13:18:00', 0, NULL, 1, '760.00', 6, '9300.00', 0, 1, 0, NULL, 1, '200000.00', 2, 'b020', '2021-02-25 13:18:00'),
(4, '2021-01-25 13:19:00', '2021-02-25 13:19:00', 4, '7900.00', 3, '9500.00', 6, '9300.00', 1, 2, 1, '400000.00', 1, '200000.00', 2, 'b001', '2021-02-25 13:19:00'),
(5, '2021-01-25 13:19:00', '2021-02-25 13:19:00', 1, '800.00', 0, NULL, 6, '9300.00', 2, 2, 1, '100000.00', 1, '200000.00', 2, 'b002', '2021-02-25 13:19:00'),
(6, '2021-01-25 13:19:00', '2021-02-25 13:19:00', 0, NULL, 1, '760.00', 6, '9300.00', 0, 1, 0, NULL, 1, '200000.00', 2, 'b020', '2021-02-25 13:19:00');

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
  `updatedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `pending` tinyint(1) NOT NULL DEFAULT '1',
  `approved` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`loan_id`),
  KEY `customer_nic` (`NIC`),
  KEY `loan_plan_id` (`interestPlanId`)
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `requestedloan`
--

INSERT INTO `requestedloan` (`loan_id`, `NIC`, `Amount`, `interestPlanId`, `reason`, `requestedDate`, `Duration_in_months`, `updatedDate`, `pending`, `approved`) VALUES
(1, '990022984v', '200000.00', 1, 'Home Loan', '2021-02-18 19:29:28', 12, '2021-02-18 20:14:34', 1, 1),
(2, '990022984v', '300000.00', 1, 'Testing', '2021-02-28 00:47:39', 36, '2021-03-02 01:57:36', 1, 0),
(3, '980021422v', '200000.00', 1, 'Shop Development', '2021-02-28 12:42:54', 24, '2021-02-28 12:55:01', 0, 0),
(4, '980021422v', '300000.00', 1, 'Home Development', '2021-03-02 00:53:58', 18, '2021-03-02 01:48:19', 1, 1),
(101, '990022984v', '100000.00', 1, 'Home Development Building', '2021-03-02 02:20:56', 18, '2021-03-02 13:06:57', 0, 0);

--
-- Triggers `requestedloan`
--
DROP TRIGGER IF EXISTS `onlineLoanRequest`;
DELIMITER $$
CREATE TRIGGER `onlineLoanRequest` AFTER INSERT ON `requestedloan` FOR EACH ROW BEGIN
	DECLARE new_next_p_time DATETIME;
    DECLARE eDate DATETIME;
    DECLARE ins DECIMAL(30,2);
    DECLARE nicd VARCHAR(12);
    
	if(NEW.pending=1 AND NEW.approved=1) THEN
    	SET new_next_p_time = DATE_ADD(CURRENT_TIMESTAMP, INTERVAL 1 MONTH);
    	SET eDate = DATE_ADD(CURRENT_TIMESTAMP, INTERVAL NEW.Duration_in_months MONTH);
    	SET ins = (NEW.Amount * 1.05)/NEW.Duration_in_months;
        
        SELECT accID INTO nicd FROM `savings_acc_details` WHERE NIC = NEW.NIC;
        
    	INSERT INTO `approvedloan` (`loan_id`, `installment_amount`, `approvedBy`, `approvedDate`, `nextPaymentDate`, `endDate`, `countPayments`, `arrear`, `status`) VALUES (NEW.loan_id, ins, NULL, CURRENT_TIMESTAMP,new_next_p_time, eDate, '0', '0', '1');
        
     INSERT INTO deposit_online (`deposit_id`, `accID`, `amount`, `Description`, `time`) VALUES (NULL, nicd , NEW.Amount, 'Loan Dposit From Bank',  CURRENT_TIMESTAMP);
    END IF;
END
$$
DELIMITER ;

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
(7, 'Adult', 5),
(9, 'Adult', 1);

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
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `transaction`
--

INSERT INTO `transaction` (`transaction_id`, `sender_id`, `recipient_id`, `amount`, `description`, `time`) VALUES
(1, 8, 7, '4000.00', 'Testing', '2021-02-04 08:45:57'),
(3, 8, 7, '1800.00', 'Sample', '2021-02-16 11:47:10'),
(4, 7, 8, '1800.00', 'sample', '2021-02-16 11:49:00'),
(6, 8, 7, '600.00', 'Sample', '2021-02-16 11:59:31'),
(7, 8, 7, '600.00', 'Sample', '2021-02-18 10:08:20'),
(8, 8, 7, '500.00', NULL, '2021-02-18 10:15:10'),
(9, 8, 7, '500.00', NULL, '2021-03-01 20:20:27'),
(10, 7, 8, '500.00', 'Donate', '2021-03-01 20:24:27'),
(11, 7, 8, '700.00', NULL, '2021-03-01 23:09:07'),
(12, 7, 8, '4000.00', 'Urgent', '2021-03-02 21:14:28'),
(13, 7, 8, '2000.00', 'Transfer fund', '2021-03-03 17:33:27'),
(14, 7, 8, '600.00', 'Last', '2021-03-03 17:46:24'),
(15, 7, 8, '500.00', 'Thakshayan', '2021-03-03 18:00:56'),
(16, 7, 8, '750.00', 'Urgent', '2021-03-03 18:20:02');

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
INSERT INTO deposit_online (`deposit_id`, `accID`, `amount`, `Description`, `time`) VALUES (NULL, NEW.recipient_id, NEW.amount, 'By Transferring', CURRENT_TIMESTAMP);

INSERT INTO withdrawal_online (`withdrawal_id`, `accID`, `amount`, `Description`, `time`) VALUES (NULL, NEW.sender_id, NEW.amount, 'By Transferring', CURRENT_TIMESTAMP);
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `withdrawal`
--

DROP TABLE IF EXISTS `withdrawal`;
CREATE TABLE IF NOT EXISTS `withdrawal` (
  `withdrawal_id` int(11) NOT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `withdrawal`
--

INSERT INTO `withdrawal` (`withdrawal_id`, `accID`, `amount`, `Description`, `branchCode`, `withdrew_by`, `time`) VALUES
(14, 7, '1000.00', NULL, 'b001', 4, '2021-03-02 21:49:35'),
(15, 8, '15000.00', 'Urgent', 'b001', 4, '2021-03-02 21:49:55'),
(16, 9, '10000.00', NULL, 'b001', 4, '2021-03-02 21:50:03'),
(17, 9, '40000.00', NULL, 'b020', 5, '2021-03-02 21:51:01'),
(18, 8, '600.00', 'Testing', 'b002', 2, '2021-03-02 21:52:08'),
(19, 8, '1800.00', 'Testoing', 'b001', 5, '2021-03-03 16:26:08'),
(22, 8, '1800.00', 'Tef', 'b002', 5, '2021-03-03 16:55:27'),
(553, 8, '1000.00', 'Medical', 'b001', 1, '2021-03-03 18:20:42');

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
DROP TRIGGER IF EXISTS `setWithdrewID`;
DELIMITER $$
CREATE TRIGGER `setWithdrewID` BEFORE INSERT ON `withdrawal` FOR EACH ROW BEGIN
DECLARE newId Integer;

SELECT MAX(withdrawal_id) INTO newId FROM withdrawal_collection;
SET NEW.withdrawal_id = newId +1;

END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Stand-in structure for view `withdrawal_collection`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `withdrawal_collection`;
CREATE TABLE IF NOT EXISTS `withdrawal_collection` (
`withdrawal_id` int(11)
,`accID` int(11)
,`amount` decimal(30,2)
,`Description` text
,`branchCode` varchar(50)
,`withdrew_by` int(11)
,`time` datetime
);

-- --------------------------------------------------------

--
-- Table structure for table `withdrawal_online`
--

DROP TABLE IF EXISTS `withdrawal_online`;
CREATE TABLE IF NOT EXISTS `withdrawal_online` (
  `withdrawal_id` int(11) NOT NULL,
  `accID` int(11) NOT NULL,
  `amount` decimal(30,2) NOT NULL,
  `Description` text NOT NULL,
  `time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`withdrawal_id`),
  KEY `takes Money Online` (`accID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `withdrawal_online`
--

INSERT INTO `withdrawal_online` (`withdrawal_id`, `accID`, `amount`, `Description`, `time`) VALUES
(76, 9, '1800.00', 'dfsdf', '2021-03-03 17:01:09'),
(546, 7, '456.00', 'fgdg', '2021-03-03 17:01:09'),
(547, 7, '2000.00', 'By Transferring', '2021-03-03 17:33:27'),
(548, 7, '600.00', 'By Transferring', '2021-03-03 17:46:24'),
(549, 7, '500.00', 'By Transferring', '2021-03-03 18:00:56'),
(550, 7, '5000.00', 'For Loan Payment', '2021-03-03 18:09:28'),
(551, 7, '5000.00', 'For Loan Payment', '2021-03-03 18:10:11'),
(552, 7, '750.00', 'By Transferring', '2021-03-03 18:20:02');

--
-- Triggers `withdrawal_online`
--
DROP TRIGGER IF EXISTS `Withdraw_from_account_online`;
DELIMITER $$
CREATE TRIGGER `Withdraw_from_account_online` BEFORE INSERT ON `withdrawal_online` FOR EACH ROW UPDATE account
SET balance = (balance - NEW.amount)
WHERE accID = NEW.accID
$$
DELIMITER ;
DROP TRIGGER IF EXISTS `setWithdrewIDOnline`;
DELIMITER $$
CREATE TRIGGER `setWithdrewIDOnline` BEFORE INSERT ON `withdrawal_online` FOR EACH ROW BEGIN
DECLARE newId Integer;

SELECT MAX(withdrawal_id) INTO newId FROM withdrawal_collection;
SET NEW.withdrawal_id = newId +1;

END
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
-- Structure for view `approvedloandetails`
--
DROP TABLE IF EXISTS `approvedloandetails`;

DROP VIEW IF EXISTS `approvedloandetails`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `approvedloandetails`  AS  select `r`.`loan_id` AS `loan_id`,`r`.`NIC` AS `NIC`,`r`.`Amount` AS `Amount`,`r`.`interestPlanId` AS `interestPlanId`,`r`.`reason` AS `reason`,`r`.`requestedDate` AS `requestedDate`,`r`.`Duration_in_months` AS `Duration_in_months`,`a`.`installment_amount` AS `installment_amount`,`a`.`approvedBy` AS `approvedBy`,`a`.`approvedDate` AS `approvedDate`,`a`.`nextPaymentDate` AS `nextPaymentDate`,`a`.`endDate` AS `endDate`,`a`.`countPayments` AS `countPayments`,`a`.`arrear` AS `arrear`,`a`.`status` AS `status` from (`requestedloan` `r` join `approvedloan` `a` on((`r`.`loan_id` = `a`.`loan_id`))) ;

-- --------------------------------------------------------

--
-- Structure for view `deposit_collection`
--
DROP TABLE IF EXISTS `deposit_collection`;

DROP VIEW IF EXISTS `deposit_collection`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `deposit_collection`  AS  select `deposit`.`deposit_id` AS `deposit_id`,`deposit`.`accID` AS `accID`,`deposit`.`amount` AS `amount`,`deposit`.`Description` AS `Description`,`deposit`.`branchCode` AS `branchCode`,`deposit`.`deposit_by` AS `deposit_by`,`deposit`.`time` AS `time` from `deposit` union all select `deposit_online`.`deposit_id` AS `deposit_id`,`deposit_online`.`accID` AS `accID`,`deposit_online`.`amount` AS `amount`,`deposit_online`.`Description` AS `Description`,NULL AS `branchCode`,NULL AS `deposit_by`,`deposit_online`.`time` AS `time` from `deposit_online` ;

-- --------------------------------------------------------

--
-- Structure for view `fd_active_details`
--
DROP TABLE IF EXISTS `fd_active_details`;

DROP VIEW IF EXISTS `fd_active_details`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `fd_active_details`  AS  select `f`.`savingAcc_id` AS `savingAcc_id`,`f`.`amount` AS `amount`,`f`.`startDate` AS `startDate`,`fp`.`rate` AS `rate`,`f`.`FD_ID` AS `FD_ID` from (`fd` `f` join `fd_plan` `fp` on((`f`.`FD_plan_id` = `fp`.`fd_plan_id`))) where (`f`.`maturityDate` > now()) order by `f`.`FD_ID` ;

-- --------------------------------------------------------

--
-- Structure for view `late_loan_installment`
--
DROP TABLE IF EXISTS `late_loan_installment`;

DROP VIEW IF EXISTS `late_loan_installment`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `late_loan_installment`  AS  select `a`.`loan_id` AS `loan_id`,`a`.`NIC` AS `NIC`,`a`.`Amount` AS `Amount`,`a`.`installment_amount` AS `installment_amount`,`a`.`reason` AS `reason`,`a`.`nextPaymentDate` AS `nextPaymentDate`,`a`.`arrear` AS `arrear`,`a`.`endDate` AS `endDate`,`c`.`name` AS `name`,`c`.`eMail` AS `eMail`,`c`.`mobileNo` AS `mobileNo`,`c`.`openedBranch` AS `openedBranch` from (`approvedloandetails` `a` join `customer` `c` on((`a`.`NIC` = `c`.`NIC`))) where ((`a`.`nextPaymentDate` < now()) and (`a`.`status` = '1')) ;

-- --------------------------------------------------------

--
-- Structure for view `savings_acc_details`
--
DROP TABLE IF EXISTS `savings_acc_details`;

DROP VIEW IF EXISTS `savings_acc_details`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `savings_acc_details`  AS  select `a`.`accID` AS `accID`,`a`.`NIC` AS `NIC`,`a`.`balance` AS `balance`,`a`.`createdDate` AS `createdDate`,`s`.`s_plan_id` AS `s_plan_id`,`sp`.`rate` AS `rate` from ((`account` `a` join `saving_account` `s` on((`a`.`accID` = `s`.`accID`))) join `saving_interest_plan` `sp` on((`s`.`s_plan_id` = `sp`.`s_plan_id`))) where ((`a`.`type` = 'saving') and isnull(`a`.`closed_date`)) order by `a`.`accID` ;

-- --------------------------------------------------------

--
-- Structure for view `withdrawal_collection`
--
DROP TABLE IF EXISTS `withdrawal_collection`;

DROP VIEW IF EXISTS `withdrawal_collection`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `withdrawal_collection`  AS  select `withdrawal`.`withdrawal_id` AS `withdrawal_id`,`withdrawal`.`accID` AS `accID`,`withdrawal`.`amount` AS `amount`,`withdrawal`.`Description` AS `Description`,`withdrawal`.`branchCode` AS `branchCode`,`withdrawal`.`withdrew_by` AS `withdrew_by`,`withdrawal`.`time` AS `time` from `withdrawal` union all select `withdrawal_online`.`withdrawal_id` AS `withdrawal_id`,`withdrawal_online`.`accID` AS `accID`,`withdrawal_online`.`amount` AS `amount`,`withdrawal_online`.`Description` AS `Description`,NULL AS `branchCode`,NULL AS `withdrew_by`,`withdrawal_online`.`time` AS `time` from `withdrawal_online` ;

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
-- Constraints for table `deposit_online`
--
ALTER TABLE `deposit_online`
  ADD CONSTRAINT `deposit Account online` FOREIGN KEY (`accID`) REFERENCES `account` (`accID`);

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
-- Constraints for table `monthly_report`
--
ALTER TABLE `monthly_report`
  ADD CONSTRAINT `generatedBranch` FOREIGN KEY (`branchCode`) REFERENCES `branch` (`branchCode`);

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

--
-- Constraints for table `withdrawal_online`
--
ALTER TABLE `withdrawal_online`
  ADD CONSTRAINT `takes Money Online` FOREIGN KEY (`accID`) REFERENCES `account` (`accID`);

DELIMITER $$
--
-- Events
--
DROP EVENT `withdrawal_set_0`$$
CREATE DEFINER=`root`@`localhost` EVENT `withdrawal_set_0` ON SCHEDULE EVERY 1 MONTH STARTS '2021-02-04 00:00:00' ON COMPLETION PRESERVE ENABLE DO UPDATE `saving_account` SET `no_of_withdrawals` =0$$

DROP EVENT `DepositsavingAccountInterest`$$
CREATE DEFINER=`root`@`localhost` EVENT `DepositsavingAccountInterest` ON SCHEDULE EVERY 1 DAY STARTS '2021-02-18 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO CALL `DepositTosavingAccountInterest`()$$

DROP EVENT `DepositForFDInterestToSA`$$
CREATE DEFINER=`root`@`localhost` EVENT `DepositForFDInterestToSA` ON SCHEDULE EVERY 1 DAY STARTS '2021-02-18 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO CALL `DepositForFDInterest`()$$

DROP EVENT `updateSavingAccountPlan`$$
CREATE DEFINER=`root`@`localhost` EVENT `updateSavingAccountPlan` ON SCHEDULE EVERY 1 DAY STARTS '2021-02-18 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO CALL `updateSavingAccPlan`()$$

DROP EVENT `generateMonthlyReportBW`$$
CREATE DEFINER=`root`@`localhost` EVENT `generateMonthlyReportBW` ON SCHEDULE EVERY 1 MONTH STARTS '2021-02-25 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO CALL `generateMonthlyReportBranchwise`()$$

DROP EVENT `generateAnnuallyReportBW`$$
CREATE DEFINER=`root`@`localhost` EVENT `generateAnnuallyReportBW` ON SCHEDULE EVERY 1 YEAR STARTS '2021-02-27 10:10:27' ON COMPLETION NOT PRESERVE ENABLE DO CALL `generateAnnuallyReportBranchwise()`$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
