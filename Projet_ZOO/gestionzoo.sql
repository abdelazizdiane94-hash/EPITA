-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : mar. 24 mars 2026 à 15:33
-- Version du serveur : 8.0.44
-- Version de PHP : 8.2.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gestionzoo`
--

-- --------------------------------------------------------

--
-- Structure de la table `animaux`
--

CREATE TABLE `animaux` (
  `id` int NOT NULL,
  `date_de_naissance` date DEFAULT NULL,
  `nom_animal` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `commentaire` text COLLATE utf8mb4_general_ci,
  `id_espece` int DEFAULT NULL,
  `sexe` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `image_path` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `enclos`
--

CREATE TABLE `enclos` (
  `id` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `nom_enclos` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `capacite_max` int DEFAULT NULL,
  `eau` tinyint(1) DEFAULT NULL,
  `taille` float DEFAULT NULL,
  `id_responsable` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `especes`
--

CREATE TABLE `especes` (
  `id` int NOT NULL,
  `nom_espece` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `duree_vie_moyenne` int DEFAULT NULL,
  `aquatique` tinyint(1) DEFAULT NULL,
  `id_nourriture` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `loc_animaux`
--

CREATE TABLE `loc_animaux` (
  `id` int NOT NULL,
  `date_arrivee` date DEFAULT NULL,
  `date_sortie` date DEFAULT NULL,
  `id_animaux` int DEFAULT NULL,
  `id_enclos` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `personnel`
--

CREATE TABLE `personnel` (
  `id` int NOT NULL,
  `nom` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `prenom` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `date_de_naissance` date DEFAULT NULL,
  `login` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `salaire` decimal(5,2) DEFAULT NULL,
  `sexe` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fonction` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `photo` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `personnel`
--

INSERT INTO `personnel` (`id`, `nom`, `prenom`, `date_de_naissance`, `login`, `password`, `salaire`, `sexe`, `fonction`, `photo`) VALUES
(9, 'Diané', 'Abdel-Aziz', '2007-12-05', 'Abdel', '1', NULL, NULL, 'Directeur', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `types_nourriture`
--

CREATE TABLE `types_nourriture` (
  `id_nourriture` int NOT NULL,
  `type_nourriture` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `animaux`
--
ALTER TABLE `animaux`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_espece` (`id_espece`);

--
-- Index pour la table `enclos`
--
ALTER TABLE `enclos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_responsable` (`id_responsable`);

--
-- Index pour la table `especes`
--
ALTER TABLE `especes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_nourriture` (`id_nourriture`);

--
-- Index pour la table `loc_animaux`
--
ALTER TABLE `loc_animaux`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_animaux` (`id_animaux`),
  ADD KEY `id_enclos` (`id_enclos`);

--
-- Index pour la table `personnel`
--
ALTER TABLE `personnel`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `login` (`login`);

--
-- Index pour la table `types_nourriture`
--
ALTER TABLE `types_nourriture`
  ADD PRIMARY KEY (`id_nourriture`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `animaux`
--
ALTER TABLE `animaux`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `especes`
--
ALTER TABLE `especes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `loc_animaux`
--
ALTER TABLE `loc_animaux`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `personnel`
--
ALTER TABLE `personnel`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `types_nourriture`
--
ALTER TABLE `types_nourriture`
  MODIFY `id_nourriture` int NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `animaux`
--
ALTER TABLE `animaux`
  ADD CONSTRAINT `animaux_ibfk_1` FOREIGN KEY (`id_espece`) REFERENCES `especes` (`id`);

--
-- Contraintes pour la table `enclos`
--
ALTER TABLE `enclos`
  ADD CONSTRAINT `enclos_ibfk_1` FOREIGN KEY (`id_responsable`) REFERENCES `personnel` (`id`);

--
-- Contraintes pour la table `especes`
--
ALTER TABLE `especes`
  ADD CONSTRAINT `especes_ibfk_1` FOREIGN KEY (`id_nourriture`) REFERENCES `types_nourriture` (`id_nourriture`);

--
-- Contraintes pour la table `loc_animaux`
--
ALTER TABLE `loc_animaux`
  ADD CONSTRAINT `loc_animaux_ibfk_1` FOREIGN KEY (`id_animaux`) REFERENCES `animaux` (`id`),
  ADD CONSTRAINT `loc_animaux_ibfk_2` FOREIGN KEY (`id_enclos`) REFERENCES `enclos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
