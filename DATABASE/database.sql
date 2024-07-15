-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 15 juil. 2024 à 14:24
-- Version du serveur : 8.2.0
-- Version de PHP : 8.3.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `export`
--

-- --------------------------------------------------------

--
-- Structure de la table `actualites`
--

CREATE TABLE `actualites` (
  `id` int NOT NULL,
  `titre` varchar(100) DEFAULT NULL,
  `head` varchar(60) DEFAULT NULL,
  `body` varchar(400) DEFAULT NULL,
  `texts` varchar(3000) DEFAULT NULL,
  `dates` date DEFAULT NULL,
  `image` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `communes`
--

CREATE TABLE `communes` (
  `id` int NOT NULL,
  `commune` varchar(255) DEFAULT NULL,
  `maire` varchar(255) DEFAULT NULL,
  `adresse_postale` varchar(255) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `mail` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `events`
--

CREATE TABLE `events` (
  `id` int NOT NULL,
  `event_date` date NOT NULL,
  `event_title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `galerie`
--

CREATE TABLE `galerie` (
  `id` int NOT NULL,
  `bio` varchar(125) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `link` varchar(1000) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `link`
--

CREATE TABLE `link` (
  `id` int NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(1500) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `id` int NOT NULL,
  `user_id` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `idroles` int NOT NULL,
  `nom` text NOT NULL,
  `ajouter` tinyint(1) NOT NULL,
  `supprimer` tinyint(1) NOT NULL,
  `modifier` tinyint(1) NOT NULL,
  `acpanel` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `services`
--

CREATE TABLE `services` (
  `id` int NOT NULL,
  `nom` varchar(512) DEFAULT NULL,
  `prenom` varchar(512) DEFAULT NULL,
  `numExt` varchar(512) DEFAULT NULL,
  `dirFixe` varchar(512) DEFAULT NULL,
  `numPort` varchar(512) DEFAULT NULL,
  `email` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `poste` varchar(512) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `tickets`
--

CREATE TABLE `tickets` (
  `id` int NOT NULL,
  `raison` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `pages` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `utilisateurs` varchar(50) NOT NULL,
  `archive` tinyint(1) NOT NULL,
  `etat` varchar(10) NOT NULL,
  `date_creation` date NOT NULL,
  `foreignkey` int NOT NULL,
  `nomAnnuaire` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `prenomAnnuaire` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `color` varchar(15) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'yellow'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `ticketsmodif`
--

CREATE TABLE `ticketsmodif` (
  `foreignkey` int NOT NULL,
  `email` varchar(50) NOT NULL,
  `numport` varchar(15) NOT NULL,
  `numext` varchar(15) NOT NULL,
  `dirfixe` int NOT NULL,
  `adresse_postale` varchar(100) NOT NULL,
  `nomAnnuaire` varchar(255) DEFAULT NULL,
  `prenomAnnuaire` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `firstname` text NOT NULL,
  `lastname` text NOT NULL,
  `username` text NOT NULL,
  `email` varchar(50) NOT NULL,
  `bio` text NOT NULL,
  `img` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `idroles` int NOT NULL,
  `poste` text NOT NULL,
  `last-conn` text NOT NULL,
  `fonctions` text NOT NULL,
  `numero` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `actualites`
--
ALTER TABLE `actualites`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `communes`
--
ALTER TABLE `communes`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `galerie`
--
ALTER TABLE `galerie`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `link`
--
ALTER TABLE `link`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `foreignKey` (`foreignkey`);

--
-- Index pour la table `ticketsmodif`
--
ALTER TABLE `ticketsmodif`
  ADD KEY `foreignKey` (`foreignkey`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `actualites`
--
ALTER TABLE `actualites`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `communes`
--
ALTER TABLE `communes`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT pour la table `events`
--
ALTER TABLE `events`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `galerie`
--
ALTER TABLE `galerie`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `link`
--
ALTER TABLE `link`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT pour la table `services`
--
ALTER TABLE `services`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT pour la table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=187;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `ticketsmodif`
--
ALTER TABLE `ticketsmodif`
  ADD CONSTRAINT `ticketsmodif_ibfk_1` FOREIGN KEY (`foreignkey`) REFERENCES `tickets` (`foreignkey`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
