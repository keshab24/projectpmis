-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 19, 2015 at 05:19 AM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `prodb_pmis`
--

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `migration` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`migration`, `batch`) VALUES
('2014_02_09_225721_create_visitor_registry', 1),
('2015_09_07_135307_create_pro_regions_table', 1),
('2015_09_07_135336_create_pro_zones_table', 1),
('2015_09_07_135354_create_pro_districts_table', 1),
('2015_09_07_135356_create_pro_divisions_table', 1),
('2015_09_07_141130_create_pro_division_districts_table', 1),
('2015_09_21_0644212_create_password_resets_table', 1),
('2015_09_21_064421_create_users_table', 1),
('2015_09_23_121244_create_pro_addresses_table', 2),
('2015_09_24_073658_create_pro_division_chiefs_table', 3),
('2015_09_25_063722_create_pro_months_table', 4),
('2015_09_25_071517_create_pro_fiscalyears_table', 5),
('2015_09_26_042905_create_pro_budget_topics_table', 7),
('2015_09_26_060225_create_pro_expenditure_topics_table', 8),
('2015_09_30_015706_create_pro_construction_types_table', 10),
('2015_09_25_095051_create_pro_progress_tracks_table', 11),
('2015_09_30_111741_create_pro_sectors_table', 12),
('2015_09_30_120222_create_pro_implementing_offices_table', 13),
('2015_10_02_110841_create_pro_implementing_modes_table', 15),
('2015_09_30_013600_create_pro_projects_table', 16),
('2015_10_02_103105_create_pro_procurements_table', 16),
('2015_09_28_141251_create_pro_lump_sum_budget_table', 17),
('2014_02_09_225721_create_visitor_registry', 1),
('2015_09_07_135307_create_pro_regions_table', 1),
('2015_09_07_135336_create_pro_zones_table', 1),
('2015_09_07_135354_create_pro_districts_table', 1),
('2015_09_07_135356_create_pro_divisions_table', 1),
('2015_09_07_141130_create_pro_division_districts_table', 1),
('2015_09_21_0644212_create_password_resets_table', 1),
('2015_09_21_064421_create_users_table', 1),
('2015_09_23_121244_create_pro_addresses_table', 2),
('2015_09_24_073658_create_pro_division_chiefs_table', 3),
('2015_09_25_063722_create_pro_months_table', 4),
('2015_09_25_071517_create_pro_fiscalyears_table', 5),
('2015_09_26_042905_create_pro_budget_topics_table', 7),
('2015_09_26_060225_create_pro_expenditure_topics_table', 8),
('2015_09_30_015706_create_pro_construction_types_table', 10),
('2015_09_25_095051_create_pro_progress_tracks_table', 11),
('2015_09_30_111741_create_pro_sectors_table', 12),
('2015_09_30_120222_create_pro_implementing_offices_table', 13),
('2015_10_02_110841_create_pro_implementing_modes_table', 15),
('2015_09_30_013600_create_pro_projects_table', 16),
('2015_10_02_103105_create_pro_procurements_table', 16),
('2015_09_28_141251_create_pro_lump_sum_budget_table', 17),
('2015_10_13_113121_create_pro_budget_allocations_table', 18);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pro_addresses`
--

CREATE TABLE IF NOT EXISTS `pro_addresses` (
`id` int(10) unsigned NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `ward` smallint(6) NOT NULL,
  `tole` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tole_eng` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `vdc_municipality` enum('VDC','Municipality') COLLATE utf8_unicode_ci NOT NULL,
  `vdc_municipality_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `vdc_municipality_name_eng` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `coordinates` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `district_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `pro_addresses`
--

