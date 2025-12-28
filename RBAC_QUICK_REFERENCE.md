# RBAC Quick Reference Guide
## Developer Cheat Sheet

**Last Updated:** December 27, 2025

---

## 🎭 Roles at a Glance

| Role | Default Redirect | Permission Count | Use Case |
|------|------------------|------------------|----------|
| `super_admin` | `/admin-dashboard.html` | 29 (all) | System administration |
| `admin` | `/admin-dashboard.html` | 21 | Platform management |
| `manager` | `/admin-dashboard.html` | 11 | Operations oversight |
| `vendor` | `/vendor-dashboard.html` | 9 | Marketplace sellers |
| `customer` | `/profile.html` | 5 (view only) | End users |

---

## 🔐 Quick Permission Checks

### In Controllers
```php
// Check if user has permission
if (!auth()->user()->can('products.manage')) {
    return response()->json(['error' => 'Forbidden'], 403);
}

// Use policy
$this->authorize('update', $product);

// Check role
if (!auth()->user()->hasRole('admin')) {
    abort(403);
}
```

### In Routes
```php
// Permission-based
Route::middleware(['auth:sanctum', 'ensure.permission:products.manage'])
    ->post('/admin/products', [ProductController::class, 'store']);

// Role-based
Route::middleware(['auth:sanctum', 'ensure.role:admin|manager'])
    ->get('/admin/dashboard', [DashboardController::class, 'index']);

// Vendor-specific
Route::middleware(['auth:sanctum', 'vendor.approved'])
    ->get('/vendor/products', [VendorProductController::class, 'index']);
```

### In Blade/Frontend
```javascript
// JavaScript (after fetching user)
if (user.permissions.includes('products.manage')) {
    // Show admin controls
}

if (user.roles.includes('vendor') && user.vendor?.status === 'approved') {
    // Show vendor dashboard
}
```

---

## 📋 Permission Reference

### Products
```
✓ products.view        → View products (all roles)
✓ products.create      → Create products (admin, vendor)
✓ products.update      → Update products (admin, manager, vendor*)
✓ products.delete      → Delete products (admin, vendor*)
✓ products.manage      → Full product control (admin only)
```
_*Vendor can only modify own products_

### Orders
```
✓ orders.view          → View orders (admin sees all, customer sees own)
✓ orders.update        → Update order status (admin, manager, vendor)
✓ orders.delete        → Delete orders (super_admin only)
✓ orders.cancel        → Cancel orders (admin, manager, customer*)
```
_*Customer can only cancel own pending orders_

### Users
```
✓ users.view           → View user list (admin)
✓ users.create         → Create users (super_admin only)
✓ users.update         → Update users (admin)
✓ users.delete         → Delete users (super_admin only)
✓ users.manage         → Full user control (admin)
```

### Reviews
```
✓ reviews.view         → View reviews (all roles)
✓ reviews.approve      → Approve reviews (admin, manager)
✓ reviews.delete       → Delete reviews (admin)
✓ reviews.manage       → Full review control (admin)
```

### Analytics
```
✓ analytics.view       → View analytics (admin, manager, vendor)
✓ analytics.export     → Export reports (admin only)
```

### System
```
✓ audit.view           → View audit logs (super_admin only)
✓ system.settings      → Modify settings (super_admin only)
✓ system.backup        → Database backup (super_admin only)
```

### Vendor-Specific
```
✓ vendor.access        → Access vendor features (vendor role)
✓ vendor.profile.update → Update vendor profile (vendor role)
```

---

## 🛣️ Common Route Patterns

### Public Routes (No Auth)
```
GET  /api/products
GET  /api/products/{id}
GET  /api/categories
POST /api/auth/register
POST /api/auth/login
```

### Authenticated Routes (Any Role)
```
GET  /api/cart
POST /api/cart/add
GET  /api/orders              → Returns user's own orders
POST /api/orders
GET  /api/profile
POST /api/reviews
```

### Admin Routes
```
GET  /api/admin/analytics/*   → Requires: analytics.view
GET  /api/admin/products/*    → Requires: products.manage
GET  /api/admin/users/*       → Requires: users.manage
GET  /api/admin/orders/*      → Requires: orders.update
POST /api/admin/reviews/{id}/approve → Requires: reviews.manage
```

### Vendor Routes
```
GET  /api/vendor/products     → Requires: auth + vendor.approved
POST /api/vendor/products     → Requires: auth + vendor.approved
GET  /api/vendor/orders       → Requires: auth + vendor.approved
GET  /api/vendor/wallet       → Requires: auth + vendor.approved
```

---

## 🔧 Common Code Snippets

### Check if User is Admin
```php
// Using role
if ($user->hasRole('admin')) { }

// Using legacy field (avoid, deprecated)
if ($user->role === 'admin') { }

// Using helper method (best for simple checks)
if ($user->isAdmin()) { }
```

### Check if Vendor is Approved
```php
$user = auth()->user();

if ($user->vendor && $user->vendor->status === 'approved') {
    // Vendor is approved
}
```

### Get User Permissions
```php
// Get all permissions
$permissions = auth()->user()->getAllPermissions()->pluck('name');

// Check specific permission
if (auth()->user()->can('products.create')) {
    // User can create products
}

// Check multiple permissions (OR)
if (auth()->user()->canAny(['products.create', 'products.update'])) {
    // User can create OR update
}
```

### Assign Role to User
```php
$user = User::find($userId);
$user->assignRole('vendor');

// Sync roles (replaces all existing)
$user->syncRoles(['admin']);

// Remove role
$user->removeRole('vendor');
```

