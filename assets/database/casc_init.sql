-- phpMyAdmin SQL Dump
-- version 2.11.8.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 20, 2016 at 10:17 AM
-- Server version: 5.1.45
-- PHP Version: 5.2.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `casc`
--

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

CREATE TABLE IF NOT EXISTS `images` (
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
  `image` longblob NOT NULL,
  PRIMARY KEY (`image_id`),
  KEY `member_id` (`member_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `images`
--


-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE IF NOT EXISTS `members` (
  `member_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL,
  `organization` varchar(128) DEFAULT NULL,
  `city` varchar(64) NOT NULL,
  `state` varchar(32) NOT NULL,
  PRIMARY KEY (`member_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=83 ;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`member_id`, `name`, `organization`, `city`, `state`) VALUES
(1, 'Advanced Computing Center', 'Arizona State University', 'Tempe', 'Arizona'),
(2, 'Advanced Research Computing', 'University of Michigan', 'Ann Arbor', 'Michigan'),
(3, 'Advanced Research Computing', 'Virginia Tech University', 'Blacksburg', 'Virginia'),
(4, 'Advanced Research Computing Center (ARCC)', 'University of Wyoming', 'Laramie', 'Wyoming'),
(5, 'Advanced Computing Services and Engagement (UVACSE)', 'University of Virginia', 'Charlottesville', 'Virginia'),
(6, 'Arctic Region Supercomputing Center (ARSC)', 'University of Alaska Fairbanks', 'Fairbanks', 'Alaska'),
(7, 'Argonne National Laboratory -', 'University of Chicago', 'Chicago', 'Illinois'),
(8, 'Berkeley Research Computing', 'University of California, Berkeley', 'Berkeley', 'California'),
(9, 'Center for Advanced Computing', 'Cornell University', 'Ithaca', 'New York'),
(10, 'Center for Advanced Computing and Data Systems', 'University of Houston', 'Houston', 'Texas'),
(11, 'Center for Advanced Research Computing', 'University of New Mexico', 'Albuquerque', 'New Mexico'),
(12, 'Center for Computation & Technology (CCT)', 'Louisiana State University', 'Baton Rouge', 'Louisiana'),
(13, 'Center for Computation & Visualization', 'Brown University', 'Providence', 'Rhode Island'),
(14, 'Center for Computational Research', 'University at Buffalo', 'Buffalo', 'New York'),
(15, 'Center for Computational Science', 'Boston University', 'Boston', 'Massachusetts'),
(16, 'Center for Computational Sciences', 'University of Kentucky', 'Lexington', 'Kentucky'),
(17, 'Center for Computationally Assisted Science & Technology', 'North Dakota State University', 'Fargo', 'North Dakota'),
(18, 'Center for High Performance Computing', 'University of Utah', 'Salt Lake City', 'Utah'),
(19, 'Center for Research Computing', 'University of Notre Dame', 'Notre Dame', 'Indiana'),
(20, 'Center for Simulation & Modeling', 'University of Pittsburgh', 'Pittsburgh', 'Pennsylvania'),
(21, 'Computing and Information Technology (CCIT)', 'Clemson University', 'Clemson', 'South Carolina'),
(22, 'Core Facility in Advanced Research Computing', 'Case Western Reserve University', 'Cleveland', 'Ohio'),
(23, 'Discovery Informatics Institute RDI2', 'Rutgers University', 'Piscataway', 'New Jersey'),
(24, 'Georgia Advanced Computing Resource Center', 'University of Georgia', 'Athens', 'Georgia'),
(25, 'Georgia Institute of Technology', NULL, 'Atlanta', 'Georgia'),
(26, 'Harvard University', NULL, 'Boston', 'Massachusetts'),
(27, 'High Performance Computing Center', 'Michigan State University', 'East Lansing', 'Michigan'),
(28, 'High Performance Computing Center', 'Texas Tech University', 'Lubbock', 'Texas'),
(29, 'High Performance Computing Center', 'University of Arkansas', 'Fayetville', 'Arkansas'),
(30, 'High Performance Computing Collaboratory (HPC2)', 'Mississippi State University', 'Mississippi State', 'Mississippi'),
(31, 'High Performance Computing Facility', 'City University of New York', 'Staten Island', 'New York'),
(32, 'Holland Computing Center', 'University of Nebraska', 'Omaha', 'Nebraska'),
(33, 'Icahn School of Medicine at Mt. Sinai', 'Mt Sinai Medical School', 'New York', 'New York'),
(34, 'Indiana University', NULL, 'Bloomington', 'Indiana'),
(35, 'Information Sciences Institute', 'University of Southern California', 'Marina del Rey', 'California'),
(36, 'Institute for Digital Research and Education', 'University of California, Los Angeles', 'Los Angeles', 'California'),
(37, 'Institute for Massively Parallel Applications', 'George Washington University', 'Washington', 'DC'),
(38, 'Institute for Scientific Computation', 'Texas A&M University', 'College Station', 'Texas'),
(39, 'Johns Hopkins University', NULL, 'Baltimore', 'Maryland'),
(40, 'Ken Kennedy Institute for Information Technology (K2I)', 'Rice University', 'Houston', 'Texas'),
(41, 'Lawrence Berkeley National Laboratory', NULL, 'Berkeley', 'California'),
(42, 'Maui High Performance Computing Center', 'University of Hawaii', 'Honolulu', 'Hawaii'),
(43, 'Michigan Technical Institute', NULL, 'Houghton', 'Michigan'),
(44, 'Minnesota Supercomputing Institute', 'University of Minnesota', 'Minneapolis', 'Minnesota'),
(45, 'National Center for Atmospheric Research (NCAR)', NULL, 'Boulder', 'Colorado'),
(46, 'National Center for Supercomputing Applications (NCSA)', 'University of Illinois at Urbana-Champaign', 'Champaign', 'Illinois'),
(47, 'National Institute for Computational Sciences (NICS)', 'University of Tennessee', 'Knoxville', 'Tennessee'),
(48, 'National Supercomputing Center for Energy & the Environment (NSCEE)', 'University of Nevada', 'Las Vegas', 'Nevada'),
(49, 'New York University', NULL, 'New York', 'New York'),
(50, 'Northwestern University', NULL, 'Evanston', 'Illinois'),
(51, 'Oak Ridge National Laboratory (ORNL) Center for Computational Sciences', NULL, 'Oak Ridge', 'Tennessee'),
(52, 'Ohio Supercomputer Center (OSC)', 'The Ohio State University', 'Columbus', 'Ohio'),
(53, 'Pittsburgh Supercomputing Center', 'Carnegie-Mellon University & University of Pittsburgh', 'Pittsburgh', 'Pennsylvania'),
(54, 'Princeton University', NULL, 'Princeton', 'New Jersey'),
(55, 'Purdue University', NULL, 'West Lafayette', 'Indiana'),
(56, 'Research Computing Center', 'University of Arizona', 'Tucson', 'Arizona'),
(57, 'Research Computing Center', 'Columbia University', 'New York', 'New York'),
(58, 'Research Technologies', 'Stony Brook University', 'Stony Brook', 'New York'),
(59, 'Renaissance Computing Institute (RENCI)', 'University of North Carolina at Chapel Hill', 'Chapel Hill', 'North Carolina'),
(60, 'San Diego Supercomputer Center (SDSC)', 'University of California, San Diego', 'La Jolla', 'California'),
(61, 'Scientific Computation Research Center (SCOREC)', 'Rensselaer Polytechnic Institute', 'Troy', 'New York'),
(62, 'Shared Research Computing Center', 'Florida State University', 'Tallahassee', 'Florida'),
(63, 'Stanford University', NULL, 'Stanford', 'California'),
(64, 'Supercomputing Center for Education and Research', 'University of Oklahoma', 'Norman', 'Oklahoma'),
(65, 'Texas Advanced Computing Center (TACC)', 'The University of Texas at Austin', 'Austin', 'Texas'),
(66, 'The Pennsylvania State University', NULL, 'University Park', 'Pennsylvania'),
(67, 'University of Colorado Boulder', NULL, 'Boulder', 'Colorado'),
(68, 'University of Connecticut', NULL, 'Storrs', 'Connecticut'),
(69, 'University of Florida', NULL, 'Gainesville', 'Florida'),
(70, 'University of Iowa', NULL, 'Iowa City', 'Iowa'),
(71, 'University of Louisville', NULL, 'Louisville', 'Kentucky'),
(72, 'University of Maryland', NULL, 'College Park', 'Maryland'),
(73, 'University of Massachusetts', NULL, 'Shrewsbury', 'Massachusetts'),
(74, 'University of Miami', NULL, 'Miami', 'Florida'),
(75, 'University of North Carolina, Chapel Hill', NULL, 'Chapel Hill', 'North Carolina'),
(76, 'University of South Florida', NULL, 'Tampa', 'Florida'),
(77, 'University of Washington', NULL, 'Seattle', 'Washington'),
(78, 'University of Wisconsin - Madison', NULL, 'Madison', 'Wisconsin'),
(79, 'University of Wisconsin - Milwaukee', NULL, 'Milwaukee', 'Wisconsin'),
(80, 'Vanderbilt University', NULL, 'Knoxville', 'Tennessee'),
(81, 'West Virginia University', NULL, 'Morgantown', 'West Virginia'),
(82, 'Yale University', NULL, 'New Haven', 'Connecticut');
