<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            'user_name'     => 'Admin',
            'user_email'    => 'admin@gmail.com',
            'user_password' => '$2y$12$0LBxujsR1.tkhnm5.lwKfeMRSzEtpaJcbdwvRq.H6XW9/uaS3ki7u', // hash sẵn
        ]);
    }
}
