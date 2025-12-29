-- ============================================================
-- PERFORMANCE INDEXES FOR NURSERY APP
-- Priority: MEDIUM - Apply after critical fixes
-- ============================================================
-- Generated: 2025-12-14
-- Database: nursery_app
-- Purpose: Improve query performance for common operations
-- ============================================================

-- ====================
-- 1. COMPOSITE INDEXES FOR COMMON QUERIES
-- ====================

-- Products: Filter by category and active status
CREATE INDEX `idx_products_category_active` ON `products` (`category_id`, `is_active`);

-- Products: Filter by active status and featured products
CREATE INDEX `idx_products_active_featured` ON `products` (`is_active`, `is_featured`);

-- Plants: Filter by category and active status
CREATE INDEX `idx_plants_category_active` ON `plants` (`category_id`, `is_active`);

-- Plants: Filter by active status and featured plants
CREATE INDEX `idx_plants_active_featured` ON `plants` (`is_active`, `is_featured`);

-- Reviews: Get approved reviews for a specific item
CREATE INDEX `idx_reviews_reviewable_approved` ON `reviews` (
    `reviewable_type`,
    `reviewable_id`,
    `is_approved`
);

-- Reviews: Get approved reviews by rating
CREATE INDEX `idx_reviews_approved_rating` ON `reviews` (
    `is_approved`,
    `rating`,
    `created_at`
);

-- Orders: Get user orders by status
CREATE INDEX `idx_orders_user_status` ON `orders` (`user_id`, `status`);

-- Orders: Get user orders by payment status
CREATE INDEX `idx_orders_user_payment_status` ON `orders` (`user_id`, `payment_status`);

-- Orders: Filter orders by date range and status
CREATE INDEX `idx_orders_status_created` ON `orders` (`status`, `created_at`);

-- Loyalty Points: Get user points with expiry
CREATE INDEX `idx_loyalty_points_user_expires` ON `loyalty_points` (`user_id`, `expires_at`);

-- Loyalty Points: Filter by type
CREATE INDEX `idx_loyalty_points_type` ON `loyalty_points` (`type`);

-- Price Alerts: Get active, untriggered alerts
CREATE INDEX `idx_price_alerts_active_triggered` ON `price_alerts` (`is_active`, `is_triggered`);

-- ====================
-- 2. INDEXES FOR ACTIVITY & AUDIT LOGS
-- ====================

-- Activity Log: Filter by subject
CREATE INDEX `idx_activity_log_subject` ON `activity_log` (
    `subject_type`,
    `subject_id`,
    `created_at`
);

-- Activity Log: Filter by causer (who performed the action)
CREATE INDEX `idx_activity_log_causer` ON `activity_log` (
    `causer_type`,
    `causer_id`,
    `created_at`
);

-- Activity Log: Time-based queries
CREATE INDEX `idx_activity_log_created_at` ON `activity_log` (`created_at`);

-- Activity Log: Filter by log name and event
CREATE INDEX `idx_activity_log_name_event` ON `activity_log` (`log_name`, `event`);

-- Audit Logs: Filter by model type and ID
CREATE INDEX `idx_audit_logs_model` ON `audit_logs` (
    `model_type`,
    `model_id`,
    `created_at`
);

-- Audit Logs: Filter by user
CREATE INDEX `idx_audit_logs_user_action` ON `audit_logs` (`user_id`, `action`, `created_at`);

-- Audit Logs: Security analysis by IP
CREATE INDEX `idx_audit_logs_ip_address` ON `audit_logs` (`ip_address`);

-- Audit Logs: Time-based queries
CREATE INDEX `idx_audit_logs_created_at` ON `audit_logs` (`created_at`);

-- ====================
-- 3. INDEXES FOR ADDRESS & USER DATA
-- ====================

-- Addresses: Filter by type (shipping/billing)
CREATE INDEX `idx_addresses_type` ON `addresses` (`type`);

-- Addresses: Filter by country for regional queries
CREATE INDEX `idx_addresses_country` ON `addresses` (`country`);

-- Addresses: Get default addresses
CREATE INDEX `idx_addresses_user_default` ON `addresses` (`user_id`, `type`, `is_default`);

-- Users: Search by role
CREATE INDEX `idx_users_role` ON `users` (`role`);

-- Users: Filter active users
CREATE INDEX `idx_users_email_verified` ON `users` (`email_verified_at`);

-- ====================
-- 4. INDEXES FOR VENDOR OPERATIONS
-- ====================

