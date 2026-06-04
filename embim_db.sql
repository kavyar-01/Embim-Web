-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 04, 2026 at 09:46 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `embim_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_days` smallint(6) NOT NULL DEFAULT 1,
  `total_price` decimal(14,2) NOT NULL,
  `status` enum('pending','confirmed','ongoing','completed','cancelled') DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `identity_photo` varchar(255) DEFAULT NULL,
  `payment_method` enum('transfer_bank_bri','transfer_bank_mandiri','shopeepay','dana','ovo','gopay','transfer_bank_bni','transfer_bank_bca','transfer_bank_seabank') DEFAULT NULL,
  `payment_status` enum('unpaid','paid','refunded') DEFAULT 'unpaid',
  `payment_proof` varchar(255) DEFAULT NULL,
  `paid_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `car_id`, `start_date`, `end_date`, `total_days`, `total_price`, `status`, `notes`, `identity_photo`, `payment_method`, `payment_status`, `payment_proof`, `paid_at`, `created_at`, `updated_at`) VALUES
(2, 13, 4, '2026-06-06', '2026-06-09', 3, 16800000.00, 'completed', 'JAMES BOND', 'selfie_13_1780576619.png', 'gopay', 'paid', 'proof_13_1780576629.png', '2026-06-04 19:37:09', '2026-06-04 19:36:59', '2026-06-04 19:37:09'),
(3, 13, 3, '2026-06-20', '2026-06-21', 1, 3800000.00, 'completed', '.', 'selfie_13_1780579577.png', 'gopay', 'paid', 'proof_13_1780579603.png', '2026-06-04 20:26:43', '2026-06-04 20:26:17', '2026-06-04 20:33:25'),
(4, 13, 1, '2026-06-06', '2026-06-08', 2, 7000000.00, 'cancelled', 'AWDAW', 'selfie_13_1780592408.png', 'gopay', 'paid', 'proof_13_1780592413.png', '2026-06-05 00:00:13', '2026-06-05 00:00:08', '2026-06-05 00:09:02'),
(7, 13, 2, '2026-06-06', '2026-06-08', 2, 8400000.00, 'cancelled', 'mkkn', 'selfie_13_1780596250.png', 'transfer_bank_mandiri', 'paid', 'proof_13_1780596276.png', '2026-06-05 01:04:36', '2026-06-05 01:04:36', '2026-06-05 01:07:53'),
(8, 13, 4, '2026-06-06', '2026-06-08', 2, 11200000.00, 'cancelled', 'Tarot', 'selfie_13_1780596522.png', 'transfer_bank_bca', 'paid', 'proof_13_1780596527.png', '2026-06-05 01:08:47', '2026-06-05 01:08:47', '2026-06-05 01:09:02');

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `id` int(11) NOT NULL,
  `brand` varchar(80) NOT NULL,
  `model` varchar(100) NOT NULL,
  `year` year(4) NOT NULL,
  `stock` int(11) NOT NULL,
  `license_plate` varchar(20) NOT NULL,
  `price_per_day` decimal(12,2) NOT NULL,
  `status` enum('available','booked','maintenance') NOT NULL DEFAULT 'available',
  `transmission` enum('automatic','manual') NOT NULL DEFAULT 'automatic',
  `fuel_type` enum('gasoline','diesel','electric','hybrid') NOT NULL DEFAULT 'gasoline',
  `seats` tinyint(4) NOT NULL DEFAULT 5,
  `description` text DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL COMMENT 'Nama file gambar di folder assets/images',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cars`
--