### Approve Vendor
```php
$vendor = Vendor::find($vendorId);
$vendor->update([
    'status' => 'approved',
    'approved_at' => now(),
]);

// Assign vendor role
$vendor->user->assignRole('vendor');

// Send email
Mail::to($vendor->user->email)->send(new VendorApproved($vendor));
```

---

## ⚠️ Common Pitfalls

### ❌ DON'T: Check role directly on user field
```php
if ($user->role === 'admin') { } // AVOID - uses legacy field
```

### ✅ DO: Use Spatie methods
```php
if ($user->hasRole('admin')) { } // CORRECT - uses proper RBAC
```

---

### ❌ DON'T: Forget ownership checks
```php
$product = Product::find($id);
$product->update($data); // WRONG - vendor can update any product!
```

### ✅ DO: Validate ownership
```php
$product = Product::where('id', $id)
    ->where('vendor_id', auth()->user()->vendor->id)
    ->firstOrFail();
$product->update($data); // CORRECT - only own products
```

---

### ❌ DON'T: Use permission for auth-only features
```php
Route::middleware('ensure.permission:cart.view') // WRONG - no such permission
    ->get('/cart', [CartController::class, 'index']);
```

### ✅ DO: Use auth middleware only
```php
Route::middleware('auth:sanctum') // CORRECT - all authenticated users
    ->get('/cart', [CartController::class, 'index']);
```

---

### ❌ DON'T: Return detailed error messages
```php
return response()->json([
    'error' => 'User with ID 123 does not have permission products.manage'
], 403); // WRONG - leaks information
```

### ✅ DO: Return generic messages
```php
return response()->json([
    'error' => 'Insufficient permissions.'
], 403); // CORRECT - no information leakage
```

---

## 🧪 Testing Role/Permission

### Seed Test Users
```bash
php artisan db:seed --class=RolePermissionSeeder
php artisan db:seed --class=UserSeeder
```

### Create Test User in Tinker
```php
php artisan tinker

// Create Super Admin
$user = User::create([
    'name' => 'Test Admin',
    'email' => 'test@admin.com',
    'password' => bcrypt('password'),
]);
$user->assignRole('super_admin');

// Create Vendor
$user = User::create([
    'name' => 'Test Vendor',
    'email' => 'test@vendor.com',
    'password' => bcrypt('password'),
]);
$vendor = Vendor::create([
    'user_id' => $user->id,
    'store_name' => 'Test Store',
    'store_slug' => 'test-store',
    'status' => 'approved',
]);
$user->assignRole('vendor');
```

### Test API with Postman
```bash
# 1. Login
POST http://localhost:8000/api/auth/login
{
    "email": "admin@nursery-app.com",
    "password": "password123"
}

# 2. Copy token from response

# 3. Test protected endpoint
GET http://localhost:8000/api/admin/users
Headers:
  Authorization: Bearer {token}
```

---

## 📚 Useful Commands

```bash
# Clear permission cache
php artisan permission:cache-reset

# Show all permissions
php artisan tinker
>>> Spatie\Permission\Models\Permission::all()->pluck('name')

# Show all roles
>>> Spatie\Permission\Models\Role::with('permissions')->get()

# Show user's roles and permissions
>>> User::find(1)->getRoleNames()
>>> User::find(1)->getAllPermissions()->pluck('name')

# Assign permission to role
>>> $role = Spatie\Permission\Models\Role::findByName('admin');
>>> $role->givePermissionTo('products.manage');

# Sync all permissions from seeder
php artisan migrate:fresh --seed
```

---

## 🐛 Debugging

### Check Why User Can't Access Route

1. **Check if authenticated:**
   ```php
   auth()->check() // Should return true
   ```

2. **Check user roles:**
   ```php
   auth()->user()->getRoleNames() // Should include required role
   ```

3. **Check user permissions:**
   ```php
   auth()->user()->getAllPermissions()->pluck('name') // Should include required permission
   ```

4. **Check vendor status (if vendor route):**
   ```php
   auth()->user()->vendor // Should exist
   auth()->user()->vendor->status // Should be 'approved'
   ```

5. **Check middleware order:**
   - `auth:sanctum` must come BEFORE `ensure.permission`
   - `vendor.approved` checks both role AND status

6. **Check route definition:**
   ```bash
   php artisan route:list --path=api/admin
   ```

---

## 🔗 Key Files Reference

| File | Purpose |
|------|---------|
| `/database/seeders/RolePermissionSeeder.php` | Define roles & permissions |
| `/app/Http/Middleware/EnsureRole.php` | Role checking logic |
| `/app/Http/Middleware/EnsurePermission.php` | Permission checking logic |
| `/app/Http/Middleware/EnsureVendorApproved.php` | Vendor approval check |
| `/app/Http/Kernel.php` | Middleware registration |
| `/routes/api.php` | API route definitions |
| `/app/Policies/*` | Authorization policies |

---

## 📞 Support

- **Full Documentation:** [COMPREHENSIVE_ROLE_ANALYSIS.md](./COMPREHENSIVE_ROLE_ANALYSIS.md)
- **Security Issues:** [SECURITY_EXECUTIVE_SUMMARY.md](./SECURITY_EXECUTIVE_SUMMARY.md)
- **User Flows:** [USER_ROLES_URL_FLOW.md](./USER_ROLES_URL_FLOW.md)

---

*Keep this document handy for quick reference during development!*