-- Vendor Transactions: Filter by vendor and type
CREATE INDEX `idx_vendor_trans_vendor_type` ON `vendor_transactions` (`vendor_id`, `type`);

-- Vendor Transactions: Filter by status
CREATE INDEX `idx_vendor_trans_status` ON `vendor_transactions` (`status`);

-- Vendor Transactions: Time-based queries
CREATE INDEX `idx_vendor_trans_created_at` ON `vendor_transactions` (`created_at`);

-- Vendor Transactions: Filter by order
CREATE INDEX `idx_vendor_trans_order_id` ON `vendor_transactions` (`order_id`);

-- Products: Filter by vendor
CREATE INDEX `idx_products_vendor` ON `products` (`vendor_id`);

-- ====================
-- 5. INDEXES FOR CART & WISHLIST
-- ====================

-- Cart Items: Get cart items by item type
CREATE INDEX `idx_cart_items_type` ON `cart_items` (`item_type`, `item_id`);

-- Wishlist Items: Get wishlist items by item type
CREATE INDEX `idx_wishlist_items_type` ON `wishlist_items` (`item_type`, `item_id`);

-- ====================
-- 6. INDEXES FOR PLANT CARE SYSTEM
-- ====================

-- Plant Care Reminders: Get reminders by plant
CREATE INDEX `idx_care_reminders_plant_id` ON `plant_care_reminders` (`plant_id`);

-- Plant Care Reminders: Get upcoming reminders
CREATE INDEX `idx_care_reminders_scheduled` ON `plant_care_reminders` (`scheduled_date`);

-- Plant Care Reminders: Filter active reminders
CREATE INDEX `idx_care_reminders_active` ON `plant_care_reminders` (`is_active`, `scheduled_date`);

-- Health Logs: Get logs by reminder
CREATE INDEX `idx_health_logs_reminder_status` ON `health_logs` (
    `plant_care_reminder_id`,
    `status`,
    `created_at`
);

-- ====================
-- 7. INDEXES FOR COMMUNITY FEATURES
-- ====================

-- Posts: Filter by user
CREATE INDEX `idx_posts_user_id` ON `posts` (`user_id`);

-- NOTE: Posts table does not have category_id column - SKIPPED

-- Comments: Get comments by post and user
CREATE INDEX `idx_comments_post_created` ON `comments` (`post_id`, `created_at`);

-- Likes: Check if user liked a post
CREATE INDEX `idx_likes_user_post` ON `likes` (`user_id`, `post_id`);

-- Helpful Votes: Get votes for a reviewable item
CREATE INDEX `idx_helpful_votes_voteable` ON `helpful_votes` (
    `voteable_type`,
    `voteable_id`,
    `is_helpful`
);

-- Helpful Votes: Check if user voted
CREATE INDEX `idx_helpful_votes_user_voteable` ON `helpful_votes` (
    `user_id`,
    `voteable_type`,
    `voteable_id`
);

-- ====================
-- 8. INDEXES FOR CATEGORIES
-- ====================

-- Categories: Get active categories sorted
CREATE INDEX `idx_categories_active_sort` ON `categories` (`is_active`, `sort_order`);

-- Categories: Get subcategories
CREATE INDEX `idx_categories_parent_active` ON `categories` (`parent_id`, `is_active`);

-- ====================
-- 9. ADDITIONAL LOOKUP INDEXES
-- ====================

-- Orders: Lookup by order number (if not already indexed)
CREATE INDEX `idx_orders_order_number` ON `orders` (`order_number`);

-- Orders: Payment transaction lookup
CREATE INDEX `idx_orders_payment_transaction` ON `orders` (`payment_transaction_id`);

-- Point Transactions: Get transactions by user
CREATE INDEX `idx_point_trans_user_created` ON `point_transactions` (`user_id`, `created_at`);

-- Sessions: Cleanup old sessions
CREATE INDEX `idx_sessions_last_activity` ON `sessions` (`last_activity`);

-- ====================
-- END OF PERFORMANCE INDEXES
-- ====================

-- PERFORMANCE NOTES:
-- 1. These indexes significantly improve query performance
-- 2. They use ~5-10% additional disk space
-- 3. Slight overhead on INSERT/UPDATE operations (negligible)
-- 4. Most beneficial for read-heavy applications
-- 5. Estimated execution time: 5-10 minutes depending on data volume
-- 6. Monitor query performance before/after to measure impact

-- MAINTENANCE:
-- Regularly run OPTIMIZE TABLE to maintain index efficiency
-- Example: OPTIMIZE TABLE products, plants, orders, reviews;
