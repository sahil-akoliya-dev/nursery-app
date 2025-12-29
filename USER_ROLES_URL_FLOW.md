# User Roles & URL Flow Guide

This document provides a comprehensive overview of all user roles, their login credentials, and the complete URL flow for navigating the Nursery App.

---

## 📋 Table of Contents

1. [User Roles Overview](#user-roles-overview)
2. [Login Credentials](#login-credentials)
3. [URL Flow by Role](#url-flow-by-role)
4. [Public Pages](#public-pages)
5. [Authentication Flow](#authentication-flow)
6. [API Endpoints](#api-endpoints)

---

## 🎭 User Roles Overview

The Nursery App has **5 user roles**:

| Role | Description | Access Level |
|------|-------------|--------------|
| **Super Admin** | Full system access, all permissions | Highest |
| **Admin** | Manages users, products, orders | High |
| **Manager** | Manages products, orders, reviews | Medium |
| **Vendor** | Manages own products and orders | Limited |
| **Customer** | Browse, purchase, review products | Basic |

---

## 🔑 Login Credentials

### Test Accounts

#### 1. Super Admin
```
Email: admin@nursery-app.com
Password: password123
```

#### 2. Admin User
```
Email: admin.user@nursery-app.com
Password: password123
```

#### 3. Manager User
```
Email: manager@nursery-app.com
Password: password123
```

#### 4. Vendor User
```
Email: sahil@matrixsoftwares.com
Password: password123
Note: This vendor doesn't have an approved vendor profile yet
```

#### 5. Customer User
```
Email: customer@example.com
Password: password
```

---

## 🔄 URL Flow by Role

### 1️⃣ SUPER ADMIN FLOW

**Login Redirect:** `/admin-dashboard`

#### Available URLs:

```
✅ /admin-dashboard          - Main admin dashboard
✅ /profile                   - User profile management
✅ /shop                      - Browse all products
✅ /product-detail?id={id}    - View product details
✅ /cart                      - Shopping cart
✅ /checkout                  - Checkout page
✅ /blog                      - Blog listing
✅ /blog-detail?id={id}       - Blog post details
✅ /about                     - About page
✅ /contact                   - Contact page
✅ /plant-finder              - Plant finder quiz
```

#### Admin-Specific Features:

**Users Management:**
- View all users: API `/api/admin/users`
- Create/Update/Delete users: API endpoints
- Manage user roles and permissions

**Products Management:**
- View all products: API `/api/admin/products`
- Create/Update/Delete products
- Manage inventory

**Orders Management:**
- View all orders: API `/api/admin/orders`
- Update order status
- Process refunds

**Settings:**
- System settings: API `/api/admin/settings`
- Email configuration
- Loyalty program settings

**Analytics:**
- Dashboard analytics: API `/api/admin/analytics/dashboard`
- Sales reports
- Customer reports
- Inventory reports

---

### 2️⃣ ADMIN FLOW

**Login Redirect:** `/admin-dashboard`

#### Available URLs:

Same as Super Admin, with slightly restricted permissions:
- Cannot modify super admin accounts
- Cannot access all system settings
- Limited analytics access

```
✅ /admin-dashboard          - Main admin dashboard
✅ /profile                   - User profile
✅ /shop                      - Browse products
✅ /product-detail?id={id}    - Product details
✅ All public pages
```

---

### 3️⃣ MANAGER FLOW

**Login Redirect:** `/admin-dashboard`

#### Available URLs:

```
✅ /admin-dashboard          - Manager dashboard (limited view)
✅ /profile                   - User profile
✅ /shop                      - Browse products
✅ /product-detail?id={id}    - Product details
✅ All public pages
```

#### Manager-Specific Features:

**Limited Admin Access:**
- Manage products (create, update, delete)
- View and update orders
- Approve/reject reviews
- View inventory reports
- Cannot manage users
- Cannot access system settings

---

### 4️⃣ VENDOR FLOW

**Login Redirect (varies by vendor status):**

#### A. Approved Vendor → `/vendor-dashboard`

```
✅ /vendor-dashboard         - Vendor dashboard
✅ /vendor/register          - Update vendor profile
✅ /profile                   - User profile
✅ /shop                      - Browse all products
✅ /product-detail?id={id}    - Product details
✅ /store?vendor={slug}       - Your store page
✅ All public pages
```

**Vendor Dashboard Features:**
- View vendor products: API `/api/vendor/products`
- Add/Edit products
- Manage inventory
- View vendor orders: API `/api/vendor/orders`
- Update order item status
- View wallet balance: API `/api/vendor/wallet`
- Request payouts

#### B. Pending Vendor → `/vendor-pending`

```
✅ /vendor-pending           - Waiting for approval page
✅ /profile                   - User profile
✅ /shop                      - Browse products
✅ All public pages
```

#### C. Vendor Without Profile → `/`

If user has vendor role but no vendor profile created:
- Redirected to homepage
- Can access vendor registration: `/vendor/register`

---

### 5️⃣ CUSTOMER FLOW

**Login Redirect:** `/profile`

#### Available URLs:

```
✅ /                          - Homepage
✅ /profile                   - User profile & order history
✅ /shop                      - Browse products
✅ /product-detail?id={id}    - View product details
✅ /cart                      - Shopping cart
✅ /checkout                  - Checkout process
✅ /order-success?id={id}     - Order confirmation
✅ /blog                      - Blog listing
✅ /blog-detail?id={id}       - Read blog posts
✅ /about                     - About us
✅ /contact                   - Contact us
✅ /plant-finder              - Plant recommendation quiz
✅ /store?vendor={slug}       - View vendor stores
✅ /community                 - Community posts
```

#### Customer-Specific Features:

**Shopping:**
- Add to cart: API `/api/cart/add`
- Manage cart: API `/api/cart`
- Create orders: API `/api/orders`
- Track orders: API `/api/orders/{id}`

**Wishlist:**
- Add to wishlist: API `/api/wishlist/add`
- View wishlist: API `/api/wishlist`
- Toggle wishlist items

**Reviews:**
- Write product reviews: API `/api/reviews`
- Vote on reviews
- View product reviews

**Loyalty Program:**
- View points: API `/api/loyalty/points`
- Redeem points: API `/api/loyalty/redeem`
- View tier status

**Profile Management:**
- Update profile: API `/api/profile`
- Change password: API `/api/profile/password`
- Manage addresses: API `/api/addresses`
- Export data (GDPR): API `/api/profile/data-export`

---

## 🌍 Public Pages (No Login Required)

These pages are accessible to everyone:

```
✅ /                          - Homepage
✅ /shop                      - Product catalog
✅ /product-detail?id={id}    - Product details
✅ /blog                      - Blog articles
✅ /blog-detail?id={id}       - Blog post
✅ /about                     - About page
✅ /contact                   - Contact page
✅ /plant-finder              - Plant finder quiz
✅ /store?vendor={slug}       - Vendor store pages
✅ /login                     - Login page
✅ /register                  - Registration page
✅ /vendor/register           - Vendor registration
```

---

## 🔐 Authentication Flow

### 1. Standard Email/Password Login

```
1. Visit: http://localhost:8000/login
2. Enter credentials
3. Submit form
4. Backend validates: POST /api/auth/login
5. Response includes:
   - User data
   - Auth token
   - Roles & permissions
6. Frontend stores token in localStorage
7. Redirect based on role:
   - super_admin/admin/manager → /admin-dashboard
   - vendor (approved) → /vendor-dashboard
   - vendor (pending) → /vendor-pending
   - vendor (no profile) → /
   - customer → /profile
```

### 2. Google Social Login

```
1. Visit: http://localhost:8000/login
2. Click "Sign in with Google"
3. Redirects to: /auth/google
4. Google OAuth flow
5. Callback to: /auth/google/callback
6. Backend:
   - Creates or links user account
   - Logs in user
   - Determines redirect based on role
7. Redirect same as standard login
```

### 3. Registration Flow

```
1. Visit: http://localhost:8000/register
2. Fill registration form
3. Submit: POST /api/auth/register
4. Backend:
   - Creates user with 'customer' role
   - Assigns 50 signup bonus points
   - Generates auth token
5. Auto-login and redirect to /profile
```

### 4. Vendor Registration Flow

```
1. Login as customer
2. Visit: http://localhost:8000/vendor/register
3. Fill vendor application form
4. Submit: POST /api/vendor/register
5. Backend:
   - Creates vendor profile with 'pending' status
   - Updates user role to 'vendor'
6. User redirected to /vendor-pending
7. Admin approves vendor
8. User can now access /vendor-dashboard
```

---

## 📡 API Endpoints Summary

### Authentication
```
POST   /api/auth/register          - Register new user
POST   /api/auth/login             - Login
POST   /api/auth/logout            - Logout
GET    /api/auth/user              - Get current user
POST   /api/auth/refresh           - Refresh token
```

### Public APIs
```
GET    /api/products               - List products
GET    /api/products/{id}          - Product details
GET    /api/categories             - List categories
GET    /api/reviews                - List reviews
GET    /api/posts                  - Blog posts
GET    /api/stores/{slug}          - Vendor store
```

### Customer APIs (Requires Auth)
```
GET    /api/cart                   - Get cart
POST   /api/cart/add               - Add to cart
GET    /api/orders                 - List orders
POST   /api/orders                 - Create order
GET    /api/wishlist               - Get wishlist
POST   /api/wishlist/add           - Add to wishlist
POST   /api/reviews                - Write review
GET    /api/profile                - Get profile
PUT    /api/profile                - Update profile
GET    /api/loyalty/points         - Get loyalty points
```

### Vendor APIs (Requires Auth + Vendor Role)
```
GET    /api/vendor/products        - List vendor products
POST   /api/vendor/products        - Create product
PUT    /api/vendor/products/{id}   - Update product
DELETE /api/vendor/products/{id}   - Delete product
GET    /api/vendor/orders          - List vendor orders
PUT    /api/vendor/orders/{id}/status - Update order status
GET    /api/vendor/wallet          - Get wallet balance
POST   /api/vendor/wallet/payout   - Request payout
```

### Admin APIs (Requires Auth + Admin Role)
```
GET    /api/admin/users            - List users
GET    /api/admin/products         - List products
POST   /api/admin/products         - Create product
GET    /api/admin/orders           - List orders
PUT    /api/admin/orders/{id}/status - Update order status
GET    /api/admin/analytics/dashboard - Dashboard analytics
GET    /api/admin/settings         - System settings
PUT    /api/admin/settings         - Update settings
```

---

## 🎯 Quick Testing Guide

### Test Super Admin Flow:
```bash
1. Visit: http://localhost:8000/login
2. Login with: admin@nursery-app.com / password123
3. Should redirect to: /admin-dashboard
4. Navigate to:
   - /profile (view profile)
   - /shop (browse products)
   - /admin-dashboard (admin features)
```

### Test Customer Flow:
```bash
1. Visit: http://localhost:8000/login
2. Login with: customer@example.com / password
3. Should redirect to: /profile
4. Navigate to:
   - /shop (browse products)
   - /cart (shopping cart)
   - /checkout (place order)
   - /wishlist (saved items)
```

### Test Vendor Flow (Pending):
```bash
1. Visit: http://localhost:8000/login
2. Login with: sahil@matrixsoftwares.com / password123
3. Should redirect to: / (homepage - no vendor profile)
4. Visit: /vendor/register (create vendor profile)
5. After registration: redirected to /vendor-pending
6. Admin approves vendor
7. Login again: redirected to /vendor-dashboard
```

### Test Google Login:
```bash
1. Visit: http://localhost:8000/login
2. Click: "Sign in with Google"
3. Complete Google OAuth
4. First time: Creates account with customer role
5. Redirects to: /profile
6. Subsequent logins: Auto-login and redirect
```

---

## 🔍 Troubleshooting

### Issue: "Route not found" Error
**Solution:**
- Clear route cache: `php artisan route:clear`
- Restart server: `php artisan serve`

### Issue: Login Successful but Not Redirecting
**Solution:**
- Hard refresh browser: Ctrl+Shift+R (Mac: Cmd+Shift+R)
- Clear browser cache
- Check browser console for JavaScript errors

### Issue: "Unauthenticated" on API Calls
**Solution:**
- Check if token is stored: `localStorage.getItem('auth_token')`
- Re-login to get new token
- Verify token is sent in Authorization header

### Issue: Admin Can't Access Admin Dashboard
**Solution:**
- Check user role: `SELECT role FROM users WHERE email='admin@nursery-app.com'`
- Verify permissions are assigned
- Clear config cache: `php artisan config:clear`

---

## 📝 Notes

- All authenticated routes require valid Bearer token
- Tokens expire after 2 hours
- Use `/api/auth/refresh` to get new token
- CSRF protection enabled for web routes
- API routes excluded from CSRF verification
- Google OAuth configured for localhost:8000
- Add production domain to Google Console for production

---

**Last Updated:** December 15, 2025
**App Version:** 2.0
**Server:** http://localhost:8000
