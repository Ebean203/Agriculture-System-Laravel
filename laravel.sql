-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 04, 2025 at 01:25 AM
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
-- Database: `laravel`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `log_id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `action_type` enum('login','farmer','rsbsa','yield','commodity','input','farmer_registration') NOT NULL,
  `details` text NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `barangays`
--

CREATE TABLE `barangays` (
  `barangay_id` int(11) NOT NULL,
  `barangay_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barangays`
--

INSERT INTO `barangays` (`barangay_id`, `barangay_name`) VALUES
(1, 'Banglay'),
(2, 'Dampil'),
(3, 'Gaston'),
(4, 'Kabulawan'),
(5, 'Kauswagan'),
(6, 'Lumbo'),
(7, 'Manaol'),
(8, 'Poblacion'),
(9, 'Tabok'),
(10, 'Umagos');

-- --------------------------------------------------------

--
-- Table structure for table `commodities`
--

CREATE TABLE `commodities` (
  `commodity_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `commodity_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `commodities`
--

INSERT INTO `commodities` (`commodity_id`, `category_id`, `commodity_name`) VALUES
(1, 1, 'Rice'),
(2, 1, 'Corn'),
(3, 2, 'Lanzones'),
(4, 2, 'Coconut'),
(5, 2, 'Coffee'),
(6, 2, 'Cacao'),
(7, 2, 'Mango'),
(8, 2, 'Rambutan'),
(9, 2, 'Ampalaya'),
(10, 2, 'String Beans'),
(11, 2, 'Eggplant'),
(12, 2, 'Squash'),
(13, 3, 'Cattle'),
(14, 3, 'Carabao'),
(15, 3, 'Swine'),
(16, 3, 'Rabbit'),
(17, 3, 'Goat'),
(18, 3, 'Horse'),
(19, 4, 'Chicken'),
(20, 4, 'Mallard Duck/Itik'),
(21, 4, 'Muscovy Duck/Pato'),
(22, 4, 'Turkey'),
(23, 4, 'Goose');

-- --------------------------------------------------------

--
-- Table structure for table `commodity_categories`
--

CREATE TABLE `commodity_categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `commodity_categories`
--

INSERT INTO `commodity_categories` (`category_id`, `category_name`) VALUES
(1, 'Agronomic Crops'),
(2, 'High Value Crops'),
(3, 'Livestocks'),
(4, 'Poultry');

-- --------------------------------------------------------

--
-- Table structure for table `farmers`
--

