-- ============================================================
-- CRITICAL DATABASE ENHANCEMENTS FOR NURSERY APP
-- Priority: HIGH - Apply these first
-- ============================================================
-- Generated: 2025-12-14
-- Database: nursery_app
-- ============================================================

-- ====================
-- 1. ADD CHECK CONSTRAINTS FOR DATA VALIDATION
-- ====================

-- Ensure ratings are between 1 and 5
ALTER TABLE `reviews`
ADD CONSTRAINT `check_rating_range`
CHECK (`rating` >= 1 AND `rating` <= 5);

-- Ensure product prices are positive
ALTER TABLE `products`
ADD CONSTRAINT `check_product_price_positive`
CHECK (`price` > 0);

-- Ensure sale price is not greater than regular price
ALTER TABLE `products`
ADD CONSTRAINT `check_product_sale_price`
CHECK (`sale_price` IS NULL OR `sale_price` < `price`);

-- Ensure plant prices are positive
ALTER TABLE `plants`
ADD CONSTRAINT `check_plant_price_positive`
CHECK (`price` > 0);

-- Ensure plant sale price is valid
ALTER TABLE `plants`
ADD CONSTRAINT `check_plant_sale_price`
CHECK (`sale_price` IS NULL OR `sale_price` < `price`);

-- Ensure order amounts are non-negative
ALTER TABLE `orders`
ADD CONSTRAINT `check_order_tax_amount`
CHECK (`tax_amount` >= 0);

ALTER TABLE `orders`
ADD CONSTRAINT `check_order_shipping_amount`
CHECK (`shipping_amount` >= 0);

ALTER TABLE `orders`
ADD CONSTRAINT `check_order_total_amount`
CHECK (`total_amount` >= 0);

-- Ensure gift card balance doesn't exceed initial value
ALTER TABLE `gift_cards`
ADD CONSTRAINT `check_gift_card_balance`
CHECK (`current_balance` <= `initial_value` AND `current_balance` >= 0);

-- Ensure gift card initial value is positive
ALTER TABLE `gift_cards`
ADD CONSTRAINT `check_gift_card_initial_value`
CHECK (`initial_value` > 0);

-- Ensure quantity in cart is positive
ALTER TABLE `cart_items`
ADD CONSTRAINT `check_cart_quantity_positive`
CHECK (`quantity` > 0);

-- Ensure quantity in orders is positive
ALTER TABLE `order_items`
ADD CONSTRAINT `check_order_item_quantity_positive`
CHECK (`quantity` > 0);

-- Ensure vendor commission rate is valid (0-100%)
ALTER TABLE `vendors`
ADD CONSTRAINT `check_vendor_commission_rate`
CHECK (`commission_rate` >= 0 AND `commission_rate` <= 100);

-- ====================
-- 2. ADD MISSING UNIQUE CONSTRAINTS
-- ====================

-- Prevent duplicate price alerts per user-product combination
ALTER TABLE `price_alerts`
ADD CONSTRAINT `unique_user_product_alert`
UNIQUE (`user_id`, `product_id`);

-- Prevent duplicate gift card usage per order
ALTER TABLE `gift_card_usages`
ADD CONSTRAINT `unique_gift_card_order`
UNIQUE (`gift_card_id`, `order_id`);

-- Ensure one vendor account per user
ALTER TABLE `vendors`
ADD CONSTRAINT `unique_vendor_user`
UNIQUE (`user_id`);

-- Ensure gift card codes are unique
ALTER TABLE `gift_cards`
ADD CONSTRAINT `unique_gift_card_code`
UNIQUE (`code`);

-- Ensure voucher codes are unique
ALTER TABLE `vouchers`
ADD CONSTRAINT `unique_voucher_code`
UNIQUE (`code`);

-- ====================
-- 3. ADD MISSING FOREIGN KEYS
-- ====================

-- Add foreign key for reviews.user_id (CRITICAL - was missing!)
ALTER TABLE `reviews`
ADD CONSTRAINT `fk_reviews_user_id`
FOREIGN KEY (`user_id`)
REFERENCES `users` (`id`)
ON DELETE CASCADE
ON UPDATE CASCADE;

