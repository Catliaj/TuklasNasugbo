-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 22, 2025 at 06:48 AM
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
-- Database: `tuklas_nasugbu`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `log_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `activity_type` varchar(100) NOT NULL,
  `entity_type` varchar(100) NOT NULL,
  `entity_id` int(11) DEFAULT NULL,
  `description` text NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `user_agent` text NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) UNSIGNED NOT NULL,
  `spot_id` int(11) UNSIGNED DEFAULT NULL,
  `customer_id` int(11) UNSIGNED DEFAULT NULL,
  `booking_date` datetime DEFAULT NULL,
  `visit_date` date DEFAULT NULL,
  `visit_time` time DEFAULT NULL,
  `num_adults` int(11) DEFAULT NULL,
  `num_children` int(11) DEFAULT NULL,
  `num_seniors` int(11) DEFAULT NULL,
  `total_guests` int(11) DEFAULT NULL,
  `price_per_person` decimal(10,2) DEFAULT NULL,
  `subtotal` decimal(15,2) DEFAULT NULL,
  `discount_amount` decimal(15,2) DEFAULT NULL,
  `tax_amount` decimal(15,2) DEFAULT NULL,
  `total_price` decimal(15,2) DEFAULT NULL,
  `booking_status` enum('Pending','Confirmed','Cancelled','Completed','Checked-out','Checked-in') DEFAULT NULL,
  `payment_status` enum('Unpaid','Paid','Refunded') DEFAULT NULL,
  `special_requests` text DEFAULT NULL,
  `cancellation_reason` text DEFAULT NULL,
  `internal_notes` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `confirmed_at` datetime DEFAULT NULL,
  `cancelled_at` datetime DEFAULT NULL,
  `completed_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `spot_id`, `customer_id`, `booking_date`, `visit_date`, `visit_time`, `num_adults`, `num_children`, `num_seniors`, `total_guests`, `price_per_person`, `subtotal`, `discount_amount`, `tax_amount`, `total_price`, `booking_status`, `payment_status`, `special_requests`, `cancellation_reason`, `internal_notes`, `created_at`, `updated_at`, `confirmed_at`, `cancelled_at`, `completed_at`) VALUES