INSERT INTO `pro_addresses` (`id`, `slug`, `ward`, `tole`, `tole_eng`, `vdc_municipality`, `vdc_municipality_name`, `vdc_municipality_name_eng`, `image`, `coordinates`, `district_id`, `created_at`, `updated_at`, `deleted_at`, `status`) VALUES
(1, 'कपन', 5, 'कपन', '', 'VDC', 'बिष्णु बुढानिलकन्थ', '', NULL, NULL, 8, '2015-09-23 22:58:37', '2015-10-11 22:30:41', NULL, 1),
(2, '-1', 9, 'बडहरभन्ज्यांग', '', 'VDC', 'किहुँ', '', NULL, NULL, 3, '2015-09-23 23:00:16', '2015-10-11 22:27:50', NULL, 1),
(6, '-2', 3, 'कमाने', 'Kamane', 'VDC', '', '', NULL, NULL, 2, '2015-09-24 00:28:21', '2015-09-24 01:45:15', NULL, 1),
(7, 'हृटाैंडा', 9, 'हृटाैंडा', 'Hetauda', 'Municipality', '', '', NULL, NULL, 2, '2015-10-01 08:24:16', '2015-10-01 08:24:16', NULL, 1),
(8, 'भिमाद', 9, 'भिमाद', 'Bhimad', 'Municipality', '', '', NULL, NULL, 3, '2015-10-03 06:39:21', '2015-10-03 06:39:21', NULL, 1),
(9, 'किचनाश', 1, 'किचनाश ', 'Kichanash', 'VDC', '', '', NULL, NULL, 6, '2015-10-09 02:15:37', '2015-10-09 02:15:37', NULL, 1),
(10, 'निलाेपुल', 9, 'निलाेपुल', 'Nilopul', 'VDC', 'बिष्णु बुढानिलकन्थ', '', NULL, NULL, 8, '2015-10-11 21:17:35', '2015-10-11 22:32:17', NULL, 1),
(11, 'इभांग', 5, 'इभांग', 'Ibhang', 'VDC', 'इभांग', 'Ibhang', NULL, NULL, 4, '2015-10-12 01:29:47', '2015-10-12 01:29:47', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pro_budget_allocations`
--

CREATE TABLE IF NOT EXISTS `pro_budget_allocations` (
`id` int(10) unsigned NOT NULL,
  `total_budget` double DEFAULT NULL,
  `first_trim` double DEFAULT NULL,
  `second_trim` double DEFAULT NULL,
  `third_trim` double DEFAULT NULL,
  `gon` double DEFAULT NULL,
  `donor` double DEFAULT NULL,
  `loan` double DEFAULT NULL,
  `anudan` double DEFAULT NULL,
  `direct` double DEFAULT NULL,
  `project_code` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `amendment` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `pro_budget_allocations`
--

INSERT INTO `pro_budget_allocations` (`id`, `total_budget`, `first_trim`, `second_trim`, `third_trim`, `gon`, `donor`, `loan`, `anudan`, `direct`, `project_code`, `created_at`, `updated_at`, `deleted_at`, `status`, `amendment`) VALUES
(54, 1200, 240, 360, 600, 801, 240, 159, 0, 0, 7, '2015-11-18 08:22:14', '2015-11-18 08:22:55', NULL, 1, '0'),
(55, 1000, 200, 200, 600, 667, 200, 133, 0, 0, 7, '2015-11-18 08:23:46', '2015-11-18 08:23:46', NULL, 1, '1'),
(56, 9000, 1800, 2700, 4500, 6001, 1800, 1199, 0, 0, 8, '2015-11-18 21:36:42', '2015-11-18 21:37:52', NULL, 1, '0'),
(57, 5000, 1000, 1000, 3000, 2500, 1000, 1000, 250, 250, 9, '2015-11-18 21:37:10', '2015-11-18 21:37:10', NULL, 1, '0'),
(58, 5000, 1000, 1000, 3000, 2500, 1000, 1000, 250, 250, 10, '2015-11-18 21:37:19', '2015-11-18 21:37:19', NULL, 1, '0'),
(59, 39000, 7800, 7800, 23400, 26001, 7800, 5199, 0, 0, 13, '2015-11-18 21:37:34', '2015-11-18 21:37:34', NULL, 1, '0'),
(60, 10000, 2000, 2000, 6000, 6667, 2000, 1333, 0, 0, 8, '2015-11-18 21:38:13', '2015-11-18 21:38:13', NULL, 1, '1'),
(61, 4500, 900, 900, 2700, 2250, 900, 900, 225, 225, 9, '2015-11-18 21:38:25', '2015-11-18 21:38:25', NULL, 1, '1'),
(62, 4500, 900, 900, 2700, 2250, 900, 900, 225, 225, 10, '2015-11-18 21:38:42', '2015-11-18 21:38:42', NULL, 1, '1'),
(63, 30000, 6000, 6000, 18000, 20001, 6000, 3999, 0, 0, 13, '2015-11-18 21:38:50', '2015-11-18 21:38:50', NULL, 1, '1'),
(64, 10000, 2000, 2000, 6000, 6667, 2000, 1333, 0, 0, 8, '2015-11-18 21:41:15', '2015-11-18 21:45:51', NULL, 1, '0'),
(65, 4500, 900, 900, 2700, 2250, 900, 900, 225, 225, 9, '2015-11-18 21:45:01', '2015-11-18 21:45:01', NULL, 1, '2'),
(66, 4500, 900, 900, 2700, 2250, 900, 900, 225, 225, 10, '2015-11-18 21:45:03', '2015-11-18 21:45:03', NULL, 1, '2'),
(67, 30000, 6000, 6000, 18000, 20001, 6000, 3999, 0, 0, 13, '2015-11-18 21:45:04', '2015-11-18 21:45:04', NULL, 1, '2'),
(68, 1000, 200, 200, 600, 667, 200, 133, 0, 0, 7, '2015-11-18 21:45:11', '2015-11-18 21:45:49', NULL, 1, '0');

-- --------------------------------------------------------

--
-- Table structure for table `pro_budget_topics`
--

CREATE TABLE IF NOT EXISTS `pro_budget_topics` (
`id` int(10) unsigned NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `budget_head` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `budget_head_eng` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `budget_topic_num` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `priority` enum('P1','P2','P3') COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `pro_budget_topics`
--

INSERT INTO `pro_budget_topics` (`id`, `slug`, `budget_head`, `budget_head_eng`, `budget_topic_num`, `priority`, `created_at`, `updated_at`, `deleted_at`, `status`) VALUES
(1, 'एकीकृत-जिल्ला-स्वास्थ्य', 'एकीकृत जिल्ला स्वास्थ्य कार्यक्रम', 'Integreted District Health Program', '3708044', 'P1', '2015-09-25 23:22:49', '2015-09-25 23:28:38', NULL, 1),
(2, 'अार्युर्वेद-सेवा-कार्यक्रम', 'अार्युर्वेद सेवा कार्यक्रम', 'Ayurved Service Program', '3708094', 'P2', '2015-09-25 23:23:52', '2015-10-04 21:40:27', NULL, 1),
(3, 'राष्टिय-मानव-अधिकार-अायाेज', 'राष्टिय मानव अधिकार अायाेज', 'National Human Rights Commission', '2140014', 'P1', '2015-10-04 21:39:21', '2015-10-04 21:39:21', NULL, 1),
(4, 'अावास-व्यवस्था-कार्यक्रम', 'अावास व्यवस्था कार्यक्रम', 'Shelter Management', '347180', 'P1', '2015-10-09 02:17:33', '2015-10-09 02:17:33', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pro_construction_types`
--

CREATE TABLE IF NOT EXISTS `pro_construction_types` (
`id` int(10) unsigned NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name_eng` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `pro_construction_types`
--

INSERT INTO `pro_construction_types` (`id`, `slug`, `name`, `name_eng`, `created_at`, `updated_at`, `deleted_at`, `status`) VALUES
(1, 'सेवा', 'सेवाहरू', 'Services', '2015-09-29 20:49:09', '2015-09-29 20:55:56', NULL, 1),
(2, 'सामान', 'सामान', 'Goods', '2015-09-29 20:49:45', '2015-09-29 20:49:45', NULL, 1),
(3, 'works', 'निर्माण कार्य', 'Works', '2015-10-09 02:34:25', '2015-10-09 02:34:25', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pro_districts`
--

CREATE TABLE IF NOT EXISTS `pro_districts` (
`id` int(10) unsigned NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name_eng` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `description_eng` text COLLATE utf8_unicode_ci,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `coordinates` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `zone_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `pro_districts`
--

INSERT INTO `pro_districts` (`id`, `slug`, `name`, `name_eng`, `description`, `description_eng`, `image`, `coordinates`, `zone_id`, `created_at`, `updated_at`, `deleted_at`, `status`) VALUES
(2, '-0', 'मकवानपुर', 'Makawanpur', 'मकवानपुर', 'Makawanpur', NULL, NULL, 4, '2015-09-21 00:12:09', '2015-09-21 00:13:06', NULL, 1),
(3, '0', 'तनहुँ', 'Tanahun', 'तनहुँ', 'Tanahun', NULL, NULL, 3, '2015-09-21 00:47:41', '2015-09-21 00:47:41', NULL, 1),
(4, 'इलाम', 'इलाम', 'Ilam', 'इलाम', 'Ilam', NULL, NULL, 5, '2015-09-25 00:25:45', '2015-09-25 00:25:45', NULL, 1),
(5, 'ताप्लेजुंग्', 'ताप्लेजुंग्', 'Taplejung', 'ताप्लेजुंग्', 'Taplejung', NULL, NULL, 5, '2015-09-25 00:26:14', '2015-09-25 00:26:38', NULL, 1),
(6, 'स्याङ्गजा', 'स्याङ्गजा', 'Syangja', '', '', NULL, NULL, 3, '2015-10-09 02:09:55', '2015-10-09 02:09:55', NULL, 1),
(7, 'कास्की', 'कास्की', 'Kaski', '', '', NULL, NULL, 3, '2015-10-09 02:11:06', '2015-10-09 02:11:06', NULL, 1),
(8, 'का-माडाैं', 'काठमाडाैं', 'Kathmandu', '', '', NULL, NULL, 6, '2015-10-11 21:18:46', '2015-10-11 21:18:46', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pro_divisions`
--

CREATE TABLE IF NOT EXISTS `pro_divisions` (
`id` int(10) unsigned NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name_eng` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `description_eng` text COLLATE utf8_unicode_ci,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `coordinates` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `division_code` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `pro_divisions`
--

INSERT INTO `pro_divisions` (`id`, `slug`, `name`, `name_eng`, `description`, `description_eng`, `image`, `coordinates`, `division_code`, `created_at`, `updated_at`, `deleted_at`, `status`) VALUES
(1, '55', 'डिभिजन कार्यालय, तनहुँ', 'Division Office, Tanahun', '', '', NULL, NULL, 3, '2015-09-21 02:18:58', '2015-09-21 02:47:13', NULL, 1),
(4, '-0', 'डिभिजन कार्यालय, मकवानपुर', 'Division Office, Makawanpur', '', '', NULL, NULL, 2, '2015-09-21 06:42:23', '2015-09-21 06:52:02', NULL, 1),
(5, 'डिभिजन-कार्यालय-इलाम', 'डिभिजन कार्यालय, इलाम', 'Division Office, Ilam', 'डिभिजन कार्यालय, इलाम', 'Division Office, Ilam', NULL, NULL, 4, '2015-09-25 00:27:38', '2015-09-25 00:27:38', NULL, 1),
(6, 'डिभिजन-कार्यालय-ताप्लेजुंग्', 'डिभिजन कार्यालय, ताप्लेजुंग्', 'Division Office, Taplejung', '', '', NULL, NULL, 5, '2015-09-30 10:54:39', '2015-09-30 10:54:39', NULL, 1),
(7, 'डिभिजन-कार्यालय-कास्की', 'डिभिजन कार्यालय, कास्की', 'Division Office, Kaski', '', '', NULL, NULL, 7, '2015-10-09 02:12:34', '2015-10-09 02:12:34', NULL, 1),
(8, 'डिभिजन-कार्यालय-का-माडाैं', 'डिभिजन कार्यालय, काठमाडाैं', 'Division Office, Kathmandu', '', '', NULL, NULL, 8, '2015-10-11 22:31:24', '2015-10-11 22:31:24', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pro_division_chiefs`
--

CREATE TABLE IF NOT EXISTS `pro_division_chiefs` (
`id` int(10) unsigned NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `home_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mobile` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `office_address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `office_phone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `fax` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `office_email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `division_code` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `pro_division_chiefs`
--

INSERT INTO `pro_division_chiefs` (`id`, `slug`, `name`, `home_address`, `email`, `mobile`, `phone`, `office_address`, `office_phone`, `fax`, `office_email`, `image`, `division_code`, `created_at`, `updated_at`, `deleted_at`, `status`) VALUES
(2, 'ललित-नेपाल', 'ललित नेपाल', 'Lalitpur', 'dudbc.ilam@gmail.com', '9851058524', '01-44152', 'Ilam', '025-25865', '025-25865', NULL, '10941116658942567569745673168832901745679n_1443169229.jpg', 5, '2015-09-25 00:29:20', '2015-09-25 02:35:29', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pro_division_districts`
--

CREATE TABLE IF NOT EXISTS `pro_division_districts` (
`id` int(11) NOT NULL,
  `division_id` int(10) unsigned NOT NULL,
  `district_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `pro_division_districts`
--

INSERT INTO `pro_division_districts` (`id`, `division_id`, `district_id`, `created_at`, `updated_at`, `deleted_at`, `status`) VALUES
(4, 4, 2, '2015-09-21 06:52:17', '2015-09-21 06:52:17', NULL, 1),
(6, 5, 4, '2015-09-25 00:27:38', '2015-09-25 00:27:38', NULL, 1),
(7, 5, 5, '2015-09-25 00:27:38', '2015-09-25 00:27:38', NULL, 1),
(8, 6, 4, '2015-09-30 10:54:39', '2015-09-30 10:54:39', NULL, 1),
(9, 6, 5, '2015-09-30 10:54:39', '2015-09-30 10:54:39', NULL, 1),
(10, 1, 3, '2015-10-04 22:08:18', '2015-10-04 22:08:18', NULL, 1),
(11, 7, 7, '2015-10-09 02:12:34', '2015-10-09 02:12:34', NULL, 1),
(12, 7, 6, '2015-10-09 02:12:34', '2015-10-09 02:12:34', NULL, 1),
(13, 8, 8, '2015-10-11 22:31:24', '2015-10-11 22:31:24', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pro_expenditure_topics`
--

CREATE TABLE IF NOT EXISTS `pro_expenditure_topics` (
`id` int(10) unsigned NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `expenditure_head` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `expenditure_head_eng` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `expenditure_topic_num` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `construction_type_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `pro_expenditure_topics`
--

INSERT INTO `pro_expenditure_topics` (`id`, `slug`, `expenditure_head`, `expenditure_head_eng`, `expenditure_topic_num`, `construction_type_id`, `created_at`, `updated_at`, `deleted_at`, `status`) VALUES
(5, 'तलव', 'तलव', 'Salary', '21111', 0, '2015-10-04 22:13:12', '2015-10-05 21:59:32', '2015-10-05 21:59:32', 1),
(6, 'स्थानिय-भत्ता', 'स्थानिय भत्ता', 'Sthaniya Bhatta', '21112', 0, '2015-10-04 22:13:53', '2015-10-05 21:59:32', '2015-10-05 21:59:32', 1),
(7, 'तलव-1', 'तलव', 'Sthaniya Bhatta', '21111', 0, '2015-10-04 22:17:32', '2015-10-05 21:59:32', '2015-10-05 21:59:32', 1),
(8, 'स्थानिय-भत्ता-1', 'स्थानिय भत्ता', 'Sthaniya Bhatta', '22112', 0, '2015-10-04 22:17:50', '2015-10-05 21:59:32', '2015-10-05 21:59:32', 1),
(9, 'तलव-2', 'तलव', 'Salary', '21111', 0, '2015-10-04 22:18:04', '2015-10-04 22:18:04', NULL, 1),
(10, 'स्थानिय-भत्ता-2', 'स्थानिय भत्ता', 'Sthaniya Bhatta', '22112', 0, '2015-10-04 22:18:14', '2015-10-04 22:18:14', NULL, 1),
(11, 'भवन-निर्माण', 'भवन निर्माण', 'Building Construction', '29221', 3, '2015-10-05 21:59:08', '2015-10-11 23:40:40', NULL, 1),
(12, 'भवन-निर्माण-1', 'भवन निर्माण', '', '29221', 0, '2015-10-09 02:19:17', '2015-10-09 02:19:48', '2015-10-09 02:19:48', 1),
(13, 'परिक्षण', 'परिक्षण', 'Test', '11111', 2, '2015-10-11 23:42:40', '2015-10-11 23:42:40', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pro_fiscalyears`
--

CREATE TABLE IF NOT EXISTS `pro_fiscalyears` (
`id` int(10) unsigned NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fy` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `pro_fiscalyears`
--

INSERT INTO `pro_fiscalyears` (`id`, `slug`, `fy`, `created_at`, `updated_at`, `deleted_at`, `status`) VALUES
(1, '2070071', '2070-071', '2015-09-29 05:48:38', '2015-09-29 05:48:38', NULL, 1),
(2, '2072072', '2071-072', '2015-09-29 05:48:47', '2015-09-29 05:49:13', NULL, 1),
(3, '2072073', '2072-073', '2015-09-29 05:48:56', '2015-09-29 05:48:56', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pro_implementing_modes`
--

CREATE TABLE IF NOT EXISTS `pro_implementing_modes` (
`id` int(10) unsigned NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name_eng` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `pro_implementing_modes`
--

INSERT INTO `pro_implementing_modes` (`id`, `slug`, `name`, `name_eng`, `created_at`, `updated_at`, `deleted_at`, `status`) VALUES
(1, 'direct', 'सिधै खरिद', 'Direct', '2015-10-02 05:28:43', '2015-10-02 05:28:43', NULL, 1),
(2, 'quotation', 'काेटेशनहरू', 'Quotations', '2015-10-02 05:28:57', '2015-10-02 05:29:38', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pro_implementing_offices`
--

CREATE TABLE IF NOT EXISTS `pro_implementing_offices` (
`id` int(10) unsigned NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name_eng` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `pro_implementing_offices`
--

INSERT INTO `pro_implementing_offices` (`id`, `slug`, `name`, `name_eng`, `created_at`, `updated_at`, `deleted_at`, `status`) VALUES
(1, 'division', 'डिभिजन कार्यालय', 'Division Office', '2015-09-30 06:22:11', '2015-09-30 06:27:18', NULL, 1),
(2, 'dudbc', 'श.वि.त.भ.नि.वि.', 'DUDBC', '2015-09-30 07:29:20', '2015-09-30 07:29:20', NULL, 1),
(3, 'projects', 'अायाेजना', 'Projects', '2015-09-30 07:29:36', '2015-09-30 07:29:36', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pro_lump_sum_budget`
--

CREATE TABLE IF NOT EXISTS `pro_lump_sum_budget` (
`id` int(10) unsigned NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `running_capital` enum('3','4') COLLATE utf8_unicode_ci NOT NULL,
  `total_budget` double(15,2) DEFAULT NULL,
  `gon` double(15,2) DEFAULT NULL,
  `donor` double(15,2) DEFAULT NULL,
  `loan` double(15,2) DEFAULT NULL,
  `grants` double(15,2) DEFAULT NULL,
  `direct_payments` double(15,2) DEFAULT NULL,
  `gon_exp` double NOT NULL,
  `donor_exp` double NOT NULL,
  `loan_exp` double NOT NULL,
  `grants_exp` double NOT NULL,
  `direct_payments_exp` double NOT NULL,
  `fy_id` int(10) unsigned NOT NULL,
  `expenditure_topic_id` int(10) unsigned NOT NULL,
  `budget_topic_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `pro_lump_sum_budget`
--

INSERT INTO `pro_lump_sum_budget` (`id`, `slug`, `running_capital`, `total_budget`, `gon`, `donor`, `loan`, `grants`, `direct_payments`, `gon_exp`, `donor_exp`, `loan_exp`, `grants_exp`, `direct_payments_exp`, `fy_id`, `expenditure_topic_id`, `budget_topic_id`, `created_at`, `updated_at`, `deleted_at`, `status`) VALUES
(9, '0', '3', 150000.00, 100000.00, 30000.00, 20000.00, 0.00, 0.00, 27335, 8200, 5465, 0, 0, 3, 11, 2, '2015-11-18 08:19:14', '2015-11-18 21:45:11', NULL, 1),
(10, '-1', '3', 10000.00, 5000.00, 2000.00, 2000.00, 500.00, 500.00, 4500, 1800, 1800, 450, 450, 3, 10, 4, '2015-11-18 08:21:17', '2015-11-18 21:45:03', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pro_months`
--

CREATE TABLE IF NOT EXISTS `pro_months` (
`id` int(10) unsigned NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name_eng` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name_eng_eng` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `pro_months`
--

INSERT INTO `pro_months` (`id`, `slug`, `name`, `name_eng`, `name_eng_eng`, `created_at`, `updated_at`, `deleted_at`, `status`) VALUES
(1, 'ashoj', 'असाेज', 'Ashoj', '18 September to 17 October', '2015-09-25 01:14:18', '2015-09-25 01:17:42', NULL, 1),
(2, 'bhadra', 'भदाै', 'Bhadra', '18 August to 17 September', '2015-09-25 01:17:16', '2015-09-25 01:17:16', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pro_procurements`
--

CREATE TABLE IF NOT EXISTS `pro_procurements` (
`id` int(10) unsigned NOT NULL,
  `estimated_amount` double(15,8) NOT NULL,
  `contract_amount` double(15,8) NOT NULL,
  `contract_date` datetime NOT NULL,
  `completion_date` datetime NOT NULL,
  `project_code` int(10) unsigned NOT NULL,
  `implementing_mode_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pro_progress_tracks`
--

CREATE TABLE IF NOT EXISTS `pro_progress_tracks` (
`id` int(10) unsigned NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `progress` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `progress_eng` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `physical_percentage` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `progress_type` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `pro_progress_tracks`
--

INSERT INTO `pro_progress_tracks` (`id`, `slug`, `progress`, `progress_eng`, `physical_percentage`, `progress_type`, `created_at`, `updated_at`, `deleted_at`, `status`) VALUES
(1, '1', 'कार्यादेश', 'Work Ordered', '15', 1, '2015-09-29 21:28:21', '2015-09-29 21:30:19', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pro_projects`
--

CREATE TABLE IF NOT EXISTS `pro_projects` (
`id` int(10) unsigned NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `project_code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name_eng` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `division_code` int(10) unsigned DEFAULT NULL,
  `expenditure_topic_id` int(10) unsigned NOT NULL,
  `budget_topic_id` int(10) unsigned NOT NULL,
  `construction_type_id` int(10) unsigned NOT NULL,
  `fy_id` int(10) unsigned NOT NULL,
  `sector_id` int(10) unsigned NOT NULL,
  `implementing_office_id` int(10) unsigned NOT NULL,
  `address_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `pro_projects`
--

INSERT INTO `pro_projects` (`id`, `slug`, `project_code`, `name`, `name_eng`, `division_code`, `expenditure_topic_id`, `budget_topic_id`, `construction_type_id`, `fy_id`, `sector_id`, `implementing_office_id`, `address_id`, `created_at`, `updated_at`, `deleted_at`, `status`) VALUES
(7, 'कपन-स्वास्थ्य-चाैकी-भवन', '1-8-3', 'कपन स्वास्थ्य चाैकी भवन निर्माण कार्य, कपन ।', '', 8, 11, 2, 3, 3, 2, 1, 10, '2015-10-12 01:23:19', '2015-10-13 02:07:27', NULL, 1),
(8, 'प्रास्वाके-का-माडाैं-।', '1-8-2', 'प्रा.स्वा.के, काठमाडाैं ।', '', 8, 11, 2, 3, 3, 2, 1, 1, '2015-10-12 01:24:28', '2015-10-13 02:09:09', NULL, 1),
(9, 'स्याङ्गजा-जिल्ला-किचनाश', '1-7-2', 'स्याङ्गजा जिल्ला किचनाश गा.वि.स. वडा नं. १ सामडाँडाको सुरेश को घर देखी सन्तोषको घरसङ्गमको बाटो स्तरोन्नती', '', 7, 10, 4, 3, 3, 2, 1, 9, '2015-10-12 01:24:50', '2015-10-13 00:45:07', NULL, 1),
(10, 'जिल्ला-प्रशासन-कार्यालय', '1-4-2', 'जिल्ला प्रशासन कार्यालय मकवानपुरकाे भवन निर्माण', '', 4, 10, 4, 1, 3, 1, 1, 7, '2015-10-12 01:26:40', '2015-10-13 02:07:16', NULL, 1),
(13, 'इलामकाे-अायुर्वेद-अस्पतालयमा', '1-5-1', 'इलामकाे अायुर्वेद अस्पतालयमा स्तराेन्नती गर्ने कार्य', '', 5, 11, 2, 3, 3, 1, 1, 11, '2015-10-12 01:30:17', '2015-10-12 01:30:17', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pro_regions`
--

CREATE TABLE IF NOT EXISTS `pro_regions` (
`id` int(10) unsigned NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name_eng` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `description_eng` text COLLATE utf8_unicode_ci,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `coordinates` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `pro_regions`
--

INSERT INTO `pro_regions` (`id`, `slug`, `name`, `name_eng`, `description`, `description_eng`, `image`, `coordinates`, `created_at`, `updated_at`, `deleted_at`, `status`) VALUES
(5, '-1', 'पश्चीमाञ्चल विकास क्षाेत्र', 'Western Development Region', 'पश्चीमाञ्चल विकास क्षाेत्र', NULL, NULL, NULL, '2015-09-15 06:59:22', '2015-09-19 00:02:55', NULL, 1),
(7, '0', 'सुदुर पश्चीमाञ्चल विकास क्षाेत्र', 'Far Western Development Region', '', '', NULL, NULL, '2015-09-19 07:42:24', '2015-09-19 07:42:24', NULL, 1),
(9, '-2', 'मध्यमाञ्चल विकास क्षेत्र', 'Mid Development Region', '', '', NULL, NULL, '2015-09-19 07:46:14', '2015-09-19 07:46:14', NULL, 1),
(10, 'estern-region', 'पुर्वाञ्चल विकास क्षेत्र', 'Estern Region', 'पुर्वाञ्चल विकास क्षेत्र', 'Estern Region', NULL, NULL, '2015-09-25 00:23:22', '2015-09-25 00:23:22', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pro_sectors`
--

CREATE TABLE IF NOT EXISTS `pro_sectors` (
`id` int(10) unsigned NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name_eng` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `pro_sectors`
--

INSERT INTO `pro_sectors` (`id`, `slug`, `name`, `name_eng`, `created_at`, `updated_at`, `deleted_at`, `status`) VALUES
(1, 'building-construction', 'भवन निर्माण', 'Building Construction', '2015-09-30 05:35:01', '2015-09-30 05:36:32', NULL, 1),
(2, 'urban-development', 'शहरी', 'Urban', '2015-09-30 05:35:47', '2015-09-30 05:36:22', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pro_users`
--

CREATE TABLE IF NOT EXISTS `pro_users` (
`id` int(10) unsigned NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `user_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `verification_code` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `access` enum('Root Level','Top Level','Limited') COLLATE utf8_unicode_ci NOT NULL,
  `division_id` int(10) unsigned DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(10) unsigned DEFAULT NULL,
  `updated_by` int(10) unsigned DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `pro_users`
--

INSERT INTO `pro_users` (`id`, `slug`, `user_name`, `email`, `password`, `verification_code`, `image`, `access`, `division_id`, `remember_token`, `created_at`, `updated_at`, `created_by`, `updated_by`, `deleted_at`, `status`) VALUES
(1, 'root', 'root', 'info@dudbc.gov.np', '$2y$10$x55ZUEEIcSXgiLOdu7JB..FookRM6HoGyLXeIcEVGMn/GuPESLpHG', NULL, NULL, 'Root Level', NULL, 't1Di7R8qQGE2RHTQ6GQBBj69mE57Hndsp0WpXbRLFihhRKEsh2gEjBopvMEY', '2015-09-07 08:43:44', '2015-11-17 09:06:51', 1, 1, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `pro_zones`
--

CREATE TABLE IF NOT EXISTS `pro_zones` (
`id` int(10) unsigned NOT NULL,
  `slug` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name_eng` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `description_eng` text COLLATE utf8_unicode_ci,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `coordinates` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `region_id` int(10) unsigned NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `pro_zones`
--

INSERT INTO `pro_zones` (`id`, `slug`, `name`, `name_eng`, `description`, `description_eng`, `image`, `coordinates`, `region_id`, `created_at`, `updated_at`, `deleted_at`, `status`) VALUES
(3, '2', 'गन्डकी अन्चल', 'Gandaki Zone', 'गन्डकी अन्चल', 'Gandaki Zone', NULL, NULL, 5, '2015-09-17 09:28:04', '2015-09-18 23:32:59', NULL, 1),
(4, '-0', 'नारायणी', 'Narayani', 'नारायणी', 'Narayani', NULL, NULL, 9, '2015-09-21 00:10:43', '2015-09-21 00:11:40', NULL, 1),
(5, 'मेची', 'मेची', 'Mechi', 'मेची', 'Mechi', NULL, NULL, 10, '2015-09-25 00:24:44', '2015-09-25 00:24:44', NULL, 1),
(6, 'बाग्मती', 'बाग्मती', 'Bagmati', '', '', NULL, NULL, 9, '2015-10-11 21:18:25', '2015-10-11 21:18:25', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `visitor_registry`
--

CREATE TABLE IF NOT EXISTS `visitor_registry` (
`id` bigint(20) unsigned NOT NULL,
  `ip` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `country` varchar(4) COLLATE utf8_unicode_ci DEFAULT NULL,
  `clicks` bigint(20) unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
 ADD KEY `password_resets_email_index` (`email`), ADD KEY `password_resets_token_index` (`token`);

--
-- Indexes for table `pro_addresses`
--
ALTER TABLE `pro_addresses`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `pro_addresses_slug_unique` (`slug`), ADD KEY `pro_addresses_district_id_foreign` (`district_id`);

--
-- Indexes for table `pro_budget_allocations`
--
ALTER TABLE `pro_budget_allocations`
 ADD PRIMARY KEY (`id`), ADD KEY `pro_budget_allocations_project_code_foreign` (`project_code`);

--
-- Indexes for table `pro_budget_topics`
--
ALTER TABLE `pro_budget_topics`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `pro_budget_topics_slug_unique` (`slug`);

--
-- Indexes for table `pro_construction_types`
--
ALTER TABLE `pro_construction_types`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `pro_construction_types_slug_unique` (`slug`);

--
-- Indexes for table `pro_districts`
--
ALTER TABLE `pro_districts`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `pro_districts_slug_unique` (`slug`), ADD KEY `pro_districts_zone_id_foreign` (`zone_id`);

--
-- Indexes for table `pro_divisions`
--
ALTER TABLE `pro_divisions`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `pro_divisions_slug_unique` (`slug`), ADD KEY `pro_divisions_division_code_foreign` (`division_code`);

--
-- Indexes for table `pro_division_chiefs`
--
ALTER TABLE `pro_division_chiefs`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `pro_division_chiefs_slug_unique` (`slug`), ADD KEY `pro_division_chiefs_division_code_foreign` (`division_code`);

--
-- Indexes for table `pro_division_districts`
--
ALTER TABLE `pro_division_districts`
 ADD PRIMARY KEY (`id`), ADD KEY `pro_division_districts_division_id_foreign` (`division_id`), ADD KEY `pro_division_districts_district_id_foreign` (`district_id`);

--
-- Indexes for table `pro_expenditure_topics`
--
ALTER TABLE `pro_expenditure_topics`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `pro_expenditure_topics_slug_unique` (`slug`);

--
-- Indexes for table `pro_fiscalyears`
--
ALTER TABLE `pro_fiscalyears`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `pro_fiscalyears_slug_unique` (`slug`);

--
-- Indexes for table `pro_implementing_modes`
--
ALTER TABLE `pro_implementing_modes`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `pro_implementing_modes_slug_unique` (`slug`);

--
-- Indexes for table `pro_implementing_offices`
--
ALTER TABLE `pro_implementing_offices`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `pro_implementing_offices_slug_unique` (`slug`);

--
-- Indexes for table `pro_lump_sum_budget`
--
ALTER TABLE `pro_lump_sum_budget`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `pro_lump_sum_budget_slug_unique` (`slug`), ADD KEY `pro_lump_sum_budget_fy_id_foreign` (`fy_id`), ADD KEY `pro_lump_sum_budget_expenditure_topic_id_foreign` (`expenditure_topic_id`), ADD KEY `pro_lump_sum_budget_budget_topic_id_foreign` (`budget_topic_id`);

--
-- Indexes for table `pro_months`
--
ALTER TABLE `pro_months`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `pro_months_slug_unique` (`slug`);

--
-- Indexes for table `pro_procurements`
--
ALTER TABLE `pro_procurements`
 ADD PRIMARY KEY (`id`), ADD KEY `pro_procurements_project_code_foreign` (`project_code`), ADD KEY `pro_procurements_implementing_mode_id_foreign` (`implementing_mode_id`);

--
-- Indexes for table `pro_progress_tracks`
--
ALTER TABLE `pro_progress_tracks`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `pro_progress_tracks_slug_unique` (`slug`), ADD KEY `pro_progress_tracks_progress_type_foreign` (`progress_type`);

--
-- Indexes for table `pro_projects`
--
ALTER TABLE `pro_projects`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `pro_projects_slug_unique` (`slug`), ADD UNIQUE KEY `pro_projects_project_code_unique` (`project_code`), ADD KEY `pro_projects_division_code_foreign` (`division_code`), ADD KEY `pro_projects_expenditure_topic_id_foreign` (`expenditure_topic_id`), ADD KEY `pro_projects_budget_topic_id_foreign` (`budget_topic_id`), ADD KEY `pro_projects_construction_type_id_foreign` (`construction_type_id`), ADD KEY `pro_projects_fy_id_foreign` (`fy_id`), ADD KEY `pro_projects_sector_id_foreign` (`sector_id`), ADD KEY `pro_projects_implementing_office_id_foreign` (`implementing_office_id`), ADD KEY `pro_projects_address_id_foreign` (`address_id`);

--
-- Indexes for table `pro_regions`
--
ALTER TABLE `pro_regions`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `pro_regions_slug_unique` (`slug`);

--
-- Indexes for table `pro_sectors`
--
ALTER TABLE `pro_sectors`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `pro_sectors_slug_unique` (`slug`);

--
-- Indexes for table `pro_users`
--
ALTER TABLE `pro_users`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `pro_users_slug_unique` (`slug`), ADD UNIQUE KEY `pro_users_email_unique` (`email`), ADD KEY `pro_users_division_id_foreign` (`division_id`), ADD KEY `pro_users_created_by_foreign` (`created_by`), ADD KEY `pro_users_updated_by_foreign` (`updated_by`);

--
-- Indexes for table `pro_zones`
--
ALTER TABLE `pro_zones`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `pro_zones_slug_unique` (`slug`), ADD KEY `pro_zones_region_id_foreign` (`region_id`);

--
-- Indexes for table `visitor_registry`
--
ALTER TABLE `visitor_registry`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `pro_addresses`
--
ALTER TABLE `pro_addresses`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `pro_budget_allocations`
--
ALTER TABLE `pro_budget_allocations`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=69;
--
-- AUTO_INCREMENT for table `pro_budget_topics`
--
ALTER TABLE `pro_budget_topics`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `pro_construction_types`
--
ALTER TABLE `pro_construction_types`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `pro_districts`
--
ALTER TABLE `pro_districts`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `pro_divisions`
--
ALTER TABLE `pro_divisions`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `pro_division_chiefs`
--
ALTER TABLE `pro_division_chiefs`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `pro_division_districts`
--
ALTER TABLE `pro_division_districts`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `pro_expenditure_topics`
--
ALTER TABLE `pro_expenditure_topics`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `pro_fiscalyears`
--
ALTER TABLE `pro_fiscalyears`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `pro_implementing_modes`
--
ALTER TABLE `pro_implementing_modes`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `pro_implementing_offices`
--
ALTER TABLE `pro_implementing_offices`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `pro_lump_sum_budget`
--
ALTER TABLE `pro_lump_sum_budget`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `pro_months`
--
ALTER TABLE `pro_months`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `pro_procurements`
--
ALTER TABLE `pro_procurements`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `pro_progress_tracks`
--
ALTER TABLE `pro_progress_tracks`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `pro_projects`
--
ALTER TABLE `pro_projects`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `pro_regions`
--
ALTER TABLE `pro_regions`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `pro_sectors`
--
ALTER TABLE `pro_sectors`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `pro_users`
--
ALTER TABLE `pro_users`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `pro_zones`
--
ALTER TABLE `pro_zones`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `visitor_registry`
--
ALTER TABLE `visitor_registry`
MODIFY `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `pro_addresses`
--
ALTER TABLE `pro_addresses`
ADD CONSTRAINT `pro_addresses_district_id_foreign` FOREIGN KEY (`district_id`) REFERENCES `pro_districts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pro_budget_allocations`
--
ALTER TABLE `pro_budget_allocations`
ADD CONSTRAINT `pro_budget_allocations_project_code_foreign` FOREIGN KEY (`project_code`) REFERENCES `pro_projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pro_districts`
--
ALTER TABLE `pro_districts`
ADD CONSTRAINT `pro_districts_zone_id_foreign` FOREIGN KEY (`zone_id`) REFERENCES `pro_zones` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pro_divisions`
--
ALTER TABLE `pro_divisions`
ADD CONSTRAINT `pro_divisions_division_code_foreign` FOREIGN KEY (`division_code`) REFERENCES `pro_districts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pro_division_chiefs`
--
ALTER TABLE `pro_division_chiefs`
ADD CONSTRAINT `pro_division_chiefs_division_code_foreign` FOREIGN KEY (`division_code`) REFERENCES `pro_divisions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pro_division_districts`
--
ALTER TABLE `pro_division_districts`
ADD CONSTRAINT `pro_division_districts_district_id_foreign` FOREIGN KEY (`district_id`) REFERENCES `pro_districts` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `pro_division_districts_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `pro_divisions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pro_lump_sum_budget`
--
ALTER TABLE `pro_lump_sum_budget`
ADD CONSTRAINT `pro_lump_sum_budget_budget_topic_id_foreign` FOREIGN KEY (`budget_topic_id`) REFERENCES `pro_budget_topics` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `pro_lump_sum_budget_expenditure_topic_id_foreign` FOREIGN KEY (`expenditure_topic_id`) REFERENCES `pro_expenditure_topics` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `pro_lump_sum_budget_fy_id_foreign` FOREIGN KEY (`fy_id`) REFERENCES `pro_fiscalyears` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pro_procurements`
--
ALTER TABLE `pro_procurements`
ADD CONSTRAINT `pro_procurements_implementing_mode_id_foreign` FOREIGN KEY (`implementing_mode_id`) REFERENCES `pro_implementing_modes` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `pro_procurements_project_code_foreign` FOREIGN KEY (`project_code`) REFERENCES `pro_projects` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pro_progress_tracks`
--
ALTER TABLE `pro_progress_tracks`
ADD CONSTRAINT `pro_progress_tracks_progress_type_foreign` FOREIGN KEY (`progress_type`) REFERENCES `pro_construction_types` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pro_projects`
--
ALTER TABLE `pro_projects`
ADD CONSTRAINT `pro_projects_address_id_foreign` FOREIGN KEY (`address_id`) REFERENCES `pro_addresses` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `pro_projects_budget_topic_id_foreign` FOREIGN KEY (`budget_topic_id`) REFERENCES `pro_budget_topics` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `pro_projects_construction_type_id_foreign` FOREIGN KEY (`construction_type_id`) REFERENCES `pro_construction_types` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `pro_projects_division_code_foreign` FOREIGN KEY (`division_code`) REFERENCES `pro_divisions` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `pro_projects_expenditure_topic_id_foreign` FOREIGN KEY (`expenditure_topic_id`) REFERENCES `pro_expenditure_topics` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `pro_projects_fy_id_foreign` FOREIGN KEY (`fy_id`) REFERENCES `pro_fiscalyears` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `pro_projects_implementing_office_id_foreign` FOREIGN KEY (`implementing_office_id`) REFERENCES `pro_implementing_offices` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `pro_projects_sector_id_foreign` FOREIGN KEY (`sector_id`) REFERENCES `pro_sectors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pro_users`
--
ALTER TABLE `pro_users`
ADD CONSTRAINT `pro_users_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `pro_users` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `pro_users_division_id_foreign` FOREIGN KEY (`division_id`) REFERENCES `pro_divisions` (`id`) ON DELETE CASCADE,
ADD CONSTRAINT `pro_users_updated_by_foreign` FOREIGN KEY (`updated_by`) REFERENCES `pro_users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pro_zones`
--
ALTER TABLE `pro_zones`
ADD CONSTRAINT `pro_zones_region_id_foreign` FOREIGN KEY (`region_id`) REFERENCES `pro_regions` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
