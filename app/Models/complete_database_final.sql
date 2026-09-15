-- ============================================================
-- Vayu — COMPLETE DATABASE
-- Sab alag alag tables — proper structure
-- phpMyAdmin > trend_theory_db > SQL tab mein run karo
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET FOREIGN_KEY_CHECKS = 0;
START TRANSACTION;

-- ============================================================
-- 1. USERS — missing columns add karo (table already exists)
-- ============================================================
ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `role` enum('admin','customer') NOT NULL DEFAULT 'customer',
  ADD COLUMN IF NOT EXISTS `phone` varchar(20) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `city` varchar(100) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `state` varchar(100) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `address` text DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `pincode` varchar(10) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `profile_image` varchar(500) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `is_active` tinyint(1) NOT NULL DEFAULT 1;

-- ============================================================
-- 2. PRODUCTS
-- ============================================================
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `category_id` int UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(300) NOT NULL,
  `sku` varchar(100) DEFAULT NULL,
  `short_description` varchar(500) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT 0.00,
  `original_price` decimal(10,2) DEFAULT NULL,
  `cost_price` decimal(10,2) DEFAULT NULL,
  `stock` int NOT NULL DEFAULT 0,
  `low_stock_alert` int DEFAULT 5,
  `total_sold` int DEFAULT 0,
  `image` varchar(500) DEFAULT NULL,
  `gallery` longtext DEFAULT NULL COMMENT 'JSON array of image URLs',
  `sizes` longtext DEFAULT NULL COMMENT 'JSON array',
  `colors` longtext DEFAULT NULL COMMENT 'JSON array',
  `fabric` varchar(100) DEFAULT NULL,
  `weight` decimal(8,2) DEFAULT NULL,
  `meta_title` varchar(70) DEFAULT NULL,
  `meta_description` varchar(170) DEFAULT NULL,
  `og_image` varchar(500) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `is_featured` tinyint(1) DEFAULT 0,
  `is_new` tinyint(1) DEFAULT 0,
  `is_trending` tinyint(1) DEFAULT 0,
  `is_on_sale` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `products_slug_unique` (`slug`),
  KEY `idx_category` (`category_id`),
  KEY `idx_active` (`is_active`),
  KEY `idx_featured` (`is_featured`),
  KEY `idx_new` (`is_new`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 3. ORDERS — sirf order confirm karna
-- ============================================================
CREATE TABLE IF NOT EXISTS `orders` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `order_number` varchar(30) NOT NULL,
  `status` enum('pending','confirmed','cancelled') NOT NULL DEFAULT 'pending',
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `discount_amount` decimal(10,2) DEFAULT 0.00,
  `shipping_charge` decimal(10,2) DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `coupon_code` varchar(50) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_number_unique` (`order_number`),
  KEY `idx_user` (`user_id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 4. ORDER_ITEMS — order ke products
-- ============================================================
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_image` varchar(500) DEFAULT NULL,
  `product_sku` varchar(100) DEFAULT NULL,
  `size` varchar(20) DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `quantity` int NOT NULL DEFAULT 1,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_order` (`order_id`),
  KEY `idx_product` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 5. ORDER_ADDRESSES — shipping address
-- ============================================================
CREATE TABLE IF NOT EXISTS `order_addresses` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint UNSIGNED NOT NULL,
  `type` enum('shipping','billing') NOT NULL DEFAULT 'shipping',
  `name` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address_line` text NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `pincode` varchar(10) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_order` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 6. PAYMENTS — alag payment table
-- ============================================================
CREATE TABLE IF NOT EXISTS `payments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint UNSIGNED NOT NULL,
  `method` enum('cod','upi','card','netbanking','wallet') NOT NULL DEFAULT 'cod',
  `status` enum('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending',
  `amount` decimal(10,2) NOT NULL,
  `payment_id` varchar(100) DEFAULT NULL,
  `gateway_order_id` varchar(100) DEFAULT NULL,
  `gateway_response` text DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_order` (`order_id`),
  KEY `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 7. SHIPMENTS — tracking alag table
-- ============================================================
CREATE TABLE IF NOT EXISTS `shipments` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint UNSIGNED NOT NULL,
  `courier_name` varchar(100) DEFAULT NULL,
  `tracking_number` varchar(100) DEFAULT NULL,
  `tracking_url` varchar(500) DEFAULT NULL,
  `status` enum('pending','pickup_scheduled','picked_up','in_transit','out_for_delivery','delivered','failed') NOT NULL DEFAULT 'pending',
  `shipped_at` timestamp NULL DEFAULT NULL,
  `expected_delivery` date DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_order` (`order_id`),
  KEY `idx_tracking` (`tracking_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 8. SHIPMENT_TRACKING — har update alag row
-- ============================================================
CREATE TABLE IF NOT EXISTS `shipment_tracking` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `shipment_id` bigint UNSIGNED NOT NULL,
  `status` varchar(100) NOT NULL,
  `location` varchar(200) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `tracked_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_shipment` (`shipment_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 9. RETURNS — return request
-- ============================================================
CREATE TABLE IF NOT EXISTS `returns` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `return_number` varchar(30) NOT NULL,
  `status` enum('requested','approved','rejected','picked_up','received','refunded') NOT NULL DEFAULT 'requested',
  `reason` enum('wrong_item','damaged','not_as_described','size_issue','changed_mind','other') NOT NULL,
  `description` text DEFAULT NULL,
  `images` longtext DEFAULT NULL COMMENT 'JSON array',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `return_number_unique` (`return_number`),
  KEY `idx_order` (`order_id`),
  KEY `idx_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 10. RETURN_ITEMS — return mein kaun sa product
-- ============================================================
CREATE TABLE IF NOT EXISTS `return_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `return_id` bigint UNSIGNED NOT NULL,
  `order_item_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL DEFAULT 1,
  `reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_return` (`return_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 11. REFUNDS — refund alag table
-- ============================================================
CREATE TABLE IF NOT EXISTS `refunds` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `return_id` bigint UNSIGNED DEFAULT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `method` enum('original_payment','bank_transfer','store_credit') NOT NULL DEFAULT 'original_payment',
  `status` enum('pending','processing','completed','failed') NOT NULL DEFAULT 'pending',
  `refund_id` varchar(100) DEFAULT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `account_number` varchar(50) DEFAULT NULL,
  `ifsc_code` varchar(20) DEFAULT NULL,
  `processed_at` timestamp NULL DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_order` (`order_id`),
  KEY `idx_return` (`return_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 12. REVIEWS
-- ============================================================
CREATE TABLE IF NOT EXISTS `reviews` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `product_id` bigint UNSIGNED DEFAULT NULL,
  `reviewer_name` varchar(150) NOT NULL,
  `reviewer_image` varchar(500) DEFAULT NULL,
  `rating` tinyint(1) NOT NULL DEFAULT 5,
  `title` varchar(200) DEFAULT NULL,
  `comment` text NOT NULL,
  `product_tag` varchar(100) DEFAULT NULL,
  `review_media` longtext DEFAULT NULL COMMENT 'JSON array',
  `is_verified` tinyint(1) DEFAULT 0,
  `is_featured` tinyint(1) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `helpful_count` int DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_product` (`product_id`),
  KEY `idx_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 13. HERO_SLIDES
-- ============================================================
CREATE TABLE IF NOT EXISTS `hero_slides` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `subtitle` varchar(300) DEFAULT NULL,
  `image` varchar(500) NOT NULL,
  `mobile_image` varchar(500) DEFAULT NULL,
  `button_text` varchar(100) DEFAULT 'SHOP NOW',
  `button_link` varchar(255) DEFAULT '/shop',
  `alt_text` varchar(200) DEFAULT NULL,
  `sort_order` tinyint DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 14. TRENDING_STORIES
-- ============================================================
CREATE TABLE IF NOT EXISTS `trending_stories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `caption` text DEFAULT NULL,
  `caption_highlight` varchar(100) DEFAULT NULL,
  `image` varchar(500) NOT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `user_avatar` varchar(500) DEFAULT NULL,
  `badge` varchar(50) DEFAULT NULL,
  `badge_type` varchar(30) DEFAULT 'trending',
  `likes_count` int DEFAULT 0,
  `sort_order` tinyint DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 15. SITE_SETTINGS
-- ============================================================
CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(100) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `site_settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 16. ADDRESSES — user saved addresses
-- ============================================================
CREATE TABLE IF NOT EXISTS `addresses` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address_line` text NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `pincode` varchar(10) NOT NULL,
  `is_default` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 17. WISHLISTS
-- ============================================================
CREATE TABLE IF NOT EXISTS `wishlists` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `wishlist_user_product` (`user_id`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 18. NEWSLETTER_SUBSCRIBERS
-- ============================================================
CREATE TABLE IF NOT EXISTS `newsletter_subscribers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(191) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `newsletter_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 19. COUPONS
-- ============================================================
CREATE TABLE IF NOT EXISTS `coupons` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `code` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `type` enum('percent','flat') NOT NULL DEFAULT 'percent',
  `value` decimal(10,2) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coupons_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 20. COUPON_RULES
-- ============================================================
CREATE TABLE IF NOT EXISTS `coupon_rules` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `coupon_id` bigint UNSIGNED NOT NULL,
  `min_order_amount` decimal(10,2) DEFAULT 0.00,
  `max_discount_amount` decimal(10,2) DEFAULT NULL,
  `usage_limit_total` int DEFAULT NULL,
  `usage_limit_per_user` int DEFAULT 1,
  `valid_from` timestamp NULL DEFAULT NULL,
  `valid_until` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_coupon` (`coupon_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 21. COUPON_USAGE
-- ============================================================
CREATE TABLE IF NOT EXISTS `coupon_usage` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `coupon_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `discount_applied` decimal(10,2) NOT NULL,
  `used_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_coupon` (`coupon_id`),
  KEY `idx_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 22. COUPON_CATEGORIES
-- ============================================================
CREATE TABLE IF NOT EXISTS `coupon_categories` (
  `coupon_id` bigint UNSIGNED NOT NULL,
  `category_id` int UNSIGNED NOT NULL,
  PRIMARY KEY (`coupon_id`,`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 23. COUPON_PRODUCTS
-- ============================================================
CREATE TABLE IF NOT EXISTS `coupon_products` (
  `coupon_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`coupon_id`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 24. OFFERS
-- ============================================================
CREATE TABLE IF NOT EXISTS `offers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `type` enum('buy_x_get_y','percent_off','flat_off','free_shipping','combo') NOT NULL,
  `image` varchar(500) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 25. OFFER_RULES
-- ============================================================
CREATE TABLE IF NOT EXISTS `offer_rules` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `offer_id` bigint UNSIGNED NOT NULL,
  `buy_quantity` int DEFAULT NULL,
  `get_quantity` int DEFAULT NULL,
  `discount_value` decimal(10,2) DEFAULT NULL,
  `min_cart_amount` decimal(10,2) DEFAULT 0.00,
  `max_discount_amount` decimal(10,2) DEFAULT NULL,
  `valid_from` timestamp NULL DEFAULT NULL,
  `valid_until` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_offer` (`offer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 26. OFFER_CATEGORIES
-- ============================================================
CREATE TABLE IF NOT EXISTS `offer_categories` (
  `offer_id` bigint UNSIGNED NOT NULL,
  `category_id` int UNSIGNED NOT NULL,
  PRIMARY KEY (`offer_id`,`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 27. OFFER_PRODUCTS
-- ============================================================
CREATE TABLE IF NOT EXISTS `offer_products` (
  `offer_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`offer_id`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 28. PRODUCT_DISCOUNTS — direct product discount
-- ============================================================
CREATE TABLE IF NOT EXISTS `product_discounts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `product_id` bigint UNSIGNED NOT NULL,
  `label` varchar(100) DEFAULT NULL,
  `type` enum('percent','flat') NOT NULL DEFAULT 'percent',
  `value` decimal(10,2) NOT NULL,
  `sale_price` decimal(10,2) DEFAULT NULL,
  `valid_from` timestamp NULL DEFAULT NULL,
  `valid_until` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_product` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 29. FLASH_SALES
-- ============================================================
CREATE TABLE IF NOT EXISTS `flash_sales` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL,
  `banner_image` varchar(500) DEFAULT NULL,
  `discount_percent` decimal(5,2) NOT NULL,
  `starts_at` timestamp NOT NULL,
  `ends_at` timestamp NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 30. FLASH_SALE_PRODUCTS
-- ============================================================
CREATE TABLE IF NOT EXISTS `flash_sale_products` (
  `flash_sale_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `special_price` decimal(10,2) DEFAULT NULL,
  `stock_limit` int DEFAULT NULL,
  `sold_count` int DEFAULT 0,
  PRIMARY KEY (`flash_sale_id`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 31. REFER_CODES
-- ============================================================
CREATE TABLE IF NOT EXISTS `refer_codes` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `code` varchar(20) NOT NULL,
  `reward_type` enum('percent','flat','store_credit') DEFAULT 'flat',
  `reward_value` decimal(10,2) DEFAULT 100.00,
  `times_used` int DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `refer_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 32. STORE_CREDITS — wallet system
-- ============================================================
CREATE TABLE IF NOT EXISTS `store_credits` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `type` enum('credit','debit') NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `reference_id` bigint UNSIGNED DEFAULT NULL,
  `reference_type` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 33. TAGS
-- ============================================================
CREATE TABLE IF NOT EXISTS `tags` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `slug` varchar(120) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tags_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 34. PRODUCT_TAGS
-- ============================================================
CREATE TABLE IF NOT EXISTS `product_tags` (
  `product_id` bigint UNSIGNED NOT NULL,
  `tag_id` bigint UNSIGNED NOT NULL,
  PRIMARY KEY (`product_id`,`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- DEFAULT SITE SETTINGS
-- ============================================================
INSERT IGNORE INTO `site_settings` (`key`, `value`, `created_at`, `updated_at`) VALUES
('site_name',           'Vayu',                              NOW(), NOW()),
('site_tagline',        'Fashion That Speaks Without Saying a Word',     NOW(), NOW()),
('meta_title',          'Vayu | Premium Fashion Store India', NOW(), NOW()),
('meta_description',    'Shop latest men & women fashion. Free shipping above Rs.999.', NOW(), NOW()),
('currency_symbol',     'Rs.',                                           NOW(), NOW()),
('shipping_free_above', '999',                                           NOW(), NOW()),
('return_days',         '15',                                            NOW(), NOW()),
('phone',               '+91 XXXXX XXXXX',                               NOW(), NOW()),
('email',               'support@thetrendtheory.com',                    NOW(), NOW());

SET FOREIGN_KEY_CHECKS = 1;
COMMIT;
