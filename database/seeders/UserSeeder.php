<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a default super admin
        User::create([
            'NRP_admin' => 1001,
            'username' => 'admin',
            'nama_admin' => 'Administrator',
            'email' => 'admin@pelni.co.id',
            'password' => Hash::make('admin123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Create additional test users
        User::create([
            'NRP_admin' => 1002,
            'username' => 'operator',
            'nama_admin' => 'Operator Pengawakan',
            'email' => 'operator@pelni.co.id',
            'password' => Hash::make('operator123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        User::create([
            'NRP_admin' => 1003,
            'username' => 'supervisor',
            'nama_admin' => 'Supervisor SDM',
            'email' => 'supervisor@pelni.co.id',
            'password' => Hash::make('supervisor123'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}