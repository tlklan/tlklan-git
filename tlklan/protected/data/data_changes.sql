#
# 2012-01-29
# Add CMS data from the test database
#
-- phpMyAdmin SQL Dump
-- version 3.3.7deb7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 29, 2012 at 05:14 PM
-- Server version: 5.1.49
-- PHP Version: 5.3.3-7+squeeze3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `tlk_lan_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `cms_attachment`
--

CREATE TABLE IF NOT EXISTS `cms_attachment` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `contentId` int(10) unsigned NOT NULL,
  `filename` varchar(255) NOT NULL,
  `extension` varchar(50) NOT NULL,
  `mimeType` varchar(255) NOT NULL,
  `byteSize` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `contentId` (`contentId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `cms_attachment`
--


-- --------------------------------------------------------

--
-- Table structure for table `cms_content`
--

CREATE TABLE IF NOT EXISTS `cms_content` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nodeId` int(10) unsigned NOT NULL,
  `locale` varchar(50) NOT NULL,
  `heading` varchar(255) DEFAULT NULL,
  `body` longtext,
  `css` longtext,
  `url` varchar(255) DEFAULT NULL,
  `pageTitle` varchar(255) DEFAULT NULL,
  `breadcrumb` varchar(255) DEFAULT NULL,
  `metaTitle` varchar(255) DEFAULT NULL,
  `metaDescription` varchar(255) DEFAULT NULL,
  `metaKeywords` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `contentId_locale` (`nodeId`,`locale`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `cms_content`
--

INSERT INTO `cms_content` (`id`, `nodeId`, `locale`, `heading`, `body`, `css`, `url`, `pageTitle`, `breadcrumb`, `metaTitle`, `metaDescription`, `metaKeywords`) VALUES
(1, 1, 'sv', 'LAN-klubbens styrelse', '{{heading}}\r\n\r\n<p>\r\n\r\n				LAN-klubben konstituerades som en klubb inom TLK den 29:e januari\r\n				2010 med fem styrelsemedlemmar. I styrelsen för verksamhetsåret\r\n				2011 sitter följande personer:\r\n			</p>\r\n			<ul>\r\n				<li><s>Ordförande: Johannes Edgren</s></li>\r\n				<li>Viceordförande: Christoffer Holmberg</li>\r\n				<li>Sekreterare: Sam Stenvall</li>\r\n				<li>Verksamhetsledare: Henri Selin</li>\r\n\r\n				<li>Kassör: Christoffer Andersson</li>\r\n			</ul>\r\n			<p>Vår nuvarande ordförande avgick av personliga skäl under \r\n				verksamhetsåret. En ny styrelse kommer att väljas inom \r\n				snar framtid.\r\n			</p>\r\n			<h2>Kontaktuppgifter</h2>\r\n			<p>LAN-klubbens styrelse kan nås per e-post på adressen <span id="e118973080">[javascript protected email address]</span><script type="text/javascript">/*<![CDATA[*/eval("var a=\\"DeLuvmxcy9B3bKXH01CY+As2RWOSglQdtIzf@TaG.5UjNJoViwh_8k6-rZM7PE4nFqp\\";var b=a.split(\\"\\").sort().join(\\"\\");var c=\\"856_8EUUJ6KP8_Low\\";var d=\\"\\";for(var e=0;e<c.length;e++)d+=b.charAt(a.indexOf(c.charAt(e)));document.getElementById(\\"e118973080\\").innerHTML=\\"<a href=\\\\\\"mailto:\\"+d+\\"\\\\\\">\\"+d+\\"</a>\\"")/*]]>*/</script></p>\r\n			<h2>Mötesprotokoll</h2>\r\n\r\n			<p>I öppenhetens och transparenta processers namn kan man här\r\n			   ladda ner protokoll över styrelsens möten.</p>\r\n\r\n			<ul>\r\n				<li><a href="documents/lanprotokoll_20100129.pdf">Konstituerande möte 29.1.2010</a></li>\r\n				<li><a href="documents/lanprotokoll_20110113.pdf">Styrelsemöte 13.1.2011</a></li>\r\n				<li><a href="documents/lanprotokoll_20110428.pdf">Styrelsemöte 28.4.2011</a></li>\r\n				<li><a href="documents/lanprotokoll_20111006.pdf">Styrelsemöte 6.10.2011</a></li>\r\n\r\n			</ul>', '', 'committee', 'Styrelsen', 'Styrelsen', '', '', ''),
(2, 2, 'sv', 'Hem', '<h1>Vad är TLK LAN?</h1>\r\n<p>TLK LAN är ett LAN-party som ordnas av LAN-klubben fyra gånger\r\n	om året. Det är ett av TLKs mer kända evenemang och lockar varje\r\n	gång tiotals teknikstuderande till att bunkra sig framför sina\r\n	datorer över ett veckoslut.\r\n</p>\r\n<h1>Vad är den här sidan?</h1>\r\n<p>På dessa sidor hittar du allt du kan tänka dig vilja veta\r\n	angående TLK LAN. Här kan du anmäla dig till kommande LAN,\r\n	se vilka regler som gäller för våra tävlingar och se resultat\r\n	samt statistik från tidigare LAN. Sidorna fungerar samtidigt\r\n	som LAN-klubbens officiella sidor.\r\n</p>\r\n<h1>Historia</h1>\r\n<p>\r\n\r\n	Alla datorintresserade har någon gång varit på LAN, det är ett\r\n	urgammalt påhitt. Det var dock först någon gång under 2008 som\r\n	Krister Bäckman (f.d. studerande) m.fl. började ta initiativet till\r\n	att ordna ett LAN för TLK-medlemmar. I början var deltagarantalet\r\n	relativt lågt, men traditionen fortsatte, och i slutet av år 2009\r\n	deltog över 20 personer. LANen ordnades till en början vid TLKs klubblokal\r\n	aka. Cornern, men numera ordnas de på Club Werket, vars faciliteter tillåter\r\n	högre deltagarantal samt bekvämare tillvaro.\r\n</p>\r\n<p>\r\n	I slutet av 2009 föddes idén om att laga en anmälningssida för\r\n	LANen så att man enkelt kunde se hur många som var påväg. Sidan\r\n	utvecklades sakta men säkert till det som ni ser här idag.\r\n</p>\r\n<h3>LAN-klubben</h3>\r\n<p>\r\n	Under år 2010 grundades LAN-klubben av Johannes Edgren, Tomas Kindstedt, Sam Stenvall,\r\n    Henri Selin samt Christoffer Holmberg. Klubbens syfte är att ordna LANen samt utveckla \r\n    dem. Klubben ansvarar för själva ordnandet av LAN-partyna, dvs.\r\n	priser till tävlingar, nätverkshårdvara osv.\r\n</p>', '', 'home', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `cms_node`
--

CREATE TABLE IF NOT EXISTS `cms_node` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated` timestamp NULL DEFAULT NULL,
  `parentId` int(10) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `name_deleted` (`name`,`deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `cms_node`
--

INSERT INTO `cms_node` (`id`, `created`, `updated`, `parentId`, `name`, `deleted`) VALUES
(1, '2011-12-13 13:25:01', '2011-12-13 14:02:17', 0, 'committee', 0),
(2, '2011-12-13 14:02:48', '2011-12-13 14:35:00', 0, 'home', 0);
