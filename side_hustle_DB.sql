/*
SQLyog Community v13.1.7 (64 bit)
MySQL - 10.4.28-MariaDB : Database - side-hustle
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`side-hustle` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;

USE `side-hustle`;

/*Table structure for table `add_to_favourites` */

DROP TABLE IF EXISTS `add_to_favourites`;

CREATE TABLE `add_to_favourites` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `model_id` int(11) NOT NULL,
  `model_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `add_to_favourites` */

insert  into `add_to_favourites`(`id`,`user_id`,`model_id`,`model_name`,`created_at`,`updated_at`) values 
(2,1,3,'Event','2024-01-25 15:58:02','2024-01-25 15:58:02');

/*Table structure for table `banners` */

DROP TABLE IF EXISTS `banners`;

CREATE TABLE `banners` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `banners` */

insert  into `banners`(`id`,`name`,`description`,`image`,`created_at`,`updated_at`) values 
(1,'First Banner','Sed commodi vero commodo ut in magna sed quas','uploads/banner/1707971862jpg','2024-02-15 17:37:42','2024-02-15 17:58:36');

/*Table structure for table `cart_details` */

DROP TABLE IF EXISTS `cart_details`;

CREATE TABLE `cart_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cart_id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` text DEFAULT NULL,
  `delivery_type` varchar(255) DEFAULT NULL,
  `service_type` varchar(255) DEFAULT NULL,
  `price` double DEFAULT NULL,
  `qty` int(11) NOT NULL,
  `product_image` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `address` text DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `appartment` varchar(255) DEFAULT NULL,
  `lat` varchar(255) DEFAULT NULL,
  `lng` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `cart_details` */

/*Table structure for table `carts` */

DROP TABLE IF EXISTS `carts`;

CREATE TABLE `carts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `sub_total` double NOT NULL,
  `total_items` int(11) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `carts` */

/*Table structure for table `chats` */

DROP TABLE IF EXISTS `chats`;

CREATE TABLE `chats` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `model_id` int(11) DEFAULT NULL,
  `model_name` varchar(255) DEFAULT NULL,
  `user_one` int(11) DEFAULT NULL,
  `user_two` int(11) DEFAULT NULL,
  `user_one_model` varchar(255) DEFAULT NULL,
  `user_two_model` varchar(255) DEFAULT NULL,
  `is_blocked` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `chats` */

/*Table structure for table `event_images` */

DROP TABLE IF EXISTS `event_images`;

CREATE TABLE `event_images` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `image` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `event_images` */

/*Table structure for table `events` */

DROP TABLE IF EXISTS `events`;

CREATE TABLE `events` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `price` double DEFAULT NULL,
  `payment_type` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `location` text DEFAULT NULL,
  `lat` varchar(255) DEFAULT NULL,
  `lng` varchar(255) DEFAULT NULL,
  `purpose` text DEFAULT NULL,
  `theme` text DEFAULT NULL,
  `vendors_list` text DEFAULT NULL,
  `available_attractions` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Scheduled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `events` */

insert  into `events`(`id`,`user_id`,`name`,`price`,`payment_type`,`date`,`end_time`,`start_time`,`location`,`lat`,`lng`,`purpose`,`theme`,`vendors_list`,`available_attractions`,`status`,`created_at`,`updated_at`) values 
(1,1,'New Test Event name update',50,'cash','2023-12-15','18:30:00','12:30:00','Test location update','123456','654321','test purpose update','test theme update','df adfad adfaf','','Completed',NULL,'2024-01-31 14:31:18');

/*Table structure for table `failed_jobs` */

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `failed_jobs` */

/*Table structure for table `interested_users` */

DROP TABLE IF EXISTS `interested_users`;

