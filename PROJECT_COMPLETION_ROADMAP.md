# Project Completion Roadmap
## Nursery App - End-to-End Execution Plan

**Document Version:** 1.0
**Status:** READY FOR EXECUTION
**Total Estimated Time:** 12-15 working days
**Critical Path:** Phase 0 → Phase 1 → Phase 2 → Phase 3

---

## Table of Contents

1. [Execution Overview](#execution-overview)
2. [Phase 0: Security Lockdown (P0)](#phase-0-security-lockdown-p0)
3. [Phase 1: RBAC Normalization](#phase-1-rbac-normalization)
4. [Phase 2: Feature Completion](#phase-2-feature-completion)
5. [Phase 3: Hardening & Finalization](#phase-3-hardening--finalization)
6. [Definition of DONE](#definition-of-done)
7. [Validation Checklists](#validation-checklists)
8. [Final Architecture Snapshot](#final-architecture-snapshot)

---

## Execution Overview

### Current State
- **Authorization Score:** 4/10 (Critical)
- **Security Status:** 🔴 7 Critical Vulnerabilities
- **Feature Completeness:** 73% (8 broken/incomplete features)
- **Code Quality:** 7/10 (inconsistencies exist)
- **Overall:** 6.2/10 - AT RISK

### Target State
- **Authorization Score:** 9/10 (Excellent)
- **Security Status:** ✅ No known vulnerabilities
- **Feature Completeness:** 100%
- **Code Quality:** 9/10 (standardized, tested)
- **Overall:** 9+/10 - PRODUCTION READY

### Phases Summary

| Phase | Goal | Duration | Blocking? | Success Metric |
|-------|------|----------|-----------|----------------|
| **Phase 0** | Security Lockdown | 1-2 days | YES (blocks all others) | 5 critical fixes deployed |
| **Phase 1** | RBAC Normalization | 2-3 days | YES (blocks Phase 2) | Dual role system eliminated |
| **Phase 2** | Feature Completion | 4-5 days | NO (can parallelize) | 8 broken features fixed |
| **Phase 3** | Hardening | 3-4 days | NO | 100% test coverage on auth |

### Dependencies Graph
```
Phase 0 (Security)
    ↓
Phase 1 (RBAC)
    ↓
Phase 2 (Features) ←→ Phase 3 (Hardening)
    ↓
    ✅ PRODUCTION COMPLETE
```

---

## Phase 0: Security Lockdown (P0)

### Goals
- Eliminate all 5 critical security vulnerabilities
- Deploy emergency patch 1.0.1
- Prevent further exploitation
- Block all new feature work until complete

### Duration
**1-2 days** (4-6 development hours + testing + deployment)

### Prerequisites
- [ ] Create hotfix branch: `hotfix/security-1.0.1`
- [ ] Notify team: "Feature freeze until security patch deployed"
- [ ] Set up staging environment for testing
- [ ] Prepare rollback plan

### Tasks

---

#### **Task 0.1: Remove Dual Role System Check**

**Problem:** Users can bypass permission checks via legacy `users.role` field

**Current Behavior:**
```php
// EnsureRole.php:41
if ($user->hasRole($role) || $user->role === $role) {
    $hasRole = true;
}
```
User with `users.role = 'admin'` but no Spatie role can access admin routes.

**Expected Behavior:**
Only Spatie roles should be checked. Legacy field ignored.

**Files to Modify:**
1. `/app/Http/Middleware/EnsureRole.php`

**Changes:**
```php
// Line 38-45: Replace with
$hasRole = false;
foreach ($roles as $role) {
    if ($user->hasRole($role)) { // Only check Spatie
        $hasRole = true;
        break;
    }
}
```

**Acceptance Criteria:**
- [ ] User with only legacy role gets 403 on admin routes
- [ ] User with Spatie role can access routes
- [ ] All existing admin users still work (have Spatie roles)

**Test Cases:**
```php
// Create user with only legacy role
$user = User::create(['role' => 'admin']); // No Spatie role
// Should get 403 on /api/admin/users

// Create user with Spatie role
$user = User::create([]);
$user->assignRole('admin'); // Spatie role
// Should get 200 on /api/admin/users
```

**Commit Message:**
```
fix(security): remove dual role system check in EnsureRole middleware

- Only check Spatie roles, ignore legacy users.role field
- Prevents authorization bypass via database manipulation
- All existing users must have Spatie roles assigned

BREAKING CHANGE: Users with only legacy role will lose access
```

**Estimated Time:** 30 minutes

---

#### **Task 0.2: Add Vendor Product Ownership Validation**

**Problem:** Any vendor can update/delete any product

**Current Behavior:**
```php
// VendorProductController.php
public function update(Request $request, $id) {
    $product = Product::findOrFail($id); // No ownership check
    $product->update($request->validated());
}
```

**Expected Behavior:**
Vendors can only modify products where `product.vendor_id = vendor.id`

**Files to Modify:**
1. `/app/Http/Controllers/Api/VendorProductController.php`

**Changes:**
```php
// update() method
public function update(Request $request, $id) {
    $vendor = $request->user()->vendor;

    if (!$vendor) {
        return $this->notFoundResponse('Vendor profile not found.');
    }

    $product = Product::where('id', $id)
        ->where('vendor_id', $vendor->id)
        ->firstOrFail(); // Returns 404 if not found or not owned

    $product->update($request->validated());

    return $this->successResponse($product, 'Product updated successfully.');
}

// destroy() method
public function destroy(Request $request, $id) {
    $vendor = $request->user()->vendor;

    if (!$vendor) {
        return $this->notFoundResponse('Vendor profile not found.');
    }

    $product = Product::where('id', $id)
        ->where('vendor_id', $vendor->id)
        ->firstOrFail();

    $product->delete();

    return $this->successResponse(null, 'Product deleted successfully.');
}
```

**Acceptance Criteria:**
- [ ] Vendor A can update own product (200)
- [ ] Vendor A cannot update Vendor B's product (404)
- [ ] Vendor A cannot update platform products (vendor_id = null) (404)
- [ ] Vendor A cannot delete Vendor B's product (404)

**Test Cases:**
```php
// Setup
$vendorA = Vendor::factory()->create();
$vendorB = Vendor::factory()->create();
$productA = Product::factory()->create(['vendor_id' => $vendorA->id]);
$productB = Product::factory()->create(['vendor_id' => $vendorB->id]);

// Test: Vendor A updates own product
$this->actingAs($vendorA->user)
    ->putJson("/api/vendor/products/{$productA->id}", ['name' => 'Updated'])
    ->assertStatus(200);

// Test: Vendor A tries to update Vendor B's product
$this->actingAs($vendorA->user)
    ->putJson("/api/vendor/products/{$productB->id}", ['name' => 'Hacked'])
    ->assertStatus(404);
```

**Commit Message:**
```
fix(security): add ownership validation to vendor product operations

- Vendors can only update/delete their own products
- Added vendor_id check in update() and destroy() methods
- Returns 404 if product not owned by vendor

Fixes: Vendor product ownership vulnerability
```

**Estimated Time:** 1 hour

---

#### **Task 0.3: Secure Vendor Management Routes**

**Problem:** Vendor approval endpoints have no authorization

**Current Behavior:**
```php
// routes/api.php (lines 228-231 are OUTSIDE the permission group)
Route::get('/vendors', [AdminVendorController::class, 'index']); // NO AUTH!
```

**Expected Behavior:**
All vendor management routes require `users.manage` permission

**Files to Modify:**
1. `/routes/api.php`

**Changes:**
```php
// Move lines 228-235 INSIDE the users.manage group
Route::middleware('ensure.permission:users.manage')->prefix('users')->group(function () {
    Route::get('/', [UserController::class, 'index']);
    Route::get('/{id}', [UserController::class, 'show']);
    Route::post('/', [UserController::class, 'store']);
    Route::put('/{id}', [UserController::class, 'update']);

    // ADD VENDOR ROUTES HERE (inside the group)
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

**Acceptance Criteria:**
- [ ] Guest cannot list vendors (401)
- [ ] Customer cannot list vendors (403)
- [ ] Admin can list vendors (200)
- [ ] Admin can approve vendors (200)
- [ ] Customer cannot approve vendors (403)

**Test Cases:**
```php
// Test: Guest tries to list vendors
$this->getJson('/api/admin/users/vendors')
    ->assertStatus(401);

// Test: Customer tries to approve vendor
$this->actingAs($customer)
    ->putJson('/api/admin/users/vendors/1/approve')
    ->assertStatus(403);

// Test: Admin can approve vendor
$this->actingAs($admin)
    ->putJson('/api/admin/users/vendors/1/approve')
    ->assertStatus(200);
```

**Commit Message:**
```
fix(security): protect vendor management routes with permission check

- Moved vendor routes inside users.manage middleware group
- Prevents unauthorized vendor approval/rejection
- All vendor management now requires admin permission

Fixes: Unprotected vendor management endpoints
```

**Estimated Time:** 30 minutes

---

#### **Task 0.4: Enforce User Delete Permission**

**Problem:** Any admin can delete users (should be Super Admin only)

**Current Behavior:**
```php
// UserController.php
public function destroy(int $id) {
    // Only checks self-delete, not permission
    $user->delete();
}
```

**Expected Behavior:**
Only users with `users.delete` permission can delete users

**Files to Modify:**
1. `/app/Http/Controllers/Api/Admin/UserController.php`

**Changes:**
```php
// destroy() method (around line 206)
public function destroy(int $id): JsonResponse
{
    $currentUser = auth()->user();
    $user = User::find($id);

    if (!$user) {
        return $this->notFoundResponse('User', 'User not found.');
    }

    // Prevent self-delete
    if ($user->id === $currentUser->id) {
        return $this->badRequestResponse(
            'You cannot delete your own account.',
            'CANNOT_DELETE_SELF'
        );
    }

    // Check permission
    if (!$currentUser->can('users.delete')) {
        return $this->forbiddenResponse(
            'Only Super Admins can delete users.',
            'INSUFFICIENT_PERMISSION'
        );
    }

    $user->delete();

    return response()->json([
        'success' => true,
        'message' => 'User deleted successfully.',
    ]);
}
```

**Acceptance Criteria:**
- [ ] Super Admin can delete users (200)
- [ ] Regular Admin cannot delete users (403)
- [ ] Cannot delete self (400)
- [ ] Returns proper error messages

**Test Cases:**
```php
// Test: Super Admin deletes user
$superAdmin = User::factory()->create();
$superAdmin->assignRole('super_admin');
$targetUser = User::factory()->create();

$this->actingAs($superAdmin)
    ->deleteJson("/api/admin/users/{$targetUser->id}")
    ->assertStatus(200);

// Test: Regular Admin tries to delete user
$admin = User::factory()->create();
$admin->assignRole('admin');

$this->actingAs($admin)
    ->deleteJson("/api/admin/users/{$targetUser->id}")
    ->assertStatus(403)
    ->assertJson(['error' => ['message' => 'Only Super Admins can delete users.']]);
```

**Commit Message:**
```
fix(security): enforce users.delete permission for user deletion

- Only users with users.delete permission can delete users
- Regular admins blocked from deletion (Super Admin only)
- Added permission check in UserController::destroy()

Fixes: Missing permission check on user delete
```

**Estimated Time:** 30 minutes

---

#### **Task 0.5: Implement Review Purchase Verification**

**Problem:** Users can review products without purchasing

**Current Behavior:**
```php
// ReviewController.php
public function store(Request $request) {
    // No purchase check
    Review::create([...]);
}
```

**Expected Behavior:**
Users can only review products they purchased and received

**Files to Modify:**
1. `/app/Http/Controllers/Api/ReviewController.php`

**Changes:**
```php
public function store(Request $request): JsonResponse
{
    $validated = $request->validate([
        'product_id' => 'required|exists:products,id',
        'rating' => 'required|integer|min:1|max:5',
        'title' => 'required|string|max:255',
        'comment' => 'required|string|max:1000',
        'images' => 'nullable|array|max:5',
    ]);

    $user = $request->user();
    $productId = $validated['product_id'];

    // CHECK: Has user purchased this product?
    $hasPurchased = Order::where('user_id', $user->id)
        ->whereHas('items', function ($query) use ($productId) {
            $query->where('product_id', $productId);
        })
        ->where('status', 'delivered') // Only delivered orders
        ->exists();

    if (!$hasPurchased) {
        return $this->forbiddenResponse(
            'You can only review products you have purchased and received.',
            'PURCHASE_REQUIRED'
        );
    }

    // CHECK: Prevent duplicate reviews
    $existingReview = Review::where('user_id', $user->id)
        ->where('product_id', $productId)
        ->first();

    if ($existingReview) {
        return $this->badRequestResponse(
            'You have already reviewed this product.',
            'DUPLICATE_REVIEW'
        );
    }

    // Create review
    $review = Review::create([
        'user_id' => $user->id,
        'product_id' => $productId,
        'rating' => $validated['rating'],
        'title' => $validated['title'],
        'comment' => $validated['comment'],
        'images' => $validated['images'] ?? [],
        'status' => 'pending', // Requires approval
    ]);

    return $this->successResponse($review, 'Review submitted successfully.', 201);
}
```

**Acceptance Criteria:**
- [ ] User who purchased product can review (201)
- [ ] User who didn't purchase cannot review (403)
- [ ] User cannot submit duplicate reviews (400)
- [ ] Only delivered orders count as "purchased"

**Test Cases:**
```php
// Test: User purchased and received product, can review
$user = User::factory()->create();
$product = Product::factory()->create();
$order = Order::factory()->create([
    'user_id' => $user->id,
    'status' => 'delivered',
]);
OrderItem::factory()->create([
    'order_id' => $order->id,
    'product_id' => $product->id,
]);

$this->actingAs($user)
    ->postJson('/api/reviews', [
        'product_id' => $product->id,
        'rating' => 5,
        'title' => 'Great!',
        'comment' => 'Loved it',
    ])
    ->assertStatus(201);

// Test: User didn't purchase, cannot review
$otherProduct = Product::factory()->create();
$this->actingAs($user)
    ->postJson('/api/reviews', [
        'product_id' => $otherProduct->id,
        'rating' => 1,
        'title' => 'Fake review',
        'comment' => 'Spam',
    ])
    ->assertStatus(403);
```

**Commit Message:**
```
fix(security): require purchase verification for product reviews

- Users can only review products they purchased and received
- Check for delivered orders before allowing review
- Prevent duplicate reviews
- Blocks fake/spam reviews

Fixes: Review creation without purchase verification
```

**Estimated Time:** 2 hours

---

### Phase 0 Deployment Checklist

- [ ] All 5 tasks completed and committed
- [ ] All test cases passing
- [ ] Code review completed
- [ ] Staging deployment successful
- [ ] Smoke tests on staging passed
- [ ] Security team sign-off
- [ ] Production deployment
- [ ] Monitor error rates for 24 hours
- [ ] Tag release: `v1.0.1-security-patch`

### Phase 0 Rollback Plan

If critical issues found:
```bash
git revert <commit-hash>
git push origin hotfix/security-1.0.1
# Deploy revert to production
```

### Phase 0 Success Metrics

- [ ] Authorization score: 4/10 → 7/10
- [ ] Zero new security vulnerabilities introduced
- [ ] All existing features still functional
- [ ] No increase in error rates

---

## Phase 1: RBAC Normalization

### Goals
- Eliminate legacy `users.role` field completely
- Migrate all role checks to Spatie
- Split permissions into "own" vs "all" where needed
- Align frontend visibility with backend permissions

### Duration
**2-3 days**

### Prerequisites
- [ ] Phase 0 deployed and stable
- [ ] All users have Spatie roles assigned
- [ ] Backup database before migration

### Tasks

---

#### **Task 1.1: Migrate All Users to Spatie Roles**

**Problem:** Some users may only have legacy `users.role` without Spatie role

**Current Behavior:**
Database may have users with `role = 'admin'` but no entry in `model_has_roles`

**Expected Behavior:**
Every user has matching Spatie role

**Files to Modify:**
1. Create new migration: `/database/migrations/2025_12_27_migrate_legacy_roles_to_spatie.php`

**Migration Code:**
```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    public function up(): void
    {
        // Get all users
        $users = User::all();

        foreach ($users as $user) {
            // Skip if user already has Spatie role
            if ($user->roles->isNotEmpty()) {
                continue;
            }

            // If user has legacy role, assign Spatie role
            if ($user->role) {
                $role = Role::firstOrCreate([
                    'name' => $user->role,
                    'guard_name' => 'web'
                ]);

                $user->assignRole($user->role);

                \Log::info("Migrated user {$user->id} ({$user->email}) to Spatie role: {$user->role}");
            } else {
                // No role at all, default to customer
                $user->assignRole('customer');
                $user->update(['role' => 'customer']);

                \Log::info("Assigned default customer role to user {$user->id} ({$user->email})");
            }
        }
    }

    public function down(): void
    {
        // Cannot safely reverse this migration
        \Log::warning('Reverting role migration may cause data loss. Manual intervention required.');
    }
};
```

**Acceptance Criteria:**
- [ ] All users have Spatie roles
- [ ] No users have only legacy role
- [ ] Migration logs all changes
- [ ] Migration is idempotent (can run multiple times safely)

**Commit Message:**
```
feat(rbac): migrate all legacy roles to Spatie roles

- Sync users.role to model_has_roles table
- Default to customer role if no role set
- Log all migrations for audit trail
- Idempotent migration (safe to re-run)

Part of: RBAC Normalization (Phase 1)
```

**Estimated Time:** 1 hour

---

#### **Task 1.2: Remove Legacy Role Field from User Model**

**Problem:** `users.role` field still exists, causing confusion

**Current Behavior:**
User model has both `role` attribute and Spatie roles

**Expected Behavior:**
Only Spatie roles used, `users.role` column dropped

**Files to Modify:**
1. `/app/Models/User.php`
2. Create migration: `/database/migrations/2025_12_27_remove_role_column_from_users.php`

**Changes to User.php:**
```php
// Remove 'role' from $fillable array
protected $fillable = [
    'name',
    'email',
    'password',
    'google_id',
    'avatar',
    // 'role', // REMOVE THIS
    'country_code',
    'phone',
    'date_of_birth',
    'address',
];

// Update helper methods to use Spatie only
public function isAdmin(): bool
{
    return $this->hasRole('admin') || $this->hasRole('super_admin');
}

public function isCustomer(): bool
{
    return $this->hasRole('customer');
}

// Add new helper for role display
public function getRoleDisplayAttribute(): string
{
    $role = $this->roles->first();

    $roleNames = [
        'super_admin' => 'Super Administrator',
        'admin' => 'Administrator',
        'manager' => 'Manager',
        'vendor' => 'Vendor',
        'customer' => 'Customer',
    ];

    return $roleNames[$role?->name] ?? 'Unknown';
}
```

**Migration:**
```php
public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('role');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('role')->default('customer');
    });
}
```

**Acceptance Criteria:**
- [ ] `users.role` column does not exist
- [ ] User model does not reference `role` attribute
- [ ] All helper methods use Spatie roles
- [ ] API responses use Spatie roles

**Commit Message:**
```
feat(rbac): remove legacy role column from users table

- Dropped users.role column
- Updated User model to only use Spatie roles
- Updated helper methods (isAdmin, isCustomer)
- All role checks now via Spatie

BREAKING CHANGE: users.role column removed
```

**Estimated Time:** 1 hour

---

#### **Task 1.3: Update AuthController to Remove Legacy Role Assignment**

**Problem:** AuthController still sets `users.role` on registration

**Current Behavior:**
```php
User::create(['role' => 'customer', ...]); // Sets legacy field
```

**Expected Behavior:**
Only assign Spatie role, no legacy field

**Files to Modify:**
1. `/app/Http/Controllers/Api/AuthController.php`

**Changes:**
```php
// register() method (line 34-41)
public function register(RegisterRequest $request): JsonResponse
{
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        // Remove: 'role' => 'customer',
        'phone' => $request->phone,
        'date_of_birth' => $request->date_of_birth,
    ]);

    // Assign customer role via Spatie only
    $user->assignRole('customer');

    // ... rest of registration logic
}
```

**Acceptance Criteria:**
- [ ] New users created without legacy `role` field
- [ ] New users have `customer` Spatie role
- [ ] Registration still works

**Commit Message:**
```
refactor(auth): remove legacy role assignment in registration

- Only assign Spatie roles
- Remove users.role field assignment
- Simplify registration logic

Part of: RBAC Normalization (Phase 1)
```

**Estimated Time:** 30 minutes

---

#### **Task 1.4: Split "View Own" vs "View All" Permissions**

**Problem:** Single permission for viewing own vs all records

**Current Behavior:**
- `orders.view` used for both "view own orders" and "view all orders"
- Customers hardcoded to see own orders without permission

**Expected Behavior:**
- `orders.view.own` - Customer can view own orders
- `orders.view.all` - Admin can view all orders

**Files to Modify:**
1. `/database/seeders/RolePermissionSeeder.php`
2. `/app/Http/Controllers/Api/OrderController.php`

**Changes to RolePermissionSeeder:**
```php
$permissions = [
    // Split orders permissions
    'orders.view.own',
    'orders.view.all',
    'orders.update',
    'orders.delete',
    'orders.cancel',

    // Split products permissions
    'products.view.own', // Vendor sees own products
    'products.view.all', // Admin sees all products
    'products.create',
    'products.update',
    'products.delete',
    'products.manage',

    // ... other permissions
];

// Customer permissions
$customer->givePermissionTo([
    'orders.view.own', // Can view own orders only
    // ... other customer permissions
]);

// Admin permissions
$admin->givePermissionTo([
    'orders.view.own',
    'orders.view.all', // Can view all orders
    // ... other admin permissions
]);

// Vendor permissions
$vendor->givePermissionTo([
    'products.view.own', // Can view own products
    'orders.view.own', // Can view own orders
    // ... other vendor permissions
]);
```

**Changes to OrderController:**
```php
public function index(Request $request): JsonResponse
{
    $user = $request->user();

    // Check permission
    if (!$user->can('orders.view.own')) {
        return $this->forbiddenResponse('Insufficient permissions.');
    }

    // Build query
    if ($user->can('orders.view.all')) {
        // Admin: see all orders
        $query = Order::query();
    } else {
        // Customer/Vendor: see only own orders
        $query = Order::where('user_id', $user->id);
    }

    // ... rest of query logic
}
```

**Acceptance Criteria:**
- [ ] Customer can view own orders (not all)
- [ ] Admin can view all orders
- [ ] Vendor can view own orders
- [ ] Permission names are descriptive

**Commit Message:**
```
feat(rbac): split view permissions into own vs all

- Added orders.view.own and orders.view.all
- Added products.view.own and products.view.all
- Updated permission checks in controllers
- Customer can only see own records

Part of: RBAC Normalization (Phase 1)
```

**Estimated Time:** 2 hours

---

#### **Task 1.5: Align Frontend Visibility with Backend Permissions**

**Problem:** Frontend shows buttons/links that backend will reject

**Current Behavior:**
- Admin buttons visible to customers
- Vendor dashboard link shown to pending vendors

**Expected Behavior:**
- Frontend checks user permissions before showing UI elements
- No "permission denied" errors after clicking buttons

**Files to Modify:**
1. `/public/assets/js/app.js` (or create `/public/assets/js/permissions.js`)

**Create Permission Helper:**
```javascript
// /public/assets/js/permissions.js
class PermissionGuard {
    constructor() {
        this.user = null;
        this.permissions = [];
        this.roles = [];
    }

    async init() {
        try {
            const token = localStorage.getItem('auth_token');
            if (!token) return false;

            const response = await fetch('/api/auth/user', {
                headers: { 'Authorization': `Bearer ${token}` }
            });

            const data = await response.json();
            if (data.success) {
                this.user = data.user;
                this.permissions = data.user.permissions || [];
                this.roles = data.user.roles?.map(r => r.name) || [];
                return true;
            }
        } catch (error) {
            console.error('Permission check failed:', error);
        }
        return false;
    }

    can(permission) {
        return this.permissions.includes(permission);
    }

    hasRole(role) {
        return this.roles.includes(role);
    }

    isAdmin() {
        return this.hasRole('admin') || this.hasRole('super_admin');
    }

    isVendor(status = 'approved') {
        if (!this.hasRole('vendor')) return false;
        if (!this.user.vendor) return false;
        return this.user.vendor.status === status;
    }

    showIfCan(permission, elementId) {
        const element = document.getElementById(elementId);
        if (!element) return;

        if (this.can(permission)) {
            element.style.display = 'block';
        } else {
            element.style.display = 'none';
        }
    }

    hideIfCannot(permission, elementId) {
        this.showIfCan(permission, elementId);
    }
}

// Global instance
const permissions = new PermissionGuard();

// Initialize on page load
document.addEventListener('DOMContentLoaded', async () => {
    await permissions.init();

    // Hide/show elements based on permissions
    if (permissions.isAdmin()) {
        document.getElementById('admin-nav-link')?.classList.remove('hidden');
    }

    if (permissions.isVendor('approved')) {
        document.getElementById('vendor-nav-link')?.classList.remove('hidden');
    }
});
```

**Update HTML Files:**
```html
<!-- /public/components/header.html -->
<nav>
    <a href="/">Home</a>
    <a href="/shop.html">Shop</a>

    <!-- Only show to admins -->
    <a href="/admin-dashboard.html" id="admin-nav-link" class="hidden">
        Admin Dashboard
    </a>

    <!-- Only show to approved vendors -->
    <a href="/vendor-dashboard.html" id="vendor-nav-link" class="hidden">
        Vendor Dashboard
    </a>
</nav>
```

**Acceptance Criteria:**
- [ ] Customer does not see admin links
- [ ] Pending vendor does not see vendor dashboard link
- [ ] Approved vendor sees vendor dashboard
- [ ] Admin sees admin dashboard
- [ ] No 403 errors from clicking visible buttons

**Commit Message:**
```
feat(frontend): align UI visibility with backend permissions

- Created PermissionGuard class for frontend permission checks
- Hide admin/vendor links based on user permissions
- Prevent 403 errors from clicking unauthorized links
- Sync frontend state with backend RBAC

Part of: RBAC Normalization (Phase 1)
```

**Estimated Time:** 3 hours

---

### Phase 1 Completion Checklist

- [ ] All users migrated to Spatie roles
- [ ] Legacy `users.role` column dropped
- [ ] All controllers use Spatie roles only
- [ ] Split permissions created (own vs all)
- [ ] Frontend visibility aligned with permissions
- [ ] Database backup created before migration
- [ ] All tests passing
- [ ] Deploy to staging
- [ ] QA approval
- [ ] Production deployment
- [ ] Tag release: `v1.1.0-rbac-normalization`

### Phase 1 Success Metrics

- [ ] Authorization score: 7/10 → 8/10
- [ ] Zero legacy role checks in codebase
- [ ] All permission checks use Spatie
- [ ] Frontend matches backend permissions

---

## Phase 2: Feature Completion

### Goals
- Fix all 8 broken/incomplete features
- Complete vendor dashboard redirect
- Implement email verification
- Fix loyalty points failures
- Add vendor commission calculation
- Complete social login
- Add soft deletes
- Implement time-based order cancellation

### Duration
**4-5 days** (can parallelize some tasks)

### Prerequisites
- [ ] Phase 1 completed and deployed
- [ ] All existing features tested and working

### Tasks

---

#### **Task 2.1: Implement Vendor Dashboard Redirect Logic**

**Problem:** Frontend doesn't redirect vendors based on status

**Current Behavior:**
Approved vendor can accidentally visit `/vendor-pending.html` and vice versa

**Expected Behavior:**
Auto-redirect based on vendor status:
- Approved → `/vendor-dashboard.html`
- Pending → `/vendor-pending.html`
- Rejected/Suspended → `/` with message
- No vendor profile → `/`

**Files to Modify:**
1. `/public/assets/js/app.js`

**Code:**
```javascript
// Add to app.js after login
async function redirectBasedOnVendorStatus(user) {
    if (!user.vendor) {
        // User is not a vendor, allow normal access
        return;
    }

    const currentPath = window.location.pathname;
    const status = user.vendor.status;

    switch (status) {
        case 'approved':
            if (currentPath === '/vendor-pending.html' || currentPath === '/login.html') {
                window.location.href = '/vendor-dashboard.html';
            }
            break;

        case 'pending':
            if (currentPath === '/vendor-dashboard.html') {
                window.location.href = '/vendor-pending.html';
            }
            break;

        case 'rejected':
        case 'suspended':
            if (currentPath.startsWith('/vendor-')) {
                alert(`Your vendor account is ${status}. Please contact support.`);
                window.location.href = '/';
            }
            break;
    }
}

// Call after fetching user
document.addEventListener('DOMContentLoaded', async () => {
    const user = await fetchCurrentUser();
    if (user) {
        await redirectBasedOnVendorStatus(user);
    }
});
```

**Acceptance Criteria:**
- [ ] Approved vendor accessing `/vendor-pending.html` redirects to dashboard
- [ ] Pending vendor accessing `/vendor-dashboard.html` redirects to pending page
- [ ] Rejected vendor accessing vendor pages redirects to home with alert
- [ ] Non-vendors can access all pages

**Commit Message:**
```
feat(vendor): implement vendor status-based redirect logic

- Auto-redirect approved vendors to dashboard
- Auto-redirect pending vendors to pending page
- Block rejected/suspended vendors from vendor pages
- Improve UX with status-aware routing

Fixes: Broken vendor dashboard redirect
```

**Estimated Time:** 2 hours

---

#### **Task 2.2: Implement Email Verification Flow**

**Problem:** Users can access everything without verifying email

**Current Behavior:**
`email_verified_at` field exists but unused

**Expected Behavior:**
Users must verify email before accessing protected features

**Files to Modify:**
1. `/app/Models/User.php`
2. `/routes/api.php`
3. Create `/app/Http/Controllers/Api/EmailVerificationController.php`

**Changes:**
```php
// User.php
use Illuminate\Contracts\Auth\MustVerifyEmail;

class User extends Authenticatable implements MustVerifyEmail
{
    // ... existing code
}

// EmailVerificationController.php
class EmailVerificationController extends Controller
{
    public function send(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified.']);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification email sent.']);
    }

    public function verify(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json(['error' => 'Invalid verification link.'], 400);
        }

        if ($user->hasVerifiedEmail()) {
            return redirect('/profile.html?verified=already');
        }

        $user->markEmailAsVerified();

        return redirect('/profile.html?verified=success');
    }
}

