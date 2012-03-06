-- phpMyAdmin SQL Dump
-- version 3.3.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 06, 2010 at 03:48 PM
-- Server version: 5.1.51
-- PHP Version: 5.2.14

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


-- --------------------------------------------------------

--
-- Table structure for table `tbl_galleries`
--

CREATE TABLE IF NOT EXISTS `tbl_galleries` (
  `id` int(3) NOT NULL AUTO_INCREMENT,
  `pid` int(4) NOT NULL,
  `imgsOrder` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_galleryConfig`
--

CREATE TABLE IF NOT EXISTS `tbl_galleryConfig` (
  `type` varchar(8) NOT NULL,
  `config` text NOT NULL,
  PRIMARY KEY (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_galleryConfig`
--

INSERT INTO `tbl_galleryConfig` (`type`, `config`) VALUES
('gallery', 'a:13:{s:8:"imgWidth";i:700;s:7:"thWidth";i:200;s:7:"quality";i:75;s:7:"sharpen";i:20;s:8:"okButton";s:2:"Ok";s:12:"cancelButton";s:6:"Cancel";s:11:"thTitleShow";b:1;s:12:"keepOriginal";b:0;s:7:"gFolder";s:9:"galleries";s:7:"tempDir";s:4:"_tmp";s:11:"originalDir";s:8:"original";s:9:"thumbsDir";s:6:"thumbs";s:11:"picturesDir";s:8:"pictures";}'),
('fancybox', 'a:8:{s:13:"titlePosition";s:6:"inside";s:13:"easingEnabled";b:1;s:12:"mouseEnabled";b:1;s:12:"transitionIn";s:7:"elastic";s:13:"transitionOut";s:7:"elastic";s:7:"speedIn";i:600;s:8:"speedOut";i:200;s:11:"overlayShow";b:0;}'),
('uploader', 'a:8:{s:6:"accept";s:11:"jpg|png|gif";s:5:"title";s:11:"Load images";s:9:"duplicate";s:19:"Transition in Efect";s:6:"denied";s:20:"Invalid type of file";s:6:"submit";s:4:"Load";s:6:"remove";s:15:"delete16x16.png";s:3:"max";s:2:"-1";s:6:"action";s:0:"";}');
