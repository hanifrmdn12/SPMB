<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'admin@spmb.test'],
            [
                'name' => 'Admin SPMB',
                'password' => Hash::make('Admin12345!'),
                'role' => 'admin',
            ],
        );

        Admin::updateOrCreate(
            ['user_id' => $user->id],
            ['nama' => 'Admin SPMB'],
        );
    }
}
