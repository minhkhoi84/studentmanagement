<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Gán tất cả permissions cho user đầu tiên
        $user = \App\Models\User::first();
        if ($user) {
            $user->permissions()->sync(\App\Models\Permission::pluck('id'));
            $this->command->info('User permissions updated: ' . $user->permissions()->count());
        }
    }
}
