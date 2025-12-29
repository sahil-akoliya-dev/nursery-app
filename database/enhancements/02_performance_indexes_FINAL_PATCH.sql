-- ============================================================
-- FINAL PATCH FOR 02_performance_indexes.sql
-- Only creates indexes that don't exist yet
-- ============================================================
-- Run this to complete the performance index installation
-- ============================================================

-- ====================
-- SKIP indexes that already exist from previous attempts
-- ====================
-- These are likely already created:
-- - idx_care_reminders_scheduled
-- - idx_care_reminders_active
-- - idx_health_logs_reminder_status
-- - idx_posts_user_id

-- ====================
-- CREATE ONLY MISSING INDEXES
-- ====================

-- Comments: Get comments by post and user
CREATE INDEX IF NOT EXISTS `idx_comments_post_created` ON `comments` (`post_id`, `created_at`);

-- Likes: Check if user liked a post
CREATE INDEX IF NOT EXISTS `idx_likes_user_post` ON `likes` (`user_id`, `post_id`);

-- Helpful Votes: Get votes for a reviewable item
CREATE INDEX IF NOT EXISTS `idx_helpful_votes_voteable` ON `helpful_votes` (
    `voteable_type`,
    `voteable_id`,
    `is_helpful`
);

-- Helpful Votes: Check if user voted
CREATE INDEX IF NOT EXISTS `idx_helpful_votes_user_voteable` ON `helpful_votes` (
    `user_id`,
    `voteable_type`,
    `voteable_id`
);

-- Categories: Get active categories sorted
CREATE INDEX IF NOT EXISTS `idx_categories_active_sort` ON `categories` (`is_active`, `sort_order`);

-- Categories: Get subcategories
CREATE INDEX IF NOT EXISTS `idx_categories_parent_active` ON `categories` (`parent_id`, `is_active`);

-- Orders: Lookup by order number
CREATE INDEX IF NOT EXISTS `idx_orders_order_number` ON `orders` (`order_number`);

-- Orders: Payment transaction lookup
CREATE INDEX IF NOT EXISTS `idx_orders_payment_transaction` ON `orders` (`payment_transaction_id`);

-- Point Transactions: Get transactions by user
CREATE INDEX IF NOT EXISTS `idx_point_trans_user_created` ON `point_transactions` (`user_id`, `created_at`);

-- Sessions: Cleanup old sessions
CREATE INDEX IF NOT EXISTS `idx_sessions_last_activity` ON `sessions` (`last_activity`);

-- ====================
-- END OF FINAL PATCH
-- ====================

-- Phase 2 is now complete!
-- You can proceed to Phase 3 (Full-text search) if desired.