CREATE TABLE `interested_users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `interested_users` */

insert  into `interested_users`(`id`,`event_id`,`user_id`,`status`,`created_at`,`updated_at`) values 
(1,1,2,'Confirmed',NULL,'2024-01-30 17:11:58'),
(2,1,2,'Pending',NULL,NULL);

/*Table structure for table `job_images` */

DROP TABLE IF EXISTS `job_images`;

CREATE TABLE `job_images` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `job_id` int(11) NOT NULL,
  `image` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `job_images` */

/*Table structure for table `job_requests` */

DROP TABLE IF EXISTS `job_requests`;

CREATE TABLE `job_requests` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `job_id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bid_amount` double NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `job_requests` */

insert  into `job_requests`(`id`,`job_id`,`owner_id`,`user_id`,`bid_amount`,`status`,`created_at`,`updated_at`) values 
(1,1,1,1,100,'Pending','2024-02-10 12:28:03','2024-02-10 12:28:03'),
(2,1,1,1,100,'Pending','2024-02-10 12:28:19','2024-02-10 12:28:19'),
(3,1,1,1,100,'Pending','2024-02-10 12:37:30','2024-02-10 12:37:30');

/*Table structure for table `jobs` */

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `assigned_user_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `bid_amount` double NOT NULL DEFAULT 0,
  `budget` double NOT NULL DEFAULT 0,
  `area_code` varchar(255) DEFAULT NULL,
  `job_date` date DEFAULT NULL,
  `job_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `total_hours` varchar(255) DEFAULT NULL,
  `location` text DEFAULT NULL,
  `lat` varchar(255) DEFAULT NULL,
  `lng` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `additional_information` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `jobs` */

insert  into `jobs`(`id`,`user_id`,`assigned_user_id`,`title`,`bid_amount`,`budget`,`area_code`,`job_date`,`job_time`,`end_time`,`total_hours`,`location`,`lat`,`lng`,`description`,`additional_information`,`status`,`created_at`,`updated_at`) values 
(1,1,NULL,'test job',0,500,'12345','2024-01-31','05:00:00',NULL,'5','test location','123','321','test description',NULL,'Pending',NULL,NULL);

/*Table structure for table `messages` */

DROP TABLE IF EXISTS `messages`;

CREATE TABLE `messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `chat_id` int(11) DEFAULT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `sender_model` varchar(255) DEFAULT NULL,
  `receiver_model` varchar(255) DEFAULT NULL,
  `product_count` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `file_path` text DEFAULT NULL,
  `type` tinyint(4) DEFAULT 1,
  `message_type` tinyint(4) DEFAULT 1,
  `is_seen` tinyint(4) DEFAULT 0,
  `product_type` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `price` double DEFAULT NULL,
  `delivery_type` varchar(255) DEFAULT NULL,
  `service_date` date DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `image` text DEFAULT NULL,
  `location` text DEFAULT NULL,
  `lat` varchar(255) DEFAULT NULL,
  `lng` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `messages` */

/*Table structure for table `migrations` */

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `migrations` */

insert  into `migrations`(`id`,`migration`,`batch`) values 
(1,'2014_10_12_000000_create_users_table',1),
(2,'2014_10_12_100000_create_password_reset_tokens_table',1),
(3,'2014_10_12_100000_create_password_resets_table',1),
(4,'2019_08_19_000000_create_failed_jobs_table',1),
(5,'2019_12_14_000001_create_personal_access_tokens_table',1),
(6,'2023_10_03_194425_create_roles_table',1),
(7,'2023_10_03_194540_create_settings_table',1),
(8,'2023_10_03_234249_create_products_table',1),
(9,'2023_10_04_021414_create_shops_table',1),
(10,'2023_10_04_223445_create_product_images_table',1),
(11,'2023_10_04_224704_create_jobs_table',1),
(12,'2023_10_04_230811_create_job_images_table',1),
(13,'2023_10_05_234930_create_job_requests_table',1),
(14,'2023_10_06_224818_create_reviews_table',1),
(15,'2023_10_06_234938_create_events_table',1),
(16,'2023_10_07_010205_create_event_images_table',1),
(17,'2023_10_09_215657_create_interested_users_table',1),
(18,'2023_10_09_230532_create_add_to_favourites_table',1),
(19,'2023_10_18_223337_create_user_cards_table',1),
(20,'2023_10_20_172213_create_carts_table',1),
(21,'2023_10_20_183826_create_orders_table',1),
(22,'2023_10_20_183837_create_order_details_table',1),
(23,'2023_10_20_212156_create_cart_details_table',1),
(24,'2023_11_01_221938_create_plans_table',1),
(25,'2023_11_01_222901_create_subscriptions_table',1),
(26,'2023_11_15_100607_create_resume_table',1),
(27,'2023_11_15_101121_create_resume_hobbies_table',1),
(28,'2023_11_15_234612_add_col_in_settings_table',1),
(29,'2023_11_17_202917_socket_users',1),
(30,'2023_11_17_232555_create_chats_table',1),
(31,'2023_11_17_233357_create_messages_table',1),
(32,'2023_11_17_234613_add_file_col_in_settings_table',1),
(33,'2023_11_23_200702_add_new_cols_in_subscriptions_table',1),
(34,'2023_11_28_010534_add_new_col_in_chats_table',1),
(35,'2023_12_16_022602_add_cols_in_resume_table',1),
(36,'2023_12_19_230418_add_new_col_in_resume_table',1),
(37,'2024_02_02_025217_add_col_in_jobs_table',2),
(38,'2024_02_07_013304_create_notifications_table',3),
(39,'2024_02_15_034632_create_banners_table',4);

