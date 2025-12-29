-- ============================================================
-- FULLTEXT SEARCH INDEXES FOR NURSERY APP
-- Priority: LOW - Apply after other optimizations
-- ============================================================
-- Generated: 2025-12-14
-- Database: nursery_app
-- Purpose: Enable fast full-text search capabilities
-- ============================================================

-- ====================
-- WHAT ARE FULLTEXT INDEXES?
-- ====================
-- FULLTEXT indexes enable fast, natural language searching in text columns.
-- Benefits:
--   - Much faster than LIKE '%keyword%' queries
--   - Supports natural language search queries
--   - Relevance ranking of search results
--   - Boolean search mode for complex queries
--
-- Requirements:
--   - Only works with InnoDB (MySQL 5.6+) or MyISAM engines
--   - Works on CHAR, VARCHAR, and TEXT columns
--   - Minimum word length: 4 characters (default, configurable)
--
-- Example Usage After Creation:
--   SELECT * FROM products
--   WHERE MATCH(name, description) AGAINST('indoor plant' IN NATURAL LANGUAGE MODE);
--
--   SELECT * FROM products
--   WHERE MATCH(name, description) AGAINST('+succulent -cactus' IN BOOLEAN MODE);
-- ====================

-- ====================
-- 1. FULLTEXT INDEX FOR PRODUCTS
-- ====================

-- Enable searching products by name and description
ALTER TABLE `products`
ADD FULLTEXT INDEX `ft_products_search` (`name`, `description`);

-- Usage example:
-- SELECT *, MATCH(name, description) AGAINST('organic fertilizer') as relevance
-- FROM products
-- WHERE MATCH(name, description) AGAINST('organic fertilizer' IN NATURAL LANGUAGE MODE)
-- ORDER BY relevance DESC;

-- ====================
-- 2. FULLTEXT INDEX FOR PLANTS
-- ====================

-- Enable searching plants by name, scientific name, and description
ALTER TABLE `plants`
ADD FULLTEXT INDEX `ft_plants_search` (`name`, `scientific_name`, `description`);

-- Separate index for just plant names (faster for name-only searches)
ALTER TABLE `plants`
ADD FULLTEXT INDEX `ft_plants_name` (`name`);

-- Usage example:
-- SELECT *, MATCH(name, scientific_name, description) AGAINST('monstera') as relevance
-- FROM plants
-- WHERE MATCH(name, scientific_name, description) AGAINST('monstera' IN NATURAL LANGUAGE MODE)
-- ORDER BY relevance DESC;

-- ====================
-- 3. FULLTEXT INDEX FOR BLOG POSTS
-- ====================

-- Enable searching posts by title and content
ALTER TABLE `posts`
ADD FULLTEXT INDEX `ft_posts_search` (`title`, `content`);

-- Separate index for just titles (faster for title-only searches)
ALTER TABLE `posts`
ADD FULLTEXT INDEX `ft_posts_title` (`title`);

-- Usage example:
-- SELECT *, MATCH(title, content) AGAINST('plant care tips') as relevance
-- FROM posts
-- WHERE is_published = 1
--   AND MATCH(title, content) AGAINST('plant care tips' IN NATURAL LANGUAGE MODE)
-- ORDER BY relevance DESC, published_at DESC;

-- ====================
-- 4. FULLTEXT INDEX FOR PLANT CARE GUIDES
-- ====================

-- Enable searching care guides (using actual column names)
ALTER TABLE `plant_care_guides`
ADD FULLTEXT INDEX `ft_care_guides_search` (`title`, `description`, `light_requirements`, `water_needs`, `soil_type`);

-- Usage example:
-- SELECT * FROM plant_care_guides
-- WHERE MATCH(title, description, light_requirements, water_needs, soil_type)
--       AGAINST('indirect light' IN NATURAL LANGUAGE MODE);

-- ====================
-- 5. FULLTEXT INDEX FOR CATEGORIES
-- ====================

-- Enable searching categories by name and description
ALTER TABLE `categories`
ADD FULLTEXT INDEX `ft_categories_search` (`name`, `description`);

-- Usage example:
-- SELECT * FROM categories
-- WHERE MATCH(name, description) AGAINST('outdoor garden' IN NATURAL LANGUAGE MODE);

-- ====================
-- 6. FULLTEXT INDEX FOR VENDORS (if vendor descriptions exist)
-- ====================

