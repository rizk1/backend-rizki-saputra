<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'id' => 1,
            'name' => 'Merchant One',
            'email' => 'merchant@example.com',
            'password' => Hash::make('password'),
            'role' => 'merchant',
        ]);

        User::create([
            'id' => 2,
            'name' => 'Customer One',
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
        ]);

        User::create([
            'id' => 3,
            'name' => 'Merchant Two',
            'email' => 'merchant2@example.com',
            'password' => Hash::make('password'),
            'role' => 'merchant',
        ]);
    }
}
