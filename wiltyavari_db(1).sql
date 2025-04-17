-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 17, 2025 at 06:36 AM
-- Server version: 11.4.3-MariaDB
-- PHP Version: 8.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wiltyavari_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts_check`
--

CREATE TABLE `accounts_check` (
  `acc_id` bigint(20) NOT NULL,
  `docType` varchar(100) NOT NULL,
  `empId` varchar(85) NOT NULL,
  `verifiedBy` varchar(100) NOT NULL,
  `verified_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE `activity` (
  `activityId` int(11) NOT NULL,
  `activityTypeCode` varchar(25) DEFAULT NULL,
  `activityType` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_config`
--

CREATE TABLE `app_config` (
  `id` int(11) NOT NULL,
  `max_distance` int(11) NOT NULL DEFAULT 1000 COMMENT 'should be meters'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `app_version`
--

CREATE TABLE `app_version` (
  `id` int(11) NOT NULL,
  `candidate_id` varchar(255) NOT NULL,
  `mobile_os` varchar(255) NOT NULL,
  `os_version` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `areas_of_work`
--

CREATE TABLE `areas_of_work` (
  `id` int(11) NOT NULL,
  `clientId` int(11) NOT NULL,
  `areaOfWork` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attachment`
--

CREATE TABLE `attachment` (
  `messageid` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id` bigint(20) UNSIGNED NOT NULL,
  `filepath` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `contents` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attachmentpath`
--

CREATE TABLE `attachmentpath` (
  `krId` int(11) NOT NULL,
  `messageid` varchar(200) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL,
  `updated_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auditlog`
--

CREATE TABLE `auditlog` (
  `id` bigint(20) NOT NULL,
  `candidateId` varchar(85) NOT NULL,
  `auditStatus` int(5) NOT NULL,
  `chandlerUser` varchar(100) NOT NULL,
  `auditedTime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_check_data`
--

CREATE TABLE `audit_check_data` (
  `id` bigint(20) NOT NULL,
  `candidateId` varchar(85) NOT NULL,
  `chkType` int(11) NOT NULL,
  `status` varchar(20) NOT NULL,
  `consultant` varchar(150) NOT NULL,
  `checked_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `jobOrderNotify` int(11) NOT NULL DEFAULT 0,
  `payroll_status` varchar(20) DEFAULT NULL,
  `payroll_officer` varchar(150) DEFAULT NULL,
  `verified_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `clientId` int(11) DEFAULT NULL,
  `positionId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_check_list`
--

CREATE TABLE `audit_check_list` (
  `id` int(11) NOT NULL,
  `doc_type_id` int(11) DEFAULT NULL,
  `description` varchar(150) NOT NULL,
  `sort` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `award`
--

CREATE TABLE `award` (
  `award_id` bigint(20) NOT NULL,
  `award_code` varchar(100) NOT NULL,
  `award` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cancelled_firebase_shifts`
--

CREATE TABLE `cancelled_firebase_shifts` (
  `id` int(11) NOT NULL,
  `shiftId` int(11) NOT NULL,
  `shiftStatus` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `candidate`
--

CREATE TABLE `candidate` (
  `candidate_no` int(11) NOT NULL,
  `clockPin` varchar(20) DEFAULT NULL,
  `candidateId` varchar(85) NOT NULL,
  `jobadderId` bigint(20) DEFAULT NULL,
  `tandaUserId` int(11) DEFAULT NULL,
  `title` varchar(10) DEFAULT NULL,
  `lamattinaId` varchar(45) DEFAULT NULL,
  `axiomno` int(11) NOT NULL DEFAULT 0,
  `messageid` varchar(200) DEFAULT NULL,
  `firstName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `nickname` varchar(100) DEFAULT NULL,
  `lastName` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fullName` text DEFAULT NULL,
  `address` mediumtext DEFAULT NULL,
  `unit_no` varchar(15) DEFAULT NULL,
  `street_number` varchar(25) DEFAULT NULL,
  `street_name` varchar(200) DEFAULT NULL,
  `state` varchar(200) DEFAULT NULL,
  `postcode` int(11) DEFAULT 0,
  `homePhoneNo` varchar(15) DEFAULT NULL,
  `mobileNo` varchar(15) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `sex` varchar(65) DEFAULT NULL,
  `screenDate` varchar(45) DEFAULT NULL,
  `suburb` varchar(150) DEFAULT NULL,
  `currentWrk` mediumtext DEFAULT NULL,
  `howfar` varchar(45) DEFAULT NULL,
  `genLabourPay` mediumtext DEFAULT NULL,
  `criminalConviction` varchar(25) DEFAULT NULL,
  `convictionDescription` mediumtext DEFAULT NULL,
  `hasCar` varchar(25) DEFAULT NULL,
  `licenceType` varchar(35) DEFAULT NULL,
  `residentStatus` varchar(35) DEFAULT NULL,
  `medicalCondition` varchar(25) DEFAULT NULL,
  `medicalConditionDesc` mediumtext DEFAULT NULL,
  `workType` mediumtext DEFAULT NULL,
  `overtime` varchar(25) DEFAULT NULL,
  `bookInterview` varchar(25) DEFAULT NULL,
  `intvwTime` varchar(45) DEFAULT NULL,
  `consultantId` varchar(65) DEFAULT NULL,
  `status` varchar(65) NOT NULL DEFAULT 'Pending',
  `dob` varchar(65) DEFAULT NULL,
  `candidateStatus` varchar(25) NOT NULL DEFAULT 'USE',
  `tfn` int(11) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `type` int(11) NOT NULL DEFAULT 2,
  `apiKey` varchar(100) DEFAULT NULL,
  `supervicerId` int(11) DEFAULT 0,
  `supervisorClient` bigint(20) DEFAULT NULL,
  `employeeImage` longblob DEFAULT NULL,
  `api_token` varchar(255) DEFAULT NULL,
  `superFundName` varchar(255) DEFAULT NULL,
  `superMemberNo` varchar(200) DEFAULT NULL,
  `superUSINo` varchar(255) DEFAULT NULL,
  `visa_type` int(11) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL,
  `empStatus` varchar(45) NOT NULL DEFAULT 'INACTIVE',
  `active_inactive_reason` text DEFAULT NULL,
  `workStartDate` date DEFAULT '1970-01-01',
  `auditStatus` int(5) NOT NULL DEFAULT 0,
  `empCondition` int(11) DEFAULT 0,
  `reg_pack_status` int(11) DEFAULT 0,
  `foundhow` varchar(85) DEFAULT NULL,
  `customerSurveyStatus` int(11) NOT NULL DEFAULT 0,
  `customerSurveySent` varchar(65) DEFAULT NULL,
  `promotion` int(11) DEFAULT NULL,
  `reg_pack_sent_time` datetime DEFAULT NULL,
  `ohsCheckStatus` varchar(35) NOT NULL DEFAULT 'NOT CHECKED',
  `ohsCheckedBy` varchar(65) DEFAULT NULL,
  `ohsCheckedTime` datetime DEFAULT NULL,
  `reg_app_completion` int(11) DEFAULT NULL,
  `reg_app_active` int(11) DEFAULT NULL,
  `reg_app_progress` int(11) DEFAULT NULL,
  `reg_app_contracts_active` int(11) DEFAULT NULL,
  `reg_app_contracts_progress` int(11) DEFAULT NULL,
  `reg_app_contracts_progress_color` varchar(45) DEFAULT NULL,
  `reg_app_update_active` int(11) DEFAULT NULL,
  `riteq_id` varchar(255) DEFAULT NULL,
  `chronus_id` varchar(255) DEFAULT NULL,
  `ref_code_applied` varchar(255) DEFAULT NULL,
  `rec_status` bigint(20) DEFAULT NULL,
  `casual_status` longtext DEFAULT NULL,
  `casual_status_update` timestamp NULL DEFAULT NULL,
  `casual_status_updated_by` varchar(200) DEFAULT NULL,
  `autoId` bigint(20) NOT NULL DEFAULT 0,
  `jb_id` bigint(20) NOT NULL DEFAULT 0,
  `reason_for_suitability` longtext DEFAULT NULL,
  `ph_screen_time` varchar(100) DEFAULT NULL,
  `fairwork_info_sent_time` datetime DEFAULT NULL,
  `verification_code` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `candidate_document`
--

CREATE TABLE `candidate_document` (
  `docId` int(11) NOT NULL,
  `docTypeId` int(11) DEFAULT 0,
  `messageid` varchar(200) DEFAULT NULL,
  `candidateId` varchar(85) NOT NULL,
  `fileName` varchar(255) NOT NULL,
  `filePath` varchar(255) NOT NULL,
  `validFrom` varchar(45) DEFAULT NULL,
  `validTo` varchar(45) DEFAULT NULL,
  `reviewDate` varchar(45) DEFAULT NULL,
  `notes` mediumtext DEFAULT NULL,
  `createdDate` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `candidate_document_order`
--

CREATE TABLE `candidate_document_order` (
  `id` bigint(20) NOT NULL,
  `docTypeId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `candidate_expoperating`
--

CREATE TABLE `candidate_expoperating` (
  `candidateId` varchar(65) NOT NULL,
  `expOperatingId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `candidate_otherlicence`
--

CREATE TABLE `candidate_otherlicence` (
  `othrId` int(11) NOT NULL,
  `candidateId` varchar(65) DEFAULT NULL,
  `otherLicenceId` int(11) DEFAULT NULL,
  `axiomno` int(11) NOT NULL DEFAULT 0,
  `attributeCode` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `candidate_position`
--

CREATE TABLE `candidate_position` (
  `positionid` bigint(20) NOT NULL,
  `positionName` varchar(85) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `candidate_safetygear`
--

CREATE TABLE `candidate_safetygear` (
  `candidateId` varchar(65) NOT NULL,
  `safetyGearId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `candidate_shiftavailable`
--

CREATE TABLE `candidate_shiftavailable` (
  `candidateId` varchar(65) NOT NULL,
  `shiftAvailableId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `candidate_superfund`
--

CREATE TABLE `candidate_superfund` (
  `candidateId` varchar(85) NOT NULL,
  `transCode` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `candidate_taxcode`
--

CREATE TABLE `candidate_taxcode` (
  `candidateId` varchar(85) NOT NULL,
  `taxcode` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carpool`
--

CREATE TABLE `carpool` (
  `id` bigint(20) NOT NULL,
  `carPoolCode` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `casual_login_monitor`
--

CREATE TABLE `casual_login_monitor` (
  `monitorIdPrimary` int(11) NOT NULL,
  `username` varchar(65) NOT NULL,
  `candidateId` varchar(85) NOT NULL,
  `actionTime` datetime NOT NULL,
  `status` varchar(65) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `check_log`
--

CREATE TABLE `check_log` (
  `id` int(11) NOT NULL,
  `candidateId` varchar(100) NOT NULL,
  `shiftId` int(11) NOT NULL,
  `candidateNo` int(11) NOT NULL,
  `checkInDate` datetime NOT NULL,
  `checkInLatitute` varchar(30) NOT NULL,
  `checkInLangitute` varchar(30) NOT NULL,
  `checkinAddress` varchar(255) NOT NULL,
  `checkOutDate` datetime NOT NULL,
  `checkOutLatitute` varchar(30) NOT NULL,
  `checkOutLangitute` varchar(30) NOT NULL,
  `checkOutAddress` varchar(255) NOT NULL,
  `supeviserId` int(11) NOT NULL,
  `confirmed` varchar(1) NOT NULL DEFAULT 'N',
  `actualWorkTime` float(5,2) NOT NULL DEFAULT 0.00,
  `confirmCheckInTime` datetime NOT NULL,
  `confirmCheckOutTime` datetime NOT NULL,
  `confirmedTime` float(5,2) NOT NULL DEFAULT 0.00,
  `workBreak` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ch_users`
--

CREATE TABLE `ch_users` (
  `user_id` bigint(20) NOT NULL,
  `username` varchar(65) NOT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `password` text NOT NULL,
  `email` varchar(65) NOT NULL,
  `type_login` varchar(65) NOT NULL,
  `avatar_path` varchar(100) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `verification_code` varchar(255) DEFAULT NULL,
  `email_verified_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client`
--

CREATE TABLE `client` (
  `clientId` bigint(20) NOT NULL,
  `clientCode` varchar(25) DEFAULT NULL,
  `industryId` bigint(20) NOT NULL DEFAULT 1,
  `industrySector` varchar(255) DEFAULT NULL,
  `tandaLocId` int(11) DEFAULT NULL,
  `client` varchar(200) NOT NULL,
  `clientAddress` text NOT NULL,
  `street_number` varchar(25) DEFAULT NULL,
  `street_name` varchar(200) DEFAULT NULL,
  `suburb` varchar(45) DEFAULT NULL,
  `state` varchar(200) DEFAULT NULL,
  `postcode` int(11) NOT NULL DEFAULT 0,
  `clientReference` varchar(45) DEFAULT NULL,
  `clientNote` text DEFAULT NULL,
  `latitude` varchar(100) DEFAULT NULL,
  `longitude` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `altPhone` varchar(15) DEFAULT NULL,
  `fax` varchar(15) DEFAULT NULL,
  `abn` varchar(16) DEFAULT NULL,
  `classification` varchar(65) DEFAULT NULL,
  `rating` varchar(45) DEFAULT NULL,
  `accountManager` varchar(65) DEFAULT NULL,
  `noOfCasuals` int(11) NOT NULL DEFAULT 0,
  `terms` int(11) DEFAULT NULL,
  `salesman` varchar(65) DEFAULT NULL,
  `invoiceType` varchar(45) DEFAULT NULL,
  `paymentMethod` varchar(25) DEFAULT NULL,
  `paymentThreshold` varchar(15) DEFAULT NULL,
  `gstPayable` varchar(15) DEFAULT NULL,
  `termsOfBusinessSigned` varchar(65) DEFAULT NULL,
  `payrollTaxSigned` varchar(65) DEFAULT NULL,
  `payrolltax` decimal(10,2) DEFAULT 0.00,
  `workcover` decimal(10,2) DEFAULT 0.00,
  `super_percentage` decimal(10,2) NOT NULL DEFAULT 0.00,
  `mhws` decimal(10,2) NOT NULL DEFAULT 0.00,
  `wic` varchar(255) DEFAULT NULL,
  `clientStatus` varchar(45) NOT NULL DEFAULT 'INACTIVE',
  `auditStatus` int(5) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_auditlog`
--

CREATE TABLE `client_auditlog` (
  `id` bigint(20) NOT NULL,
  `clientId` varchar(85) NOT NULL,
  `auditStatus` int(5) NOT NULL,
  `chandlerUser` varchar(100) NOT NULL,
  `auditedTime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_audit_check_data`
--

CREATE TABLE `client_audit_check_data` (
  `id` bigint(20) NOT NULL,
  `chkType` int(11) NOT NULL,
  `status` varchar(20) NOT NULL,
  `consultant` varchar(150) NOT NULL,
  `checked_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `jobOrderNotify` int(11) NOT NULL DEFAULT 0,
  `payroll_status` varchar(20) DEFAULT NULL,
  `payroll_officer` varchar(150) DEFAULT NULL,
  `verified_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `clientId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_audit_check_list`
--

CREATE TABLE `client_audit_check_list` (
  `id` int(11) NOT NULL,
  `description` varchar(150) NOT NULL,
  `sort` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_document`
--

CREATE TABLE `client_document` (
  `docId` int(11) NOT NULL,
  `clientId` bigint(20) NOT NULL,
  `docDesc` varchar(100) DEFAULT NULL,
  `fileName` varchar(255) NOT NULL,
  `filePath` varchar(255) NOT NULL,
  `clientDocNote` text NOT NULL,
  `createdDate` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_email`
--

CREATE TABLE `client_email` (
  `id` bigint(20) NOT NULL,
  `clientId` bigint(20) NOT NULL,
  `email` varchar(150) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_position`
--

CREATE TABLE `client_position` (
  `clientPosId` int(11) NOT NULL,
  `clientId` int(11) NOT NULL,
  `stateId` int(11) NOT NULL,
  `deptId` int(11) NOT NULL,
  `posId` int(11) NOT NULL,
  `job_description` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_summary`
--

CREATE TABLE `client_summary` (
  `id` bigint(20) NOT NULL,
  `clientId` bigint(20) NOT NULL,
  `payUnits` varchar(20) NOT NULL,
  `payAmount` decimal(10,2) NOT NULL,
  `billUnits` varchar(20) NOT NULL,
  `billAmount` decimal(10,2) NOT NULL,
  `tax` decimal(10,2) NOT NULL,
  `net` decimal(10,2) NOT NULL,
  `gross` decimal(10,2) NOT NULL,
  `superUnits` varchar(20) NOT NULL,
  `superAmount` decimal(10,2) NOT NULL,
  `weekendingDate` date NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_survey_log`
--

CREATE TABLE `client_survey_log` (
  `id` bigint(20) NOT NULL,
  `client_id` bigint(20) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `client_email` varchar(255) NOT NULL,
  `client_position` varchar(255) NOT NULL,
  `sent_time` datetime DEFAULT NULL,
  `received_time` datetime DEFAULT NULL,
  `fileName` varchar(255) DEFAULT NULL,
  `filePath` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_terms`
--

CREATE TABLE `client_terms` (
  `termId` int(11) NOT NULL,
  `days` int(11) NOT NULL,
  `description` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `client_visits`
--

CREATE TABLE `client_visits` (
  `id` bigint(20) NOT NULL,
  `consultant_id` bigint(20) NOT NULL,
  `client_visit_date` date NOT NULL,
  `client_id` bigint(20) NOT NULL,
  `notes` longtext NOT NULL,
  `issues` longtext DEFAULT NULL,
  `follow_up_date` date DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `color_category`
--

CREATE TABLE `color_category` (
  `catid` int(11) NOT NULL,
  `catcolor` varchar(65) NOT NULL,
  `category` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `companyId` int(11) NOT NULL,
  `companyName` varchar(200) NOT NULL,
  `companyAddress` text NOT NULL,
  `addressLine1` text DEFAULT NULL,
  `addressLine2` text DEFAULT NULL,
  `postCode` varchar(25) DEFAULT NULL,
  `state` varchar(15) DEFAULT NULL,
  `suburb` varchar(45) DEFAULT NULL,
  `telephone` varchar(20) NOT NULL,
  `fax` varchar(20) NOT NULL,
  `companyDesc` text NOT NULL,
  `companyLogoPath` varchar(150) DEFAULT NULL,
  `website` varchar(100) NOT NULL,
  `remittanceEmail` varchar(150) NOT NULL,
  `abn` bigint(20) NOT NULL,
  `acn` bigint(20) DEFAULT NULL,
  `companyNote` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `companybankaccount`
--

CREATE TABLE `companybankaccount` (
  `accId` int(11) NOT NULL,
  `companyId` int(11) NOT NULL,
  `accountName` varchar(100) NOT NULL,
  `accountNumber` varchar(65) NOT NULL,
  `bsb` varchar(15) NOT NULL,
  `userName` varchar(85) NOT NULL,
  `userCode` varchar(85) NOT NULL,
  `tradeCode` varchar(85) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `consultant`
--

CREATE TABLE `consultant` (
  `consultantId` int(11) NOT NULL,
  `username` varchar(65) NOT NULL,
  `name` varchar(65) DEFAULT NULL,
  `workAddress` text DEFAULT NULL,
  `workPhoneNo` varchar(45) DEFAULT NULL,
  `mobileNo` varchar(45) DEFAULT NULL,
  `email` varchar(45) DEFAULT NULL,
  `status` int(5) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `corporate_bank_account`
--

CREATE TABLE `corporate_bank_account` (
  `accId` int(11) NOT NULL,
  `accountName` varchar(85) NOT NULL,
  `accountNumber` varchar(65) NOT NULL,
  `bsb` varchar(15) NOT NULL,
  `userName` varchar(85) NOT NULL,
  `userCode` varchar(85) NOT NULL,
  `tradeCode` varchar(85) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `customerId` varchar(65) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `department`
--

CREATE TABLE `department` (
  `deptId` int(11) NOT NULL,
  `clientId` int(11) NOT NULL,
  `stateId` int(11) NOT NULL,
  `tandaDeptId` int(11) DEFAULT NULL,
  `department` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `note` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `diarynote`
--

CREATE TABLE `diarynote` (
  `diaryNoteId` int(11) NOT NULL,
  `noteType` varchar(65) DEFAULT NULL,
  `firstName` varchar(65) DEFAULT NULL,
  `lastName` varchar(65) DEFAULT NULL,
  `candidateId` varchar(85) DEFAULT NULL,
  `axiomno` int(11) DEFAULT 0,
  `activityId` int(11) DEFAULT 0,
  `activityType` varchar(6) DEFAULT NULL,
  `priorityId` int(11) NOT NULL,
  `consultantId` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `todoDate` datetime DEFAULT NULL,
  `todoTime` varchar(100) DEFAULT NULL,
  `todoDuration` varchar(100) DEFAULT NULL,
  `todoNote` longtext DEFAULT NULL,
  `actionDate` datetime DEFAULT NULL,
  `actionTime` varchar(100) DEFAULT NULL,
  `actionDuration` varchar(100) DEFAULT NULL,
  `actionNote` longtext DEFAULT NULL,
  `createdDate` datetime DEFAULT NULL,
  `modifiedDate` datetime DEFAULT NULL,
  `createdBy` int(11) NOT NULL DEFAULT 0,
  `lastmodBy` int(11) NOT NULL DEFAULT 0,
  `loginAccount` varchar(65) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `document_type`
--

CREATE TABLE `document_type` (
  `typeId` int(11) NOT NULL,
  `typeCode` varchar(35) NOT NULL,
  `typeLabel` varchar(85) NOT NULL,
  `typeDescription` text NOT NULL,
  `acc_check` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `emailaccount`
--

CREATE TABLE `emailaccount` (
  `eaId` int(11) NOT NULL,
  `email` varchar(85) NOT NULL,
  `accountName` varchar(85) NOT NULL,
  `tbl_email` varchar(85) NOT NULL,
  `tbl_attachment` varchar(85) NOT NULL,
  `tbl_attachmentpath` varchar(85) NOT NULL,
  `mailContent` text DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `empdupe_list`
--

CREATE TABLE `empdupe_list` (
  `id` bigint(20) NOT NULL,
  `filePath` varchar(65) NOT NULL,
  `fileDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_allocation`
--

CREATE TABLE `employee_allocation` (
  `allocationId` int(11) NOT NULL,
  `candidateId` varchar(85) NOT NULL,
  `clientId` int(11) NOT NULL,
  `stateId` int(11) NOT NULL,
  `deptId` int(11) NOT NULL,
  `priorityId` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `ohs_sent_time` datetime DEFAULT NULL,
  `ohsCheckStatus` varchar(35) NOT NULL DEFAULT 'NOT CHECKED',
  `ohsCheckedBy` varchar(65) DEFAULT NULL,
  `ohsCheckedTime` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_availability`
--

CREATE TABLE `employee_availability` (
  `eid` int(11) NOT NULL,
  `candidateId` varchar(85) NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_bank_account`
--

CREATE TABLE `employee_bank_account` (
  `empAccId` int(11) NOT NULL,
  `candidateId` varchar(85) NOT NULL,
  `bankName` varchar(255) DEFAULT NULL,
  `accountName` varchar(65) NOT NULL,
  `accountNumber` varchar(65) NOT NULL,
  `bsb` varchar(15) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_carpool`
--

CREATE TABLE `employee_carpool` (
  `empPoolId` bigint(20) NOT NULL,
  `candidateId` varchar(85) NOT NULL,
  `carPoolId` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_covid_answers`
--

CREATE TABLE `employee_covid_answers` (
  `id` bigint(20) NOT NULL,
  `candidateId` varchar(85) NOT NULL,
  `shiftId` bigint(20) NOT NULL,
  `shiftDate` date NOT NULL,
  `clientId` int(11) NOT NULL,
  `answer` varchar(100) NOT NULL,
  `answer_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_deductioncode`
--

CREATE TABLE `employee_deductioncode` (
  `id` int(11) NOT NULL,
  `candidateId` varchar(85) NOT NULL,
  `transCode` int(11) NOT NULL,
  `weekendingDate` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_positions`
--

CREATE TABLE `employee_positions` (
  `emp_pos_id` int(11) NOT NULL,
  `candidateId` varchar(85) NOT NULL,
  `positionid` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_signature`
--

CREATE TABLE `employee_signature` (
  `id` bigint(20) NOT NULL,
  `candidateId` varchar(85) NOT NULL,
  `signature` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_visatype`
--

CREATE TABLE `employee_visatype` (
  `empVisaTypeId` bigint(20) NOT NULL,
  `candidateId` varchar(85) NOT NULL,
  `visaTypeId` bigint(20) NOT NULL,
  `expiryDate` varchar(255) DEFAULT NULL,
  `workHourRestriction` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` bigint(20) NOT NULL,
  `start` datetime DEFAULT NULL,
  `end` datetime DEFAULT NULL,
  `title` text NOT NULL,
  `photo` longblob DEFAULT NULL,
  `uid` varchar(100) DEFAULT NULL,
  `consultant_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `events_delete`
--

CREATE TABLE `events_delete` (
  `id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expoperating`
--

CREATE TABLE `expoperating` (
  `expOperatingId` int(11) NOT NULL,
  `expOperating` varchar(65) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `finance_check_data`
--

CREATE TABLE `finance_check_data` (
  `id` bigint(20) NOT NULL,
  `candidateId` varchar(85) NOT NULL,
  `chkType` int(11) NOT NULL,
  `status` varchar(20) NOT NULL,
  `accounts_user` varchar(150) NOT NULL,
  `checked_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `finance_check_list`
--

CREATE TABLE `finance_check_list` (
  `id` int(11) NOT NULL,
  `description` varchar(150) NOT NULL,
  `sort` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `firebase_reminder_shifts`
--

CREATE TABLE `firebase_reminder_shifts` (
  `id` int(11) NOT NULL,
  `shiftId` bigint(20) NOT NULL,
  `status` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `firebase_shifts`
--

CREATE TABLE `firebase_shifts` (
  `id` bigint(20) NOT NULL,
  `shiftId` bigint(20) NOT NULL,
  `status` varchar(25) NOT NULL DEFAULT 'DEFAULT'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hire_rates`
--

CREATE TABLE `hire_rates` (
  `id` bigint(20) NOT NULL,
  `client` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `award` varchar(255) NOT NULL,
  `client_email` varchar(200) NOT NULL,
  `rates_file` varchar(255) NOT NULL,
  `sent_date` datetime NOT NULL,
  `ip` varchar(65) DEFAULT NULL,
  `signed_rate_file` varchar(255) DEFAULT NULL,
  `submit_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `inbox_reference`
--

CREATE TABLE `inbox_reference` (
  `id` bigint(20) NOT NULL,
  `reference` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `indattachment`
--

CREATE TABLE `indattachment` (
  `messageid` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id` bigint(20) UNSIGNED NOT NULL,
  `filepath` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `contents` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `indattachmentpath`
--

CREATE TABLE `indattachmentpath` (
  `indId` int(11) NOT NULL,
  `messageid` varchar(200) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `indeedresume`
--

CREATE TABLE `indeedresume` (
  `autoid` bigint(20) NOT NULL,
  `messageid` varchar(255) NOT NULL,
  `uid` int(11) NOT NULL,
  `msgno` int(11) NOT NULL,
  `mailfrom` text NOT NULL,
  `mailto` text DEFAULT NULL,
  `subject` text NOT NULL,
  `msgbody` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `reference` varchar(255) DEFAULT 'UNKNOWN',
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `indeed_reference`
--

CREATE TABLE `indeed_reference` (
  `id` bigint(20) NOT NULL,
  `reference` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `industry`
--

CREATE TABLE `industry` (
  `industryId` bigint(20) NOT NULL,
  `industryName` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `interviewnotes`
--

CREATE TABLE `interviewnotes` (
  `candidateId` varchar(85) NOT NULL,
  `intro` varchar(10) NOT NULL,
  `formsCompleted` varchar(10) NOT NULL,
  `fit2work` varchar(15) DEFAULT NULL,
  `policeHistory` varchar(15) DEFAULT NULL,
  `wrkhistory` text NOT NULL,
  `forklift` varchar(10) NOT NULL,
  `rf` varchar(10) NOT NULL,
  `powertools` varchar(10) NOT NULL,
  `containers` varchar(10) NOT NULL,
  `yleaving` text NOT NULL,
  `ohsrules` text NOT NULL,
  `perftask` text NOT NULL,
  `strengths` text NOT NULL,
  `teamwork` text NOT NULL,
  `eyetest` text NOT NULL,
  `readtest` text NOT NULL,
  `rating` text NOT NULL,
  `consultantId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice`
--

CREATE TABLE `invoice` (
  `invoice_no` bigint(20) NOT NULL,
  `invoiceId` varchar(100) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci ROW_FORMAT=FIXED;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_addition`
--

CREATE TABLE `invoice_addition` (
  `id` bigint(20) NOT NULL,
  `clientId` bigint(20) NOT NULL,
  `weekendingDate` date NOT NULL,
  `description` varchar(150) NOT NULL,
  `units` varchar(20) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `invoiceNo` varchar(100) DEFAULT NULL,
  `empId` varchar(85) DEFAULT NULL,
  `jobCode` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_detail`
--

CREATE TABLE `invoice_detail` (
  `creationNo` bigint(20) NOT NULL,
  `invoiceId` varchar(100) NOT NULL,
  `invoiceDate` date DEFAULT NULL,
  `weekendingDate` varchar(100) DEFAULT NULL,
  `clientId` int(11) NOT NULL,
  `netAmount` decimal(10,2) DEFAULT NULL,
  `gst` decimal(10,2) DEFAULT NULL,
  `gross` decimal(10,2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoice_path`
--

CREATE TABLE `invoice_path` (
  `id` bigint(20) NOT NULL,
  `weekendingDate` date NOT NULL,
  `clientcode` varchar(45) DEFAULT NULL,
  `path` varchar(200) NOT NULL,
  `fileName` varchar(150) NOT NULL,
  `invoice_date` date DEFAULT NULL,
  `generatedDate` datetime NOT NULL DEFAULT current_timestamp(),
  `sentDate` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_master`
--

CREATE TABLE `item_master` (
  `id` bigint(20) NOT NULL,
  `itemType` int(11) NOT NULL,
  `item_desc` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jbattachment`
--

CREATE TABLE `jbattachment` (
  `messageid` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id` bigint(20) UNSIGNED NOT NULL,
  `filepath` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `contents` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jbattachmentpath`
--

CREATE TABLE `jbattachmentpath` (
  `krId` int(11) NOT NULL,
  `messageid` varchar(200) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jb_resume`
--

CREATE TABLE `jb_resume` (
  `id` bigint(20) NOT NULL,
  `applied_position` varchar(255) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `gender` varchar(45) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `suburb` varchar(255) NOT NULL,
  `experience` text DEFAULT NULL,
  `drivers_licence` varchar(50) DEFAULT NULL,
  `own_car` varchar(25) DEFAULT NULL,
  `work_with_children` varchar(25) DEFAULT NULL,
  `police_check` varchar(25) DEFAULT NULL,
  `forklift_licence` varchar(25) DEFAULT NULL,
  `white_card` varchar(25) DEFAULT NULL,
  `mr_licence` varchar(25) DEFAULT NULL,
  `work_rights` varchar(65) DEFAULT NULL,
  `resume_path` varchar(255) NOT NULL,
  `photo_path` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobboard_mail_color_category`
--

CREATE TABLE `jobboard_mail_color_category` (
  `mailcatid` int(11) NOT NULL,
  `catid` int(11) NOT NULL,
  `autoid` int(11) NOT NULL,
  `username` varchar(65) NOT NULL,
  `modifiedDate` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobboard_resume`
--

CREATE TABLE `jobboard_resume` (
  `autoid` int(11) NOT NULL,
  `messageid` varchar(255) NOT NULL,
  `uid` int(11) NOT NULL,
  `msgno` int(11) NOT NULL,
  `mailfrom` text NOT NULL,
  `mailto` text NOT NULL,
  `subject` text NOT NULL,
  `msgbody` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `reference` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobcode`
--

CREATE TABLE `jobcode` (
  `jobNo` bigint(20) NOT NULL,
  `jobCode` varchar(100) NOT NULL,
  `clientId` int(11) NOT NULL,
  `positionid` int(11) NOT NULL,
  `deptId` int(11) NOT NULL DEFAULT 0,
  `awardId` bigint(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `joborder`
--

CREATE TABLE `joborder` (
  `id` bigint(20) NOT NULL,
  `jobOrderId` varchar(255) NOT NULL,
  `clientId` bigint(20) NOT NULL,
  `stateId` bigint(20) NOT NULL,
  `deptId` bigint(20) NOT NULL,
  `note` text NOT NULL,
  `numCasuals` int(11) NOT NULL,
  `creator` int(11) NOT NULL,
  `status` varchar(65) DEFAULT NULL,
  `orderTime` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `noteUpdater` int(11) NOT NULL,
  `additionalNote` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `joborderlog`
--

CREATE TABLE `joborderlog` (
  `id` bigint(20) NOT NULL,
  `jobOrderId` varchar(255) NOT NULL,
  `consultantId` int(11) NOT NULL,
  `status` varchar(65) NOT NULL,
  `actionedTime` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `joborder_documents`
--

CREATE TABLE `joborder_documents` (
  `id` int(11) NOT NULL,
  `joborderid` varchar(200) NOT NULL,
  `consultantId` int(11) NOT NULL,
  `fileName` varchar(200) NOT NULL,
  `filePath` varchar(255) NOT NULL,
  `uploaded_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_board_reference`
--

CREATE TABLE `job_board_reference` (
  `id` bigint(20) NOT NULL,
  `reference` varchar(255) NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_detail`
--

CREATE TABLE `job_detail` (
  `jobDetailId` int(11) NOT NULL,
  `jobCode` varchar(100) NOT NULL,
  `clientId` int(11) NOT NULL,
  `clientCode` varchar(25) NOT NULL,
  `clientName` varchar(65) NOT NULL,
  `contactFirstName` varchar(65) DEFAULT NULL,
  `contactLastName` varchar(65) DEFAULT NULL,
  `accountManager` varchar(75) DEFAULT NULL,
  `profitCentre` int(11) DEFAULT NULL,
  `description` varchar(150) DEFAULT NULL,
  `startDate` date DEFAULT '1970-01-01',
  `endDate` date DEFAULT '1970-01-01',
  `custOrderNo` varchar(45) DEFAULT NULL,
  `reportingTo` varchar(45) DEFAULT NULL,
  `classification` varchar(65) DEFAULT NULL,
  `rateGroup` varchar(45) DEFAULT NULL,
  `shiftType` varchar(65) DEFAULT NULL,
  `workersComp` varchar(65) DEFAULT NULL,
  `payrollName` varchar(65) DEFAULT NULL,
  `invoiceTo` varchar(200) DEFAULT NULL,
  `workAddress` varchar(200) DEFAULT NULL,
  `payroll` varchar(65) DEFAULT NULL,
  `superLevy` varchar(65) DEFAULT NULL,
  `other` varchar(65) DEFAULT NULL,
  `wk_offset` varchar(65) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_order`
--

CREATE TABLE `job_order` (
  `job_id` bigint(20) NOT NULL,
  `client_id` int(11) NOT NULL,
  `dept_id` int(11) NOT NULL,
  `state_id` int(11) NOT NULL,
  `pos_id` int(11) NOT NULL,
  `order_date` date NOT NULL,
  `start_time` varchar(20) NOT NULL,
  `order_qty` int(11) NOT NULL,
  `male_qty` int(11) DEFAULT NULL,
  `female_qty` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_order_log`
--

CREATE TABLE `job_order_log` (
  `log_id` bigint(20) NOT NULL,
  `job_id` bigint(20) NOT NULL,
  `order_date` date NOT NULL,
  `order_qty` int(11) NOT NULL,
  `client` varchar(255) NOT NULL,
  `department` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `position` varchar(255) NOT NULL,
  `start_time` varchar(255) NOT NULL DEFAULT '00:00',
  `male_qty` int(11) NOT NULL,
  `female_qty` int(11) NOT NULL,
  `logged_in_user` varchar(255) NOT NULL,
  `log_action` varchar(255) NOT NULL,
  `log_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login_monitor`
--

CREATE TABLE `login_monitor` (
  `monitorId` int(11) NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `username` varchar(65) NOT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `remote_ip` varchar(100) DEFAULT NULL,
  `actionTime` datetime NOT NULL,
  `status` varchar(65) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mailcomment`
--

CREATE TABLE `mailcomment` (
  `commentId` bigint(20) NOT NULL,
  `autoId` int(11) NOT NULL,
  `comment` text NOT NULL,
  `username` varchar(65) NOT NULL,
  `modifiedDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mail_color_category`
--

CREATE TABLE `mail_color_category` (
  `mailcatid` int(11) NOT NULL,
  `catid` int(11) NOT NULL,
  `autoid` int(11) NOT NULL,
  `username` varchar(65) NOT NULL,
  `modifiedDate` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mail_view_items`
--

CREATE TABLE `mail_view_items` (
  `id` bigint(20) NOT NULL,
  `name` varchar(200) NOT NULL,
  `text` varchar(200) NOT NULL,
  `link` varchar(200) NOT NULL,
  `parent_id` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mediadata`
--

CREATE TABLE `mediadata` (
  `id` int(11) NOT NULL,
  `url` varchar(225) NOT NULL,
  `path` varchar(150) NOT NULL,
  `cid` varchar(150) NOT NULL,
  `filename` varchar(150) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifier_log`
--

CREATE TABLE `notifier_log` (
  `notifierId` int(11) NOT NULL,
  `diaryNoteId` int(11) NOT NULL,
  `notifyStatus` int(11) NOT NULL DEFAULT 0,
  `logDate` datetime NOT NULL DEFAULT current_timestamp(),
  `logNote` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `otherlicence`
--

CREATE TABLE `otherlicence` (
  `otherLicenceId` int(11) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `otherLicenceType` varchar(65) NOT NULL,
  `deptId` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `paycategory`
--

CREATE TABLE `paycategory` (
  `payCatId` bigint(20) NOT NULL,
  `payCatCode` varchar(100) NOT NULL,
  `payCategory` text NOT NULL,
  `sorting` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `paygmaillist`
--

CREATE TABLE `paygmaillist` (
  `id` bigint(20) NOT NULL,
  `empId` varchar(85) NOT NULL,
  `email` varchar(45) NOT NULL,
  `filepath` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll_clock_in_out_log`
--

CREATE TABLE `payroll_clock_in_out_log` (
  `id` bigint(20) NOT NULL,
  `shift_id` bigint(20) NOT NULL,
  `log_desc` longtext DEFAULT NULL,
  `date_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll_name_detail`
--

CREATE TABLE `payroll_name_detail` (
  `id` int(11) NOT NULL,
  `payrollName` varchar(65) NOT NULL,
  `profitCentre` int(11) NOT NULL,
  `yearStartDate` date DEFAULT NULL,
  `yearEndDate` date DEFAULT NULL,
  `frequency` varchar(45) NOT NULL,
  `periodEndDay` int(45) NOT NULL,
  `payslipMessage` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll_reports`
--

CREATE TABLE `payroll_reports` (
  `id` bigint(20) NOT NULL,
  `payrunId` bigint(20) NOT NULL,
  `weekendingDate` date NOT NULL,
  `filePath` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci PACK_KEYS=0;

-- --------------------------------------------------------

--
-- Table structure for table `payrule`
--

CREATE TABLE `payrule` (
  `payruleId` bigint(20) NOT NULL,
  `jobCode` varchar(25) NOT NULL,
  `payAwrdCode` varchar(25) NOT NULL,
  `payAwrdDesc` text DEFAULT NULL,
  `avgNormalHrs` varchar(20) NOT NULL,
  `spreadStart` varchar(20) NOT NULL,
  `spreadEnd` varchar(20) NOT NULL,
  `spreadDuration` varchar(20) NOT NULL,
  `firstEightHours` varchar(20) NOT NULL DEFAULT '8:00',
  `minimumHrs` varchar(20) NOT NULL,
  `earlyMorningStartTime` varchar(20) DEFAULT NULL,
  `earlyMorningEndTime` varchar(20) DEFAULT NULL,
  `dayShiftStartTime` varchar(20) DEFAULT NULL,
  `dayShiftEndTime` varchar(20) DEFAULT NULL,
  `afternoonShiftStartTime` varchar(20) DEFAULT NULL,
  `afternoonShiftEndTime` varchar(20) DEFAULT NULL,
  `nightShiftStartTime` varchar(20) DEFAULT NULL,
  `nightShiftEndTime` varchar(20) DEFAULT NULL,
  `overtime` varchar(20) DEFAULT NULL,
  `overtimeAfterHrs` varchar(20) DEFAULT NULL,
  `overtimeSatAfterHrs` varchar(20) DEFAULT NULL,
  `overtimeSunAfterHrs` varchar(20) DEFAULT NULL,
  `doubletime` varchar(20) DEFAULT NULL,
  `saturday` varchar(20) DEFAULT NULL,
  `sunday` varchar(20) DEFAULT NULL,
  `publicHoliday` varchar(20) DEFAULT NULL,
  `overtimeRule` varchar(45) DEFAULT NULL,
  `mealBreakLimit` varchar(20) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payrun`
--

CREATE TABLE `payrun` (
  `payrunId` bigint(20) NOT NULL,
  `payrollName` varchar(200) NOT NULL,
  `invoiceDate` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payrundetails`
--

CREATE TABLE `payrundetails` (
  `id` bigint(20) NOT NULL,
  `payrunId` bigint(20) NOT NULL,
  `weekendingDate` date NOT NULL,
  `candidateId` varchar(85) NOT NULL,
  `clientId` int(11) NOT NULL,
  `positionId` int(11) NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `itemType` int(11) NOT NULL,
  `jobCode` varchar(15) DEFAULT NULL,
  `transCode` int(11) DEFAULT NULL,
  `units` varchar(20) DEFAULT NULL,
  `rate` decimal(10,2) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `chargeRate` varchar(20) DEFAULT NULL,
  `chargeAmount` decimal(10,2) DEFAULT NULL,
  `gross` decimal(10,2) DEFAULT NULL,
  `net` decimal(10,2) DEFAULT NULL,
  `paygTax` decimal(10,2) DEFAULT NULL,
  `deduction` decimal(10,2) DEFAULT NULL,
  `superAnnuation` decimal(10,2) DEFAULT NULL,
  `payReversalDate` datetime DEFAULT NULL,
  `reversedBy` varchar(85) DEFAULT NULL,
  `status` varchar(45) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payslip_info`
--

CREATE TABLE `payslip_info` (
  `id` bigint(20) NOT NULL,
  `candidateId` varchar(85) NOT NULL,
  `payrunId` bigint(20) NOT NULL,
  `weekendingDate` date NOT NULL,
  `filePath` varchar(200) NOT NULL,
  `payDate` date DEFAULT NULL,
  `payPeriodStart` date DEFAULT NULL,
  `payPeriodEnd` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `person`
--

CREATE TABLE `person` (
  `id` bigint(20) NOT NULL,
  `firstName` varchar(100) NOT NULL,
  `lastName` varchar(100) NOT NULL,
  `gender` varchar(25) NOT NULL,
  `phoneNumber` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `placement_info`
--

CREATE TABLE `placement_info` (
  `id` bigint(20) NOT NULL,
  `placement_id` bigint(20) NOT NULL,
  `jobadder_id` bigint(20) DEFAULT NULL,
  `candidate_name` varchar(255) DEFAULT NULL,
  `candidate_mobile` varchar(20) DEFAULT NULL,
  `candidate_email` varchar(255) DEFAULT NULL,
  `candidate_dob` varchar(150) DEFAULT NULL,
  `job_detail_name` varchar(255) DEFAULT NULL,
  `job_title` varchar(255) DEFAULT NULL,
  `work_place_address` longtext DEFAULT NULL,
  `approver_name` varchar(255) DEFAULT NULL,
  `approver_email` varchar(255) DEFAULT NULL,
  `placement_period_type` varchar(255) DEFAULT NULL,
  `placement_period_start_date` varchar(255) DEFAULT NULL,
  `placement_period_end_date` varchar(255) DEFAULT NULL,
  `billing_name` varchar(255) DEFAULT NULL,
  `billing_email` varchar(255) DEFAULT NULL,
  `billing_address` longtext DEFAULT NULL,
  `billing_terms` varchar(150) DEFAULT NULL,
  `pay_rate` decimal(10,2) DEFAULT NULL,
  `charge_rate` decimal(10,2) DEFAULT NULL,
  `net_margin` decimal(10,2) DEFAULT NULL,
  `award` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `priority`
--

CREATE TABLE `priority` (
  `priorityId` int(11) NOT NULL,
  `priorityLevel` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profit_centre`
--

CREATE TABLE `profit_centre` (
  `id` int(11) NOT NULL,
  `centreName` varchar(100) NOT NULL,
  `clientId` int(11) NOT NULL,
  `address1` varchar(100) DEFAULT NULL,
  `address2` varchar(100) DEFAULT NULL,
  `address3` varchar(100) DEFAULT NULL,
  `stateId` int(11) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `manager` varchar(65) DEFAULT NULL,
  `taxCalc` varchar(65) DEFAULT NULL,
  `taxPercentage` varchar(10) DEFAULT NULL,
  `remittanceAddress` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `public_holiday`
--

CREATE TABLE `public_holiday` (
  `publicHolidayId` int(11) NOT NULL,
  `stateId` int(11) NOT NULL,
  `publicHolidayDate` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `questionnaire`
--

CREATE TABLE `questionnaire` (
  `id` bigint(20) NOT NULL,
  `candidateId` varchar(85) NOT NULL,
  `paidBasis` varchar(45) DEFAULT NULL,
  `taxClaim` varchar(10) DEFAULT NULL,
  `taxHelp` varchar(10) DEFAULT NULL,
  `taxResident` varchar(150) DEFAULT NULL,
  `jobActiveStatus` varchar(45) DEFAULT NULL,
  `jobActiveProvider` varchar(255) DEFAULT NULL,
  `workprocin` varchar(100) DEFAULT NULL,
  `emcName` varchar(255) DEFAULT NULL,
  `emcRelationship` varchar(255) DEFAULT NULL,
  `emcState` varchar(65) DEFAULT NULL,
  `emcMobile` varchar(15) DEFAULT NULL,
  `emcHomePhone` varchar(15) DEFAULT NULL,
  `referee1Name` varchar(255) DEFAULT NULL,
  `referee1CompanyName` varchar(255) DEFAULT NULL,
  `referee1Position` varchar(255) DEFAULT NULL,
  `referee1Relationship` varchar(255) DEFAULT NULL,
  `referee1Mobile` varchar(15) DEFAULT NULL,
  `referee2Name` varchar(255) DEFAULT NULL,
  `referee2CompanyName` varchar(255) DEFAULT NULL,
  `referee2Position` varchar(255) DEFAULT NULL,
  `referee2Relationship` varchar(255) DEFAULT NULL,
  `referee2Mobile` varchar(15) DEFAULT NULL,
  `superAccountName` varchar(255) DEFAULT NULL,
  `superFundName` varchar(255) DEFAULT NULL,
  `superFundAddress` text DEFAULT NULL,
  `superPhoneNo` varchar(15) DEFAULT NULL,
  `superWebsite` varchar(150) DEFAULT NULL,
  `superFundABN` varchar(20) DEFAULT NULL,
  `superFundUSI` varchar(45) DEFAULT NULL,
  `medicalCondition` varchar(10) DEFAULT NULL,
  `medConditionDesc` text DEFAULT NULL,
  `psycoCondition` varchar(10) DEFAULT NULL,
  `psycoConditionDesc` text DEFAULT NULL,
  `alergyCondition` varchar(10) DEFAULT NULL,
  `alergyConditionDesc` text DEFAULT NULL,
  `pregnantCondition` varchar(10) DEFAULT NULL,
  `shoulderCondition` varchar(10) DEFAULT NULL,
  `armCondition` varchar(10) DEFAULT NULL,
  `strainCondition` varchar(10) DEFAULT NULL,
  `epilepsyCondition` varchar(10) DEFAULT NULL,
  `hearingCondition` varchar(10) DEFAULT NULL,
  `stressCondition` varchar(10) DEFAULT NULL,
  `fatiqueCondition` varchar(10) DEFAULT NULL,
  `asthmaCondition` varchar(10) DEFAULT NULL,
  `arthritisCondition` varchar(10) DEFAULT NULL,
  `dizzinessCondition` varchar(10) DEFAULT NULL,
  `headCondition` varchar(10) DEFAULT NULL,
  `speechCondition` varchar(10) DEFAULT NULL,
  `backCondition` varchar(10) DEFAULT NULL,
  `kneeCondition` varchar(10) DEFAULT NULL,
  `persistentCondition` varchar(10) DEFAULT NULL,
  `skinCondition` varchar(10) DEFAULT NULL,
  `stomachStrains` varchar(10) DEFAULT NULL,
  `visionCondition` varchar(10) DEFAULT NULL,
  `boneCondition` varchar(10) DEFAULT NULL,
  `bloodCondition` varchar(10) DEFAULT NULL,
  `lungCondition` varchar(10) DEFAULT NULL,
  `surgeryInformation` varchar(10) DEFAULT NULL,
  `stomachCondition` varchar(10) DEFAULT NULL,
  `heartCondition` varchar(10) DEFAULT NULL,
  `infectiousCondition` varchar(10) DEFAULT NULL,
  `medicalTreatment` varchar(10) DEFAULT NULL,
  `medicalTreatmentDesc` text DEFAULT NULL,
  `drowsinessCondition` varchar(10) DEFAULT NULL,
  `drowsinessConditionDesc` text DEFAULT NULL,
  `chronicCondition` varchar(10) DEFAULT NULL,
  `chronicConditionDesc` text DEFAULT NULL,
  `workInjury` varchar(10) DEFAULT NULL,
  `workInjuryDesc` text DEFAULT NULL,
  `workCoverClaim` varchar(10) DEFAULT NULL,
  `crouchingCondition` varchar(10) DEFAULT NULL,
  `sittingCondition` varchar(10) DEFAULT NULL,
  `workShoulderHeight` varchar(10) DEFAULT NULL,
  `hearingConversation` varchar(10) DEFAULT NULL,
  `workAtHeights` varchar(10) DEFAULT NULL,
  `groundCondition` varchar(10) DEFAULT NULL,
  `handlingFood` varchar(10) DEFAULT NULL,
  `shiftWork` varchar(10) DEFAULT NULL,
  `standingMinutes` varchar(10) DEFAULT NULL,
  `liftingCondition` varchar(10) DEFAULT NULL,
  `grippingObjects` varchar(10) DEFAULT NULL,
  `repetitiveMovement` varchar(10) DEFAULT NULL,
  `walkingStairs` varchar(10) DEFAULT NULL,
  `handTools` varchar(10) DEFAULT NULL,
  `protectiveEquipment` varchar(10) DEFAULT NULL,
  `workHeights` varchar(10) DEFAULT NULL,
  `workConfinedSpaces` varchar(10) DEFAULT NULL,
  `workHotColdEnvironment` varchar(10) DEFAULT NULL,
  `superFundCheck` varchar(40) DEFAULT NULL,
  `policeCheck` varchar(10) DEFAULT NULL,
  `agreePoliceCheckCost` varchar(45) DEFAULT NULL,
  `statOccupation` varchar(255) DEFAULT NULL,
  `crimeCheck` varchar(10) DEFAULT NULL,
  `crimeDate1` varchar(25) DEFAULT NULL,
  `crime1` text DEFAULT NULL,
  `crimeDate2` varchar(25) DEFAULT NULL,
  `crime2` text DEFAULT NULL,
  `optionChk` varchar(10) DEFAULT NULL,
  `neverConvicted` varchar(5) DEFAULT NULL,
  `neverImprisonment` varchar(5) DEFAULT NULL,
  `pb_suburb` varchar(150) DEFAULT NULL,
  `pb_state` varchar(150) DEFAULT NULL,
  `pb_country` varchar(150) DEFAULT NULL,
  `fw_first_name` varchar(150) DEFAULT NULL,
  `fw_middle_name` varchar(150) DEFAULT NULL,
  `fw_last_name` varchar(150) DEFAULT NULL,
  `fw_unit_no1` varchar(10) DEFAULT NULL,
  `fw_street_number1` varchar(25) DEFAULT NULL,
  `fw_street_name1` varchar(150) DEFAULT NULL,
  `fw_suburb1` varchar(150) DEFAULT NULL,
  `fw_state1` varchar(150) DEFAULT NULL,
  `fw_postcode1` varchar(10) DEFAULT NULL,
  `fw_country1` varchar(150) DEFAULT NULL,
  `fw_unit_no2` varchar(10) DEFAULT NULL,
  `fw_street_number2` varchar(25) DEFAULT NULL,
  `fw_street_name2` varchar(150) DEFAULT NULL,
  `fw_suburb2` varchar(150) DEFAULT NULL,
  `fw_state2` varchar(150) DEFAULT NULL,
  `fw_postcode2` varchar(10) DEFAULT NULL,
  `fw_country2` varchar(150) DEFAULT NULL,
  `fw_licence` varchar(100) DEFAULT NULL,
  `fw_licence_state` varchar(100) DEFAULT NULL,
  `fw_passport_no` varchar(50) DEFAULT NULL,
  `fw_passport_country` varchar(100) DEFAULT NULL,
  `fw_type` varchar(50) DEFAULT NULL,
  `fw_passport_type` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ratecard`
--

CREATE TABLE `ratecard` (
  `rateCardId` int(11) NOT NULL,
  `clientId` int(11) NOT NULL,
  `positionId` int(11) NOT NULL,
  `jobCode` varchar(100) NOT NULL,
  `payCatCode` varchar(100) NOT NULL,
  `payRate` decimal(10,2) DEFAULT NULL,
  `chargeRate` decimal(10,2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ratecard_new_financial_year`
--

CREATE TABLE `ratecard_new_financial_year` (
  `rateCardId` int(11) NOT NULL,
  `clientId` int(11) NOT NULL,
  `positionId` int(11) NOT NULL,
  `jobCode` varchar(100) NOT NULL,
  `payCatCode` varchar(100) NOT NULL,
  `payRate` decimal(10,2) DEFAULT NULL,
  `chargeRate` decimal(10,2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ratecard_snapshot`
--

CREATE TABLE `ratecard_snapshot` (
  `rateCardId` int(11) NOT NULL,
  `clientId` int(11) NOT NULL,
  `positionId` int(11) NOT NULL,
  `jobCode` varchar(100) NOT NULL,
  `payCatCode` varchar(100) NOT NULL,
  `payRate` decimal(10,2) DEFAULT NULL,
  `chargeRate` decimal(10,2) DEFAULT NULL,
  `rateCardYear` varchar(25) NOT NULL,
  `dateSaved` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recruitment_job_detail`
--

CREATE TABLE `recruitment_job_detail` (
  `rec_job_id` bigint(20) NOT NULL,
  `client_id` bigint(20) NOT NULL,
  `position_id` bigint(20) NOT NULL,
  `job_description` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recruitment_status`
--

CREATE TABLE `recruitment_status` (
  `rec_status_id` bigint(20) NOT NULL,
  `rec_status` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reference_check`
--

CREATE TABLE `reference_check` (
  `id` bigint(20) NOT NULL,
  `candidateId` varchar(255) NOT NULL,
  `consultantId` bigint(20) NOT NULL,
  `referenceName` varchar(255) NOT NULL,
  `referenceEmail` varchar(255) NOT NULL,
  `companyName` varchar(255) NOT NULL,
  `positionHeld` varchar(255) NOT NULL,
  `phoneNumber` varchar(255) NOT NULL,
  `q1` longtext DEFAULT NULL,
  `q2` longtext DEFAULT NULL,
  `q3` longtext DEFAULT NULL,
  `q4` longtext DEFAULT NULL,
  `q5` longtext DEFAULT NULL,
  `q6` longtext DEFAULT NULL,
  `q7` longtext DEFAULT NULL,
  `q8` longtext DEFAULT NULL,
  `q9` longtext DEFAULT NULL,
  `q10` longtext DEFAULT NULL,
  `q11` longtext DEFAULT NULL,
  `q12` longtext DEFAULT NULL,
  `reference_check_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `regions`
--

CREATE TABLE `regions` (
  `regionId` int(11) NOT NULL,
  `region` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reg_candidate`
--

CREATE TABLE `reg_candidate` (
  `regId` int(11) NOT NULL,
  `candidateId` varchar(85) NOT NULL,
  `axiomno` int(11) NOT NULL DEFAULT 0,
  `messageId` varchar(200) DEFAULT NULL,
  `title` varchar(15) DEFAULT NULL,
  `firstName` varchar(65) NOT NULL,
  `lastName` varchar(65) NOT NULL,
  `homeAddress` varchar(100) DEFAULT NULL,
  `postcode` int(4) NOT NULL DEFAULT 0,
  `homePhone` varchar(15) DEFAULT NULL,
  `mobile` varchar(15) NOT NULL,
  `dob` varchar(45) DEFAULT NULL,
  `gender` varchar(25) DEFAULT NULL,
  `nationality` varchar(30) DEFAULT NULL,
  `email` varchar(65) NOT NULL,
  `emgFullName` varchar(85) DEFAULT NULL,
  `relationship` varchar(45) DEFAULT NULL,
  `emgAddress` text DEFAULT NULL,
  `emghomePhone` varchar(15) DEFAULT NULL,
  `emgMobile` varchar(15) DEFAULT NULL,
  `behalf` varchar(5) DEFAULT NULL,
  `transportMethod` varchar(25) DEFAULT NULL,
  `howFar` varchar(20) DEFAULT NULL,
  `earlyCalls` varchar(25) DEFAULT NULL,
  `visa` varchar(45) DEFAULT NULL,
  `visaLimitation` text DEFAULT NULL,
  `qualification` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `languages` text DEFAULT NULL,
  `cprCourseDate` varchar(45) DEFAULT NULL,
  `manualHandlingDate` varchar(45) DEFAULT NULL,
  `hear` varchar(40) DEFAULT NULL,
  `otherNote` text DEFAULT NULL,
  `referralNote` text DEFAULT NULL,
  `jobactive` text DEFAULT NULL,
  `newsletter` varchar(2) DEFAULT NULL,
  `disabilities` varchar(5) DEFAULT NULL,
  `disabilityDesc` text DEFAULT NULL,
  `hepatitisA` varchar(25) DEFAULT NULL,
  `hepatitisB` varchar(25) DEFAULT NULL,
  `rubella` varchar(25) DEFAULT NULL,
  `tetanus` varchar(25) DEFAULT NULL,
  `polio` varchar(25) DEFAULT NULL,
  `compensation` varchar(5) DEFAULT NULL,
  `compensationDesc` text DEFAULT NULL,
  `empName1` varchar(65) DEFAULT NULL,
  `doi1` varchar(45) DEFAULT NULL,
  `natureInjury1` text DEFAULT NULL,
  `durAbsense1` text DEFAULT NULL,
  `empName2` varchar(65) DEFAULT NULL,
  `doi2` varchar(45) DEFAULT NULL,
  `natureInjury2` text DEFAULT NULL,
  `durAbsense2` text DEFAULT NULL,
  `pension` varchar(5) DEFAULT NULL,
  `pensionDesc` text DEFAULT NULL,
  `hearing` varchar(5) DEFAULT NULL,
  `hearingDesc` text DEFAULT NULL,
  `smoker` varchar(5) DEFAULT NULL,
  `eyetrouble` varchar(5) DEFAULT NULL,
  `hearingImp` varchar(5) DEFAULT NULL,
  `surgicalPro` varchar(5) DEFAULT NULL,
  `asthma` varchar(5) DEFAULT NULL,
  `hernia` varchar(5) DEFAULT NULL,
  `duodenal` varchar(5) DEFAULT NULL,
  `deafness` varchar(5) DEFAULT NULL,
  `epilepsy` varchar(5) DEFAULT NULL,
  `lungDisorder` varchar(5) DEFAULT NULL,
  `fainting` varchar(5) DEFAULT NULL,
  `backdisorder` varchar(5) DEFAULT NULL,
  `nervedisorder` varchar(5) DEFAULT NULL,
  `dizziness` varchar(5) DEFAULT NULL,
  `arthritis` varchar(5) DEFAULT NULL,
  `injury` varchar(5) DEFAULT NULL,
  `injuryDesc` text DEFAULT NULL,
  `swollen` varchar(5) DEFAULT NULL,
  `chronic` varchar(5) DEFAULT NULL,
  `skin` varchar(5) DEFAULT NULL,
  `diabetes` varchar(5) DEFAULT NULL,
  `medicalAttention` varchar(5) DEFAULT NULL,
  `medicalAttentionDesc` text DEFAULT NULL,
  `criminalHistory` varchar(20) DEFAULT NULL,
  `criminalHistoryDesc` text DEFAULT NULL,
  `doo1` varchar(45) DEFAULT NULL,
  `natureOffence1` text DEFAULT NULL,
  `doo2` varchar(45) DEFAULT NULL,
  `natureOffence2` text DEFAULT NULL,
  `pcheck` varchar(25) DEFAULT NULL,
  `regDate` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reg_shiftavailable`
--

CREATE TABLE `reg_shiftavailable` (
  `candidateId` varchar(65) NOT NULL,
  `shiftAvailableId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `release_shift`
--

CREATE TABLE `release_shift` (
  `rel_shift_id` bigint(20) NOT NULL,
  `rel_shift_date` date NOT NULL,
  `rel_shift_day` varchar(10) DEFAULT NULL,
  `rel_client_id` int(11) NOT NULL,
  `rel_state_id` int(11) NOT NULL,
  `rel_dept_id` int(11) NOT NULL,
  `rel_position_id` int(11) DEFAULT NULL,
  `rel_shift_start` varchar(20) NOT NULL,
  `rel_shift_end` varchar(20) NOT NULL,
  `rel_shift_break` varchar(20) NOT NULL,
  `rel_shift_status` varchar(255) NOT NULL,
  `rel_address_id` int(11) NOT NULL DEFAULT 0,
  `candidates` longtext DEFAULT NULL,
  `release_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `release_shift_log`
--

CREATE TABLE `release_shift_log` (
  `id` bigint(20) NOT NULL,
  `rel_shift_id` bigint(20) NOT NULL,
  `rel_shift_status` varchar(255) NOT NULL,
  `candidate_id` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `remove_firebase_shifts`
--

CREATE TABLE `remove_firebase_shifts` (
  `id` bigint(20) NOT NULL,
  `shiftId` bigint(20) NOT NULL,
  `status` varchar(25) NOT NULL DEFAULT 'REMOVE'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resume`
--

CREATE TABLE `resume` (
  `autoid` bigint(20) NOT NULL,
  `messageid` varchar(255) NOT NULL,
  `uid` int(11) NOT NULL,
  `msgno` int(11) NOT NULL,
  `mailfrom` text NOT NULL,
  `mailto` text DEFAULT NULL,
  `subject` text NOT NULL,
  `msgbody` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `reference` varchar(255) DEFAULT 'UNKNOWN',
  `inbox_status` int(11) NOT NULL DEFAULT 0,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resumekr_temp`
--

CREATE TABLE `resumekr_temp` (
  `autoid` int(11) NOT NULL,
  `messageid` varchar(255) NOT NULL,
  `uid` int(11) NOT NULL,
  `msgno` int(11) NOT NULL,
  `mailfrom` text NOT NULL,
  `mailto` text NOT NULL,
  `subject` text NOT NULL,
  `msgbody` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resumeshealth`
--

CREATE TABLE `resumeshealth` (
  `autoid` int(11) NOT NULL,
  `messageid` varchar(255) NOT NULL,
  `uid` int(11) NOT NULL,
  `msgno` int(11) NOT NULL,
  `mailfrom` text NOT NULL,
  `mailto` text NOT NULL,
  `subject` text NOT NULL,
  `msgbody` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resumeshealth_temp`
--

CREATE TABLE `resumeshealth_temp` (
  `autoid` int(11) NOT NULL,
  `messageid` varchar(255) NOT NULL,
  `uid` int(11) NOT NULL,
  `msgno` int(11) NOT NULL,
  `mailfrom` text NOT NULL,
  `mailto` text NOT NULL,
  `subject` text NOT NULL,
  `msgbody` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resume_short_list`
--

CREATE TABLE `resume_short_list` (
  `id` bigint(20) NOT NULL,
  `auto_id` bigint(20) DEFAULT NULL,
  `jb_id` bigint(20) DEFAULT NULL,
  `msg_id` varchar(255) DEFAULT NULL,
  `account_name` varchar(150) DEFAULT NULL,
  `state_id` int(11) NOT NULL,
  `region` int(11) NOT NULL,
  `gender` varchar(25) DEFAULT NULL,
  `applied_date` datetime NOT NULL,
  `ref_code` varchar(255) NOT NULL,
  `consultant_id` bigint(20) NOT NULL,
  `positions` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resume_short_list_positions`
--

CREATE TABLE `resume_short_list_positions` (
  `id` bigint(20) NOT NULL,
  `position` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rosternotes`
--

CREATE TABLE `rosternotes` (
  `candidateId` varchar(85) NOT NULL,
  `rosterNote` text DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rosterorder`
--

CREATE TABLE `rosterorder` (
  `id` bigint(20) NOT NULL,
  `startDate` varchar(100) NOT NULL,
  `endDate` varchar(100) NOT NULL,
  `positionId` bigint(20) NOT NULL,
  `deptId` bigint(20) NOT NULL,
  `orderQty` bigint(20) NOT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `safetygear`
--

CREATE TABLE `safetygear` (
  `safetyGearId` int(11) NOT NULL,
  `safetyGear` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shift`
--

CREATE TABLE `shift` (
  `shiftId` bigint(20) NOT NULL,
  `tandaShiftId` int(11) DEFAULT NULL,
  `tandaTimesheetId` int(11) DEFAULT NULL,
  `shiftDate` date NOT NULL,
  `shiftDay` varchar(10) DEFAULT NULL,
  `clientId` int(11) NOT NULL,
  `stateId` int(11) NOT NULL,
  `departmentId` int(11) NOT NULL,
  `candidateId` varchar(85) NOT NULL,
  `shiftStart` varchar(20) NOT NULL,
  `shiftEnd` varchar(20) NOT NULL,
  `workBreak` varchar(20) NOT NULL,
  `wrkhrs` varchar(20) DEFAULT NULL,
  `shiftNote` text NOT NULL,
  `shiftStatus` varchar(35) NOT NULL DEFAULT 'OPEN',
  `shiftSMSStatus` tinyint(1) NOT NULL DEFAULT 0,
  `consultantId` int(11) DEFAULT 0,
  `positionId` int(11) DEFAULT 0,
  `timeSheetStatus` varchar(35) DEFAULT NULL,
  `addressId` int(11) NOT NULL DEFAULT 0,
  `submittedTime` datetime NOT NULL DEFAULT current_timestamp(),
  `comments` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shiftavailable`
--

CREATE TABLE `shiftavailable` (
  `shiftAvailableId` int(11) NOT NULL,
  `shift` varchar(65) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shiftimportlog`
--

CREATE TABLE `shiftimportlog` (
  `id` int(11) NOT NULL,
  `empId` varchar(85) NOT NULL,
  `shiftDate` varchar(45) NOT NULL,
  `startTime` varchar(20) NOT NULL,
  `endTime` varchar(20) NOT NULL,
  `impStatus` varchar(45) NOT NULL,
  `loggedInUser` varchar(65) NOT NULL,
  `importDate` varchar(45) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shiftLog`
--

CREATE TABLE `shiftLog` (
  `id` bigint(20) NOT NULL,
  `shiftId` int(11) NOT NULL,
  `consultantId` int(11) NOT NULL,
  `status` varchar(65) NOT NULL,
  `updatedTime` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shift_address`
--

CREATE TABLE `shift_address` (
  `id` int(11) NOT NULL,
  `clientId` int(11) DEFAULT NULL,
  `address` varchar(150) NOT NULL,
  `street` varchar(150) DEFAULT NULL,
  `city` varchar(150) DEFAULT NULL,
  `state` varchar(150) DEFAULT NULL,
  `sub` varchar(150) DEFAULT NULL,
  `country` varchar(25) DEFAULT NULL,
  `postalCode` varchar(20) DEFAULT NULL,
  `latitude` varchar(25) DEFAULT NULL,
  `longitude` varchar(25) DEFAULT NULL,
  `location_check` varchar(25) NOT NULL DEFAULT 'YES'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shift_availability`
--

CREATE TABLE `shift_availability` (
  `id` bigint(20) NOT NULL,
  `candidateId` varchar(85) NOT NULL,
  `shift_date` date NOT NULL,
  `am` varchar(40) NOT NULL,
  `pm` varchar(40) NOT NULL,
  `night` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `smsaccount`
--

CREATE TABLE `smsaccount` (
  `accountId` int(11) NOT NULL,
  `accountDescription` varchar(55) DEFAULT NULL,
  `username` varchar(65) NOT NULL,
  `password` varchar(255) NOT NULL,
  `lastRetrievedId` bigint(20) DEFAULT NULL,
  `isDefault` varchar(5) DEFAULT NULL,
  `isActive` varchar(5) DEFAULT NULL,
  `phoneNumber` varchar(15) DEFAULT NULL,
  `email` varchar(65) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `smsinbox`
--

CREATE TABLE `smsinbox` (
  `autoid` int(11) NOT NULL,
  `messageid` varchar(255) NOT NULL,
  `uid` int(11) NOT NULL,
  `msgno` int(11) NOT NULL,
  `mailfrom` text NOT NULL,
  `mailto` text NOT NULL,
  `subject` text NOT NULL,
  `msgbody` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `actionStatus` varchar(25) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `smslog`
--

CREATE TABLE `smslog` (
  `smsid` bigint(20) NOT NULL,
  `message_id` mediumtext DEFAULT NULL,
  `consultantId` int(11) DEFAULT NULL,
  `sentTimeStamp` varchar(50) DEFAULT NULL,
  `recipientName` varchar(255) DEFAULT NULL,
  `recipientNumber` varchar(50) DEFAULT NULL,
  `candidateId` varchar(85) DEFAULT NULL,
  `axiomno` int(11) DEFAULT NULL,
  `smsMessage` longtext DEFAULT NULL,
  `smsReturnData` longtext DEFAULT NULL,
  `sent` varchar(1) DEFAULT NULL,
  `unitCost` int(11) DEFAULT NULL,
  `smsActivity` varchar(40) DEFAULT NULL,
  `smsAccount` int(11) DEFAULT NULL,
  `smsSubject` varchar(255) DEFAULT NULL,
  `smsSender` varchar(25) DEFAULT NULL,
  `unicode` varchar(1) DEFAULT 'f',
  `retryCount` int(11) NOT NULL DEFAULT 0,
  `alertMe` varchar(20) DEFAULT NULL,
  `errorDescription` varchar(50) DEFAULT NULL,
  `timeRecieved` varchar(50) DEFAULT NULL,
  `direction` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

-- --------------------------------------------------------

--
-- Table structure for table `sms_body`
--

CREATE TABLE `sms_body` (
  `id` int(11) NOT NULL,
  `body_desc` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `states`
--

CREATE TABLE `states` (
  `stateId` int(11) NOT NULL,
  `stateCode` varchar(5) NOT NULL,
  `state` varchar(65) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supervisor`
--

CREATE TABLE `supervisor` (
  `supervisorId` int(11) NOT NULL,
  `supervisorName` varchar(85) NOT NULL,
  `phone` varchar(25) DEFAULT NULL,
  `email` varchar(65) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `clientId` int(11) NOT NULL,
  `stateId` int(11) NOT NULL,
  `deptId` int(11) NOT NULL,
  `type` int(11) NOT NULL DEFAULT 0,
  `apiKey` varchar(100) DEFAULT NULL,
  `supervisorImage` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supervisor_login_monitor`
--

CREATE TABLE `supervisor_login_monitor` (
  `monitorIdPrimary` int(11) NOT NULL,
  `username` varchar(65) NOT NULL,
  `candidateId` varchar(85) NOT NULL,
  `actionTime` datetime NOT NULL,
  `status` varchar(65) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `super_calculate`
--

CREATE TABLE `super_calculate` (
  `id` bigint(20) NOT NULL,
  `candidateId` varchar(85) NOT NULL,
  `super_amount` varchar(100) NOT NULL,
  `wk_start_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `talent_mailcomment`
--

CREATE TABLE `talent_mailcomment` (
  `commentId` bigint(20) NOT NULL,
  `autoId` int(11) NOT NULL,
  `comment` text NOT NULL,
  `username` varchar(65) NOT NULL,
  `modifiedDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `talent_note`
--

CREATE TABLE `talent_note` (
  `id` int(11) NOT NULL,
  `autoId` int(11) NOT NULL,
  `consultant` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `talent_resume`
--

CREATE TABLE `talent_resume` (
  `autoid` int(11) NOT NULL,
  `messageid` varchar(255) NOT NULL,
  `uid` int(11) NOT NULL,
  `msgno` int(11) NOT NULL,
  `mailfrom` text NOT NULL,
  `mailto` text NOT NULL,
  `subject` text NOT NULL,
  `msgbody` longtext CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp(),
  `reference` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `taxcode`
--

CREATE TABLE `taxcode` (
  `taxcode` int(11) NOT NULL,
  `taxcodeDesc` varchar(65) NOT NULL,
  `treatment_code` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `testbatch`
--

CREATE TABLE `testbatch` (
  `id` int(11) NOT NULL,
  `testdata` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tfn`
--

CREATE TABLE `tfn` (
  `tfnId` int(11) NOT NULL,
  `candidateId` varchar(85) NOT NULL,
  `tfn` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `timeclock`
--

CREATE TABLE `timeclock` (
  `id` bigint(20) NOT NULL,
  `shiftId` bigint(20) DEFAULT NULL,
  `candidateId` varchar(85) NOT NULL,
  `shiftDate` date DEFAULT NULL,
  `shiftDay` varchar(50) NOT NULL,
  `clientId` bigint(20) NOT NULL,
  `positionId` bigint(20) NOT NULL,
  `deptId` bigint(20) NOT NULL,
  `jobCode` varchar(100) DEFAULT NULL,
  `checkIn` varchar(10) NOT NULL,
  `checkOut` varchar(10) NOT NULL,
  `supervisorCheckIn` varchar(10) DEFAULT NULL,
  `payrollCheckIn` varchar(10) DEFAULT NULL,
  `supervisorCheckOut` varchar(10) DEFAULT NULL,
  `payrollCheckOut` varchar(10) DEFAULT NULL,
  `workBreak` varchar(20) NOT NULL,
  `wrkhrs` varchar(20) NOT NULL,
  `supervicerId` bigint(20) DEFAULT 0,
  `supervisorCheck` varchar(5) NOT NULL DEFAULT 'N',
  `comment` varchar(255) DEFAULT NULL,
  `supervisor` varchar(100) DEFAULT NULL,
  `approvalTime` datetime DEFAULT NULL,
  `transport` varchar(25) NOT NULL DEFAULT 'BUS',
  `checkin_latitude` varchar(35) DEFAULT NULL,
  `checkin_longitude` varchar(35) DEFAULT NULL,
  `checkout_latitude` varchar(35) DEFAULT NULL,
  `checkout_longitude` varchar(35) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `timesheet`
--

CREATE TABLE `timesheet` (
  `timeSheetId` bigint(20) NOT NULL,
  `tandaTimesheetId` int(11) DEFAULT NULL,
  `shiftId` bigint(20) DEFAULT NULL,
  `tandaShiftId` int(11) DEFAULT NULL,
  `shiftDate` date NOT NULL,
  `shiftDay` varchar(50) NOT NULL,
  `candidateId` varchar(85) NOT NULL,
  `clientId` bigint(20) NOT NULL,
  `positionId` bigint(20) NOT NULL,
  `deptId` bigint(20) DEFAULT NULL,
  `jobCode` varchar(100) NOT NULL,
  `shiftStart` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `shiftEnd` varchar(20) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `workBreak` varchar(20) NOT NULL,
  `wrkHrs` varchar(20) NOT NULL,
  `weekendingDate` date DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `appStatus` varchar(5) NOT NULL DEFAULT 'D',
  `supervisorEdit` varchar(5) NOT NULL DEFAULT 'N',
  `transport` varchar(25) NOT NULL DEFAULT 'BUS',
  `accountsEdit` varchar(5) NOT NULL DEFAULT 'N',
  `importId` varchar(45) DEFAULT NULL,
  `comments` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `timesheetdetail`
--

CREATE TABLE `timesheetdetail` (
  `id` int(11) NOT NULL,
  `transCode` int(11) NOT NULL,
  `candidateId` varchar(85) NOT NULL,
  `jobCode` varchar(100) NOT NULL,
  `weekendingDate` date NOT NULL,
  `clientId` int(11) NOT NULL,
  `positionId` int(11) NOT NULL,
  `transCodeAmount` varchar(45) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `timesheet_audit_report`
--

CREATE TABLE `timesheet_audit_report` (
  `id` bigint(20) NOT NULL,
  `weekendingDate` date NOT NULL,
  `filePath` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `timesheet_department`
--

CREATE TABLE `timesheet_department` (
  `id` bigint(20) NOT NULL,
  `clientId` int(11) NOT NULL,
  `positionId` int(11) NOT NULL,
  `empId` varchar(85) NOT NULL,
  `workDate` date NOT NULL,
  `weekendingDate` date NOT NULL,
  `deptId` int(11) NOT NULL,
  `jobCode` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `timesheet_payrun_log`
--

CREATE TABLE `timesheet_payrun_log` (
  `id` bigint(20) NOT NULL,
  `totId` bigint(20) NOT NULL,
  `payrunId` bigint(20) DEFAULT NULL,
  `jobCode` varchar(15) DEFAULT NULL,
  `candidateId` varchar(85) DEFAULT NULL,
  `weekendingDate` date DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `timesheet_totals`
--

CREATE TABLE `timesheet_totals` (
  `totId` bigint(20) NOT NULL,
  `clientId` int(11) NOT NULL,
  `positionId` int(11) NOT NULL,
  `deptId` int(11) DEFAULT NULL,
  `jobCode` varchar(100) DEFAULT NULL,
  `candidateId` varchar(85) NOT NULL,
  `emgTotal` varchar(20) NOT NULL,
  `ordTotal` varchar(20) NOT NULL,
  `aftTotal` varchar(20) NOT NULL,
  `nightTotal` varchar(20) NOT NULL,
  `rdoTotal` varchar(20) NOT NULL DEFAULT '0.00',
  `satTotal` varchar(20) NOT NULL,
  `sunTotal` varchar(20) NOT NULL,
  `ovtTotal` varchar(20) NOT NULL,
  `dblTotal` varchar(20) NOT NULL,
  `hldTotal` varchar(20) NOT NULL,
  `hol_total` varchar(20) NOT NULL DEFAULT '0.00',
  `satovtTotal` varchar(20) NOT NULL,
  `sunovtTotal` varchar(20) NOT NULL,
  `povtTotal` varchar(20) NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `wkendDate` date NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tlattachment`
--

CREATE TABLE `tlattachment` (
  `messageid` varchar(200) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `id` bigint(20) UNSIGNED NOT NULL,
  `filepath` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `contents` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tlattachmentpath`
--

CREATE TABLE `tlattachmentpath` (
  `tlId` int(11) NOT NULL,
  `messageid` varchar(200) NOT NULL,
  `filepath` varchar(255) NOT NULL,
  `filename` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tmpsmslist`
--

CREATE TABLE `tmpsmslist` (
  `tmpId` int(11) NOT NULL,
  `sessionid` varchar(100) NOT NULL,
  `candidateId` varchar(85) NOT NULL,
  `firstName` varchar(65) NOT NULL,
  `lastName` varchar(65) NOT NULL,
  `mobileNo` varchar(15) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactioncode`
--

CREATE TABLE `transactioncode` (
  `transCode` int(11) NOT NULL,
  `transCodeDesc` varchar(85) NOT NULL,
  `transCodeType` int(11) NOT NULL,
  `taxorder` varchar(45) NOT NULL,
  `payslipOrder` varchar(45) DEFAULT NULL,
  `groupCertFormat` varchar(45) DEFAULT NULL,
  `printOnPaySlip` varchar(45) DEFAULT NULL,
  `printOnReports` varchar(45) DEFAULT NULL,
  `defaultPercent` varchar(45) NOT NULL,
  `defaultAmount` varchar(45) NOT NULL,
  `addUnitsAsHours` varchar(45) DEFAULT NULL,
  `autoReduceCode` varchar(45) DEFAULT NULL,
  `autoBillPercent` varchar(45) DEFAULT NULL,
  `autoBillCode` varchar(45) DEFAULT NULL,
  `superfundABN` varchar(65) DEFAULT NULL,
  `superfundSPINID` varchar(65) DEFAULT NULL,
  `USI` varchar(100) DEFAULT NULL,
  `product_name` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactioncodetype`
--

CREATE TABLE `transactioncodetype` (
  `transCodeTypeId` int(11) NOT NULL,
  `transCodeType` varchar(85) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transport_method`
--

CREATE TABLE `transport_method` (
  `transId` int(11) NOT NULL,
  `transport` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `uid_container`
--

CREATE TABLE `uid_container` (
  `candidateId` varchar(85) NOT NULL,
  `uid` varchar(255) DEFAULT NULL,
  `device_os` varchar(150) DEFAULT ''' ''',
  `os_version` varchar(150) DEFAULT ''' ''',
  `updated_time` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `api_token` varchar(60) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_activity_log`
--

CREATE TABLE `user_activity_log` (
  `id` bigint(20) NOT NULL,
  `username` varchar(255) NOT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `page` varchar(255) DEFAULT NULL,
  `shiftId` bigint(20) DEFAULT NULL,
  `activity_type` varchar(255) NOT NULL,
  `activity_detail` longtext DEFAULT NULL,
  `log_time` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_tracking`
--

CREATE TABLE `user_tracking` (
  `id` bigint(20) NOT NULL,
  `autoid` bigint(20) DEFAULT NULL,
  `color_cat_id` int(11) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `username` varchar(85) DEFAULT NULL,
  `action_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `visatype`
--

CREATE TABLE `visatype` (
  `id` bigint(20) NOT NULL,
  `visaType` varchar(85) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `weekly_scale`
--

CREATE TABLE `weekly_scale` (
  `id` bigint(20) NOT NULL,
  `taxcode` int(11) NOT NULL,
  `lessThan` decimal(10,4) NOT NULL,
  `rate` decimal(10,4) NOT NULL,
  `adjustment` decimal(10,4) NOT NULL,
  `rateDate` date DEFAULT '2024-07-01'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `workcover_industry_classification`
--

CREATE TABLE `workcover_industry_classification` (
  `wic_id` bigint(20) NOT NULL,
  `wic_code` varchar(50) NOT NULL,
  `classification` varchar(255) NOT NULL,
  `rate` decimal(10,3) NOT NULL,
  `rate_year` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts_check`
--
ALTER TABLE `accounts_check`
  ADD PRIMARY KEY (`acc_id`);

--
-- Indexes for table `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`activityId`);

--
-- Indexes for table `app_config`
--
ALTER TABLE `app_config`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `app_version`
--
ALTER TABLE `app_version`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `candidate_id` (`candidate_id`);

--
-- Indexes for table `areas_of_work`
--
ALTER TABLE `areas_of_work`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attachment`
--
ALTER TABLE `attachment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attachmentpath`
--
ALTER TABLE `attachmentpath`
  ADD PRIMARY KEY (`krId`);

--
-- Indexes for table `auditlog`
--
ALTER TABLE `auditlog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `audit_check_data`
--
ALTER TABLE `audit_check_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `audit_check_list`
--
ALTER TABLE `audit_check_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `award`
--
ALTER TABLE `award`
  ADD PRIMARY KEY (`award_id`);

--
-- Indexes for table `cancelled_firebase_shifts`
--
ALTER TABLE `cancelled_firebase_shifts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `candidate`
--
ALTER TABLE `candidate`
  ADD PRIMARY KEY (`candidate_no`),
  ADD UNIQUE KEY `candidateId_2` (`candidateId`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `clockPin` (`clockPin`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_Candidate_Consultant_idx` (`consultantId`),
  ADD KEY `candidateId` (`candidateId`),
  ADD KEY `mobileNo` (`mobileNo`),
  ADD KEY `empStatus` (`empStatus`),
  ADD KEY `auditStatus` (`auditStatus`);

--
-- Indexes for table `candidate_document`
--
ALTER TABLE `candidate_document`
  ADD PRIMARY KEY (`docId`);

--
-- Indexes for table `candidate_document_order`
--
ALTER TABLE `candidate_document_order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `candidate_otherlicence`
--
ALTER TABLE `candidate_otherlicence`
  ADD PRIMARY KEY (`othrId`),
  ADD KEY `candidateId` (`candidateId`),
  ADD KEY `otherLicenceId` (`otherLicenceId`);

--
-- Indexes for table `candidate_position`
--
ALTER TABLE `candidate_position`
  ADD PRIMARY KEY (`positionid`),
  ADD KEY `positionName` (`positionName`);

--
-- Indexes for table `candidate_superfund`
--
ALTER TABLE `candidate_superfund`
  ADD PRIMARY KEY (`candidateId`);

--
-- Indexes for table `candidate_taxcode`
--
ALTER TABLE `candidate_taxcode`
  ADD PRIMARY KEY (`candidateId`);

--
-- Indexes for table `carpool`
--
ALTER TABLE `carpool`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `carpoolCode_2` (`carPoolCode`),
  ADD KEY `carpoolCode` (`carPoolCode`),
  ADD KEY `carPoolCode_3` (`carPoolCode`),
  ADD KEY `id` (`id`),
  ADD KEY `carPoolCode_4` (`carPoolCode`),
  ADD KEY `id_2` (`id`);

--
-- Indexes for table `casual_login_monitor`
--
ALTER TABLE `casual_login_monitor`
  ADD PRIMARY KEY (`monitorIdPrimary`);

--
-- Indexes for table `check_log`
--
ALTER TABLE `check_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ch_users`
--
ALTER TABLE `ch_users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`clientId`),
  ADD UNIQUE KEY `client` (`client`),
  ADD UNIQUE KEY `clientCode` (`clientCode`),
  ADD KEY `industryId` (`industryId`),
  ADD KEY `clientId` (`clientId`);

--
-- Indexes for table `client_auditlog`
--
ALTER TABLE `client_auditlog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_audit_check_data`
--
ALTER TABLE `client_audit_check_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_audit_check_list`
--
ALTER TABLE `client_audit_check_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_document`
--
ALTER TABLE `client_document`
  ADD PRIMARY KEY (`docId`);

--
-- Indexes for table `client_email`
--
ALTER TABLE `client_email`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_position`
--
ALTER TABLE `client_position`
  ADD PRIMARY KEY (`clientPosId`);

--
-- Indexes for table `client_summary`
--
ALTER TABLE `client_summary`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_survey_log`
--
ALTER TABLE `client_survey_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `client_terms`
--
ALTER TABLE `client_terms`
  ADD PRIMARY KEY (`termId`);

--
-- Indexes for table `client_visits`
--
ALTER TABLE `client_visits`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `color_category`
--
ALTER TABLE `color_category`
  ADD PRIMARY KEY (`catid`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`companyId`);

--
-- Indexes for table `companybankaccount`
--
ALTER TABLE `companybankaccount`
  ADD PRIMARY KEY (`accId`);

--
-- Indexes for table `consultant`
--
ALTER TABLE `consultant`
  ADD PRIMARY KEY (`consultantId`),
  ADD KEY `consultantId` (`consultantId`),
  ADD KEY `username` (`username`);

--
-- Indexes for table `corporate_bank_account`
--
ALTER TABLE `corporate_bank_account`
  ADD PRIMARY KEY (`accId`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`customerId`);

--
-- Indexes for table `department`
--
ALTER TABLE `department`
  ADD PRIMARY KEY (`deptId`),
  ADD KEY `clientId` (`clientId`),
  ADD KEY `stateId` (`stateId`),
  ADD KEY `department` (`department`);

--
-- Indexes for table `diarynote`
--
ALTER TABLE `diarynote`
  ADD PRIMARY KEY (`diaryNoteId`);

--
-- Indexes for table `document_type`
--
ALTER TABLE `document_type`
  ADD PRIMARY KEY (`typeId`),
  ADD UNIQUE KEY `typeCode` (`typeCode`);

--
-- Indexes for table `emailaccount`
--
ALTER TABLE `emailaccount`
  ADD PRIMARY KEY (`eaId`),
  ADD KEY `accountName` (`accountName`);

--
-- Indexes for table `employee_allocation`
--
ALTER TABLE `employee_allocation`
  ADD PRIMARY KEY (`allocationId`),
  ADD KEY `clientId` (`clientId`),
  ADD KEY `stateId` (`stateId`),
  ADD KEY `deptId` (`deptId`),
  ADD KEY `candidateId` (`candidateId`),
  ADD KEY `status` (`status`),
  ADD KEY `employee_allocation_index_1` (`clientId`,`stateId`,`deptId`,`status`,`candidateId`);

--
-- Indexes for table `employee_availability`
--
ALTER TABLE `employee_availability`
  ADD PRIMARY KEY (`eid`),
  ADD KEY `candidateId` (`candidateId`);

--
-- Indexes for table `employee_bank_account`
--
ALTER TABLE `employee_bank_account`
  ADD PRIMARY KEY (`empAccId`),
  ADD KEY `candidateId` (`candidateId`);

--
-- Indexes for table `employee_carpool`
--
ALTER TABLE `employee_carpool`
  ADD PRIMARY KEY (`empPoolId`),
  ADD KEY `empPoolId` (`empPoolId`),
  ADD KEY `candidateId` (`candidateId`),
  ADD KEY `carPoolId` (`carPoolId`);

--
-- Indexes for table `employee_covid_answers`
--
ALTER TABLE `employee_covid_answers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_deductioncode`
--
ALTER TABLE `employee_deductioncode`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee_positions`
--
ALTER TABLE `employee_positions`
  ADD PRIMARY KEY (`emp_pos_id`),
  ADD UNIQUE KEY `candidateId_2` (`candidateId`,`positionid`),
  ADD KEY `candidateId` (`candidateId`),
  ADD KEY `positionid` (`positionid`),
  ADD KEY `employee_positions_index_1` (`positionid`,`candidateId`);

--
-- Indexes for table `employee_signature`
--
ALTER TABLE `employee_signature`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `candidateId` (`candidateId`);

--
-- Indexes for table `employee_visatype`
--
ALTER TABLE `employee_visatype`
  ADD PRIMARY KEY (`empVisaTypeId`),
  ADD UNIQUE KEY `candidateId` (`candidateId`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events_delete`
--
ALTER TABLE `events_delete`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `expoperating`
--
ALTER TABLE `expoperating`
  ADD PRIMARY KEY (`expOperatingId`);

--
-- Indexes for table `finance_check_data`
--
ALTER TABLE `finance_check_data`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `finance_check_list`
--
ALTER TABLE `finance_check_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `firebase_reminder_shifts`
--
ALTER TABLE `firebase_reminder_shifts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `firebase_shifts`
--
ALTER TABLE `firebase_shifts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `shiftId` (`shiftId`);

--
-- Indexes for table `hire_rates`
--
ALTER TABLE `hire_rates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `inbox_reference`
--
ALTER TABLE `inbox_reference`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `indattachment`
--
ALTER TABLE `indattachment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `indattachmentpath`
--
ALTER TABLE `indattachmentpath`
  ADD PRIMARY KEY (`indId`);

--
-- Indexes for table `indeedresume`
--
ALTER TABLE `indeedresume`
  ADD PRIMARY KEY (`messageid`),
  ADD UNIQUE KEY `autoId` (`autoid`);
ALTER TABLE `indeedresume` ADD FULLTEXT KEY `subject` (`subject`);
ALTER TABLE `indeedresume` ADD FULLTEXT KEY `mailfrom` (`mailfrom`);
ALTER TABLE `indeedresume` ADD FULLTEXT KEY `msgbody` (`msgbody`);

--
-- Indexes for table `indeed_reference`
--
ALTER TABLE `indeed_reference`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `industry`
--
ALTER TABLE `industry`
  ADD PRIMARY KEY (`industryId`);

--
-- Indexes for table `interviewnotes`
--
ALTER TABLE `interviewnotes`
  ADD PRIMARY KEY (`candidateId`);

--
-- Indexes for table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`invoice_no`);

--
-- Indexes for table `invoice_addition`
--
ALTER TABLE `invoice_addition`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `invoice_detail`
--
ALTER TABLE `invoice_detail`
  ADD PRIMARY KEY (`creationNo`);

--
-- Indexes for table `invoice_path`
--
ALTER TABLE `invoice_path`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item_master`
--
ALTER TABLE `item_master`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jbattachment`
--
ALTER TABLE `jbattachment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jbattachmentpath`
--
ALTER TABLE `jbattachmentpath`
  ADD PRIMARY KEY (`krId`);

--
-- Indexes for table `jb_resume`
--
ALTER TABLE `jb_resume`
  ADD PRIMARY KEY (`id`),
  ADD KEY `first_name` (`first_name`),
  ADD KEY `last_name` (`last_name`),
  ADD KEY `phone` (`phone`),
  ADD KEY `status` (`status`),
  ADD KEY `created_at` (`created_at`),
  ADD KEY `email` (`email`),
  ADD KEY `id` (`id`),
  ADD KEY `applied_position` (`applied_position`),
  ADD KEY `suburb` (`suburb`);

--
-- Indexes for table `jobboard_mail_color_category`
--
ALTER TABLE `jobboard_mail_color_category`
  ADD PRIMARY KEY (`mailcatid`);

--
-- Indexes for table `jobboard_resume`
--
ALTER TABLE `jobboard_resume`
  ADD PRIMARY KEY (`messageid`),
  ADD UNIQUE KEY `autoId` (`autoid`);
ALTER TABLE `jobboard_resume` ADD FULLTEXT KEY `subject` (`subject`);
ALTER TABLE `jobboard_resume` ADD FULLTEXT KEY `mailfrom` (`mailfrom`);
ALTER TABLE `jobboard_resume` ADD FULLTEXT KEY `msgbody` (`msgbody`);

--
-- Indexes for table `jobcode`
--
ALTER TABLE `jobcode`
  ADD PRIMARY KEY (`jobNo`),
  ADD KEY `jobCode` (`jobCode`),
  ADD KEY `clientId` (`clientId`),
  ADD KEY `positionid` (`positionid`);

--
-- Indexes for table `joborder`
--
ALTER TABLE `joborder`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `jobOrderId` (`jobOrderId`);

--
-- Indexes for table `joborderlog`
--
ALTER TABLE `joborderlog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `joborder_documents`
--
ALTER TABLE `joborder_documents`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_board_reference`
--
ALTER TABLE `job_board_reference`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_detail`
--
ALTER TABLE `job_detail`
  ADD PRIMARY KEY (`jobDetailId`);

--
-- Indexes for table `job_order`
--
ALTER TABLE `job_order`
  ADD PRIMARY KEY (`job_id`);

--
-- Indexes for table `job_order_log`
--
ALTER TABLE `job_order_log`
  ADD PRIMARY KEY (`log_id`);

--
-- Indexes for table `login_monitor`
--
ALTER TABLE `login_monitor`
  ADD PRIMARY KEY (`monitorId`);

--
-- Indexes for table `mailcomment`
--
ALTER TABLE `mailcomment`
  ADD PRIMARY KEY (`commentId`),
  ADD UNIQUE KEY `autoId` (`autoId`);

--
-- Indexes for table `mail_color_category`
--
ALTER TABLE `mail_color_category`
  ADD PRIMARY KEY (`mailcatid`);

--
-- Indexes for table `mail_view_items`
--
ALTER TABLE `mail_view_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mediadata`
--
ALTER TABLE `mediadata`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifier_log`
--
ALTER TABLE `notifier_log`
  ADD PRIMARY KEY (`notifierId`);

--
-- Indexes for table `otherlicence`
--
ALTER TABLE `otherlicence`
  ADD PRIMARY KEY (`otherLicenceId`),
  ADD KEY `code` (`code`);

--
-- Indexes for table `paycategory`
--
ALTER TABLE `paycategory`
  ADD PRIMARY KEY (`payCatId`);

--
-- Indexes for table `paygmaillist`
--
ALTER TABLE `paygmaillist`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payroll_clock_in_out_log`
--
ALTER TABLE `payroll_clock_in_out_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payroll_name_detail`
--
ALTER TABLE `payroll_name_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payroll_reports`
--
ALTER TABLE `payroll_reports`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payrule`
--
ALTER TABLE `payrule`
  ADD PRIMARY KEY (`payruleId`),
  ADD UNIQUE KEY `jobCode` (`jobCode`),
  ADD UNIQUE KEY `payAwrdCode` (`payAwrdCode`);

--
-- Indexes for table `payrun`
--
ALTER TABLE `payrun`
  ADD PRIMARY KEY (`payrunId`);

--
-- Indexes for table `payrundetails`
--
ALTER TABLE `payrundetails`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payslip_info`
--
ALTER TABLE `payslip_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `person`
--
ALTER TABLE `person`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `placement_info`
--
ALTER TABLE `placement_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `priority`
--
ALTER TABLE `priority`
  ADD PRIMARY KEY (`priorityId`);

--
-- Indexes for table `profit_centre`
--
ALTER TABLE `profit_centre`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `public_holiday`
--
ALTER TABLE `public_holiday`
  ADD PRIMARY KEY (`publicHolidayId`);

--
-- Indexes for table `questionnaire`
--
ALTER TABLE `questionnaire`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ratecard`
--
ALTER TABLE `ratecard`
  ADD PRIMARY KEY (`rateCardId`);

--
-- Indexes for table `ratecard_new_financial_year`
--
ALTER TABLE `ratecard_new_financial_year`
  ADD PRIMARY KEY (`rateCardId`);

--
-- Indexes for table `ratecard_snapshot`
--
ALTER TABLE `ratecard_snapshot`
  ADD PRIMARY KEY (`rateCardId`);

--
-- Indexes for table `recruitment_job_detail`
--
ALTER TABLE `recruitment_job_detail`
  ADD PRIMARY KEY (`rec_job_id`);

--
-- Indexes for table `recruitment_status`
--
ALTER TABLE `recruitment_status`
  ADD PRIMARY KEY (`rec_status_id`);

--
-- Indexes for table `reference_check`
--
ALTER TABLE `reference_check`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`regionId`),
  ADD KEY `regionId` (`regionId`);

--
-- Indexes for table `reg_candidate`
--
ALTER TABLE `reg_candidate`
  ADD PRIMARY KEY (`regId`);

--
-- Indexes for table `release_shift`
--
ALTER TABLE `release_shift`
  ADD PRIMARY KEY (`rel_shift_id`);

--
-- Indexes for table `release_shift_log`
--
ALTER TABLE `release_shift_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `remove_firebase_shifts`
--
ALTER TABLE `remove_firebase_shifts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `shiftId` (`shiftId`);

--
-- Indexes for table `resume`
--
ALTER TABLE `resume`
  ADD PRIMARY KEY (`messageid`),
  ADD UNIQUE KEY `autoId` (`autoid`),
  ADD KEY `inbox_status` (`inbox_status`),
  ADD KEY `status` (`status`),
  ADD KEY `reference` (`reference`);
ALTER TABLE `resume` ADD FULLTEXT KEY `subject` (`subject`);
ALTER TABLE `resume` ADD FULLTEXT KEY `mailfrom` (`mailfrom`);
ALTER TABLE `resume` ADD FULLTEXT KEY `msgbody` (`msgbody`);

--
-- Indexes for table `resumekr_temp`
--
ALTER TABLE `resumekr_temp`
  ADD PRIMARY KEY (`messageid`),
  ADD UNIQUE KEY `autoId` (`autoid`);

--
-- Indexes for table `resumeshealth`
--
ALTER TABLE `resumeshealth`
  ADD PRIMARY KEY (`messageid`),
  ADD UNIQUE KEY `autoId` (`autoid`);

--
-- Indexes for table `resumeshealth_temp`
--
ALTER TABLE `resumeshealth_temp`
  ADD PRIMARY KEY (`messageid`),
  ADD UNIQUE KEY `autoId` (`autoid`);

--
-- Indexes for table `resume_short_list`
--
ALTER TABLE `resume_short_list`
  ADD PRIMARY KEY (`id`),
  ADD KEY `auto_id` (`auto_id`),
  ADD KEY `jb_id` (`jb_id`),
  ADD KEY `state_id` (`state_id`),
  ADD KEY `region` (`region`),
  ADD KEY `msg_id` (`msg_id`),
  ADD KEY `ref_code` (`ref_code`),
  ADD KEY `consultant_id` (`consultant_id`),
  ADD KEY `account_name` (`account_name`),
  ADD KEY `gender` (`gender`);

--
-- Indexes for table `resume_short_list_positions`
--
ALTER TABLE `resume_short_list_positions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `position` (`position`);

--
-- Indexes for table `rosterorder`
--
ALTER TABLE `rosterorder`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `safetygear`
--
ALTER TABLE `safetygear`
  ADD PRIMARY KEY (`safetyGearId`);

--
-- Indexes for table `shift`
--
ALTER TABLE `shift`
  ADD PRIMARY KEY (`shiftId`),
  ADD KEY `candidateId` (`candidateId`),
  ADD KEY `clientId` (`clientId`),
  ADD KEY `stateId` (`stateId`),
  ADD KEY `departmentId` (`departmentId`),
  ADD KEY `positionId` (`positionId`),
  ADD KEY `shiftDate` (`shiftDate`),
  ADD KEY `shiftStart` (`shiftStart`,`shiftEnd`,`workBreak`,`shiftStatus`),
  ADD KEY `shiftDay` (`shiftDay`);

--
-- Indexes for table `shiftavailable`
--
ALTER TABLE `shiftavailable`
  ADD PRIMARY KEY (`shiftAvailableId`);

--
-- Indexes for table `shiftimportlog`
--
ALTER TABLE `shiftimportlog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shiftLog`
--
ALTER TABLE `shiftLog`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shift_address`
--
ALTER TABLE `shift_address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shift_availability`
--
ALTER TABLE `shift_availability`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shift_date` (`shift_date`),
  ADD KEY `candidateId` (`candidateId`);

--
-- Indexes for table `smsaccount`
--
ALTER TABLE `smsaccount`
  ADD PRIMARY KEY (`accountId`);

--
-- Indexes for table `smsinbox`
--
ALTER TABLE `smsinbox`
  ADD PRIMARY KEY (`messageid`),
  ADD UNIQUE KEY `autoId` (`autoid`);
ALTER TABLE `smsinbox` ADD FULLTEXT KEY `subject` (`subject`);
ALTER TABLE `smsinbox` ADD FULLTEXT KEY `mailfrom` (`mailfrom`);
ALTER TABLE `smsinbox` ADD FULLTEXT KEY `msgbody` (`msgbody`);

--
-- Indexes for table `smslog`
--
ALTER TABLE `smslog`
  ADD PRIMARY KEY (`smsid`),
  ADD UNIQUE KEY `smsid` (`smsid`);

--
-- Indexes for table `sms_body`
--
ALTER TABLE `sms_body`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `states`
--
ALTER TABLE `states`
  ADD PRIMARY KEY (`stateId`),
  ADD KEY `state` (`state`),
  ADD KEY `stateId` (`stateId`);

--
-- Indexes for table `supervisor`
--
ALTER TABLE `supervisor`
  ADD PRIMARY KEY (`supervisorId`);

--
-- Indexes for table `supervisor_login_monitor`
--
ALTER TABLE `supervisor_login_monitor`
  ADD PRIMARY KEY (`monitorIdPrimary`);

--
-- Indexes for table `super_calculate`
--
ALTER TABLE `super_calculate`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `talent_mailcomment`
--
ALTER TABLE `talent_mailcomment`
  ADD PRIMARY KEY (`commentId`),
  ADD UNIQUE KEY `autoId` (`autoId`);

--
-- Indexes for table `talent_note`
--
ALTER TABLE `talent_note`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `talent_resume`
--
ALTER TABLE `talent_resume`
  ADD PRIMARY KEY (`messageid`),
  ADD UNIQUE KEY `autoId` (`autoid`);
ALTER TABLE `talent_resume` ADD FULLTEXT KEY `subject` (`subject`);
ALTER TABLE `talent_resume` ADD FULLTEXT KEY `mailfrom` (`mailfrom`);
ALTER TABLE `talent_resume` ADD FULLTEXT KEY `msgbody` (`msgbody`);

--
-- Indexes for table `taxcode`
--
ALTER TABLE `taxcode`
  ADD PRIMARY KEY (`taxcode`);

--
-- Indexes for table `testbatch`
--
ALTER TABLE `testbatch`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tfn`
--
ALTER TABLE `tfn`
  ADD PRIMARY KEY (`tfnId`);

--
-- Indexes for table `timeclock`
--
ALTER TABLE `timeclock`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `shiftId` (`shiftId`),
  ADD KEY `candidateId` (`candidateId`),
  ADD KEY `supervicerId` (`supervicerId`),
  ADD KEY `checkIn` (`checkIn`),
  ADD KEY `checkOut` (`checkOut`);

--
-- Indexes for table `timesheet`
--
ALTER TABLE `timesheet`
  ADD PRIMARY KEY (`timeSheetId`),
  ADD KEY `clientId` (`clientId`),
  ADD KEY `shiftId` (`shiftId`),
  ADD KEY `candidateId` (`candidateId`),
  ADD KEY `positionId` (`positionId`),
  ADD KEY `jobCode` (`jobCode`),
  ADD KEY `shiftDate` (`shiftDate`),
  ADD KEY `candidateId_2` (`candidateId`),
  ADD KEY `clientId_2` (`clientId`),
  ADD KEY `positionId_2` (`positionId`),
  ADD KEY `jobCode_2` (`jobCode`),
  ADD KEY `shiftId_2` (`shiftId`);

--
-- Indexes for table `timesheetdetail`
--
ALTER TABLE `timesheetdetail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timesheet_audit_report`
--
ALTER TABLE `timesheet_audit_report`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timesheet_department`
--
ALTER TABLE `timesheet_department`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timesheet_payrun_log`
--
ALTER TABLE `timesheet_payrun_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timesheet_totals`
--
ALTER TABLE `timesheet_totals`
  ADD PRIMARY KEY (`totId`),
  ADD KEY `clientId` (`clientId`),
  ADD KEY `candidateId` (`candidateId`);

--
-- Indexes for table `tlattachment`
--
ALTER TABLE `tlattachment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tlattachmentpath`
--
ALTER TABLE `tlattachmentpath`
  ADD PRIMARY KEY (`tlId`);

--
-- Indexes for table `tmpsmslist`
--
ALTER TABLE `tmpsmslist`
  ADD PRIMARY KEY (`tmpId`),
  ADD KEY `sessionid` (`sessionid`),
  ADD KEY `candidateId` (`candidateId`),
  ADD KEY `mobileNo` (`mobileNo`);

--
-- Indexes for table `transactioncode`
--
ALTER TABLE `transactioncode`
  ADD PRIMARY KEY (`transCode`),
  ADD KEY `transCodeType` (`transCodeType`);

--
-- Indexes for table `transactioncodetype`
--
ALTER TABLE `transactioncodetype`
  ADD PRIMARY KEY (`transCodeTypeId`);

--
-- Indexes for table `transport_method`
--
ALTER TABLE `transport_method`
  ADD PRIMARY KEY (`transId`);

--
-- Indexes for table `uid_container`
--
ALTER TABLE `uid_container`
  ADD PRIMARY KEY (`candidateId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `api_token` (`api_token`);

--
-- Indexes for table `user_activity_log`
--
ALTER TABLE `user_activity_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_tracking`
--
ALTER TABLE `user_tracking`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `visatype`
--
ALTER TABLE `visatype`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `weekly_scale`
--
ALTER TABLE `weekly_scale`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `workcover_industry_classification`
--
ALTER TABLE `workcover_industry_classification`
  ADD PRIMARY KEY (`wic_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts_check`
--
ALTER TABLE `accounts_check`
  MODIFY `acc_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `activity`
--
ALTER TABLE `activity`
  MODIFY `activityId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `app_config`
--
ALTER TABLE `app_config`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `app_version`
--
ALTER TABLE `app_version`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `areas_of_work`
--
ALTER TABLE `areas_of_work`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attachment`
--
ALTER TABLE `attachment`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attachmentpath`
--
ALTER TABLE `attachmentpath`
  MODIFY `krId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auditlog`
--
ALTER TABLE `auditlog`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audit_check_data`
--
ALTER TABLE `audit_check_data`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `audit_check_list`
--
ALTER TABLE `audit_check_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `award`
--
ALTER TABLE `award`
  MODIFY `award_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cancelled_firebase_shifts`
--
ALTER TABLE `cancelled_firebase_shifts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `candidate`
--
ALTER TABLE `candidate`
  MODIFY `candidate_no` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `candidate_document`
--
ALTER TABLE `candidate_document`
  MODIFY `docId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `candidate_document_order`
--
ALTER TABLE `candidate_document_order`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `candidate_otherlicence`
--
ALTER TABLE `candidate_otherlicence`
  MODIFY `othrId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `candidate_position`
--
ALTER TABLE `candidate_position`
  MODIFY `positionid` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `carpool`
--
ALTER TABLE `carpool`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `casual_login_monitor`
--
ALTER TABLE `casual_login_monitor`
  MODIFY `monitorIdPrimary` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `check_log`
--
ALTER TABLE `check_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ch_users`
--
ALTER TABLE `ch_users`
  MODIFY `user_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client`
--
ALTER TABLE `client`
  MODIFY `clientId` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_auditlog`
--
ALTER TABLE `client_auditlog`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_audit_check_data`
--
ALTER TABLE `client_audit_check_data`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_audit_check_list`
--
ALTER TABLE `client_audit_check_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_document`
--
ALTER TABLE `client_document`
  MODIFY `docId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_email`
--
ALTER TABLE `client_email`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_position`
--
ALTER TABLE `client_position`
  MODIFY `clientPosId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_summary`
--
ALTER TABLE `client_summary`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_survey_log`
--
ALTER TABLE `client_survey_log`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_terms`
--
ALTER TABLE `client_terms`
  MODIFY `termId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `client_visits`
--
ALTER TABLE `client_visits`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `color_category`
--
ALTER TABLE `color_category`
  MODIFY `catid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `companyId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `companybankaccount`
--
ALTER TABLE `companybankaccount`
  MODIFY `accId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `consultant`
--
ALTER TABLE `consultant`
  MODIFY `consultantId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `corporate_bank_account`
--
ALTER TABLE `corporate_bank_account`
  MODIFY `accId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `department`
--
ALTER TABLE `department`
  MODIFY `deptId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `diarynote`
--
ALTER TABLE `diarynote`
  MODIFY `diaryNoteId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `document_type`
--
ALTER TABLE `document_type`
  MODIFY `typeId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `emailaccount`
--
ALTER TABLE `emailaccount`
  MODIFY `eaId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_allocation`
--
ALTER TABLE `employee_allocation`
  MODIFY `allocationId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_availability`
--
ALTER TABLE `employee_availability`
  MODIFY `eid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_bank_account`
--
ALTER TABLE `employee_bank_account`
  MODIFY `empAccId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_carpool`
--
ALTER TABLE `employee_carpool`
  MODIFY `empPoolId` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_covid_answers`
--
ALTER TABLE `employee_covid_answers`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_deductioncode`
--
ALTER TABLE `employee_deductioncode`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_positions`
--
ALTER TABLE `employee_positions`
  MODIFY `emp_pos_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_signature`
--
ALTER TABLE `employee_signature`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_visatype`
--
ALTER TABLE `employee_visatype`
  MODIFY `empVisaTypeId` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expoperating`
--
ALTER TABLE `expoperating`
  MODIFY `expOperatingId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `finance_check_data`
--
ALTER TABLE `finance_check_data`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `finance_check_list`
--
ALTER TABLE `finance_check_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `firebase_reminder_shifts`
--
ALTER TABLE `firebase_reminder_shifts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `firebase_shifts`
--
ALTER TABLE `firebase_shifts`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `hire_rates`
--
ALTER TABLE `hire_rates`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `inbox_reference`
--
ALTER TABLE `inbox_reference`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `indattachment`
--
ALTER TABLE `indattachment`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `indattachmentpath`
--
ALTER TABLE `indattachmentpath`
  MODIFY `indId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `indeedresume`
--
ALTER TABLE `indeedresume`
  MODIFY `autoid` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `indeed_reference`
--
ALTER TABLE `indeed_reference`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `industry`
--
ALTER TABLE `industry`
  MODIFY `industryId` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `invoice_no` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_addition`
--
ALTER TABLE `invoice_addition`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_detail`
--
ALTER TABLE `invoice_detail`
  MODIFY `creationNo` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoice_path`
--
ALTER TABLE `invoice_path`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `item_master`
--
ALTER TABLE `item_master`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jbattachment`
--
ALTER TABLE `jbattachment`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jbattachmentpath`
--
ALTER TABLE `jbattachmentpath`
  MODIFY `krId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jb_resume`
--
ALTER TABLE `jb_resume`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobboard_mail_color_category`
--
ALTER TABLE `jobboard_mail_color_category`
  MODIFY `mailcatid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobboard_resume`
--
ALTER TABLE `jobboard_resume`
  MODIFY `autoid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobcode`
--
ALTER TABLE `jobcode`
  MODIFY `jobNo` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `joborder`
--
ALTER TABLE `joborder`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `joborderlog`
--
ALTER TABLE `joborderlog`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `joborder_documents`
--
ALTER TABLE `joborder_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_board_reference`
--
ALTER TABLE `job_board_reference`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_detail`
--
ALTER TABLE `job_detail`
  MODIFY `jobDetailId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_order`
--
ALTER TABLE `job_order`
  MODIFY `job_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_order_log`
--
ALTER TABLE `job_order_log`
  MODIFY `log_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `login_monitor`
--
ALTER TABLE `login_monitor`
  MODIFY `monitorId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mailcomment`
--
ALTER TABLE `mailcomment`
  MODIFY `commentId` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mail_color_category`
--
ALTER TABLE `mail_color_category`
  MODIFY `mailcatid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mail_view_items`
--
ALTER TABLE `mail_view_items`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mediadata`
--
ALTER TABLE `mediadata`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifier_log`
--
ALTER TABLE `notifier_log`
  MODIFY `notifierId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `otherlicence`
--
ALTER TABLE `otherlicence`
  MODIFY `otherLicenceId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `paycategory`
--
ALTER TABLE `paycategory`
  MODIFY `payCatId` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `paygmaillist`
--
ALTER TABLE `paygmaillist`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payroll_clock_in_out_log`
--
ALTER TABLE `payroll_clock_in_out_log`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payroll_name_detail`
--
ALTER TABLE `payroll_name_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payroll_reports`
--
ALTER TABLE `payroll_reports`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payrule`
--
ALTER TABLE `payrule`
  MODIFY `payruleId` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payrun`
--
ALTER TABLE `payrun`
  MODIFY `payrunId` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payrundetails`
--
ALTER TABLE `payrundetails`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payslip_info`
--
ALTER TABLE `payslip_info`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `person`
--
ALTER TABLE `person`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `placement_info`
--
ALTER TABLE `placement_info`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `priority`
--
ALTER TABLE `priority`
  MODIFY `priorityId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `profit_centre`
--
ALTER TABLE `profit_centre`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `public_holiday`
--
ALTER TABLE `public_holiday`
  MODIFY `publicHolidayId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `questionnaire`
--
ALTER TABLE `questionnaire`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ratecard`
--
ALTER TABLE `ratecard`
  MODIFY `rateCardId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ratecard_new_financial_year`
--
ALTER TABLE `ratecard_new_financial_year`
  MODIFY `rateCardId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ratecard_snapshot`
--
ALTER TABLE `ratecard_snapshot`
  MODIFY `rateCardId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recruitment_job_detail`
--
ALTER TABLE `recruitment_job_detail`
  MODIFY `rec_job_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recruitment_status`
--
ALTER TABLE `recruitment_status`
  MODIFY `rec_status_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reference_check`
--
ALTER TABLE `reference_check`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `regions`
--
ALTER TABLE `regions`
  MODIFY `regionId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reg_candidate`
--
ALTER TABLE `reg_candidate`
  MODIFY `regId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `release_shift`
--
ALTER TABLE `release_shift`
  MODIFY `rel_shift_id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `release_shift_log`
--
ALTER TABLE `release_shift_log`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `remove_firebase_shifts`
--
ALTER TABLE `remove_firebase_shifts`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resume`
--
ALTER TABLE `resume`
  MODIFY `autoid` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resumeshealth`
--
ALTER TABLE `resumeshealth`
  MODIFY `autoid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resume_short_list`
--
ALTER TABLE `resume_short_list`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resume_short_list_positions`
--
ALTER TABLE `resume_short_list_positions`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `rosterorder`
--
ALTER TABLE `rosterorder`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `safetygear`
--
ALTER TABLE `safetygear`
  MODIFY `safetyGearId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shift`
--
ALTER TABLE `shift`
  MODIFY `shiftId` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shiftavailable`
--
ALTER TABLE `shiftavailable`
  MODIFY `shiftAvailableId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shiftimportlog`
--
ALTER TABLE `shiftimportlog`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shiftLog`
--
ALTER TABLE `shiftLog`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shift_address`
--
ALTER TABLE `shift_address`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shift_availability`
--
ALTER TABLE `shift_availability`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `smsaccount`
--
ALTER TABLE `smsaccount`
  MODIFY `accountId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `smsinbox`
--
ALTER TABLE `smsinbox`
  MODIFY `autoid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `smslog`
--
ALTER TABLE `smslog`
  MODIFY `smsid` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sms_body`
--
ALTER TABLE `sms_body`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `states`
--
ALTER TABLE `states`
  MODIFY `stateId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supervisor`
--
ALTER TABLE `supervisor`
  MODIFY `supervisorId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supervisor_login_monitor`
--
ALTER TABLE `supervisor_login_monitor`
  MODIFY `monitorIdPrimary` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `super_calculate`
--
ALTER TABLE `super_calculate`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `talent_mailcomment`
--
ALTER TABLE `talent_mailcomment`
  MODIFY `commentId` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `talent_note`
--
ALTER TABLE `talent_note`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `talent_resume`
--
ALTER TABLE `talent_resume`
  MODIFY `autoid` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `testbatch`
--
ALTER TABLE `testbatch`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tfn`
--
ALTER TABLE `tfn`
  MODIFY `tfnId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `timeclock`
--
ALTER TABLE `timeclock`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `timesheet`
--
ALTER TABLE `timesheet`
  MODIFY `timeSheetId` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `timesheetdetail`
--
ALTER TABLE `timesheetdetail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `timesheet_audit_report`
--
ALTER TABLE `timesheet_audit_report`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `timesheet_department`
--
ALTER TABLE `timesheet_department`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `timesheet_payrun_log`
--
ALTER TABLE `timesheet_payrun_log`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `timesheet_totals`
--
ALTER TABLE `timesheet_totals`
  MODIFY `totId` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tlattachment`
--
ALTER TABLE `tlattachment`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tlattachmentpath`
--
ALTER TABLE `tlattachmentpath`
  MODIFY `tlId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tmpsmslist`
--
ALTER TABLE `tmpsmslist`
  MODIFY `tmpId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactioncodetype`
--
ALTER TABLE `transactioncodetype`
  MODIFY `transCodeTypeId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transport_method`
--
ALTER TABLE `transport_method`
  MODIFY `transId` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_activity_log`
--
ALTER TABLE `user_activity_log`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_tracking`
--
ALTER TABLE `user_tracking`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `weekly_scale`
--
ALTER TABLE `weekly_scale`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `workcover_industry_classification`
--
ALTER TABLE `workcover_industry_classification`
  MODIFY `wic_id` bigint(20) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