-- Enable searching vendors by store name and description
ALTER TABLE `vendors`
ADD FULLTEXT INDEX `ft_vendors_search` (`store_name`, `description`);

-- Usage example:
-- SELECT * FROM vendors
-- WHERE status = 'active'
--   AND MATCH(store_name, description) AGAINST('organic nursery' IN NATURAL LANGUAGE MODE);

-- ====================
-- ADVANCED FULLTEXT SEARCH EXAMPLES
-- ====================

-- Example 1: Boolean Mode Search (Products)
-- Find products with "indoor" but not "cactus"
/*
SELECT * FROM products
WHERE MATCH(name, description) AGAINST('+indoor -cactus' IN BOOLEAN MODE)
  AND is_active = 1;
*/

-- Example 2: Natural Language with Relevance Scoring
-- Find most relevant plants for "low light indoor"
/*
SELECT
    p.*,
    MATCH(name, scientific_name, description) AGAINST('low light indoor') as relevance
FROM plants p
WHERE MATCH(name, scientific_name, description) AGAINST('low light indoor' IN NATURAL LANGUAGE MODE)
  AND is_active = 1
ORDER BY relevance DESC, name ASC
LIMIT 10;
*/

-- Example 3: Combined with Regular Filters
-- Search products in specific category with price range
/*
SELECT
    p.*,
    MATCH(name, description) AGAINST('fertilizer') as relevance
FROM products p
WHERE MATCH(name, description) AGAINST('fertilizer' IN NATURAL LANGUAGE MODE)
  AND category_id = 8
  AND price BETWEEN 10 AND 50
  AND is_active = 1
ORDER BY relevance DESC;
*/

-- Example 4: Multi-table Search (Products and Plants)
-- Search across both products and plants
/*
(SELECT 'product' as type, id, name, description, price,
        MATCH(name, description) AGAINST('monstera') as relevance
 FROM products
 WHERE MATCH(name, description) AGAINST('monstera' IN NATURAL LANGUAGE MODE))
UNION
(SELECT 'plant' as type, id, name, description, price,
        MATCH(name, scientific_name, description) AGAINST('monstera') as relevance
 FROM plants
 WHERE MATCH(name, scientific_name, description) AGAINST('monstera' IN NATURAL LANGUAGE MODE))
ORDER BY relevance DESC
LIMIT 20;
*/

-- ====================
-- PERFORMANCE CONSIDERATIONS
-- ====================

-- 1. FULLTEXT indexes can be large (20-50% of table size)
-- 2. Rebuild indexes periodically for optimal performance:
--    OPTIMIZE TABLE products, plants, posts;
--
-- 3. Check index usage:
--    SHOW INDEX FROM products;
--
-- 4. Monitor query performance:
--    EXPLAIN SELECT * FROM products
--    WHERE MATCH(name, description) AGAINST('search term');

-- ====================
-- CONFIGURATION OPTIONS (Optional)
-- ====================

-- Check current minimum word length:
-- SHOW VARIABLES LIKE 'ft_min_word_len';

-- To change minimum word length (requires MySQL restart):
-- Add to my.cnf or my.ini:
-- [mysqld]
-- ft_min_word_len = 3

-- Then rebuild FULLTEXT indexes:
-- REPAIR TABLE products QUICK;

-- ====================
-- MAINTENANCE QUERIES
-- ====================

-- Check FULLTEXT index size
/*
SELECT
    TABLE_NAME,
    INDEX_NAME,
    ROUND(STAT_VALUE * @@innodb_page_size / 1024 / 1024, 2) AS 'Size (MB)'
FROM mysql.innodb_index_stats
WHERE TABLE_NAME IN ('products', 'plants', 'posts', 'categories', 'vendors', 'plant_care_guides')
  AND INDEX_NAME LIKE 'ft_%'
ORDER BY TABLE_NAME, INDEX_NAME;
*/

-- ====================
-- END OF FULLTEXT SEARCH INDEXES
-- ====================

-- IMPORTANT NOTES:
-- 1. Test search queries before deploying to production
-- 2. FULLTEXT search requires minimum 4-character words by default
-- 3. Words in stopword list (a, an, the, etc.) are ignored
-- 4. Boolean mode supports: +word (must include), -word (must exclude), "exact phrase"
-- 5. Consider using a dedicated search solution (Elasticsearch, Algolia) for very large datasets
-- 6. Estimated execution time: 3-8 minutes depending on data volume