/*Table structure for table `notifications` */

DROP TABLE IF EXISTS `notifications`;

CREATE TABLE `notifications` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `sender_id` int(11) DEFAULT NULL,
  `receiver_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `notification` text DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `is_read` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `notifications` */

/*Table structure for table `order_details` */

DROP TABLE IF EXISTS `order_details`;

CREATE TABLE `order_details` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `product_name` text DEFAULT NULL,
  `delivery_type` varchar(255) DEFAULT NULL,
  `service_type` varchar(255) DEFAULT NULL,
  `product_per_price` decimal(8,2) NOT NULL,
  `product_qty` int(11) NOT NULL,
  `product_subtotal_price` decimal(8,2) NOT NULL,
  `product_image` text DEFAULT NULL,
  `delivery_address` text DEFAULT NULL,
  `street` varchar(255) DEFAULT NULL,
  `appartment` varchar(255) DEFAULT NULL,
  `lat` varchar(255) DEFAULT NULL,
  `lng` varchar(255) DEFAULT NULL,
  `service_date` date DEFAULT NULL,
  `hours_required` varchar(255) DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `order_details` */

/*Table structure for table `orders` */

DROP TABLE IF EXISTS `orders`;

CREATE TABLE `orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_no` varchar(255) NOT NULL,
  `owner_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_email` varchar(255) DEFAULT NULL,
  `sub_total` double NOT NULL,
  `total` double NOT NULL,
  `items_total` int(11) NOT NULL,
  `order_status` enum('','paid','pending','cancelled','unpaid','completed','shipped') NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `orders` */

/*Table structure for table `password_reset_tokens` */

DROP TABLE IF EXISTS `password_reset_tokens`;

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_reset_tokens` */

/*Table structure for table `password_resets` */

DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `password_resets` */

/*Table structure for table `personal_access_tokens` */

DROP TABLE IF EXISTS `personal_access_tokens`;

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `personal_access_tokens` */

