<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::firstOrCreate(
            ['email' => 'admin@prosiding.test'],
            [
                'name' => 'Admin Prosiding',
                'password' => bcrypt('password'),
                'role' => 'admin',
                'institution' => 'LPKD-APJI',
                'phone' => '081234567890',
            ]
        );

        // Create editor user
        $editor = User::firstOrCreate(
            ['email' => 'editor@prosiding.test'],
            [
                'name' => 'Editor Prosiding',
                'password' => bcrypt('password'),
                'role' => 'editor',
                'institution' => 'LPKD-APJI',
                'phone' => '081234567891',
            ]
        );

        // Create reviewer user
        $reviewer = User::firstOrCreate(
            ['email' => 'reviewer@prosiding.test'],
            [
                'name' => 'Reviewer Prosiding',
                'password' => bcrypt('password'),
                'role' => 'reviewer',
                'institution' => 'Universitas Testing',
                'phone' => '081234567892',
            ]
        );

        $this->command->info("\n✅ Admin users created successfully!");
        $this->command->info("\n👤 Admin Login:");
        $this->command->info("   Email: admin@prosiding.test");
        $this->command->info("   Password: password");
        $this->command->info("\n👤 Editor Login:");
        $this->command->info("   Email: editor@prosiding.test");
        $this->command->info("   Password: password");
        $this->command->info("\n👤 Reviewer Login:");
        $this->command->info("   Email: reviewer@prosiding.test");
        $this->command->info("   Password: password");
    }
}
