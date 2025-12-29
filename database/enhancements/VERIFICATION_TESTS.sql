-- ============================================================
-- DATABASE VERIFICATION TESTS
-- Run these to confirm all enhancements are working
-- ============================================================
-- Database: nursery_app
-- Expected: Some queries should FAIL (that's good - constraints working!)
-- ============================================================

-- ====================
-- TEST 1: CHECK CONSTRAINT - Rating Range (Should FAIL)
-- ====================
-- This should fail with "CONSTRAINT `check_rating_range` failed"
-- If it fails, the constraint is working! ✅

INSERT INTO reviews (reviewable_type, reviewable_id, user_id, rating, title, content, is_approved)
VALUES ('App\\Models\\Product', 1, 1, 10, 'Test Review', 'Test content', 1);

-- Expected Result: ERROR - Rating must be between 1-5
-- If you get this error, DELETE this test data:
-- DELETE FROM reviews WHERE title = 'Test Review';

-- ====================
-- TEST 2: CHECK CONSTRAINT - Negative Price (Should FAIL)
-- ====================
-- This should fail with "CONSTRAINT `check_product_price_positive` failed"

INSERT INTO products (name, slug, description, price, stock_quantity, category_id, is_active, sku)
VALUES ('Test Product', 'test-product-xyz', 'Test', -10.00, 100, 1, 1, 'TEST-001');

-- Expected Result: ERROR - Price must be positive
-- If you get this error, DELETE this test data:
-- DELETE FROM products WHERE sku = 'TEST-001';

-- ====================
-- TEST 3: UNIQUE CONSTRAINT - Duplicate Price Alert (Should FAIL)
-- ====================
-- First, create a price alert (if none exist)
-- Then try to create a duplicate - should fail

INSERT INTO price_alerts (user_id, product_id, target_price, is_active)
VALUES (1, 1, 50.00, 1);

-- Try to insert duplicate (Should FAIL)
INSERT INTO price_alerts (user_id, product_id, target_price, is_active)
VALUES (1, 1, 45.00, 1);

-- Expected Result: ERROR - Duplicate entry for key 'unique_user_product_alert'
-- Clean up:
-- DELETE FROM price_alerts WHERE user_id = 1 AND product_id = 1;

-- ====================
-- TEST 4: FOREIGN KEY CONSTRAINT (Should FAIL)
-- ====================
-- Try to insert review for non-existent user

INSERT INTO reviews (user_id, reviewable_type, reviewable_id, rating, title, content, is_approved)
VALUES (99999, 'App\\Models\\Product', 1, 5, 'Test', 'Test', 1);

-- Expected Result: ERROR - Cannot add foreign key constraint
-- This proves the fk_reviews_user_id constraint is working!

-- ====================
-- TEST 5: FULLTEXT SEARCH (Should SUCCEED)
-- ====================
-- Search for products containing "plant"

SELECT
    id,
    name,
    MATCH(name, description) AGAINST('plant') as relevance
FROM products
WHERE MATCH(name, description) AGAINST('plant' IN NATURAL LANGUAGE MODE)
ORDER BY relevance DESC
LIMIT 10;

-- Expected Result: List of products with "plant" in name/description, sorted by relevance
-- If this works, FULLTEXT search is active! ✅

-- ====================
-- TEST 6: FULLTEXT SEARCH - Plants (Should SUCCEED)
-- ====================
-- Search plants by scientific name or common name

SELECT
    id,
    name,
    scientific_name,
    MATCH(name, scientific_name, description) AGAINST('monstera') as relevance
FROM plants
WHERE MATCH(name, scientific_name, description) AGAINST('monstera' IN NATURAL LANGUAGE MODE)
ORDER BY relevance DESC
LIMIT 10;

-- Expected Result: Plants matching "monstera"

-- ====================
-- TEST 7: FULLTEXT SEARCH - Boolean Mode (Should SUCCEED)
-- ====================
-- Search with boolean operators: +indoor -outdoor

SELECT
    id,
    name,
    price
FROM products
WHERE MATCH(name, description) AGAINST('+indoor -outdoor' IN BOOLEAN MODE)
LIMIT 10;

-- Expected Result: Products with "indoor" but NOT "outdoor"

-- ====================
-- TEST 8: INDEX USAGE VERIFICATION
-- ====================
-- Check if query uses the composite index

EXPLAIN SELECT * FROM products
WHERE category_id = 1 AND is_active = 1
ORDER BY created_at DESC
LIMIT 20;

-- Expected Result: Look for "Using index" or "Using where" in Extra column
-- Key column should show "idx_products_category_active"

-- ====================
-- TEST 9: INDEX USAGE - Orders
-- ====================

EXPLAIN SELECT * FROM orders
WHERE user_id = 1 AND status = 'completed'
ORDER BY created_at DESC;

-- Expected Result: Should use idx_orders_user_status index

-- ====================
-- TEST 10: VERIFY ALL FULLTEXT INDEXES EXIST
-- ====================

SELECT
    TABLE_NAME,
    INDEX_NAME,
    GROUP_CONCAT(COLUMN_NAME ORDER BY SEQ_IN_INDEX) as columns
FROM information_schema.STATISTICS
WHERE TABLE_SCHEMA = 'nursery_app'
  AND INDEX_TYPE = 'FULLTEXT'
GROUP BY TABLE_NAME, INDEX_NAME
ORDER BY TABLE_NAME, INDEX_NAME;

-- Expected Result: Should show all FULLTEXT indexes:
-- - ft_categories_search
-- - ft_plants_search, ft_plants_name
-- - ft_care_guides_search
-- - ft_posts_search, ft_posts_title
-- - ft_products_search
-- - ft_vendors_search

-- ====================
-- TEST 11: VERIFY UNIQUE CONSTRAINTS
-- ====================

SELECT
    TABLE_NAME,
    CONSTRAINT_NAME,
    GROUP_CONCAT(COLUMN_NAME ORDER BY ORDINAL_POSITION) as columns
FROM information_schema.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = 'nursery_app'
  AND CONSTRAINT_NAME LIKE 'unique_%'
GROUP BY TABLE_NAME, CONSTRAINT_NAME
ORDER BY TABLE_NAME, CONSTRAINT_NAME;

-- Expected Result: Should show:
-- - unique_gift_card_code
-- - unique_gift_card_order
-- - unique_user_product_alert
-- - unique_vendor_user
-- - unique_voucher_code

-- ====================
-- TEST 12: VERIFY FOREIGN KEYS
-- ====================

SELECT
    CONSTRAINT_NAME,
    TABLE_NAME,
    COLUMN_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME
FROM information_schema.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = 'nursery_app'
  AND CONSTRAINT_NAME LIKE 'fk_%'
ORDER BY TABLE_NAME, CONSTRAINT_NAME;

-- Expected Result: Should show our new foreign keys:
-- - fk_reviews_user_id
-- - fk_comments_post_id, fk_comments_user_id
-- - fk_likes_post_id, fk_likes_user_id

-- ====================
-- TEST 13: COUNT ALL INDEXES
-- ====================

SELECT
    TABLE_NAME,
    COUNT(DISTINCT INDEX_NAME) as index_count,
    GROUP_CONCAT(DISTINCT INDEX_TYPE) as index_types
FROM information_schema.STATISTICS
WHERE TABLE_SCHEMA = 'nursery_app'
  AND INDEX_NAME != 'PRIMARY'
GROUP BY TABLE_NAME
ORDER BY index_count DESC;

-- Expected Result: Should show tables with many indexes
-- products, plants, orders should have 10+ indexes each

-- ====================
-- TEST 14: CHECK CONSTRAINTS VERIFICATION
-- ====================
-- Note: CHECK constraints don't always show in information_schema
-- The best way to verify is to try violating them (see tests 1-2 above)

-- Try this query to see if your MariaDB version shows CHECK constraints:
SELECT
    TABLE_NAME,
    CONSTRAINT_NAME,
    CHECK_CLAUSE
FROM information_schema.CHECK_CONSTRAINTS
WHERE CONSTRAINT_SCHEMA = 'nursery_app'
ORDER BY TABLE_NAME, CONSTRAINT_NAME;

-- Expected Result: May show CHECK constraints if supported by your MariaDB version
-- If empty, constraints still work (see tests 1-2)

-- ====================
-- TEST 15: PERFORMANCE TEST - Before/After
-- ====================
-- Enable profiling
SET profiling = 1;

-- Query WITHOUT using FULLTEXT (slow)
SELECT * FROM products
WHERE name LIKE '%plant%' OR description LIKE '%plant%'
LIMIT 10;

-- Query WITH FULLTEXT (fast)
SELECT * FROM products
WHERE MATCH(name, description) AGAINST('plant' IN NATURAL LANGUAGE MODE)
LIMIT 10;

-- Show timing
SHOW PROFILES;

-- Expected Result: FULLTEXT query should be significantly faster (10-100x)
-- Disable profiling
SET profiling = 0;

-- ====================
-- TEST 16: VERIFY CRITICAL INDEXES ON FEATURES
-- ====================

SHOW INDEX FROM features;

-- Expected Result: Should show:
-- - idx_features_is_active
-- - idx_features_order
-- - idx_features_active_order

-- ====================
-- TEST 17: VERIFY TESTIMONIALS INDEXES
-- ====================

SHOW INDEX FROM testimonials;

-- Expected Result: Should show:
-- - idx_testimonials_is_active
-- - idx_testimonials_order
-- - idx_testimonials_rating
-- - idx_testimonials_active_order

-- ====================
-- VERIFICATION SUMMARY
-- ====================
-- Run all tests above and check:
-- ✅ Tests 1-4 should FAIL (constraints working)
-- ✅ Tests 5-7 should SUCCEED (FULLTEXT working)
-- ✅ Tests 8-9 should show index usage
-- ✅ Tests 10-17 should show all enhancements

-- If all tests pass as expected, your database is fully enhanced! 🎉

-- ====================
-- CLEANUP (Run after testing)
-- ====================
-- Clean up any test data that was successfully inserted:

-- DELETE FROM price_alerts WHERE user_id = 1 AND product_id = 1;
-- DELETE FROM reviews WHERE title = 'Test Review' OR title = 'Test';
-- DELETE FROM products WHERE sku = 'TEST-001';

-- ====================
-- END OF VERIFICATION TESTS
-- ====================
