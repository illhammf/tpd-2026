<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Super Admin',
                'nomor_whatsapp' => '0895336900466',
                'role' => 'admin',
                'password' => Hash::make('password'),
            ]
        );
        $user->assignRole('super_admin');

        $user = User::firstOrCreate(
            ['email' => 'user@admin.com'],
            [
                'name' => 'User Account',
                'nomor_whatsapp' => '0895000000000',
                'role' => 'user',
                'password' => Hash::make('password'),
            ]
        );
        $user->assignRole('user');
    }
}