// routes/api.php
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/email/verify/send', [EmailVerificationController::class, 'send']);
    Route::get('/email/verify/{id}/{hash}', [EmailVerificationController::class, 'verify'])
        ->name('verification.verify');
});
```

**Acceptance Criteria:**
- [ ] Email sent on registration
- [ ] User can resend verification email
- [ ] Clicking link verifies email
- [ ] Verified users can access all features
- [ ] Unverified users see "verify email" banner

**Commit Message:**
```
feat(auth): implement email verification flow

- Users must verify email on registration
- Added resend verification email endpoint
- Email verification link via signed URL
- MustVerifyEmail interface on User model

Fixes: Email verification not enforced
```

**Estimated Time:** 3 hours

---

#### **Task 2.3: Fix Loyalty Points Silent Failures**

**Problem:** Signup bonus fails silently, user never notified

**Current Behavior:**
```php
try {
    $loyaltyService->awardPoints(...);
} catch (\Exception $e) {
    Log::error(...); // SILENT FAILURE
}
```

**Expected Behavior:**
- If loyalty points fail, notify user
- Create admin notification for manual resolution
- Include loyalty status in API response

**Files to Modify:**
1. `/app/Http/Controllers/Api/AuthController.php`
2. `/app/Services/LoyaltyService.php`

**Changes:**
```php
// AuthController.php
public function register(RegisterRequest $request): JsonResponse
{
    // ... create user ...

    $loyaltyBonus = 0;
    $loyaltyPending = false;

    try {
        $loyaltyService = app(LoyaltyService::class);
        $loyaltyService->awardPoints($user->id, 50, 'signup_bonus', null, 'Welcome Bonus');
        $loyaltyBonus = 50;
    } catch (\Exception $e) {
        Log::error('Failed to award signup points: ' . $e->getMessage(), [
            'user_id' => $user->id,
            'email' => $user->email,
        ]);

        // Create admin notification
        \App\Models\AdminNotification::create([
            'type' => 'loyalty_failure',
            'user_id' => $user->id,
            'message' => "Failed to award signup bonus to {$user->email}",
            'data' => ['error' => $e->getMessage()],
        ]);

        $loyaltyPending = true;
    }

    return $this->successResponse([
        'message' => 'Registration successful',
        'user' => [...],
        'token' => $token->plainTextToken,
        'loyalty' => [
            'points_awarded' => $loyaltyBonus,
            'pending' => $loyaltyPending,
            'message' => $loyaltyPending
                ? 'Your welcome bonus is being processed and will be added soon.'
                : 'You received 50 welcome bonus points!',
        ],
    ], 'Registration successful', 201);
}
```

**Acceptance Criteria:**
- [ ] User sees loyalty status in response
- [ ] If failed, user notified it's pending
- [ ] Admin notification created on failure
- [ ] Admin can manually award points
- [ ] User receives email when points awarded

**Commit Message:**
```
fix(loyalty): notify user when signup bonus fails