insert  into `personal_access_tokens`(`id`,`tokenable_type`,`tokenable_id`,`name`,`token`,`abilities`,`last_used_at`,`expires_at`,`created_at`,`updated_at`) values 
(1,'App\\Models\\User',1,'API Token','3243ac18266820e72d73897161d204355abe37cc37796c4fbb5e82e0a2258f08','[\"*\"]',NULL,NULL,'2023-12-28 17:33:55','2023-12-28 17:33:55'),
(2,'App\\Models\\User',1,'API Token','35c2faf2ec1713548d118729c6e372b4e832cb6ad2b6633f9d14c66b8b6a059c','[\"*\"]','2023-12-28 17:35:48',NULL,'2023-12-28 17:34:12','2023-12-28 17:35:48'),
(3,'App\\Models\\User',1,'API Token','f63de951d127e2bc1496342fcba3d6d5ae63204290687587bf1a9aa72fc7d588','[\"*\"]','2023-12-30 14:27:52',NULL,'2023-12-30 14:20:08','2023-12-30 14:27:52'),
(4,'App\\Models\\User',1,'API Token','2e16a886b6f9a668a51071c0af2b202c2c3ed8a3e8b2a413724e94de6b7fb103','[\"*\"]','2024-01-25 16:09:33',NULL,'2024-01-25 15:56:13','2024-01-25 16:09:33'),
(5,'App\\Models\\User',1,'API Token','c88d6589b5e214538bcc072633c850c8e6d3e428315baaf6f6eff9d600a9290a','[\"*\"]','2024-01-30 11:51:32',NULL,'2024-01-30 11:46:27','2024-01-30 11:51:32'),
(6,'App\\Models\\User',1,'API Token','df74aba1c59e73575264e9191f4dfb8b1769c05d6dc13702205eb28231ec7a59','[\"*\"]','2024-01-30 17:33:49',NULL,'2024-01-30 17:09:36','2024-01-30 17:33:49'),
(7,'App\\Models\\User',1,'API Token','bfca98e5fbff7020e519a969db72b70b55caef69ec13d63029df27b25b17052d','[\"*\"]','2024-01-31 17:35:38',NULL,'2024-01-31 12:05:16','2024-01-31 17:35:38'),
(8,'App\\Models\\User',1,'API Token','82d4520fd02b1f28f70ba8b334247eb7a24efb454848b8cbbf4cfb49b3c40377','[\"*\"]','2024-02-01 13:54:25',NULL,'2024-02-01 12:18:21','2024-02-01 13:54:25'),
(9,'App\\Models\\User',1,'API Token','ee7a329e731591fb48254a94474d11437ebbabcaff4cb36304604a9a72f6f60d','[\"*\"]','2024-02-02 13:54:07',NULL,'2024-02-02 13:42:10','2024-02-02 13:54:07'),
(10,'App\\Models\\User',1,'API Token','54eb7b94c927f4325e816991eb334887dbc544b7c43bf2c6486ba2ae462f15b2','[\"*\"]','2024-02-07 13:04:23',NULL,'2024-02-07 13:01:59','2024-02-07 13:04:23'),
(11,'App\\Models\\User',1,'API Token','9b3e9bfdf35cc5556a2ab36a5deaaa2b2e820c69971e431574ef0d4e03d2040a','[\"*\"]','2024-02-07 14:30:52',NULL,'2024-02-07 14:29:44','2024-02-07 14:30:52'),
(12,'App\\Models\\User',1,'API Token','a51e9d465e715fa1b9a8167f2c3415a31daf2ed8190486f34c7fa584ac040e66','[\"*\"]','2024-02-10 12:41:28',NULL,'2024-02-10 12:27:24','2024-02-10 12:41:28');

/*Table structure for table `plans` */

DROP TABLE IF EXISTS `plans`;

CREATE TABLE `plans` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `price` double DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `plans` */

insert  into `plans`(`id`,`product_id`,`name`,`price`,`status`,`created_at`,`updated_at`) values 
(1,'price_1O7cPXDBTVUYOnmbGUaS3kLt','Post Per Day',1,NULL,'2023-12-21 10:17:24','2023-12-21 10:17:24'),
(2,'price_1O7cPHDBTVUYOnmb215HoMA9','Post Per Week',7,NULL,'2023-12-21 10:17:24','2023-12-21 10:17:24'),
(3,'price_1O7ZSPDBTVUYOnmbs0NlCIqz','Post Per Month',30,NULL,'2023-12-21 10:17:24','2023-12-21 10:17:24');

/*Table structure for table `product_images` */

DROP TABLE IF EXISTS `product_images`;

CREATE TABLE `product_images` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `product_id` int(11) NOT NULL,
  `image` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `product_images` */

/*Table structure for table `products` */

DROP TABLE IF EXISTS `products`;

CREATE TABLE `products` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `price` double DEFAULT NULL,
  `hourly_rate` double DEFAULT NULL,
  `delivery_type` varchar(255) DEFAULT NULL,
  `service_type` varchar(255) DEFAULT NULL,
  `location` text DEFAULT NULL,
  `lat` varchar(255) DEFAULT NULL,
  `lng` varchar(255) DEFAULT NULL,
  `zip_code` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `additional_information` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `products` */

/*Table structure for table `resume` */

DROP TABLE IF EXISTS `resume`;

