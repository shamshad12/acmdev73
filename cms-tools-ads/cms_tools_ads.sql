-- MySQL dump 10.13  Distrib 5.1.73, for redhat-linux-gnu (x86_64)
--
-- Host: localhost    Database: cms_tools_ads
-- ------------------------------------------------------
-- Server version	5.1.73

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cms_global_parameter`
--

DROP TABLE IF EXISTS `cms_global_parameter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cms_global_parameter` (
  `id` varchar(50) NOT NULL,
  `value` varchar(255) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_time` datetime DEFAULT NULL,
  `update_by` int(11) DEFAULT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_global_parameter`
--

LOCK TABLES `cms_global_parameter` WRITE;
/*!40000 ALTER TABLE `cms_global_parameter` DISABLE KEYS */;
INSERT INTO `cms_global_parameter` VALUES ('AppTitle','Zing Mobile AdsCampaignManagement CMS ',NULL,NULL,0,'0000-00-00 00:00:00'),('EmployerTitle','CMS',NULL,NULL,NULL,NULL),('meta-author','PT. Media Kreasindo Utama',0,'0000-00-00 00:00:00',NULL,NULL),('meta-description','PT. Media Kreasindo Utama',0,'0000-00-00 00:00:00',NULL,NULL),('meta-keywords','PT. Media Kreasindo Utama',0,'0000-00-00 00:00:00',NULL,NULL);
/*!40000 ALTER TABLE `cms_global_parameter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_karyawan`
--

DROP TABLE IF EXISTS `cms_karyawan`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cms_karyawan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_account` enum('Karyawan','Partner','Publisher') DEFAULT NULL,
  `nama_karyawan` varchar(50) DEFAULT NULL,
  `store_id` int(11) NOT NULL,
  `publisher_id` int(11) NOT NULL,
  `tempat_lahir` varchar(50) DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` tinyint(4) DEFAULT NULL,
  `agama` varchar(25) DEFAULT NULL,
  `alamat` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `telepon` varchar(20) DEFAULT NULL,
  `avatar_ori` varchar(255) DEFAULT NULL,
  `avatar_thumb` varchar(255) NOT NULL,
  `status` enum('0','1','2') DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_karyawan`
--

