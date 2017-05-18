-- MySQL dump 10.13  Distrib 5.5.55, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: casc
-- ------------------------------------------------------
-- Server version	5.5.55-0ubuntu0.14.04.1

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
-- Table structure for table `images`
--

DROP TABLE IF EXISTS `images`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `images` (
  `image_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `member_id` int(11) NOT NULL,
  `description` mediumtext NOT NULL,
  `name` varchar(128) NOT NULL,
  `phone` varchar(64) NOT NULL,
  `email` varchar(128) NOT NULL,
  `researcher_name` varchar(128) NOT NULL,
  `researcher_phone` varchar(64) NOT NULL,
  `researcher_email` varchar(128) NOT NULL,
  `researcher_institution` varchar(255) DEFAULT NULL,
  `researcher_address` varchar(255) DEFAULT NULL,
  `viz_name` varchar(128) DEFAULT NULL,
  `viz_institution` varchar(255) DEFAULT NULL,
  `compute_name` varchar(128) DEFAULT NULL,
  `compute_system` varchar(128) DEFAULT NULL,
  `compute_institution` varchar(255) DEFAULT NULL,
  `date_uploaded` datetime NOT NULL,
  `imagetype` varchar(128) NOT NULL,
  `image_resolution` varchar(32) DEFAULT NULL,
  `image_ext` varchar(8) DEFAULT NULL,
  `image` longblob NOT NULL,
  PRIMARY KEY (`image_id`),
  KEY `member_id` (`member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `images`
--

LOCK TABLES `images` WRITE;
/*!40000 ALTER TABLE `images` DISABLE KEYS */;
/*!40000 ALTER TABLE `images` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `members`
--

DROP TABLE IF EXISTS `members`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `members` (
  `member_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `organization` varchar(128) DEFAULT NULL,
  `city` varchar(64) NOT NULL,
  `state` varchar(32) NOT NULL,
  PRIMARY KEY (`member_id`)
) ENGINE=MyISAM AUTO_INCREMENT=88 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `members`
--

LOCK TABLES `members` WRITE;
/*!40000 ALTER TABLE `members` DISABLE KEYS */;
INSERT INTO `members` VALUES
(1,'Advanced Computing Center','Arizona State University','Tempe','Arizona'),
(2,'Advanced Research Computing','University of Michigan','Ann Arbor','Michigan'),
(3,'Advanced Research Computing','Virginia Tech University','Blacksburg','Virginia'),
(4,'Advanced Research Computing Center (ARCC)','University of Wyoming','Laramie','Wyoming'),
(5,'Alliance for Computational Science and Engineering','University of Virginia','Charlottesville','Virginia'),
(6,'Arctic Region Supercomputing Center (ARSC)','University of Alaska Fairbanks','Fairbanks','Alaska'),
(7,'Argonne National Laboratory -','University of Chicago','Chicago','Illinois'),
(8,'Berkeley Research Computing','University of California, Berkeley','Berkeley','California'),
(9,'Center for Advanced Computing','Cornell University','Ithaca','New York'),
(10,'Center for Advanced Computing and Data Systems','University of Houston','Houston','Texas'),
(11,'Center for Advanced Research Computing','University of New Mexico','Albuquerque','New Mexico'),
(12,'Center for Computation & Technology (CCT)','Louisiana State University','Baton Rouge','Louisiana'),
(13,'Center for Computation & Visualization','Brown University','Providence','Rhode Island'),
(14,'Center for Computational Research','University at Buffalo','Buffalo','New York'),
(15,'Center for Computational Science','Boston University','Boston','Massachusetts'),
(16,'Center for Computational Sciences','University of Kentucky','Lexington','Kentucky'),
(17,'Center for Computationally Assisted Science & Technology','North Dakota State University','Fargo','North Dakota'),
(18,'Center for High Performance Computing','University of Utah','Salt Lake City','Utah'),
(19,'Center for Research Computing','University of Notre Dame','Notre Dame','Indiana'),
(20,'Center for Simulation & Modeling','University of Pittsburgh','Pittsburgh','Pennsylvania'),
(21,'Computing and Information Technology (CCIT)','Clemson University','Clemson','South Carolina'),
(22,'Core Facility in Advanced Research Computing','Case Western Reserve University','Cleveland','Ohio'),
(23,'Discovery Informatics Institute RDI2','Rutgers University','Piscataway','New Jersey'),
(24,'Georgia Advanced Computing Resource Center','University of Georgia','Athens','Georgia'),
(25,'Georgia Institute of Technology',NULL,'Atlanta','Georgia'),
(26,'Harvard University',NULL,'Boston','Massachusetts'),
(27,'High Performance Computing Center','Michigan State University','East Lansing','Michigan'),
(28,'High Performance Computing Center','Oklahoma State University','Stillwater','Oklahoma'),
(29,'High Performance Computing Center','Texas Tech University','Lubbock','Texas'),
(30,'High Performance Computing Center','University of Arkansas','Fayetville','Arkansas'),
(31,'High Performance Computing Collaboratory (HPC2)','Mississippi State University','Mississippi State','Mississippi'),
(32,'High Performance Computing Facility','City University of New York','Staten Island','New York'),
(33,'Holland Computing Center','University of Nebraska','Omaha','Nebraska'),
(34,'Icahn School of Medicine at Mt. Sinai','Mt Sinai Medical School','New York','New York'),
(35,'Indiana University',NULL,'Bloomington','Indiana'),
(36,'Information Sciences Institute','University of Southern California','Marina del Rey','California'),
(37,'Institute for Digital Research and Education','University of California, Los Angeles','Los Angeles','California'),
(38,'Institute for Massively Parallel Applications','George Washington University','Washington','DC'),
(39,'Institute for Scientific Computation','Texas A&M University','College Station','Texas'),
(40,'Johns Hopkins University',NULL,'Baltimore','Maryland'),
(41,'Ken Kennedy Institute for Information Technology (K2I)','Rice University','Houston','Texas'),
(42,'Lawrence Berkeley National Laboratory',NULL,'Berkeley','California'),
(43,'Maui High Performance Computing Center','University of Hawaii','Honolulu','Hawaii'),
(44,'Michigan Technical Institute',NULL,'Houghton','Michigan'),
(45,'Minnesota Supercomputing Institute','University of Minnesota','Minneapolis','Minnesota'),
(46,'Montana State University',NULL,'Bozeman','Montana'),
(47,'National Center for Atmospheric Research (NCAR)',NULL,'Boulder','Colorado'),
(48,'National Center for Supercomputing Applications (NCSA)','University of Illinois at Urbana-Champaign','Champaign','Illinois'),
(49,'National Institute for Computational Sciences (NICS)','University of Tennessee','Knoxville','Tennessee'),
(50,'National Supercomputing Center for Energy & the Environment (NSCEE)','University of Nevada','Las Vegas','Nevada'),
(51,'New York University',NULL,'New York','New York'),
(52,'Northwestern University',NULL,'Evanston','Illinois'),
(53,'Oak Ridge National Laboratory (ORNL) Center for Computational Sciences',NULL,'Oak Ridge','Tennessee'),
(54,'Ohio Supercomputer Center (OSC)','The Ohio State University','Columbus','Ohio'),
(55,'Old Dominion University',NULL,'Norfolk','Virginia'),
(56,'Pittsburgh Supercomputing Center','Carnegie-Mellon University & University of Pittsburgh','Pittsburgh','Pennsylvania'),
(57,'Princeton University',NULL,'Princeton','New Jersey'),
(58,'Purdue University',NULL,'West Lafayette','Indiana'),
(59,'Research Computing Center','University of Arizona','Tucson','Arizona'),
(60,'Research Computing Center','Columbia University','New York','New York'),
(61,'Research Computing Center','University of Illinois, Chicago','Chicago','Illinois'),
(62,'Research Computing Center','University of New Hampshire','Durham','New Hampshire'),
(63,'Research Technologies','Stony Brook University','Stony Brook','New York'),
(64,'Renaissance Computing Institute (RENCI)','University of North Carolina at Chapel Hill','Chapel Hill','North Carolina'),
(65,'San Diego Supercomputer Center (SDSC)','University of California, San Diego','La Jolla','California'),
(66,'Scientific Computation Research Center (SCOREC)','Rensselaer Polytechnic Institute','Troy','New York'),
(67,'Shared Research Computing Center','Florida State University','Tallahassee','Florida'),
(68,'Stanford University',NULL,'Stanford','California'),
(69,'Supercomputing Center for Education and Research','University of Oklahoma','Norman','Oklahoma'),
(70,'Texas Advanced Computing Center (TACC)','The University of Texas at Austin','Austin','Texas'),
(71,'The Pennsylvania State University',NULL,'University Park','Pennsylvania'),
(72,'University of Colorado Boulder',NULL,'Boulder','Colorado'),
(73,'University of Connecticut',NULL,'Storrs','Connecticut'),
(74,'University of Florida',NULL,'Gainesville','Florida'),
(75,'University of Iowa',NULL,'Iowa City','Iowa'),
(76,'University of Louisville',NULL,'Louisville','Kentucky'),
(77,'University of Maryland',NULL,'College Park','Maryland'),
(78,'University of Massachusetts',NULL,'Shrewsbury','Massachusetts'),
(79,'University of Miami',NULL,'Miami','Florida'),
(80,'University of North Carolina, Chapel Hill',NULL,'Chapel Hill','North Carolina'),
(81,'University of South Florida',NULL,'Tampa','Florida'),
(82,'University of Washington',NULL,'Seattle','Washington'),
(83,'University of Wisconsin - Madison',NULL,'Madison','Wisconsin'),
(84,'University of Wisconsin - Milwaukee',NULL,'Milwaukee','Wisconsin'),
(85,'Vanderbilt University',NULL,'Knoxville','Tennessee'),
(86,'West Virginia University',NULL,'Morgantown','West Virginia'),
(87,'Yale University',NULL,'New Haven','Connecticut');
/*!40000 ALTER TABLE `members` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-05-18 15:22:37
