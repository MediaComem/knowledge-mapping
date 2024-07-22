<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => config('app.user'),
            'email' => config('app.email'),
            'is_admin' => true,
            'password' => bcrypt(config('app.password')),
        ]);
    }
}
