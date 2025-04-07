<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Member',
                'email' => 'member@email.com',
                'role' => 'member',
                'password' => bcrypt('senha123'),
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@email.com',
                'role' => 'admin',
                'password' => bcrypt('senha123'),
            ]
        ];

        foreach ($users as $user) {
            User::create($user);
        }
    }
}
