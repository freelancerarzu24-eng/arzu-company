-- Database Schema for Dmagancy - Freelance Web Developer Service Selling Website

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- --------------------------------------------------------

-- Table structure for table `admins`
CREATE TABLE `admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `users`
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL UNIQUE,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `categories`
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL UNIQUE,
  `image` varchar(255) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `services`
CREATE TABLE `services` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL UNIQUE,
  `short_description` text DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `features` text DEFAULT NULL, -- JSON or comma separated
  `faqs` longtext DEFAULT NULL, -- JSON
  `basic_price` decimal(10,2) NOT NULL,
  `standard_price` decimal(10,2) DEFAULT NULL,
  `premium_price` decimal(10,2) DEFAULT NULL,
  `featured` tinyint(1) DEFAULT 0,
  `status` tinyint(1) DEFAULT 1,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `service_images`
CREATE TABLE `service_images` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `service_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`service_id`) REFERENCES `services`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `orders`
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL, -- Null for guest orders or international orders handled via WhatsApp (though mostly via dashboard)
  `service_id` int(11) NOT NULL,
  `order_number` varchar(20) NOT NULL UNIQUE,
  `package_type` varchar(20) NOT NULL, -- Basic, Standard, Premium
  `amount` decimal(10,2) NOT NULL,
  `country` varchar(100) NOT NULL,
  `requirements` text DEFAULT NULL,
  `status` enum('Pending', 'Processing', 'Completed', 'Cancelled') DEFAULT 'Pending',
  `admin_notes` text DEFAULT NULL,
  `delivery_files` varchar(255) DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`service_id`) REFERENCES `services`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `payments`
CREATE TABLE `payments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `payment_method` varchar(50) NOT NULL, -- bKash, Nagad, etc.
  `transaction_id` varchar(100) DEFAULT NULL,
  `payment_screenshot` varchar(255) DEFAULT NULL,
  `status` enum('Pending', 'Verified', 'Rejected') DEFAULT 'Pending',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `reviews`
CREATE TABLE `reviews` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_name` varchar(100) NOT NULL,
  `client_image` varchar(255) DEFAULT NULL,
  `rating` int(1) NOT NULL,
  `comment` text NOT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `portfolios`
CREATE TABLE `portfolios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `images` text DEFAULT NULL, -- Multiple images (JSON or comma separated)
  `live_url` varchar(255) DEFAULT NULL,
  `github_url` varchar(255) DEFAULT NULL,
  `tech_stack` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `blogs`
CREATE TABLE `blogs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL UNIQUE,
  `featured_image` varchar(255) DEFAULT NULL,
  `content` longtext DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `meetings`
CREATE TABLE `meetings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `topic` varchar(255) DEFAULT NULL,
  `meeting_date` date NOT NULL,
  `meeting_time` time NOT NULL,
  `platform` enum('Google Meet', 'Zoom', 'WhatsApp Call') NOT NULL,
  `status` enum('Pending', 'Accepted', 'Rejected', 'Rescheduled') DEFAULT 'Pending',
  `meeting_link` varchar(255) DEFAULT NULL,
  `admin_notes` text DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `meeting_slots`
CREATE TABLE `meeting_slots` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `slot_day` varchar(20) NOT NULL, -- Monday, Tuesday, etc.
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `support_tickets`
CREATE TABLE `support_tickets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `priority` enum('Low', 'Medium', 'High') DEFAULT 'Medium',
  `status` enum('Open', 'Pending', 'Closed') DEFAULT 'Open',
  `attachment` varchar(255) DEFAULT NULL,
  `internal_notes` text DEFAULT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `ticket_replies`
CREATE TABLE `ticket_replies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ticket_id` int(11) NOT NULL,
  `sender_type` enum('user', 'admin') NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`ticket_id`) REFERENCES `support_tickets`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `settings`
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `website_name` varchar(255) DEFAULT 'Dmagancy',
  `website_logo` varchar(255) DEFAULT NULL,
  `favicon` varchar(255) DEFAULT NULL,
  `footer_content` text DEFAULT NULL,
  `contact_email` varchar(100) DEFAULT NULL,
  `contact_phone` varchar(20) DEFAULT NULL,
  `whatsapp_number` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `bkash_number` varchar(20) DEFAULT NULL,
  `nagad_number` varchar(20) DEFAULT NULL,
  `rocket_number` varchar(20) DEFAULT NULL,
  `bank_details` text DEFAULT NULL,
  `paypal_link` varchar(255) DEFAULT NULL,
  `wise_link` varchar(255) DEFAULT NULL,
  `payment_qr_code` varchar(255) DEFAULT NULL,
  `payment_instructions` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `seo_settings`
CREATE TABLE `seo_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `page_name` varchar(50) NOT NULL UNIQUE, -- home, services, portfolio, blog, contact
  `meta_title` varchar(255) DEFAULT NULL,
  `meta_description` text DEFAULT NULL,
  `meta_keywords` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `social_links`
CREATE TABLE `social_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `platform` varchar(50) NOT NULL UNIQUE, -- facebook, linkedin, github, youtube, instagram, twitter, telegram
  `url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Initial data for admins
INSERT INTO `admins` (`username`, `email`, `password`) VALUES ('admin', 'admin@dmagancy.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'); -- password: password

-- Initial data for SEO settings
INSERT INTO `seo_settings` (`page_name`, `meta_title`, `meta_description`, `meta_keywords`) VALUES
('home', 'Dmagancy | Professional Web Developer', 'Get professional web development services at Dmagancy.', 'web development, freelance, php, mysql'),
('services', 'Our Services | Dmagancy', 'Explore our wide range of web development services.', 'services, web design, development'),
('portfolio', 'Our Portfolio | Dmagancy', 'Check out our latest projects and success stories.', 'portfolio, projects, showcase'),
('blog', 'Blog | Dmagancy', 'Read our latest articles and web development tips.', 'blog, tech tips, web dev'),
('contact', 'Contact Us | Dmagancy', 'Get in touch with us for your next project.', 'contact, hire me');

-- Initial data for social links
INSERT INTO `social_links` (`platform`, `url`) VALUES
('facebook', '#'),
('linkedin', '#'),
('github', '#'),
('youtube', '#'),
('instagram', '#'),
('twitter', '#'),
('telegram', '#');

-- Initial data for settings
INSERT INTO `settings` (`website_name`) VALUES ('Dmagancy');

-- Indexes for performance
CREATE INDEX idx_service_status ON services(status);
CREATE INDEX idx_service_featured ON services(featured);
CREATE INDEX idx_order_user ON orders(user_id);
CREATE INDEX idx_order_status ON orders(status);
CREATE INDEX idx_ticket_user ON support_tickets(user_id);
CREATE INDEX idx_meeting_user ON meetings(user_id);

COMMIT;
