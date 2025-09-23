<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@deha'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('Admin@12345'),
                'role' => 'super_admin',
            ]
        );
    }
}
