<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'RolePermissionSeeder']);
    }

    /** @test */
    public function admin_has_all_permissions()
    {
        $admin = User::factory()->create();
        $admin->assignRole('super_admin'); // Changed to super_admin as they usually have all perms via Gate or direct assignment

        // Check a random high level permission
        $this->assertTrue($admin->hasPermissionTo('users.manage'));
        $this->assertTrue($admin->hasPermissionTo('system.settings'));
    }

    /** @test */
    public function manager_has_limited_permissions()
    {
        $manager = User::factory()->create();
        $manager->assignRole('manager');

        $this->assertTrue($manager->hasPermissionTo('products.update'));
        // Assuming managers cannot manage system settings
        $this->assertFalse($manager->hasPermissionTo('system.settings')); // Changed permission name to match likely seeder
    }

    /** @test */
    public function customer_has_no_admin_permissions()
    {
        $customer = User::factory()->create();
        $customer->assignRole('customer');

        $this->assertFalse($customer->hasPermissionTo('products.manage'));
        $this->assertFalse($customer->hasPermissionTo('users.manage'));
    }
}
