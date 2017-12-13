-- phpMyAdmin SQL Dump
-- version 4.5.5.1
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mer 13 Décembre 2017 à 07:34
-- Version du serveur :  5.7.11
-- Version de PHP :  5.6.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `apidallas`
--

-- --------------------------------------------------------

--
-- Structure de la table `content`
--

CREATE TABLE `content` (
  `id` int(30) NOT NULL,
  `quantitiy` int(11) NOT NULL,
  `id_order` int(11) NOT NULL,
  `id_article` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `content`
--

INSERT INTO `content` (`id`, `quantitiy`, `id_order`, `id_article`, `date`) VALUES
(1, 2, 1, 1, '2017-12-06 10:21:29'),
(2, 2, 2, 2, '2017-12-06 10:24:10'),
(3, 1, 2, 1, '2017-12-11 08:47:00'),
(4, 4, 4, 2, '2017-12-11 08:51:35');

-- --------------------------------------------------------

--
-- Structure de la table `tbl_article`
--

CREATE TABLE `tbl_article` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `price` float NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `tbl_article`
--

INSERT INTO `tbl_article` (`id`, `name`, `price`, `active`) VALUES
(1, 'Kinder', 2, 1),
(2, 'Bueno', 1, 1),
(4, 'Cafe', 2, 0);

-- --------------------------------------------------------

--
-- Structure de la table `tbl_key`
--

CREATE TABLE `tbl_key` (
  `id` int(11) NOT NULL,
  `UID` varchar(50) NOT NULL,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `tbl_key`
--

INSERT INTO `tbl_key` (`id`, `UID`, `id_user`) VALUES
(1, '28738273827837', 1),
(2, '2368237827832453', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `tbl_order`
--

CREATE TABLE `tbl_order` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `tbl_order`
--

INSERT INTO `tbl_order` (`id`, `id_user`) VALUES
(1, 1),
(2, 2),
(3, 2),
(4, 2);

-- --------------------------------------------------------

--
-- Structure de la table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id` int(11) NOT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `email` varchar(80) DEFAULT NULL,
  `admin` tinyint(4) NOT NULL DEFAULT '0',
  `token` varchar(50) DEFAULT NULL,
  `credit` float DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `tbl_user`
--

INSERT INTO `tbl_user` (`id`, `firstname`, `lastname`, `email`, `admin`, `token`, `credit`, `active`) VALUES
(1, 'Alan', 'Mortellaro', 'alan.mortellaro@rpn.ch', 1, '7ed17ac1ece95f1951a0c250f4ee9c55', 10000, 1),
(2, 'John', 'Doe', 'John.Doe@gmail.com', 0, NULL, 40, 1);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `content`
--
ALTER TABLE `content`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_content_id` (`id_order`),
  ADD KEY `FK_content_id_tbl_article` (`id_article`);

--
-- Index pour la table `tbl_article`
--
ALTER TABLE `tbl_article`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tbl_key`
--
ALTER TABLE `tbl_key`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_tbl_key_id_tbl_user` (`id_user`);

--
-- Index pour la table `tbl_order`
--
ALTER TABLE `tbl_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_user` (`id_user`);

--
-- Index pour la table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `content`
--
ALTER TABLE `content`
  MODIFY `id` int(30) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `tbl_article`
--
ALTER TABLE `tbl_article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `tbl_key`
--
ALTER TABLE `tbl_key`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `tbl_order`
--
ALTER TABLE `tbl_order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `content`
--
ALTER TABLE `content`
  ADD CONSTRAINT `FK_content_id` FOREIGN KEY (`id_order`) REFERENCES `tbl_order` (`id`),
  ADD CONSTRAINT `FK_content_id_tbl_article` FOREIGN KEY (`id_article`) REFERENCES `tbl_article` (`id`);

--
-- Contraintes pour la table `tbl_key`
--
ALTER TABLE `tbl_key`
  ADD CONSTRAINT `FK_tbl_key_id_tbl_user` FOREIGN KEY (`id_user`) REFERENCES `tbl_user` (`id`);

--
-- Contraintes pour la table `tbl_order`
--
ALTER TABLE `tbl_order`
  ADD CONSTRAINT `FK_tbl_order_id_tbl_user` FOREIGN KEY (`id_user`) REFERENCES `tbl_user` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