- Include loyalty status in registration response
- Create admin notification on failure
- User sees "pending" message if bonus failed
- Admin can manually resolve failed bonuses

Fixes: Loyalty points silent failure
```

**Estimated Time:** 2 hours

---

#### **Task 2.4: Implement Vendor Commission Calculation**

**Problem:** Vendor commission not deducted on orders

**Current Behavior:**
Vendors earn full order amount, platform earns nothing

**Expected Behavior:**
- Calculate commission on each order item
- Create `VendorTransaction` record
- Track platform revenue
- Display in vendor wallet

**Files to Modify:**
1. `/app/Services/OrderService.php`
2. `/app/Http/Controllers/Api/VendorWalletController.php`

**Changes:**
```php
// OrderService.php
public function createOrder(array $data)
{
    // ... create order and order items ...

    // Calculate vendor commissions
    foreach ($order->items as $item) {
        if ($item->product->vendor_id) {
            $vendor = $item->product->vendor;
            $itemTotal = $item->price * $item->quantity;
            $commissionRate = $vendor->commission_rate; // e.g., 10.00
            $commissionAmount = $itemTotal * ($commissionRate / 100);
            $vendorEarnings = $itemTotal - $commissionAmount;

            // Create vendor transaction
            VendorTransaction::create([
                'vendor_id' => $vendor->id,
                'order_id' => $order->id,
                'order_item_id' => $item->id,
                'type' => 'sale',
                'amount' => $vendorEarnings,
                'commission' => $commissionAmount,
                'commission_rate' => $commissionRate,
                'status' => 'pending', // Paid out after order delivered
                'description' => "Sale of {$item->product->name}",
            ]);

            Log::info("Created vendor transaction", [
                'vendor_id' => $vendor->id,
                'order_id' => $order->id,
                'earnings' => $vendorEarnings,
                'commission' => $commissionAmount,
            ]);
        }
    }

    return $order;
}
```

**Acceptance Criteria:**
- [ ] Commission calculated on all vendor products
- [ ] VendorTransaction created for each item
- [ ] Vendor sees earnings in wallet (after commission)
- [ ] Platform can track total commission earned
- [ ] Commission rate is per-vendor configurable

**Commit Message:**
```
feat(vendor): implement commission calculation on orders

