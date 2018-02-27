-- MySQL dump 10.13  Distrib 5.5.40, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: kur_campaign
-- ------------------------------------------------------
-- Server version	5.5.40-0ubuntu0.14.04.1

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
-- Table structure for table `ads_publishers`
--

DROP TABLE IF EXISTS `ads_publishers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ads_publishers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_country` int(11) NOT NULL,
  `utc_timezone` tinyint(4) NOT NULL,
  `code` varchar(25) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `ads_type` enum('CPA','CPC') DEFAULT NULL,
  `pf_value` double(10,2) NOT NULL,
  `affiliate_params` varchar(255) NOT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ads_publishers`
--

LOCK TABLES `ads_publishers` WRITE;
/*!40000 ALTER TABLE `ads_publishers` DISABLE KEYS */;
INSERT INTO `ads_publishers` VALUES (1,1,7,'dev','Development Vendor','','CPC',0.00,'affiliate_id',1),(2,2,8,'kma','Kiss My Ads','','CPA',0.00,'',1),(3,2,8,'kimia','Kimia','','CPA',0.00,'',1),(4,1,7,'adwords','AdWords','','CPC',0.00,'',1),(5,1,7,'aptv','Aptv','','CPA',0.00,'',1),(6,1,7,'buzzcity','Buzzcity','','CPC',0.00,'',1),(7,1,7,'vserv','VserV','','CPC',0.00,'',1);
/*!40000 ALTER TABLE `ads_publishers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `banners`
--

DROP TABLE IF EXISTS `banners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `banners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `path_thumb` varchar(255) DEFAULT NULL,
  `path_ori` varchar(255) DEFAULT NULL,
  `url_thumb` varchar(255) DEFAULT NULL,
  `url_ori` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `banners`
--

