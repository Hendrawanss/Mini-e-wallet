<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'username' => 'ynMlL',
                'email' => 'ynMlL@gmail.com',
                'role' => 'user',
                'password' => Hash::make('password'),
                'key' => Hash::make(Str::random(10))
            ],
            [
                'username' => '8hh1p',
                'email' => '8hh1p@gmail.com',
                'role' => 'user',
                'password' => Hash::make('password'),
                'key' => Hash::make(Str::random(10))
            ],
            [
                'username' => 'xvnuj',
                'email' => 'xvnuj@gmail.com',
                'role' => 'user',
                'password' => Hash::make('password'),
                'key' => Hash::make(Str::random(10))
            ],
            [
                'username' => 'admin',
                'email' => 'admin@gmail.com',
                'role' => 'admin',
                'password' => Hash::make('password'),
                'key' => Hash::make(Str::random(10))
            ],
        ]);
    }
}