- Calculate vendor commission on each order item
- Create VendorTransaction records
- Track platform revenue from commissions
- Display net earnings in vendor wallet

Fixes: Missing vendor commission calculation
```

**Estimated Time:** 3 hours

---

#### **Task 2.5: Complete Social Login Integration**

**Problem:** Social login routes exist but not in API routes

**Current Behavior:**
Google login button exists but may redirect to web routes

**Expected Behavior:**
API-based social login flow

**Files to Modify:**
1. `/routes/api.php`
2. `/app/Http/Controllers/Api/Auth/SocialAuthController.php`

**Changes:**
```php
// routes/api.php
Route::prefix('auth/social')->group(function () {
    Route::get('/{provider}', [SocialAuthController::class, 'redirect']);
    Route::get('/{provider}/callback', [SocialAuthController::class, 'callback']);
});

// SocialAuthController.php
public function redirect($provider)
{
    if (!in_array($provider, ['google', 'facebook'])) {
        return response()->json(['error' => 'Invalid provider'], 400);
    }

    return Socialite::driver($provider)->stateless()->redirect();
}

public function callback($provider)
{
    try {
        $socialUser = Socialite::driver($provider)->stateless()->user();

        // Find or create user
        $user = User::where('email', $socialUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'google_id' => $socialUser->getId(),
                'avatar' => $socialUser->getAvatar(),
                'email_verified_at' => now(), // Auto-verify social logins
            ]);

            $user->assignRole('customer');

            // Award signup bonus
            try {
                $loyaltyService = app(LoyaltyService::class);
                $loyaltyService->awardPoints($user->id, 50, 'signup_bonus', null, 'Welcome Bonus');
            } catch (\Exception $e) {
                Log::error('Loyalty bonus failed for social login: ' . $e->getMessage());
            }
        }

        // Generate token
        $token = $user->createToken('api-token', ['*'], now()->addMinutes(1440));

        return response()->json([
            'success' => true,
            'user' => $user,
            'token' => $token->plainTextToken,
            'message' => 'Login successful',
        ]);
    } catch (\Exception $e) {
        Log::error('Social login failed: ' . $e->getMessage());
        return response()->json(['error' => 'Authentication failed'], 500);
    }
}
```

**Acceptance Criteria:**
- [ ] Google login works end-to-end
- [ ] New users created with customer role
- [ ] Existing users can login via Google
- [ ] Token generated and returned
- [ ] Email auto-verified for social logins

**Commit Message:**
```
feat(auth): complete Google social login integration