INSERT INTO `cars` (`id`, `brand`, `model`, `year`, `stock`, `license_plate`, `price_per_day`, `status`, `transmission`, `fuel_type`, `seats`, `description`, `photo`, `created_at`, `updated_at`) VALUES
(1, 'Nissan', 'Nissan GTR R34', '2024', 1, 'D 1234 ABM', 3500000.00, 'available', 'automatic', 'electric', 5, 'Sedan listrik ultra-premium dengan akselerasi 0-100 km/h dalam 2,1 detik. Dilengkapi layar sentuh 17 inci, Autopilot, dan interior mewah vegan leather.', 'nissan_gtr_r34.jpg', '2026-05-25 18:55:06', '2026-06-05 00:26:59'),
(2, 'Mercedes-Benz', 'S-Class S500 4MATIC', '2023', 2, 'D 5678 EMB', 4200000.00, 'available', 'automatic', 'gasoline', 5, 'Sedan flagship Mercedes dengan suspensi air AIRMATIC, ambient lighting 64 warna, sistem audio Burmester 4D, dan fitur MBUX Hyperscreen.', 'Mercedes-Benz S-Class_S500_4MATIC.jpeg', '2026-05-25 18:55:06', '2026-06-05 01:07:53'),
(3, 'BMW', 'M4 Competition', '2023', 1, 'D 9101 IME', 3800000.00, 'booked', 'manual', 'gasoline', 4, 'Sports coupe bertenaga 510 hp dengan M xDrive AWD, rem karbon-keramik opsional, dan teknologi M Servotronic steering untuk pengalaman berkendara kelas dunia.', 'bmw-m4.jpg', '2026-05-25 18:55:06', '2026-06-05 00:27:04'),
(4, 'Porsche', '911 Carrera S', '2024', 1, 'D 1121 BIM', 5600000.00, 'available', 'manual', 'gasoline', 4, 'Ikon otomotif legendaris dengan mesin flat-six 450 hp, PDK 8-speed, PASM active suspension, dan desain timeless yang diakui dunia.', 'porsche_carreraS.jpg', '2026-05-25 18:55:06', '2026-06-05 01:09:02');

-- --------------------------------------------------------

--
-- Table structure for table `returns`
--

CREATE TABLE `returns` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `return_date` date NOT NULL,
  `late_days` smallint(6) NOT NULL DEFAULT 0,
  `car_condition` enum('good','damaged') NOT NULL DEFAULT 'good',
  `notes` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `fine_per_day` decimal(12,2) DEFAULT 700000.00,
  `fine_amount` decimal(14,2) DEFAULT 0.00,
  `fine_status` enum('none','unpaid','paid') DEFAULT 'none'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `returns`
--

INSERT INTO `returns` (`id`, `booking_id`, `return_date`, `late_days`, `car_condition`, `notes`, `created_at`, `fine_per_day`, `fine_amount`, `fine_status`) VALUES
(1, 3, '2026-06-22', 1, 'good', 'OKE', '2026-06-05 02:17:17', 700000.00, 700000.00, 'paid');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL COMMENT 'Satu booking hanya bisa review sekali',
  `rating` tinyint(4) NOT NULL DEFAULT 5,
  `comment` text DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `car_id`, `booking_id`, `rating`, `comment`, `created_at`) VALUES
(6, 13, 4, 2, 4, 'Kendarannya baik', '2026-06-04 20:27:33'),
(7, 13, 3, 3, 3, 'Indah', '2026-06-04 23:56:09');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL COMMENT 'bcrypt hash',
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `role` enum('admin','user') NOT NULL DEFAULT 'user',
  `photo_profile` varchar(100) DEFAULT NULL,
  `photo_ktp` varchar(100) DEFAULT NULL,
  `photo_sim` varchar(100) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `phone`, `address`, `role`, `photo_profile`, `photo_ktp`, `photo_sim`, `created_at`, `updated_at`) VALUES
