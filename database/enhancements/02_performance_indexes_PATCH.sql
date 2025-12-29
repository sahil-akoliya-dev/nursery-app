-- ============================================================
-- PATCH FOR 02_performance_indexes.sql
-- Run this to complete the performance index installation
-- ============================================================
-- Issue: Column name was wrong (next_due_date → scheduled_date)
-- ============================================================

-- Plant Care Reminders: Get upcoming reminders (CORRECTED)
CREATE INDEX `idx_care_reminders_scheduled` ON `plant_care_reminders` (`scheduled_date`);

-- Plant Care Reminders: Filter active reminders (CORRECTED)
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

-- Posts: Filter by category
CREATE INDEX `idx_posts_category_id` ON `posts` (`category_id`);

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
-- END OF PATCH
-- ====================
