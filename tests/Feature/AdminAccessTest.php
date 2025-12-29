<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);
    }

    /** @test */
    public function unauthorized_users_cannot_access_admin_dashboard_stats()
    {
        $user = User::factory()->create();
        $user->assignRole('customer');

        $response = $this->actingAs($user)->getJson('/api/admin/analytics/dashboard');

        $response->assertStatus(403);
    }

    /** @test */
    public function admin_can_access_admin_dashboard_stats()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->getJson('/api/admin/analytics/dashboard');

        // Depending on implementation, might return 200 or 500 if DB empty? 
        // Assuming 200 because controller usually handles empty data.
        $response->assertStatus(200);
    }

    /** @test */
    public function manager_can_access_orders()
    {
        $manager = User::factory()->create();
        $manager->assignRole('manager');

        $response = $this->actingAs($manager)->getJson('/api/admin/orders');

        $response->assertStatus(200);
    }
}