- Added API routes for social auth
- Implemented callback handler
- Auto-create user on first social login
- Auto-verify email for social logins
- Award signup bonus for new social users

Fixes: Incomplete social login implementation
```

**Estimated Time:** 3 hours

---

#### **Task 2.6: Implement Soft Deletes**

**Problem:** Hard deletes cause data loss

**Current Behavior:**
Deleting user cascades and deletes all their data

**Expected Behavior:**
Soft delete users, products, orders, vendors

**Files to Modify:**
1. `/app/Models/User.php`, `/app/Models/Product.php`, `/app/Models/Order.php`, `/app/Models/Vendor.php`
2. Create migration: `/database/migrations/2025_12_27_add_soft_deletes_to_critical_tables.php`

**Migration:**
```php
public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->softDeletes();
    });

    Schema::table('products', function (Blueprint $table) {
        $table->softDeletes();
    });

    Schema::table('orders', function (Blueprint $table) {
        $table->softDeletes();
    });

    Schema::table('vendors', function (Blueprint $table) {
        $table->softDeletes();
    });
}
```

**Model Changes:**
```php
// User.php
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use SoftDeletes;
    // ... rest of model
}

// Repeat for Product, Order, Vendor
```

**Controller Changes:**
```php
// UserController.php
public function destroy(int $id): JsonResponse
{
    // ... permission checks ...

    $user->delete(); // Now a soft delete

    return response()->json([
        'success' => true,
        'message' => 'User soft-deleted successfully.',
    ]);
}

// Add force delete method (Super Admin only)
public function forceDestroy(int $id): JsonResponse
{
    if (!auth()->user()->can('users.delete')) {
        return $this->forbiddenResponse();
    }

    $user = User::withTrashed()->findOrFail($id);
    $user->forceDelete(); // Permanent delete

    return response()->json([
        'success' => true,
        'message' => 'User permanently deleted.',
    ]);
}

// Add restore method
public function restore(int $id): JsonResponse
{
    if (!auth()->user()->can('users.delete')) {
        return $this->forbiddenResponse();
    }

    $user = User::withTrashed()->findOrFail($id);
    $user->restore();

    return response()->json([
        'success' => true,
        'message' => 'User restored successfully.',
    ]);
}
```

**Acceptance Criteria:**
- [ ] Deleted users not shown in queries
- [ ] Deleted users can be restored
- [ ] Super Admin can permanently delete
- [ ] Order history preserved when user deleted
- [ ] Vendor products hidden when vendor deleted

**Commit Message:**
```
feat(data): implement soft deletes for critical models

- Added soft deletes to users, products, orders, vendors
- Deleted records can be restored
- Super Admin can force delete (permanent)
- Prevents data loss from accidental deletion

Fixes: Hard deletes causing data loss
```

**Estimated Time:** 2 hours

---

#### **Task 2.7: Implement Time-Based Order Cancellation**

**Problem:** Customers can cancel orders anytime

**Current Behavior:**
Customer can cancel order even if already shipped

**Expected Behavior:**
Customer can only cancel within 24 hours of placing order

**Files to Modify:**
1. `/app/Policies/OrderPolicy.php`

**Changes:**
```php
public function cancel(User $user, Order $order): bool
{
    // Admin can cancel anytime
    if ($user->can('orders.cancel')) {
        return true;
    }

    // Must be own order
    if ($order->user_id !== $user->id) {
        return false;
    }

    // Must be pending or processing
    if (!in_array($order->status, ['pending', 'processing'])) {
        return false;
    }

    // Must be within 24 hours
    $hoursSinceOrder = $order->created_at->diffInHours(now());
    if ($hoursSinceOrder > 24) {
        return false;
    }

    return true;
}
```

**Update OrderController:**
```php
public function cancel(Request $request, $id): JsonResponse
{
    $order = Order::findOrFail($id);

    // Check policy
    if (!$request->user()->can('cancel', $order)) {
        $hoursSinceOrder = $order->created_at->diffInHours(now());

        if ($hoursSinceOrder > 24) {
            return $this->forbiddenResponse(
                'Orders can only be cancelled within 24 hours of placing.',
                'CANCELLATION_WINDOW_EXPIRED'
            );
        }

        return $this->forbiddenResponse('You cannot cancel this order.');
    }

    // Cancel order logic...
}
```

**Acceptance Criteria:**
- [ ] Customer can cancel within 24 hours (200)
- [ ] Customer cannot cancel after 24 hours (403)
- [ ] Admin can cancel anytime (200)
- [ ] Error message explains time limit

**Commit Message:**
```
feat(orders): add time-based cancellation policy

- Customers can only cancel within 24 hours
- Admin can cancel anytime
- Clear error message when cancellation window expired
- Prevents abuse of cancellation system

Fixes: Order cancellation policy not enforced
```

**Estimated Time:** 1 hour

---

#### **Task 2.8: Fix Category Management Missing Feature**

**Problem:** Category permissions exist but no management endpoints

**Current Behavior:**
- Permissions: `categories.create`, `categories.update`, etc.
- No admin endpoints to manage categories

**Expected Behavior:**
Either implement category management or remove unused permissions

**Decision:** Remove unused permissions (categories are static/seeded)

**Files to Modify:**
1. `/database/seeders/RolePermissionSeeder.php`

**Changes:**
```php
// Remove category permissions from seeder
$permissions = [
    // ... other permissions ...

    // REMOVE THESE:
    // 'categories.view',
    // 'categories.create',
    // 'categories.update',
    // 'categories.delete',
];

// Remove from roles
$admin->givePermissionTo([
    // ... other permissions ...
    // Remove category permissions
]);
```

**Alternative (if categories should be managed):**
Create `/app/Http/Controllers/Api/Admin/CategoryController.php` with full CRUD

**Acceptance Criteria:**
- [ ] Unused permissions removed from seeder
- [ ] Categories still accessible via public API
- [ ] Admin can manage categories via database only

**Commit Message:**
```
refactor(rbac): remove unused category management permissions

- Categories are static/seeded, not managed via UI
- Removed unused category CRUD permissions
- Categories still accessible via public API
- Reduces permission complexity

Part of: Feature Completion (Phase 2)
```

**Estimated Time:** 30 minutes

---

### Phase 2 Completion Checklist

- [ ] All 8 tasks completed
- [ ] Vendor dashboard redirect working
- [ ] Email verification flow complete
- [ ] Loyalty points failures handled gracefully
- [ ] Vendor commissions calculated correctly
- [ ] Social login working end-to-end
- [ ] Soft deletes implemented
- [ ] Time-based order cancellation enforced
- [ ] All tests passing
- [ ] Deploy to staging
- [ ] QA approval
- [ ] Production deployment
- [ ] Tag release: `v1.2.0-feature-complete`

### Phase 2 Success Metrics

- [ ] Feature completeness: 73% → 100%
- [ ] Zero broken features
- [ ] All workflows complete end-to-end
- [ ] User satisfaction improved

---

## Phase 3: Hardening & Finalization

### Goals
- Add comprehensive test coverage
- Standardize API responses
- Add API documentation
- Clean up unused code
- Performance optimization
- Final security audit

### Duration
**3-4 days**

### Prerequisites
- [ ] Phase 2 completed
- [ ] All features working

### Tasks

---

#### **Task 3.1: Add RBAC Test Suite**

**Problem:** No automated tests for authorization

**Expected Behavior:**
100% test coverage for all permission checks

**Files to Create:**
1. `/tests/Feature/AuthorizationTest.php`
2. `/tests/Feature/VendorAccessTest.php`
3. `/tests/Feature/AdminAccessTest.php`

**Test Examples:**
```php
// AuthorizationTest.php
class AuthorizationTest extends TestCase
{
    public function test_customer_cannot_access_admin_routes()
    {
        $customer = User::factory()->create();
        $customer->assignRole('customer');

        $this->actingAs($customer)
            ->getJson('/api/admin/users')
            ->assertStatus(403);
    }

