--
-- Table structure for table `articles`
--

DROP TABLE IF EXISTS `articles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `articles` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `IDPage` int(11) NOT NULL,
  `IDUser` int(11) NOT NULL,
  `Titre` varchar(150) NOT NULL,
  `Contenu` text NOT NULL,
  `DateHeure` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `DerniereEdition` timestamp NULL DEFAULT NULL,
  `Etat` tinyint(1) NOT NULL,
  `EpingleAccueil` tinyint(1) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Table structure for table `configuration`
--

DROP TABLE IF EXISTS `configuration`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `configuration` (
  `ID` int(11) NOT NULL,
  `HeadTitle` varchar(50) DEFAULT NULL,
  `HeadDescription` varchar(255) DEFAULT NULL,
  `HeadKeywords` varchar(255) DEFAULT NULL,
  `HeadNavBarColor` varchar(20) DEFAULT NULL,
  `HeadNavBarWordColor` varchar(20) DEFAULT NULL,
  `HeadNavBarSelectWordColor` varchar(20) DEFAULT NULL,
  `TitrePAccueil` varchar(50) DEFAULT NULL,
  `TitrePrincipal` varchar(50) DEFAULT NULL,
  `SousTitre` varchar(50) DEFAULT NULL,
  `CopyFooter` varchar(155) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pages` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Etat` tinyint(1) NOT NULL,
  `Titre` varchar(150) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Table structure for table `utilisateurs`
--

DROP TABLE IF EXISTS `utilisateurs`;
CREATE TABLE `utilisateurs` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Etat` tinyint(1) NOT NULL,
  `Pseudo` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Mail` varchar(150) NOT NULL,
  `Session` varchar(50) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;