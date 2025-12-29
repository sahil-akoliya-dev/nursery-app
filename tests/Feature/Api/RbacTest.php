<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class RbacTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed roles and permissions
        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);
    }

    /**
     * Test that customers can only access their allowed endpoints
     */
    public function test_customer_can_access_their_allowed_endpoints(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $customer->assignRole('customer');
        
        $token = $customer->createToken('api-token')->plainTextToken;

        // Customer can view products
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/auth/permissions');
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'roles',
                'permissions',
                'role',
            ]);

        $data = $response->json();
        $this->assertContains('customer', $data['roles']);
        $this->assertContains('products.view', $data['permissions']);
        $this->assertContains('plants.view', $data['permissions']);
    }

    /**
     * Test that admin can access admin endpoints
     */
    public function test_admin_can_access_admin_endpoints(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $admin->assignRole('admin');
        
        $token = $admin->createToken('api-token')->plainTextToken;

        // Admin can view roles
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/roles');
        
        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'roles',
            ]);
    }

    /**
     * Test that customer cannot access admin endpoints
     */
    public function test_customer_cannot_access_admin_endpoints(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $customer->assignRole('customer');
        
        $token = $customer->createToken('api-token')->plainTextToken;

        // Customer cannot view roles
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/roles');
        
        $response->assertStatus(403)
            ->assertJson([
                'success' => false,
                'error' => [
                    'code' => 'FORBIDDEN',
                ]
            ]);
    }

    /**
     * Test role assignment
     */
    public function test_admin_can_assign_role_to_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $admin->assignRole('admin');
        
        $customer = User::factory()->create(['role' => 'customer']);
        $customer->assignRole('customer');

        $token = $admin->createToken('api-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson("/api/roles/{$customer->id}/assign", [
                'role' => 'manager',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $customer->refresh();
        $this->assertTrue($customer->hasRole('manager'));
    }

    /**
     * Test permission checking
     */
    public function test_user_with_permission_can_access_protected_endpoint(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $admin->assignRole('admin');
        
        $token = $admin->createToken('api-token')->plainTextToken;

        // Admin should have products.view permission
        $this->assertTrue($admin->can('products.view'));
        $this->assertTrue($admin->can('products.create'));
    }

    /**
     * Test user without permission cannot access protected endpoint
     */
    public function test_user_without_permission_cannot_access_protected_endpoint(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $customer->assignRole('customer');
        
        // Customer should not have products.create permission
        $this->assertFalse($customer->can('products.create'));
        $this->assertFalse($customer->can('products.delete'));
        
        // But should have products.view
        $this->assertTrue($customer->can('products.view'));
    }

    /**
     * Test my permissions endpoint
     */
    public function test_user_can_get_their_permissions(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $admin->assignRole('admin');
        
        $token = $admin->createToken('api-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/auth/permissions');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'roles',
                'permissions',
                'role',
            ])
            ->assertJson([
                'success' => true,
                'role' => 'admin',
            ]);

        $data = $response->json();
        $this->assertContains('admin', $data['roles']);
        $this->assertIsArray($data['permissions']);
        $this->assertGreaterThan(0, count($data['permissions']));
    }

    /**
     * Test super admin has all permissions
     */
    public function test_super_admin_has_all_permissions(): void
    {
        $superAdmin = User::factory()->create(['role' => 'super_admin']);
        $superAdmin->assignRole('super_admin');

        // Super admin should have all permissions
        $allPermissions = Permission::all()->pluck('name');
        
        foreach ($allPermissions as $permission) {
            $this->assertTrue(
                $superAdmin->can($permission),
                "Super admin should have permission: {$permission}"
            );
        }
    }

    /**
     * Test role removal
     */
    public function test_admin_can_remove_role_from_user(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $admin->assignRole('admin');
        
        $manager = User::factory()->create(['role' => 'manager']);
        $manager->assignRole('manager');

        $token = $admin->createToken('api-token')->plainTextToken;

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson("/api/roles/{$manager->id}/remove", [
                'role' => 'manager',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $manager->refresh();
        $this->assertFalse($manager->hasRole('manager'));
    }

    /**
     * Test unauthorized access to role management
     */
    public function test_non_admin_cannot_manage_roles(): void
    {
        $customer = User::factory()->create(['role' => 'customer']);
        $customer->assignRole('customer');
        
        $token = $customer->createToken('api-token')->plainTextToken;

        $targetUser = User::factory()->create();

        // Try to assign role
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson("/api/roles/{$targetUser->id}/assign", [
                'role' => 'admin',
            ]);

        $response->assertStatus(403);
    }
}

