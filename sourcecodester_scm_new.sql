-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 27 mai 2024 à 18:55
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `sourcecodester_scm_new`
--

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`id`, `username`, `password`) VALUES
(1, 'admin', 'admin123');

-- --------------------------------------------------------

--
-- Structure de la table `area`
--

CREATE TABLE `area` (
  `area_id` int(11) NOT NULL,
  `area_name` varchar(50) NOT NULL,
  `area_code` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `area`
--

INSERT INTO `area` (`area_id`, `area_name`, `area_code`) VALUES
(1, 'Sarkhej', 'SRKJ'),
(2, 'Vastrapur', 'VSTR'),
(3, 'Maninagar', 'MNGR'),
(4, 'New Ranip', 'NRANIP');

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

CREATE TABLE `categories` (
  `cat_id` int(11) NOT NULL,
  `cat_name` varchar(25) NOT NULL,
  `cat_details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`cat_id`, `cat_name`, `cat_details`) VALUES
(1, 'Fast Food', ''),
(2, 'Bread Buns', ''),
(3, 'Counter Cakes', ''),
(5, 'Pastry Rs - 55', ''),
(9, 'Add On Items', '');

-- --------------------------------------------------------

--
-- Structure de la table `distributor`
--

CREATE TABLE `distributor` (
  `dist_id` int(11) NOT NULL,
  `dist_name` varchar(25) NOT NULL,
  `dist_email` varchar(50) DEFAULT NULL,
  `dist_phone` varchar(10) NOT NULL,
  `dist_address` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `distributor`
--

INSERT INTO `distributor` (`dist_id`, `dist_name`, `dist_email`, `dist_phone`, `dist_address`) VALUES
(5, 'Hanihjgu', 'hanipatel@gmail.com', '8980745372', 'CTM, Ahmedabad'),
(7, 'Mamoud', 'car32@gmail.com', '+237695367', 'miami');

-- --------------------------------------------------------

--
-- Structure de la table `invoice`
--

CREATE TABLE `invoice` (
  `invoice_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `retailer_id` int(11) NOT NULL,
  `dist_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `total_amount` decimal(10,3) NOT NULL,
  `comments` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `invoice_items`
--

CREATE TABLE `invoice_items` (
  `invoice_items_id` int(11) NOT NULL,
  `invoice_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Structure de la table `manufacturer`
--

CREATE TABLE `manufacturer` (
  `man_id` int(11) NOT NULL,
  `man_name` varchar(25) NOT NULL,
  `man_email` varchar(50) DEFAULT NULL,
  `man_phone` varchar(10) NOT NULL,
  `username` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `notification_message` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `manufacturer`
--

INSERT INTO `manufacturer` (`man_id`, `man_name`, `man_email`, `man_phone`, `username`, `password`, `notification_message`) VALUES
(1, 'Suresh Kumar', 'suresh@gmail.com', '697345680', 'suresh', 'suresh123', NULL),
(2, 'Ankit Pandya', 'ankitp@gmail.com', '8980956231', 'ankit', 'ankit123', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `recipient_id` int(11) DEFAULT NULL,
  `message` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `recipient_id`, `message`, `created_at`) VALUES
(6, 0, 'Your order (ID: 37) has been confirmed and received by the retailer.', '2024-05-23 20:03:45'),
(7, 0, 'Your order (ID: 38) has been confirmed and received by the retailer.', '2024-05-23 20:31:52'),
(9, 0, 'Your order (ID: 39) has been confirmed and received by the retailer.', '2024-05-23 20:58:59'),
(10, 0, 'Your order (ID: 40) has been confirmed and received by the retailer.', '2024-05-27 10:09:09'),
(11, 0, 'Your order (ID: 41) has been confirmed and received by the retailer.', '2024-05-27 10:47:21'),
(12, 0, 'Your order (ID: 42) has been confirmed and received by the retailer.', '2024-05-27 11:18:39'),
(13, 0, 'Your order (ID: 43) has been confirmed and received by the retailer.', '2024-05-27 11:20:08'),
(14, 0, 'Your order (ID: 45) has been confirmed and received by the retailer.', '2024-05-27 11:59:20'),
(15, 0, 'Your order (ID: 46) has been confirmed and received by the retailer.', '2024-05-27 16:27:39'),
(16, 0, 'Your order (ID: 47) has been confirmed and received by the retailer.', '2024-05-27 16:34:10'),
(17, 0, 'Your order (ID: 48) has been confirmed and received by the retailer.', '2024-05-27 16:48:58');

-- --------------------------------------------------------

--
-- Structure de la table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `retailer_id` int(11) NOT NULL,
  `man_id` int(11) DEFAULT NULL,
  `approved` tinyint(1) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `total_amount` decimal(10,3) NOT NULL,
  `received` tinyint(1) DEFAULT 0,
  `received_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `orders`
--

INSERT INTO `orders` (`order_id`, `date`, `retailer_id`, `man_id`, `approved`, `status`, `total_amount`, `received`, `received_date`) VALUES
(41, '2024-05-27', 7, NULL, 0, 0, 97.000, 1, '2024-05-27 10:47:21'),
(42, '2024-05-27', 7, NULL, 0, 0, 145.500, 1, '2024-05-27 11:18:39'),
(43, '2024-05-27', 7, NULL, 0, 0, 97.000, 1, '2024-05-27 11:20:08'),
(44, '2024-05-27', 7, NULL, 0, 0, 145.500, 1, '2024-05-27 11:58:43'),
(45, '2024-05-27', 7, NULL, 0, 0, 97.000, 1, '2024-05-27 11:59:20'),
(46, '2024-05-27', 7, NULL, 0, 0, 145.500, 1, '2024-05-27 16:27:39'),
(47, '2024-05-27', 7, NULL, 0, 0, 48.500, 1, '2024-05-27 16:34:10'),
(48, '2024-05-27', 7, NULL, 0, 0, 145.500, 1, '2024-05-27 16:48:58');

-- --------------------------------------------------------

--
-- Structure de la table `order_items`
--

CREATE TABLE `order_items` (
  `order_items_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `pro_id` int(11) NOT NULL,
  `quantity` int(6) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `order_items`
--

INSERT INTO `order_items` (`order_items_id`, `order_id`, `pro_id`, `quantity`) VALUES
(94, 41, 9, 2),
(95, 42, 9, 3),
(96, 43, 9, 2),
(97, 44, 9, 3),
(98, 45, 9, 2),
(99, 46, 9, 3),
(100, 47, 9, 1),
(101, 48, 9, 3);

-- --------------------------------------------------------

--
-- Structure de la table `products`
--

CREATE TABLE `products` (
  `pro_id` int(11) NOT NULL,
  `pro_name` varchar(25) NOT NULL,
  `pro_desc` text DEFAULT NULL,
  `pro_price` decimal(10,3) NOT NULL,
  `unit` int(11) NOT NULL,
  `pro_cat` int(11) NOT NULL,
  `quantity` int(6) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `products`
--

INSERT INTO `products` (`pro_id`, `pro_name`, `pro_desc`, `pro_price`, `unit`, `pro_cat`, `quantity`) VALUES
(9, 'Dabeli Bun 12 PCS', '', 48.500, 2, 2, NULL),
(19, 'www', 'yo', 899.000, 1, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `retailer`
--

CREATE TABLE `retailer` (
  `retailer_id` int(11) NOT NULL,
  `username` varchar(25) NOT NULL,
  `password` varchar(25) NOT NULL,
  `address` varchar(200) NOT NULL,
  `area_id` int(11) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `email` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `retailer`
--

INSERT INTO `retailer` (`retailer_id`, `username`, `password`, `address`, `area_id`, `phone`, `email`) VALUES
(2, 'nayan', 'nayan123', 'Opp. Shivalik Complex, Vastrapur, Ahmedabad', 2, '9898906523', 'nayan@gmail.com'),
(7, 'neymar', 'neymar123', 'yaounde', 1, '696453224', 'neymar@gmail.com');

-- --------------------------------------------------------

--
-- Structure de la table `unit`
--

CREATE TABLE `unit` (
  `id` int(11) NOT NULL,
  `unit_name` varchar(20) NOT NULL,
  `unit_details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Déchargement des données de la table `unit`
--

INSERT INTO `unit` (`id`, `unit_name`, `unit_details`) VALUES
(1, 'KG', 'Kilo Gram'),
(2, 'PCS', 'Pieces'),
(3, 'LTR', 'Litre');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `area`
--
ALTER TABLE `area`
  ADD PRIMARY KEY (`area_id`);

--
-- Index pour la table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`cat_id`);

--
-- Index pour la table `distributor`
--
ALTER TABLE `distributor`
  ADD PRIMARY KEY (`dist_id`);

--
-- Index pour la table `invoice`
--
ALTER TABLE `invoice`
  ADD PRIMARY KEY (`invoice_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `retailer_id` (`retailer_id`),
  ADD KEY `dist_id` (`dist_id`);

--
-- Index pour la table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD PRIMARY KEY (`invoice_items_id`),
  ADD KEY `invoice_id` (`invoice_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Index pour la table `manufacturer`
--
ALTER TABLE `manufacturer`
  ADD PRIMARY KEY (`man_id`);

--
-- Index pour la table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`);

--
-- Index pour la table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `retailer_id` (`retailer_id`);

--
-- Index pour la table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_items_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `pro_id` (`pro_id`);

--
-- Index pour la table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`pro_id`),
  ADD KEY `unit` (`unit`),
  ADD KEY `pro_cat` (`pro_cat`);

--
-- Index pour la table `retailer`
--
ALTER TABLE `retailer`
  ADD PRIMARY KEY (`retailer_id`),
  ADD KEY `area_code` (`area_id`),
  ADD KEY `area_id` (`area_id`),
  ADD KEY `area_id_2` (`area_id`),
  ADD KEY `area_id_3` (`area_id`),
  ADD KEY `area_id_4` (`area_id`);

--
-- Index pour la table `unit`
--
ALTER TABLE `unit`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `area`
--
ALTER TABLE `area`
  MODIFY `area_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `categories`
--
ALTER TABLE `categories`
  MODIFY `cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `distributor`
--
ALTER TABLE `distributor`
  MODIFY `dist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `invoice`
--
ALTER TABLE `invoice`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `invoice_items`
--
ALTER TABLE `invoice_items`
  MODIFY `invoice_items_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT pour la table `manufacturer`
--
ALTER TABLE `manufacturer`
  MODIFY `man_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT pour la table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT pour la table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_items_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT pour la table `products`
--
ALTER TABLE `products`
  MODIFY `pro_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `retailer`
--
ALTER TABLE `retailer`
  MODIFY `retailer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `unit`
--
ALTER TABLE `unit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `invoice`
--
ALTER TABLE `invoice`
  ADD CONSTRAINT `invoice_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `invoice_ibfk_2` FOREIGN KEY (`retailer_id`) REFERENCES `retailer` (`retailer_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `invoice_ibfk_3` FOREIGN KEY (`dist_id`) REFERENCES `distributor` (`dist_id`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `invoice_items`
--
ALTER TABLE `invoice_items`
  ADD CONSTRAINT `invoice_items_ibfk_1` FOREIGN KEY (`invoice_id`) REFERENCES `invoice` (`invoice_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `invoice_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`pro_id`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`retailer_id`) REFERENCES `retailer` (`retailer_id`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`pro_id`) REFERENCES `products` (`pro_id`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`unit`) REFERENCES `unit` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`pro_cat`) REFERENCES `categories` (`cat_id`) ON UPDATE CASCADE;

--
-- Contraintes pour la table `retailer`
--
ALTER TABLE `retailer`
  ADD CONSTRAINT `retailer_ibfk_1` FOREIGN KEY (`area_id`) REFERENCES `area` (`area_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