    public function test_admin_can_view_users()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin)
            ->getJson('/api/admin/users')
            ->assertStatus(200);
    }

    public function test_vendor_can_only_update_own_products()
    {
        $vendor1 = Vendor::factory()->create();
        $vendor2 = Vendor::factory()->create();
        $product = Product::factory()->create(['vendor_id' => $vendor2->id]);

        $this->actingAs($vendor1->user)
            ->putJson("/api/vendor/products/{$product->id}", ['name' => 'Hacked'])
            ->assertStatus(404);
    }

    // ... 50+ more test cases
}
```

**Acceptance Criteria:**
- [ ] 100+ test cases for authorization
- [ ] All roles tested (super_admin, admin, manager, vendor, customer)
- [ ] All permissions tested
- [ ] Ownership checks tested
- [ ] All tests passing

**Commit Message:**
```
test(rbac): add comprehensive authorization test suite

- 100+ test cases for all roles and permissions
- Ownership validation tests
- Permission boundary tests
- 100% coverage of authorization logic

Part of: Hardening & Finalization (Phase 3)
```

**Estimated Time:** 1 day

---

#### **Task 3.2: Standardize API Response Format**

**Problem:** Inconsistent API responses across controllers

**Expected Behavior:**
All APIs use same response format

**Files to Modify:**
1. All controllers using raw `response()->json()`
2. Ensure all use `ApiResponse` trait

**Standard Format:**
```json
{
    "success": true,
    "data": {...},
    "message": "Operation successful",
    "meta": {
        "pagination": {...},
        "filters": {...}
    }
}
```

**Error Format:**
```json
{
    "success": false,
    "error": {
        "code": "ERROR_CODE",
        "message": "User-friendly message",
        "details": {...}
    }
}
```

**Acceptance Criteria:**
- [ ] All controllers use `ApiResponse` trait
- [ ] No raw `response()->json()` calls
- [ ] Consistent structure across all endpoints
- [ ] Frontend can parse all responses uniformly

**Commit Message:**
```
refactor(api): standardize all API response formats

- All controllers use ApiResponse trait
- Consistent success/error structure
- Uniform pagination format
- Easier frontend integration

Part of: Hardening & Finalization (Phase 3)
```

**Estimated Time:** 4 hours

---

#### **Task 3.3: Generate API Documentation**

**Problem:** No API documentation for frontend/external devs

**Expected Behavior:**
Auto-generated API docs (OpenAPI/Swagger)

**Tools:**
- Use `darkaonline/l5-swagger` package

**Implementation:**
```bash
composer require darkaonline/l5-swagger
php artisan vendor:publish --provider="L5Swagger\L5SwaggerServiceProvider"
php artisan l5-swagger:generate
```

**Add annotations to controllers:**
```php
/**
 * @OA\Get(
 *     path="/api/products",
 *     tags={"Products"},
 *     summary="Get all products",
 *     @OA\Response(response=200, description="List of products")
 * )
 */
public function index() { ... }
```

**Acceptance Criteria:**
- [ ] OpenAPI spec generated
- [ ] All endpoints documented
- [ ] Request/response examples included
- [ ] Authentication documented
- [ ] Accessible at `/api/documentation`

**Commit Message:**
```
docs(api): generate OpenAPI documentation

- Auto-generated API docs with Swagger
- All endpoints documented with examples
- Authentication flow documented
- Accessible at /api/documentation

Part of: Hardening & Finalization (Phase 3)
```

**Estimated Time:** 1 day

---

#### **Task 3.4: Clean Up Unused Code**

**Problem:** Unused middleware, routes, and code

**Expected Behavior:**
Codebase contains only used code

**Cleanup Checklist:**
- [ ] Remove unused middleware aliases (`admin`, `isAdmin`)
- [ ] Remove unused imports
- [ ] Remove commented code
- [ ] Remove unused routes
- [ ] Remove dead code (unreachable branches)

**Tools:**
```bash
# Find unused imports
composer require nunomaduro/phpinsights
php artisan insights

# Find dead code
composer require phpstan/phpstan
vendor/bin/phpstan analyse app
```

**Acceptance Criteria:**
- [ ] No unused middleware aliases
- [ ] No commented code
- [ ] No dead code
- [ ] Code quality score: 9/10

**Commit Message:**
```
refactor: remove unused code and clean up codebase

- Removed unused middleware aliases
- Cleaned up dead code
- Removed commented sections
- Standardized code style

Part of: Hardening & Finalization (Phase 3)
```

**Estimated Time:** 4 hours

---

#### **Task 3.5: Performance Optimization**

**Problem:** Potential N+1 queries, slow endpoints

**Expected Behavior:**
All endpoints respond < 200ms (95th percentile)

**Optimizations:**
1. Add eager loading to prevent N+1 queries
2. Add database indexes
3. Implement caching for static data
4. Optimize images

**Example:**
```php
// Before (N+1 query)
$orders = Order::all();
foreach ($orders as $order) {
    echo $order->user->name; // Query for each order
}

// After (eager loading)
$orders = Order::with('user')->all();
foreach ($orders as $order) {
    echo $order->user->name; // No additional queries
}
```

**Acceptance Criteria:**
- [ ] No N+1 queries
- [ ] Database indexes on foreign keys
- [ ] Cache for categories, settings
- [ ] All endpoints < 200ms response time

**Commit Message:**
```
perf: optimize database queries and add caching

- Added eager loading to prevent N+1 queries
- Database indexes on foreign keys
- Cache static data (categories, settings)
- 95th percentile response time < 200ms

Part of: Hardening & Finalization (Phase 3)
```

**Estimated Time:** 1 day

---

#### **Task 3.6: Final Security Audit**

**Problem:** Need to verify all security fixes are in place

**Expected Behavior:**
Third-party security scan passes with no critical issues

**Security Checklist:**
- [ ] All inputs sanitized
- [ ] All queries use parameter binding (no SQL injection)
- [ ] All file uploads validated
- [ ] CSRF protection enabled
- [ ] XSS prevention (no raw HTML output)
- [ ] Rate limiting on all auth endpoints
- [ ] Security headers present
- [ ] HTTPS enforced (production)
- [ ] Secrets not in code (use .env)
- [ ] Dependencies up-to-date (no known vulnerabilities)

**Tools:**
```bash
# Security scan
composer require enlightn/security-checker
php artisan security:check

# Dependency vulnerabilities
composer audit
```

**Acceptance Criteria:**
- [ ] Security scan passes
- [ ] No critical vulnerabilities
- [ ] All dependencies updated
- [ ] Security score: 9/10

**Commit Message:**
```
security: final security audit and hardening

- All security checks passing
- Dependencies updated
- No known vulnerabilities
- Security score: 9/10

