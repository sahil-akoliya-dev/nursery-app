<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // Product permissions
            'products.view',
            'products.create',
            'products.update',
            'products.delete',
            'products.manage', // Manage permission (includes all product operations)

            // Plant permissions
            'plants.view',
            'plants.create',
            'plants.update',
            'plants.delete',

            // Category permissions
            'categories.view',
            'categories.create',
            'categories.update',
            'categories.delete',

            // Order permissions
            'orders.view',
            'orders.update',
            'orders.delete',
            'orders.cancel',

            // User permissions
            'users.view',
            'users.create',
            'users.update',
            'users.delete',
            'users.manage', // Manage permission (includes all user operations)

            // Review permissions
            'reviews.view',
            'reviews.approve',
            'reviews.delete',
            'reviews.manage', // Manage permission (includes all review operations)

            // Analytics permissions
            'analytics.view',
            'analytics.export',

            // Audit permissions
            'audit.view', // View audit logs

            // System permissions
            'system.settings',
            'system.backup',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create roles and assign permissions

        // Super Admin - All permissions
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdmin->givePermissionTo(Permission::all());

        // Admin - Most permissions except system settings
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->givePermissionTo([
            'products.view',
            'products.create',
            'products.update',
            'products.delete',
            'products.manage', // Add products.manage permission
            'plants.view',
            'plants.create',
            'plants.update',
            'plants.delete',
            'categories.view',
            'categories.create',
            'categories.update',
            'categories.delete',
            'orders.view',
            'orders.update',
            'orders.cancel',
            'users.view',
            'users.update',
            'users.manage', // Add users.manage permission
            'reviews.view',
            'reviews.approve',
            'reviews.delete',
            'reviews.manage', // Add reviews.manage permission
            'analytics.view',
            'analytics.export',
        ]);

        // Manager - Limited admin permissions
        $manager = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $manager->givePermissionTo([
            'products.view',
            'products.update',
            'plants.view',
            'plants.update',
            'categories.view',
            'categories.update',
            'orders.view',
            'orders.update',
            'orders.cancel',
            'reviews.view',
            'reviews.approve',
            'analytics.view',
        ]);

        // Customer - Basic permissions
        $customer = Role::firstOrCreate(['name' => 'customer', 'guard_name' => 'web']);
        $customer->givePermissionTo([
            'products.view',
            'plants.view',
            'categories.view',
            'orders.view',
            'reviews.view',
        ]);

        // Vendor - Manage own products and orders
        $vendor = Role::firstOrCreate(['name' => 'vendor', 'guard_name' => 'web']);
        $vendor->givePermissionTo([
            'products.create',
            'products.view',
            'products.update',
            'products.delete',
            'orders.view',
            'orders.update', // To mark as shipped
            'analytics.view',
        ]);

        // Create specific vendor permissions if not already in the list
        $vendorPermissions = [
            'vendor.access',
            'vendor.profile.update',
        ];

        foreach ($vendorPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
            $vendor->givePermissionTo($permission);
        }
    }
}

