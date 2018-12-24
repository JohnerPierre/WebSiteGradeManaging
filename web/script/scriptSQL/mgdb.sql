-- phpMyAdmin SQL Dump
-- version 4.1.4
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Jeu 12 Juin 2014 à 08:28
-- Version du serveur :  5.6.15-log
-- Version de PHP :  5.4.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `mgdb`
--
CREATE DATABASE IF NOT EXISTS `mgdb` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `mgdb`;

-- --------------------------------------------------------

--
-- Structure de la table `tarealise`
--

CREATE TABLE IF NOT EXISTS `tarealise` (
  `idEleve` int(11) NOT NULL,
  `idTravail` int(11) NOT NULL,
  `note` int(1) NOT NULL,
  PRIMARY KEY (`idEleve`,`idTravail`),
  KEY `idEleve` (`idEleve`),
  KEY `idTravail` (`idTravail`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `tarealise`
--

INSERT INTO `tarealise` (`idEleve`, `idTravail`, `note`) VALUES
(1, 14, 4),
(3, 10, 5),
(4, 10, 5),
(5, 10, 4),
(5, 13, 4),
(6, 14, 1),
(7, 12, 3),
(7, 15, 2),
(8, 14, 6),
(9, 14, 2),
(10, 14, 3),
(12, 14, 5);

-- --------------------------------------------------------

--
-- Structure de la table `tbranche`
--

CREATE TABLE IF NOT EXISTS `tbranche` (
  `idBranche` int(11) NOT NULL AUTO_INCREMENT,
  `brancheNom` varchar(40) NOT NULL,
  PRIMARY KEY (`idBranche`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Contenu de la table `tbranche`
--

INSERT INTO `tbranche` (`idBranche`, `brancheNom`) VALUES
(1, 'Français'),
(3, 'Chimie'),
(4, 'Russe'),
(6, 'Histoire'),
(7, 'Anglais'),
(8, 'Math'),
(9, 'Allemand');

-- --------------------------------------------------------

--
-- Structure de la table `tclasse`
--

CREATE TABLE IF NOT EXISTS `tclasse` (
  `idClasse` int(11) NOT NULL AUTO_INCREMENT,
  `classeNom` varchar(10) NOT NULL,
  `classeAnnee` year(4) NOT NULL,
  PRIMARY KEY (`idClasse`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Contenu de la table `tclasse`
--

INSERT INTO `tclasse` (`idClasse`, `classeNom`, `classeAnnee`) VALUES
(1, 'Null', 0000),
(2, 'A', 2013),
(10, 'IFAP3B', 2015),
(12, 'IFAP2A', 2014),
(13, 'MPT3G', 2015),
(14, 'INAP4C', 2014);

-- --------------------------------------------------------

--
-- Structure de la table `televe`
--

CREATE TABLE IF NOT EXISTS `televe` (
  `idEleve` int(11) NOT NULL AUTO_INCREMENT,
  `eleveNom` varchar(20) NOT NULL,
  `elevePrenom` varchar(20) NOT NULL,
  `eleveMotPasse` varchar(40) NOT NULL,
  `idClasse` int(11) NOT NULL,
  `eleveIdentifiant` varchar(20) NOT NULL,
  PRIMARY KEY (`idEleve`),
  KEY `idClasse` (`idClasse`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Contenu de la table `televe`
--

INSERT INTO `televe` (`idEleve`, `eleveNom`, `elevePrenom`, `eleveMotPasse`, `idClasse`, `eleveIdentifiant`) VALUES
(1, 'Dupont', 'Alphonse', 'f6889fc97e14b42dec11a8c183ea791c5465b658', 14, 'Dupont.A'),
(2, 'Durand', 'Cléante', 'f6889fc97e14b42dec11a8c183ea791c5465b658', 1, 'Durand.C'),
(3, 'Rodrigez', 'Jean', 'f6889fc97e14b42dec11a8c183ea791c5465b658', 1, 'Rodrigez.J'),
(4, 'Rapaz', 'Charle', 'f6889fc97e14b42dec11a8c183ea791c5465b658', 12, 'Rapaz.C'),
(5, 'Leutart', 'Hubert', 'f6889fc97e14b42dec11a8c183ea791c5465b658', 10, 'Leutart.H'),
(6, 'Nosella', 'Yves', 'f6889fc97e14b42dec11a8c183ea791c5465b658', 14, 'Nosella.Y'),
(7, 'Teweld', 'Filmon', 'f6889fc97e14b42dec11a8c183ea791c5465b658', 13, 'Teweld.F'),
(8, 'Siron', 'Sébastien', 'f6889fc97e14b42dec11a8c183ea791c5465b658', 14, 'Siron.S'),
(9, 'Johner', 'Pierre', 'f6889fc97e14b42dec11a8c183ea791c5465b658', 14, 'Johner.P'),
(10, 'Cervera', 'Mikael', 'f6889fc97e14b42dec11a8c183ea791c5465b658', 14, 'Cervera.M'),
(11, 'Pedrina', 'Adrien', 'f6889fc97e14b42dec11a8c183ea791c5465b658', 2, 'Pedrina.A'),
(12, 'Picard', 'Jean-Luc', 'f6889fc97e14b42dec11a8c183ea791c5465b658', 14, 'Picard.L');

-- --------------------------------------------------------

--
-- Structure de la table `tprof`
--

CREATE TABLE IF NOT EXISTS `tprof` (
  `idProf` int(11) NOT NULL AUTO_INCREMENT,
  `profNom` varchar(20) NOT NULL,
  `profPrenom` varchar(20) NOT NULL,
  `profMotPasse` varchar(40) NOT NULL,
  `profIdentifiant` varchar(20) NOT NULL,
  PRIMARY KEY (`idProf`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Contenu de la table `tprof`
--

INSERT INTO `tprof` (`idProf`, `profNom`, `profPrenom`, `profMotPasse`, `profIdentifiant`) VALUES
(1, 'Fridly', 'Anne', 'ab99dd86a269c9b8dc4f6179f039515c5a745609', 'Fridly.A'),
(2, 'Norris', 'Chuck', 'f6889fc97e14b42dec11a8c183ea791c5465b658', 'Norris.C');

-- --------------------------------------------------------

--
-- Structure de la table `ttravail`
--

CREATE TABLE IF NOT EXISTS `ttravail` (
  `idTravail` int(11) NOT NULL AUTO_INCREMENT,
  `travailIntitule` varchar(50) NOT NULL,
  `travailDate` date NOT NULL,
  `idBranche` int(11) NOT NULL,
  `idClasse` int(11) NOT NULL,
  `idProf` int(11) NOT NULL,
  PRIMARY KEY (`idTravail`),
  KEY `idBranche` (`idBranche`),
  KEY `idClasse` (`idClasse`),
  KEY `idProf` (`idProf`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Contenu de la table `ttravail`
--

INSERT INTO `ttravail` (`idTravail`, `travailIntitule`, `travailDate`, `idBranche`, `idClasse`, `idProf`) VALUES
(10, 'Valium', '2015-03-01', 3, 2, 2),
(12, 'Arbeit', '2015-10-04', 9, 13, 2),
(13, 'Oral', '2015-02-02', 7, 10, 2),
(14, 'Communisme', '2016-11-03', 6, 14, 1),
(15, 'Dictée', '2015-01-30', 4, 13, 1);

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `tarealise`
--
ALTER TABLE `tarealise`
  ADD CONSTRAINT `contrainte05` FOREIGN KEY (`idEleve`) REFERENCES `televe` (`idEleve`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `contrainte06` FOREIGN KEY (`idTravail`) REFERENCES `ttravail` (`idTravail`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Contraintes pour la table `televe`
--
ALTER TABLE `televe`
  ADD CONSTRAINT `containte04` FOREIGN KEY (`idClasse`) REFERENCES `tclasse` (`idClasse`) ON DELETE NO ACTION ON UPDATE CASCADE;

--
-- Contraintes pour la table `ttravail`
--
ALTER TABLE `ttravail`
  ADD CONSTRAINT `contrainte01` FOREIGN KEY (`idBranche`) REFERENCES `tbranche` (`idBranche`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `contrainte02` FOREIGN KEY (`idClasse`) REFERENCES `tclasse` (`idClasse`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `contrainte03` FOREIGN KEY (`idProf`) REFERENCES `tprof` (`idProf`) ON DELETE NO ACTION ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
