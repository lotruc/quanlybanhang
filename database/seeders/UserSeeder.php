<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => 1,
            'role' => 0,
            'name' => 'Administrator',
            'email' => 'lotruc2k2@gmail.com',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
            'created_at' => Carbon::now()
        ]);

        for ($i = 1; $i <= 5; $i++) {
            $id = $i + 1;
            DB::table('users')->insert([
                'id' => $id,
                'role' => 1,
                'name' => "User_" . $i,
                'email' => 'user' . $i . '@test.com',
                'password' => Hash::make('12345678'),
                'email_verified_at' => now(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