Part of: Hardening & Finalization (Phase 3)
```

**Estimated Time:** 4 hours

---

### Phase 3 Completion Checklist

- [ ] 100+ authorization tests passing
- [ ] API responses standardized
- [ ] API documentation generated
- [ ] Unused code removed
- [ ] Performance optimized
- [ ] Security audit passed
- [ ] All tests passing (unit + feature + integration)
- [ ] Deploy to staging
- [ ] Full QA regression testing
- [ ] Performance testing (load testing)
- [ ] Security penetration testing
- [ ] Production deployment
- [ ] Tag release: `v2.0.0-production-ready`

### Phase 3 Success Metrics

- [ ] Code quality: 7/10 → 9/10
- [ ] Test coverage: 0% → 80%+
- [ ] Performance: < 200ms response time
- [ ] Security: 9/10
- [ ] Overall: 9+/10 - PRODUCTION READY

---

## Definition of DONE

### Technical Criteria

#### **Code Must:**
- [ ] Have zero critical security vulnerabilities
- [ ] Use only Spatie roles (no legacy `users.role` checks)
- [ ] Have ownership validation on all vendor operations
- [ ] Have permission checks on all protected routes
- [ ] Use standardized API response format
- [ ] Have soft deletes on critical models
- [ ] Have 80%+ test coverage on authorization logic
- [ ] Pass all automated tests (PHPUnit)
- [ ] Pass security audit (no critical issues)
- [ ] Have response times < 200ms (95th percentile)

#### **Database Must:**
- [ ] Have no `users.role` column
- [ ] Have `deleted_at` on users, products, orders, vendors
- [ ] Have indexes on all foreign keys
- [ ] Have all Spatie permission tables populated

#### **Documentation Must:**
- [ ] Have OpenAPI spec for all endpoints
- [ ] Have README with setup instructions
- [ ] Have inline code comments on complex logic
- [ ] Have ERD (entity relationship diagram)
- [ ] Have deployment guide

#### **Frontend Must:**
- [ ] Check permissions before showing UI elements
- [ ] Redirect vendors based on approval status
- [ ] Show email verification banner if unverified
- [ ] Handle all API error codes gracefully
- [ ] Display loading states
- [ ] Show user-friendly error messages

#### **Features Must:**
- [ ] All 5 roles functional (super_admin, admin, manager, vendor, customer)
- [ ] All 29 permissions enforced
- [ ] Vendor commission calculated on orders
- [ ] Loyalty points awarded correctly
- [ ] Email verification working
- [ ] Social login (Google) working
- [ ] Order cancellation enforced (24-hour window)
- [ ] Review purchase verification working

### Operational Criteria

#### **Deployment Must:**
- [ ] Have CI/CD pipeline (GitHub Actions / GitLab CI)
- [ ] Have staging environment
- [ ] Have production environment
- [ ] Have rollback procedure documented
- [ ] Have monitoring (error tracking, performance)
- [ ] Have automated backups (database)

#### **Security Must:**
- [ ] Use HTTPS in production
- [ ] Have rate limiting on auth endpoints
- [ ] Have security headers (CSP, HSTS, etc.)
- [ ] Have environment variables for secrets
- [ ] Have SQL injection protection (parameter binding)
- [ ] Have XSS protection (escaped output)
- [ ] Have CSRF protection on web routes

#### **Quality Must:**
- [ ] Pass code review (1+ reviewers)
- [ ] Pass QA testing (manual + automated)
- [ ] Pass performance testing (load testing)
- [ ] Pass security testing (penetration testing)
- [ ] Have zero known bugs in production

### Sign-Off Criteria

#### **Before Production Deployment:**
- [ ] Technical Lead approval
- [ ] Security Officer approval
- [ ] QA Manager approval
- [ ] Product Owner approval
- [ ] CTO approval (for first production release)

#### **After Production Deployment:**
- [ ] Monitor for 48 hours
- [ ] Zero critical errors
- [ ] User acceptance testing passed
- [ ] Performance metrics within SLA
- [ ] Security monitoring active

### Project is DONE when:

1. ✅ All 4 phases completed
2. ✅ All tasks in checklist marked complete
3. ✅ All acceptance criteria met
4. ✅ All tests passing
5. ✅ All documentation complete
6. ✅ Production deployment successful
7. ✅ 48-hour monitoring period passed
8. ✅ All stakeholders signed off

**At this point, the project can be confidently labeled:**

```
🎉 PRODUCTION COMPLETE 🎉
```

---

## Validation Checklists

### Security Validation

- [ ] **Authentication**
  - [ ] Sanctum tokens expire correctly
  - [ ] Token refresh works
  - [ ] Logout revokes tokens
  - [ ] Rate limiting prevents brute force
  - [ ] Password reset flow secure

- [ ] **Authorization**
  - [ ] All admin routes require permissions
  - [ ] All vendor routes check approval status
  - [ ] Ownership validated on vendor operations
  - [ ] Customers cannot access admin routes
  - [ ] No role bypass vulnerabilities

- [ ] **Data Protection**
  - [ ] All inputs sanitized
  - [ ] SQL injection prevention
  - [ ] XSS prevention
  - [ ] CSRF tokens on web routes
  - [ ] File upload validation

- [ ] **Infrastructure**
  - [ ] HTTPS enforced
  - [ ] Security headers present
  - [ ] Secrets in environment variables
  - [ ] Database encrypted at rest
  - [ ] Backups encrypted

### RBAC Validation

- [ ] **Roles**
  - [ ] Super Admin has all permissions
  - [ ] Admin has most permissions (not system)
  - [ ] Manager has update-only permissions
  - [ ] Vendor has product/order permissions
  - [ ] Customer has view-only permissions

- [ ] **Permissions**
  - [ ] All 29 permissions exist in database
  - [ ] All permissions assigned to correct roles
  - [ ] Permission checks in all controllers
  - [ ] Permission names descriptive (e.g., `orders.view.own`)

- [ ] **Middleware**
  - [ ] `auth:sanctum` on all protected routes
  - [ ] `ensure.permission` on admin routes
  - [ ] `vendor.approved` on vendor routes
  - [ ] Middleware order correct (auth before permission)

- [ ] **Frontend**
  - [ ] UI elements hidden based on permissions
  - [ ] No 403 errors from clicking visible buttons
  - [ ] Vendor status redirect working
  - [ ] Admin links only visible to admins

### Features Validation

- [ ] **Shopping Cart**
  - [ ] Add/remove items works
  - [ ] Quantity updates correctly
  - [ ] Cart persists across sessions
  - [ ] Cart clears after checkout

- [ ] **Orders**
  - [ ] Order creation works
  - [ ] Order cancellation enforced (24-hour window)
  - [ ] Admin can update order status
  - [ ] Vendor can fulfill orders
  - [ ] Email notifications sent

- [ ] **Reviews**
  - [ ] Purchase verification works
  - [ ] Duplicate review prevention works
  - [ ] Admin can approve/reject reviews
  - [ ] Review voting works

- [ ] **Loyalty Program**
  - [ ] Signup bonus awarded (50 points)
  - [ ] Purchase points calculated correctly
  - [ ] Points redemption works
  - [ ] Points expiration tracked

- [ ] **Vendor Features**
  - [ ] Vendor registration creates profile
  - [ ] Admin can approve/reject vendors
  - [ ] Approved vendors can manage products
  - [ ] Commission calculated on orders
  - [ ] Wallet shows correct balance

- [ ] **Email Verification**
  - [ ] Verification email sent
  - [ ] Verification link works
  - [ ] Resend verification works
  - [ ] Verified users have access

### API Validation

- [ ] **Response Format**
  - [ ] All success responses have `success: true`
  - [ ] All error responses have `success: false`
  - [ ] Error codes are consistent
  - [ ] Pagination format consistent

- [ ] **Error Handling**
  - [ ] 401 for unauthenticated
  - [ ] 403 for unauthorized
  - [ ] 404 for not found
  - [ ] 422 for validation errors
  - [ ] 500 for server errors (with logging)

- [ ] **Rate Limiting**
  - [ ] Registration: 10/min
  - [ ] Login: 5/min
  - [ ] Password reset: 5/min
  - [ ] Other endpoints: 60/min

- [ ] **Documentation**
  - [ ] OpenAPI spec generated
  - [ ] All endpoints documented
  - [ ] Request/response examples provided
  - [ ] Authentication documented

### Frontend-Backend Consistency

- [ ] **Data Sync**
  - [ ] User data matches between frontend/backend
  - [ ] Cart count matches actual cart items
  - [ ] Order status reflects database state
  - [ ] Vendor status synced

- [ ] **Permissions**
  - [ ] Frontend checks match backend checks
  - [ ] Hidden elements cannot be accessed via API
  - [ ] No client-side bypasses

- [ ] **Error Messages**
  - [ ] Backend errors displayed to user
  - [ ] User-friendly error messages
  - [ ] No technical details leaked

---

## Final Architecture Snapshot

### Role System (Final State)

```
┌─────────────────────────────────────────────────────────┐
│                    USER MODEL                            │
│  - No legacy role field                                  │
│  - Uses Spatie\Permission\Traits\HasRoles               │
│  - All role checks via hasRole()                        │
└─────────────────────────────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────┐
│               SPATIE ROLES (5 total)                     │
├─────────────────────────────────────────────────────────┤
│  1. super_admin (29 permissions)                         │
│  2. admin (21 permissions)                               │
│  3. manager (11 permissions)                             │
│  4. vendor (9 permissions)                               │
│  5. customer (5 permissions - view only)                 │
└─────────────────────────────────────────────────────────┘
                           │
                           ▼
┌─────────────────────────────────────────────────────────┐
│           PERMISSIONS (29 total)                         │
├─────────────────────────────────────────────────────────┤
│  Products: view.own, view.all, create, update, delete,  │
│            manage                                        │
│  Orders: view.own, view.all, update, delete, cancel     │
│  Users: view, create, update, delete, manage            │
│  Reviews: view, approve, delete, manage                 │
│  Analytics: view, export                                │
│  System: audit.view, settings, backup                   │
│  Vendor: access, profile.update                         │
└─────────────────────────────────────────────────────────┘
```

### Permission Strategy

**Principle: Least Privilege**
- Users get minimum permissions needed
- Separate "own" vs "all" permissions
- Vendor-specific permissions isolated
- System permissions only for super_admin

**Naming Convention:**
```
{resource}.{action}.{scope}

Examples:
- products.view.own    (vendor sees own products)
- products.view.all    (admin sees all products)
- orders.cancel        (no scope = applies to own + permission check)
```

### Authorization Flow (Final)

```
┌─────────────────┐
│  API Request    │
└────────┬────────┘
         │
         ▼
┌─────────────────────────────────────────┐
│  Layer 1: Sanctum Authentication        │
│  - Check Bearer token                   │
│  - Validate token expiration            │
│  - Load user                            │
└────────┬────────────────────────────────┘
         │
         ▼
┌─────────────────────────────────────────┐
│  Layer 2: Vendor Approval (if vendor)   │
│  - Check vendor profile exists          │
│  - Check status = 'approved'            │
│  - Check has vendor role                │
└────────┬────────────────────────────────┘
         │
         ▼
┌─────────────────────────────────────────┐
│  Layer 3: Permission Check              │
│  - Check $user->can('permission.name')  │
│  - Supports OR logic (perm1|perm2)      │
└────────┬────────────────────────────────┘
         │
         ▼
┌─────────────────────────────────────────┐
│  Layer 4: Policy Check (if applicable)  │
│  - Business logic validation            │
│  - Ownership check                      │
│  - Time-based rules                     │
└────────┬────────────────────────────────┘
         │
         ▼
