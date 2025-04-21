<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'ikrex',
            'email' => 'illeskalman77@gmail.com',
            'password' => Hash::make('faszom'),
            'user_group' => 'admin'
        ]);
    }
}
