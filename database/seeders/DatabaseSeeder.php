<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'first_name' => 'Admin',
            'second_name' => 'Admin',
            'email' => 'admin@localhost',
            'password' => bcrypt('password'),
            'role' => 'SU',
            'department' => 'All',
        ]);
    }
}
