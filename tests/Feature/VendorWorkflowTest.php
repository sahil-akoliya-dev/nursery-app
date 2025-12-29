<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

class VendorWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles and permissions
        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);
    }

    /** @test */
    public function customer_can_register_as_vendor()
    {
        $user = User::factory()->create();
        $user->assignRole('customer');

        $response = $this->actingAs($user)->postJson('/api/vendor/register', [
            'store_name' => 'Test Store',
            'description' => 'Test store description',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('vendors', [
            'user_id' => $user->id,
            'store_name' => 'Test Store',
            'status' => 'pending',
        ]);

        // User should NOT have vendor role yet
        $this->assertFalse($user->fresh()->hasRole('vendor'));
    }

    /** @test */
    public function pending_vendor_cannot_access_vendor_routes()
    {
        $user = User::factory()->create();
        $user->assignRole('customer');

        Vendor::create([
            'user_id' => $user->id,
            'store_name' => 'Test Store',
            'store_slug' => 'test-store',
            'description' => 'Test',
            'status' => 'pending',
            'commission_rate' => 15.0
        ]);

        $response = $this->actingAs($user)->getJson('/api/vendor/products');

        $response->assertStatus(403); // Forbidden
    }

    /** @test */
    public function admin_can_approve_vendor()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $customer = User::factory()->create();
        $customer->assignRole('customer');

        $vendor = Vendor::create([
            'user_id' => $customer->id,
            'store_name' => 'Test Store',
            'store_slug' => 'test-store',
            'description' => 'Test',
            'status' => 'pending',
            'commission_rate' => 15.0
        ]);

        $response = $this->actingAs($admin)->putJson("/api/admin/users/vendors/{$vendor->id}/approve");

        $response->assertStatus(200);
        $this->assertDatabaseHas('vendors', [
            'id' => $vendor->id,
            'status' => 'approved',
        ]);

        // User should now have vendor role
        $this->assertTrue($customer->fresh()->hasRole('vendor'));
    }

    /** @test */
    public function approved_vendor_can_access_vendor_routes()
    {
        $user = User::factory()->create();
        // Manually assign role and approved status
        $user->assignRole('vendor');

        $vendor = Vendor::create([
            'user_id' => $user->id,
            'store_name' => 'Test Store',
            'store_slug' => 'test-store',
            'description' => 'Test',
            'status' => 'approved',
            'commission_rate' => 15.0
        ]);

        $response = $this->actingAs($user)->getJson('/api/vendor/products');

        // Assuming endpoint exists and returns 200 or at least passed middleware
        // If products check DB, it might be 200 with empty list
        $response->assertStatus(200);
    }

    /** @test */
    public function customer_cannot_access_vendor_routes()
    {
        $customer = User::factory()->create();
        $customer->assignRole('customer');

        $response = $this->actingAs($customer)->getJson('/api/vendor/products');

        $response->assertStatus(404);
    }
}
