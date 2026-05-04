-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 04 mai 2026 à 01:56
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `magasin`
--

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

CREATE TABLE `article` (
  `codart` varchar(20) NOT NULL,
  `description` varchar(100) NOT NULL,
  `prix` decimal(10,2) NOT NULL DEFAULT 0.00,
  `categorie` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`codart`, `description`, `prix`, `categorie`) VALUES
('AZERT', 'Ordinateur HP Elitebook', 2000000.00, 'Informatique'),
('BENUE', 'Téléphone Portable iphone', 200000.00, 'Informatique'),
('CA300', 'Canon EOS 3000V zoom 28/80', 32900.00, 'photo'),
('CAS07', 'Cassette DV60 par 5', 4580.00, 'divers'),
('CP100', 'Caméscope Panasonic SV-AV 100', 149000.00, 'Vidéo'),
('CS330', 'Caméscope Sony DCR-PC330', 162900.00, 'Vidéo'),
('DEL30', 'Portable Dell X300', 23499.00, 'Informatique'),
('DVD75', 'DVD Vierge pat 3', 1750.00, 'divers'),
('HP497', 'PC Bureau HP467 écran TFT', 700450.00, 'Informatique'),
('NIK55', 'Nikon F55+zoom 28/80', 26900.00, 'photo'),
('NIK80', 'Nikon F80', 47900.00, 'photo'),
('SAX15', 'Portable Samsung X15 XVM', 245000.00, 'Informatique'),
('SOMXMP', 'PC Portable Sony Z1-XMP', 239000.00, 'Informatique'),
('VR17pro', 'iphone 17pro', 3678900.00, 'Informatique');

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `id_client` mediumint(8) UNSIGNED NOT NULL,
  `nom` varchar(30) NOT NULL,
  `prenom` varchar(30) NOT NULL,
  `age` varchar(2) NOT NULL,
  `numéro` int(10) NOT NULL,
  `ville` varchar(20) NOT NULL,
  `adresse` varchar(60) NOT NULL,
  `mail` varchar(50) NOT NULL,
  `mot_de_passe` varchar(20) DEFAULT NULL,
  `id_user` mediumint(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`id_client`, `nom`, `prenom`, `age`, `numéro`, `ville`, `adresse`, `mail`, `mot_de_passe`, `id_user`) VALUES
(1, 'ALID', 'Jean', '', 0, '', 'jgfkhg', 'Admin@gmail.com', 'Vladimir', 0),
(2, 'FATON', 'VLAD', '', 0, '', 'alotreyui', 'boss@gmail.com', NULL, 0),
(3, 'MAGENGO', 'GUTEMBERT', '12', 123678954, 'COTONOU', 'APKAPKA', 'KING@gmail.com', NULL, 4),
(4, 'BILLY', 'Berger', '20', 123458790, 'Abidjan', 'vlodimir-avenue12', 'Billy@gmail.com', NULL, 5);

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id_com` mediumint(8) UNSIGNED NOT NULL,
  `id_client` mediumint(8) UNSIGNED NOT NULL,
  `date_com` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id_com`, `id_client`, `date_com`) VALUES
(1, 2, '05/2026');

-- --------------------------------------------------------

--
-- Structure de la table `detail_vente`
--

CREATE TABLE `detail_vente` (
  `id_detail` int(11) NOT NULL,
  `id_vente` int(11) NOT NULL,
  `codart` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `quantite` int(11) NOT NULL,
  `prix_unitaire` decimal(10,2) NOT NULL,
  `montant` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Déchargement des données de la table `detail_vente`
--

INSERT INTO `detail_vente` (`id_detail`, `id_vente`, `codart`, `quantite`, `prix_unitaire`, `montant`) VALUES
(1, 1, 'DVD75', 1, 1750.00, 1750.00),
(2, 2, 'SOMXMP', 1, 239000.00, 239000.00),
(3, 2, 'SOMXMP', 1, 239000.00, 239000.00),
(4, 3, 'DEL30', 1, 23499.00, 23499.00),
(5, 3, 'VR17pro', 1, 3678900.00, 3678900.00);

-- --------------------------------------------------------

--
-- Structure de la table `ligne_commande`
--

CREATE TABLE `ligne_commande` (
  `id_ligne` int(11) NOT NULL,
  `id_com` int(11) NOT NULL,
  `codart` varchar(20) NOT NULL,
  `quantite` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `ligne_commande`
--

INSERT INTO `ligne_commande` (`id_ligne`, `id_com`, `codart`, `quantite`) VALUES
(1, 1, 'DEL30', 1),
(2, 1, 'VR17pro', 1);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` mediumint(11) NOT NULL,
  `nom` varchar(20) NOT NULL,
  `prenom` varchar(20) NOT NULL,
  `adresse` varchar(60) NOT NULL,
  `numéro` int(10) NOT NULL,
  `ville` varchar(15) NOT NULL,
  `mail` varchar(30) NOT NULL,
  `mot_de_passe` varchar(50) NOT NULL,
  `tentatives` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `nom`, `prenom`, `adresse`, `numéro`, `ville`, `mail`, `mot_de_passe`, `tentatives`) VALUES
(1, '', '', '', 0, '', 'boss@gmail.com', 'azerty', 0),
(4, '', '', '', 0, '', 'KING@gmail.com', 'ytreza', 0),
(5, '', '', '', 0, '', 'Billy@gmail.com', 'Billy321', 0);

-- --------------------------------------------------------

--
-- Structure de la table `vente`
--

CREATE TABLE `vente` (
  `id_vente` int(11) NOT NULL,
  `id_client` mediumint(9) NOT NULL,
  `date_vente` datetime NOT NULL DEFAULT current_timestamp(),
  `montant_total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `vente`
--

INSERT INTO `vente` (`id_vente`, `id_client`, `date_vente`, `montant_total`) VALUES
(1, 3, '2026-05-03 02:07:08', 1750.00),
(2, 2, '2026-05-03 02:07:25', 478000.00),
(3, 2, '2026-05-04 00:08:26', 3702399.00);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`codart`);

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id_client`),
  ADD KEY `fk_user_client` (`id_user`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id_com`,`id_client`);

--
-- Index pour la table `detail_vente`
--
ALTER TABLE `detail_vente`
  ADD PRIMARY KEY (`id_detail`),
  ADD KEY `fk_detail_vente` (`id_vente`),
  ADD KEY `codart` (`codart`),
  ADD KEY `codart_2` (`codart`);

--
-- Index pour la table `ligne_commande`
--
ALTER TABLE `ligne_commande`
  ADD PRIMARY KEY (`id_ligne`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `vente`
--
ALTER TABLE `vente`
  ADD PRIMARY KEY (`id_vente`),
  ADD KEY `id_client` (`id_client`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `client`
--
ALTER TABLE `client`
  MODIFY `id_client` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id_com` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `detail_vente`
--
ALTER TABLE `detail_vente`
  MODIFY `id_detail` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `ligne_commande`
--
ALTER TABLE `ligne_commande`
  MODIFY `id_ligne` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` mediumint(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `vente`
--
ALTER TABLE `vente`
  MODIFY `id_vente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `client`
--
ALTER TABLE `client`
  ADD CONSTRAINT `fk_user_client` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `detail_vente`
--
ALTER TABLE `detail_vente`
  ADD CONSTRAINT `fk_detail_article` FOREIGN KEY (`codart`) REFERENCES `article` (`codart`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detail_vente` FOREIGN KEY (`id_vente`) REFERENCES `vente` (`id_vente`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