(1, 'Admin EMBIM', 'admin@embim.com', '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '+6282144845847', 'Jl. Cikutra No.1, Bandung, Jawa Barat 40124', 'admin', NULL, NULL, NULL, '2026-05-25 18:55:06', '2026-05-25 18:55:06'),
(2, 'Budi Santoso', 'budi@gmail.com', 'Budi123', '+6281234567890', 'Jl. Dago Asri No.45, Bandung, Jawa Barat 40135', 'user', NULL, NULL, NULL, '2026-05-25 18:55:06', '2026-06-01 15:52:30'),
(9, 'Fajar Perdana', 'fajar@gmail.com', '$2y$12$unyuucHZoDAm8IbXVfhsi.l.ZmF6/20nPcoZtbABiyPVaF149ojjm', '+62 812 2144 7689', 'Cangkuang', 'user', 'profile_9_1780372082.jpg', 'ktp_9_1780372040.png', 'sim_9_1780372040.png', '2026-06-02 10:45:00', '2026-06-02 10:48:02'),
(10, 'Fara', 'fara@gmai.com', '$2y$12$jbdNe84fKRBHXZ/NTHYDmuRbP7Ei2C7U5maco2GgwvwkNvPNDKqJC', '+62 812 22398921', NULL, 'user', NULL, NULL, NULL, '2026-06-03 14:56:16', '2026-06-04 11:04:15'),
(12, 'Jafarian', 'jafar@gmail.com', '$2y$12$8U1pA/W5GACyyilISvmzi.rfFJdRhy/8LPJiiFFTYisLr2X9I30zS', '+6281221568765', '', 'user', 'profile_12_1780552048.jpeg', NULL, NULL, '2026-06-04 11:05:14', '2026-06-04 14:41:02'),
(13, 'Fatur', 'fatur@gmail.com', '$2y$12$0K4D/Bbg5kxW/QDdcreFr.SdnJDRNGvOZKnUWjp6oVZbBqeMA00mq', '+6281221445845', 'TAI', 'user', NULL, NULL, NULL, '2026-06-04 15:24:23', '2026-06-05 01:36:55'),
(14, 'admin', 'admin@gmail.com', '$2y$12$s.jmDo2RyUQeUEjExu/K2OEKSUFsaCKZZhEMtBXcVgWRf5EicKgvm', '+62 812 2144 7689', NULL, 'admin', NULL, NULL, NULL, '2026-06-04 17:19:12', '2026-06-04 17:19:12');

-- --------------------------------------------------------

--
-- Table structure for table `vehicle_highlights`
--

CREATE TABLE `vehicle_highlights` (
  `id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `drivetrain` enum('AWD','FWD','RWD') NOT NULL DEFAULT 'FWD',
  `body_style` enum('Sedan','SUV','Sports') NOT NULL DEFAULT 'Sedan',
  `engine` varchar(100) DEFAULT NULL,
  `transmission` enum('Manual','Automatic') NOT NULL DEFAULT 'Automatic',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `vehicle_highlights`
--

INSERT INTO `vehicle_highlights` (`id`, `car_id`, `drivetrain`, `body_style`, `engine`, `transmission`, `created_at`, `updated_at`) VALUES
(1, 4, 'FWD', 'Sports', 'naturally aspirated 4.0L flat-six in the 911 GT3', 'Manual', '2026-06-04 16:55:19', '2026-06-04 16:55:40');

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_car_ratings`
-- (See below for the actual view)
--
CREATE TABLE `v_car_ratings` (
`id` int(11)
,`car_name` varchar(181)
,`photo` varchar(255)
,`price_per_day` decimal(12,2)
,`status` enum('available','booked','maintenance')
,`total_reviews` bigint(21)
,`avg_rating` decimal(5,1)
);

-- --------------------------------------------------------

--
-- Structure for view `v_car_ratings`
--
DROP TABLE IF EXISTS `v_car_ratings`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_car_ratings`  AS SELECT `c`.`id` AS `id`, concat(`c`.`brand`,' ',`c`.`model`) AS `car_name`, `c`.`photo` AS `photo`, `c`.`price_per_day` AS `price_per_day`, `c`.`status` AS `status`, count(`r`.`id`) AS `total_reviews`, round(avg(`r`.`rating`),1) AS `avg_rating` FROM (`cars` `c` left join `reviews` `r` on(`r`.`car_id` = `c`.`id`)) GROUP BY `c`.`id` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_bookings_user` (`user_id`),
  ADD KEY `fk_bookings_car` (`car_id`);

--
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `license_plate` (`license_plate`),
  ADD KEY `idx_cars_status` (`status`),
  ADD KEY `idx_cars_brand_model` (`brand`,`model`);

--
-- Indexes for table `returns`
--
ALTER TABLE `returns`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_returns_booking` (`booking_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_id` (`booking_id`),
  ADD KEY `idx_reviews_car` (`car_id`),
  ADD KEY `idx_reviews_user` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_users_email` (`email`),
  ADD KEY `idx_users_role` (`role`);

--
-- Indexes for table `vehicle_highlights`
--
ALTER TABLE `vehicle_highlights`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_vehicle_highlights_car` (`car_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `returns`
--
ALTER TABLE `returns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `vehicle_highlights`
--
ALTER TABLE `vehicle_highlights`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `fk_bookings_car` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_bookings_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `returns`
--
ALTER TABLE `returns`
  ADD CONSTRAINT `fk_returns_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `fk_reviews_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reviews_car` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_reviews_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `vehicle_highlights`
--
ALTER TABLE `vehicle_highlights`
  ADD CONSTRAINT `fk_vehicle_highlights_car` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