┌─────────────────┐
│  Controller     │
│  Action         │
└─────────────────┘
```

### Folder Structure (Recommended)

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       ├── Auth/
│   │       │   ├── AuthController.php
│   │       │   └── SocialAuthController.php
│   │       ├── Admin/
│   │       │   ├── UserController.php
│   │       │   ├── ProductController.php
│   │       │   ├── AnalyticsController.php
│   │       │   └── AuditLogController.php
│   │       ├── Vendor/
│   │       │   ├── ProductController.php
│   │       │   ├── OrderController.php
│   │       │   └── WalletController.php
│   │       └── [Public Controllers]
│   ├── Middleware/
│   │   ├── EnsurePermission.php
│   │   ├── EnsureRole.php (DEPRECATED - use Spatie)
│   │   └── EnsureVendorApproved.php
│   └── Requests/
├── Models/
│   ├── User.php (with HasRoles trait)
│   ├── Vendor.php
│   ├── Product.php
│   └── Order.php
├── Policies/
│   ├── OrderPolicy.php
│   ├── ProductPolicy.php
│   └── ReviewPolicy.php
├── Services/
│   ├── OrderService.php
│   ├── LoyaltyService.php
│   └── CartService.php
└── Traits/
    └── ApiResponse.php

database/
├── migrations/
│   ├── *_create_users_table.php
│   ├── *_create_permission_tables.php (Spatie)
│   ├── *_add_soft_deletes_to_critical_tables.php
│   └── *_migrate_legacy_roles_to_spatie.php
└── seeders/
    ├── RolePermissionSeeder.php (CRITICAL - defines all roles/permissions)
    └── UserSeeder.php

routes/
├── api.php (ALL API routes with middleware)
└── web.php (Frontend routes)

tests/
├── Feature/
│   ├── AuthorizationTest.php
│   ├── VendorAccessTest.php
│   └── AdminAccessTest.php
└── Unit/
    └── PermissionTest.php

docs/
├── COMPREHENSIVE_ROLE_ANALYSIS.md (Master doc)
├── SECURITY_EXECUTIVE_SUMMARY.md (Security summary)
├── RBAC_QUICK_REFERENCE.md (Dev cheat sheet)
└── PROJECT_COMPLETION_ROADMAP.md (This doc)
```

---

## Risk Management

### Phase 0 Risks

| Risk | Impact | Probability | Mitigation |
|------|--------|-------------|------------|
| Breaking existing auth | HIGH | MEDIUM | Thorough testing, rollback plan |
| Users locked out | HIGH | LOW | Ensure all users have Spatie roles before deploy |
| Performance degradation | MEDIUM | LOW | Load testing on staging |

### Phase 1 Risks

| Risk | Impact | Probability | Mitigation |
|------|--------|-------------|------------|
| Data loss during migration | HIGH | LOW | Database backup before migration |
| Frontend breaks without legacy role | MEDIUM | MEDIUM | Update frontend before backend deploy |
| Permission gaps | MEDIUM | MEDIUM | Comprehensive testing |

### Phase 2 Risks

| Risk | Impact | Probability | Mitigation |
|------|--------|-------------|------------|
| Commission calculation errors | HIGH | MEDIUM | Manual verification before go-live |
| Email delivery failures | MEDIUM | MEDIUM | Queue emails, monitor delivery |
| Soft delete breaks queries | MEDIUM | LOW | Add `withTrashed()` where needed |

### Phase 3 Risks

| Risk | Impact | Probability | Mitigation |
|------|--------|-------------|------------|
| Performance regression | MEDIUM | LOW | Load testing, monitoring |
| Test suite too slow | LOW | MEDIUM | Parallelize tests |
| Documentation drift | LOW | MEDIUM | Auto-generate from code |

---

## Timeline & Resource Allocation

### Gantt Chart (Text Format)

```
Week 1:
  Phase 0 (Security)      [■■■■■■■■■■] 2 days (1 dev)

Week 1-2:
  Phase 1 (RBAC)          [■■■■■■■■■■■■■■] 3 days (1 dev)

Week 2-3:
  Phase 2 (Features)      [■■■■■■■■■■■■■■■■■■■■] 5 days (2 devs)

Week 3-4:
  Phase 3 (Hardening)     [■■■■■■■■■■■■■■■■] 4 days (1 dev + 1 QA)

Total: 14 working days (3 weeks)
```

### Resource Requirements

| Phase | Backend Dev | Frontend Dev | QA | DevOps | Total Person-Days |
|-------|-------------|--------------|----|---------|--------------------|
| Phase 0 | 1 | 0 | 0.5 | 0.5 | 2 |
| Phase 1 | 1 | 0.5 | 0.5 | 0 | 3 |
| Phase 2 | 1 | 1 | 1 | 0 | 5 |
| Phase 3 | 0.5 | 0.5 | 1 | 0.5 | 4 |
| **Total** | **3.5** | **2** | **2** | **1** | **14** |

---

## Success Metrics & KPIs

### Before Execution

| Metric | Current | Target | Measurement |
|--------|---------|--------|-------------|
| Security Vulnerabilities | 7 critical | 0 critical | Manual audit |
| Authorization Score | 4/10 | 9/10 | Comprehensive testing |
| Feature Completeness | 73% | 100% | Feature checklist |
| Test Coverage (Auth) | 0% | 80%+ | PHPUnit coverage report |
| Code Quality | 7/10 | 9/10 | PHPInsights |

### After Phase 0

| Metric | Target |
|--------|--------|
| Critical Vulnerabilities | 0 |
| Authorization Score | 7/10 |
| Deployment Time | < 1 hour |
| Zero Downtime | ✅ |

### After Phase 1

| Metric | Target |
|--------|--------|
| Legacy Role Usage | 0% |
| Permission Checks | 100% via Spatie |
| Frontend-Backend Sync | 100% |

### After Phase 2

| Metric | Target |
|--------|--------|
| Broken Features | 0 |
| Feature Completeness | 100% |
| User Satisfaction | 8/10+ |

### After Phase 3

| Metric | Target |
|--------|--------|
| Test Coverage | 80%+ |
| Code Quality | 9/10 |
| Performance (p95) | < 200ms |
| Security Score | 9/10 |
| Overall System Health | 9+/10 |

---

## Final Deployment Checklist

### Pre-Deployment

- [ ] All code merged to `main` branch
- [ ] All tests passing (unit + feature + integration)
- [ ] Database backup created
- [ ] Environment variables configured
- [ ] SSL certificate installed (production)
- [ ] Monitoring configured (Sentry, New Relic, etc.)
- [ ] Error tracking enabled
- [ ] Performance monitoring enabled
- [ ] Rollback plan documented
- [ ] Team notified of deployment window

### Deployment Steps

1. [ ] Put site in maintenance mode
2. [ ] Pull latest code from repository
3. [ ] Run database migrations
4. [ ] Run database seeders (role/permission)
5. [ ] Clear all caches (`php artisan cache:clear`, etc.)
6. [ ] Restart queue workers
7. [ ] Run smoke tests
8. [ ] Take site out of maintenance mode
9. [ ] Monitor error rates for 30 minutes
10. [ ] Notify team deployment complete

### Post-Deployment

- [ ] Verify all critical user flows
- [ ] Check error logs (no new errors)
- [ ] Check performance metrics (response times)
- [ ] Verify email delivery
- [ ] Test authentication (login/logout)
- [ ] Test authorization (permission checks)
- [ ] Test vendor flow (registration, approval, dashboard)
- [ ] Test customer flow (cart, checkout, orders)
- [ ] Monitor for 48 hours
- [ ] Create post-deployment report

### Rollback Procedure (If Needed)

```bash
# 1. Put site in maintenance mode
php artisan down

# 2. Revert code to previous version
git revert <commit-hash>
git push origin main

# 3. Restore database backup
mysql -u root -p nursery_app < backup_before_deployment.sql

# 4. Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# 5. Take site out of maintenance
php artisan up

# 6. Notify team of rollback
```

---

## Conclusion

This roadmap provides a **complete, step-by-step path** from the current state (6.2/10 - AT RISK) to the target state (9+/10 - PRODUCTION READY).

### Key Takeaways

1. **Phase 0 is BLOCKING** - Security fixes must be deployed first
2. **Phase 1 is ESSENTIAL** - RBAC normalization prevents future bugs
3. **Phase 2 is USER-FACING** - Completes broken features
4. **Phase 3 is PROFESSIONAL** - Makes project maintainable

### Total Effort

- **Duration:** 12-15 working days (3 weeks)
- **Resources:** 1-2 devs + 1 QA + 1 DevOps
- **Cost:** ~$15,000-$20,000 (assuming $100/hour average rate)
- **ROI:** Eliminates security risks, completes features, enables scaling

### When Complete

The project will be:
- ✅ **Secure** - No known vulnerabilities
- ✅ **Complete** - All features working
- ✅ **Tested** - 80%+ test coverage
- ✅ **Documented** - OpenAPI spec + guides
- ✅ **Maintainable** - Clean, standardized code
- ✅ **Scalable** - Optimized performance

### Next Steps

1. **Review this roadmap** with technical team
2. **Get stakeholder buy-in** on timeline and resources
3. **Create hotfix branch** for Phase 0
4. **Begin execution** immediately

---

**Status:** READY FOR EXECUTION
**Last Updated:** December 27, 2025
**Document Owner:** Technical Lead
**Review Cycle:** After each phase completion

---

*This roadmap is a living document. Update after each phase completion to reflect actual progress, blockers, and learnings.*
