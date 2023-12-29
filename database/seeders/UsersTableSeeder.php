<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $password = Hash::make('admin');

        User::firstOrCreate([
            'id' => 1,
            'uuid' =>   Str::uuid(),
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => $password,
            'role' => 'superadmin'
        ]);

    }
}
