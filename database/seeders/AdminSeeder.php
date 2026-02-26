<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table( "users" )->insert([
            "name" => "super",
            "email" => "super@vmi.lan",
            "password" => bcrypt( "Aa123?" ),
            "role" => "superadmin"
        ]);

        DB::table( "users" )->insert([
            "name" => "admin",
            "email" => "admin@vmi.lan",
            "password" => bcrypt( "Aa123?" ),
            "role" => "admin"
        ]);

        DB::table( "users" )->insert([
            "name" => "user",
            "email" => "user@vmi.lan",
            "password" => bcrypt( "Aa123?" ),
            "role" => "user"
        ]);
    }
}