(1, 1, 1, '2025-11-20 08:53:46', '2024-12-15', '10:00:00', 2, 1, 0, 3, 500.00, 1500.00, 0.00, 150.00, 1650.00, 'Confirmed', 'Paid', 'Need wheelchair access', NULL, NULL, '2025-11-20 08:53:46', '2025-11-20 08:53:46', NULL, NULL, NULL),
(2, 1, 1, '2025-11-20 08:53:46', '2025-09-16', '14:00:00', 1, 0, 1, 2, 500.00, 1000.00, 50.00, 95.00, 1045.00, 'Confirmed', 'Unpaid', '', NULL, NULL, '2025-11-20 08:53:46', NULL, NULL, NULL, NULL),
(3, 2, 2, '2025-11-20 08:53:46', '2024-10-05', '11:30:00', 2, 0, 2, 4, 400.00, 1600.00, 0.00, 160.00, 1760.00, 'Cancelled', 'Refunded', '', NULL, NULL, '2025-11-20 08:53:46', NULL, NULL, NULL, NULL),
(4, 3, 3, '2025-11-20 08:53:46', '2024-12-01', '13:00:00', 1, 1, 0, 2, 600.00, 1200.00, 0.00, 120.00, 1320.00, 'Completed', 'Paid', 'Near the stage', NULL, NULL, '2025-11-20 08:53:46', NULL, NULL, NULL, NULL),
(5, 4, 4, '2025-11-20 08:53:46', '2025-02-14', '12:00:00', 2, 2, 1, 5, 550.00, 2750.00, 200.00, 255.00, 2805.00, 'Completed', 'Unpaid', 'High chair needed', NULL, NULL, '2025-11-20 08:53:46', NULL, NULL, NULL, NULL),
(6, 5, 5, '2025-11-20 08:53:46', '2025-04-18', '14:30:00', 2, 0, 2, 4, 400.00, 1600.00, 80.00, 152.00, 1672.00, 'Confirmed', 'Paid', 'Quiet area', NULL, NULL, '2025-11-20 08:53:46', NULL, NULL, NULL, NULL),
(7, 6, 6, '2025-11-20 08:53:46', '2025-06-30', '11:00:00', 2, 2, 1, 5, 550.00, 2750.00, 150.00, 260.00, 2860.00, 'Confirmed', 'Unpaid', 'Allergic to nuts', NULL, NULL, '2025-11-20 08:53:46', NULL, NULL, NULL, NULL),
(8, 7, 7, '2025-11-20 08:53:46', '2025-08-12', '15:00:00', 1, 0, 1, 2, 600.00, 1200.00, 0.00, 120.00, 1320.00, 'Confirmed', 'Paid', '', NULL, NULL, '2025-11-20 08:53:46', NULL, NULL, NULL, NULL),
(9, 8, 8, '2025-11-20 08:53:46', '2024-11-20', '09:30:00', 3, 1, 0, 4, 450.00, 1800.00, 100.00, 170.00, 1870.00, 'Confirmed', 'Paid', 'Vegetarian meals', NULL, NULL, '2025-11-20 08:53:46', NULL, NULL, NULL, NULL),
(10, 9, 9, '2025-11-20 08:53:46', '2025-03-22', '13:30:00', 2, 2, 1, 5, 500.00, 2500.00, 200.00, 230.00, 2530.00, 'Confirmed', 'Unpaid', '', NULL, NULL, '2025-11-20 08:53:46', NULL, NULL, NULL, NULL),
(11, 10, 10, '2025-11-20 08:53:46', '2024-12-25', '12:30:00', 4, 0, 2, 6, 600.00, 3600.00, 300.00, 330.00, 3630.00, 'Confirmed', 'Paid', 'Window seats', NULL, NULL, '2025-11-20 08:53:46', NULL, NULL, NULL, NULL),
(12, 12, 13, '2025-11-20 00:00:00', '2025-11-20', '17:07:00', 1, 2, 0, 3, 333.33, 1000.00, NULL, NULL, 1000.00, 'Checked-in', 'Unpaid', NULL, NULL, NULL, '2025-11-20 09:07:31', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `businesses`
--

CREATE TABLE `businesses` (
  `business_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `business_name` varchar(100) NOT NULL,
  `contact_email` varchar(100) DEFAULT NULL,
  `contact_phone` varchar(15) NOT NULL,
  `business_address` text NOT NULL,
  `logo_url` text DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  `rejection_reason` text DEFAULT NULL,
  `gov_id_type` varchar(50) DEFAULT NULL,
  `gov_id_number` varchar(100) DEFAULT NULL,
  `gov_id_image` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `businesses`
--

INSERT INTO `businesses` (`business_id`, `user_id`, `business_name`, `contact_email`, `contact_phone`, `business_address`, `logo_url`, `status`, `rejection_reason`, `gov_id_type`, `gov_id_number`, `gov_id_image`, `created_at`, `updated_at`) VALUES
(1, 2, 'Sunset Tours', 'businessEmail@gmail.com', '09171234567', '456 Beach Rd, Seaside City', 'https://example.com/logos/sunset_tours.png', 'Approved', NULL, NULL, NULL, NULL, '2025-11-20 08:53:46', '2025-11-20 09:03:50'),
(2, 3, 'Sunset Cove Resort', 'sunsetcove@gmail.com', '09456374891', 'Calayo Beach, Nasugbu, Batangas', 'https://example.com/logos/sunset_cove.png', 'Rejected', 'sample', NULL, NULL, NULL, '2025-11-20 08:53:46', '2025-11-20 09:04:02'),
(3, 4, 'Ocean Breeze Inn', 'oceanbreeze@gmail.com', '09356479201', 'Wawa, Nasugbu, Batangas', 'https://example.com/logos/ocean_breeze.png', 'Approved', NULL, NULL, NULL, NULL, '2025-11-20 08:53:46', '2025-11-22 04:06:16'),
(4, 5, 'Highlands Viewpoint Café', 'highlandscafe@gmail.com', '09171230034', 'Tagaytay-Nasugbu Highway, Nasugbu, Batangas', 'https://example.com/logos/highlands_viewpoint.png', 'Pending', NULL, NULL, NULL, NULL, '2025-11-20 08:53:46', '2025-11-20 08:53:46'),
(5, 6, 'Fortune Island Adventures', 'fortuneisland@gmail.com', '09659372931', 'Fortune Island, Nasugbu, Batangas', 'https://example.com/logos/fortune_island.png', 'Pending', NULL, NULL, NULL, NULL, '2025-11-20 08:53:46', '2025-11-20 08:53:46'),
(6, 7, 'Punta Fuego Getaway', 'puntafuego@gmail.com', '09376451982', 'Punta Fuego, Nasugbu, Batangas', 'https://example.com/logos/punta_fuego.png', 'Pending', NULL, NULL, NULL, NULL, '2025-11-20 08:53:46', '2025-11-20 08:53:46'),
(7, 8, 'Hamilo Coast Staycation', 'hamilocoast@gmail.com', '09456783245', 'Hamilo Coast, Pico de Loro Cove, Nasugbu, Batangas', 'https://example.com/logos/hamilo_coast.png', 'Pending', NULL, NULL, NULL, NULL, '2025-11-20 08:53:46', '2025-11-20 08:53:46'),
(8, 9, 'Mountain Peak Eco Park', 'mountainpeak@gmail.com', '09171230007', 'Barangay Banilad, Nasugbu, Batangas', 'https://example.com/logos/mountain_peak.png', 'Pending', NULL, NULL, NULL, NULL, '2025-11-20 08:53:46', '2025-11-20 08:53:46'),
(9, 10, 'Nasugbu Dive Center', 'nasugbudive@gmail.com', '096574923140', 'Apacible Blvd, Nasugbu, Batangas', 'https://example.com/logos/nasugbu_dive.png', 'Pending', NULL, NULL, NULL, NULL, '2025-11-20 08:53:46', '2025-11-20 08:53:46');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `phone` varchar(15) NOT NULL,
  `address` text NOT NULL,
  `date_of_birth` date DEFAULT NULL,
  `emergency_contact` varchar(100) NOT NULL,
  `emergency_phone` varchar(15) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`customer_id`, `user_id`, `type`, `phone`, `address`, `date_of_birth`, `emergency_contact`, `emergency_phone`, `created_at`, `updated_at`) VALUES
(1, 13, 'regular', '1234567890', '123 Main St, Cityville', '1990-01-01', 'Juan Dela Cruz', '0987654321', '2025-11-20 08:53:46', '2025-11-20 08:53:46'),
(2, 14, 'regular', '2345678901', '456 Oak St, Townsville', '1985-05-15', 'Maria Santos', '0876543210', '2025-11-20 08:53:46', '2025-11-20 08:53:46'),
(3, 15, 'regular', '3456789012', '789 Pine St, Villageville', '1992-09-30', 'Pedro Reyes', '0765432109', '2025-11-20 08:53:46', '2025-11-20 08:53:46'),
(4, 16, 'regular', '4567890123', '101 Maple St, Hamletville', '1988-12-20', 'Ana Lopez', '0654321098', '2025-11-20 08:53:46', '2025-11-20 08:53:46'),
(5, 17, 'regular', '5678901234', '202 Birch St, Boroughville', '1995-07-25', 'Luis Garcia', '0543210987', '2025-11-20 08:53:46', '2025-11-20 08:53:46'),
(6, 18, 'regular', '6789012345', '303 Cedar St, Metroville', '1991-03-10', 'Carmen Diaz', '0432109876', '2025-11-20 08:53:46', '2025-11-20 08:53:46'),
(7, 19, 'regular', '7890123456', '404 Spruce St, Capitolville', '1987-11-05', 'Ramon Cruz', '0974836512', '2025-11-20 08:53:46', '2025-11-20 08:53:46'),
(8, 20, 'regular', '8901234567', '505 Walnut St, Urbantown', '1993-06-18', 'Isabel Fernandez', '0973546892', '2025-11-20 08:53:46', '2025-11-20 08:53:46'),
(9, 21, 'regular', '0982345768', '606 Chestnut St, Downtown', '1994-02-22', 'Victor Ramos', '09653478292', '2025-11-20 08:53:46', '2025-11-20 08:53:46'),
(10, 22, 'regular', '09432567894', '707 Poplar St, Suburbia', '1989-08-14', 'Gloria Mendoza', '09567483920', '2025-11-20 08:53:46', '2025-11-20 08:53:46'),
(11, 23, 'regular', '0912345678', '808 Ash St, Countryside', '1996-04-12', 'Felipe Navarro', '0987654320', '2025-11-20 08:53:46', '2025-11-20 08:53:46'),
(12, 24, 'regular', '0923456789', '909 Willow St, Lakeside', '1990-10-30', 'Sofia Castillo', '0976543210', '2025-11-20 08:53:46', '2025-11-20 08:53:46'),
(13, 25, 'regular', '0934567890', '1001 Cypress St, Riverside', '1986-09-09', 'Jorge Silva', '0965432109', '2025-11-20 08:53:46', '2025-11-20 08:53:46');

-- --------------------------------------------------------

--
-- Table structure for table `itinerary`
--

CREATE TABLE `itinerary` (
  `itinerary_id` int(11) UNSIGNED NOT NULL,
  `preference_id` int(11) UNSIGNED DEFAULT NULL,
  `spot_id` int(11) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `day` int(3) NOT NULL DEFAULT 1,
  `budget` decimal(15,2) DEFAULT NULL,
  `adults` int(3) NOT NULL DEFAULT 1,
  `children` int(3) NOT NULL DEFAULT 0,
  `seniors` int(3) NOT NULL DEFAULT 0,
  `trip_title` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int(11) NOT NULL,
  `batch` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(35, '2025-10-24-060916', 'App\\Database\\Migrations\\CreateUser', 'default', 'App', 1763628814, 1),
(36, '2025-10-24-060955', 'App\\Database\\Migrations\\CreateBusiness', 'default', 'App', 1763628814, 1),
(37, '2025-10-24-061013', 'App\\Database\\Migrations\\CreateTouristSpot', 'default', 'App', 1763628814, 1),
(38, '2025-10-24-061042', 'App\\Database\\Migrations\\CreateSpotGallery', 'default', 'App', 1763628814, 1),
(39, '2025-10-25-104532', 'App\\Database\\Migrations\\CreateSpotAvailability', 'default', 'App', 1763628814, 1),
(40, '2025-10-25-104710', 'App\\Database\\Migrations\\CreateRevenueAnalytics', 'default', 'App', 1763628814, 1),
(41, '2025-10-25-104829', 'App\\Database\\Migrations\\CreateActivityLog', 'default', 'App', 1763628814, 1),
(42, '2025-10-25-104952', 'App\\Database\\Migrations\\CreateCustomers', 'default', 'App', 1763628814, 1),
(43, '2025-10-25-105506', 'App\\Database\\Migrations\\CreateUserPreference', 'default', 'App', 1763628815, 1),
(44, '2025-10-25-110743', 'App\\Database\\Migrations\\CreateBooking', 'default', 'App', 1763628815, 1),
(45, '2025-10-25-110910', 'App\\Database\\Migrations\\CreateVisitorCheckin', 'default', 'App', 1763628815, 1),
(46, '2025-10-25-110942', 'App\\Database\\Migrations\\CreatePayment', 'default', 'App', 1763628815, 1),
(47, '2025-10-25-111011', 'App\\Database\\Migrations\\CreateFeedback', 'default', 'App', 1763628815, 1),
(48, '2025-10-31-062535', 'App\\Database\\Migrations\\Itinerary', 'default', 'App', 1763628815, 1),
(49, '2025-11-17-013516', 'App\\Database\\Migrations\\CreateUserVisitHistory', 'default', 'App', 1763628815, 1),
(50, '2025-11-18-162546', 'App\\Database\\Migrations\\SpotViewLogs', 'default', 'App', 1763628815, 1),
(51, '2025-11-18-164342', 'App\\Database\\Migrations\\CreateSpotFavByCustomer', 'default', 'App', 1763628815, 1);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) UNSIGNED NOT NULL,
  `booking_id` int(11) UNSIGNED NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_method` enum('Credit Card','Debit Card','PayPal','Bank Transfer','Cash') NOT NULL,
  `payment_date` datetime NOT NULL,
  `transaction_id` varchar(100) NOT NULL,
  `reference_number` varchar(100) NOT NULL,
  `status` enum('Pending','Completed','Failed','Refunded') NOT NULL DEFAULT 'Pending',
  `notes` text DEFAULT NULL,
  `processed_by` int(11) UNSIGNED NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `revenue_analytics`
--

CREATE TABLE `revenue_analytics` (
  `analytics_id` int(11) UNSIGNED NOT NULL,
  `business_id` int(11) UNSIGNED NOT NULL,
  `spot_id` int(11) UNSIGNED NOT NULL,
  `by_Date` date NOT NULL,
  `total_bookings` int(11) NOT NULL,
  `confirmed_bookings` int(11) NOT NULL,
  `cancelled_bookings` int(11) NOT NULL,
  `total_visitors` int(11) NOT NULL,
  `gross_revenue` decimal(15,2) NOT NULL,
  `discounts` decimal(15,2) NOT NULL,
  `refunds` decimal(15,2) NOT NULL,
  `net_revenue` decimal(15,2) NOT NULL,
  `avg_booking_value` decimal(10,2) NOT NULL,
  `avg_party_size` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `review_feedback`
--

CREATE TABLE `review_feedback` (
  `review_id` int(11) UNSIGNED NOT NULL,
  `booking_id` int(11) UNSIGNED NOT NULL,
  `spot_id` int(11) UNSIGNED NOT NULL,
  `customer_id` int(11) UNSIGNED NOT NULL,
  `business_id` int(11) UNSIGNED NOT NULL,
  `rating` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `comment` text NOT NULL,
  `cleanliness_rating` int(11) NOT NULL,
  `staff_rating` int(11) NOT NULL,
  `value_rating` int(11) NOT NULL,
  `location_rating` int(11) NOT NULL,
  `status` enum('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  `is_verified_visit` tinyint(1) NOT NULL DEFAULT 0,
  `owner_response` text DEFAULT NULL,
  `response_date` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `spot_availability`
--

CREATE TABLE `spot_availability` (
  `availability_id` int(11) UNSIGNED NOT NULL,
  `spot_id` int(11) UNSIGNED NOT NULL,
  `available_date` date NOT NULL,
  `total_capacity` int(11) NOT NULL,
  `booked_capacity` int(11) NOT NULL DEFAULT 0,
  `available_capacity` int(11) NOT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `reason_unavailable` varchar(255) DEFAULT NULL,
  `special_price` decimal(10,2) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `spot_fav_by_customer`
--

CREATE TABLE `spot_fav_by_customer` (
  `fav_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `spot_id` int(11) UNSIGNED NOT NULL,
  `favorited_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `spot_gallery`
--

CREATE TABLE `spot_gallery` (
  `image_id` int(11) UNSIGNED NOT NULL,
  `spot_id` int(11) UNSIGNED NOT NULL,
  `image` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `spot_gallery`
--

INSERT INTO `spot_gallery` (`image_id`, `spot_id`, `image`) VALUES
(1, 12, 0x313736333632393537335f34306132393732373738623836393162313431622e6a7067),
(2, 12, 0x313736333632393537335f61613337356238333237373263313135323430322e6a7067),
(3, 12, 0x313736333632393537335f32623863313535626634656263346335616232362e6a7067);

-- --------------------------------------------------------

--
-- Table structure for table `spot_view_logs`
--

CREATE TABLE `spot_view_logs` (
  `log_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `spot_id` int(11) UNSIGNED NOT NULL,
  `viewed_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `spot_view_logs`
--

INSERT INTO `spot_view_logs` (`log_id`, `user_id`, `spot_id`, `viewed_at`) VALUES
(1, 13, 12, '2025-11-20 09:07:04');

-- --------------------------------------------------------

--
-- Table structure for table `tourist_spots`
--

CREATE TABLE `tourist_spots` (
  `spot_id` int(11) UNSIGNED NOT NULL,
  `business_id` int(11) UNSIGNED NOT NULL,
  `spot_name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `category` enum('Historical','Cultural','Natural','Recreational','Religious','Adventure','Ecotourism','Urban') NOT NULL,
  `location` varchar(255) NOT NULL,
  `capacity` int(11) NOT NULL,
  `opening_time` time NOT NULL,
  `closing_time` time NOT NULL,
  `operating_days` varchar(100) NOT NULL,
  `status` enum('pending','approved','rejected','suspended') NOT NULL DEFAULT 'pending',
  `price_per_person` decimal(10,2) NOT NULL,
  `child_price` decimal(10,2) NOT NULL,
  `senior_price` decimal(10,2) NOT NULL,
  `group_discount_percent` decimal(5,2) NOT NULL,
  `primary_image` text DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `status_reason` text DEFAULT NULL,
  `suspension_reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tourist_spots`
--

INSERT INTO `tourist_spots` (`spot_id`, `business_id`, `spot_name`, `description`, `latitude`, `longitude`, `category`, `location`, `capacity`, `opening_time`, `closing_time`, `operating_days`, `status`, `price_per_person`, `child_price`, `senior_price`, `group_discount_percent`, `primary_image`, `created_at`, `updated_at`, `status_reason`, `suspension_reason`) VALUES
(1, 1, 'Ancient Ruins Park', 'Explore the remnants of an ancient civilization with guided tours and interactive exhibits.', 0.00000000, 0.00000000, 'Historical', '123 Heritage St, Nasugbo City', 200, '08:00:00', '18:00:00', 'Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday', 'pending', 300.00, 150.00, 200.00, 10.00, 'ancient_ruins.jpg', '2025-11-20 08:53:46', '2025-11-20 08:53:46', NULL, NULL),
(2, 1, 'Sunset Beach', 'Relax on the golden sands and enjoy breathtaking sunsets at our pristine beach.', 0.00000000, 0.00000000, '', '456 Ocean Ave, Nasugbo City', 500, '06:00:00', '20:00:00', 'Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday', 'approved', 0.00, 0.00, 0.00, 0.00, 'sunset_beach.jpg', '2025-11-20 08:53:46', '2025-11-20 08:53:46', NULL, NULL),
(3, 2, 'Mountain Adventure Park', 'Experience thrilling outdoor activities like zip-lining, rock climbing, and hiking trails.', 0.00000000, 0.00000000, 'Adventure', '789 Summit Rd, Nasugbo City', 150, '09:00:00', '17:00:00', 'Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday', 'approved', 500.00, 250.00, 300.00, 15.00, 'mountain_adventure.jpg', '2025-11-20 08:53:46', '2025-11-20 08:53:46', NULL, NULL),
(4, 3, 'Calayo Beach', 'A peaceful beach with soft sand, calm waves, and local fishing village charm — perfect for relaxation.', 0.00000000, 0.00000000, '', 'Calayo, Nasugbu, Batangas', 400, '06:00:00', '20:00:00', 'Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday', 'approved', 100.00, 70.00, 80.00, 10.00, 'calayo_beach.jpg', '2025-11-20 08:53:46', '2025-11-20 08:53:46', NULL, NULL),
(5, 4, 'Canyon Cove Beach Resort', 'A family-friendly resort with white sand beach, infinity pool, and various water sports.', 0.00000000, 0.00000000, '', 'Far East Road, Piloto Wawa, Nasugbu, Batangas', 800, '07:00:00', '22:00:00', 'Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday', 'approved', 500.00, 300.00, 400.00, 15.00, 'canyon_cove.jpg', '2025-11-20 08:53:46', '2025-11-20 08:53:46', NULL, NULL),
(6, 5, 'Tali Beach', 'A private seaside community famous for cliff diving, swimming, and relaxing views.', 0.00000000, 0.00000000, '', 'Tali Beach Subdivision, Nasugbu, Batangas', 250, '06:00:00', '19:00:00', 'Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday', 'approved', 300.00, 200.00, 250.00, 5.00, 'tali_beach.jpg', '2025-11-20 08:53:46', '2025-11-20 08:53:46', NULL, NULL),
(7, 6, 'Mount Talamitam', 'A beginner-friendly hiking trail with panoramic views and a peaceful summit area.', 0.00000000, 0.00000000, '', 'Sitio Bayabasan, Nasugbu, Batangas', 150, '05:00:00', '17:00:00', 'Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday', 'approved', 40.00, 20.00, 30.00, 5.00, 'mount_talamitam.jpg', '2025-11-20 08:53:46', '2025-11-20 08:53:46', NULL, NULL),
(8, 7, 'Club Punta Fuego', 'An exclusive resort offering world-class amenities, golf courses, and a private beach.', 0.00000000, 0.00000000, '', 'Balaytigue, Nasugbu, Batangas', 1000, '07:00:00', '22:00:00', 'Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday', 'approved', 1200.00, 800.00, 1000.00, 10.00, 'club_punta_fuego.jpg', '2025-11-20 08:53:46', '2025-11-20 08:53:46', NULL, NULL),
(9, 8, 'Kaybiang Tunnel', 'The longest underground tunnel in the Philippines, connecting Ternate, Cavite and Nasugbu — ideal for scenic drives and cycling.', 0.00000000, 0.00000000, '', 'Nasugbu-Ternate Highway, Nasugbu, Batangas', 1000, '00:00:00', '23:59:59', 'Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday', 'approved', 0.00, 0.00, 0.00, 0.00, 'kaybiang_tunnel.jpg', '2025-11-20 08:53:46', '2025-11-20 08:53:46', NULL, NULL),
(10, 9, 'Fortune Island', 'A stunning island featuring Greek-style ruins, white sand beaches, and turquoise waters ideal for snorkeling and diving.', 0.00000000, 0.00000000, '', 'Fortune Island, Nasugbu, Batangas', 300, '06:00:00', '18:00:00', 'Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday', 'approved', 350.00, 200.00, 250.00, 10.00, 'fortune_island.jpg', '2025-11-20 08:53:46', '2025-11-20 08:53:46', NULL, NULL),
(11, 9, 'Laiya Beach', 'A popular beach destination known for its white sand, clear waters, and vibrant nightlife.', 0.00000000, 0.00000000, '', 'Laiya, San Juan, Batangas', 600, '06:00:00', '20:00:00', 'Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday', 'approved', 400.00, 250.00, 300.00, 10.00, 'laiya_beach.jpg', '2025-11-20 08:53:46', '2025-11-20 08:53:46', NULL, NULL),
(12, 1, 'catBeach', 'sample lang po ito ', 14.07646380, 120.62404449, '', 'La Miranda beach resort, Apacible Boulevard, Nasugbu, 4231 BT, Philippines', 100, '09:00:00', '18:00:00', 'Monday, Tuesday, Wednesday, Thursday, Friday, Saturday', 'pending', 500.00, 250.00, 400.00, 10.00, '1763629573_2d32087b40dbfa936b39.jpg', '2025-11-20 09:06:13', '2025-11-20 09:06:13', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) UNSIGNED NOT NULL,
  `FirstName` varchar(50) NOT NULL,
  `MiddleName` varchar(50) DEFAULT NULL,
  `LastName` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Tourist','Spot Owner','Admin') NOT NULL,
  `LastLogin` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `FirstName`, `MiddleName`, `LastName`, `email`, `password`, `role`, `LastLogin`, `created_at`, `updated_at`) VALUES
(1, 'Admin', 'A', 'User', 'admin@gmail.com', '$2y$10$ZYF2TeQuQEhK6638yUjg7.166MCNuF4JiCZxaNRZq8y9zVzuW1VBG', 'Admin', NULL, '2025-11-20 08:53:44', '2025-11-20 08:53:44'),
(2, 'Spot', 'O', 'Owner', 'spot@gmail.com', '$2y$10$tdILbmIkL0gbcxpMkUsE0eF3ilWb0BsR3Nibc6YsUew3Q2D.ejwQO', 'Spot Owner', NULL, '2025-11-20 08:53:44', '2025-11-20 08:53:44'),
(3, 'Maria', 'B', 'Santos', 'maria.santos@gmail.com', '$2y$10$rhr3rYsexAz/jWB64PTIG.FdecO6lMnekuCm3/toOShCScXBa3GJu', 'Spot Owner', NULL, '2025-11-20 08:53:44', '2025-11-20 08:53:44'),
(4, 'Juan', 'R', 'Cruz', 'juan.cruz@gmail.com', '$2y$10$A3lseR37q89QqeTVZH1SHuabmYWNCEb8gU7YdJwkMxooa1pEQ2VQq', 'Spot Owner', NULL, '2025-11-20 08:53:44', '2025-11-20 08:53:44'),
(5, 'Anna', 'L', 'Garcia', 'anna.garcia@gmail.com', '$2y$10$CWZdG1Fd.u7/nJ1WEfIgmOtGD0FF1zcraNb41rLhZfexQ/b.rtVEm', 'Spot Owner', NULL, '2025-11-20 08:53:44', '2025-11-20 08:53:44'),
(6, 'Mark', 'T', 'Reyes', 'mark.reyes@gmail.com', '$2y$10$5DG777wexsmSzXy56I1/3ew9vWlTpnQYXX60gxe8OB//T2jh56FQ.', 'Spot Owner', NULL, '2025-11-20 08:53:44', '2025-11-20 08:53:44'),
(7, 'Elaine', 'G', 'Tores', 'elaine.tores@gmail.com', '$2y$10$KBakXxVH23j4N4h7Lrl1qeLWNjWY1pSDNHgqp3Ni.H.1qA32XpOGC', 'Spot Owner', NULL, '2025-11-20 08:53:44', '2025-11-20 08:53:44'),
(8, 'Patric', 'V', 'Mendoza', 'patric.mendoza@gmail.com', '$2y$10$GgqwTTN6XGEST2rODxlMAuN8IJYSNCXFko7iBuUOYHWg/8Pda2H4K', 'Spot Owner', NULL, '2025-11-20 08:53:44', '2025-11-20 08:53:44'),
(9, 'Lara', 'C', 'Vilanueva', 'lara.vilanueva@gmail.com', '$2y$10$DXvMuclIvOot8YuVl8mDPOSLExGOkcmFV2Fw2IG/tCKU/Efc4WDE2', 'Spot Owner', NULL, '2025-11-20 08:53:44', '2025-11-20 08:53:44'),
(10, 'Joseph', 'E', 'Mendoza', 'joseph.mendoza@gmail.com', '$2y$10$fkNyVcp6wx47xLLbY06kMuQZpTb8bLTp69QjzsjimkkWMCvtgON32', 'Spot Owner', NULL, '2025-11-20 08:53:44', '2025-11-20 08:53:44'),
(11, 'Ryan', 'J', 'Ramos', 'ryan.ramoz@gmail.com', '$2y$10$CbpCaBBBD.wPeQdIEuD/Xer/soQU/5Enn6lInirUGh/.Vcgj0Z8j6', 'Spot Owner', NULL, '2025-11-20 08:53:44', '2025-11-20 08:53:44'),
(12, 'Nicole', 'F', 'Lopez', 'nicole.lopez@gmail.com', '$2y$10$OAB26neIrWiwHpimWKk/OuxGvUI61y5.TUa2N98/zsTGRsSUqcXOa', 'Spot Owner', NULL, '2025-11-20 08:53:44', '2025-11-20 08:53:44'),
(13, 'Carlos', 'D', 'Villanueva', 'carlos.villanueva@gmail.com', '$2y$10$rWkmvgOn8k50hzPyvJK45eJvYt/0SPDTvzt/oCwaEln32XRAO9Nci', 'Tourist', NULL, '2025-11-20 08:53:45', '2025-11-20 08:53:45'),
(14, 'Sofia', 'M', 'Delgado', 'sofian.delgado@gmail.com', '$2y$10$yGtlM5QseeH5wpCl47aCAuveoHmESVMhtwkYWcVu7V5PLdOT04p36', 'Tourist', NULL, '2025-11-20 08:53:45', '2025-11-20 08:53:45'),
(15, 'Miguel', 'A', 'Torres', 'miguel.tores@gmail.com', '$2y$10$iAZhOcjzGxLDW7r.PhvDU.susEGmUtvXTcJvx/8clW88T/atI2InK', 'Tourist', NULL, '2025-11-20 08:53:45', '2025-11-20 08:53:45'),
(16, 'Elena', 'S', 'Morales', 'elena.morales@gmail.com', '$2y$10$qSoz.IxhKUrwOLJs1C7dHek99iEw6nQT9EtWz8AXVel0q27F.wOOa', 'Tourist', NULL, '2025-11-20 08:53:45', '2025-11-20 08:53:45'),
(17, 'Andres', 'J', 'Cabrera', 'andres.cabrera@gmail.com', '$2y$10$GderRzaqO94Yts6WohLr0Ongc2125yF4g9dx7frIg9vzuw6kqzTPC', 'Tourist', NULL, '2025-11-20 08:53:45', '2025-11-20 08:53:45'),
(18, 'Lucia', 'V', 'Serrano', 'lucia.serrano@gmail.com', '$2y$10$E1FFcsT2PNjwUsoLHdNyKeYOzpllTpi7aHQtI4eX5AlglTmt1QZ0e', 'Tourist', NULL, '2025-11-20 08:53:45', '2025-11-20 08:53:45'),
(19, 'Diego', 'R', 'Navarro', 'diego.navarro@gmail.com', '$2y$10$WRn1075YZ/QnKSIoQnHj4eacN5M0pmHMU7R30SuuJkMBAazWPqsaS', 'Tourist', NULL, '2025-11-20 08:53:45', '2025-11-20 08:53:45'),
(20, 'Camila', 'L', 'Fuentes', 'camila.fuentes@gmail.com', '$2y$10$h.W0enxth.moFlidl7SXYOljC5lwKot5fr0RcOvlCPKxn3C.1Oc4C', 'Tourist', NULL, '2025-11-20 08:53:45', '2025-11-20 08:53:45'),
(21, 'Javier', 'E', 'Silva', 'javier.silva@gmail.com', '$2y$10$Dmu3amAmTRqKDdoSrIGXR.cq8gjitClUcSC.udsbcslWL6mANYGWC', 'Tourist', NULL, '2025-11-20 08:53:45', '2025-11-20 08:53:45'),
(22, 'Valeria', 'G', 'Ortiz', 'valeria.ortiz@gmail.com', '$2y$10$tjdAteccbn6XwA93PW/pROo7YsvSM1zLRom7oOtgH3NYYRZFr5XpW', 'Tourist', NULL, '2025-11-20 08:53:45', '2025-11-20 08:53:45'),
(23, 'Fernando', 'M', 'Gomez', 'fernando.gomez@gmail.com', '$2y$10$AFP2RyleeTu4Qc7H3uZ8L.iIZktJFvmhprSGKaP72NE9YSkf/5of2', 'Tourist', NULL, '2025-11-20 08:53:45', '2025-11-20 08:53:45'),
(24, 'Natalia', 'C', 'Rojas', 'natalia.rojas@gmail.com', '$2y$10$.7JJh7wkjrPLe.8LOT13f.INgkBnvy25QzOPvJOmUYOSGNnd1.q9i', 'Tourist', NULL, '2025-11-20 08:53:45', '2025-11-20 08:53:45'),
(25, 'Sergio', 'T', 'Vega', 'sergio.vega@gmail.com', '$2y$10$VhJUiNmbz4cwxO4z8MEuTeR0T/dRDTiO6l7iHZpvUBv7xmYO9aORe', 'Tourist', NULL, '2025-11-20 08:53:45', '2025-11-20 08:53:45'),
(26, 'Isabella', 'F', 'Silva', 'isabella.silva@gmail.com', '$2y$10$iFzyHzxFXGp.RP7jfjNvC.sDVdk7EbMEPnuHilbsWF4Ao39cTVBFG', 'Tourist', NULL, '2025-11-20 08:53:45', '2025-11-20 08:53:45'),
(27, 'Ricardo', 'H', 'Mora', 'ricardo.mora@gmail.com', '$2y$10$OLYFaW9NRRSS88TL2JNu/O0XuZAhePGMK8/tZmXrIpMrmbAoggH06', 'Tourist', NULL, '2025-11-20 08:53:45', '2025-11-20 08:53:45'),
(28, 'Gabriela', 'N', 'Cortez', 'gabriela.cortez@gmail.com', '$2y$10$j9KuBnplZ2K8Zi7i4C.iAeftbMq2rNzby78FkRBR6qIXymHSHYIwW', 'Tourist', NULL, '2025-11-20 08:53:45', '2025-11-20 08:53:45'),
(29, 'Matias', 'O', 'Salazar', 'matias.salazar@gmail.com', '$2y$10$kQJ8hpHfSc6Dj1Fwt94AXe6v7ekUdvjzw6GpPSnLJFmIPOf0uhYq6', 'Tourist', NULL, '2025-11-20 08:53:45', '2025-11-20 08:53:45'),
(30, 'Carmila', 'P', 'Duarte', 'carmila.duarte@gmail.com', '$2y$10$Zto2h3LhWxWh4V.mdi5M/e/Ls0huTN29cpRww9KYqjHOWkyFuKf7G', 'Tourist', NULL, '2025-11-20 08:53:46', '2025-11-20 08:53:46'),
(31, 'Emilio', 'Q', 'Benitez', 'emilio.benitez@gmail.com', '$2y$10$7PfUFJyE1ls2vdic9Uhw/.zK8aPdGflcZTQijs4UKKecdNj4xjit6', 'Tourist', NULL, '2025-11-20 08:53:46', '2025-11-20 08:53:46');

-- --------------------------------------------------------

--
-- Table structure for table `user_preferences`
--

CREATE TABLE `user_preferences` (
  `preference_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `category` varchar(150) DEFAULT NULL COMMENT 'Comma-separated categories or single category (example: "nature,culture")',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_visit_history`
--

CREATE TABLE `user_visit_history` (
  `history_id` int(11) UNSIGNED NOT NULL,
  `user_id` int(11) UNSIGNED NOT NULL,
  `spot_id` int(11) UNSIGNED NOT NULL,
  `liked` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'TRUE if liked, FALSE if disliked',
  `last_visited_at` datetime DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `visitor_checkins`
--

CREATE TABLE `visitor_checkins` (
  `checkin_id` int(11) UNSIGNED NOT NULL,
  `customer_id` int(11) UNSIGNED NOT NULL,
  `booking_id` int(11) UNSIGNED NOT NULL,
  `checkin_time` datetime NOT NULL,
  `checkout_time` datetime DEFAULT NULL,
  `actual_visitors` int(11) NOT NULL,
  `is_walkin` tinyint(1) NOT NULL DEFAULT 0,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `visitor_checkins`
--

INSERT INTO `visitor_checkins` (`checkin_id`, `customer_id`, `booking_id`, `checkin_time`, `checkout_time`, `actual_visitors`, `is_walkin`, `notes`) VALUES
(1, 13, 12, '2025-11-20 09:11:22', NULL, 3, 0, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `activity_log_user_id_foreign` (`user_id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `bookings_spot_id_foreign` (`spot_id`),
  ADD KEY `bookings_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `businesses`
--
ALTER TABLE `businesses`
  ADD PRIMARY KEY (`business_id`),
  ADD KEY `businesses_user_id_foreign` (`user_id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`),
  ADD KEY `customers_user_id_foreign` (`user_id`);

--
-- Indexes for table `itinerary`
--
ALTER TABLE `itinerary`
  ADD PRIMARY KEY (`itinerary_id`),
  ADD KEY `preference_id` (`preference_id`),
  ADD KEY `spot_id` (`spot_id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `payments_booking_id_foreign` (`booking_id`);

--
-- Indexes for table `revenue_analytics`
--
ALTER TABLE `revenue_analytics`
  ADD PRIMARY KEY (`analytics_id`),
  ADD KEY `revenue_analytics_business_id_foreign` (`business_id`),
  ADD KEY `revenue_analytics_spot_id_foreign` (`spot_id`);

--
-- Indexes for table `review_feedback`
--
ALTER TABLE `review_feedback`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `review_feedback_booking_id_foreign` (`booking_id`),
  ADD KEY `review_feedback_spot_id_foreign` (`spot_id`),
  ADD KEY `review_feedback_customer_id_foreign` (`customer_id`),
  ADD KEY `review_feedback_business_id_foreign` (`business_id`);

--
-- Indexes for table `spot_availability`
--
ALTER TABLE `spot_availability`
  ADD PRIMARY KEY (`availability_id`),
  ADD KEY `spot_availability_spot_id_foreign` (`spot_id`);

--
-- Indexes for table `spot_fav_by_customer`
--
ALTER TABLE `spot_fav_by_customer`
  ADD PRIMARY KEY (`fav_id`),
  ADD KEY `spot_fav_by_customer_user_id_foreign` (`user_id`),
  ADD KEY `spot_fav_by_customer_spot_id_foreign` (`spot_id`);

--
-- Indexes for table `spot_gallery`
--
ALTER TABLE `spot_gallery`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `spot_gallery_spot_id_foreign` (`spot_id`);

--
-- Indexes for table `spot_view_logs`
--
ALTER TABLE `spot_view_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `spot_view_logs_user_id_foreign` (`user_id`),
  ADD KEY `spot_view_logs_spot_id_foreign` (`spot_id`);

--
-- Indexes for table `tourist_spots`
--
ALTER TABLE `tourist_spots`
  ADD PRIMARY KEY (`spot_id`),
  ADD KEY `tourist_spots_business_id_foreign` (`business_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD PRIMARY KEY (`preference_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_visit_history`
--
ALTER TABLE `user_visit_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `user_visit_history_user_id_foreign` (`user_id`),
  ADD KEY `user_visit_history_spot_id_foreign` (`spot_id`);

--
-- Indexes for table `visitor_checkins`
--
ALTER TABLE `visitor_checkins`
  ADD PRIMARY KEY (`checkin_id`),
  ADD KEY `visitor_checkins_customer_id_foreign` (`customer_id`),
  ADD KEY `visitor_checkins_booking_id_foreign` (`booking_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `log_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `businesses`
--
ALTER TABLE `businesses`
  MODIFY `business_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `itinerary`
--
ALTER TABLE `itinerary`
  MODIFY `itinerary_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `revenue_analytics`
--
ALTER TABLE `revenue_analytics`
  MODIFY `analytics_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `review_feedback`
--
ALTER TABLE `review_feedback`
  MODIFY `review_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `spot_availability`
--
ALTER TABLE `spot_availability`
  MODIFY `availability_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `spot_fav_by_customer`
--
ALTER TABLE `spot_fav_by_customer`
  MODIFY `fav_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `spot_gallery`
--
ALTER TABLE `spot_gallery`
  MODIFY `image_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `spot_view_logs`
--
ALTER TABLE `spot_view_logs`
  MODIFY `log_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tourist_spots`
--
ALTER TABLE `tourist_spots`
  MODIFY `spot_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `user_preferences`
--
ALTER TABLE `user_preferences`
  MODIFY `preference_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_visit_history`
--
ALTER TABLE `user_visit_history`
  MODIFY `history_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `visitor_checkins`
--
ALTER TABLE `visitor_checkins`
  MODIFY `checkin_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD CONSTRAINT `activity_log_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `bookings_spot_id_foreign` FOREIGN KEY (`spot_id`) REFERENCES `tourist_spots` (`spot_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `businesses`
--
ALTER TABLE `businesses`
  ADD CONSTRAINT `businesses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `itinerary`
--
ALTER TABLE `itinerary`
  ADD CONSTRAINT `itinerary_preference_id_foreign` FOREIGN KEY (`preference_id`) REFERENCES `user_preferences` (`preference_id`) ON DELETE CASCADE ON UPDATE SET NULL,
  ADD CONSTRAINT `itinerary_spot_id_foreign` FOREIGN KEY (`spot_id`) REFERENCES `tourist_spots` (`spot_id`) ON DELETE CASCADE ON UPDATE SET NULL;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `revenue_analytics`
--
ALTER TABLE `revenue_analytics`
  ADD CONSTRAINT `revenue_analytics_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`business_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `revenue_analytics_spot_id_foreign` FOREIGN KEY (`spot_id`) REFERENCES `tourist_spots` (`spot_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `review_feedback`
--
ALTER TABLE `review_feedback`
  ADD CONSTRAINT `review_feedback_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `review_feedback_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`business_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `review_feedback_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `review_feedback_spot_id_foreign` FOREIGN KEY (`spot_id`) REFERENCES `tourist_spots` (`spot_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `spot_availability`
--
ALTER TABLE `spot_availability`
  ADD CONSTRAINT `spot_availability_spot_id_foreign` FOREIGN KEY (`spot_id`) REFERENCES `tourist_spots` (`spot_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `spot_fav_by_customer`
--
ALTER TABLE `spot_fav_by_customer`
  ADD CONSTRAINT `spot_fav_by_customer_spot_id_foreign` FOREIGN KEY (`spot_id`) REFERENCES `tourist_spots` (`spot_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `spot_fav_by_customer_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `spot_gallery`
--
ALTER TABLE `spot_gallery`
  ADD CONSTRAINT `spot_gallery_spot_id_foreign` FOREIGN KEY (`spot_id`) REFERENCES `tourist_spots` (`spot_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `spot_view_logs`
--
ALTER TABLE `spot_view_logs`
  ADD CONSTRAINT `spot_view_logs_spot_id_foreign` FOREIGN KEY (`spot_id`) REFERENCES `tourist_spots` (`spot_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `spot_view_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tourist_spots`
--
ALTER TABLE `tourist_spots`
  ADD CONSTRAINT `tourist_spots_business_id_foreign` FOREIGN KEY (`business_id`) REFERENCES `businesses` (`business_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD CONSTRAINT `user_preferences_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`UserID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_visit_history`
--
ALTER TABLE `user_visit_history`
  ADD CONSTRAINT `user_visit_history_spot_id_foreign` FOREIGN KEY (`spot_id`) REFERENCES `tourist_spots` (`spot_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `user_visit_history_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `visitor_checkins`
--
ALTER TABLE `visitor_checkins`
  ADD CONSTRAINT `visitor_checkins_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `visitor_checkins_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`customer_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