CREATE TABLE `resume` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `actual_name` varchar(255) DEFAULT NULL,
  `nick_name` varchar(255) DEFAULT NULL,
  `profession` varchar(255) DEFAULT NULL,
  `family_ties` varchar(255) DEFAULT NULL,
  `professional_background` varchar(255) DEFAULT NULL,
  `favourite_quote` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `file_size` varchar(255) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `profile_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `resume` */

insert  into `resume`(`id`,`user_id`,`actual_name`,`nick_name`,`profession`,`family_ties`,`professional_background`,`favourite_quote`,`description`,`filename`,`file_size`,`file`,`profile_image`,`created_at`,`updated_at`) values 
(1,1,'test name','nick name','Software Engineer','new family ties','Information Technology','test quote','test descripton','file-sample_150kB.pdf','139 KB','http://127.0.0.1:8000/uploads/files/resumes/1706673508-file-sample_150kB.pdf','http://127.0.0.1:8000/uploads/files/resumes/1703738148-1618996771_camera-7.jpg','2023-12-28 17:35:48','2024-01-31 16:58:28');

/*Table structure for table `resume_hobbies` */

DROP TABLE IF EXISTS `resume_hobbies`;

CREATE TABLE `resume_hobbies` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `resume_id` bigint(20) unsigned NOT NULL,
  `hobby` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `resume_hobbies` */

/*Table structure for table `reviews` */

DROP TABLE IF EXISTS `reviews`;

CREATE TABLE `reviews` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `model_id` int(11) DEFAULT NULL,
  `model_name` varchar(255) DEFAULT NULL,
  `task_giver` int(11) DEFAULT NULL,
  `tasker` int(11) DEFAULT NULL,
  `rating` double DEFAULT NULL,
  `review` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `reviews` */

/*Table structure for table `roles` */

DROP TABLE IF EXISTS `roles`;

CREATE TABLE `roles` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `roles` */

insert  into `roles`(`id`,`name`,`created_at`,`updated_at`) values 
(1,'Admin','2023-12-21 10:17:24','2023-12-21 10:17:24'),
(2,'Contractor','2023-12-21 10:17:24','2023-12-21 10:17:24'),
(3,'Worker','2023-12-21 10:17:24','2023-12-21 10:17:24');

/*Table structure for table `settings` */

DROP TABLE IF EXISTS `settings`;

CREATE TABLE `settings` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `privacy_policy` longtext DEFAULT NULL,
  `terms_and_conditions` longtext DEFAULT NULL,
  `about_us` longtext DEFAULT NULL,
  `united_capitalism` longtext DEFAULT NULL,
  `pdf_file` varchar(255) DEFAULT NULL,
  `logo` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `settings` */

insert  into `settings`(`id`,`privacy_policy`,`terms_and_conditions`,`about_us`,`united_capitalism`,`pdf_file`,`logo`,`created_at`,`updated_at`) values 
(1,'<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheet containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheet containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>','<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheet containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheet containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>','<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p><p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>','<p>United Capitalism</p><p>Reinventing Local Economies through Community Support</p><p>At 22 years old, freshly separated from the military, I found myself on vacation in San Francisco, California. During a memorable weekend, I visited a Leonardo da Vinci exhibit showcasing scaled-down models of his inventions, from parachutes to ball bearings. Amidst my awe for these remarkable creations, I stumbled upon a model of a small city, prompting a profound question: \'Had this man discovered the blueprint for a perfect city?\' To this day, that question remains unanswered, but it ignited a concept that continues to fuel my passion. This experience led me to contemplate the essence of a perfect city, emphasizing one vital element: \'circulation\'—the seamless flow of goods and resources within a community.</p><p>The thought of circulation led me to ponder the Local Circulation of Money (LCM). In many cities, monopolies, represented by giant chain stores like Wal-Mart, drain substantial amounts of money from the local economy, leaving minimal returns. This financial drain cripples the LCM, hindering the city\'s economic growth and prosperity.</p><p>As responsible neighbors and citizens, it is our duty to reinforce the LCM by supporting one another, enhancing the circulation of money within our communities. Large corporations, akin to winners in a Monopoly game, often dominate the economic landscape. To counter this, I envisioned United Capitalism—a concept aimed at empowering ordinary people to compete with these monopolies. This vision materialized into the Side Hustle app, a platform designed to give individuals a chance to participate actively in their local economies.</p><p>The Side Hustle app, in its initial stages, serves as a basic tool to facilitate local transactions. However, my aspiration is to evolve it into a platform promoting \'Repetitious Cyclical Patterns of Money.\' This concept involves establishing a chain of transactions among neighbors, allowing them to nurture their side hustles and break free from the chains of the system. Starting a business demands substantial resources, especially when competing against colossal franchises. Yet, the potential for success is evident; anyone can create a superior product, like a healthier cheeseburger, compared to what large corporations offer. The missing element has always been a supportive community platform.</p><p>Imagine our neighbor Jimmy using the Side Hustle app to announce, \'Im grilling cheeseburgers in my driveway this Saturday.\' When our community rallies behind Jimmy, we provide him with more than just financial support. We give him hope—a chance to break free from a job he dislikes, in a place he despises, working with people he cannot stand. By challenging the system, we offer Jimmy the opportunity to plan a new, brighter future.</p><p>In essence, United Capitalism and the Side Hustle app are not just about financial transactions; they represent the embodiment of hope and the power of communities working together. My wish is for everyone involved to find the strength and support they need to escape the confines of the existing economic system, paving the way for a more prosperous and equitable future for all.</p>',NULL,NULL,NULL,NULL);

/*Table structure for table `shops` */

DROP TABLE IF EXISTS `shops`;

CREATE TABLE `shops` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `zip_code` varchar(255) DEFAULT NULL,
  `location` text DEFAULT NULL,
  `lat` text DEFAULT NULL,
  `lng` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `shops` */

/*Table structure for table `socket_users` */

DROP TABLE IF EXISTS `socket_users`;

CREATE TABLE `socket_users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `user_model` varchar(255) NOT NULL,
  `socket_id` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `socket_users` */

