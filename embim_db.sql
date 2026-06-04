-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 04, 2026 at 12:40 PM
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
  `status` enum('pending','confirmed','ongoing','completed','cancelled') NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL COMMENT 'Catatan tambahan dari penyewa',
  `identity_photo` varchar(255) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `car_id`, `start_date`, `end_date`, `total_days`, `total_price`, `status`, `notes`, `identity_photo`, `created_at`, `updated_at`) VALUES
(19, 13, 4, '2026-06-05', '2026-06-07', 2, 11200000.00, 'completed', 'WAKDAKWOD', 'selfie_13_1780562636.png', '2026-06-04 15:43:56', '2026-06-04 15:56:29');

-- --------------------------------------------------------

--
-- Table structure for table `cars`
--

CREATE TABLE `cars` (
  `id` int(11) NOT NULL,
  `brand` varchar(80) NOT NULL,
  `model` varchar(100) NOT NULL,
  `year` year(4) NOT NULL,
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

INSERT INTO `cars` (`id`, `brand`, `model`, `year`, `license_plate`, `price_per_day`, `status`, `transmission`, `fuel_type`, `seats`, `description`, `photo`, `created_at`, `updated_at`) VALUES
(1, 'Nissan', 'Nissan GTR R34', '2024', 'D 1234 ABM', 3500000.00, 'available', 'automatic', 'electric', 5, 'Sedan listrik ultra-premium dengan akselerasi 0-100 km/h dalam 2,1 detik. Dilengkapi layar sentuh 17 inci, Autopilot, dan interior mewah vegan leather.', 'nissan_gtr_r34.jpg', '2026-05-25 18:55:06', '2026-06-04 15:43:08'),
(2, 'Mercedes-Benz', 'S-Class S500 4MATIC', '2023', 'D 5678 EMB', 4200000.00, 'available', 'automatic', 'gasoline', 5, 'Sedan flagship Mercedes dengan suspensi air AIRMATIC, ambient lighting 64 warna, sistem audio Burmester 4D, dan fitur MBUX Hyperscreen.', 'Mercedes-Benz S-Class_S500_4MATIC.jpeg', '2026-05-25 18:55:06', '2026-06-04 14:46:59'),
(3, 'BMW', 'M4 Competition', '2023', 'D 9101 IME', 3800000.00, 'available', 'manual', 'gasoline', 4, 'Sports coupe bertenaga 510 hp dengan M xDrive AWD, rem karbon-keramik opsional, dan teknologi M Servotronic steering untuk pengalaman berkendara kelas dunia.', 'bmw-m4.jpg', '2026-05-25 18:55:06', '2026-06-04 14:47:04'),
(4, 'Porsche', '911 Carrera S', '2024', 'D 1121 BIM', 5600000.00, 'booked', 'manual', 'gasoline', 4, 'Ikon otomotif legendaris dengan mesin flat-six 450 hp, PDK 8-speed, PASM active suspension, dan desain timeless yang diakui dunia.', 'porsche_carreraS.jpg', '2026-05-25 18:55:06', '2026-06-04 15:43:56');

-- --------------------------------------------------------

--
-- Table structure for table `fines`
--

CREATE TABLE `fines` (
  `id` int(11) NOT NULL,
  `return_id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL,
  `late_days` smallint(6) NOT NULL DEFAULT 0,
  `fine_per_day` decimal(12,2) NOT NULL DEFAULT 50000.00,
  `fine_amount` decimal(14,2) NOT NULL DEFAULT 0.00,
  `status` enum('unpaid','paid') NOT NULL DEFAULT 'unpaid',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) NOT NULL COMMENT '1 booking = 1 payment record',
  `payment_method` enum('transfer_bank_bri','transfer_bank_mandiri','tunai','shopeepay','dana','ovo','gopay','transfer_bank_bni','transfer_bank_bca','transfer_bank_seabank') NOT NULL,
  `amount` decimal(14,2) NOT NULL,
  `status` enum('unpaid','paid','refunded') NOT NULL DEFAULT 'unpaid',
  `payment_proof` varchar(255) DEFAULT NULL COMMENT 'Nama file bukti pembayaran',
  `paid_at` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `booking_id`, `payment_method`, `amount`, `status`, `payment_proof`, `paid_at`, `created_at`, `updated_at`) VALUES
(19, 19, 'gopay', 11200000.00, 'paid', 'proof_13_1780562856.png', '2026-06-04 15:47:36', '2026-06-04 15:43:56', '2026-06-04 15:47:36');

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
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(5, 13, 4, 19, 3, 'W', '2026-06-04 15:57:14');

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
(13, 'Fatur', 'fatur@gmail.com', '$2y$12$0K4D/Bbg5kxW/QDdcreFr.SdnJDRNGvOZKnUWjp6oVZbBqeMA00mq', '+6281221445845', NULL, 'user', NULL, NULL, NULL, '2026-06-04 15:24:23', '2026-06-04 15:24:23'),
(14, 'admin', 'admin@gmail.com', '$2y$12$s.jmDo2RyUQeUEjExu/K2OEKSUFsaCKZZhEMtBXcVgWRf5EicKgvm', '+62 812 2144 7689', NULL, 'admin', NULL, NULL, NULL, '2026-06-04 17:19:12', '2026-06-04 17:19:12');

-- --------------------------------------------------------

--
-- Stand-in structure for view `v_booking_details`
-- (See below for the actual view)
--
CREATE TABLE `v_booking_details` (
`booking_id` int(11)
,`start_date` date
,`end_date` date
,`total_days` smallint(6)
,`total_price` decimal(14,2)
,`booking_status` enum('pending','confirmed','ongoing','completed','cancelled')
,`booked_at` datetime
,`user_id` int(11)
,`full_name` varchar(100)
,`email` varchar(150)
,`phone` varchar(20)
,`car_id` int(11)
,`car_name` varchar(181)
,`year` year(4)
,`license_plate` varchar(20)
,`price_per_day` decimal(12,2)
,`car_photo` varchar(255)
,`payment_method` enum('transfer_bank_bri','transfer_bank_mandiri','tunai','shopeepay','dana','ovo','gopay','transfer_bank_bni','transfer_bank_bca','transfer_bank_seabank')
,`payment_amount` decimal(14,2)
,`payment_status` enum('unpaid','paid','refunded')
,`paid_at` datetime
);

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
-- Structure for view `v_booking_details`
--
DROP TABLE IF EXISTS `v_booking_details`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `v_booking_details`  AS SELECT `b`.`id` AS `booking_id`, `b`.`start_date` AS `start_date`, `b`.`end_date` AS `end_date`, `b`.`total_days` AS `total_days`, `b`.`total_price` AS `total_price`, `b`.`status` AS `booking_status`, `b`.`created_at` AS `booked_at`, `u`.`id` AS `user_id`, `u`.`full_name` AS `full_name`, `u`.`email` AS `email`, `u`.`phone` AS `phone`, `c`.`id` AS `car_id`, concat(`c`.`brand`,' ',`c`.`model`) AS `car_name`, `c`.`year` AS `year`, `c`.`license_plate` AS `license_plate`, `c`.`price_per_day` AS `price_per_day`, `c`.`photo` AS `car_photo`, `p`.`payment_method` AS `payment_method`, `p`.`amount` AS `payment_amount`, `p`.`status` AS `payment_status`, `p`.`paid_at` AS `paid_at` FROM (((`bookings` `b` join `users` `u` on(`b`.`user_id` = `u`.`id`)) join `cars` `c` on(`b`.`car_id` = `c`.`id`)) left join `payments` `p` on(`p`.`booking_id` = `b`.`id`)) ;

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
  ADD KEY `idx_bookings_user` (`user_id`),
  ADD KEY `idx_bookings_car` (`car_id`),
  ADD KEY `idx_bookings_status` (`status`),
  ADD KEY `idx_bookings_dates` (`start_date`,`end_date`);

--
-- Indexes for table `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `license_plate` (`license_plate`),
  ADD KEY `idx_cars_status` (`status`),
  ADD KEY `idx_cars_brand_model` (`brand`,`model`);

--
-- Indexes for table `fines`
--
ALTER TABLE `fines`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uk_fines_return` (`return_id`),
  ADD KEY `fk_fines_booking` (`booking_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `booking_id` (`booking_id`),
  ADD KEY `idx_payments_status` (`status`);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `fines`
--
ALTER TABLE `fines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `returns`
--
ALTER TABLE `returns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `fk_bookings_car` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_bookings_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `fines`
--
ALTER TABLE `fines`
  ADD CONSTRAINT `fk_fines_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_fines_return` FOREIGN KEY (`return_id`) REFERENCES `returns` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `fk_payments_booking` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
