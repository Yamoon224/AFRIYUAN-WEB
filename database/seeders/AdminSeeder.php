<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('admins')->updateOrInsert(
            ['email' => 'superadmin@afriyuan.com'],
            [
                'uuid'       => (string) Str::uuid(),
                'first_name' => 'Super',
                'last_name'  => 'Admin',
                'email'      => 'superadmin@afriyuan.com',
                'password'   => Hash::make('AfriYuan@2026!'),
                'role'       => 'super_admin',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('admins')->updateOrInsert(
            ['email' => 'compliance@afriyuan.com'],
            [
                'uuid'       => (string) Str::uuid(),
                'first_name' => 'Compliance',
                'last_name'  => 'Officer',
                'email'      => 'compliance@afriyuan.com',
                'password'   => Hash::make('AfriYuan@2026!'),
                'role'       => 'compliance_officer',
                'is_active'  => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
