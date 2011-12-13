-- WELOVEDIFFERENCE SQL Dump
--
-- Generato il: 13 dic, 2011 at 06:00 PM
-- Versione MySQL: 5.0.77
-- Versione PHP: 5.2.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `welovedifference`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `see_likes`
--

CREATE TABLE IF NOT EXISTS `see_likes` (
  `id` int(11) NOT NULL auto_increment,
  `user_agent` varchar(255) NOT NULL,
  `ip_address` varchar(15) NOT NULL,
  `date_time` datetime NOT NULL,
  `photo_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `photo_id` (`photo_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `see_photos`
--

CREATE TABLE IF NOT EXISTS `see_photos` (
  `id` int(11) NOT NULL auto_increment,
  `week_id` int(11) NOT NULL,
  `author_name` varchar(64) collate utf8_unicode_ci NOT NULL,
  `author_www` varchar(255) collate utf8_unicode_ci NOT NULL,
  `author_email` varchar(128) collate utf8_unicode_ci NOT NULL,
  `abstract` varchar(64) collate utf8_unicode_ci NOT NULL,
  `img_path` varchar(255) collate utf8_unicode_ci NOT NULL,
  `source_file` varchar(255) collate utf8_unicode_ci NOT NULL,
  `img_width` int(4) NOT NULL,
  `img_height` int(4) NOT NULL,
  `upload_date` datetime NOT NULL,
  `visible` tinyint(1) NOT NULL,
  `likes_count` int(11) NOT NULL,
  `priority` smallint(6) NOT NULL,
  `user_language` varchar(16) collate utf8_unicode_ci NOT NULL,
  `uid` varchar(128) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `visible` (`visible`),
  KEY `week_id` (`week_id`),
  KEY `priority` (`priority`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `see_sessions`
--

CREATE TABLE IF NOT EXISTS `see_sessions` (
  `id` int(11) NOT NULL auto_increment,
  `week_id` int(11) NOT NULL,
  `ip_address` varchar(15) NOT NULL,
  `user_agent` varchar(255) NOT NULL,
  `date_time` datetime NOT NULL,
  `photo_id` int(11) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `week_id` (`week_id`,`ip_address`,`user_agent`),
  KEY `photo_id` (`photo_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `see_weeks`
--

CREATE TABLE IF NOT EXISTS `see_weeks` (
  `id` int(11) NOT NULL auto_increment,
  `week` int(11) NOT NULL,
  `title` varchar(64) character set latin1 NOT NULL,
  `description` text character set latin1 NOT NULL,
  `title_en` varchar(64) character set latin1 NOT NULL,
  `description_en` text character set latin1 NOT NULL,
  `visibility_date` date NOT NULL,
  `likes_sent` tinyint(1) NOT NULL,
  `published_sent` tinyint(1) NOT NULL,
  `winner_sent` tinyint(1) NOT NULL,
  `is_open` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `week` (`week`),
  KEY `visibility_date` (`visibility_date`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;