CREATE TABLE `farmers` (
  `farmer_id` varchar(100) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `suffix` varchar(10) NOT NULL,
  `birth_date` date NOT NULL,
  `gender` varchar(10) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `barangay_id` int(11) NOT NULL,
  `address_details` varchar(255) NOT NULL,
  `is_member_of_4ps` tinyint(1) NOT NULL DEFAULT 0,
  `is_ip` tinyint(1) NOT NULL DEFAULT 0,
  `other_income_source` text NOT NULL,
  `land_area_hectares` decimal(10,2) DEFAULT NULL COMMENT 'Total land area owned by the farmer in hectares',
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `archived` tinyint(1) DEFAULT 0,
  `archive_reason` varchar(255) DEFAULT NULL,
  `is_rsbsa` tinyint(1) DEFAULT 0,
  `is_ncfrs` tinyint(1) DEFAULT 0,
  `is_boat` tinyint(1) DEFAULT 0,
  `is_fisherfolk` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `farmer_commodities`
--

CREATE TABLE `farmer_commodities` (
  `id` int(11) NOT NULL,
  `farmer_id` varchar(100) NOT NULL,
  `commodity_id` int(11) NOT NULL,
  `years_farming` int(11) DEFAULT NULL,
  `is_primary` tinyint(1) DEFAULT 1 COMMENT 'Indicates if this is the farmers primary commodity',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Junction table linking farmers to their commodities';

-- --------------------------------------------------------

--
-- Table structure for table `farmer_photos`
--

CREATE TABLE `farmer_photos` (
  `photo_id` int(11) NOT NULL,
  `farmer_id` varchar(100) NOT NULL,
  `file_path` varchar(500) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='Simple table storing farmer photo file paths';

-- --------------------------------------------------------

--
-- Table structure for table `generated_reports`
--

CREATE TABLE `generated_reports` (
  `report_id` int(11) NOT NULL,
  `report_type` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `timestamp` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `household_info`
--

CREATE TABLE `household_info` (
  `id` int(11) NOT NULL,
  `farmer_id` varchar(100) NOT NULL,
  `civil_status` varchar(50) NOT NULL,
  `spouse_name` varchar(255) NOT NULL,
  `household_size` int(11) NOT NULL,
  `education_level` varchar(255) NOT NULL,
  `occupation` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `input_categories`
--

CREATE TABLE `input_categories` (
  `input_id` int(11) NOT NULL,
  `input_name` varchar(100) NOT NULL,
  `unit` varchar(50) NOT NULL,
  `requires_visitation` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Indicates if this input requires a follow-up visitation.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mao_activities`
--

CREATE TABLE `mao_activities` (
  `activity_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `activity_type` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `activity_date` date NOT NULL,
  `location` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mao_distribution_log`
--

CREATE TABLE `mao_distribution_log` (
  `log_id` int(11) NOT NULL,
  `farmer_id` varchar(100) NOT NULL,
  `input_id` int(11) NOT NULL,
  `quantity_distributed` int(11) NOT NULL,
  `date_given` date NOT NULL,
  `visitation_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mao_inventory`
--

CREATE TABLE `mao_inventory` (
  `inventory_id` int(11) NOT NULL,
  `input_id` int(11) NOT NULL,
  `quantity_on_hand` int(11) NOT NULL,
  `last_updated` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `mao_staff`
--

CREATE TABLE `mao_staff` (
  `staff_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `position` varchar(100) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mao_staff`
--

INSERT INTO `mao_staff` (`staff_id`, `first_name`, `last_name`, `position`, `contact_number`, `username`, `password`, `role_id`) VALUES
(1, 'System', 'Administrator', 'Administrator', 'N/A', 'admin', '$2y$10$JJJIqKoQtcfBLgGxpeJvwep3vSLyDCjIciBX6O/dsxMpzisW2Itv6', 1);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role`) VALUES
(1, 'admin'),
(2, 'staff');

-- --------------------------------------------------------

--
-- Table structure for table `yield_monitoring`
--

CREATE TABLE `yield_monitoring` (
  `yield_id` int(11) NOT NULL,
  `farmer_id` varchar(100) NOT NULL,
  `commodity_id` int(11) NOT NULL,
  `season` varchar(50) NOT NULL,
  `yield_amount` decimal(10,2) NOT NULL,
  `record_date` datetime NOT NULL DEFAULT current_timestamp(),
  `recorded_by_staff_id` int(11) NOT NULL,
  `distributed_input` varchar(255) DEFAULT NULL,
  `visit_date` date DEFAULT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `quality_grade` varchar(50) DEFAULT NULL,
  `growth_stage` varchar(50) DEFAULT NULL,
  `field_conditions` varchar(255) DEFAULT NULL,
  `visit_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`staff_id`);

--
-- Indexes for table `barangays`
--
ALTER TABLE `barangays`
  ADD PRIMARY KEY (`barangay_id`),
  ADD UNIQUE KEY `uq_barangays_name` (`barangay_name`);

--
-- Indexes for table `commodities`
--
ALTER TABLE `commodities`
  ADD PRIMARY KEY (`commodity_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `commodity_categories`
--
ALTER TABLE `commodity_categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `farmers`
--
ALTER TABLE `farmers`
  ADD PRIMARY KEY (`farmer_id`),
  ADD KEY `idx_farmers_barangay_id` (`barangay_id`);

--
-- Indexes for table `farmer_commodities`
--
ALTER TABLE `farmer_commodities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_farmer_commodity` (`farmer_id`,`commodity_id`),
  ADD KEY `idx_farmer_id` (`farmer_id`),
  ADD KEY `idx_commodity_id` (`commodity_id`),
  ADD KEY `idx_primary_commodity` (`farmer_id`,`is_primary`);

--
-- Indexes for table `farmer_photos`
--
ALTER TABLE `farmer_photos`
  ADD PRIMARY KEY (`photo_id`),
  ADD KEY `idx_farmer_photos_farmer` (`farmer_id`);

--
-- Indexes for table `generated_reports`
--
ALTER TABLE `generated_reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `household_info`
--
ALTER TABLE `household_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_household_farmer` (`farmer_id`);

--
-- Indexes for table `input_categories`
--
ALTER TABLE `input_categories`
  ADD PRIMARY KEY (`input_id`),
  ADD UNIQUE KEY `uq_input_name` (`input_name`);

--
-- Indexes for table `mao_activities`
--
ALTER TABLE `mao_activities`
  ADD PRIMARY KEY (`activity_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `mao_distribution_log`
--
ALTER TABLE `mao_distribution_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `fk_distribution_farmer` (`farmer_id`),
  ADD KEY `fk_distribution_input` (`input_id`),
  ADD KEY `idx_visitation_date` (`visitation_date`);

--
-- Indexes for table `mao_inventory`
--
ALTER TABLE `mao_inventory`
  ADD PRIMARY KEY (`inventory_id`),
  ADD UNIQUE KEY `uq_inventory_input` (`input_id`);

--
-- Indexes for table `mao_staff`
--
ALTER TABLE `mao_staff`
  ADD PRIMARY KEY (`staff_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_maostaff_role` (`role_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`);

--
-- Indexes for table `yield_monitoring`
--
ALTER TABLE `yield_monitoring`
  ADD PRIMARY KEY (`yield_id`),
  ADD KEY `fk_yield_farmer` (`farmer_id`),
  ADD KEY `fk_yield_commodity` (`commodity_id`),
  ADD KEY `fk_yield_staff` (`recorded_by_staff_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `barangays`
--
ALTER TABLE `barangays`
  MODIFY `barangay_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `commodities`
--
ALTER TABLE `commodities`
  MODIFY `commodity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `commodity_categories`
--
ALTER TABLE `commodity_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `farmer_commodities`
--
ALTER TABLE `farmer_commodities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `farmer_photos`
--
ALTER TABLE `farmer_photos`
  MODIFY `photo_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `generated_reports`
--
ALTER TABLE `generated_reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `household_info`
--
ALTER TABLE `household_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `input_categories`
--
ALTER TABLE `input_categories`
  MODIFY `input_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mao_activities`
--
ALTER TABLE `mao_activities`
  MODIFY `activity_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mao_distribution_log`
--
ALTER TABLE `mao_distribution_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mao_inventory`
--
ALTER TABLE `mao_inventory`
  MODIFY `inventory_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mao_staff`
--
ALTER TABLE `mao_staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `yield_monitoring`
--
ALTER TABLE `yield_monitoring`
  MODIFY `yield_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD CONSTRAINT `fk_activitylogs_staff` FOREIGN KEY (`staff_id`) REFERENCES `mao_staff` (`staff_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `commodities`
--
ALTER TABLE `commodities`
  ADD CONSTRAINT `fk_commodities_category` FOREIGN KEY (`category_id`) REFERENCES `commodity_categories` (`category_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `farmers`
--
ALTER TABLE `farmers`
  ADD CONSTRAINT `fk_farmers_barangay` FOREIGN KEY (`barangay_id`) REFERENCES `barangays` (`barangay_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `farmer_commodities`
--
ALTER TABLE `farmer_commodities`
  ADD CONSTRAINT `fk_farmer_commodities_commodity` FOREIGN KEY (`commodity_id`) REFERENCES `commodities` (`commodity_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_farmer_commodities_farmer` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`farmer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `farmer_photos`
--
ALTER TABLE `farmer_photos`
  ADD CONSTRAINT `fk_farmer_photos_farmer` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`farmer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `generated_reports`
--
ALTER TABLE `generated_reports`
  ADD CONSTRAINT `generated_reports_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `mao_staff` (`staff_id`);

--
-- Constraints for table `household_info`
--
ALTER TABLE `household_info`
  ADD CONSTRAINT `fk_household_farmer` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`farmer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mao_activities`
--
ALTER TABLE `mao_activities`
  ADD CONSTRAINT `mao_activities_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `mao_staff` (`staff_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mao_distribution_log`
--
ALTER TABLE `mao_distribution_log`
  ADD CONSTRAINT `fk_distribution_farmer` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`farmer_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_distribution_input` FOREIGN KEY (`input_id`) REFERENCES `input_categories` (`input_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mao_inventory`
--
ALTER TABLE `mao_inventory`
  ADD CONSTRAINT `fk_inventory_input` FOREIGN KEY (`input_id`) REFERENCES `input_categories` (`input_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `mao_staff`
--
ALTER TABLE `mao_staff`
  ADD CONSTRAINT `fk_maostaff_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `yield_monitoring`
--
ALTER TABLE `yield_monitoring`
  ADD CONSTRAINT `fk_yield_commodity` FOREIGN KEY (`commodity_id`) REFERENCES `commodities` (`commodity_id`),
  ADD CONSTRAINT `fk_yield_farmer` FOREIGN KEY (`farmer_id`) REFERENCES `farmers` (`farmer_id`),
  ADD CONSTRAINT `fk_yield_staff` FOREIGN KEY (`recorded_by_staff_id`) REFERENCES `mao_staff` (`staff_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