LOCK TABLES `banners` WRITE;
/*!40000 ALTER TABLE `banners` DISABLE KEYS */;
INSERT INTO `banners` VALUES (1,'Banners Test 1','Desc Banners Test 1','assets/images/thumbs/campaigns/img_5857.jpg','/var/www/html/cms-tools/assets/images/campaigns/img_5857.jpg','assets/images/thumbs/campaigns/img_5857.jpg','assets/images/campaigns/img_5857.jpg',1),(2,'Banner Web Campaign Test','','/var/www/html/cms-tools/assets/images/thumbs/campaigns/web-campaign-header-test.jpg','/var/www/html/cms-tools/assets/images/campaigns/web-campaign-header-test.jpg','assets/images/thumbs/campaigns/web-campaign-header-test.jpg','assets/images/campaigns/web-campaign-header-test.jpg',1);
/*!40000 ALTER TABLE `banners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `campaigns`
--

DROP TABLE IF EXISTS `campaigns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `campaigns` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_country` int(11) NOT NULL,
  `id_ads_publisher` int(11) NOT NULL,
  `id_campaign_media` int(11) NOT NULL,
  `id_campaign_category` int(11) NOT NULL,
  `id_banner` int(11) NOT NULL,
  `id_template` int(11) NOT NULL,
  `content` varchar(25) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `use_confirmation` tinyint(4) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campaigns`
--

LOCK TABLES `campaigns` WRITE;
/*!40000 ALTER TABLE `campaigns` DISABLE KEYS */;
INSERT INTO `campaigns` VALUES (1,1,1,1,1,1,1,'11543','Campaign Test 1','Just Test Campaign 1',1,1),(2,1,4,2,2,2,2,'556431','Campaign Test 2','',1,1);
/*!40000 ALTER TABLE `campaigns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `campaigns_categories`
--

DROP TABLE IF EXISTS `campaigns_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `campaigns_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campaigns_categories`
--

LOCK TABLES `campaigns_categories` WRITE;
/*!40000 ALTER TABLE `campaigns_categories` DISABLE KEYS */;
INSERT INTO `campaigns_categories` VALUES (1,'Perempuan Sexy','Category of Sexy Woman',1),(2,'Campaign Lucu','Description of Funny Campaign',1);
/*!40000 ALTER TABLE `campaigns_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `campaigns_details`
--

DROP TABLE IF EXISTS `campaigns_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `campaigns_details` (
  `id_campaign` int(11) NOT NULL,
  `id_partner_service` int(11) NOT NULL,
  `campaign_code` varchar(50) NOT NULL,
  PRIMARY KEY (`id_campaign`,`id_partner_service`),
  UNIQUE KEY `campaign_code` (`campaign_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campaigns_details`
--

LOCK TABLES `campaigns_details` WRITE;
/*!40000 ALTER TABLE `campaigns_details` DISABLE KEYS */;
INSERT INTO `campaigns_details` VALUES (1,4,'a050e27541a360a4f03d847d058a7c85'),(0,2,'b6d767d2f8ed5d21a44b0e5886680cb9'),(2,3,'e5636da1d2550237bf358e9b13f0fc5f');
/*!40000 ALTER TABLE `campaigns_details` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `campaigns_media`
--

DROP TABLE IF EXISTS `campaigns_media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `campaigns_media` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(25) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `campaigns_media`
--

LOCK TABLES `campaigns_media` WRITE;
/*!40000 ALTER TABLE `campaigns_media` DISABLE KEYS */;
INSERT INTO `campaigns_media` VALUES (1,'wap','Wap Campaign','Media for Wap Campaign',1),(2,'web','Web Campaign','Media for Web Campaign',1);
/*!40000 ALTER TABLE `campaigns_media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `countries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` char(2) NOT NULL,
  `prefix` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`,`code`,`prefix`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `countries`
--

LOCK TABLES `countries` WRITE;
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;
INSERT INTO `countries` VALUES (1,'ID',62,'Indonesia','Indonesia Country',1),(2,'MY',60,'Malaysia','Malaysia Country',1);
/*!40000 ALTER TABLE `countries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `currencies`
--

DROP TABLE IF EXISTS `currencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `currencies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_country` int(11) NOT NULL,
  `code` char(3) NOT NULL,
  `name` varchar(25) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `currencies`
--

LOCK TABLES `currencies` WRITE;
/*!40000 ALTER TABLE `currencies` DISABLE KEYS */;
INSERT INTO `currencies` VALUES (1,1,'IDR','Indonesian Rupiah',NULL,1),(2,2,'RM','Ringgit Malaysia',NULL,1);
/*!40000 ALTER TABLE `currencies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `domains`
--

DROP TABLE IF EXISTS `domains`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `domains` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_country` int(11) NOT NULL,
  `code` varchar(25) NOT NULL,
  `name` varchar(25) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `domains`
--

LOCK TABLES `domains` WRITE;
/*!40000 ALTER TABLE `domains` DISABLE KEYS */;
INSERT INTO `domains` VALUES (1,1,'8kotak','http://8kotak.com',NULL,1),(2,2,'8wapmas','http://8wapmas.com',NULL,1);
/*!40000 ALTER TABLE `domains` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `keyword_groups`
--

DROP TABLE IF EXISTS `keyword_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `keyword_groups` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_partner` int(11) NOT NULL,
  `code` varchar(25) NOT NULL,
  `name` varchar(25) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `keyword_groups`
--

LOCK TABLES `keyword_groups` WRITE;
/*!40000 ALTER TABLE `keyword_groups` DISABLE KEYS */;
INSERT INTO `keyword_groups` VALUES (1,2,'NEO','Group NEO',NULL,1);
/*!40000 ALTER TABLE `keyword_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `operators`
--

DROP TABLE IF EXISTS `operators`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `operators` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_country` int(11) NOT NULL,
  `utc_timezone` tinyint(4) NOT NULL,
  `code` varchar(25) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `prefix` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `operators`
--

LOCK TABLES `operators` WRITE;
/*!40000 ALTER TABLE `operators` DISABLE KEYS */;
INSERT INTO `operators` VALUES (1,1,7,'xl','XL','','62817,62818,62819,62859,62877,62878,62879,62831,62832,62838',1),(2,1,7,'tsel','Telkomsel','','62811,62812,62813,62821,62822,62823,62852,62853',1),(3,1,7,'isat','Indosat','','62814,62815,62816,62855,62856,62857,62858',1),(4,1,7,'hutch','Three (Hutchinson)','','62896,62897,62898,62899',1),(5,2,8,'maxis','Maxis Telecom','','',1),(6,2,8,'celcom','Celcom','','',1);
/*!40000 ALTER TABLE `operators` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `operators_apis`
--

DROP TABLE IF EXISTS `operators_apis`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `operators_apis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_operator` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `url_api` varchar(255) DEFAULT NULL,
  `type` enum('Redirect','Callback') DEFAULT NULL,
  `method` enum('POST','GET') DEFAULT NULL,
  `params_request` varchar(255) DEFAULT NULL,
  `params_response` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `operators_apis`
--

LOCK TABLES `operators_apis` WRITE;
/*!40000 ALTER TABLE `operators_apis` DISABLE KEYS */;
INSERT INTO `operators_apis` VALUES (1,1,'XL via Zing.bz with OTC','http://zing.bz/acm/Wap_action.php','Redirect','GET','dest={SHORTCODE}&content={KEYWORD} __{ADS-PUBLISHER}OTC__ {MT-ID}&status={URL-STATUS}','',1),(2,1,'XL via Optin','http://112.215.81.112:8080/Wap_action.jsp','Redirect','GET','dest={SHORTCODE}&content={KEYWORD} {CONTENT} {ADS-PUBLISHER} {MT-ID}&status={URL-STATUS}','',1),(3,1,'XL via Zing.bz','http://zing.bz/acm/Wap_action.php','Redirect','GET','dest={SHORTCODE}&content={KEYWORD} __{ADS-PUBLISHER}{CAMPAIGN-ID}__ {MT-ID}&status={URL-STATUS}','',1);
/*!40000 ALTER TABLE `operators_apis` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `operators_partners`
--

DROP TABLE IF EXISTS `operators_partners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `operators_partners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_operator` int(11) NOT NULL,
  `id_partner` int(11) NOT NULL,
  `share_operator` double(5,2) DEFAULT NULL,
  `share_partner` double(5,2) DEFAULT NULL,
  `vat` double(5,2) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `operators_partners`
--

LOCK TABLES `operators_partners` WRITE;
/*!40000 ALTER TABLE `operators_partners` DISABLE KEYS */;
INSERT INTO `operators_partners` VALUES (1,1,1,50.00,50.00,10.00,1),(2,1,2,50.00,50.00,10.00,1),(3,5,3,50.00,50.00,0.00,1),(4,5,4,50.00,50.00,0.00,1),(5,6,3,50.00,50.00,0.00,1),(6,6,4,50.00,50.00,0.00,1);
/*!40000 ALTER TABLE `operators_partners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partners`
--

DROP TABLE IF EXISTS `partners`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `partners` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_country` int(11) NOT NULL,
  `utc_timezone` tinyint(4) NOT NULL,
  `code` varchar(25) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partners`
--

LOCK TABLES `partners` WRITE;
/*!40000 ALTER TABLE `partners` DISABLE KEYS */;
INSERT INTO `partners` VALUES (1,1,7,'ZM','Zingmobile','PT. Media Kreasindo Utama (Zingmobile Indonesia)',1),(2,1,7,'NEO','Neo','PT. Media Kreasindo Utama (Neo)',1),(3,2,8,'zeptomobile','Zeptomobile Sdn Bhd','PT. CP1',1),(4,2,8,'megamobile','Megamobile Solutions Sdn Bhd','PT. CP2',1);
/*!40000 ALTER TABLE `partners` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partners_services`
--

DROP TABLE IF EXISTS `partners_services`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `partners_services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `id_operator` int(11) NOT NULL,
  `id_operator_api` int(11) NOT NULL,
  `id_partner` int(11) NOT NULL,
  `id_shortcode` int(11) NOT NULL,
  `id_price` int(11) NOT NULL,
  `id_service_type` int(11) NOT NULL,
  `id_keyword_group` int(11) NOT NULL,
  `sid` varchar(25) NOT NULL,
  `keyword` varchar(25) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_partner` (`id_partner`,`id_shortcode`,`id_price`,`sid`,`keyword`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partners_services`
--

LOCK TABLES `partners_services` WRITE;
/*!40000 ALTER TABLE `partners_services` DISABLE KEYS */;
INSERT INTO `partners_services` VALUES (1,'c1e39d912d21c91dce811d6da9929ae8',1,2,2,7,13,1,1,'955801','Neo',1),(2,'c1e39d912d21c91dce811d6da9929ae8',1,2,2,7,14,1,1,'955803','Neo1',1),(3,'c1e39d912d21c91dce811d6da9929ae8',1,2,2,7,15,1,1,'955805','Neo2',1),(4,'c1e39d912d21c91dce811d6da9929ae8',1,2,2,7,16,1,1,'955807','Neo3',1),(5,'c1e39d912d21c91dce811d6da9929ae8',1,2,2,7,17,1,1,'955808','Neo5',1);
/*!40000 ALTER TABLE `partners_services` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `prices`
--

DROP TABLE IF EXISTS `prices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `prices` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_currency` int(11) NOT NULL,
  `value` double(10,2) NOT NULL,
  `value_with_tax` double(10,2) DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `prices`
--

LOCK TABLES `prices` WRITE;
/*!40000 ALTER TABLE `prices` DISABLE KEYS */;
INSERT INTO `prices` VALUES (1,2,30.00,33.00,1),(2,2,50.00,55.00,1),(3,2,100.00,110.00,1),(4,2,150.00,165.00,1),(5,2,200.00,220.00,1),(6,2,250.00,275.00,1),(7,2,300.00,330.00,1),(8,2,350.00,385.00,1),(9,2,400.00,440.00,1),(10,2,450.00,495.00,1),(11,2,500.00,550.00,1),(12,1,0.00,0.00,1),(13,1,500.00,550.00,1),(14,1,1000.00,1100.00,1),(15,1,2000.00,2200.00,1),(16,1,3000.00,3300.00,1),(17,1,5000.00,5500.00,1),(18,1,8000.00,8800.00,1),(19,1,10000.00,11000.00,1),(20,1,15000.00,16500.00,1);
/*!40000 ALTER TABLE `prices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `services_types`
--

DROP TABLE IF EXISTS `services_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `services_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `services_types`
--

LOCK TABLES `services_types` WRITE;
/*!40000 ALTER TABLE `services_types` DISABLE KEYS */;
INSERT INTO `services_types` VALUES (1,'One time purchase','',1),(2,'Subscription','',1);
/*!40000 ALTER TABLE `services_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `shortcodes`
--

DROP TABLE IF EXISTS `shortcodes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `shortcodes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `shortcodes`
--

LOCK TABLES `shortcodes` WRITE;
/*!40000 ALTER TABLE `shortcodes` DISABLE KEYS */;
INSERT INTO `shortcodes` VALUES (1,'32599',NULL,1),(2,'23273',NULL,1),(3,'33293',NULL,1),(4,'32266',NULL,1),(5,'32599',NULL,1),(6,'5526','',1),(7,'9558','',1),(8,'32666','',1),(9,'32665','',1),(10,'9887','',1),(11,'9699','',1),(12,'99787','',1),(13,'99555','',1),(14,'89887','',1);
/*!40000 ALTER TABLE `shortcodes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `templates`
--

DROP TABLE IF EXISTS `templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `templates` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_country` int(11) NOT NULL,
  `id_campaign_media` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `page_confirm` text,
  `page_status` text,
  `page_error` text,
  `is_uploaded` tinyint(4) NOT NULL,
  `status` tinyint(4) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `templates`
--

LOCK TABLES `templates` WRITE;
/*!40000 ALTER TABLE `templates` DISABLE KEYS */;
INSERT INTO `templates` VALUES (1,1,1,'Wap Default 1','','&lt;html xmlns=&quot;http://www.w3.org/1999/xhtml&quot;&gt;\n&lt;head&gt;\n	&lt;title&gt;{TITLE-ADS}&lt;/title&gt;\n	&lt;meta http-equiv=&quot;Cache-Control&quot; content=&quot;max-age=0&quot;/&gt;&lt;meta name=&quot;viewport&quot; content=&quot;width=device-width, initial-scale=1.0&quot;&gt;\n	&lt;style type=&quot;text/css&quot;&gt;a {\n				text-decoration: none;\n				color: #000;\n			}\n			body {\n				color: #000;\n				font-family:Arial, Helvetica, sans-serif;\n				font-size:12px;\n			}			\n			.button {\n				-webkit-box-shadow: rgb(240, 247, 250) 0px 1px 0px 0px; \n				box-shadow: rgb(240, 247, 250) 0px 1px 0px 0px; \n				border-radius: 6px; border: 1px solid rgb(5, 127, 208); \n				display: inline-block; cursor: pointer; color: rgb(255, 255, 255); \n				font-family: arial; font-size: 15px; font-weight: bold; \n				padding: 6px 24px; text-decoration: none; \n				text-shadow: rgb(91, 97, 120) 0px -1px 0px; \n				/*background: linear-gradient(rgb(51, 189, 239) 5%, rgb(1, 154, 210) 100%) rgb(51, 189, 239);*/\n				background-color: #0099FF;\n   \n			}\n	&lt;/style&gt;\n&lt;/head&gt;\n&lt;body&gt;\n&lt;center&gt;&lt;label style=&quot;font-family:Arial, Helvetica, sans-serif;font-weight:bold&quot;&gt;Ambil Konten Kamu&lt;/label&gt;&lt;br /&gt;\n&lt;a href=&quot;{URL-ADS}&quot;&gt;&lt;span class=&quot;button&quot;&gt;Klik Disini&lt;/span&gt;&lt;br /&gt;\n&lt;br /&gt;\n{BANNER-ADS}&lt;/a&gt;&lt;/center&gt;\n\n&lt;center&gt;\n&lt;p&gt;&lt;a href=&quot;{URL-ADS}&quot;&gt;Bukan Layanan Berlangganan&lt;/a&gt;&lt;/p&gt;\n\n&lt;h4&gt;&lt;a href=&quot;{URL-ADS}&quot;&gt;Tarif Rp. {PRICE-ADS}&lt;/a&gt;&lt;/h4&gt;\n\n&lt;p&gt;&lt;a href=&quot;{URL-ADS}&quot;&gt;Apabila pulsa tidak mencukupi, paket akan turun ke Rp2500/7hr atau ke Rp1000/hr&lt;br /&gt;\nCS: 0215764122&lt;/a&gt;&lt;/p&gt;\n\n&lt;p&gt;&lt;a href=&quot;{URL-ADS}&quot;&gt;HADIAH TOTAL JUTAAN RUPIAH SEGERA MENANTI ANDA! &lt;/a&gt;&lt;/p&gt;\n&lt;/center&gt;\n&lt;/body&gt;\n&lt;/html&gt;\n','&lt;html xmlns=&quot;http://www.w3.org/1999/xhtml&quot;&gt;\n&lt;head&gt;\n	&lt;title&gt;{TITLE-ADS}&lt;/title&gt;\n	&lt;meta http-equiv=&quot;Cache-Control&quot; content=&quot;max-age=0&quot;/&gt;\n	&lt;style type=&quot;text/css&quot;&gt;a {\n				text-decoration: none;\n				color: #000;\n			}\n			body {\n				color: #000;\n				font-family:Arial, Helvetica, sans-serif;\n				font-size:12px;\n			}\n	&lt;/style&gt;\n&lt;/head&gt;\n&lt;body&gt;\n&lt;center&gt;Terima kasih telah membeli.&lt;br /&gt;\nsenilai Rp. {PRICE-ADS}.&lt;br /&gt;\nKamu akan menerima&lt;br /&gt;\nSMS untuk&lt;br /&gt;\nmendownload konten tersebut.\n&lt;p style=&quot;font-size:10px&quot;&gt;Konten disediakan oleh&lt;br /&gt;\ndengan pembayaran menggunakan pulsa {TELCO-ADS}.&lt;br /&gt;\nTarif GPRS berlaku Normal. Customer service : 0215764122&lt;/p&gt;\n\n&lt;div&gt;&lt;br /&gt;\n{CROSSELL-ADS}&lt;/div&gt;\n&lt;/center&gt;\n&lt;/body&gt;\n&lt;/html&gt;\n','',0,1),(2,1,2,'Web Template Default 1','','&lt;!DOCTYPE html PUBLIC &quot;-//W3C//DTD XHTML 1.0 Transitional//EN&quot; &quot;http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd&quot;&gt;\n&lt;html xmlns=&quot;http://www.w3.org/1999/xhtml&quot;&gt;\n&lt;head&gt;\n&lt;base href=&quot;http://localhost/cms-tools/&quot;&gt;\n&lt;meta name=&quot;viewport&quot; content=&quot;width=480, target-densitydpi=device-dpi, user-scalable=no&quot;&gt;&lt;meta http-equiv=&quot;X-UA-Compatible&quot; content=&quot;IE=edge,chrome=1&quot;&gt;&lt;meta http-equiv=&quot;content-type&quot; content=&quot;text/html; charset=utf-8&quot; /&gt;\n	&lt;title&gt;&lt;/title&gt;\n	&lt;script src=&quot;http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js&quot;&gt;&lt;/script&gt;\n	&lt;style type=&quot;text/css&quot;&gt;body{\n	margin:0;\n	padding:0;\n	background-color:{BACKGROUND-ADS};\n	}\n\nimg{\n	border:0px;\n	}\n	\n\n.main_wrap{\n	width:100%;\n	height:auto;\n	padding-bottom:100px;\n	background:{BACKGROUND-ADS};\n	}\n\n.Title{\n	font-family:Arial, Helvetica, sans-serif;\n	font-size:26px;\n	color:#1f2020;\n	font-weight:normal;\n	font-style: italic; \n	display:block;\n	clear:both;\n	padding:0px 0px 5px 0px;\n	}\n\n.numbersOnly{\n	color:#000000;\n	font-family: Arial, Helvetica, sans-serif;\n	font-size:45px;\n	font-weight:bold;\n	padding:0px 7px;\n	text-align:left;\n	width:278px;\n	height:47px;\n	margin-bottom:5px;\n	margin-left:35px;\n	}\n	\n.phoneNoTitle{\n	font-family:Arial, Helvetica, sans-serif;\n	font-size:23px;\n	color:#FFFFFF;\n	font-weight:normal;\n	font-style:italic;\n	display:block;\n	clear:both;\n	padding:0px 0px 5px 0px;\n}\n\n.tnc{\n	font-family:Arial, Helvetica, sans-serif;\n	font-size:11px;\n	color:#999999;\n	font-weight:normal;\n	text-align:justify;\n	padding: 0px 20px 0px 20px;\n	}\n	\n.link{\n	font-size:14px;\n}\n	\n	.button {			\n				width: 200px;\n				height: 40px;\n				-webkit-box-shadow: rgb(240, 247, 250) 0px 1px 0px 0px; \n				box-shadow: rgb(240, 247, 250) 0px 1px 0px 0px; \n				border-radius: 25px; border: 1px solid rgb(5, 127, 208); \n				display: inline-block; cursor: pointer; color: rgb(255, 255, 255); \n				font-family: arial; font-size: 20px; font-weight: bold; \n				padding: 24px 24px 5px 24px; text-decoration: none; \n				text-shadow: rgb(91, 97, 120) 0px -1px 0px; \n				background: linear-gradient(rgb(51, 189, 239) 5%, rgb(1, 154, 210) 100%) rgb(51, 189, 239);\n			}\n	&lt;/style&gt;\n	&lt;script language=&quot;JavaScript&quot; type=&quot;text/javascript&quot;&gt;\n\n    $(document).ready(function(){\n		$(\'.numbersOnly\').keyup(function () { \n			this.value = this.value.replace(/[^0-9\\.]/g,\'\');\n		});	\n	});\n	\n	window.onload=function(e)\n	{\n		var urlarr = document.URL.split(\'/\');\n		var formid=urlarr[urlarr.length - 2];\n		var newdiv = document.createElement(\'span\');\n		newdiv.innerHTML =\'&lt;input type=&quot;hidden&quot; name=&quot;formid&quot; value=&quot;\'+formid+\'&quot;&gt;&lt;input type=&quot;hidden&quot; name=&quot;sendme&quot; value=&quot;101&quot;&gt;&lt;input type=&quot;hidden&quot; name=&quot;referreid&quot; value=&quot;\'+document.URL+\'&quot;&gt;\';\n		document.getElementById(\'myform\').appendChild(newdiv);\n	}\n&lt;/script&gt;\n&lt;/head&gt;\n&lt;body&gt;\n&lt;center&gt;\n&lt;form action=&quot;http://land.zingmobiledev.com/admin/msubmit.php&quot; id=&quot;myform&quot; method=&quot;post&quot; name=&quot;msisdnsubmit&quot;&gt;\n&lt;div class=&quot;main_wrap&quot;&gt;\n&lt;table border=&quot;0&quot; cellpadding=&quot;0&quot; cellspacing=&quot;0&quot; width=&quot;478&quot;&gt;\n	&lt;tbody&gt;\n		&lt;tr&gt;\n			&lt;td align=&quot;center&quot; valign=&quot;top&quot;&gt;{HEADER-ADS}&lt;/td&gt;\n		&lt;/tr&gt;\n		&lt;tr&gt;\n			&lt;td align=&quot;center&quot; height=&quot;142&quot; valign=&quot;top&quot; width=&quot;478&quot;&gt;{BANNER-ADS}&lt;/td&gt;\n		&lt;/tr&gt;\n		&lt;tr&gt;\n			&lt;td align=&quot;center&quot; bgcolor=&quot;#1f2020&quot; class=&quot;Title&quot; height=&quot;26&quot; valign=&quot;top&quot;&gt;&amp;nbsp;&lt;/td&gt;\n		&lt;/tr&gt;\n		&lt;tr align=&quot;center&quot;&gt;\n			&lt;td align=&quot;center&quot; bgcolor=&quot;#1F2020&quot; height=&quot;60&quot; valign=&quot;top&quot;&gt;&lt;span class=&quot;phoneNoTitle&quot;&gt;{TEXT-ADS}&lt;/span&gt; &lt;input class=&quot;numbersOnly&quot; id=&quot;msisdn&quot; maxlength=&quot;11&quot; name=&quot;msisdn&quot; type=&quot;tel&quot; value=&quot;{PREFIX-ADS}&quot; /&gt; &lt;input id=&quot;formid&quot; name=&quot;formid&quot; type=&quot;hidden&quot; value=&quot;&quot; /&gt; &lt;input name=&quot;redirect&quot; type=&quot;hidden&quot; value=&quot;details.html&quot; /&gt;&lt;/td&gt;\n		&lt;/tr&gt;\n		&lt;tr&gt;\n			&lt;td align=&quot;center&quot; bgcolor=&quot;#1f2020&quot; height=&quot;105&quot; valign=&quot;middle&quot;&gt;&lt;a class=&quot;button&quot; onclick=&quot;checkmsisdn();&quot; style=&quot;cursor:pointer;&quot; title=&quot;Download&quot;&gt;{BUTTON-ADS}&lt;/a&gt;&lt;/td&gt;\n		&lt;/tr&gt;\n		&lt;script&gt;\n        urlarr = document.URL.split(\'/\');\n        document.getElementById(\'formid\').value = urlarr[urlarr.length - 2];\n        function checkmsisdn() {\n            var msisdn = document.getElementById(\'msisdn\').value;\n            if (msisdn == \'\' || ((msisdn.length &lt; 8) || (msisdn.length &gt; 11))) {\n                alert(\'Please enter valid phone number\');\n                return false;\n            }else{				\n                window.onbeforeunload = null\n                document.getElementById(\'myform\').submit();         \n            }\n        }    \n	  &lt;/script&gt;\n	&lt;/tbody&gt;\n&lt;/table&gt;\n\n&lt;table bgcolor=&quot;#1f2020&quot; border=&quot;0&quot; cellpadding=&quot;0&quot; cellspacing=&quot;0&quot; width=&quot;478&quot;&gt;\n	&lt;tbody&gt;\n		&lt;tr&gt;\n			&lt;td align=&quot;center&quot; class=&quot;tnc&quot; colspan=&quot;4&quot; height=&quot;174&quot; valign=&quot;top&quot;&gt;{DESCRIPTION-ADS}&lt;/td&gt;\n		&lt;/tr&gt;\n		&lt;tr&gt;\n			&lt;td align=&quot;center&quot; colspan=&quot;4&quot; valign=&quot;top&quot;&gt;{FOOTER-ADS}&lt;/td&gt;\n		&lt;/tr&gt;\n	&lt;/tbody&gt;\n&lt;/table&gt;\n&lt;/div&gt;\n&lt;/form&gt;\n&lt;/center&gt;\n&lt;/body&gt;\n&lt;/html&gt;','&lt;!DOCTYPE html PUBLIC &quot;-//W3C//DTD XHTML 1.0 Transitional//EN&quot; &quot;http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd&quot;&gt;\n&lt;html xmlns=&quot;http://www.w3.org/1999/xhtml&quot;&gt;\n&lt;head&gt;\n&lt;base href=&quot;http://localhost/cms-tools/&quot;&gt;\n&lt;meta name=&quot;viewport&quot; content=&quot;width=480, target-densitydpi=device-dpi, user-scalable=no&quot;&gt;&lt;meta http-equiv=&quot;X-UA-Compatible&quot; content=&quot;IE=edge,chrome=1&quot;&gt;&lt;meta http-equiv=&quot;content-type&quot; content=&quot;text/html; charset=utf-8&quot; /&gt;\n	&lt;title&gt;&lt;/title&gt;\n	&lt;script src=&quot;http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js&quot;&gt;&lt;/script&gt;\n	&lt;style type=&quot;text/css&quot;&gt;body{\n	margin:0;\n	padding:0;\n	background-color:{BACKGROUND-ADS};\n	}\n\nimg{\n	border:0px;\n	}\n	\n\n.main_wrap{\n	width:100%;\n	height:auto;\n	padding-bottom:100px;\n	background:{BACKGROUND-ADS};\n	}\n\n.Title{\n	font-family:Arial, Helvetica, sans-serif;\n	font-size:26px;\n	color:#1f2020;\n	font-weight:normal;\n	font-style: italic; \n	display:block;\n	clear:both;\n	padding:0px 0px 5px 0px;\n	}\n\n.numbersOnly{\n	color:#000000;\n	font-family: Arial, Helvetica, sans-serif;\n	font-size:45px;\n	font-weight:bold;\n	padding:0px 7px;\n	text-align:left;\n	width:278px;\n	height:47px;\n	margin-bottom:5px;\n	margin-left:35px;\n	}\n	\n.phoneNoTitle{\n	font-family:Arial, Helvetica, sans-serif;\n	font-size:20px;\n	color:#FFFFFF;\n	font-weight:normal;\n	font-style:italic;\n	display:block;\n	clear:both;\n	padding:0px 0px 5px 0px;\n}\n\n.tnc{\n	font-family:Arial, Helvetica, sans-serif;\n	font-size:11px;\n	color:#999999;\n	font-weight:normal;\n	text-align:justify;\n	padding: 0px 20px 0px 20px;\n	}\n	\n.link{\n	font-size:14px;\n}\n	\n	.button {			\n				width: 150px;\n				height: 25px;\n				-webkit-box-shadow: rgb(240, 247, 250) 0px 1px 0px 0px; \n				box-shadow: rgb(240, 247, 250) 0px 1px 0px 0px; \n				border-radius: 25px; border: 1px solid rgb(5, 127, 208); \n				display: inline-block; cursor: pointer; color: rgb(255, 255, 255); \n				font-family: arial; font-size: 17px; font-weight: bold; \n				padding: 20px 10px 13px 10px; text-decoration: none; \n				text-shadow: rgb(91, 97, 120) 0px -1px 0px; \n				background: linear-gradient(rgb(51, 189, 239) 5%, rgb(1, 154, 210) 100%) rgb(51, 189, 239);\n			}\n	&lt;/style&gt;\n	&lt;script language=&quot;JavaScript&quot; type=&quot;text/javascript&quot;&gt;\n\n    $(document).ready(function(){\n		$(\'.numbersOnly\').keyup(function () { \n			this.value = this.value.replace(/[^0-9\\.]/g,\'\');\n		});	\n	});\n	\n	window.onload=function(e)\n	{\n		var urlarr = document.URL.split(\'/\');\n		var formid=urlarr[urlarr.length - 2];\n		var newdiv = document.createElement(\'span\');\n		newdiv.innerHTML =\'&lt;input type=&quot;hidden&quot; name=&quot;formid&quot; value=&quot;\'+formid+\'&quot;&gt;&lt;input type=&quot;hidden&quot; name=&quot;sendme&quot; value=&quot;101&quot;&gt;&lt;input type=&quot;hidden&quot; name=&quot;referreid&quot; value=&quot;\'+document.URL+\'&quot;&gt;\';\n		document.getElementById(\'myform\').appendChild(newdiv);\n	}\n&lt;/script&gt;\n&lt;/head&gt;\n&lt;body&gt;\n&lt;center&gt;\n&lt;form action=&quot;http://land.zingmobiledev.com/admin/msubmit.php&quot; id=&quot;myform&quot; method=&quot;post&quot; name=&quot;msisdnsubmit&quot;&gt;\n&lt;div class=&quot;main_wrap&quot;&gt;\n&lt;table border=&quot;0&quot; cellpadding=&quot;0&quot; cellspacing=&quot;0&quot; width=&quot;478&quot;&gt;\n	&lt;tbody&gt;\n		&lt;tr&gt;\n			&lt;td align=&quot;center&quot; valign=&quot;top&quot;&gt;{HEADER-ADS}&lt;/td&gt;\n		&lt;/tr&gt;\n		&lt;tr&gt;\n			&lt;td align=&quot;center&quot; height=&quot;142&quot; valign=&quot;top&quot; width=&quot;478&quot;&gt;{BANNER-ADS}&lt;/td&gt;\n		&lt;/tr&gt;\n		&lt;tr&gt;\n			&lt;td align=&quot;center&quot; bgcolor=&quot;#1f2020&quot; class=&quot;Title&quot; height=&quot;26&quot; valign=&quot;top&quot;&gt;&amp;nbsp;&lt;/td&gt;\n		&lt;/tr&gt;\n		&lt;tr align=&quot;center&quot;&gt;\n			&lt;td align=&quot;center&quot; bgcolor=&quot;#1F2020&quot; valign=&quot;top&quot;&gt;&lt;span class=&quot;phoneNoTitle&quot;&gt;{TEXT-ADS}&lt;/span&gt;&lt;/td&gt;\n		&lt;/tr&gt;\n		&lt;tr align=&quot;center&quot;&gt;\n			&lt;td align=&quot;center&quot; bgcolor=&quot;#1F2020&quot; height=&quot;60&quot; valign=&quot;top&quot;&gt;&lt;span class=&quot;phoneNoTitle&quot;&gt;SMS &lt;font style=&quot;font-size:25px&quot;&gt;{KEYWORD-ADS}&lt;/font&gt; To &lt;font style=&quot;font-size:25px&quot;&gt;{SHORTCODE-ADS}&lt;/font&gt;&lt;/span&gt;&lt;/td&gt;\n		&lt;/tr&gt;\n		&lt;tr&gt;\n			&lt;td align=&quot;center&quot; bgcolor=&quot;#1f2020&quot; height=&quot;105&quot; valign=&quot;middle&quot;&gt;&lt;a class=&quot;button&quot; onclick=&quot;checkmsisdn();&quot; style=&quot;cursor:pointer;&quot; title=&quot;Download&quot;&gt;{BUTTON-ADS}&lt;/a&gt;&lt;/td&gt;\n		&lt;/tr&gt;\n		&lt;script&gt;\n        urlarr = document.URL.split(\'/\');\n        document.getElementById(\'formid\').value = urlarr[urlarr.length - 2];\n        function checkmsisdn() {\n            var msisdn = document.getElementById(\'msisdn\').value;\n            if (msisdn == \'\' || ((msisdn.length &lt; 8) || (msisdn.length &gt; 11))) {\n                alert(\'Please enter valid phone number\');\n                return false;\n            }else{				\n                window.onbeforeunload = null\n                document.getElementById(\'myform\').submit();         \n            }\n        }    \n	  &lt;/script&gt;\n	&lt;/tbody&gt;\n&lt;/table&gt;\n\n&lt;table bgcolor=&quot;#1f2020&quot; border=&quot;0&quot; cellpadding=&quot;0&quot; cellspacing=&quot;0&quot; width=&quot;478&quot;&gt;\n	&lt;tbody&gt;\n		&lt;tr&gt;\n			&lt;td align=&quot;center&quot; class=&quot;tnc&quot; colspan=&quot;4&quot; height=&quot;174&quot; valign=&quot;top&quot;&gt;{DESCRIPTION-ADS}&lt;/td&gt;\n		&lt;/tr&gt;\n		&lt;tr&gt;\n			&lt;td align=&quot;center&quot; colspan=&quot;4&quot; valign=&quot;top&quot;&gt;{FOOTER-ADS}&lt;/td&gt;\n		&lt;/tr&gt;\n	&lt;/tbody&gt;\n&lt;/table&gt;\n&lt;/div&gt;\n&lt;/form&gt;\n&lt;/center&gt;\n&lt;/body&gt;\n&lt;/html&gt;','&lt;html&gt;\n&lt;head&gt;\n	&lt;title&gt;&lt;/title&gt;\n&lt;/head&gt;\n&lt;body&gt;&lt;/body&gt;\n&lt;/html&gt;\n',0,1),(5,1,2,'Templates Static Default Test 1','','&lt;!DOCTYPE html PUBLIC &quot;-//W3C//DTD XHTML 1.0 Transitional//EN&quot; &quot;http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd&quot;&gt;\n&lt;html xmlns=&quot;http://www.w3.org/1999/xhtml&quot;&gt;\n&lt;head&gt;\n&lt;base href=&quot;http://localhost/cms-tools/&quot;&gt;\n&lt;meta name=&quot;viewport&quot; content=&quot;width=480, target-densitydpi=device-dpi, user-scalable=no&quot;&gt;&lt;meta http-equiv=&quot;X-UA-Compatible&quot; content=&quot;IE=edge,chrome=1&quot;&gt;&lt;meta http-equiv=&quot;content-type&quot; content=&quot;text/html; charset=utf-8&quot; /&gt;\n	&lt;title&gt;&lt;/title&gt;\n	&lt;script src=&quot;http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js&quot;&gt;&lt;/script&gt;\n	&lt;style type=&quot;text/css&quot;&gt;body{\n	margin:0;\n	padding:0;\n	background-color:{BACKGROUND-ADS};\n	}\n\nimg{\n	border:0px;\n	}\n	\n\n.main_wrap{\n	width:100%;\n	height:auto;\n	padding-bottom:100px;\n	background:{BACKGROUND-ADS};\n	}\n\n.Title{\n	font-family:Arial, Helvetica, sans-serif;\n	font-size:26px;\n	color:#1f2020;\n	font-weight:normal;\n	font-style: italic; \n	display:block;\n	clear:both;\n	padding:0px 0px 5px 0px;\n	}\n\n.numbersOnly{\n	color:#000000;\n	font-family: Arial, Helvetica, sans-serif;\n	font-size:45px;\n	font-weight:bold;\n	padding:0px 7px;\n	text-align:left;\n	width:278px;\n	height:47px;\n	margin-bottom:5px;\n	margin-left:35px;\n	}\n	\n.phoneNoTitle{\n	font-family:Arial, Helvetica, sans-serif;\n	font-size:23px;\n	color:#FFFFFF;\n	font-weight:normal;\n	font-style:italic;\n	display:block;\n	clear:both;\n	padding:0px 0px 5px 0px;\n}\n\n.tnc{\n	font-family:Arial, Helvetica, sans-serif;\n	font-size:11px;\n	color:#999999;\n	font-weight:normal;\n	text-align:justify;\n	padding: 0px 20px 0px 20px;\n	}\n	\n.link{\n	font-size:14px;\n}\n	\n	.button {			\n				width: 200px;\n				height: 40px;\n				-webkit-box-shadow: rgb(240, 247, 250) 0px 1px 0px 0px; \n				box-shadow: rgb(240, 247, 250) 0px 1px 0px 0px; \n				border-radius: 25px; border: 1px solid rgb(5, 127, 208); \n				display: inline-block; cursor: pointer; color: rgb(255, 255, 255); \n				font-family: arial; font-size: 20px; font-weight: bold; \n				padding: 24px 24px 5px 24px; text-decoration: none; \n				text-shadow: rgb(91, 97, 120) 0px -1px 0px; \n				background: linear-gradient(rgb(51, 189, 239) 5%, rgb(1, 154, 210) 100%) rgb(51, 189, 239);\n			}\n	&lt;/style&gt;\n	&lt;script language=&quot;JavaScript&quot; type=&quot;text/javascript&quot;&gt;\n\n    $(document).ready(function(){\n		$(\'.numbersOnly\').keyup(function () { \n			this.value = this.value.replace(/[^0-9\\.]/g,\'\');\n		});	\n	});\n	\n	window.onload=function(e)\n	{\n		var urlarr = document.URL.split(\'/\');\n		var formid=urlarr[urlarr.length - 2];\n		var newdiv = document.createElement(\'span\');\n		newdiv.innerHTML =\'&lt;input type=&quot;hidden&quot; name=&quot;formid&quot; value=&quot;\'+formid+\'&quot;&gt;&lt;input type=&quot;hidden&quot; name=&quot;sendme&quot; value=&quot;101&quot;&gt;&lt;input type=&quot;hidden&quot; name=&quot;referreid&quot; value=&quot;\'+document.URL+\'&quot;&gt;\';\n		document.getElementById(\'myform\').appendChild(newdiv);\n	}\n&lt;/script&gt;\n&lt;/head&gt;\n&lt;body&gt;\n&lt;center&gt;\n&lt;form action=&quot;http://land.zingmobiledev.com/admin/msubmit.php&quot; id=&quot;myform&quot; method=&quot;post&quot; name=&quot;msisdnsubmit&quot;&gt;\n&lt;div class=&quot;main_wrap&quot;&gt;\n&lt;table border=&quot;0&quot; cellpadding=&quot;0&quot; cellspacing=&quot;0&quot; width=&quot;478&quot;&gt;\n	&lt;tbody&gt;\n		&lt;tr&gt;\n			&lt;td align=&quot;center&quot; valign=&quot;top&quot;&gt;&amp;nbsp;&lt;/td&gt;\n		&lt;/tr&gt;\n		&lt;tr&gt;\n			&lt;td align=&quot;center&quot; height=&quot;142&quot; valign=&quot;top&quot; width=&quot;478&quot;&gt;&lt;img src=&quot;assets/images/campaigns/web-campaign-header-test.jpg&quot; /&gt;&lt;/td&gt;\n		&lt;/tr&gt;\n		&lt;tr&gt;\n			&lt;td align=&quot;center&quot; bgcolor=&quot;#1f2020&quot; class=&quot;Title&quot; height=&quot;26&quot; valign=&quot;top&quot;&gt;&amp;nbsp;&lt;/td&gt;\n		&lt;/tr&gt;\n		&lt;tr align=&quot;center&quot;&gt;\n			&lt;td align=&quot;center&quot; bgcolor=&quot;#1F2020&quot; height=&quot;60&quot; valign=&quot;top&quot;&gt;&lt;span class=&quot;phoneNoTitle&quot;&gt;Masukkan nomor handphone Anda&lt;/span&gt; &lt;input class=&quot;numbersOnly&quot; id=&quot;msisdn&quot; maxlength=&quot;11&quot; name=&quot;msisdn&quot; type=&quot;tel&quot; value=&quot;62&quot; /&gt; &lt;input id=&quot;formid&quot; name=&quot;formid&quot; type=&quot;hidden&quot; value=&quot;&quot; /&gt; &lt;input name=&quot;redirect&quot; type=&quot;hidden&quot; value=&quot;details.html&quot; /&gt;&lt;/td&gt;\n		&lt;/tr&gt;\n		&lt;tr&gt;\n			&lt;td align=&quot;center&quot; bgcolor=&quot;#1f2020&quot; height=&quot;105&quot; valign=&quot;middle&quot;&gt;&lt;a class=&quot;button&quot; onclick=&quot;checkmsisdn();&quot; style=&quot;cursor:pointer;&quot; title=&quot;Download&quot;&gt;Unduh&lt;/a&gt;&lt;/td&gt;\n		&lt;/tr&gt;\n		&lt;script&gt;\n        urlarr = document.URL.split(\'/\');\n        document.getElementById(\'formid\').value = urlarr[urlarr.length - 2];\n        function checkmsisdn() {\n            var msisdn = document.getElementById(\'msisdn\').value;\n            if (msisdn == \'\' || ((msisdn.length &lt; 8) || (msisdn.length &gt; 11))) {\n                alert(\'Please enter valid phone number\');\n                return false;\n            }else{				\n                window.onbeforeunload = null\n                document.getElementById(\'myform\').submit();         \n            }\n        }    \n	  &lt;/script&gt;\n	&lt;/tbody&gt;\n&lt;/table&gt;\n\n&lt;table bgcolor=&quot;#1f2020&quot; border=&quot;0&quot; cellpadding=&quot;0&quot; cellspacing=&quot;0&quot; width=&quot;478&quot;&gt;\n	&lt;tbody&gt;\n		&lt;tr&gt;\n			&lt;td align=&quot;center&quot; class=&quot;tnc&quot; colspan=&quot;4&quot; height=&quot;174&quot; valign=&quot;top&quot;&gt;Please read these &amp;ldquo;General Terms and Conditions&amp;rdquo; carefully. This is an ongoing wallpaper subscription service until you quit. All subscription contents are compatible with 3G/GPRS/WAP-enabled mobile phones and applicable to both postpaid and prepaid users. Supported mobile brands include Samsung, Sony, HTC, Sony Ericsson, Nokia and more. No subscription fees will be charged. RM4 per message, less than 4 time per week. Upon subscribing to the service, you will receive 2 contents per day and subsequent week; the content shall be delivered 4 times a week. Regular mobile operator network charges apply. GPRS / 3G access need to be enable to download the content. Data charges are billed separately. Please seek parental or guardian approval if you are below 18 years old. Upon sending in the SMS to the shortcode as per advertisement, you are acknowledged that you have read and understood the &amp;ldquo;General Terms &amp;amp; Conditions&amp;rdquo;. To cancel, send STOP SX to 33293. Helpdesk 03-7727 2452 (9am-5pm, Mon-Fri). This service is operates as according to the Malaysia Code of Conduct for the SMS services.&lt;/td&gt;\n		&lt;/tr&gt;\n		&lt;tr&gt;\n			&lt;td align=&quot;center&quot; colspan=&quot;4&quot; valign=&quot;top&quot;&gt;Powered by Zeptomobile Sdn. Bhd.&lt;/td&gt;\n		&lt;/tr&gt;\n	&lt;/tbody&gt;\n&lt;/table&gt;\n&lt;/div&gt;\n&lt;/form&gt;\n&lt;/center&gt;\n&lt;/body&gt;\n&lt;/html&gt;','&lt;!DOCTYPE html PUBLIC &quot;-//W3C//DTD XHTML 1.0 Transitional//EN&quot; &quot;http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd&quot;&gt;\n&lt;html xmlns=&quot;http://www.w3.org/1999/xhtml&quot;&gt;\n&lt;head&gt;\n&lt;base href=&quot;http://localhost/cms-tools/&quot;&gt;\n&lt;meta name=&quot;viewport&quot; content=&quot;width=480, target-densitydpi=device-dpi, user-scalable=no&quot;&gt;&lt;meta http-equiv=&quot;X-UA-Compatible&quot; content=&quot;IE=edge,chrome=1&quot;&gt;&lt;meta http-equiv=&quot;content-type&quot; content=&quot;text/html; charset=utf-8&quot; /&gt;\n	&lt;title&gt;&lt;/title&gt;\n	&lt;script src=&quot;http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js&quot;&gt;&lt;/script&gt;\n	&lt;style type=&quot;text/css&quot;&gt;body{\n	margin:0;\n	padding:0;\n	background-color:{BACKGROUND-ADS};\n	}\n\nimg{\n	border:0px;\n	}\n	\n\n.main_wrap{\n	width:100%;\n	height:auto;\n	padding-bottom:100px;\n	background:{BACKGROUND-ADS};\n	}\n\n.Title{\n	font-family:Arial, Helvetica, sans-serif;\n	font-size:26px;\n	color:#1f2020;\n	font-weight:normal;\n	font-style: italic; \n	display:block;\n	clear:both;\n	padding:0px 0px 5px 0px;\n	}\n\n.numbersOnly{\n	color:#000000;\n	font-family: Arial, Helvetica, sans-serif;\n	font-size:45px;\n	font-weight:bold;\n	padding:0px 7px;\n	text-align:left;\n	width:278px;\n	height:47px;\n	margin-bottom:5px;\n	margin-left:35px;\n	}\n	\n.phoneNoTitle{\n	font-family:Arial, Helvetica, sans-serif;\n	font-size:20px;\n	color:#FFFFFF;\n	font-weight:normal;\n	font-style:italic;\n	display:block;\n	clear:both;\n	padding:0px 0px 5px 0px;\n}\n\n.tnc{\n	font-family:Arial, Helvetica, sans-serif;\n	font-size:11px;\n	color:#999999;\n	font-weight:normal;\n	text-align:justify;\n	padding: 0px 20px 0px 20px;\n	}\n	\n.link{\n	font-size:14px;\n}\n	\n	.button {			\n				width: 150px;\n				height: 25px;\n				-webkit-box-shadow: rgb(240, 247, 250) 0px 1px 0px 0px; \n				box-shadow: rgb(240, 247, 250) 0px 1px 0px 0px; \n				border-radius: 25px; border: 1px solid rgb(5, 127, 208); \n				display: inline-block; cursor: pointer; color: rgb(255, 255, 255); \n				font-family: arial; font-size: 17px; font-weight: bold; \n				padding: 20px 10px 13px 10px; text-decoration: none; \n				text-shadow: rgb(91, 97, 120) 0px -1px 0px; \n				background: linear-gradient(rgb(51, 189, 239) 5%, rgb(1, 154, 210) 100%) rgb(51, 189, 239);\n			}\n	&lt;/style&gt;\n	&lt;script language=&quot;JavaScript&quot; type=&quot;text/javascript&quot;&gt;\n\n    $(document).ready(function(){\n		$(\'.numbersOnly\').keyup(function () { \n			this.value = this.value.replace(/[^0-9\\.]/g,\'\');\n		});	\n	});\n	\n	window.onload=function(e)\n	{\n		var urlarr = document.URL.split(\'/\');\n		var formid=urlarr[urlarr.length - 2];\n		var newdiv = document.createElement(\'span\');\n		newdiv.innerHTML =\'&lt;input type=&quot;hidden&quot; name=&quot;formid&quot; value=&quot;\'+formid+\'&quot;&gt;&lt;input type=&quot;hidden&quot; name=&quot;sendme&quot; value=&quot;101&quot;&gt;&lt;input type=&quot;hidden&quot; name=&quot;referreid&quot; value=&quot;\'+document.URL+\'&quot;&gt;\';\n		document.getElementById(\'myform\').appendChild(newdiv);\n	}\n&lt;/script&gt;\n&lt;/head&gt;\n&lt;body&gt;\n&lt;center&gt;\n&lt;form action=&quot;http://land.zingmobiledev.com/admin/msubmit.php&quot; id=&quot;myform&quot; method=&quot;post&quot; name=&quot;msisdnsubmit&quot;&gt;\n&lt;div class=&quot;main_wrap&quot;&gt;\n&lt;table border=&quot;0&quot; cellpadding=&quot;0&quot; cellspacing=&quot;0&quot; width=&quot;478&quot;&gt;\n	&lt;tbody&gt;\n		&lt;tr&gt;\n			&lt;td align=&quot;center&quot; valign=&quot;top&quot;&gt;&amp;nbsp;&lt;/td&gt;\n		&lt;/tr&gt;\n		&lt;tr&gt;\n			&lt;td align=&quot;center&quot; height=&quot;142&quot; valign=&quot;top&quot; width=&quot;478&quot;&gt;&lt;img src=&quot;assets/images/campaigns/web-campaign-header-test.jpg&quot; /&gt;&lt;/td&gt;\n		&lt;/tr&gt;\n		&lt;tr&gt;\n			&lt;td align=&quot;center&quot; bgcolor=&quot;#1f2020&quot; class=&quot;Title&quot; height=&quot;26&quot; valign=&quot;top&quot;&gt;&amp;nbsp;&lt;/td&gt;\n		&lt;/tr&gt;\n		&lt;tr align=&quot;center&quot;&gt;\n			&lt;td align=&quot;center&quot; bgcolor=&quot;#1F2020&quot; valign=&quot;top&quot;&gt;&amp;nbsp;&lt;/td&gt;\n		&lt;/tr&gt;\n		&lt;tr align=&quot;center&quot;&gt;\n			&lt;td align=&quot;center&quot; bgcolor=&quot;#1F2020&quot; height=&quot;60&quot; valign=&quot;top&quot;&gt;&lt;span class=&quot;phoneNoTitle&quot;&gt;SMS &lt;font style=&quot;font-size:25px&quot;&gt;ON SX&lt;/font&gt; To &lt;font style=&quot;font-size:25px&quot;&gt;33293&lt;/font&gt;&lt;/span&gt;&lt;/td&gt;\n		&lt;/tr&gt;\n		&lt;tr&gt;\n			&lt;td align=&quot;center&quot; bgcolor=&quot;#1f2020&quot; height=&quot;105&quot; valign=&quot;middle&quot;&gt;&lt;a class=&quot;button&quot; onclick=&quot;checkmsisdn();&quot; style=&quot;cursor:pointer;&quot; title=&quot;Download&quot;&gt;SMS Sekarang&lt;/a&gt;&lt;/td&gt;\n		&lt;/tr&gt;\n		&lt;script&gt;\n        urlarr = document.URL.split(\'/\');\n        document.getElementById(\'formid\').value = urlarr[urlarr.length - 2];\n        function checkmsisdn() {\n            var msisdn = document.getElementById(\'msisdn\').value;\n            if (msisdn == \'\' || ((msisdn.length &lt; 8) || (msisdn.length &gt; 11))) {\n                alert(\'Please enter valid phone number\');\n                return false;\n            }else{				\n                window.onbeforeunload = null\n                document.getElementById(\'myform\').submit();         \n            }\n        }    \n	  &lt;/script&gt;\n	&lt;/tbody&gt;\n&lt;/table&gt;\n\n&lt;table bgcolor=&quot;#1f2020&quot; border=&quot;0&quot; cellpadding=&quot;0&quot; cellspacing=&quot;0&quot; width=&quot;478&quot;&gt;\n	&lt;tbody&gt;\n		&lt;tr&gt;\n			&lt;td align=&quot;center&quot; class=&quot;tnc&quot; colspan=&quot;4&quot; height=&quot;174&quot; valign=&quot;top&quot;&gt;Please read these &amp;ldquo;General Terms and Conditions&amp;rdquo; carefully. This is an ongoing wallpaper subscription service until you quit. All subscription contents are compatible with 3G/GPRS/WAP-enabled mobile phones and applicable to both postpaid and prepaid users. Supported mobile brands include Samsung, Sony, HTC, Sony Ericsson, Nokia and more. No subscription fees will be charged. RM4 per message, less than 4 time per week. Upon subscribing to the service, you will receive 2 contents per day and subsequent week; the content shall be delivered 4 times a week. Regular mobile operator network charges apply. GPRS / 3G access need to be enable to download the content. Data charges are billed separately. Please seek parental or guardian approval if you are below 18 years old. Upon sending in the SMS to the shortcode as per advertisement, you are acknowledged that you have read and understood the &amp;ldquo;General Terms &amp;amp; Conditions&amp;rdquo;. To cancel, send STOP SX to 33293. Helpdesk 03-7727 2452 (9am-5pm, Mon-Fri). This service is operates as according to the Malaysia Code of Conduct for the SMS services.&lt;/td&gt;\n		&lt;/tr&gt;\n		&lt;tr&gt;\n			&lt;td align=&quot;center&quot; colspan=&quot;4&quot; valign=&quot;top&quot;&gt;Powered by Zeptomobile Sdn. Bhd.&lt;/td&gt;\n		&lt;/tr&gt;\n	&lt;/tbody&gt;\n&lt;/table&gt;\n&lt;/div&gt;\n&lt;/form&gt;\n&lt;/center&gt;\n&lt;/body&gt;\n&lt;/html&gt;\n','&lt;html&gt;\n&lt;head&gt;\n	&lt;title&gt;&lt;/title&gt;\n&lt;/head&gt;\n&lt;body&gt;&lt;/body&gt;\n&lt;/html&gt;\n',0,1),(6,1,2,'Templates Tes Upload 1','','templates/20150114194517/index.php','templates/20150114194517/details.php',NULL,1,0);
/*!40000 ALTER TABLE `templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `traffic_`
--

DROP TABLE IF EXISTS `traffic_`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `traffic_` (
  `id` bigint(20) NOT NULL,
  `server_time` datetime NOT NULL,
  `operator_time` datetime DEFAULT NULL,
  `partner_time` datetime DEFAULT NULL,
  `ads_publisher_time` datetime DEFAULT NULL,
  `charging_time` datetime DEFAULT NULL,
  `id_operator` int(11) NOT NULL,
  `id_partner` int(11) NOT NULL,
  `id_campaign` int(11) NOT NULL,
  `id_ads_publisher` int(11) NOT NULL,
  `id_partner_service` int(11) NOT NULL,
  `campaign_code` varchar(50) NOT NULL,
  `content` varchar(50) DEFAULT NULL,
  `http_host` varchar(50) NOT NULL,
  `request_uri` varchar(100) NOT NULL,
  `msisdn_detection` varchar(15) DEFAULT NULL,
  `msisdn_charging` varchar(15) DEFAULT NULL,
  `campaign_price` double(10,2) NOT NULL,
  `charging_price` double(10,2) DEFAULT NULL,
  `is_confirmation` tinyint(4) DEFAULT NULL,
  `status_campaign` tinyint(4) DEFAULT NULL,
  `status_charging` tinyint(4) DEFAULT NULL,
  `shortcode` varchar(10) DEFAULT NULL,
  `sid` varchar(50) DEFAULT NULL,
  `keyword` varchar(50) DEFAULT NULL,
  `affiliate_params` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `traffic_`
--

LOCK TABLES `traffic_` WRITE;
/*!40000 ALTER TABLE `traffic_` DISABLE KEYS */;
/*!40000 ALTER TABLE `traffic_` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-01-15  8:54:34
