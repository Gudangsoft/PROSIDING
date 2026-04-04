<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

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

        // Create treasurer (bendahara) user
        $treasurer = User::firstOrCreate(
            ['email' => 'bendahara@prosiding.test'],
            [
                'name' => 'Bendahara Prosiding',
                'password' => bcrypt('password'),
                'role' => 'treasurer',
                'institution' => 'LPKD-APJI',
                'phone' => '081234567893',
            ]
        );

        // Assign roles to users
        $this->assignRoles($reviewer, $editor, $treasurer);

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
        $this->command->info("\n👤 Bendahara Login:");
        $this->command->info("   Email: bendahara@prosiding.test");
        $this->command->info("   Password: password");
    }

    /**
     * Assign appropriate roles to users
     */
    private function assignRoles($reviewer, $editor, $treasurer): void
    {
        // Assign Reviewer role
        $reviewerRole = Role::where('slug', 'reviewer')->first();
        if ($reviewerRole && !$reviewer->roles()->where('role_id', $reviewerRole->id)->exists()) {
            $reviewer->roles()->attach($reviewerRole->id);
        }

        // Assign Journal Editor role to editor
        $editorRole = Role::where('slug', 'journal-editor')->first();
        if ($editorRole && !$editor->roles()->where('role_id', $editorRole->id)->exists()) {
            $editor->roles()->attach($editorRole->id);
        }

        // Assign Treasurer role to bendahara
        $treasurerRole = Role::where('slug', 'treasurer')->first();
        if ($treasurerRole && !$treasurer->roles()->where('role_id', $treasurerRole->id)->exists()) {
            $treasurer->roles()->attach($treasurerRole->id);
        }
    }
}
