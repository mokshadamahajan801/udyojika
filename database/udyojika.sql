-- =======================================================
-- UDYOJIKA - Women Home Entrepreneurs Marketplace
-- Database Schema for MySQL / MariaDB (XAMPP Compatible)
-- Database Name: udyojika_db
-- =======================================================

CREATE DATABASE IF NOT EXISTS `udyojika_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `udyojika_db`;

-- 1. USERS & ROLES TABLE
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'seller', 'customer') NOT NULL DEFAULT 'customer',
  `phone` VARCHAR(30) DEFAULT NULL,
  `avatar` VARCHAR(255) DEFAULT NULL,
  `status` ENUM('active', 'pending', 'suspended') NOT NULL DEFAULT 'active',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4; 

-- 2. CATEGORIES TABLE
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `icon` VARCHAR(50) DEFAULT 'fa-palette',
  `image` VARCHAR(255) DEFAULT NULL,
  `product_count` INT DEFAULT 0,
  `description` TEXT,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. SELLERS / BUSINESSES TABLE
DROP TABLE IF EXISTS `sellers`;
CREATE TABLE `sellers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT DEFAULT NULL,
  `business_name` VARCHAR(150) NOT NULL,
  `owner_name` VARCHAR(150) NOT NULL,
  `category` VARCHAR(100) NOT NULL,
  `location` VARCHAR(100) NOT NULL,
  `rating` DECIMAL(3,2) DEFAULT 5.00,
  `review_count` INT DEFAULT 0,
  `product_count` INT DEFAULT 0,
  `avatar` VARCHAR(255) DEFAULT NULL,
  `banner_image` VARCHAR(255) DEFAULT NULL,
  `short_bio` TEXT,
  `full_story` TEXT,
  `specialty` VARCHAR(255) DEFAULT NULL,
  `joined_year` VARCHAR(10) DEFAULT '2023',
  `whatsapp` VARCHAR(30) DEFAULT NULL,
  `email` VARCHAR(150) DEFAULT NULL,
  `address` TEXT,
  `is_verified` TINYINT(1) DEFAULT 1,
  `status` ENUM('active', 'pending', 'suspended') DEFAULT 'active',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4. PRODUCTS TABLE
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL UNIQUE,
  `category_id` INT DEFAULT NULL,
  `category_name` VARCHAR(100) DEFAULT NULL,
  `seller_id` INT NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `original_price` DECIMAL(10,2) DEFAULT NULL,
  `unit` VARCHAR(80) DEFAULT 'piece',
  `rating` DECIMAL(3,2) DEFAULT 5.00,
  `review_count` INT DEFAULT 0,
  `badge` VARCHAR(50) DEFAULT 'Handmade',
  `in_stock` TINYINT(1) DEFAULT 1,
  `stock_quantity` INT DEFAULT 20,
  `images` TEXT,
  `description` TEXT,
  `features` TEXT,
  `ingredients` TEXT,
  `prep_time` VARCHAR(100) DEFAULT 'Freshly prepared daily',
  `is_featured` TINYINT(1) DEFAULT 0,
  `status` ENUM('active', 'inactive', 'draft') DEFAULT 'active',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5. ORDERS TABLE
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_number` VARCHAR(50) NOT NULL UNIQUE,
  `customer_id` INT NOT NULL,
  `customer_name` VARCHAR(150) NOT NULL,
  `customer_email` VARCHAR(150) NOT NULL,
  `customer_phone` VARCHAR(30) NOT NULL,
  `shipping_address` TEXT NOT NULL,
  `payment_method` VARCHAR(50) DEFAULT 'UPI',
  `payment_status` ENUM('Paid', 'Pending', 'Failed') DEFAULT 'Paid',
  `order_status` ENUM('pending', 'processing', 'completed', 'cancelled') DEFAULT 'pending',
  `subtotal` DECIMAL(10,2) NOT NULL,
  `shipping_fee` DECIMAL(10,2) DEFAULT 0.00,
  `discount` DECIMAL(10,2) DEFAULT 0.00,
  `total_amount` DECIMAL(10,2) NOT NULL,
  `notes` TEXT,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6. ORDER ITEMS TABLE
DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `product_name` VARCHAR(255) NOT NULL,
  `seller_id` INT NOT NULL,
  `seller_name` VARCHAR(150) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `quantity` INT NOT NULL DEFAULT 1,
  `subtotal` DECIMAL(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 7. REVIEWS TABLE
DROP TABLE IF EXISTS `reviews`;
CREATE TABLE `reviews` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT NOT NULL,
  `product_name` VARCHAR(255) NOT NULL,
  `seller_id` INT NOT NULL,
  `seller_name` VARCHAR(150) NOT NULL,
  `customer_id` INT NOT NULL,
  `customer_name` VARCHAR(150) NOT NULL,
  `rating` INT NOT NULL DEFAULT 5,
  `review_text` TEXT NOT NULL,
  `status` ENUM('approved', 'pending', 'rejected') DEFAULT 'approved',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 8. SELLER ONBOARDING REQUESTS TABLE
DROP TABLE IF EXISTS `seller_requests`;
CREATE TABLE `seller_requests` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `full_name` VARCHAR(150) NOT NULL,
  `business_name` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(30) NOT NULL,
  `city` VARCHAR(100) NOT NULL,
  `category` VARCHAR(100) NOT NULL,
  `description` TEXT,
  `sample_products` TEXT,
  `status` ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 9. CUSTOMER & BULK ENQUIRIES TABLE
DROP TABLE IF EXISTS `enquiries`;
CREATE TABLE `enquiries` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `sender_id` INT DEFAULT 0,
  `sender_name` VARCHAR(150) NOT NULL,
  `sender_email` VARCHAR(150) NOT NULL,
  `sender_phone` VARCHAR(30) DEFAULT NULL,
  `recipient_type` ENUM('admin', 'seller') DEFAULT 'seller',
  `seller_id` INT DEFAULT 0,
  `seller_name` VARCHAR(150) DEFAULT NULL,
  `subject` VARCHAR(255) NOT NULL,
  `message` TEXT NOT NULL,
  `reply` TEXT,
  `status` ENUM('unread', 'replied', 'closed') DEFAULT 'unread',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 10. CUSTOMER SAVED ADDRESSES TABLE
DROP TABLE IF EXISTS `addresses`;
CREATE TABLE `addresses` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL,
  `title` VARCHAR(50) DEFAULT 'Home',
  `full_name` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(30) NOT NULL,
  `address_line1` TEXT NOT NULL,
  `address_line2` TEXT,
  `city` VARCHAR(100) NOT NULL,
  `state` VARCHAR(100) NOT NULL,
  `pincode` VARCHAR(20) NOT NULL,
  `is_default` TINYINT(1) DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- PASSWORD RESETS TABLE
DROP TABLE IF EXISTS `password_resets`;

CREATE TABLE `password_resets` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `email` VARCHAR(150) NOT NULL,
  `otp` VARCHAR(10) NOT NULL,
  `expires_at` DATETIME NOT NULL,
  `verified` TINYINT(1) DEFAULT 0,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- -- -------------------------------------------------------------
-- -- SAMPLE SEED DATA
-- -- -------------------------------------------------------------

-- INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `phone`, `avatar`, `status`) VALUES
-- (1, 'Dr. Aruna Deshmukh', 'admin@udyojika.in', 'admin123', 'admin', '+91 98220 99999', 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=300&auto=format&fit=crop', 'active'),
-- (2, 'Sunita Kulkarni', 'sunita@annapurnaswaad.in', 'seller123', 'seller', '+91 98220 12345', 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=300&auto=format&fit=crop', 'active'),
-- (3, 'Ananya Sengupta', 'ananya@mrittikaclay.in', 'seller123', 'seller', '+91 98301 12345', 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=300&auto=format&fit=crop', 'active'),
-- (4, 'Radha Deshmukh', 'radha@sugandham.in', 'seller123', 'seller', '+91 98223 54321', 'https://images.unsplash.com/photo-1580489944761-15a19d654956?q=80&w=300&auto=format&fit=crop', 'active'),
-- (101, 'Priya Patil', 'priya@example.com', 'customer123', 'customer', '+91 98765 43210', 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?q=80&w=300&auto=format&fit=crop', 'active'),
-- (102, 'Aditi Sharma', 'aditi@example.com', 'customer123', 'customer', '+91 98234 56789', 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=300&auto=format&fit=crop', 'active');

-- INSERT INTO `categories` (`id`, `name`, `slug`, `icon`, `image`, `product_count`, `description`) VALUES
-- (1, 'Homemade Food', 'homemade-food', 'fa-utensils', 'https://images.unsplash.com/photo-1599488615731-7e5c2823ff28?q=80&w=600&auto=format&fit=crop', 142, 'Authentic regional snacks, pure ghee sweets, traditional pickles, sun-dried papads, and secret family recipe spice blends.'),
-- (2, 'Fashion & Clothing', 'fashion-clothing', 'fa-shirt', 'https://images.unsplash.com/photo-1610030469983-98e550d6193c?q=80&w=600&auto=format&fit=crop', 88, 'Handloom cotton kurtas, custom-stitched saree blouses, block printed dupattas, and handcrafted sustainable ethnic wear.'),
-- (3, 'Jewellery & Accessories', 'jewellery-accessories', 'fa-gem', 'https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?q=80&w=600&auto=format&fit=crop', 115, 'Eco-friendly terracotta jewellery sets, silk thread bangles, oxidized silver jhumkas, and handcrafted bridal hair accessories.'),
-- (4, 'Handicrafts & Decor', 'handicrafts-decor', 'fa-palette', 'https://images.unsplash.com/photo-1513519245088-0e12902e5a38?q=80&w=600&auto=format&fit=crop', 94, 'Hand-painted terracotta diyas, Lippan art wall plates, macrame hangings, brass artifacts, and festive torans.'),
-- (5, 'Natural Candles & Aromas', 'candles-aromas', 'fa-fire-flame-curved', 'https://images.unsplash.com/photo-1603006905003-be475563bc59?q=80&w=600&auto=format&fit=crop', 67, 'Hand-poured 100% soy wax candles, organic essential oils, aromatic dhoop cones, and soothing wax melts.'),
-- (6, 'Organic Beauty & Soaps', 'beauty-wellness', 'fa-spa', 'https://images.unsplash.com/photo-1608248597359-0098e7228807?q=80&w=600&auto=format&fit=crop', 53, 'Cold-processed goat milk soaps, herbal hair growth oils, Ayurvedic ubtan packs, and chemical-free lip balms.');

-- INSERT INTO `sellers` (`id`, `user_id`, `business_name`, `owner_name`, `category`, `location`, `rating`, `review_count`, `product_count`, `avatar`, `banner_image`, `short_bio`, `full_story`, `specialty`, `joined_year`, `whatsapp`, `email`, `address`, `is_verified`, `status`) VALUES
-- (1, 2, 'Annapurna Swaad', 'Sunita Kulkarni', 'Homemade Food', 'Pune, Maharashtra', 4.90, 184, 16, 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=300&auto=format&fit=crop', 'https://images.unsplash.com/photo-1599488615731-7e5c2823ff28?q=80&w=800&auto=format&fit=crop', 'Carrying forward 40-year-old Maharashtrian heirloom recipes with pure Gir cow ghee and zero preservatives.', 'Sunita started Annapurna Swaad from her kitchen in Pune in 2021.', 'Maharashtrian Festive Faral & Poha Chivda', '2021', '+919822012345', 'sunita@annapurnaswaad.in', 'Lane 4, Prabhat Road, Erandwane, Pune - 411004', 1, 'active'),
-- (2, 3, 'Mrittika Clay Art', 'Ananya Sengupta', 'Jewellery & Accessories', 'Kolkata, West Bengal', 4.80, 142, 22, 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?q=80&w=300&auto=format&fit=crop', 'https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?q=80&w=800&auto=format&fit=crop', 'Hand-sculpted terracotta jewellery & decorative pottery inspired by Bengal rural heritage.', 'Ananya turned her balcony into a pottery workshop.', 'Terracotta Choker Sets & Festive Diyas', '2022', '+919830112345', 'ananya@mrittikaclay.in', '24/B Lake Gardens, Kolkata - 700045', 1, 'active'),
-- (3, 4, 'Sugandham Fragrance', 'Radha Deshmukh', 'Natural Candles & Aromas', 'Nashik, Maharashtra', 5.00, 96, 12, 'https://images.unsplash.com/photo-1580489944761-15a19d654956?q=80&w=300&auto=format&fit=crop', 'https://images.unsplash.com/photo-1603006905003-be475563bc59?q=80&w=800&auto=format&fit=crop', 'Hand-poured 100% natural soy wax candles infused with Indian floral essential oils.', 'Radha researched organic soy wax and pure flower distillates.', 'Mogra & Mysore Sandalwood Aromatherapy Melts', '2022', '+919822354321', 'radha@sugandham.in', 'Plot 8, Gangapur Road, Nashik - 422013', 1, 'active');
