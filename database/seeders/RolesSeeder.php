<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name'             => 'Journal Manager',
                'slug'             => 'journal-manager',
                'permission_level' => 'manager',
                'can_submission'   => false,
                'can_review'       => false,
                'can_copyediting'  => false,
                'can_production'   => false,
                'description'      => 'Manajer jurnal/prosiding dengan akses penuh ke manajemen',
                'sort_order'       => 1,
            ],
            [
                'name'             => 'Journal Editor',
                'slug'             => 'journal-editor',
                'permission_level' => 'manager',
                'can_submission'   => true,
                'can_review'       => true,
                'can_copyediting'  => true,
                'can_production'   => true,
                'description'      => 'Editor jurnal dengan akses ke semua tahap workflow',
                'sort_order'       => 2,
            ],
            [
                'name'             => 'Production Editor',
                'slug'             => 'production-editor',
                'permission_level' => 'manager',
                'can_submission'   => false,
                'can_review'       => false,
                'can_copyediting'  => true,
                'can_production'   => true,
                'description'      => 'Editor produksi, fokus pada copyediting dan produksi',
                'sort_order'       => 3,
            ],
            [
                'name'             => 'Section Editor',
                'slug'             => 'section-editor',
                'permission_level' => 'editor',
                'can_submission'   => true,
                'can_review'       => true,
                'can_copyediting'  => true,
                'can_production'   => true,
                'description'      => 'Editor seksi dengan akses penuh ke workflow paper',
                'sort_order'       => 4,
            ],
            [
                'name'             => 'Guest Editor',
                'slug'             => 'guest-editor',
                'permission_level' => 'editor',
                'can_submission'   => true,
                'can_review'       => true,
                'can_copyediting'  => true,
                'can_production'   => true,
                'description'      => 'Editor tamu dengan akses penuh ke workflow',
                'sort_order'       => 5,
            ],
            [
                'name'             => 'Copyeditor',
                'slug'             => 'copyeditor',
                'permission_level' => 'assistant',
                'can_submission'   => false,
                'can_review'       => false,
                'can_copyediting'  => true,
                'can_production'   => false,
                'description'      => 'Copyeditor untuk perbaikan naskah',
                'sort_order'       => 6,
            ],
            [
                'name'             => 'Layout Editor',
                'slug'             => 'layout-editor',
                'permission_level' => 'assistant',
                'can_submission'   => false,
                'can_review'       => false,
                'can_copyediting'  => false,
                'can_production'   => true,
                'description'      => 'Layout editor untuk tata letak publikasi',
                'sort_order'       => 7,
            ],
            [
                'name'             => 'Reviewer',
                'slug'             => 'reviewer',
                'permission_level' => 'reviewer',
                'can_submission'   => false,
                'can_review'       => true,
                'can_copyediting'  => false,
                'can_production'   => false,
                'description'      => 'Reviewer untuk mereview paper yang ditugaskan',
                'sort_order'       => 8,
            ],
            [
                'name'             => 'Author',
                'slug'             => 'author',
                'permission_level' => 'author',
                'can_submission'   => true,
                'can_review'       => false,
                'can_copyediting'  => false,
                'can_production'   => false,
                'description'      => 'Penulis yang mengirimkan paper',
                'sort_order'       => 9,
            ],
        ];

        foreach ($roles as $role) {
            Role::updateOrCreate(
                ['slug' => $role['slug']],
                $role
            );
        }
    }
}
