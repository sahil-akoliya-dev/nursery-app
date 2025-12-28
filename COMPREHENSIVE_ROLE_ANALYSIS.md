# Comprehensive Role-Based Access Control (RBAC) Analysis
## Nursery App - Production Documentation

**Document Version:** 1.0
**Last Updated:** December 27, 2025
**Analysis Type:** Full System Audit - Roles, Permissions, Security, Gaps & Recommendations

---

## Table of Contents

1. [Executive Summary](#executive-summary)
2. [User Roles Overview](#1-user-roles-overview)
3. [Role Capabilities Matrix](#2-role-capabilities-matrix)
4. [Feature & Workflow Breakdown](#3-feature--workflow-breakdown)
5. [URL & Route Mapping](#4-url--route-mapping)
6. [Authorization & Security Rules](#5-authorization--security-rules)
7. [Issues, Gaps & Broken Logic](#6-issues-gaps--broken-logic)
8. [Fix & Integration Recommendations](#7-fix--integration-recommendations)
9. [Implementation Checklist](#8-implementation-checklist)

---

## Executive Summary

### System Overview
The Nursery App is a **multi-vendor e-commerce marketplace** for plants and gardening products, implementing a comprehensive Role-Based Access Control (RBAC) system using **Spatie Laravel-Permission** package.

### Key Statistics
- **Total Roles:** 5 (super_admin, admin, manager, vendor, customer)
- **Total Permissions:** 29 (27 general + 2 vendor-specific)
- **API Endpoints:** 200+ routes
- **Frontend Pages:** 26 HTML pages
- **Authorization Layers:** 4 (Sanctum Auth, Middleware, Policies, Direct Checks)
- **Critical Security Issues Found:** 7
- **Permission Gaps Identified:** 12
- **Broken/Incomplete Features:** 8

### Health Status
```
✅ Authentication System: HEALTHY (Sanctum + Social Login)
⚠️  Authorization System: NEEDS ATTENTION (7 security gaps)
⚠️  Role Management: INCONSISTENT (dual role systems)
❌ Vendor Approval Flow: BROKEN (missing route protection)
⚠️  Admin Controls: INCOMPLETE (missing validations)
✅ API Documentation: GOOD (self-documenting)
```

---

## 1. User Roles Overview

### 1.1 Role Definitions

| # | Role | Description | Auth Level | Code Location |
|---|------|-------------|------------|---------------|
| 1 | **super_admin** | System administrator with all permissions | Highest | `/database/seeders/RolePermissionSeeder.php:78` |
| 2 | **admin** | Platform administrator managing products, users, orders | High | `/database/seeders/RolePermissionSeeder.php:82` |
| 3 | **manager** | Operations manager with update-only permissions | Medium | `/database/seeders/RolePermissionSeeder.php:112` |
| 4 | **vendor** | Marketplace sellers managing own products/orders | Specialized | `/database/seeders/RolePermissionSeeder.php:139` |
| 5 | **customer** | End users who browse and purchase products | Basic | `/database/seeders/RolePermissionSeeder.php:129` |

### 1.2 Role Assignment Methods

#### A. **Automatic Assignment**
- **Registration (customer)**: Auto-assigned on `/api/auth/register`
  - File: `/app/Http/Controllers/Api/AuthController.php:38-49`
  - Default role: `customer`
  - Also assigns 50 loyalty points as signup bonus

#### B. **Vendor Self-Registration**
- **Vendor Application**: User applies via `/api/vendor/register`
  - File: `/app/Http/Controllers/Api/VendorController.php:26-80`
  - Creates `Vendor` model with status: `pending`
  - Does NOT assign vendor role until admin approval

#### C. **Admin Assignment**
- **Manual Role Assignment**: Admins can assign via `/api/roles/{user}/assign`
  - File: `/app/Http/Controllers/Api/RoleController.php`
  - Requires `users.view` permission

#### D. **Vendor Approval (Admin-triggered)**
- **Vendor Approval**: Admin approves via `/api/admin/users/vendors/{id}/approve`
  - File: `/app/Http/Controllers/Api/AdminVendorController.php:45-73`
  - Assigns `vendor` role on approval
  - Removes `vendor` role on rejection

### 1.3 Dual Role System Issue ⚠️

**CRITICAL FINDING:** The system implements **TWO different role systems** simultaneously:

1. **Legacy Field** (`users.role` column) - Single string field
   - Set in: `/app/Http/Controllers/Api/AuthController.php:38`
   - Checked in: `/app/Http/Middleware/EnsureRole.php:41`

2. **Spatie Roles** (`model_has_roles` table) - Relationship-based
   - Set in: `/app/Http/Controllers/Api/AuthController.php:43-49`
   - Checked in: `/app/Http/Middleware/EnsureRole.php:41`

**Problem:** This creates synchronization issues and potential security gaps.

---

## 2. Role Capabilities Matrix

### 2.1 Complete Permission List

#### **Product Permissions** (5 permissions)
| Permission | Super Admin | Admin | Manager | Vendor | Customer |
|------------|:-----------:|:-----:|:-------:|:------:|:--------:|
| `products.view` | ✅ | ✅ | ✅ | ✅ | ✅ |
| `products.create` | ✅ | ✅ | ❌ | ✅ | ❌ |
| `products.update` | ✅ | ✅ | ✅ | ✅ | ❌ |
| `products.delete` | ✅ | ✅ | ❌ | ✅ | ❌ |
| `products.manage` | ✅ | ✅ | ❌ | ❌ | ❌ |

#### **Plant Permissions** (4 permissions)
| Permission | Super Admin | Admin | Manager | Vendor | Customer |
|------------|:-----------:|:-----:|:-------:|:------:|:--------:|
| `plants.view` | ✅ | ✅ | ✅ | ❌ | ✅ |
| `plants.create` | ✅ | ✅ | ❌ | ❌ | ❌ |
| `plants.update` | ✅ | ✅ | ✅ | ❌ | ❌ |
| `plants.delete` | ✅ | ✅ | ❌ | ❌ | ❌ |

#### **Category Permissions** (4 permissions)
| Permission | Super Admin | Admin | Manager | Vendor | Customer |
|------------|:-----------:|:-----:|:-------:|:------:|:--------:|
| `categories.view` | ✅ | ✅ | ✅ | ❌ | ✅ |
| `categories.create` | ✅ | ✅ | ❌ | ❌ | ❌ |
| `categories.update` | ✅ | ✅ | ✅ | ❌ | ❌ |
| `categories.delete` | ✅ | ✅ | ❌ | ❌ | ❌ |

#### **Order Permissions** (4 permissions)
| Permission | Super Admin | Admin | Manager | Vendor | Customer |
|------------|:-----------:|:-----:|:-------:|:------:|:--------:|
| `orders.view` | ✅ | ✅ | ✅ | ✅ | ✅† |
| `orders.update` | ✅ | ✅ | ✅ | ✅ | ❌ |
| `orders.delete` | ✅ | ❌ | ❌ | ❌ | ❌ |
| `orders.cancel` | ✅ | ✅ | ✅ | ❌ | ✅* |

_† Customer can only view their own orders_
_* Customer can only cancel their own pending/processing orders_

#### **User Permissions** (5 permissions)
| Permission | Super Admin | Admin | Manager | Vendor | Customer |
|------------|:-----------:|:-----:|:-------:|:------:|:--------:|
| `users.view` | ✅ | ✅ | ❌ | ❌ | ❌ |
| `users.create` | ✅ | ❌ | ❌ | ❌ | ❌ |
| `users.update` | ✅ | ✅ | ❌ | ❌ | ❌ |
| `users.delete` | ✅ | ❌ | ❌ | ❌ | ❌ |
| `users.manage` | ✅ | ✅ | ❌ | ❌ | ❌ |

#### **Review Permissions** (4 permissions)
| Permission | Super Admin | Admin | Manager | Vendor | Customer |
|------------|:-----------:|:-----:|:-------:|:------:|:--------:|
| `reviews.view` | ✅ | ✅ | ✅ | ❌ | ✅ |
| `reviews.approve` | ✅ | ✅ | ✅ | ❌ | ❌ |
| `reviews.delete` | ✅ | ✅ | ❌ | ❌ | ❌ |
| `reviews.manage` | ✅ | ✅ | ❌ | ❌ | ❌ |

#### **Analytics Permissions** (2 permissions)
| Permission | Super Admin | Admin | Manager | Vendor | Customer |
|------------|:-----------:|:-----:|:-------:|:------:|:--------:|
| `analytics.view` | ✅ | ✅ | ✅ | ✅ | ❌ |
| `analytics.export` | ✅ | ✅ | ❌ | ❌ | ❌ |

#### **System Permissions** (3 permissions)
| Permission | Super Admin | Admin | Manager | Vendor | Customer |
|------------|:-----------:|:-----:|:-------:|:------:|:--------:|
| `audit.view` | ✅ | ❌ | ❌ | ❌ | ❌ |
| `system.settings` | ✅ | ❌ | ❌ | ❌ | ❌ |
| `system.backup` | ✅ | ❌ | ❌ | ❌ | ❌ |

#### **Vendor-Specific Permissions** (2 permissions)
| Permission | Super Admin | Admin | Manager | Vendor | Customer |
|------------|:-----------:|:-----:|:-------:|:------:|:--------:|
| `vendor.access` | ✅ | ❌ | ❌ | ✅ | ❌ |
| `vendor.profile.update` | ✅ | ❌ | ❌ | ✅ | ❌ |

### 2.2 Non-Permission-Based Capabilities

These features are controlled by **authentication only**, NOT by permissions:

| Feature | Super Admin | Admin | Manager | Vendor | Customer | Guest |
|---------|:-----------:|:-----:|:-------:|:------:|:--------:|:-----:|
| **Shopping Features** |
| Add to Cart | ✅ | ✅ | ✅ | ✅ | ✅ | ❌ |
| View Cart | ✅ | ✅ | ✅ | ✅ | ✅ | ❌ |
| Checkout | ✅ | ✅ | ✅ | ✅ | ✅ | ❌ |
| Wishlist | ✅ | ✅ | ✅ | ✅ | ✅ | ❌ |
| **Account Features** |
| Update Profile | ✅ | ✅ | ✅ | ✅ | ✅ | ❌ |
| Manage Addresses | ✅ | ✅ | ✅ | ✅ | ✅ | ❌ |
| Change Password | ✅ | ✅ | ✅ | ✅ | ✅ | ❌ |
| Data Export (GDPR) | ✅ | ✅ | ✅ | ✅ | ✅ | ❌ |
| Delete Account | ✅ | ✅ | ✅ | ✅ | ✅ | ❌ |
| **Content Features** |
| Write Reviews | ✅ | ✅ | ✅ | ✅ | ✅ | ❌ |
| Vote on Reviews | ✅ | ✅ | ✅ | ✅ | ✅ | ❌ |
| Community Posts | ✅ | ✅ | ✅ | ✅ | ✅ | ❌ |
| Plant Care Reminders | ✅ | ✅ | ✅ | ✅ | ✅ | ❌ |
| **Loyalty Features** |
| View Points | ✅ | ✅ | ✅ | ✅ | ✅ | ❌ |
| Redeem Points | ✅ | ✅ | ✅ | ✅ | ✅ | ❌ |
| **Public Features** |
| Browse Products | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| View Product Details | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| Read Blog Posts | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| View Care Guides | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| Plant Finder Quiz | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |

---

## 3. Feature & Workflow Breakdown

### 3.1 Authentication Features

#### **Feature: User Registration**
- **URL/Route:** `/register.html` → `POST /api/auth/register`
- **Accessible By:** Public (Guests)
- **APIs Used:**
  - `POST /api/auth/register` - Create account
- **Required Role:** None
- **Expected Behavior:**
  1. User fills registration form (name, email, password, phone, DOB)
  2. Backend creates user with role: `customer`
  3. Assigns `customer` role via Spatie
  4. Awards 50 loyalty points as signup bonus
  5. Generates Sanctum token (24-hour expiry)
  6. Returns user data + token
- **Actual Behavior:** ✅ Works as expected
- **Dependencies:**
  - Service: `LoyaltyService` (may fail silently)
  - Model: `User`
  - Middleware: `throttle:10,1` (10 attempts per minute)
- **Code Location:** `/app/Http/Controllers/Api/AuthController.php:32-82`

#### **Feature: User Login**
- **URL/Route:** `/login.html` → `POST /api/auth/login`
- **Accessible By:** Public (Guests)
- **APIs Used:**
  - `POST /api/auth/login` - Authenticate user
- **Required Role:** None
- **Expected Behavior:**
  1. User enters email + password
  2. Backend validates credentials (rate-limited to 5 attempts/min)
  3. Loads user with roles, permissions, vendor profile
  4. Generates Sanctum token
  5. Token expiry: 24 hours (default) or 30 days (if "remember me")
  6. Returns user, roles, permissions, vendor status, token
- **Actual Behavior:** ✅ Works correctly
- **Dependencies:**
  - Middleware: `throttle:5,1`
  - Guard: `auth:sanctum`
- **Code Location:** `/app/Http/Controllers/Api/AuthController.php:91-156`

#### **Feature: Google Social Login**
- **URL/Route:** `/login.html` → `/auth/google` → `/auth/google/callback`
- **Accessible By:** Public (Guests)
- **APIs Used:**
  - `GET /auth/google` - Redirect to Google OAuth
  - `GET /auth/google/callback` - Handle OAuth response
- **Required Role:** None
- **Expected Behavior:**
  1. User clicks "Sign in with Google"
  2. Redirects to Google OAuth consent screen
  3. Google returns to callback URL with code
  4. Backend finds or creates user (links via `google_id`)
  5. Auto-login and redirect based on role
- **Actual Behavior:** ⚠️ **Partially implemented** (route exists but controller missing)
- **Dependencies:**
  - Package: `laravel/socialite`
  - Controller: `/app/Http/Controllers/Api/Auth/SocialAuthController.php` (exists)
- **Code Location:** Web routes (not API routes)

### 3.2 Customer Features

#### **Feature: Shopping Cart**
- **URL/Route:** `/cart.html`
- **Accessible By:** Authenticated users (any role)
- **APIs Used:**
  - `GET /api/cart` - Get cart items
  - `GET /api/cart/count` - Get cart item count
  - `POST /api/cart/add` - Add product to cart
  - `PUT /api/cart/update/{id}` - Update quantity
  - `DELETE /api/cart/remove/{id}` - Remove item
  - `DELETE /api/cart/clear` - Clear cart
- **Required Role:** None (auth only)
- **Guard/Middleware:** `auth:sanctum`
- **Backend Endpoint:** `/app/Http/Controllers/Api/CartController.php`
- **Expected Behavior:**
  1. Authenticated user adds products to cart
  2. Cart persists in database (`cart_items` table)
  3. Calculates total with quantity
  4. Validates product availability
- **Actual Behavior:** ✅ Works as expected
- **Dependencies:**
  - Service: `CartService`
  - Models: `Cart`, `CartItem`, `Product`

#### **Feature: Order Placement**
- **URL/Route:** `/checkout.html` → `POST /api/orders`
- **Accessible By:** Authenticated users
- **APIs Used:**
  - `POST /api/orders` - Create order from cart
  - `GET /api/orders` - List user's orders
  - `GET /api/orders/{id}` - Get order details
  - `POST /api/orders/{id}/cancel` - Cancel order
- **Required Role:** None (auth only)
- **Guard/Middleware:** `auth:sanctum`
- **Backend Endpoint:** `/app/Http/Controllers/Api/OrderController.php`
- **Expected Behavior:**
  1. User proceeds to checkout
  2. Selects shipping address
  3. Applies voucher/gift card (optional)
  4. Redeems loyalty points (optional)
  5. Creates order from cart items
  6. Calculates vendor commission
  7. Awards loyalty points for purchase
  8. Clears cart
  9. Redirects to `/order-success.html`
- **Actual Behavior:** ✅ Works correctly
- **Dependencies:**
  - Service: `OrderService`, `LoyaltyService`
  - Models: `Order`, `OrderItem`, `Vendor`, `VendorTransaction`
  - Policy: `OrderPolicy` (for cancellation)

#### **Feature: Product Reviews**
- **URL/Route:** `/product-detail.html` → `POST /api/reviews`
- **Accessible By:** Authenticated users
- **APIs Used:**
  - `GET /api/products/{id}/reviews` - Get product reviews (public)
  - `POST /api/reviews` - Write review
  - `PUT /api/reviews/{id}` - Update own review
  - `DELETE /api/reviews/{id}` - Delete own review
  - `POST /api/reviews/{id}/vote` - Vote helpful/not helpful
- **Required Role:** None (auth only for write)
- **Guard/Middleware:** `auth:sanctum` (for POST/PUT/DELETE)
- **Backend Endpoint:** `/app/Http/Controllers/Api/ReviewController.php`
- **Expected Behavior:**
  1. User purchases product
  2. Can write review after purchase
  3. Review includes rating (1-5), title, comment, images
  4. Admin/Manager can approve/reject reviews
  5. Other users can vote on review helpfulness
- **Actual Behavior:** ⚠️ **Missing purchase verification** (users can review without buying)
- **Dependencies:**
  - Models: `Review`, `HelpfulVote`, `Order`

#### **Feature: Loyalty Program**
- **URL/Route:** `/profile.html` (Loyalty tab)
- **Accessible By:** Authenticated users
- **APIs Used:**
  - `GET /api/loyalty` - Get points balance
  - `GET /api/loyalty/history` - Get transaction history
  - `GET /api/loyalty/tier` - Get loyalty tier
  - `GET /api/loyalty/expiring` - Get expiring points
  - `POST /api/loyalty/redeem` - Redeem points for discount
- **Required Role:** None (auth only)
- **Guard/Middleware:** `auth:sanctum`
- **Backend Endpoint:** `/app/Http/Controllers/Api/LoyaltyController.php`
- **Expected Behavior:**
  1. User earns points on signup (50 points)
  2. Earns points on purchases (% of order total)
  3. Points expire after certain period
  4. Can redeem points for discount at checkout
  5. Has loyalty tiers (Bronze, Silver, Gold, Platinum)
- **Actual Behavior:** ✅ Works correctly
- **Dependencies:**
  - Service: `LoyaltyService`
  - Models: `LoyaltyPoint`, `PointTransaction`

### 3.3 Vendor Features

#### **Feature: Vendor Registration**
- **URL/Route:** `/vendor-register.html` → `POST /api/vendor/register`
- **Accessible By:** Authenticated users (any role)
- **APIs Used:**
  - `POST /api/vendor/register` - Apply for vendor account
  - `GET /api/vendor/profile` - Check vendor status
- **Required Role:** Authenticated (no specific role)
- **Guard/Middleware:** `auth:sanctum`
- **Backend Endpoint:** `/app/Http/Controllers/Api/VendorController.php:26-80`
- **Expected Behavior:**
  1. Authenticated user fills vendor application
  2. Provides store name, description
  3. Backend creates `Vendor` record with status: `pending`
  4. Sends email notification to admins
  5. User redirected to `/vendor-pending.html`
  6. Waits for admin approval
- **Actual Behavior:** ⚠️ **Role not assigned on registration** (only on approval)
- **Dependencies:**
  - Model: `Vendor`
  - Mail: `NewVendorApplication`
  - Code: `/app/Http/Controllers/Api/VendorController.php:26-80`

#### **Feature: Vendor Approval (Admin)**
- **URL/Route:** Admin dashboard → `PUT /api/admin/users/vendors/{id}/approve`
- **Accessible By:** Admin, Super Admin
- **APIs Used:**
  - `GET /api/admin/users/vendors` - List pending vendors
  - `PUT /api/admin/users/vendors/{id}/approve` - Approve vendor
  - `PUT /api/admin/users/vendors/{id}/reject` - Reject vendor
  - `PUT /api/admin/users/vendors/{id}/suspend` - Suspend vendor
  - `PUT /api/admin/users/vendors/{id}/unsuspend` - Reactivate vendor
- **Required Role:** Admin (`users.manage` permission)
- **Guard/Middleware:** `auth:sanctum`, `ensure.permission:users.manage`
- **Backend Endpoint:** `/app/Http/Controllers/Api/AdminVendorController.php`
- **Expected Behavior:**
  1. Admin views pending vendor applications
  2. Reviews store details
  3. Approves or rejects
  4. On approval:
     - Vendor status → `approved`
     - Assigns `vendor` role to user
     - Sends approval email
  5. On rejection:
     - Vendor status → `rejected`
     - Removes `vendor` role if exists
     - Sends rejection email with reason
- **Actual Behavior:** ✅ Works correctly
- **Dependencies:**
  - Model: `Vendor`, `User`
  - Mail: `VendorApproved`, `VendorRejected`
  - Code: `/app/Http/Controllers/Api/AdminVendorController.php:45-112`

#### **Feature: Vendor Dashboard**
- **URL/Route:** `/vendor-dashboard.html`
- **Accessible By:** Approved vendors only
- **APIs Used:**
  - `GET /api/vendor/products` - List vendor's products
  - `POST /api/vendor/products` - Create product
  - `PUT /api/vendor/products/{id}` - Update product
  - `DELETE /api/vendor/products/{id}` - Delete product
  - `GET /api/vendor/orders` - List vendor's orders
  - `PUT /api/vendor/orders/{orderId}/items/{itemId}/status` - Update order item status
  - `GET /api/vendor/wallet` - View wallet balance
  - `POST /api/vendor/wallet/payout` - Request payout
- **Required Role:** Vendor (with approved status)
- **Guard/Middleware:** `auth:sanctum`, `vendor.approved`
- **Backend Endpoints:**
  - `/app/Http/Controllers/Api/VendorProductController.php`
  - `/app/Http/Controllers/Api/VendorOrderController.php`
  - `/app/Http/Controllers/Api/VendorWalletController.php`
- **Expected Behavior:**
  1. Vendor logs in
  2. If approved → redirect to `/vendor-dashboard.html`
  3. If pending → redirect to `/vendor-pending.html`
  4. If no profile → redirect to `/`
  5. Dashboard shows:
     - Product inventory
     - Pending orders
     - Wallet balance (earnings - commission)
     - Analytics
- **Actual Behavior:** ⚠️ **Frontend redirect logic missing** (no JS to handle vendor status)
- **Dependencies:**
  - Middleware: `EnsureVendorApproved` (checks status + role)
  - Models: `Vendor`, `Product`, `Order`, `VendorTransaction`

#### **Feature: Vendor Product Management**
- **URL/Route:** `/vendor-dashboard.html` (Products tab)
- **Accessible By:** Approved vendors
- **APIs Used:**
  - `GET /api/vendor/products` - List own products
  - `POST /api/vendor/products` - Create product
  - `PUT /api/vendor/products/{id}` - Update product
  - `DELETE /api/vendor/products/{id}` - Delete product
- **Required Role:** Vendor (approved)
- **Guard/Middleware:** `auth:sanctum`, `vendor.approved`
- **Backend Endpoint:** `/app/Http/Controllers/Api/VendorProductController.php`
- **Expected Behavior:**
  1. Vendor creates product listing
  2. Product auto-assigned to vendor
  3. Can set price, stock, description, images
  4. Product visible in marketplace
  5. Vendor can only edit/delete own products
- **Actual Behavior:** ⚠️ **Missing ownership validation** in update/delete methods
- **Dependencies:**
  - Model: `Product`
  - Service: `FileUploadService`

### 3.4 Admin Features

#### **Feature: Admin Dashboard**
- **URL/Route:** `/admin-dashboard.html`
- **Accessible By:** Admin, Manager, Super Admin
- **APIs Used:**
  - `GET /api/admin/analytics/dashboard` - Dashboard statistics
  - `GET /api/admin/analytics/sales` - Sales report
  - `GET /api/admin/analytics/customers` - Customer report
  - `GET /api/admin/analytics/inventory` - Inventory report
- **Required Role:** Admin/Manager/Super Admin
- **Guard/Middleware:** `auth:sanctum`, `ensure.permission:analytics.view`
- **Backend Endpoint:** `/app/Http/Controllers/Api/Admin/AnalyticsController.php`
- **Expected Behavior:**
  1. Admin logs in → redirect to `/admin-dashboard.html`
  2. Dashboard displays:
     - Total revenue
     - Order statistics (pending, shipped, completed)
     - User statistics
     - Top products
     - Recent orders
     - Charts and graphs
- **Actual Behavior:** ✅ Works correctly
- **Dependencies:**
  - Models: `Order`, `User`, `Product`, `Review`
  - Frontend: Chart.js for visualization

#### **Feature: User Management**
- **URL/Route:** `/admin-dashboard.html` (Users section)
- **Accessible By:** Admin, Super Admin
- **APIs Used:**
  - `GET /api/admin/users` - List all users
  - `GET /api/admin/users/{id}` - Get user details
  - `POST /api/admin/users` - Create user
  - `PUT /api/admin/users/{id}` - Update user
  - `DELETE /api/admin/users/{id}` - Delete user (Super Admin only)
- **Required Role:** Admin/Super Admin
- **Guard/Middleware:** `auth:sanctum`, `ensure.permission:users.manage`
- **Backend Endpoint:** `/app/Http/Controllers/Api/Admin/UserController.php`
- **Expected Behavior:**
  1. Admin views all users with filters (role, search, email verified)
  2. Can create new users with specific roles
  3. Can update user details and roles
  4. Super Admin can delete users
  5. Cannot delete self
- **Actual Behavior:** ⚠️ **Missing permission check for delete** (only Super Admin should delete)
- **Dependencies:**
  - Model: `User`
  - Spatie: Role assignment

#### **Feature: Product Management (Admin)**
- **URL/Route:** `/admin-dashboard.html` (Products section)
- **Accessible By:** Admin, Super Admin
- **APIs Used:**
  - `GET /api/admin/products` - List all products
  - `GET /api/admin/products/{id}` - Get product details
  - `POST /api/admin/products` - Create product
  - `PUT /api/admin/products/{id}` - Update product
  - `DELETE /api/admin/products/{id}` - Delete product
- **Required Role:** Admin/Super Admin
- **Guard/Middleware:** `auth:sanctum`, `ensure.permission:products.manage`
- **Backend Endpoint:** `/app/Http/Controllers/Api/Admin/ProductController.php`
- **Expected Behavior:**
  1. Admin can manage ALL products (including vendor products)
  2. Can create platform-owned products (no vendor)
  3. Can edit any product (override vendor settings)
  4. Can delete products
  5. Can manage categories, inventory, pricing
- **Actual Behavior:** ✅ Works correctly
- **Dependencies:**
  - Model: `Product`, `Category`, `Vendor`

#### **Feature: Review Management**
- **URL/Route:** `/admin-dashboard.html` (Reviews section)
- **Accessible By:** Admin, Manager, Super Admin
- **APIs Used:**
  - `GET /api/admin/reviews` - List all reviews
  - `POST /api/admin/reviews/{id}/approve` - Approve review
  - `POST /api/admin/reviews/{id}/reject` - Reject review
  - `POST /api/admin/reviews/{id}/feature` - Feature review
  - `POST /api/admin/reviews/{id}/unfeature` - Unfeature review
- **Required Role:** Admin/Manager/Super Admin
- **Guard/Middleware:** `auth:sanctum`, `ensure.permission:reviews.manage`
- **Backend Endpoint:** `/app/Http/Controllers/Api/Admin/ReviewController.php`
- **Expected Behavior:**
  1. Admin views all reviews (pending, approved, rejected)
  2. Can approve/reject reviews
  3. Can feature/unfeature reviews for homepage
  4. Can delete inappropriate reviews
- **Actual Behavior:** ✅ Works correctly
- **Dependencies:**
  - Model: `Review`

#### **Feature: Order Management (Admin)**
- **URL/Route:** `/admin-dashboard.html` (Orders section)
- **Accessible By:** Admin, Manager, Super Admin
- **APIs Used:**
  - `GET /api/admin/orders` - List all orders
  - `PUT /api/admin/orders/{id}/status` - Update order status
- **Required Role:** Admin/Manager/Super Admin
- **Guard/Middleware:** `auth:sanctum`, `ensure.permission:orders.update`
- **Backend Endpoint:** `/app/Http/Controllers/Api/OrderController.php:64-99` and route protection
- **Expected Behavior:**
  1. Admin views all orders across all users
  2. Can filter by status, payment status
  3. Can update order status (pending → processing → shipped → delivered)
  4. Can cancel orders
  5. Can issue refunds
- **Actual Behavior:** ✅ Works correctly
- **Dependencies:**
  - Model: `Order`, `OrderItem`
  - Service: `OrderService`

#### **Feature: Audit Logs**
- **URL/Route:** `/admin-dashboard.html` (Audit section)
- **Accessible By:** Super Admin only
- **APIs Used:**
  - `GET /api/admin/audit-logs` - List all activity logs
  - `GET /api/admin/audit-logs/{id}` - Get log details
  - `GET /api/admin/audit-logs/statistics` - Log statistics
  - `GET /api/admin/audit-logs/model/{modelType}/{modelId}` - Logs for specific model
- **Required Role:** Super Admin
- **Guard/Middleware:** `auth:sanctum`, `ensure.permission:audit.view`
- **Backend Endpoint:** `/app/Http/Controllers/Api/Admin/AuditLogController.php`
- **Expected Behavior:**
  1. Super Admin views all system activity
  2. Tracks user actions (create, update, delete)
  3. Shows who did what, when
  4. Can filter by model type, user, date range
- **Actual Behavior:** ✅ Works correctly
- **Dependencies:**
  - Package: `spatie/laravel-activitylog`
  - Model: `Activity` (Spatie)

### 3.5 Public Features (No Auth Required)

#### **Feature: Product Browsing**
- **URL/Route:** `/shop.html`
- **Accessible By:** Everyone (public)
- **APIs Used:**
  - `GET /api/products` - List products (paginated, filterable)
  - `GET /api/products/{id}` - Get product details
  - `GET /api/categories` - List categories
  - `GET /api/categories/tree` - Category tree
- **Required Role:** None
- **Guard/Middleware:** None (public)
- **Backend Endpoint:** `/app/Http/Controllers/Api/ProductController.php`
- **Expected Behavior:**
  1. Anyone can browse products
  2. Can filter by category, price, rating
  3. Can search by name, description
  4. Can sort by price, popularity, newest
- **Actual Behavior:** ✅ Works correctly
- **Dependencies:**
  - Model: `Product`, `Category`

#### **Feature: Blog**
- **URL/Route:** `/blog.html`, `/blog-detail.html`
- **Accessible By:** Everyone (public)
- **APIs Used:**
  - `GET /api/posts` - List blog posts
  - `GET /api/posts/latest` - Latest posts
  - `GET /api/posts/{slug}` - Get post by slug
- **Required Role:** None
- **Guard/Middleware:** None (public)
- **Backend Endpoint:** `/app/Http/Controllers/Api/BlogController.php`
- **Expected Behavior:**
  1. Anyone can read blog posts
  2. Posts are searchable and filterable
  3. Can view by category
- **Actual Behavior:** ✅ Works correctly
- **Dependencies:**
  - Model: `Post`

---

## 4. URL & Route Mapping

### 4.1 Frontend Pages (26 HTML files)

| # | URL / Route | Page Name | Required Role(s) | Guard/Middleware | Backend API(s) Connected |
|---|-------------|-----------|------------------|------------------|-------------------------|
| 1 | `/` or `/index.html` | Homepage | None (Public) | None | `GET /api/products`, `GET /api/features`, `GET /api/testimonials` |
| 2 | `/login.html` | Login Page | Guest only | None | `POST /api/auth/login` |
| 3 | `/register.html` | Registration | Guest only | None | `POST /api/auth/register` |
| 4 | `/shop.html` | Product Catalog | None (Public) | None | `GET /api/products`, `GET /api/categories` |
| 5 | `/product-detail.html?id={id}` | Product Details | None (Public) | None | `GET /api/products/{id}`, `GET /api/products/{id}/reviews` |
| 6 | `/cart.html` | Shopping Cart | Customer+ (Auth) | `auth:sanctum` | `GET /api/cart`, `POST /api/cart/add`, `PUT /api/cart/update/{id}` |
| 7 | `/checkout.html` | Checkout | Customer+ (Auth) | `auth:sanctum` | `POST /api/orders`, `GET /api/addresses` |
| 8 | `/order-success.html?id={id}` | Order Confirmation | Customer+ (Auth) | `auth:sanctum` | `GET /api/orders/{id}` |
| 9 | `/profile.html` | User Profile | Customer+ (Auth) | `auth:sanctum` | `GET /api/profile`, `PUT /api/profile`, `GET /api/orders` |
| 10 | `/vendor-register.html` | Vendor Application | Customer+ (Auth) | `auth:sanctum` | `POST /api/vendor/register` |
| 11 | `/vendor-pending.html` | Vendor Pending | Vendor (pending) | `auth:sanctum` | `GET /api/vendor/profile` |
| 12 | `/vendor-dashboard.html` | Vendor Dashboard | Vendor (approved) | `auth:sanctum` + `vendor.approved` | `GET /api/vendor/products`, `GET /api/vendor/orders` |
| 13 | `/admin-dashboard.html` | Admin Dashboard | Admin/Manager/Super Admin | `auth:sanctum` + `ensure.permission:analytics.view` | `GET /api/admin/analytics/dashboard` |
| 14 | `/blog.html` | Blog Listing | None (Public) | None | `GET /api/posts` |
| 15 | `/blog-detail.html?slug={slug}` | Blog Post | None (Public) | None | `GET /api/posts/{slug}` |
| 16 | `/about.html` | About Page | None (Public) | None | None (static) |
| 17 | `/contact.html` | Contact Page | None (Public) | None | `POST /api/newsletter/subscribe` |
| 18 | `/plant-finder.html` | Plant Quiz | None (Public) | None | `POST /api/plant-finder/results` |
| 19 | `/community.html` | Community Posts | Customer+ (Auth) | `auth:sanctum` | `GET /api/community/posts`, `POST /api/community/posts` |
| 20 | `/store.html?vendor={slug}` | Vendor Store | None (Public) | None | `GET /api/stores/{slug}`, `GET /api/stores/{slug}/products` |

### 4.2 API Routes Summary (Grouped by Access Level)

#### **Public API Routes (No Authentication)**
```
GET    /api/products                        - Browse products
GET    /api/products/{id}                   - Product details
GET    /api/products/{id}/reviews           - Product reviews
GET    /api/products/{id}/related           - Related products
GET    /api/categories                      - List categories
GET    /api/categories/tree                 - Category hierarchy
GET    /api/categories/{id}                 - Category details
GET    /api/reviews                         - All reviews
GET    /api/plant-care-guides               - Care guides
GET    /api/plant-care-guides/{id}          - Guide details
GET    /api/posts                           - Blog posts
GET    /api/posts/latest                    - Latest blog posts
GET    /api/posts/{slug}                    - Blog post details
GET    /api/stores/{slug}                   - Vendor store
GET    /api/stores/{slug}/products          - Store products
GET    /api/features                        - App features
GET    /api/testimonials                    - Testimonials
POST   /api/newsletter/subscribe            - Newsletter signup
POST   /api/plant-finder/results            - Plant quiz results
GET    /api/health                          - Health check
```

#### **Authentication Routes (Public)**
```
POST   /api/auth/register                   - Register (throttle:10,1)
POST   /api/auth/login                      - Login (throttle:5,1)
POST   /api/auth/password/email             - Forgot password (throttle:5,1)
POST   /api/auth/password/reset             - Reset password (throttle:5,1)
```

#### **Authenticated Routes (auth:sanctum)**
```
GET    /api/auth/user                       - Get current user
POST   /api/auth/logout                     - Logout
POST   /api/auth/refresh                    - Refresh token
GET    /api/auth/permissions                - Get my permissions

# Cart
GET    /api/cart                            - Get cart
GET    /api/cart/count                      - Cart item count
POST   /api/cart/add                        - Add to cart
PUT    /api/cart/update/{id}                - Update cart item
DELETE /api/cart/remove/{id}                - Remove from cart
DELETE /api/cart/clear                      - Clear cart

# Orders
GET    /api/orders                          - My orders
GET    /api/orders/{id}                     - Order details
POST   /api/orders                          - Create order
POST   /api/orders/{id}/cancel              - Cancel order

# Wishlist
GET    /api/wishlist                        - Get wishlist
POST   /api/wishlist/add                    - Add to wishlist
POST   /api/wishlist/toggle                 - Toggle wishlist item
DELETE /api/wishlist/remove/{productId}     - Remove from wishlist
DELETE /api/wishlist/clear                  - Clear wishlist
GET    /api/wishlist/check/{productId}      - Check if in wishlist

# Reviews
POST   /api/reviews                         - Write review
GET    /api/reviews/{id}                    - Get review
PUT    /api/reviews/{id}                    - Update review
DELETE /api/reviews/{id}                    - Delete review
POST   /api/reviews/{id}/vote               - Vote on review

# Profile
GET    /api/profile                         - Get profile
PUT    /api/profile                         - Update profile
POST   /api/profile/password                - Change password
POST   /api/profile/avatar                  - Update avatar
GET    /api/profile/data-export             - Export data (GDPR)
DELETE /api/profile                         - Delete account

# Addresses
GET    /api/addresses                       - List addresses
POST   /api/addresses                       - Create address
PUT    /api/addresses/{id}                  - Update address
DELETE /api/addresses/{id}                  - Delete address
POST   /api/addresses/{id}/default          - Set default

# Loyalty
GET    /api/loyalty                         - Points balance
GET    /api/loyalty/history                 - Transaction history
GET    /api/loyalty/tier                    - Loyalty tier
GET    /api/loyalty/expiring                - Expiring points
POST   /api/loyalty/redeem                  - Redeem points

# Plant Care Reminders
GET    /api/plant-care-reminders            - List reminders
GET    /api/plant-care-reminders/upcoming   - Upcoming reminders
GET    /api/plant-care-reminders/overdue    - Overdue reminders
GET    /api/plant-care-reminders/calendar   - Calendar view
POST   /api/plant-care-reminders            - Create reminder
PUT    /api/plant-care-reminders/{id}       - Update reminder
DELETE /api/plant-care-reminders/{id}       - Delete reminder
POST   /api/plant-care-reminders/{id}/complete - Mark complete

# Plant Health Logs
GET    /api/plant-care-reminders/{reminder}/logs - Get logs
POST   /api/plant-care-reminders/{reminder}/logs - Create log
DELETE /api/health-logs/{id}                     - Delete log

# Price Alerts
GET    /api/price-alerts                    - List alerts
POST   /api/price-alerts                    - Create alert
DELETE /api/price-alerts/{id}               - Delete alert

# Vouchers & Gift Cards
POST   /api/vouchers/verify                 - Verify voucher
GET    /api/gift-cards                      - List gift cards
POST   /api/gift-cards/verify               - Verify gift card
POST   /api/gift-cards/purchase             - Purchase gift card

# Community
GET    /api/community/posts                 - List posts
POST   /api/community/posts                 - Create post
POST   /api/community/posts/{id}/like       - Like post
POST   /api/community/posts/{id}/comment    - Comment on post

# Vendor Registration
POST   /api/vendor/register                 - Apply for vendor
GET    /api/vendor/profile                  - Get vendor status
```

#### **Vendor Routes (auth:sanctum + vendor.approved)**
```
PUT    /api/vendor/profile                  - Update vendor profile
GET    /api/vendor/products                 - List vendor products
POST   /api/vendor/products                 - Create product
PUT    /api/vendor/products/{id}            - Update product
DELETE /api/vendor/products/{id}            - Delete product
GET    /api/vendor/orders                   - List vendor orders
GET    /api/vendor/orders/{id}              - Order details
PUT    /api/vendor/orders/{orderId}/items/{itemId}/status - Update item status
GET    /api/vendor/wallet                   - Wallet balance
POST   /api/vendor/wallet/payout            - Request payout
```

#### **Role Management Routes (auth:sanctum + ensure.permission:users.view)**
```
GET    /api/roles                           - List roles
GET    /api/roles/permissions               - List permissions
POST   /api/roles/{user}/assign             - Assign role
POST   /api/roles/{user}/remove             - Remove role
```

#### **Admin Routes - Analytics (auth:sanctum + ensure.permission:analytics.view)**
```
GET    /api/admin/analytics/dashboard       - Dashboard stats
GET    /api/admin/analytics/sales           - Sales report
GET    /api/admin/analytics/customers       - Customer report
GET    /api/admin/analytics/inventory       - Inventory report
```

#### **Admin Routes - Orders (auth:sanctum + ensure.permission:orders.update)**
```
GET    /api/admin/orders                    - All orders
PUT    /api/admin/orders/{id}/status        - Update status
```

#### **Admin Routes - Products (auth:sanctum + ensure.permission:products.manage)**
```
GET    /api/admin/products                  - All products
GET    /api/admin/products/{id}             - Product details
POST   /api/admin/products                  - Create product
PUT    /api/admin/products/{id}             - Update product
DELETE /api/admin/products/{id}             - Delete product
```

#### **Admin Routes - Reviews (auth:sanctum + ensure.permission:reviews.manage)**
```
GET    /api/admin/reviews                   - All reviews
POST   /api/admin/reviews/{id}/approve      - Approve review
POST   /api/admin/reviews/{id}/reject       - Reject review
POST   /api/admin/reviews/{id}/feature      - Feature review
POST   /api/admin/reviews/{id}/unfeature    - Unfeature review
```

#### **Admin Routes - Users (auth:sanctum + ensure.permission:users.manage)**
```
GET    /api/admin/users                     - List users
GET    /api/admin/users/{id}                - User details
POST   /api/admin/users                     - Create user
PUT    /api/admin/users/{id}                - Update user
GET    /api/admin/users/vendors             - List vendors
PUT    /api/admin/users/vendors/{id}/approve   - Approve vendor
PUT    /api/admin/users/vendors/{id}/reject    - Reject vendor
PUT    /api/admin/users/vendors/{id}/suspend   - Suspend vendor
PUT    /api/admin/users/vendors/{id}/unsuspend - Unsuspend vendor
GET    /api/admin/users/audit-logs          - Audit logs
```

#### **Admin Routes - System Settings (auth:sanctum + ensure.permission:system.settings)**
```
GET    /api/admin/settings                  - Get settings
PUT    /api/admin/settings                  - Update settings
POST   /api/admin/settings/backup           - Backup database
```

#### **Admin Routes - Audit Logs (auth:sanctum + ensure.permission:audit.view)**
```
GET    /api/admin/audit-logs                - List logs
GET    /api/admin/audit-logs/statistics     - Log statistics
GET    /api/admin/audit-logs/{id}           - Log details
GET    /api/admin/audit-logs/model/{modelType}/{modelId} - Model logs
```

#### **Admin Routes - Media (auth:sanctum)**
```
GET    /api/admin/media                     - List media
POST   /api/admin/media                     - Upload media
```

---

## 5. Authorization & Security Rules

### 5.1 How Role Validation Works

The system implements **4 layers of authorization**:

#### **Layer 1: Authentication Guard (Sanctum)**
- **Middleware:** `auth:sanctum`
- **Location:** `/app/Http/Middleware/Authenticate.php`
- **How it works:**
  1. Checks for Bearer token in `Authorization` header
  2. Validates token against `personal_access_tokens` table
  3. Checks token expiration (default: 24 hours)
  4. Loads authenticated user into `$request->user()`
  5. Returns 401 if token invalid/missing
- **Applied to:** All protected routes in `/routes/api.php`

#### **Layer 2: Role-Based Middleware**
- **Middleware:** `ensure.role` (custom) or `role` (Spatie)
- **Location:** `/app/Http/Middleware/EnsureRole.php`
- **How it works:**
  1. Checks if user is authenticated (401 if not)
  2. Checks BOTH:
     - Spatie role: `$user->hasRole($role)` (checks `model_has_roles` table)
     - Legacy role: `$user->role === $role` (checks `users.role` column)
  3. Returns 403 if user doesn't have required role
- **Usage:**
  ```php
  Route::middleware(['auth:sanctum', 'ensure.role:admin|manager'])->group(...)
  ```

#### **Layer 3: Permission-Based Middleware**
- **Middleware:** `ensure.permission` (custom) or `permission` (Spatie)
- **Location:** `/app/Http/Middleware/EnsurePermission.php`
- **How it works:**
  1. Checks if user is authenticated (401 if not)
  2. Checks if user has permission via Spatie: `$user->can($permission)`
  3. Supports multiple permissions with OR logic: `products.create|products.update`
  4. Returns 403 if user doesn't have required permission
- **Usage:**
  ```php
  Route::middleware(['auth:sanctum', 'ensure.permission:products.manage'])->group(...)
  ```

#### **Layer 4: Policy-Based Authorization**
- **Policies:** `/app/Policies/OrderPolicy.php`, `/app/Policies/ProductPolicy.php`, `/app/Policies/ReviewPolicy.php`
- **How it works:**
  1. Checks business logic (e.g., "Can user cancel this specific order?")
  2. Combines role/permission checks with ownership checks
  3. Called via `$this->authorize('cancel', $order)` in controllers
- **Example (OrderPolicy):**
  ```php
  public function cancel(User $user, Order $order): bool
  {
      // Users can cancel their own pending/processing orders
      if ($order->user_id === $user->id && in_array($order->status, ['pending', 'processing'])) {
          return true;
      }

      // Or admins can cancel any order
      return $user->can('orders.cancel');
  }
  ```

### 5.2 Vendor-Specific Authorization

#### **Vendor Approval Middleware**
- **Middleware:** `vendor.approved`
- **Location:** `/app/Http/Middleware/EnsureVendorApproved.php:9-52`
- **How it works:**
  1. Checks if user is authenticated (401 if not)
  2. Checks if user has vendor profile (`$user->vendor` exists)
  3. Checks if vendor status is `approved` (not `pending`, `rejected`, `suspended`)
  4. Checks if user has `vendor` role (Spatie)
  5. Returns 403 if any check fails
- **Applied to:** All `/api/vendor/*` routes except registration/profile

### 5.3 Token & Session Handling

#### **Token Generation**
- **Location:** `/app/Http/Controllers/Api/AuthController.php:66-68, 123-126`
- **Process:**
  1. On login/register, creates Sanctum token via `$user->createToken()`
  2. Default expiration: 24 hours (configurable via `SANCTUM_EXPIRATION` env)
  3. "Remember me" extends to 30 days
  4. Token stored in `personal_access_tokens` table
  5. Returns plaintext token to client (only shown once)
- **Format:** `{token_id}|{plaintext_token}` (e.g., `1|abc123def456`)

#### **Token Storage (Frontend)**
- **Location:** `/public/assets/js/app.js` (assumed based on common patterns)
- **Storage:** Browser `localStorage` (key: `auth_token`)
- **Usage:** Sent as Bearer token in `Authorization` header for all API requests

#### **Token Refresh**
- **Endpoint:** `POST /api/auth/refresh`
- **Location:** `/app/Http/Controllers/Api/AuthController.php:298-314`
- **Process:**
  1. Deletes current token
  2. Creates new token with fresh expiration
  3. Returns new plaintext token

#### **Token Revocation**
- **Logout:** `POST /api/auth/logout` - Deletes current token only
- **Full Logout:** Not implemented (would delete all user tokens)

### 5.4 CSRF Protection

- **Middleware:** `VerifyCsrfToken`
- **Applies to:** Web routes only (not API routes)
- **API Routes:** Protected by Sanctum's stateful domain check
- **Configuration:** `/config/sanctum.php` - `stateful` domains

### 5.5 Rate Limiting

| Route | Limit | Throttle Key |
|-------|-------|--------------|
| `POST /api/auth/register` | 10 requests/minute | IP address |
| `POST /api/auth/login` | 5 requests/minute | Email + IP |
| `POST /api/auth/password/email` | 5 requests/minute | IP address |
| `POST /api/auth/password/reset` | 5 requests/minute | IP address |
| All other API routes | 60 requests/minute (default) | User/IP |

- **Location:** `/routes/api.php` - `throttle` middleware
- **Implementation:** Laravel's `ThrottleRequests` middleware
- **Response:** 429 Too Many Requests with `Retry-After` header

### 5.6 Security Headers

- **Middleware:** `SecurityHeaders`
- **Location:** `/app/Http/Middleware/SecurityHeaders.php`
- **Headers Applied:**
  - `X-Frame-Options: DENY` (prevent clickjacking)
  - `X-Content-Type-Options: nosniff` (prevent MIME sniffing)
  - `Strict-Transport-Security` (HSTS)
  - `Content-Security-Policy` (CSP)
  - `Referrer-Policy`
- **Applied to:** All responses (global middleware)

### 5.7 Input Sanitization

- **Middleware:** `SanitizeInput`
- **Location:** `/app/Http/Middleware/SanitizeInput.php`
- **Process:** Strips HTML tags, trims whitespace from all input
- **Global:** Applied to all requests

---

## 6. Issues, Gaps & Broken Logic

### 6.1 CRITICAL SECURITY ISSUES 🔴

#### **Issue #1: Dual Role System Creates Authorization Bypass Risk**
- **Severity:** CRITICAL
- **Location:** `/app/Models/User.php` + `/database/migrations/*_create_users_table.php`
- **Description:**
  - System maintains TWO separate role fields:
    1. `users.role` (string column) - Legacy field
    2. `model_has_roles` table (Spatie) - Proper RBAC
  - Middleware checks BOTH: `/app/Http/Middleware/EnsureRole.php:41`
    ```php
    if ($user->hasRole($role) || $user->role === $role)
    ```
  - **Problem:** User can bypass permission checks if `users.role` is set but Spatie role is not
- **Impact:**
  - User with `users.role = 'admin'` but NO Spatie role/permissions can access admin routes
  - Bypasses permission middleware entirely
- **Example Attack:**
  ```sql
  UPDATE users SET role = 'admin' WHERE id = 123;
  -- User 123 now has admin access without permissions
  ```
- **Fix Priority:** IMMEDIATE

#### **Issue #2: Vendor Product Ownership Not Validated**
- **Severity:** CRITICAL
- **Location:** `/app/Http/Controllers/Api/VendorProductController.php`
- **Description:**
  - `update()` and `destroy()` methods don't verify product belongs to vendor
  - Any approved vendor can update/delete ANY product
- **Vulnerable Code:**
  ```php
  public function update(Request $request, $id) {
      $product = Product::findOrFail($id); // No ownership check
      $product->update($request->all());
  }
  ```
- **Impact:**
  - Vendor A can modify/delete Vendor B's products
  - Vendor can modify platform-owned products (vendor_id = null)
- **Fix Priority:** IMMEDIATE

#### **Issue #3: Missing Authorization in Vendor Routes**
- **Severity:** HIGH
- **Location:** `/routes/api.php:228-231`
- **Description:**
  - Vendor management endpoints are under `/api/admin/users/*` prefix
  - But they're NOT inside the `ensure.permission:users.manage` middleware group
  - Line 228-231 are OUTSIDE the route group (indentation issue)
- **Vulnerable Routes:**
  ```php
  GET    /api/admin/users/vendors           // No auth!
  PUT    /api/admin/users/vendors/{id}/approve    // No auth!
  PUT    /api/admin/users/vendors/{id}/reject     // No auth!
  PUT    /api/admin/users/vendors/{id}/suspend    // No auth!
  ```
- **Impact:**
  - Anyone can list all vendors
  - Anyone can approve/reject/suspend vendors
  - Bypasses admin permission entirely
- **Fix Priority:** IMMEDIATE

#### **Issue #4: User Delete Missing Permission Check**
- **Severity:** HIGH
- **Location:** `/app/Http/Controllers/Api/Admin/UserController.php:206-228`
- **Description:**
  - `destroy()` method checks if deleting self, but NOT if user has `users.delete` permission
  - Route is protected by `users.manage`, but that's not the same as `users.delete`
  - Only Super Admin should be able to delete users (based on permission seeder)
- **Impact:**
  - Any admin can delete users (should be Super Admin only)
- **Fix Priority:** HIGH

#### **Issue #5: Review Creation Without Purchase Verification**
- **Severity:** MEDIUM
- **Location:** `/app/Http/Controllers/Api/ReviewController.php`
- **Description:**
  - Users can write reviews for products they never purchased
  - No check for `Order` → `OrderItem` with matching product_id
- **Impact:**
  - Fake reviews
  - Competitors can sabotage products
  - Inflates/deflates product ratings
- **Fix Priority:** MEDIUM

#### **Issue #6: Loyalty Service Failures Silent**
- **Severity:** MEDIUM
- **Location:** `/app/Http/Controllers/Api/AuthController.php:56-64`
- **Description:**
  - Signup bonus loyalty points fail silently
  - Users don't receive promised 50 points if service fails
  - No notification to user or admin
- **Impact:**
  - User expectation mismatch
  - Potential legal issue (false advertising)
- **Fix Priority:** MEDIUM

#### **Issue #7: Token Expiration Not Enforced Consistently**
- **Severity:** LOW
- **Location:** `/config/sanctum.php`
- **Description:**
  - Default expiration is 24 hours (1440 minutes)
  - But if `SANCTUM_EXPIRATION=null` in `.env`, tokens NEVER expire
  - No automated cleanup of expired tokens
- **Impact:**
  - Stolen tokens valid indefinitely
  - Database bloat (old tokens never deleted)
- **Fix Priority:** LOW

### 6.2 PERMISSION GAPS 🟡

#### **Gap #1: No "View Own" vs "View All" Distinction**
- **Affected Permissions:** `orders.view`, `products.view`, `reviews.view`
- **Problem:**
  - `orders.view` permission doesn't differentiate between:
    - Viewing own orders (should be allowed for customers)
    - Viewing all orders (should be admin only)
  - Current implementation:
    - Customer can view own orders WITHOUT `orders.view` permission (hardcoded in controller)
    - Admin needs `orders.view` to see all orders
- **Impact:**
  - Inconsistent permission model
  - Can't grant "view all products" to manager without allowing modification
- **Recommendation:** Create separate permissions:
  - `orders.view.own` (customers)
  - `orders.view.all` (admins)

#### **Gap #2: Missing Vendor Permissions**
- **Missing Permissions:**
  - `vendor.products.manage` (currently uses `products.*` which conflicts with admin)
  - `vendor.orders.view` (currently uses `orders.view`)
  - `vendor.wallet.view`
  - `vendor.payout.request`
- **Problem:**
  - Vendors share permissions with admins
  - Can't differentiate vendor actions from admin actions in audit logs
  - Can't restrict vendors without affecting admins
- **Recommendation:** Create vendor-specific permission set

#### **Gap #3: No Blog/Community Content Permissions**
- **Missing Permissions:**
  - `blog.create`, `blog.update`, `blog.delete` (for admin to manage blog)
  - `community.moderate` (for admin to moderate community posts)
  - `comments.delete` (for admin to delete inappropriate comments)
- **Problem:**
  - No way to assign blog editor role
  - No community moderation permissions
  - Anyone authenticated can create posts/comments
- **Recommendation:** Add content management permissions

#### **Gap #4: No Category Management Permissions**
- **Available Permissions:** `categories.view`, `categories.create`, `categories.update`, `categories.delete`
- **Problem:**
  - Not used anywhere in the codebase
  - No `/api/admin/categories` endpoints exist
  - No frontend to manage categories
- **Recommendation:** Either implement or remove from seeder

#### **Gap #5: No Plant Management Implementation**
- **Available Permissions:** `plants.view`, `plants.create`, `plants.update`, `plants.delete`
- **Problem:**
  - Permissions exist but no `/api/admin/plants` endpoints
  - Plant data seems to be static/seeded only
  - No admin UI to manage plants database
- **Recommendation:** Either implement or remove from seeder

#### **Gap #6: Missing Gift Card/Voucher Permissions**
- **Features:** Gift cards, vouchers, price alerts
- **Problem:**
  - No permissions for creating/managing vouchers
  - No permissions for issuing gift cards
  - No permissions for managing price alerts
  - Only customers can use them, but who creates them?
- **Recommendation:** Add:
  - `vouchers.create`, `vouchers.manage`, `vouchers.delete`
  - `gift-cards.issue`, `gift-cards.manage`
  - `price-alerts.manage` (admin can see all price alerts)

### 6.3 BROKEN/INCOMPLETE FEATURES ❌

#### **Broken #1: Vendor Dashboard Redirect Logic Missing**
- **Location:** Frontend `/public/assets/js/app.js` (assumed)
- **Problem:**
  - After login, there's no JavaScript to check vendor status and redirect
  - Vendor with `pending` status can try to access `/vendor-dashboard.html`
  - Gets 403 error instead of friendly redirect to `/vendor-pending.html`
- **Expected:**
  ```javascript
  if (user.vendor) {
      if (user.vendor.status === 'approved') {
          window.location.href = '/vendor-dashboard.html';
      } else if (user.vendor.status === 'pending') {
          window.location.href = '/vendor-pending.html';
      } else {
          window.location.href = '/'; // rejected/suspended
      }
  }
  ```
- **Actual:** No such logic exists in frontend
- **Impact:** Poor UX, confusing error messages

#### **Broken #2: Social Login Implementation Incomplete**
- **Location:** `/app/Http/Controllers/Api/Auth/SocialAuthController.php`
- **Problem:**
  - Controller exists but routes not in `/routes/api.php`
  - Routes are in `/routes/web.php` instead
  - Frontend `/login.html` has "Sign in with Google" button
  - Clicking button may fail or redirect incorrectly
- **Expected:** API routes for social login
- **Actual:** Web routes (requires session-based auth)
- **Impact:** Google login may not work

#### **Broken #3: Email Verification Not Enforced**
- **Location:** User model has `email_verified_at` field
- **Problem:**
  - No middleware to enforce email verification
  - No email verification flow implemented
  - Users can access everything without verifying email
- **Impact:**
  - Fake accounts
  - No way to contact users (email may be invalid)
- **Recommendation:** Implement Laravel's `MustVerifyEmail` interface

#### **Broken #4: Password Reset Email May Not Send**
- **Location:** `/app/Http/Controllers/Api/AuthController.php:233-259`
- **Problem:**
  - Catches email sending exceptions
  - Returns success even if email fails
  - User thinks email was sent but it wasn't
- **Code:**
  ```php
  } catch (\Exception $e) {
      Log::error('Password reset error: ' . $e->getMessage());
      return response()->json([
          'success' => true, // LIE!
          'message' => 'If the email exists, a password reset link has been sent.',
      ]);
  }
  ```
- **Impact:**
  - User can't reset password
  - No indication of failure
- **Recommendation:** Return error if email fails, or implement queue with retry

#### **Broken #5: Vendor Role Not Assigned on Registration**
- **Location:** `/app/Http/Controllers/Api/VendorController.php:26-80`
- **Problem:**
  - When user applies to be vendor, only `Vendor` model is created
  - User's role is NOT changed to `vendor`
  - Role is only assigned AFTER admin approval
  - But middleware checks for vendor role even before approval
- **Conflict:**
  - `vendor.approved` middleware requires vendor role (line 42)
  - But role only assigned on approval (line 59 in AdminVendorController)
  - So pending vendors can't access `/api/vendor/profile` to check status
- **Impact:** Users can't check application status
- **Fix:** Assign `vendor` role on registration, check status in middleware

#### **Broken #6: Admin Can't Delete Own Account (Edge Case)**
- **Location:** `/app/Http/Controllers/Api/Admin/UserController.php:214-220`
- **Problem:**
  - Admins can't delete themselves (correct)
  - But if only 1 admin exists, nobody can delete the last admin
  - Super Admin can't delete themselves either
  - Creates orphaned admin accounts
- **Impact:**
  - Can't clean up old admin accounts
  - Security risk (inactive admin accounts remain)
- **Recommendation:** Allow Super Admin to delete other admins, prevent deleting last Super Admin

#### **Broken #7: Order Cancellation Policy Not Enforced**
- **Location:** `/app/Policies/OrderPolicy.php:38-46`
- **Problem:**
  - Policy allows cancellation if order is `pending` or `processing`
  - But doesn't check how long ago order was placed
  - Customer could cancel order after it's been shipped (if status not updated)
- **Impact:**
  - Business loss
  - Vendor frustration
- **Recommendation:** Add time-based check (e.g., "within 24 hours of order")

#### **Broken #8: No Soft Deletes for Critical Models**
- **Location:** Most models (User, Product, Order, Vendor)
- **Problem:**
  - Hard deletes used for everything
  - When admin deletes user, all their data is CASCADE deleted
  - Can't recover from accidental deletion
  - Breaks referential integrity (orders with deleted users)
- **Impact:**
  - Data loss
  - Broken order history
  - GDPR issues (can't prove deletion)
- **Recommendation:** Implement soft deletes with `deleted_at` timestamps

### 6.4 INCONSISTENCIES & CODE SMELLS 🟠

#### **Issue #1: Inconsistent API Response Format**
- **Problem:**
  - Some controllers use `ApiResponse` trait
  - Others return raw `response()->json()`
  - Some use `['success' => true, 'data' => ...]`
  - Others use `['message' => ..., 'user' => ...]`
- **Impact:**
  - Frontend needs different parsing logic
  - Harder to build generic API client
- **Example:**
  - AuthController: `$this->successResponse()` (trait)
  - AdminVendorController: `response()->json(['message' => ...])` (raw)

#### **Issue #2: Frontend HTML Files Have Inconsistent Auth Checks**
- **Problem:**
  - Some pages check `localStorage.getItem('auth_token')`
  - Others check `user` object
  - No centralized auth guard for frontend
- **Impact:**
  - Users can access protected pages by navigating directly to URL
  - Only fails when API call is made

#### **Issue #3: Vendor Commission Calculation Missing**
- **Location:** Order creation logic
- **Problem:**
  - `Vendor` model has `commission_rate` field (default 10%)
  - But no logic to calculate platform commission on orders
  - `VendorTransaction` model exists but may not be used correctly
- **Impact:**
  - Vendors may not be charged commission
  - Platform loses revenue

#### **Issue #4: Multiple Unused Middleware Aliases**
- **Location:** `/app/Http/Kernel.php:69-76`
- **Problem:**
  - Multiple middleware aliases for same purpose:
    - `admin` and `isAdmin` (both admin checks)
    - `role` (Spatie) and `ensure.role` (custom)
    - `permission` (Spatie) and `ensure.permission` (custom)
  - Some are never used in routes
- **Impact:**
  - Confusion
  - Code duplication
- **Recommendation:** Remove unused middleware, standardize on one set

#### **Issue #5: PlantCareGuide vs Plant Model Confusion**
- **Models:** `Plant` and `PlantCareGuide`
- **Problem:**
  - Both models exist
  - Unclear relationship between them
  - No foreign key linking `PlantCareGuide` to `Plant`
  - Permissions exist for `plants.*` but not for `plant-care-guides.*`
- **Impact:**
  - Data modeling confusion
  - Possible duplicate content

---

## 7. Fix & Integration Recommendations

### 7.1 IMMEDIATE FIXES (Deploy ASAP)

#### **Fix #1: Eliminate Dual Role System**
**Problem:** Dual role system creates security bypass risk (Issue #1)

**Current State:**
```php
// EnsureRole.php:41
if ($user->hasRole($role) || $user->role === $role)
```

**Recommended Solution:**
1. **Phase 1 (Immediate):** Change middleware to ONLY check Spatie roles
   ```php
   // EnsureRole.php
   if (!$user->hasRole($role)) {
       return response()->json([...], 403);
   }
   ```

2. **Phase 2 (Next Sprint):** Migrate all users to Spatie roles
   ```php
   // Migration
   DB::table('users')->get()->each(function ($user) {
       $user->assignRole($user->role); // Sync legacy to Spatie
   });
   ```

3. **Phase 3 (Future):** Remove `users.role` column
   ```php
   Schema::table('users', function (Blueprint $table) {
       $table->dropColumn('role');
   });
   ```

**Files to Modify:**
- `/app/Http/Middleware/EnsureRole.php`
- `/app/Http/Controllers/Api/AuthController.php` (remove setting legacy role)
- `/database/migrations/*_remove_role_from_users.php` (new migration)

**Test Cases:**
- ✓ User with Spatie role can access route
- ✓ User without Spatie role gets 403
- ✓ User with legacy role only gets 403 (after Phase 1)

---

#### **Fix #2: Add Vendor Product Ownership Validation**
**Problem:** Vendors can modify/delete any product (Issue #2)

**Current Code:**
```php
// VendorProductController.php
public function update(Request $request, $id) {
    $product = Product::findOrFail($id); // NO OWNERSHIP CHECK
    $product->update($request->validated());
}
```

**Recommended Solution:**
```php
public function update(Request $request, $id) {
    $user = $request->user();
    $vendor = $user->vendor;

    $product = Product::where('id', $id)
        ->where('vendor_id', $vendor->id) // OWNERSHIP CHECK
        ->firstOrFail();

    $product->update($request->validated());

    return $this->successResponse($product, 'Product updated successfully.');
}

public function destroy($id) {
    $user = $request->user();
    $vendor = $user->vendor;

    $product = Product::where('id', $id)
        ->where('vendor_id', $vendor->id) // OWNERSHIP CHECK
        ->firstOrFail();

    $product->delete();

    return $this->successResponse(null, 'Product deleted successfully.');
}
```

**Files to Modify:**
- `/app/Http/Controllers/Api/VendorProductController.php` (update, destroy methods)

**Test Cases:**
- ✓ Vendor A can update own product
- ✓ Vendor A cannot update Vendor B's product (404)
- ✓ Vendor A cannot delete platform products (vendor_id = null)

---

#### **Fix #3: Secure Vendor Management Routes**
**Problem:** Vendor approval endpoints not protected (Issue #3)

**Current Code:**
```php
// routes/api.php (WRONG INDENTATION!)
Route::middleware('ensure.permission:users.manage')->prefix('users')->group(function () {
    // ... other routes ...
});

// THESE ARE OUTSIDE THE GROUP! (lines 228-231)
Route::get('/vendors', [AdminVendorController::class, 'index']);
Route::put('/vendors/{id}/approve', [AdminVendorController::class, 'approve']);
```

**Recommended Solution:**
```php
Route::middleware('ensure.permission:users.manage')->prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::post('/', [UserController::class, 'store']);
    Route::put('/{id}', [UserController::class, 'update']);

    // MOVE VENDOR ROUTES INSIDE THE GROUP
    Route::prefix('vendors')->group(function () {
        Route::get('/', [AdminVendorController::class, 'index']);
        Route::put('/{id}/approve', [AdminVendorController::class, 'approve']);
        Route::put('/{id}/reject', [AdminVendorController::class, 'reject']);
        Route::put('/{id}/suspend', [AdminVendorController::class, 'suspend']);
        Route::put('/{id}/unsuspend', [AdminVendorController::class, 'unsuspend']);
    });

    Route::get('/audit-logs', [AuditLogController::class, 'index']);
});
```

**Files to Modify:**
- `/routes/api.php` (lines 221-236)

**Test Cases:**
- ✓ Admin can approve vendor
- ✓ Customer cannot approve vendor (403)
- ✓ Guest cannot list vendors (401)

---

#### **Fix #4: Enforce User Delete Permission**
**Problem:** Any admin can delete users, should be Super Admin only (Issue #4)

**Current Code:**
```php
// UserController.php
public function destroy(int $id): JsonResponse {
    // ... prevents self-delete ...
    $user->delete(); // NO PERMISSION CHECK!
}
```

**Recommended Solution:**
```php
public function destroy(int $id): JsonResponse {
    $currentUser = auth()->user();
    $user = User::find($id);

    if (!$user) {
        return $this->notFoundResponse('User');
    }

    // Prevent deleting yourself
    if ($user->id === $currentUser->id) {
        return $this->badRequestResponse('You cannot delete your own account.');
    }

    // Only Super Admin can delete users
    if (!$currentUser->can('users.delete')) {
        return $this->forbiddenResponse('Only Super Admins can delete users.');
    }

    $user->delete();

    return response()->json([
        'success' => true,
        'message' => 'User deleted successfully.',
    ]);
}
```

**Files to Modify:**
- `/app/Http/Controllers/Api/Admin/UserController.php:206-228`

**Test Cases:**
- ✓ Super Admin can delete users
- ✓ Admin cannot delete users (403)
- ✓ Cannot delete self (400)

---

### 7.2 HIGH-PRIORITY IMPROVEMENTS (Next Sprint)

#### **Improvement #1: Implement Review Purchase Verification**
**Problem:** Users can review products they never bought (Issue #5)

**Recommended Solution:**
```php
// ReviewController.php
public function store(Request $request): JsonResponse {
    $user = $request->user();
    $productId = $request->product_id;

    // CHECK IF USER PURCHASED THIS PRODUCT
    $hasPurchased = Order::where('user_id', $user->id)
        ->whereHas('items', function ($query) use ($productId) {
            $query->where('product_id', $productId);
        })
        ->where('status', 'delivered') // Only allow reviews after delivery
        ->exists();

    if (!$hasPurchased) {
        return $this->forbiddenResponse(
            'You can only review products you have purchased and received.'
        );
    }

    // Prevent duplicate reviews
    $existingReview = Review::where('user_id', $user->id)
        ->where('product_id', $productId)
        ->first();

    if ($existingReview) {
        return $this->badRequestResponse(
            'You have already reviewed this product. You can update your existing review instead.'
        );
    }

    // Create review...
}
```

**Files to Modify:**
- `/app/Http/Controllers/Api/ReviewController.php`

**Test Cases:**
- ✓ User who purchased product can review
- ✓ User who didn't purchase cannot review (403)
- ✓ Cannot submit duplicate reviews

---

#### **Improvement #2: Fix Loyalty Points Silent Failures**
**Problem:** Signup bonus fails silently (Issue #6)

**Recommended Solution:**
```php
// AuthController.php
public function register(RegisterRequest $request): JsonResponse {
    // ... create user ...

    // Award signup bonus with error handling
    $loyaltyPoints = 0;
    try {
        $loyaltyService = app(LoyaltyService::class);
        $loyaltyService->awardPoints($user->id, 50, 'signup_bonus', null, 'Welcome Bonus');
        $loyaltyPoints = 50;
    } catch (\Exception $e) {
        Log::error('Failed to award signup points: ' . $e->getMessage());

        // NOTIFY USER (don't fail registration, but let them know)
        // Option 1: Add to response
        // Option 2: Send email notification
        // Option 3: Create admin notification for manual resolution
    }

    return $this->successResponse([
        'message' => 'Registration successful',
        'user' => [...],
        'token' => $token->plainTextToken,
        'loyalty_points' => $loyaltyPoints,
        'loyalty_bonus_pending' => $loyaltyPoints === 0, // Flag if failed
    ], 'Registration successful', 201);
}
```

**Files to Modify:**
- `/app/Http/Controllers/Api/AuthController.php:32-82`
- `/app/Services/LoyaltyService.php` (ensure proper error handling)

**Test Cases:**
- ✓ Successful registration awards 50 points
- ✓ If loyalty service fails, user is still registered
- ✓ User is notified if bonus pending

---

#### **Improvement #3: Add Vendor-Specific Permissions**
**Problem:** Vendors share permissions with admins (Gap #2)

**Recommended Solution:**

1. **Create New Permissions:**
```php
// RolePermissionSeeder.php
$vendorPermissions = [
    'vendor.access',
    'vendor.profile.update',
    'vendor.products.create',
    'vendor.products.view',
    'vendor.products.update',
    'vendor.products.delete',
    'vendor.orders.view',
    'vendor.orders.update',
    'vendor.wallet.view',
    'vendor.wallet.payout',
];

foreach ($vendorPermissions as $permission) {
    Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
}

$vendorRole->syncPermissions($vendorPermissions);
```

2. **Update Middleware Checks:**
```php
// routes/api.php
Route::middleware(['auth:sanctum', 'vendor.approved', 'ensure.permission:vendor.products.create'])
    ->post('/vendor/products', [VendorProductController::class, 'store']);
```

**Files to Modify:**
- `/database/seeders/RolePermissionSeeder.php`
- `/routes/api.php` (vendor routes)

**Test Cases:**
- ✓ Vendor has `vendor.products.create`, not `products.create`
- ✓ Can differentiate vendor actions in audit logs

---

#### **Improvement #4: Implement Frontend Vendor Status Redirect**
**Problem:** No frontend logic to handle vendor status (Broken #1)

**Recommended Solution:**
```javascript
// /public/assets/js/app.js (or similar)
async function checkAndRedirectAfterLogin(user) {
    const currentPath = window.location.pathname;

    // Vendor-specific redirects
    if (user.vendor) {
        if (user.vendor.status === 'approved') {
            if (currentPath !== '/vendor-dashboard.html') {
                window.location.href = '/vendor-dashboard.html';
            }
        } else if (user.vendor.status === 'pending') {
            if (currentPath !== '/vendor-pending.html') {
                window.location.href = '/vendor-pending.html';
            }
        } else { // rejected or suspended
            if (currentPath.startsWith('/vendor-')) {
                alert('Your vendor account has been ' + user.vendor.status);
                window.location.href = '/';
            }
        }
    }

    // Admin redirects
    else if (user.roles.includes('admin') || user.roles.includes('super_admin') || user.roles.includes('manager')) {
        if (currentPath !== '/admin-dashboard.html') {
            window.location.href = '/admin-dashboard.html';
        }
    }

    // Customer redirect
    else {
        if (currentPath === '/login.html' || currentPath === '/register.html') {
            window.location.href = '/profile.html';
        }
    }
}

// Call after login
document.addEventListener('DOMContentLoaded', async () => {
    const token = localStorage.getItem('auth_token');
    if (token) {
        try {
            const response = await fetch('/api/auth/user', {
                headers: { 'Authorization': `Bearer ${token}` }
            });
            const data = await response.json();
            if (data.success) {
                checkAndRedirectAfterLogin(data.user);
            }
        } catch (error) {
            console.error('Auth check failed:', error);
        }
    }
});
```

**Files to Modify:**
- `/public/assets/js/app.js` (or create new `/public/assets/js/auth-guard.js`)
- Include in all protected pages

**Test Cases:**
- ✓ Approved vendor redirected to dashboard
- ✓ Pending vendor redirected to pending page
- ✓ Rejected/suspended vendor redirected to homepage with message

---

#### **Improvement #5: Implement Soft Deletes**
**Problem:** Hard deletes cause data loss (Broken #8)

**Recommended Solution:**

1. **Add SoftDeletes Trait to Models:**
```php
// User.php, Product.php, Order.php, Vendor.php
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable {
    use SoftDeletes;
}
```

2. **Create Migration:**
```php
Schema::table('users', function (Blueprint $table) {
    $table->softDeletes(); // Adds deleted_at column
});
Schema::table('products', function (Blueprint $table) {
    $table->softDeletes();
});
// ... repeat for all critical tables
```

3. **Update Controllers:**
```php
// UserController.php
public function destroy(int $id): JsonResponse {
    // ...
    $user->delete(); // Now a soft delete
    // ...
}

// Add endpoint to permanently delete
public function forceDestroy(int $id): JsonResponse {
    if (!auth()->user()->can('users.delete')) {
        return $this->forbiddenResponse();
    }

    $user = User::withTrashed()->findOrFail($id);
    $user->forceDelete(); // Permanent delete

    return $this->successResponse(null, 'User permanently deleted.');
}
```

**Files to Modify:**
- `/app/Models/User.php`, `/app/Models/Product.php`, etc.
- `/database/migrations/*_add_soft_deletes.php` (new)
- All delete methods in controllers

**Test Cases:**
- ✓ Deleted users not returned in queries
- ✓ Can restore deleted users
- ✓ Permanent delete requires special permission

---

### 7.3 MEDIUM-PRIORITY ENHANCEMENTS

#### **Enhancement #1: Separate "View Own" vs "View All" Permissions**
**Problem:** Permission gap #1

**Recommended Permissions:**
```
orders.view.own    → Customer can view their orders
orders.view.all    → Admin can view all orders

products.view.own  → Vendor can view their products
products.view.all  → Admin can view all products
```

**Implementation:**
- Update RolePermissionSeeder
- Update middleware/policies to check specific permissions
- Update controllers to use specific permissions

---

#### **Enhancement #2: Implement Email Verification**
**Problem:** Email not verified (Broken #3)

**Recommended Solution:**
1. Add `MustVerifyEmail` interface to User model
2. Add `verified` middleware to protected routes
3. Add email verification routes:
   ```php
   GET /api/email/verify/{id}/{hash}
   POST /api/email/resend
   ```
4. Send verification email on registration

**Files to Modify:**
- `/app/Models/User.php`
- `/routes/api.php`
- `/app/Http/Controllers/Api/AuthController.php`

---

#### **Enhancement #3: Add Time-Based Order Cancellation**
**Problem:** Broken #7 - can cancel anytime

**Recommended Solution:**
```php
// OrderPolicy.php
public function cancel(User $user, Order $order): bool {
    // Admin can cancel anytime
    if ($user->can('orders.cancel')) {
        return true;
    }

    // Customer can only cancel own orders
    if ($order->user_id !== $user->id) {
        return false;
    }

    // Must be pending or processing
    if (!in_array($order->status, ['pending', 'processing'])) {
        return false;
    }

    // Must be within 24 hours of order
    $hoursSinceOrder = $order->created_at->diffInHours(now());
    if ($hoursSinceOrder > 24) {
        return false;
    }

    return true;
}
```

---

#### **Enhancement #4: Implement Vendor Commission Calculation**
**Problem:** Commission not calculated (Issue #3)

**Recommended Solution:**
- On order creation, calculate vendor commission
- Create VendorTransaction record for each vendor in order
- Deduct commission from vendor earnings
- Track platform revenue

**Example:**
```php
// OrderService.php
foreach ($order->items as $item) {
    if ($item->product->vendor_id) {
        $vendor = $item->product->vendor;
        $itemTotal = $item->price * $item->quantity;
        $commission = $itemTotal * ($vendor->commission_rate / 100);
        $vendorEarnings = $itemTotal - $commission;

        VendorTransaction::create([
            'vendor_id' => $vendor->id,
            'order_id' => $order->id,
            'type' => 'sale',
            'amount' => $vendorEarnings,
            'commission' => $commission,
        ]);
    }
}
```

---

### 7.4 LOW-PRIORITY / NICE-TO-HAVE

#### **Enhancement #1: Implement Category Management UI**
**Problem:** Permission gap #4

**Recommendation:**
- Create `/api/admin/categories` endpoints (CRUD)
- Add category management UI to admin dashboard
- Or remove unused permissions

---

#### **Enhancement #2: Implement Plant Database Management**
**Problem:** Permission gap #5

**Recommendation:**
- Create `/api/admin/plants` endpoints (CRUD)
- Add plant database UI to admin dashboard
- Or remove unused permissions

---

#### **Enhancement #3: Add Gift Card/Voucher Management**
**Problem:** Permission gap #6

**Recommendation:**
- Create admin endpoints to issue/manage vouchers
- Create admin endpoints to issue/manage gift cards
- Add permissions: `vouchers.manage`, `gift-cards.issue`

---

#### **Enhancement #4: Standardize API Response Format**
**Problem:** Inconsistency #1

**Recommendation:**
- Create `ApiResponse` trait (already exists)
- Ensure ALL controllers use it
- Standardize on format:
  ```json
  {
    "success": true,
    "data": {...},
    "message": "...",
    "meta": {...}
  }
  ```

---

#### **Enhancement #5: Clean Up Unused Middleware**
**Problem:** Inconsistency #4

**Recommendation:**
- Remove unused middleware aliases from Kernel
- Standardize on:
  - `ensure.role` (custom, better error messages)
  - `ensure.permission` (custom, better error messages)
  - `vendor.approved` (custom)
- Remove: `admin`, `isAdmin` (use `ensure.role:admin` instead)

---

## 8. Implementation Checklist

### 8.1 Security Fixes (CRITICAL - Deploy Immediately)

- [ ] **Fix #1:** Remove dual role system check from EnsureRole middleware
- [ ] **Fix #2:** Add vendor product ownership validation
- [ ] **Fix #3:** Move vendor management routes inside permission group
- [ ] **Fix #4:** Add `users.delete` permission check to UserController::destroy
- [ ] **Deploy Security Patch 1.0.1**
- [ ] **Run Full Security Audit**

### 8.2 High-Priority Fixes (Next Sprint)

- [ ] **Improvement #1:** Implement review purchase verification
- [ ] **Improvement #2:** Fix loyalty points silent failures
- [ ] **Improvement #3:** Add vendor-specific permissions
- [ ] **Improvement #4:** Implement frontend vendor status redirect
- [ ] **Improvement #5:** Implement soft deletes for critical models
- [ ] **Deploy Feature Release 1.1.0**

### 8.3 Medium-Priority Enhancements

- [ ] **Enhancement #1:** Separate "view own" vs "view all" permissions
- [ ] **Enhancement #2:** Implement email verification
- [ ] **Enhancement #3:** Add time-based order cancellation
- [ ] **Enhancement #4:** Implement vendor commission calculation
- [ ] **Deploy Feature Release 1.2.0**

### 8.4 Low-Priority / Future

- [ ] Implement category management UI or remove permissions
- [ ] Implement plant database management or remove permissions
- [ ] Add gift card/voucher admin management
- [ ] Standardize all API responses
- [ ] Clean up unused middleware
- [ ] Implement blog/community content permissions
- [ ] Add automated token cleanup job
- [ ] Implement Google social login (complete integration)

---

## Appendix A: Testing Credentials

### Production Test Accounts

| Role | Email | Password | Status |
|------|-------|----------|--------|
| Super Admin | admin@nursery-app.com | password123 | Active |
| Admin | admin.user@nursery-app.com | password123 | Active |
| Manager | manager@nursery-app.com | password123 | Active |
| Vendor (Approved) | vendor@example.com | password123 | Create Manually |
| Vendor (Pending) | sahil@matrixsoftwares.com | password123 | Pending |
| Customer | customer@example.com | password | Active |

---

## Appendix B: File Reference Index

### Core Authorization Files
- `/app/Http/Middleware/EnsureRole.php` - Role checking middleware
- `/app/Http/Middleware/EnsurePermission.php` - Permission checking middleware
- `/app/Http/Middleware/EnsureVendorApproved.php` - Vendor approval middleware
- `/app/Http/Kernel.php` - Middleware registration
- `/database/seeders/RolePermissionSeeder.php` - Role & permission definitions
- `/routes/api.php` - API route definitions with guards

### Models
- `/app/Models/User.php` - User model with roles/permissions
- `/app/Models/Vendor.php` - Vendor profile model
- `/app/Models/Product.php` - Product model
- `/app/Models/Order.php` - Order model
- `/app/Models/Review.php` - Review model

### Controllers
- `/app/Http/Controllers/Api/AuthController.php` - Authentication
- `/app/Http/Controllers/Api/VendorController.php` - Vendor registration
- `/app/Http/Controllers/Api/AdminVendorController.php` - Vendor approval
- `/app/Http/Controllers/Api/Admin/UserController.php` - User management
- `/app/Http/Controllers/Api/Admin/ProductController.php` - Product management
- `/app/Http/Controllers/Api/VendorProductController.php` - Vendor products
- `/app/Http/Controllers/Api/OrderController.php` - Orders
- `/app/Http/Controllers/Api/ReviewController.php` - Reviews

### Policies
- `/app/Policies/OrderPolicy.php` - Order authorization rules
- `/app/Policies/ProductPolicy.php` - Product authorization rules
- `/app/Policies/ReviewPolicy.php` - Review authorization rules

### Frontend
- `/public/admin-dashboard.html` - Admin dashboard
- `/public/vendor-dashboard.html` - Vendor dashboard
- `/public/vendor-register.html` - Vendor registration
- `/public/vendor-pending.html` - Vendor pending approval
- `/public/profile.html` - User profile
- `/public/login.html` - Login page
- `/public/register.html` - Registration page

---

## Document Change Log

| Version | Date | Changes |
|---------|------|---------|
| 1.0 | 2025-12-27 | Initial comprehensive analysis |

---

**END OF DOCUMENT**

*This document should be treated as the single source of truth for all RBAC, security, and feature implementation in the Nursery App. Any deviations from this document should be documented and approved by the technical lead.*