-- Add foreign key for comments.post_id
ALTER TABLE `comments`
ADD CONSTRAINT `fk_comments_post_id`
FOREIGN KEY (`post_id`)
REFERENCES `posts` (`id`)
ON DELETE CASCADE
ON UPDATE CASCADE;

-- Add foreign key for comments.user_id
ALTER TABLE `comments`
ADD CONSTRAINT `fk_comments_user_id`
FOREIGN KEY (`user_id`)
REFERENCES `users` (`id`)
ON DELETE CASCADE
ON UPDATE CASCADE;

-- Add foreign key for likes.post_id
ALTER TABLE `likes`
ADD CONSTRAINT `fk_likes_post_id`
FOREIGN KEY (`post_id`)
REFERENCES `posts` (`id`)
ON DELETE CASCADE
ON UPDATE CASCADE;

-- Add foreign key for likes.user_id
ALTER TABLE `likes`
ADD CONSTRAINT `fk_likes_user_id`
FOREIGN KEY (`user_id`)
REFERENCES `users` (`id`)
ON DELETE CASCADE
ON UPDATE CASCADE;

-- ====================
-- 4. ADD CRITICAL INDEXES FOR PERFORMANCE
-- ====================

-- Indexes for features table (NO INDEXES AT ALL!)
CREATE INDEX `idx_features_is_active` ON `features` (`is_active`);
CREATE INDEX `idx_features_order` ON `features` (`order`);
CREATE INDEX `idx_features_active_order` ON `features` (`is_active`, `order`);

-- Indexes for testimonials table (NO INDEXES AT ALL!)
CREATE INDEX `idx_testimonials_is_active` ON `testimonials` (`is_active`);
CREATE INDEX `idx_testimonials_order` ON `testimonials` (`order`);
CREATE INDEX `idx_testimonials_rating` ON `testimonials` (`rating`);
CREATE INDEX `idx_testimonials_active_order` ON `testimonials` (`is_active`, `order`);

-- Index for filtering published posts
CREATE INDEX `idx_posts_is_published` ON `posts` (`is_published`);
CREATE INDEX `idx_posts_published_at` ON `posts` (`published_at`);
CREATE INDEX `idx_posts_published_combo` ON `posts` (`is_published`, `published_at`);

-- Index for gift card lookups and expiry checks
CREATE INDEX `idx_gift_cards_is_active` ON `gift_cards` (`is_active`);
CREATE INDEX `idx_gift_cards_expires_at` ON `gift_cards` (`expires_at`);
CREATE INDEX `idx_gift_cards_active_expires` ON `gift_cards` (`is_active`, `expires_at`);

-- Index for voucher lookups
CREATE INDEX `idx_vouchers_is_active` ON `vouchers` (`is_active`);
CREATE INDEX `idx_vouchers_expires_at` ON `vouchers` (`expires_at`);
CREATE INDEX `idx_vouchers_active_expires` ON `vouchers` (`is_active`, `expires_at`);

-- Index for vendor status filtering
CREATE INDEX `idx_vendors_status` ON `vendors` (`status`);

-- Index for health log status
CREATE INDEX `idx_health_logs_status` ON `health_logs` (`status`);
CREATE INDEX `idx_health_logs_created_at` ON `health_logs` (`created_at`);

-- Index for system settings grouping
CREATE INDEX `idx_system_settings_group` ON `system_settings` (`group`);

-- Index for reviews verification and featured status
CREATE INDEX `idx_reviews_is_verified` ON `reviews` (`is_verified_purchase`);
CREATE INDEX `idx_reviews_is_featured` ON `reviews` (`is_featured`);

-- ====================
-- END OF CRITICAL FIXES
-- ====================

-- NOTES:
-- 1. Apply these in a test environment first
-- 2. Some constraints may fail if existing data violates them
-- 3. Clean up invalid data before applying constraints
-- 4. These changes will improve data integrity and query performance
-- 5. Estimated execution time: 2-5 minutes depending on data volume