LOCK TABLES `cms_karyawan` WRITE;
/*!40000 ALTER TABLE `cms_karyawan` DISABLE KEYS */;
INSERT INTO `cms_karyawan` VALUES (1,'Karyawan','Admin ADS CMS Tools',0,0,'Jakarta','1989-10-10',1,'Islam','Ciledug','kurniawan@zingmobile.co.id','62','','','1','2015-01-15 08:40:25'),(2,'Karyawan','Hari Kurniawan',0,0,'Jakarta','1989-07-06',1,'Islam','Ciledug','harik.kurniawan@gmail.com','+62','','','1','2014-05-29 00:00:00'),(7,'Karyawan','Marina',0,0,'Jakarta','2010-10-10',0,'Islam','Tebet','lisa.simatupang@zingmobile.co.id','62','','','1','2015-01-22 11:50:44'),(8,'Karyawan','Kondar',0,0,'Jakarta','0000-00-00',1,'Kristen Katolik','Jl.','kondar.naibaho@neo-dimensi.com','62','','','1','2015-01-27 12:06:56'),(9,'Partner','HKurniawan',5,0,'Jakarta','2014-10-10',1,'Islam','Jakarta','kurniawan@zingmobile.co.id','62','','','1','2015-01-29 09:35:51'),(10,'Publisher','wildan',17,0,'jakarta','2015-01-30',1,'Islam','Jl.','wildan.jazuli@zingmobile.co.id','0856','','','1','2015-01-30 19:50:43'),(11,'Karyawan','Jacky',0,0,'Malaysia','1985-01-01',1,'Kristen Katolik','Malaysia','jacky@zingmobile.com','60','','','1','2015-02-24 15:06:20'),(12,'Karyawan','Agung Nugroho',0,0,'-','0000-00-00',1,'Kristen Katolik','-','agung.nugroho@zingmobile.co.id','-','','','1','2015-03-03 17:28:18');
/*!40000 ALTER TABLE `cms_karyawan` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_menu`
--

DROP TABLE IF EXISTS `cms_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cms_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `url` varchar(100) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(50) DEFAULT NULL,
  `icon` varchar(25) DEFAULT NULL,
  `parent` int(11) NOT NULL DEFAULT '0',
  `sort` int(11) DEFAULT NULL,
  `level` tinyint(4) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_time` datetime DEFAULT NULL,
  `update_by` int(11) NOT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_menu`
--

LOCK TABLES `cms_menu` WRITE;
/*!40000 ALTER TABLE `cms_menu` DISABLE KEYS */;
INSERT INTO `cms_menu` VALUES (1,'dashboard','Dashboard','','home',0,1,1,1,0,NULL,0,NULL),(2,'user','User Management','User access, menu and privilege','group',0,2,1,1,0,NULL,0,NULL),(5,'#','Parameter','Manage Parameter','shopping-cart',0,4,1,1,0,NULL,0,NULL),(12,'user_management/karyawan','Karyawan','Manage Karyawan','',2,1,2,1,0,NULL,0,NULL),(13,'parameter/tipeuser','Tipe User','Manage Tipe User','',5,2,2,1,0,NULL,0,'2014-10-23 09:49:31'),(14,'user_management/user','User','Manage User','',2,3,2,1,0,NULL,0,NULL),(20,'user_management/privileges','Privileges','Manage Privileges','',2,2,2,1,0,NULL,0,NULL),(21,'user_management/privileges_menu','Privileges Menu','Manage Privileges Menu','',2,5,2,1,0,NULL,0,NULL),(23,'user_management/account','Account','Manage Account','',2,3,2,1,0,NULL,0,NULL),(33,'parameter/global_parameter','Global Parameter','Manage Global Parameter','',5,4,2,1,0,'2014-07-13 16:54:44',0,'2014-10-23 09:49:41'),(68,'#','Campaign manager','Manage Campaign Manager','group',0,1,1,1,0,'2014-12-20 09:12:16',0,NULL),(69,'#','Parameter','Manage Parameter','',68,1,2,1,0,'2014-12-20 09:12:32',0,NULL),(70,'campaign_manager/country','Countries','Manage Countries','',69,1,3,1,0,'2014-12-20 09:12:59',0,'2014-12-25 12:58:14'),(71,'campaign_manager/currencies','Currencies','Master Currencies','',69,2,3,1,0,'2014-12-25 13:15:31',0,NULL),(72,'#','Master','Manage Master','',68,2,2,1,0,'2014-12-25 16:13:19',0,NULL),(73,'campaign_manager/partners','Partners','Manage Partners','',72,1,3,1,0,'2014-12-25 16:13:50',0,NULL),(74,'campaign_manager/operators','Operators','Manage Operators','',72,2,3,1,0,'2014-12-25 19:45:51',0,NULL),(75,'campaign_manager/prices','Prices','Manage Prices','',72,3,3,1,0,'2014-12-25 23:15:32',0,NULL),(76,'campaign_manager/ads_publishers','Ads Vendors','Manage Ads Vendors','',72,4,3,1,0,'2014-12-26 19:00:39',0,'2015-01-21 12:00:15'),(77,'campaign_manager/shortcodes','Shortcodes','Manage Shortcodes','',72,5,3,1,0,'2014-12-26 20:13:43',0,NULL),(78,'#','Campaigns','Manage Campaigns','',68,3,2,1,0,'2014-12-30 22:37:10',0,NULL),(79,'campaign_manager/campaigns_categories','Categories','Manage Categories','',78,1,3,1,0,'2014-12-30 22:37:53',0,NULL),(80,'campaign_manager/campaigns_media','Media','Manage Media','',78,2,3,1,0,'2014-12-30 22:56:16',0,NULL),(81,'campaign_manager/campaigns','Campaigns','Manage Campaigns','',78,3,3,1,0,'2014-12-31 08:27:48',0,NULL),(82,'campaign_manager/operators_partners','Operator Partner','Manage Operator Partner','',72,6,3,1,0,'2015-01-01 22:47:44',0,NULL),(83,'campaign_manager/templates','Templates','Manage Templates','',72,7,3,1,0,'2015-01-03 11:00:06',0,NULL),(84,'campaign_manager/banners','Banners','Manage Banners','',72,8,3,1,0,'2015-01-04 09:08:31',0,NULL),(85,'campaign_manager/partners_services','Partner Service','Manage Partner Service','',72,9,3,1,0,'2015-01-04 14:58:02',0,NULL),(86,'campaign_manager/services_types','Services Type','Manage Services Type','',69,3,3,1,0,'2015-01-05 19:15:55',0,NULL),(87,'campaign_manager/operators_apis','Operators APIs','Manage Operators APIs','',72,10,3,1,0,'2015-01-07 10:27:48',0,'2015-01-07 11:10:39'),(88,'campaign_manager/domains','Domains','Manage Domains','',72,11,3,1,0,'2015-01-08 22:48:25',0,NULL),(89,'campaign_manager/keyword_groups','Keyword Groups','Manage Keyword Groups','',72,12,3,1,0,'2015-01-10 23:05:31',0,NULL),(90,'#','Reporting','Manage Report','',68,4,2,1,0,'2015-01-16 20:11:47',0,NULL),(91,'campaign_manager/traffic_reporting','Traffic','Manage Traffic','',90,1,3,1,0,'2015-01-17 13:09:21',0,NULL),(92,'campaign_manager/campaigns_auto_redirect','Auto Redirect','Manage Auto Redirect','',78,4,3,1,0,'2015-01-18 00:07:36',0,NULL),(93,'campaign_manager/ads_publishers_pf','Pixel Fire Vendors','Manage Pixel Fire Vendors','',72,13,3,1,0,'2015-01-19 12:53:46',0,'2015-03-25 10:59:37'),(94,'campaign_manager/dashboard_reporting','Dashboard','Management Dashboard','',90,2,3,1,0,'2015-01-19 16:14:05',0,NULL),(95,'campaign_manager/all_records_reporting','All Records','Management All Records','',90,3,3,1,0,'2015-01-19 19:43:46',0,NULL),(96,'campaign_manager/reporting_acquisition','Publisher Acquisition','Manage Publisher Acqusition','',90,4,3,1,0,'2015-01-21 01:42:10',0,'2015-03-19 15:52:36'),(97,'user_management/partner','Partner Account','Manage Partner Account','',2,6,2,1,0,'2015-01-29 09:31:17',0,NULL),(98,'user_management/publisher_account','Publisher Account','Manage Publisher Account','',2,7,2,1,0,'2015-01-30 19:46:37',0,NULL),(99,'campaign_manager/reporting_profit_cost','Profil Lost','Manage Profit Lost','',90,5,3,1,0,'2015-03-20 17:38:13',0,NULL);
/*!40000 ALTER TABLE `cms_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_privilege`
--

DROP TABLE IF EXISTS `cms_privilege`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cms_privilege` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipe_user_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `default_menu` int(3) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_time` datetime DEFAULT NULL,
  `update_by` int(11) NOT NULL,
  `update_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_privilege`
--

LOCK TABLES `cms_privilege` WRITE;
/*!40000 ALTER TABLE `cms_privilege` DISABLE KEYS */;
INSERT INTO `cms_privilege` VALUES (1,1,'Superadmin',23,2,0,NULL,0,NULL),(2,2,'Administrator',23,1,0,NULL,0,'0000-00-00 00:00:00'),(8,6,'Admin Campaign',23,1,0,'0000-00-00 00:00:00',0,NULL),(9,7,'Partner Campaign',23,1,0,'0000-00-00 00:00:00',0,NULL),(10,8,'Publisher Campaign',23,0,0,'0000-00-00 00:00:00',0,NULL);
/*!40000 ALTER TABLE `cms_privilege` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_privilege_menu`
--

DROP TABLE IF EXISTS `cms_privilege_menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cms_privilege_menu` (
  `id_privilege` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `status` enum('0','1') DEFAULT NULL,
  `access` tinyint(4) NOT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id_privilege`,`id_menu`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_privilege_menu`
--

LOCK TABLES `cms_privilege_menu` WRITE;
/*!40000 ALTER TABLE `cms_privilege_menu` DISABLE KEYS */;
INSERT INTO `cms_privilege_menu` VALUES (1,1,'1',0,1,'2014-05-30 18:57:52'),(1,2,'1',1,1,'2014-05-30 18:57:52'),(1,5,'1',1,1,'2014-05-30 18:57:52'),(1,12,'1',15,1,'2014-05-30 18:57:52'),(1,13,'1',15,1,'2014-05-30 18:57:52'),(1,14,'1',15,1,'2014-05-30 18:57:52'),(1,20,'1',15,1,'2014-05-30 18:57:52'),(1,21,'1',15,1,'2014-05-30 18:57:52'),(1,23,'1',1,1,'2014-05-30 18:57:52'),(1,33,NULL,15,0,'2014-07-13 16:54:44'),(1,45,'1',0,0,'2014-11-24 13:58:41'),(1,68,'1',1,0,'2014-12-20 09:12:16'),(1,69,'1',1,0,'2014-12-20 09:12:32'),(1,70,'1',15,0,'2014-12-20 09:12:59'),(1,71,'1',15,0,'2014-12-25 13:15:31'),(1,72,'1',1,0,'2014-12-25 16:13:19'),(1,73,'1',15,0,'2014-12-25 16:13:50'),(1,74,'1',15,0,'2014-12-25 19:45:51'),(1,75,'1',15,0,'2014-12-25 23:15:32'),(1,76,'1',15,0,'2014-12-26 19:00:39'),(1,77,'1',15,0,'2014-12-26 20:13:43'),(1,78,'1',1,0,'2014-12-30 22:37:10'),(1,79,'1',15,0,'2014-12-30 22:37:53'),(1,80,'1',15,0,'2014-12-30 22:56:16'),(1,81,'1',15,0,'2014-12-31 08:27:48'),(1,82,'1',15,0,'2015-01-01 22:47:44'),(1,83,'1',15,0,'2015-01-03 11:00:06'),(1,84,'1',15,0,'2015-01-04 09:08:31'),(1,85,'1',15,0,'2015-01-04 14:58:02'),(1,86,'1',15,0,'2015-01-05 19:15:55'),(1,87,'1',15,0,'2015-01-07 10:27:48'),(1,88,'1',15,0,'2015-01-08 22:48:25'),(1,89,'1',15,0,'2015-01-10 23:05:31'),(1,90,'1',0,0,'2015-01-16 20:11:47'),(1,91,'1',0,0,'2015-01-17 13:09:21'),(1,92,'1',0,0,'2015-01-18 00:07:36'),(1,93,'1',0,0,'2015-01-19 12:53:46'),(1,94,'1',0,0,'2015-01-19 16:14:05'),(1,95,'1',0,0,'2015-01-19 19:43:46'),(1,96,'1',0,0,'2015-01-21 01:42:10'),(1,97,'1',0,0,'2015-01-29 09:31:17'),(1,98,'1',0,0,'2015-01-30 19:46:37'),(1,99,'1',0,0,'2015-03-20 17:38:13'),(2,1,'1',0,1,'2014-05-30 18:57:52'),(2,2,'1',1,1,'2014-05-30 18:57:52'),(2,5,'1',1,1,'2014-05-30 18:57:52'),(2,12,'1',15,1,'2014-05-30 18:57:52'),(2,13,'1',15,1,'2014-05-30 18:57:52'),(2,14,'1',15,1,'2014-05-30 18:57:52'),(2,20,'1',15,1,'2014-05-30 18:57:52'),(2,21,'1',15,1,'2014-05-30 18:57:52'),(2,23,'1',3,1,'2014-05-30 18:57:52'),(2,33,NULL,15,0,'2014-07-13 16:54:44'),(2,45,'1',0,0,'2014-11-24 13:58:41'),(2,68,'1',3,0,'2014-12-20 09:12:16'),(2,69,'1',1,0,'2014-12-20 09:12:32'),(2,70,'1',15,0,'2014-12-20 09:12:59'),(2,71,'1',15,0,'2014-12-25 13:15:31'),(2,72,'1',1,0,'2014-12-25 16:13:19'),(2,73,'1',15,0,'2014-12-25 16:13:50'),(2,74,'1',15,0,'2014-12-25 19:45:51'),(2,75,'1',15,0,'2014-12-25 23:15:32'),(2,76,'1',15,0,'2014-12-26 19:00:39'),(2,77,'1',15,0,'2014-12-26 20:13:43'),(2,78,'1',1,0,'2014-12-30 22:37:10'),(2,79,'1',15,0,'2014-12-30 22:37:53'),(2,80,'1',15,0,'2014-12-30 22:56:16'),(2,81,'1',15,0,'2014-12-31 08:27:48'),(2,82,'1',15,0,'2015-01-01 22:47:44'),(2,83,'1',15,0,'2015-01-03 11:00:06'),(2,84,'1',15,0,'2015-01-04 09:08:31'),(2,85,'1',15,0,'2015-01-04 14:58:02'),(2,86,'1',15,0,'2015-01-05 19:15:55'),(2,87,'1',15,0,'2015-01-07 10:27:48'),(2,88,'1',15,0,'2015-01-08 22:48:25'),(2,89,'1',15,0,'2015-01-10 23:05:31'),(2,90,'1',1,0,'2015-01-16 20:11:47'),(2,91,'1',1,0,'2015-01-17 13:09:21'),(2,92,'1',15,0,'2015-01-18 00:07:36'),(2,93,'1',15,0,'2015-01-19 12:53:46'),(2,94,'1',1,0,'2015-01-19 16:14:05'),(2,95,'1',1,0,'2015-01-19 19:43:46'),(2,96,'1',1,0,'2015-01-21 01:42:10'),(2,97,'1',15,0,'2015-01-29 09:31:17'),(2,98,'1',15,0,'2015-01-30 19:46:37'),(2,99,'1',1,0,'2015-03-20 17:38:13'),(5,1,'1',0,0,'0000-00-00 00:00:00'),(5,2,'1',1,0,'0000-00-00 00:00:00'),(5,5,'1',0,0,'0000-00-00 00:00:00'),(5,12,'1',0,0,'0000-00-00 00:00:00'),(5,13,'1',0,0,'0000-00-00 00:00:00'),(5,14,'1',0,0,'0000-00-00 00:00:00'),(5,20,'1',0,0,'0000-00-00 00:00:00'),(5,21,'1',0,0,'0000-00-00 00:00:00'),(5,23,'1',1,0,'0000-00-00 00:00:00'),(5,33,'1',0,0,'0000-00-00 00:00:00'),(5,45,'1',0,0,'2014-11-24 13:58:41'),(5,68,'1',0,0,'2014-12-20 09:12:16'),(5,69,'1',0,0,'2014-12-20 09:12:32'),(5,70,'1',0,0,'2014-12-20 09:12:59'),(5,71,'1',0,0,'2014-12-25 13:15:31'),(5,72,'1',0,0,'2014-12-25 16:13:19'),(5,73,'1',0,0,'2014-12-25 16:13:50'),(5,74,'1',0,0,'2014-12-25 19:45:51'),(5,75,'1',0,0,'2014-12-25 23:15:32'),(5,76,'1',0,0,'2014-12-26 19:00:39'),(5,77,'1',0,0,'2014-12-26 20:13:43'),(5,78,'1',0,0,'2014-12-30 22:37:10'),(5,79,'1',0,0,'2014-12-30 22:37:53'),(5,80,'1',0,0,'2014-12-30 22:56:16'),(5,81,'1',0,0,'2014-12-31 08:27:48'),(5,82,'1',0,0,'2015-01-01 22:47:44'),(5,83,'1',0,0,'2015-01-03 11:00:06'),(5,84,'1',0,0,'2015-01-04 09:08:31'),(5,85,'1',0,0,'2015-01-04 14:58:02'),(5,86,'1',0,0,'2015-01-05 19:15:55'),(5,87,'1',0,0,'2015-01-07 10:27:48'),(5,88,'1',0,0,'2015-01-08 22:48:25'),(5,89,'1',0,0,'2015-01-10 23:05:31'),(6,1,'1',0,0,'0000-00-00 00:00:00'),(6,2,'1',1,0,'0000-00-00 00:00:00'),(6,5,'1',0,0,'0000-00-00 00:00:00'),(6,12,'1',0,0,'0000-00-00 00:00:00'),(6,13,'1',0,0,'0000-00-00 00:00:00'),(6,14,'1',0,0,'0000-00-00 00:00:00'),(6,20,'1',0,0,'0000-00-00 00:00:00'),(6,21,'1',0,0,'0000-00-00 00:00:00'),(6,23,'1',3,0,'0000-00-00 00:00:00'),(6,33,'1',0,0,'0000-00-00 00:00:00'),(6,45,'1',0,0,'2014-11-24 13:58:41'),(6,68,'1',0,0,'2014-12-20 09:12:16'),(6,69,'1',0,0,'2014-12-20 09:12:32'),(6,70,'1',0,0,'2014-12-20 09:12:59'),(6,71,'1',0,0,'2014-12-25 13:15:31'),(6,72,'1',0,0,'2014-12-25 16:13:19'),(6,73,'1',0,0,'2014-12-25 16:13:50'),(6,74,'1',0,0,'2014-12-25 19:45:51'),(6,75,'1',0,0,'2014-12-25 23:15:32'),(6,76,'1',0,0,'2014-12-26 19:00:39'),(6,77,'1',0,0,'2014-12-26 20:13:43'),(6,78,'1',0,0,'2014-12-30 22:37:10'),(6,79,'1',0,0,'2014-12-30 22:37:53'),(6,80,'1',0,0,'2014-12-30 22:56:16'),(6,81,'1',0,0,'2014-12-31 08:27:48'),(6,82,'1',0,0,'2015-01-01 22:47:44'),(6,83,'1',0,0,'2015-01-03 11:00:06'),(6,84,'1',0,0,'2015-01-04 09:08:31'),(6,85,'1',0,0,'2015-01-04 14:58:02'),(6,86,'1',0,0,'2015-01-05 19:15:55'),(6,87,'1',0,0,'2015-01-07 10:27:48'),(6,88,'1',0,0,'2015-01-08 22:48:25'),(6,89,'1',0,0,'2015-01-10 23:05:31'),(7,1,'1',0,0,'0000-00-00 00:00:00'),(7,2,'1',1,0,'0000-00-00 00:00:00'),(7,5,'1',0,0,'0000-00-00 00:00:00'),(7,12,'1',0,0,'0000-00-00 00:00:00'),(7,13,'1',0,0,'0000-00-00 00:00:00'),(7,14,'1',0,0,'0000-00-00 00:00:00'),(7,20,'1',0,0,'0000-00-00 00:00:00'),(7,21,'1',0,0,'0000-00-00 00:00:00'),(7,23,'1',3,0,'0000-00-00 00:00:00'),(7,33,'1',0,0,'0000-00-00 00:00:00'),(7,68,'1',0,0,'2014-12-20 09:12:16'),(7,69,'1',0,0,'2014-12-20 09:12:32'),(7,70,'1',0,0,'2014-12-20 09:12:59'),(7,71,'1',0,0,'2014-12-25 13:15:31'),(7,72,'1',0,0,'2014-12-25 16:13:19'),(7,73,'1',0,0,'2014-12-25 16:13:50'),(7,74,'1',0,0,'2014-12-25 19:45:51'),(7,75,'1',0,0,'2014-12-25 23:15:32'),(7,76,'1',0,0,'2014-12-26 19:00:39'),(7,77,'1',0,0,'2014-12-26 20:13:43'),(7,78,'1',0,0,'2014-12-30 22:37:10'),(7,79,'1',0,0,'2014-12-30 22:37:53'),(7,80,'1',0,0,'2014-12-30 22:56:16'),(7,81,'1',0,0,'2014-12-31 08:27:48'),(7,82,'1',0,0,'2015-01-01 22:47:44'),(7,83,'1',0,0,'2015-01-03 11:00:06'),(7,84,'1',0,0,'2015-01-04 09:08:31'),(7,85,'1',0,0,'2015-01-04 14:58:02'),(7,86,'1',0,0,'2015-01-05 19:15:55'),(7,87,'1',0,0,'2015-01-07 10:27:48'),(7,88,'1',0,0,'2015-01-08 22:48:25'),(7,89,'1',0,0,'2015-01-10 23:05:31'),(8,1,'1',0,0,'0000-00-00 00:00:00'),(8,2,'1',1,0,'0000-00-00 00:00:00'),(8,5,'1',0,0,'0000-00-00 00:00:00'),(8,12,'1',0,0,'0000-00-00 00:00:00'),(8,13,'1',0,0,'0000-00-00 00:00:00'),(8,14,'1',0,0,'0000-00-00 00:00:00'),(8,20,'1',0,0,'0000-00-00 00:00:00'),(8,21,'1',0,0,'0000-00-00 00:00:00'),(8,23,'1',3,0,'0000-00-00 00:00:00'),(8,33,'1',0,0,'0000-00-00 00:00:00'),(8,68,'1',1,0,'2014-12-20 09:12:16'),(8,69,'1',1,0,'2014-12-20 09:12:32'),(8,70,'1',15,0,'2014-12-20 09:12:59'),(8,71,'1',15,0,'2014-12-25 13:15:31'),(8,72,'1',1,0,'2014-12-25 16:13:19'),(8,73,'1',15,0,'2014-12-25 16:13:50'),(8,74,'1',15,0,'2014-12-25 19:45:51'),(8,75,'1',15,0,'2014-12-25 23:15:32'),(8,76,'1',15,0,'2014-12-26 19:00:39'),(8,77,'1',15,0,'2014-12-26 20:13:43'),(8,78,'1',1,0,'2014-12-30 22:37:10'),(8,79,'1',15,0,'2014-12-30 22:37:53'),(8,80,'1',15,0,'2014-12-30 22:56:16'),(8,81,'1',15,0,'2014-12-31 08:27:48'),(8,82,'1',15,0,'2015-01-01 22:47:44'),(8,83,'1',15,0,'2015-01-03 11:00:06'),(8,84,'1',15,0,'2015-01-04 09:08:31'),(8,85,'1',15,0,'2015-01-04 14:58:02'),(8,86,'1',15,0,'2015-01-05 19:15:55'),(8,87,'1',15,0,'2015-01-07 10:27:48'),(8,88,'1',15,0,'2015-01-08 22:48:25'),(8,89,'1',15,0,'2015-01-10 23:05:31'),(8,90,'1',1,0,'2015-01-16 20:11:47'),(8,91,'1',1,0,'2015-01-17 13:09:21'),(8,92,'1',15,0,'2015-01-18 00:07:36'),(8,93,'1',15,0,'2015-01-19 12:53:46'),(8,94,'1',1,0,'2015-01-19 16:14:05'),(8,95,'1',1,0,'2015-01-19 19:43:46'),(8,96,'1',1,0,'2015-01-21 01:42:10'),(8,97,'1',0,0,'2015-01-29 09:31:17'),(8,98,'1',0,0,'2015-01-30 19:46:37'),(8,99,'1',1,0,'2015-03-20 17:38:13'),(9,1,'1',8,0,'0000-00-00 00:00:00'),(9,2,'1',1,0,'0000-00-00 00:00:00'),(9,5,'1',0,0,'0000-00-00 00:00:00'),(9,12,'1',0,0,'0000-00-00 00:00:00'),(9,13,'1',0,0,'0000-00-00 00:00:00'),(9,14,'1',0,0,'0000-00-00 00:00:00'),(9,20,'1',0,0,'0000-00-00 00:00:00'),(9,21,'1',0,0,'0000-00-00 00:00:00'),(9,23,'1',3,0,'0000-00-00 00:00:00'),(9,33,'1',0,0,'0000-00-00 00:00:00'),(9,68,'1',1,0,'0000-00-00 00:00:00'),(9,69,'1',0,0,'0000-00-00 00:00:00'),(9,70,'1',0,0,'0000-00-00 00:00:00'),(9,71,'1',0,0,'0000-00-00 00:00:00'),(9,72,'1',1,0,'0000-00-00 00:00:00'),(9,73,'1',0,0,'0000-00-00 00:00:00'),(9,74,'1',0,0,'0000-00-00 00:00:00'),(9,75,'1',7,0,'0000-00-00 00:00:00'),(9,76,'1',7,0,'0000-00-00 00:00:00'),(9,77,'1',7,0,'0000-00-00 00:00:00'),(9,78,'1',1,0,'0000-00-00 00:00:00'),(9,79,'1',7,0,'0000-00-00 00:00:00'),(9,80,'1',0,0,'0000-00-00 00:00:00'),(9,81,'1',7,0,'0000-00-00 00:00:00'),(9,82,'1',0,0,'0000-00-00 00:00:00'),(9,83,'1',7,0,'0000-00-00 00:00:00'),(9,84,'1',7,0,'0000-00-00 00:00:00'),(9,85,'1',7,0,'0000-00-00 00:00:00'),(9,86,'1',0,0,'0000-00-00 00:00:00'),(9,87,'1',0,0,'0000-00-00 00:00:00'),(9,88,'1',7,0,'0000-00-00 00:00:00'),(9,89,'1',7,0,'0000-00-00 00:00:00'),(9,90,'1',1,0,'0000-00-00 00:00:00'),(9,91,'1',1,0,'0000-00-00 00:00:00'),(9,92,'1',7,0,'0000-00-00 00:00:00'),(9,93,'1',7,0,'0000-00-00 00:00:00'),(9,94,'1',1,0,'0000-00-00 00:00:00'),(9,95,'1',1,0,'0000-00-00 00:00:00'),(9,96,'1',1,0,'0000-00-00 00:00:00'),(9,97,'1',0,0,'2015-01-29 09:31:17'),(9,98,'1',0,0,'2015-01-30 19:46:37'),(9,99,'1',0,0,'2015-03-20 17:38:13'),(10,1,'1',0,0,'0000-00-00 00:00:00'),(10,2,'1',0,0,'0000-00-00 00:00:00'),(10,5,'1',0,0,'0000-00-00 00:00:00'),(10,12,'1',0,0,'0000-00-00 00:00:00'),(10,13,'1',0,0,'0000-00-00 00:00:00'),(10,14,'1',0,0,'0000-00-00 00:00:00'),(10,20,'1',0,0,'0000-00-00 00:00:00'),(10,21,'1',0,0,'0000-00-00 00:00:00'),(10,23,'1',0,0,'0000-00-00 00:00:00'),(10,33,'1',0,0,'0000-00-00 00:00:00'),(10,68,'1',0,0,'0000-00-00 00:00:00'),(10,69,'1',0,0,'0000-00-00 00:00:00'),(10,70,'1',0,0,'0000-00-00 00:00:00'),(10,71,'1',0,0,'0000-00-00 00:00:00'),(10,72,'1',0,0,'0000-00-00 00:00:00'),(10,73,'1',0,0,'0000-00-00 00:00:00'),(10,74,'1',0,0,'0000-00-00 00:00:00'),(10,75,'1',0,0,'0000-00-00 00:00:00'),(10,76,'1',0,0,'0000-00-00 00:00:00'),(10,77,'1',0,0,'0000-00-00 00:00:00'),(10,78,'1',0,0,'0000-00-00 00:00:00'),(10,79,'1',0,0,'0000-00-00 00:00:00'),(10,80,'1',0,0,'0000-00-00 00:00:00'),(10,81,'1',0,0,'0000-00-00 00:00:00'),(10,82,'1',0,0,'0000-00-00 00:00:00'),(10,83,'1',0,0,'0000-00-00 00:00:00'),(10,84,'1',0,0,'0000-00-00 00:00:00'),(10,85,'1',0,0,'0000-00-00 00:00:00'),(10,86,'1',0,0,'0000-00-00 00:00:00'),(10,87,'1',0,0,'0000-00-00 00:00:00'),(10,88,'1',0,0,'0000-00-00 00:00:00'),(10,89,'1',0,0,'0000-00-00 00:00:00'),(10,90,'1',0,0,'0000-00-00 00:00:00'),(10,91,'1',0,0,'0000-00-00 00:00:00'),(10,92,'1',0,0,'0000-00-00 00:00:00'),(10,93,'1',0,0,'0000-00-00 00:00:00'),(10,94,'1',0,0,'0000-00-00 00:00:00'),(10,95,'1',0,0,'0000-00-00 00:00:00'),(10,96,'1',0,0,'0000-00-00 00:00:00'),(10,97,'1',0,0,'2015-01-29 09:31:17'),(10,98,'1',0,0,'2015-01-30 19:46:37'),(10,99,'1',0,0,'2015-03-20 17:38:13');
/*!40000 ALTER TABLE `cms_privilege_menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_send_email`
--

DROP TABLE IF EXISTS `cms_send_email`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cms_send_email` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `email` varchar(50) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `title` varchar(50) NOT NULL,
  `body` text,
  `send_status` tinyint(4) DEFAULT NULL,
  `created_time` datetime DEFAULT NULL,
  `send_time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_send_email`
--

LOCK TABLES `cms_send_email` WRITE;
/*!40000 ALTER TABLE `cms_send_email` DISABLE KEYS */;
/*!40000 ALTER TABLE `cms_send_email` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_tipe_user`
--

DROP TABLE IF EXISTS `cms_tipe_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cms_tipe_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_tipe_user` varchar(25) DEFAULT NULL,
  `isAdmin` enum('0','1') DEFAULT '1',
  `status` enum('0','1','2') DEFAULT '1',
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_tipe_user`
--

LOCK TABLES `cms_tipe_user` WRITE;
/*!40000 ALTER TABLE `cms_tipe_user` DISABLE KEYS */;
INSERT INTO `cms_tipe_user` VALUES (2,'Administrator','1','1','2014-02-23 10:30:09'),(6,'Admin Campaign','1','1','2014-12-20 09:10:32'),(7,'Partner Campaign','1','1','2015-01-29 09:22:15'),(8,'Publisher Campaign','1','1','2015-01-29 09:22:22');
/*!40000 ALTER TABLE `cms_tipe_user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cms_user`
--

DROP TABLE IF EXISTS `cms_user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cms_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `karyawan_id` int(11) NOT NULL,
  `tipe_user_id` int(11) NOT NULL,
  `username` varchar(25) DEFAULT NULL,
  `userpass` varchar(50) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cms_user`
--

LOCK TABLES `cms_user` WRITE;
/*!40000 ALTER TABLE `cms_user` DISABLE KEYS */;
INSERT INTO `cms_user` VALUES (1,1,2,'zm','2a0803e706c1772341ec1276fb33267f',1,'2015-01-15 08:40:25'),(2,2,1,'hari','5f4dcc3b5aa765d61d8327deb882cf99',1,'2014-06-02 17:01:03'),(4,7,6,'marina','2a0803e706c1772341ec1276fb33267f',1,'2015-01-22 11:50:44'),(5,8,6,'kondar','304cdba85b461facbf4ce2cb0f9b1e48',1,'2015-01-27 12:06:56'),(6,9,7,'kurniawan','2a0803e706c1772341ec1276fb33267f',1,'2015-01-29 09:35:51'),(7,10,8,'test','cc03e747a6afbbcbf8be7668acfebee5',1,'2015-01-30 19:50:43'),(8,11,7,'jacky','2a0803e706c1772341ec1276fb33267f',1,'2015-02-24 15:06:20'),(9,12,6,'anb','2a0803e706c1772341ec1276fb33267f',1,'2015-03-03 17:28:18');
/*!40000 ALTER TABLE `cms_user` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-03-25 10:07:47
