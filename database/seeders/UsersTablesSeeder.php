<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTablesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'),
            'remember_token' => Str::random(10),
            'paypal' => 'admin@gmail.com',
            'subscription' => 'CONTINUED',
            'role' => 'ADM',
            'status' => 'ACTIVE'
        ]);
    }
}
