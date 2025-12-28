<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ImprovedAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * IMPORTANT: This seeder creates admin accounts with documented passwords
     * For PRODUCTION: Change all passwords immediately after deployment
     */
    public function run(): void
    {
        // ============================================
        // SUPER ADMIN ACCOUNT
        // ============================================
        $superAdmin = User::create([
            'name' => 'Dr. Emily Thompson',
            'email' => 'emily.thompson@nurseryapp.com',
            'password' => Hash::make('SuperAdmin2025!'), // CHANGE IN PRODUCTION!
            'role' => 'super_admin',
            'phone' => '+1-555-0100',
        ]);
        $superAdmin->assignRole('super_admin');

        // ============================================
        // ADMIN USERS
        // ============================================
        $admin1 = User::create([
            'name' => 'Michael Chen',
            'email' => 'michael.chen@nurseryapp.com',
            'password' => Hash::make('Admin2025!'), // CHANGE IN PRODUCTION!
            'role' => 'admin',
            'phone' => '+1-555-0101',
        ]);
        $admin1->assignRole('admin');

        $admin2 = User::create([
            'name' => 'Sarah Rodriguez',
            'email' => 'sarah.rodriguez@nurseryapp.com',
            'password' => Hash::make('Admin2025!'), // CHANGE IN PRODUCTION!
            'role' => 'admin',
            'phone' => '+1-555-0102',
        ]);
        $admin2->assignRole('admin');

        // ============================================
        // MANAGER USERS
        // ============================================
        $manager1 = User::create([
            'name' => 'David Kumar',
            'email' => 'david.kumar@nurseryapp.com',
            'password' => Hash::make('Manager2025!'), // CHANGE IN PRODUCTION!
            'role' => 'manager',
            'phone' => '+1-555-0201',
        ]);
        $manager1->assignRole('manager');

        $manager2 = User::create([
            'name' => 'Jessica Martinez',
            'email' => 'jessica.martinez@nurseryapp.com',
            'password' => Hash::make('Manager2025!'), // CHANGE IN PRODUCTION!
            'role' => 'manager',
            'phone' => '+1-555-0202',
        ]);
        $manager2->assignRole('manager');

        // ============================================
        // DEMO/TEST CUSTOMER ACCOUNTS
        // ============================================

        // Demo Customer 1 - Active buyer
        $customer1 = User::create([
            'name' => 'Jennifer Williams',
            'email' => 'jennifer.williams@example.com',
            'password' => Hash::make('Customer123!'), // Test account
            'role' => 'customer',
            'phone' => '+1-555-1001',
        ]);
        $customer1->assignRole('customer');

        // Demo Customer 2 - New customer
        $customer2 = User::create([
            'name' => 'Robert Johnson',
            'email' => 'robert.johnson@example.com',
            'password' => Hash::make('Customer123!'), // Test account
            'role' => 'customer',
            'phone' => '+1-555-1002',
        ]);
        $customer2->assignRole('customer');

        // Demo Customer 3 - VIP customer
        $customer3 = User::create([
            'name' => 'Amanda Peterson',
            'email' => 'amanda.peterson@example.com',
            'password' => Hash::make('Customer123!'), // Test account
            'role' => 'customer',
            'phone' => '+1-555-1003',
            'current_loyalty_points' => 1500,
            'lifetime_loyalty_points' => 3000,
        ]);
        $customer3->assignRole('customer');

        // ============================================
        // REALISTIC CUSTOMER DATA (25 customers)
        // ============================================
        $customerNames = [
            ['name' => 'Lisa Anderson', 'email' => 'lisa.anderson@gmail.com'],
            ['name' => 'James Mitchell', 'email' => 'james.mitchell@yahoo.com'],
            ['name' => 'Maria Garcia', 'email' => 'maria.garcia@outlook.com'],
            ['name' => 'Christopher Lee', 'email' => 'christopher.lee@gmail.com'],
            ['name' => 'Patricia Taylor', 'email' => 'patricia.taylor@hotmail.com'],
            ['name' => 'Daniel White', 'email' => 'daniel.white@proton.me'],
            ['name' => 'Nancy Hall', 'email' => 'nancy.hall@gmail.com'],
            ['name' => 'Ryan Moore', 'email' => 'ryan.moore@icloud.com'],
            ['name' => 'Elizabeth Clark', 'email' => 'elizabeth.clark@yahoo.com'],
            ['name' => 'Kevin Lewis', 'email' => 'kevin.lewis@gmail.com'],
            ['name' => 'Karen Robinson', 'email' => 'karen.robinson@outlook.com'],
            ['name' => 'Steven Walker', 'email' => 'steven.walker@gmail.com'],
            ['name' => 'Betty Young', 'email' => 'betty.young@yahoo.com'],
            ['name' => 'Brian King', 'email' => 'brian.king@proton.me'],
            ['name' => 'Sandra Wright', 'email' => 'sandra.wright@gmail.com'],
            ['name' => 'George Lopez', 'email' => 'george.lopez@icloud.com'],
            ['name' => 'Helen Scott', 'email' => 'helen.scott@gmail.com'],
            ['name' => 'Edward Green', 'email' => 'edward.green@outlook.com'],
            ['name' => 'Carol Adams', 'email' => 'carol.adams@yahoo.com'],
            ['name' => 'Timothy Baker', 'email' => 'timothy.baker@gmail.com'],
            ['name' => 'Rebecca Nelson', 'email' => 'rebecca.nelson@proton.me'],
            ['name' => 'Jason Carter', 'email' => 'jason.carter@gmail.com'],
            ['name' => 'Deborah Mitchell', 'email' => 'deborah.mitchell@icloud.com'],
            ['name' => 'Matthew Perez', 'email' => 'matthew.perez@gmail.com'],
            ['name' => 'Laura Roberts', 'email' => 'laura.roberts@outlook.com'],
        ];

        foreach ($customerNames as $index => $customerData) {
            $customer = User::create([
                'name' => $customerData['name'],
                'email' => $customerData['email'],
                'password' => Hash::make('DemoPass123!'),
                'role' => 'customer',
                'phone' => '+1-555-' . str_pad(2000 + $index, 4, '0', STR_PAD_LEFT),
                'current_loyalty_points' => rand(0, 800),
                'lifetime_loyalty_points' => rand(100, 2500),
            ]);
            $customer->assignRole('customer');
        }

        // ============================================
        // OUTPUT CREDENTIALS FOR REFERENCE
        // ============================================
        $this->command->info('==============================================');
        $this->command->info('ADMIN CREDENTIALS (CHANGE IN PRODUCTION!)');
        $this->command->info('==============================================');
        $this->command->info('Super Admin:');
        $this->command->info('  Email: emily.thompson@nurseryapp.com');
        $this->command->info('  Password: SuperAdmin2025!');
        $this->command->info('');
        $this->command->info('Admin:');
        $this->command->info('  Email: michael.chen@nurseryapp.com');
        $this->command->info('  Password: Admin2025!');
        $this->command->info('');
        $this->command->info('Manager:');
        $this->command->info('  Email: david.kumar@nurseryapp.com');
        $this->command->info('  Password: Manager2025!');
        $this->command->info('');
        $this->command->info('Test Customer:');
        $this->command->info('  Email: jennifer.williams@example.com');
        $this->command->info('  Password: Customer123!');
        $this->command->info('==============================================');
    }
}