/*Table structure for table `subscriptions` */

DROP TABLE IF EXISTS `subscriptions`;

CREATE TABLE `subscriptions` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) DEFAULT NULL,
  `model_name` varchar(255) NOT NULL,
  `payer_email` varchar(255) DEFAULT NULL,
  `stripe_subscription_id` varchar(255) DEFAULT NULL,
  `stripe_customer_id` varchar(255) DEFAULT NULL,
  `stripe_plan_id` varchar(255) DEFAULT NULL,
  `plan_amount` double DEFAULT NULL,
  `plan_amount_currency` varchar(255) DEFAULT NULL,
  `plan_interval` varchar(255) DEFAULT NULL,
  `plan_period_start` datetime DEFAULT NULL,
  `plan_period_end` datetime DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `subscriptions` */

/*Table structure for table `user_cards` */

DROP TABLE IF EXISTS `user_cards`;

CREATE TABLE `user_cards` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `card_id` varchar(255) NOT NULL,
  `customer_id` varchar(255) NOT NULL,
  `last4` varchar(255) DEFAULT NULL,
  `type` varchar(255) DEFAULT NULL,
  `owner_name` varchar(255) DEFAULT NULL,
  `is_default` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `user_cards` */

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `otp` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `zip_code` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `image` text DEFAULT NULL,
  `rating` double NOT NULL DEFAULT 0,
  `api_token` varchar(255) DEFAULT NULL,
  `fcm_token` varchar(255) DEFAULT NULL,
  `provider_id` varchar(255) DEFAULT NULL,
  `provider_name` varchar(255) DEFAULT NULL,
  `access_token` varchar(255) DEFAULT NULL,
  `is_push_notification` tinyint(4) DEFAULT 1,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `is_verified` tinyint(4) NOT NULL DEFAULT 0,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Data for the table `users` */

insert  into `users`(`id`,`role_id`,`first_name`,`last_name`,`name`,`email`,`otp`,`phone`,`zip_code`,`country`,`image`,`rating`,`api_token`,`fcm_token`,`provider_id`,`provider_name`,`access_token`,`is_push_notification`,`status`,`is_verified`,`email_verified_at`,`password`,`remember_token`,`created_at`,`updated_at`) values 
(1,1,NULL,NULL,'Admin','admin@admin.com','123456','+123123123123',NULL,NULL,NULL,0,'12|Yd310nlLSZuNd38QeNYneilwzRwV8p6oOgNuLSMQ130ea2f2','dPauEKtDqFL3v4g0vCGI_j:APA91bHwDna5_aWSGJVcsN7R_O90wVIQ8UDczjAKW0xvsjG_-qYC3FP2vOM1yD9yzjY9JbkTvN6ui7lJfVUwDQaFl3mtjEXqYnv7OXlfTfxwkgjDgjDO9minj7c_I1GQHwhG95hdgJkS',NULL,NULL,NULL,NULL,1,1,NULL,'$2y$10$1j6d.vHe1ffkopai2bT8l.XPfA14e0prJwdEhcga.Ko2glER1UNCa',NULL,'2023-12-21 10:17:24','2024-02-10 12:27:24'),
(2,2,'test ','developer ','test developer','dev@gmail.com','123456','987654321','12345','UK',NULL,4.5,NULL,NULL,NULL,NULL,NULL,NULL,0,0,NULL,NULL,NULL,NULL,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
